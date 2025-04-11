<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use App\Models\CoursesContentItem;
use App\Models\Video;
use App\Services\GoogleDriveOauthService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
        // Lưu URL trả về trong session để chuyển hướng sau khi xác thực
        if ($request->has('return_url')) {
            Session::put('google_auth_return_url', $request->input('return_url'));
        }

        // Lưu ID khóa học và ID mục nội dung nếu có
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

            // Nếu có content_item_id và courses_id trong session, chuyển hướng đến resumeUpload
            if (Session::has('google_auth_courses_id') && Session::has('google_auth_content_item_id')) {
                $coursesId = Session::get('google_auth_courses_id');
                $contentItemId = Session::get('google_auth_content_item_id');

                return redirect()->route('courses.management.video.resume-upload', [
                    'courses_id' => $coursesId,
                    'content_item_id' => $contentItemId
                ]);
            }

            // Nếu có return_url trong session
            if (Session::has('google_auth_return_url')) {
                $returnUrl = Session::pull('google_auth_return_url');
                return redirect($returnUrl)->with('success', 'Google Drive authentication successful!');
            }

            // Mặc định
            return redirect()->route('courses.management.courses.index')
                ->with('success', 'Google Drive authentication successful!');
        } catch (Exception $e) {
            return redirect()->route('courses.management.courses.index')
                ->with('error', 'Failed to authenticate with Google Drive: ' . $e->getMessage());
        }
    }
    /**
     * Show add video form (if needed)
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|boolean',
            'file' => 'required|file|mimes:mp4,mov,avi,wmv|max:102400', // Limit: 100MB
        ]);

        // Kiểm tra xác thực Google Drive
        if (!$this->googleDriveService->isAuthenticated()) {
            // Lưu dữ liệu form trong session
            $uploadData = $request->except('file');

            // Lưu thông tin về file tạm thời
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $tempPath = $file->getPathname();
                $originalName = $file->getClientOriginalName();
                $mimeType = $file->getMimeType();
                $size = $file->getSize();

                // Lưu thông tin về file
                $uploadData['file'] = [
                    'temp_path' => $tempPath,
                    'original_name' => $originalName,
                    'mime_type' => $mimeType,
                    'size' => $size
                ];
            }

            Session::put('video_upload_data', $uploadData);

            // Chuyển hướng tới xác thực
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
            // Code tải lên video
            $filePath = $request->file('file')->getPathname();
            $fileName = $request->input('title') . '.' . $request->file('file')->getClientOriginalExtension();

            // Sử dụng ID folder mặc định
            $folderId = '1RXcqFOJacEYTSQKrfBvl-HsZ6gfGwLR8';

            // Tải file lên
            $uploadedFile = $this->googleDriveService->uploadFile($filePath, $fileName, $folderId);

            // Tạo bản ghi video trong cơ sở dữ liệu
            $video = Video::create([
                'google_drive_id' => $uploadedFile->id,
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'status' => $request->input('status') ? 1 : 0,
            ]);

            // Tạo item nội dung khóa học
            CoursesContentItem::create([
                'courses_content_id' => $content_item_id,
                'content_type' => 'video',
                'content_id' => $video->id,
                'status' => $request->input('status') ? 1 : 0,
            ]);

            return redirect()->route('courses.management.courses.content', ['id' => $courses_id])
                ->with('success', 'Video uploaded and added to course successfully!');
        } catch (Exception $e) {
            // Nếu token hết hạn trong quá trình tải lên, chuyển hướng đến xác thực
            if (strpos($e->getMessage(), 'invalid_grant') !== false ||
                strpos($e->getMessage(), 'invalid_token') !== false) {

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

                // Chuyển hướng đến xác thực
                return redirect()->route('google-drive.authenticate', [
                    'return_url' => route('courses.management.video.resume-upload', [
                        'courses_id' => $courses_id,
                        'content_item_id' => $content_item_id
                    ]),
                    'courses_id' => $courses_id,
                    'content_item_id' => $content_item_id
                ]);
            }

            return redirect()->back()->with('error', 'Failed to upload video: ' . $e->getMessage());
        }
    }
    /**
     * Resume upload after authentication
     */
    public function resumeUpload($courses_id, $content_item_id)
    {
        // Kiểm tra xem người dùng đã xác thực chưa
        if (!$this->googleDriveService->isAuthenticated()) {
            return redirect()->route('courses.management.video.add-form', [
                'courses_id' => $courses_id,
                'content_item_id' => $content_item_id
            ])->with('error', 'You need to authenticate with Google Drive first.');
        }

        // Kiểm tra dữ liệu tải lên
        if (!Session::has('video_upload_data')) {
            return redirect()->route('courses.management.video.add-form', [
                'courses_id' => $courses_id,
                'content_item_id' => $content_item_id
            ])->with('info', 'Please fill in the form to upload a video.');
        }

        $uploadData = Session::pull('video_upload_data');

        // Tạo request mới với dữ liệu đã lưu
        $request = new Request();
        $request->merge($uploadData);

        // Nếu có file, cần xử lý đặc biệt vì file không thể lưu trong session
        if (!isset($uploadData['file']) || !$uploadData['file']) {
            return redirect()->route('courses.management.video.add-form', [
                'courses_id' => $courses_id,
                'content_item_id' => $content_item_id
            ])->with('error', 'The upload session has expired. Please try again.');
        }

        // Gọi phương thức addVideo
        return $this->addVideo($request, $courses_id, $content_item_id);
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
    public function edit($courses_id, $video_id)
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
    public function update(Request $request, $courses_id, $video_id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|boolean',
        ]);

        try {
            $video = Video::findOrFail($video_id);
            $video->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'status' => $request->input('status') ? 1 : 0,
            ]);

            // Update content item status
            $contentItem = CoursesContentItem::where('content_id', $video_id)
                ->where('content_type', 'video')
                ->first();

            if ($contentItem) {
                $contentItem->update([
                    'status' => $request->input('status') ? 1 : 0,
                ]);
            }

            return redirect()->route('courses.management.courses.content', ['id' => $courses_id])
                ->with('success', 'Video updated successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to update video: ' . $e->getMessage());
        }
    }

    /**
     * Delete video
     */
    public function delete($courses_id, $video_id)
    {
        try {
            $video = Video::findOrFail($video_id);

            // Check if we should also delete from Google Drive
            if ($this->googleDriveService->isAuthenticated() && $video->google_drive_id) {
                try {
                    $this->googleDriveService->deleteFile($video->google_drive_id);
                } catch (Exception $e) {
                    // Log the error but continue with DB deletion
                    \Log::error('Failed to delete video from Google Drive: ' . $e->getMessage());
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
