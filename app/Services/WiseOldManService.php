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
}
