<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use App\Models\CoursesContentItem;
use App\Models\Video;
use App\Services\GoogleDriveOauthService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class VideoController extends Controller
{
    protected GoogleDriveOauthService $googleDriveService;

    public function __construct(GoogleDriveOauthService $googleDriveService)
    {
        $this->googleDriveService = $googleDriveService;
    }

    /**
     * Start Google Drive authentication process
     */
    public function authenticate(Request $request)
    {
        // Save return URL in session for redirection after authentication
        if ($request->has('return_url')) {
            Session::put('google_auth_return_url', $request->input('return_url'));
        }

        // Save course ID and content item ID if provided
        if ($request->has('courses_id')) {
            Session::put('google_auth_courses_id', $request->input('courses_id'));
        }

        if ($request->has('content_item_id')) {
            Session::put('google_auth_content_item_id', $request->input('content_item_id'));
        }

        return redirect($this->googleDriveService->getAuthUrl());
    }

    /**
     * Handle Google OAuth callback
     */
    public function callback(Request $request)
    {
        $code = $request->input('code');

        if (!$code) {
            return redirect()->route('courses.management.courses.index')
                ->with('error', 'Authorization code not found.');
        }

        try {
            $this->googleDriveService->authenticate($code);

            // If content_item_id and courses_id exist in session, redirect to resumeUpload
            if (Session::has('google_auth_courses_id') && Session::has('google_auth_content_item_id')) {
                $coursesId = Session::get('google_auth_courses_id');
                $contentItemId = Session::get('google_auth_content_item_id');

                return redirect()->route('courses.management.video.resume-upload', [
                    'courses_id' => $coursesId,
                    'content_item_id' => $contentItemId
                ]);
            }

            if (Session::has('google_auth_return_url')) {
                $returnUrl = Session::pull('google_auth_return_url');
                return redirect($returnUrl)->with('success', 'Google Drive authentication successful!');
            }

            return redirect()->route('courses.management.courses.index')
                ->with('success', 'Google Drive authentication successful!');
        } catch (Exception $e) {
            return redirect()->route('courses.management.courses.index')
                ->with('error', 'Failed to authenticate with Google Drive: ' . $e->getMessage());
        }
    }

    /**
     * Show add video form
     */
    public function addForm($courses_id, $content_item_id): \Inertia\Response
    {
        // Check if Google Drive is authenticated
        $isAuthenticated = $this->googleDriveService->isAuthenticated();

        return Inertia::render('Courses/Videos/Add', [
            'courses_id' => $courses_id,
            'content_item_id' => $content_item_id,
            'isGoogleAuthenticated' => $isAuthenticated
        ]);
    }

    /**
     * Handle video upload and creation
     */
    public function addVideo(Request $request, $courses_id, $content_item_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|boolean',
            'file' => 'required|file|mimes:mp4,mov,avi,wmv|max:102400',
        ]);

        // Check Google Drive authentication
        if (!$this->googleDriveService->isAuthenticated()) {
            // Save form data in session
            $uploadData = $request->except('file');

            // Save temporary file information
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $tempPath = $file->getPathname();
                $originalName = $file->getClientOriginalName();
                $mimeType = $file->getMimeType();
                $size = $file->getSize();

                // Save file information
                $uploadData['file'] = [
                    'temp_path' => $tempPath,
                    'original_name' => $originalName,
                    'mime_type' => $mimeType,
                    'size' => $size
                ];
            }

            Session::put('video_upload_data', $uploadData);

            // Redirect to authentication
            return redirect()->route('google-drive.authenticate', [
                'return_url' => route('courses.management.video.resume-upload', [
                    'courses_id' => $courses_id,
                    'content_item_id' => $content_item_id
                ]),
                'courses_id' => $courses_id,
                'content_item_id' => $content_item_id
            ]);
        }

        try {
            // Upload video process
            $filePath = $request->file('file')->getPathname();
            $fileName = $request->input('name') . '.' . $request->file('file')->getClientOriginalExtension();

            // Get storage folder ID from environment variable
            $folderId = $this->googleDriveService->getStorageFolderId();

            // Upload file
            $uploadedFile = $this->googleDriveService->uploadFile($filePath, $fileName, $folderId);

            // Create video record in database
            $video = Video::create([
                'google_drive_id' => $uploadedFile->id,
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'status' => $request->input('status') ? 1 : 0,
            ]);

            // Create course content item
            CoursesContentItem::create([
                'courses_content_id' => $content_item_id,
                'content_type' => 'video',
                'content_id' => $video->id,
                'status' => $request->input('status') ? 1 : 0,
            ]);

            return redirect()->route('courses.management.courses.content', ['id' => $courses_id])
                ->with('success', 'Video uploaded and added to course successfully!');
        } catch (Exception $e) {
            // If token expired during upload, redirect to authentication
            if (str_contains($e->getMessage(), 'invalid_grant') ||
                str_contains($e->getMessage(), 'invalid_token') ||
                str_contains($e->getMessage(), 'Not authenticated')) {

                $uploadData = $request->except('file');

                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    $uploadData['file'] = [
                        'temp_path' => $file->getPathname(),
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize()
                    ];
                }

                Session::put('video_upload_data', $uploadData);

                // Redirect to authentication
                return redirect()->route('google-drive.authenticate', [
                    'return_url' => route('courses.management.video.resume-upload', [
                        'courses_id' => $courses_id,
                        'content_item_id' => $content_item_id
                    ]),
                    'courses_id' => $courses_id,
                    'content_item_id' => $content_item_id
                ]);
            }

            Log::error('Video upload error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to upload video: ' . $e->getMessage());
        }
    }

    /**
     * Resume upload after authentication
     */
    public function resumeUpload($courses_id, $content_item_id)
    {
        // Check if user is authenticated
        if (!$this->googleDriveService->isAuthenticated()) {
            return redirect()->route('google-drive.authenticate', [
                'return_url' => route('courses.management.video.resume-upload', [
                    'courses_id' => $courses_id,
                    'content_item_id' => $content_item_id
                ]),
                'courses_id' => $courses_id,
                'content_item_id' => $content_item_id
            ]);
        }

        // Check upload data
        if (!Session::has('video_upload_data')) {
            return redirect()->route('courses.management.video.add-form', [
                'courses_id' => $courses_id,
                'content_item_id' => $content_item_id
            ])->with('info', 'Please fill in the form to upload a video.');
        }

        $uploadData = Session::pull('video_upload_data');

        // Create new request with saved data
        $request = new Request();
        $request->merge($uploadData);

        // If file info is missing, return to form
        if (!isset($uploadData['file']) || !$uploadData['file']) {
            return redirect()->route('courses.management.video.add-form', [
                'courses_id' => $courses_id,
                'content_item_id' => $content_item_id
            ])->with('error', 'The upload session has expired. Please try again.');
        }

        // Get file info
        $fileInfo = $uploadData['file'];

        // Verify the temp file still exists
        if (!file_exists($fileInfo['temp_path'])) {
            return redirect()->route('courses.management.video.add-form', [
                'courses_id' => $courses_id,
                'content_item_id' => $content_item_id
            ])->with('error', 'The temporary file has expired. Please try uploading again.');
        }

        try {
            // Upload directly to Google Drive
            $fileName = $uploadData['name'] . '.' . pathinfo($fileInfo['original_name'], PATHINFO_EXTENSION);

            // Get storage folder ID from environment variable
            $folderId = $this->googleDriveService->getStorageFolderId();

            // Upload file
            $uploadedFile = $this->googleDriveService->uploadFile($fileInfo['temp_path'], $fileName, $folderId);

            // Create video record in database
            $video = Video::create([
                'google_drive_id' => $uploadedFile->id,
                'name' => $uploadData['name'],
                'description' => $uploadData['description'],
                'status' => $uploadData['status'] ? 1 : 0,
            ]);

            // Create course content item
            CoursesContentItem::create([
                'courses_content_id' => $content_item_id,
                'content_type' => 'video',
                'content_id' => $video->id,
                'status' => $uploadData['status'] ? 1 : 0,
            ]);

            return redirect()->route('courses.management.courses.content', ['id' => $courses_id])
                ->with('success', 'Video uploaded and added to course successfully!');

        } catch (Exception $e) {
            Log::error('Failed to resume video upload: ' . $e->getMessage());
            return redirect()->route('courses.management.video.add-form', [
                'courses_id' => $courses_id,
                'content_item_id' => $content_item_id
            ])->with('error', 'Failed to upload video: ' . $e->getMessage());
        }
    }

    /**
     * List videos for a course
     */
    public function index($courses_id)
    {
        // Get all videos associated with the course
        $videos = CoursesContentItem::where('content_type', 'video')
            ->whereHas('coursesContent', function($query) use ($courses_id) {
                $query->where('courses_id', $courses_id);
            })
            ->with(['video'])
            ->get();

        return Inertia::render('Courses/Videos/Index', [
            'videos' => $videos,
            'courses_id' => $courses_id
        ]);
    }

    /**
     * Edit video form
     */
    public function edit($courses_id, $video_id): \Inertia\Response
    {
        $video = Video::findOrFail($video_id);
        $contentItem = CoursesContentItem::where('content_id', $video_id)
            ->where('content_type', 'video')
            ->first();

        return Inertia::render('Courses/Videos/Edit', [
            'video' => $video,
            'content_item' => $contentItem,
            'courses_id' => $courses_id,
            'isGoogleAuthenticated' => $this->googleDriveService->isAuthenticated()
        ]);
    }

    /**
     * Update video details
     */
    public function update(Request $request, $courses_id, $video_id): \Illuminate\Http\RedirectResponse
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'boolean',
        ]);

        try {
            $video = Video::findOrFail($video_id);
            $video->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'status' => request()->input('status'),
            ]);

            $contentItem = CoursesContentItem::where('content_id', $video_id)
                ->where('content_type', 'video')
                ->first();

            if ($contentItem) {
                $contentItem->update([
                    'status' => request()->input('status'),
                ]);
            }

            return back()
                ->with(['message', 'Video updated successfully!','status'=>true]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to update video: ' . $e->getMessage());
        }
    }

    /**
     * Delete video
     */
    public function delete($courses_id, $video_id): \Illuminate\Http\RedirectResponse
    {
        try {
            $video = Video::findOrFail($video_id);

            // Check if we should also delete from Google Drive
            if ($this->googleDriveService->isAuthenticated() && $video->google_drive_id) {
                try {
                    $this->googleDriveService->deleteFile($video->google_drive_id);
                } catch (Exception $e) {
                    // Log the error but continue with DB deletion
                    Log::error('Failed to delete video from Google Drive: ' . $e->getMessage());
                }
            }

            // First, delete the content item reference
            CoursesContentItem::where('content_id', $video_id)
                ->where('content_type', 'video')
                ->delete();

            // Then delete the video record
            $video->delete();

            return redirect()->route('courses.management.courses.content', ['id' => $courses_id])
                ->with('success', 'Video removed from course successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete video: ' . $e->getMessage());
        }
    }
}
