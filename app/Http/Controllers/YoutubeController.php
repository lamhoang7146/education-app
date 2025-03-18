<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_YouTube;

class YouTubeController extends Controller
{
    protected $client;
    protected $youtube;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setDeveloperKey(config('app.youtube'));

        $this->youtube = new Google_Service_YouTube($this->client);
    }

    public function getVideos(Request $request)
    {
        $channelId = $request->input('channel_id', 'UCNSCWwgW-rwmoE3Yc4WmJhw');
        $maxResults = $request->input('max_results', 10);

        $queryParams = [
            'channelId' => $channelId,
            'maxResults' => $maxResults,
            'order' => 'date'
        ];

        $response = $this->youtube->search->listSearch('snippet', $queryParams);

        return response()->json($response);
    }

    public function getChannelId(Request $request)
    {
        $username = $request->input('username', 'F8VNOfficial');

        $queryParams = [
            'forUsername' => $username,
        ];

        $response = $this->youtube->channels->listChannels('id', $queryParams);

        if (count($response->items) > 0) {
            return response()->json(['channelId' => $response->items[0]->id]);
        } else {
            return response()->json(['error' => 'Channel not found'], 404);
        }
    }
}
