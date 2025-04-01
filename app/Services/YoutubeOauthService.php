<?php

namespace App\Services;

use App\Models\YoutubeOauth;

class YoutubeOauthService
{
    public function isTableEmpty(): bool
    {
        return !YoutubeOauth::where('provider', 'youtube')->exists();
    }

    public function getAccessToken()
    {
        $record = YoutubeOauth::where('provider', 'youtube')->first();
        return $record ? json_decode($record->provider_value) : null;
    }

    public function getRefreshToken()
    {
        $accessToken = $this->getAccessToken();
        return $accessToken ? $accessToken->refresh_token : null;
    }

    public function updateAccessToken($token)
    {
        $data = ['provider' => 'youtube', 'provider_value' => $token];

        if ($this->isTableEmpty()) {
            YoutubeOauth::create($data);
        } else {
            YoutubeOauth::where('provider', 'youtube')->update(['provider_value' => $token]);
        }
    }
}
