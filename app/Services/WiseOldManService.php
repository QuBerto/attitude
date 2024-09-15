<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class WiseOldManService
{
    protected $baseUrl;
    protected $groupId;
    protected $apiKey;
    protected $userAgent;

    public function __construct()
    {
        $this->baseUrl = 'https://api.wiseoldman.net/v2'; // Base URL of Wise Old Man API
        $this->groupId = false;
        $this->apiKey = config('services.wiseoldman.api_key');  // Fetch API key from config
        $this->userAgent = config('services.wiseoldman.user_agent');  // Fetch user-agent from config
    }

    public function setGroupId($id)
    {
        $this->groupId = $id;
    }

    public function getGroupId()
    {
        return $this->groupId;
    }

    protected function getHeaders()
    {
        return [
            'x-api-key' => $this->apiKey,
            'User-Agent' => $this->userAgent,
        ];
    }

    public function getGroup()
    {
        if (!$this->groupId) {
            throw new \Exception("Group ID not set");
        }

        $response = Http::withHeaders($this->getHeaders())
                        ->get("{$this->baseUrl}/groups/{$this->groupId}");

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function getGroupCompetitions()
    {
        if (!$this->groupId) {
            throw new \Exception("Group ID not set");
        }

        $response = Http::withHeaders($this->getHeaders())
                        ->get("{$this->baseUrl}/groups/{$this->groupId}/competitions");

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function getGroupMembers()
    {
        $data = $this->getGroup();
  
        if ($data && isset($data['memberships'])) {
            return $data['memberships'];
        }

        return null;
    }

    public function getPlayerGain($player, $start_data = false, $end_data = false)
    {
        if (!$start_data){
            $start_data = Carbon::today()->subWeek()->toDateString(); // Default start date to one week ago
        }
        if (!$end_data){
            $end_data = Carbon::today()->toDateString(); // Default end date to today
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                            ->get("{$this->baseUrl}/players/{$player}/gained", [
                                'startDate' => $start_data,
                                'endDate' => $end_data
                            ]);

            if ($response->successful()) {
                $data = $response->json();
                return ['success' => true, 'data' => $data];
            } else {
                return ['success' => false, 'message' => 'Error fetching data', 'status' => $response->status()];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updatePlayer($username)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->baseUrl}/players/{$username}", [
                    'Content-Type' => 'application/json'
                ]);
           
            // Check if the response is successful
            if ($response->successful()) {
                // Return the updated player details
                $data = $response->json();
                return ['success' => true, 'data' => $data];
            } else {
          
                // Handle unsuccessful response
                return ['success' => false, 'message' => 'Failed to update player'];
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

}
