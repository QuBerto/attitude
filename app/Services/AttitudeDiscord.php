<?php
namespace App\Services;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;


class AttitudeDiscord
{

    public $guildId;
    private $token;
    private $rolId;
    private $client;

    public function __construct($guildId, $token)
    {

        $this->guildId = $guildId;
        $this->token = $token;
        $this->rolId = '1233119240496873616';
        $this->client = new Client([
            'base_uri' => 'https://discord.com/api/v9/',
            'headers' => [
                'Authorization' => "Bot {$this->token}",
                'Content-Type' => 'application/json'
            ],
        ]);
    }

    public function connect($endpoint)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://discord.com/api/guilds/{$this->guildId}/" . $endpoint,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bot {$this->token}",
                "Content-Type: application/json"
            ],
            CURLOPT_RETURNTRANSFER => true
        ]);
       
        $response = curl_exec($ch);
        if ($response === false) {
            die('Error retrieving data from Discord API.');
        }

        $data = json_decode($response, true);
        if ($data === null) {
            die('Error decoding JSON response.');
        }
        return $data;
        curl_close($ch);
        return $response;
    }

    public function list_roles()
    {

        $data = $this->connect('roles');


        foreach ($data as $role) {
        }
    }

    public function get_emojis()
    {

        $data = $this->connect('emojis');
        return $data;
    }

    public function get_users()
    {
        $data = $this->connect('members?limit=1000');

        // Return the list of participants as an array
        return $data;
    }

    
    public function listRoles()
    {
        return $this->connect('roles');
    }
    public function get_channels()
    {
        return $this->connect('channels');
    }
    public function listChannels()
    {
        return $this->connect('channels');
    }



    public function list_pvp_participants_as_array()
    {
        $data = $this->connect('members?limit=1000');
        $participants = []; // Array to store participants list

        foreach ($data as $member) {
            if (isset($member['roles']) && in_array($this->rolId, $member['roles'])) {
                // The member has the role '1232084740618850324', so we need to add them to the list
                if ($member['nick']) {
                    $memberId = ($member['nick']);
                } else {

                    $memberId = ($member['user']['username']);
                }


                $participants[] = $memberId;
            }
        }

        // Return the list of participants as an array
        return $participants;
    }

    public function get_bingo_user($index)
    {
        $this->rolId = '1252665145499451412';
        $data = $this->connect('members?limit=1000');
        $participants = []; // Array to store participants list
        $i = 0;
        foreach ($data as $member) {
            if (isset($member['roles']) && in_array($this->rolId, $member['roles'])) {
                $i++;
                $memberId = false;
                if ($i == $index) {
                    if ($member['nick']) {
                        $pattern = '/\s*\p{So}+\(\d+\)/u';
                        $replacement = '';
                        $participant = preg_replace($pattern, $replacement, $member['nick']);
                        $memberId = ($participant);
                    } else {
                        $pattern = '/\s*\p{So}+\(\d+\)/u';
                        $replacement = '';
                        $participant = preg_replace($pattern, $replacement, $member['user']['username']);
                        $memberId = ($participant);
                    }

                    return $memberId;
                }


                $participants[] = $memberId;
            }
        }

        // Return the list of participants as an array
        return $participants;
    }

    public function format_bingo_participants_as_list($number = false)
    {
        $this->rolId = '1252665145499451412';
        $participants = $this->list_pvp_participants_as_array();

        $participantsList = ''; // String to store formatted list

        foreach ($participants as $index => $participant) {
            // Add the participant to the list
            if ($number) {
                $pattern = '/\s*\p{So}+\(\d+\)/u';
                $replacement = '';

                $participant = preg_replace($pattern, $replacement, $participant);
                $participantsList .= $index + 1 . " $participant\n"; // Number list
            } else {
                $participantsList .= "- $participant\n"; // Markdown bulleted list
            }
        }
        if (!$participantsList) {
            $participantsList = 'You will be the first to take part.';
        }

        // Return the formatted list of participants
        return $participantsList;
    }


    public function format_pvp_participants_as_list()
    {
        $participants = $this->list_pvp_participants_as_array();

        $participantsList = ''; // String to store formatted list

        foreach ($participants as $participant) {
            // Add the participant to the list
            $participantsList .= "- $participant\n"; // Markdown bulleted list
        }
        if (!$participantsList) {
            $participantsList = 'You will be the first to take part.';
        }

        // Return the formatted list of participants
        return $participantsList;
    }


    public function remove_role_from_users()
    {

        $data = $this->connect('members?limit=1000');
        $status = 'success';
        $updatedMembers = []; // Array to store members with updated roles
        foreach ($data as $member) {
            if (isset($member['roles']) && in_array($this->rolId, $member['roles'])) {

                // The member has the role '1232084740618850324', so we need to remove it
                $memberId = $member['user']['id'];

                $memberRoles = array_diff($member['roles'], [$this->rolId]); // Remove the specified role

                $result = 'success';
                // Update the member's roles
                if (!$this->updateMemberRoles($memberId, $memberRoles)) {
                    $status = 'error';
                    $result = 'error';
                }
                echo $result . PHP_EOL;

                // Add the updated member to the array
                $updatedMembers[] = [
                    'id' => $memberId,
                    'roles' => $memberRoles,
                    'result' => $result
                ];
            }
        }
        $data = ['status' => $status, 'data' => $updatedMembers];
        return $updatedMembers;
    }
    public function add_role_to_users($limit = 64)
    {
        $data = $this->connect('members?limit=1000');
        $status = 'success';
        $updatedMembers = []; // Array to store members with updated roles
        $count = 0; // Count of members with the role added
        foreach ($data as $member) {
            if (!isset($member['roles']) || !in_array($this->rolId, $member['roles'])) {
                // The member doesn't have the role, so we can add it
                $memberId = $member['user']['id'];
                $memberRoles = isset($member['roles']) ? $member['roles'] : [];
                $memberRoles[] = $this->rolId; // Add the specified role

                $result = 'success';
                // Update the member's roles
                if (!$this->updateMemberRoles($memberId, $memberRoles)) {
                    $status = 'error';
                    $result = 'error';
                }
                echo $result . PHP_EOL;

                // Add the updated member to the array
                $updatedMembers[] = [
                    'id' => $memberId,
                    'roles' => $memberRoles,
                    'result' => $result
                ];

                $count++;
                if ($count >= $limit) {
                    // Stop adding roles if the limit is reached
                    break;
                }
            }
        }
        $data = ['status' => $status, 'data' => $updatedMembers];
        return $updatedMembers;
    }

    public function updateMemberRoles($memberId, $memberRoles)
    {
        // Discord API endpoint for updating member roles
        $url = "https://discord.com/api/guilds/{$this->guildId}/members/{$memberId}";

        // Data to be sent in the PATCH request
        $data = [
            'roles' => array_values($memberRoles)
        ];

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_HTTPHEADER => [
                "Authorization: Bot {$this->token}",
                "Content-Type: application/json"
            ],
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_RETURNTRANSFER => true
        ]);
   
        // Execute cURL request
        $response = curl_exec($ch);

        // Close cURL session
        curl_close($ch);

        // Check if the request was successful
        if ($response === false) {
            return false; // Request failed
        }

        // Decode the JSON response
        $responseData = json_decode($response, true);
        dd($response);
        // Check if the response contains any errors
        if (isset($responseData['message'])) {
            dd($responseData);
            return false; // Error occurred
        }

        // Member roles updated successfully
        return true;
    }

    
    public function fetchMessages($channel, $limit = 100, $logger = null)
    {
        try {
            $response = $this->client->get("channels/{$channel}/messages", [
                'query' => [
                    'limit' => $limit,
                ],
            ]);
            if ($logger) {
                $logger->info("Fetched messages from channel {$channel}");
            }
            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 429) {
                $retryAfter = $e->getResponse()->getHeader('Retry-After')[0];
                if ($logger) {
                    $logger->info("Rate limited. Retrying after {$retryAfter} seconds");
                }
                sleep($retryAfter);
                return $this->fetchMessages($channel, $limit, $logger);
            }
            throw $e;
        }
    }

    public function deleteMessage($channel, $messageId, $logger = null)
    {
        try {
            $response = $this->client->delete("channels/{$channel}/messages/{$messageId}");
            if ($response->getStatusCode() === 204 && $logger) {
                $logger->info("Deleted message {$messageId} in channel {$channel}");
            }
            return $response->getStatusCode() === 204;
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 429) {
                $retryAfter = $e->getResponse()->getHeader('Retry-After')[0];
                if ($logger) {
                    $logger->info("Rate limited. Retrying after {$retryAfter} seconds");
                }
                sleep($retryAfter);
                return $this->deleteMessage($channel, $messageId, $logger);
            }
            throw $e;
        }
    }

    public function deleteMessagesByUser($channel, $userId, $limit = 100, $logger = null)
    {
        $messages = $this->fetchMessages($channel, $limit, $logger);
        foreach ($messages as $message) {
            if ($message['author']['id'] == $userId) {
                $this->deleteMessage($channel, $message['id'], $logger);
            }
        }
    }

    public function sendImageToDiscord($channel, $imagePath, $logger = null)
    {
        try {
            $url = "channels/{$channel}/messages";

            $response = $this->client->post($url, [
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => fopen($imagePath, 'r'),
                        'filename' => basename($imagePath),
                    ],
                    [
                        'name'     => 'payload_json',
                        'contents' => json_encode(['content' => '']),
                    ]
                ],
            ]);

            if ($response->getStatusCode() === 200 && $logger) {
                $logger->info('Screenshot sent to Discord successfully');
            }
            return $response->getStatusCode() === 200;
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 429) {
                $retryAfter = $e->getResponse()->getHeader('Retry-After')[0];
                if ($logger) {
                    $logger->info("Rate limited. Retrying after {$retryAfter} seconds");
                }
                sleep($retryAfter);
                return $this->sendImageToDiscord($channel, $imagePath, $logger);
            }
            throw $e;
        }
    }

    public function sendImageToDiscord2($channel, $imagePath, $data = null, $logger = null)
    {
        try {
            $url = "channels/{$channel}/messages";

            $response = $this->client->post($url, [
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => fopen($imagePath, 'r'),
                        'filename' => 'image.png',
                    ],
                    [
                        'name'     => 'payload_json',
                        'contents' => json_encode(['embeds' => $data['embeds']]),
                    ]
                ],
            ]);

            if ($response->getStatusCode() === 200 && $logger) {
                $logger->info('Screenshot sent to Discord successfully');
            }
            return $response->getStatusCode() === 200;
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 429) {
                $retryAfter = $e->getResponse()->getHeader('Retry-After')[0];
                if ($logger) {
                    $logger->info("Rate limited. Retrying after {$retryAfter} seconds");
                }
                sleep($retryAfter);
                return $this->sendImageToDiscord($channel, $imagePath, $logger);
            }
            throw $e;
        }
    }
}
