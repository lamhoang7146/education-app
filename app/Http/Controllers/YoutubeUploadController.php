<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\YoutubeOauthService;
use Google_Client;
use Google_Service_YouTube;

class YoutubeUploadController extends Controller
{
    public function showUploadForm()
    {
        return view('youtube.upload'); // Hiển thị biểu mẫu tải video
    }

    public function uploadVideo(Request $request, YoutubeOauthService $youtubeOauthService)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'file' => 'required|file|mimes:mp4,mov,avi,wmv|max:102400', // Giới hạn 100MB
        ]);

        // Lấy access token từ cơ sở dữ liệu
        $accessToken = $youtubeOauthService->getAccessToken();
        if (!$accessToken) {
            return response()->json(['error' => 'Access token not found. Please authenticate first.'], 401);
        }

        // Cấu hình Google Client
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/youtube-oauth-credentials.json'));
        $client->setAccessToken(json_encode($accessToken));

        // Làm mới access token nếu cần
        if ($client->isAccessTokenExpired()) {
            $refreshToken = $youtubeOauthService->getRefreshToken();
            $client->fetchAccessTokenWithRefreshToken($refreshToken);
            $youtubeOauthService->updateAccessToken(json_encode($client->getAccessToken()));
        }

        // Tải video lên YouTube
        $youtube = new Google_Service_YouTube($client);

        $video = new \Google_Service_YouTube_Video();
        $videoSnippet = new \Google_Service_YouTube_VideoSnippet();
        $videoSnippet->setDescription($request->input('summary'));
        $videoSnippet->setTitle($request->input('title'));
        $video->setSnippet($videoSnippet);

        $videoStatus = new \Google_Service_YouTube_VideoStatus();
        $videoStatus->setPrivacyStatus('public'); // Hoặc 'private', 'unlisted'
        $video->setStatus($videoStatus);

        $videoPath = $request->file('file')->getPathname();

        try {
            $response = $youtube->videos->insert(
                'snippet,status',
                $video,
                [
                    'data' => file_get_contents($videoPath),
                    'mimeType' => 'video/*',
                    'uploadType' => 'multipart',
                ]
            );

            return response()->json(['message' => 'Video uploaded successfully!', 'videoId' => $response->id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
