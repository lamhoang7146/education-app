<?php
namespace App\Http\Controllers\Courses;
use App\Http\Controllers\Controller;
use App\Models\Video;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CoursesManagementVideo extends Controller
{
    private array $video = [];
    private $clientId;
    private $clientSecret;
    private $redirectUri;

    public function __construct()
    {
        $this->clientId = config('services.youtube.client_id');
        $this->clientSecret = config('services.youtube.client_secret');
        $this->redirectUri = config('services.youtube.redirect_uri');
    }

    public function video()
    {
        return Inertia::render('CoursesManagementVideo/Video', $this->video);
    }

    // Thêm endpoint để kiểm tra trạng thái xác thực
    public function checkAuth()
    {
        $accessToken = session('youtube_access_token');
        return response()->json([
            'authenticated' => !empty($accessToken)
        ]);
    }

    public function storeVideo(Request $request)
    {
        $credentials = $request->validate([
            'title' => 'required|string|max:255|min:3|',
            'description' => 'required|string|max:255|min:3',
            'video' => 'required|file|mimes:mp4',
            'status' => 'required|boolean',
        ]);

        try {
            // Kiểm tra xem có access token không
            $accessToken = session('youtube_access_token');

            if (!$accessToken) {
                // Chuyển hướng đến trang xác thực
                return redirect()->route('youtube.auth');
            }

            // Tải lên video với access token đã có
            $videoFile = $request->file('video');
            $videoPath = $videoFile->store('videos', 'public');

            // Gọi API YouTube để tải lên
            $youtubeVideoId = $this->uploadToYoutube(
                Storage::disk('public')->path($videoPath),
                $credentials['title'],
                $credentials['description']
            );

            // Lưu thông tin video vào database
            Video::create([
                'title' => $credentials['title'],
                'description' => $credentials['description'],
                'youtube_id' => $youtubeVideoId,
                'local_path' => $videoPath,
                'status' => $credentials['status'],
                // Thêm các trường khác nếu cần
            ]);

            // Xóa file local nếu không cần giữ lại
            // Storage::disk('public')->delete($videoPath);

            return redirect()->back()->with('success', 'Video uploaded successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error uploading video: ' . $e->getMessage());
        }
    }

    // Phương thức xác thực với YouTube API
    public function authenticateYoutube()
    {
        $authUrl = 'https://accounts.google.com/o/oauth2/auth';
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'scope' => 'https://www.googleapis.com/auth/youtube.upload',
            'response_type' => 'code',
            'access_type' => 'offline',
            'prompt' => 'consent'
        ];

        $authUrlWithParams = $authUrl . '?' . http_build_query($params);

        // Chuyển hướng toàn trang đến URL xác thực Google
        return redirect($authUrlWithParams);
    }

    public function youtubeCallback(Request $request)
    {
        if ($request->has('code')) {
            // Xử lý code để lấy access token
            $code = $request->input('code');

            // Gửi request để lấy access token từ Google
            $client = new \GuzzleHttp\Client();

            try {
                $response = $client->post('https://oauth2.googleapis.com/token', [
                    'form_params' => [
                        'code' => $code,
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                        'redirect_uri' => $this->redirectUri,
                        'grant_type' => 'authorization_code',
                    ],
                ]);

                $tokens = json_decode((string) $response->getBody(), true);

                // Lưu access token vào session
                session(['youtube_access_token' => $tokens['access_token']]);

                if (isset($tokens['refresh_token'])) {
                    session(['youtube_refresh_token' => $tokens['refresh_token']]);
                }

                // Sau khi xác thực xong, chuyển về trang video
                return redirect()->route('courses.management.video')->with('success', 'Xác thực YouTube thành công! Bạn có thể tải video lên bây giờ.');

            } catch (\Exception $e) {
                return redirect()->route('courses.management.video')->with('error', 'Lỗi khi lấy access token: ' . $e->getMessage());
            }
        }

        return redirect()->route('courses.management.video')->with('error', 'Xác thực YouTube thất bại!');
    }

    // Thêm phương thức để thực hiện tải video lên YouTube sau khi đã có access token
    private function uploadToYoutube($videoPath, $title, $description)
    {
        // Thêm code để sử dụng YouTube API tải video lên
        $accessToken = session('youtube_access_token');

        // Khởi tạo Google API Client
        $client = new \Google_Client();
        $client->setAccessToken($accessToken);

        // Nếu token hết hạn, thử refresh
        if ($client->isAccessTokenExpired() && session()->has('youtube_refresh_token')) {
            $client->fetchAccessTokenWithRefreshToken(session('youtube_refresh_token'));
            session(['youtube_access_token' => $client->getAccessToken()]);
        }

        // Khởi tạo YouTube service
        $youtube = new \Google_Service_YouTube($client);

        // Tạo video resource
        $video = new \Google_Service_YouTube_Video();

        // Thiết lập tiêu đề và mô tả
        $videoSnippet = new \Google_Service_YouTube_VideoSnippet();
        $videoSnippet->setTitle($title);
        $videoSnippet->setDescription($description);
        $videoSnippet->setCategoryId("22"); // Loại "Education"
        $video->setSnippet($videoSnippet);

        // Thiết lập quyền riêng tư
        $videoStatus = new \Google_Service_YouTube_VideoStatus();
        $videoStatus->setPrivacyStatus("unlisted"); // hoặc "private", "public"
        $video->setStatus($videoStatus);

        // Thực hiện upload
        $chunkSizeBytes = 1 * 1024 * 1024; // 1MB

        // Tạo file upload
        $client->setDefer(true);
        $insertRequest = $youtube->videos->insert("status,snippet", $video);
        $media = new \Google_Http_MediaFileUpload(
            $client,
            $insertRequest,
            'video/*',
            null,
            true,
            $chunkSizeBytes
        );
        $media->setFileSize(filesize($videoPath));

        // Tải lên từng phần
        $status = false;
        $handle = fopen($videoPath, "rb");
        while (!$status && !feof($handle)) {
            $chunk = fread($handle, $chunkSizeBytes);
            $status = $media->nextChunk($chunk);
        }
        fclose($handle);

        // Reset defer setting
        $client->setDefer(false);

        // Trả về ID video
        return $status['id'];
    }
}
