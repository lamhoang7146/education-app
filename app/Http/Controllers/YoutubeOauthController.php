<?php

namespace App\Http\Controllers;

use App\Models\YoutubeOauth;
use App\Services\YoutubeOauthService;
use Hybridauth\Provider\Google;

class YoutubeOauthController extends Controller
{
    protected $youtubeOauthService;

    public function __construct(YoutubeOauthService $youtubeOauthService)
    {
        $this->youtubeOauthService = $youtubeOauthService;
    }

    public function getAccessToken()
    {
        $accessToken = $this->youtubeOauthService->getAccessToken();
        return response()->json($accessToken);
    }

    public function updateAccessToken($token)
{
    $data = json_decode($token, true);

    if (!empty($data['refresh_token'])) {
        // Lưu refresh_token nếu nó tồn tại
        YoutubeOauth::updateOrCreate(
            ['provider' => 'youtube'],
            ['provider_value' => $token]
        );
    } else {
        // Nếu không có refresh_token, chỉ cập nhật access_token
        $existingToken = YoutubeOauth::where('provider', 'youtube')->first();
        if ($existingToken) {
            $existingData = json_decode($existingToken->provider_value, true);
            $existingData['access_token'] = $data['access_token'];
            $existingData['expires_in'] = $data['expires_in'];
            $existingToken->update(['provider_value' => json_encode($existingData)]);
        }
    }
}

    // Thêm phương thức authenticate
    public function authenticate()
    {
        $config = config('hybridauth.google'); // Lấy cấu hình từ config/hybridauth.php
        $adapter = new Google($config);

        try {
            $adapter->authenticate(); // Thực hiện xác thực
            $accessToken = $adapter->getAccessToken();
            $refreshToken = $accessToken['refresh_token'] ?? null;

            // Lưu token vào cơ sở dữ liệu thông qua service
            $this->youtubeOauthService->updateAccessToken(json_encode($accessToken));

            return response()->json([
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function callback()
    {
        $config = config('hybridauth.google'); // Lấy cấu hình từ config/hybridauth.php
        $adapter = new Google($config);

        try {
            // Thực hiện xác thực và lấy access token
            $adapter->authenticate();
            $token = $adapter->getAccessToken();

            // Lưu token vào cơ sở dữ liệu thông qua service
            $this->youtubeOauthService->updateAccessToken(json_encode($token));

            return response()->json(['message' => 'Access token inserted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
