<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WiseOldManService
{
    protected $baseUrl;
    protected $groupId;

    public function __construct()
    {
        $this->groupId = false;
        $this->baseUrl = 'https://api.wiseoldman.net/v2'; // Base URL of Wise Old Man API
    }

    public function setGroupId($id)
    {
        $this->groupId = $id;
    }

    public function getGroupId()
    {
        return $this->groupId;
    }

    public function getGroup()
    {
        if (!$this->groupId) {
            throw new \Exception("Group ID not set");
        }

        $response = Http::get("{$this->baseUrl}/groups/{$this->groupId}");
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

        $response = Http::get("{$this->baseUrl}/groups/{$this->groupId}/competitions");
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

    public function getPlayerGain($player)
    {
        $start_data = '2024-07-31';
        $end_data = '2024-08-31';

        try {
            $response = Http::get("{$this->baseUrl}/players/{$player}/gained", [
                'startDate' => $start_data,
                'endDate' => $end_data
            ]);

            if ($response->successful()) {
       
                // Handle successful response
                $data = $response->json();
                return response()->json(['success' => true, 'data' => $data]);
            } else {
                // Handle non-successful response
                return response()->json(['success' => false, 'message' => 'Error fetching data'], $response->status());
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
