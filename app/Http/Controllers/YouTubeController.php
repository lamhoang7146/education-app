<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Google_Client;
use Google_Service_YouTube;
use App\Models\YoutubeAccessToken;
use App\Models\UploadedVideo;
use Carbon\Carbon;
use Log;

class YouTubeController extends Controller
{
    public function uploadVideo(Request $request)
    {
        // Validate the request
        $request->validate([
            'video' => 'required|mimes:mp4,mov,ogg,qt|max:20000', // Adjust the max size as needed
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        // Initialize Google Client
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->addScope(Google_Service_YouTube::YOUTUBE_UPLOAD);
        $client->setAccessType('offline');

        // Check if we have an access token in the database
        $token = YoutubeAccessToken::latest()->first();
        if ($token) {
            $client->setAccessToken([
                'access_token' => $token->access_token,
                'refresh_token' => $token->refresh_token,
                'expires_in' => Carbon::parse($token->expires_at)->diffInSeconds(Carbon::now()),
            ]);

            // Refresh the token if it's expired
            if ($client->isAccessTokenExpired()) {
                $newToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $token->update([
                    'access_token' => $newToken['access_token'],
                    'expires_at' => Carbon::now()->addSeconds($newToken['expires_in']),
                ]);
            }
        } else {
            // Redirect to Google's OAuth 2.0 server
            $authUrl = $client->createAuthUrl();
            return Inertia::location($authUrl);
        }

        $youtube = new Google_Service_YouTube($client);

        // Create a snippet with title, description, tags and category ID
        $snippet = new Google_Service_YouTube_VideoSnippet();
        $snippet->setTitle($request->input('title'));
        $snippet->setDescription($request->input('description'));
        $snippet->setTags(['tag1', 'tag2']);
        $snippet->setCategoryId('22'); // Category ID for "People & Blogs"

        // Create a video status with privacy status
        $status = new Google_Service_YouTube_VideoStatus();
        $status->setPrivacyStatus('public');

        // Create a YouTube video with snippet and status
        $video = new Google_Service_YouTube_Video();
        $video->setSnippet($snippet);
        $video->setStatus($status);

        // Upload the video file
        $videoPath = $request->file('video')->getPathname();
        $chunkSizeBytes = 1 * 1024 * 1024; // 1MB

        $client->setDefer(true);
        $insertRequest = $youtube->videos->insert('status,snippet', $video);

        $media = new \Google_Http_MediaFileUpload(
            $client,
            $insertRequest,
            'video/*',
            null,
            true,
            $chunkSizeBytes
        );
        $media->setFileSize(filesize($videoPath));

        $status = false;
        $handle = fopen($videoPath, "rb");
        while (!$status && !feof($handle)) {
            $chunk = fread($handle, $chunkSizeBytes);
            $status = $media->nextChunk($chunk);
        }

        fclose($handle);

        $client->setDefer(false);

        // Log the response from YouTube API
        Log::info('YouTube API response', ['status' => $status]);

        // Save video information to the database
        UploadedVideo::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'video_id' => $status['id'],
        ]);

        return response()->json(['message' => 'Video uploaded successfully', 'videoId' => $status['id']]);
    }

    public function oauth2callback(Request $request)
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->addScope(Google_Service_YouTube::YOUTUBE_UPLOAD);
        $client->setAccessType('offline');

        $accessToken = $client->fetchAccessTokenWithAuthCode($request->input('code'));
        YoutubeAccessToken::create([
            'access_token' => $accessToken['access_token'],
            'refresh_token' => $accessToken['refresh_token'],
            'expires_at' => Carbon::now()->addSeconds($accessToken['expires_in']),
        ]);

        return redirect()->route('upload-video-form');
    }

    public function listVideos()
    {
        $videos = UploadedVideo::all();
        return Inertia::render('VideoList', ['videos' => $videos]);
    }
}
