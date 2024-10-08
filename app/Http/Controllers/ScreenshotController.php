<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Models\Team;
class ScreenshotController extends Controller
{
    public function capture(Request $request)
    {
        // Validate that all inputs are strings
        $validatedData = $request->validate([
            'team' => 'required|string',
            'channel' => 'required',
            'selector' => 'required|string',
        ]);

        // Retrieve the validated input data
        $teamInput = $validatedData['team'];
        $channel = $validatedData['channel'];
        $selector = $validatedData['selector'];
        // Determine if the team input is a number or a string
        if (is_numeric($teamInput)) {
            $team = Team::find($teamInput);
        } else {
            $team = Team::where('name', 'LIKE', 'Team ' . $teamInput)->first();
        }
    
        // Check if the team exists
        if (!$team) {
            return response()->json(['error' => 'Team not found'], 404);
        }
    
        $bingo_id = $team->bingoCards[0]->id;
    
        $url = route('frontend-teams', ['bingoCard' => $bingo_id, 'team' => $team->id]);
    
        $outputPath = public_path("screenshots/screenshot_team_{$selector}_{$team->id}.png");
    
        $process = new Process(['node', base_path('resources/js/capture.js'), $url, $outputPath, $selector]);
        $process->run();
    
        // Executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    
        return response()->json($this->sendImageToDiscord($channel, $outputPath));
    }
   

    public function sendImageToDiscord($channel, $image)
    {
        // Path to the generated screenshot
        $imagePath = $image;
        
        // Discord API settings
        $discordChannelId = $channel;
        $discordBotToken = env('DISCORD_BOT_TOKEN');
        $message = '';
        
        // Initialize GuzzleHTTP client
        $client = new Client();

        try {
            $response = $client->post("https://discord.com/api/v9/channels/1267270235317080064/messages", [
            //$response = $client->post("https://discord.com/api/v9/channels/{$discordChannelId}/messages", [
            //$response = $client->post("https://discord.com/api/v9/channels/1130621274792665089/messages", [
                'headers' => [
                    'Authorization' => "Bot {$discordBotToken}",
                ],
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => fopen($imagePath, 'r'),
                        'filename' => basename($imagePath),
                    ],
                    [
                        'name'     => 'payload_json',
                        'contents' => json_encode(['content' => $message]),
                    ]
                ],
            ]);

            if ($response->getStatusCode() == 200) {
                return response()->json(['message' => 'Screenshot sent to Discord successfully']);
            } else {
                return response()->json(['error' => 'Error sending screenshot to Discord'], $response->getStatusCode());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error sending screenshot to Discord: ' . $e->getMessage()]);
        }
    }
    public function sendImageToDiscord2($channel, $image, $threadId = null)
{
    // Path to the generated screenshot
    $imagePath = $image;
    
    // Discord API settings
    $threadId = 1269646607528366080;
    $discordChannelId = 1269645507332935824;
    $discordBotToken = env('DISCORD_BOT_TOKEN');
    $message = '';
    
    // Initialize GuzzleHTTP client
    $client = new Client();

    try {
        // Construct the URL
        $url = "https://discord.com/api/v9/channels/{$discordChannelId}/messages";
        if ($threadId) {
            $url = "https://discord.com/api/v9/channels/{$discordChannelId}/threads/{$threadId}/messages";
        }

        $response = $client->post($url, [
            'headers' => [
                'Authorization' => "Bot {$discordBotToken}",
            ],
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => fopen($imagePath, 'r'),
                    'filename' => basename($imagePath),
                ],
                [
                    'name'     => 'payload_json',
                    'contents' => json_encode(['content' => $message]),
                ]
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            return response()->json(['message' => 'Screenshot sent to Discord successfully']);
        } else {
            return response()->json(['error' => 'Error sending screenshot to Discord'], $response->getStatusCode());
        }
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error sending screenshot to Discord: ' . $e->getMessage()]);
    }
}
}
