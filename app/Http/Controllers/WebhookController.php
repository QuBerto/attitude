<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\NpcKillController;
use App\Http\Controllers\PlayerStatusController;
use App\Models\RSAccount;
use App\Services\AttitudeDiscord;
use Illuminate\Support\Facades\Log;
class WebhookController extends Controller
{
    public function webhook(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'payload_json' => 'required|json', // Ensure payload_json is present and valid JSON
            'file' => 'file', // Validate that 'file' is uploaded
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid data format.',
                'errors' => $validator->errors(),
            ], 400); // 400 Bad Request
        }
        $data =  json_decode($request->input('payload_json'),1);
        $type = $data['type'];
        Log::info('DATA:', [$data], true);
        if (isset($data['extra'])){
            $extra = $data['extra'];
           
            
        }
        if (isset($data['embeds'])){
            $embeds = ($data['embeds']);
            $data['embeds'][0]['footer']['text'] = 'Powered by Erva Ring';
            Log::info('DATA:', [$data['embeds'][0]['footer']['text']], true);
        }
        $playerName = $data['playerName'];
        $player = RSAccount::where('username', $playerName)->first();
        if (!$player){
            Log::info('Player not found:', [$playerName], true);
        } 
        $user = $player->discordUser;
        if (!$user){
            Log::info('User not found:', [$playerName], true);
        }
        if ($type){
            Log::info('Request type:', [$type], true);
        }
        $channels = [
        
                'DEATH' => 1059049971783061524,
                'LEVEL' => 1059051291663413248,
                'XP_MILESTONE' => 1059051291663413248,
                'COLLECTION' => 1059050475166650408,
                'LOOT'  => 1063869073219403866,
                'CLUE' => 1059050187382865970,
                'COMBAT_ACHIEVEMENT' => 1059050475166650408,
                'ACHIEVEMENT_DIARY' => 1059050475166650408,
                'PET' => 1059050146400309248,
                'BARBARIAN_ASSAULT_GAMBLE' => 1063869073219403866,
                'PLAYER_KILL' => 1063869073219403866,
            ];
        
        switch($type){
            case 'DEATH':
                $channel_id = $channels['DEATH'];
                break;
            case 'LEVEL':
                $channel_id = $channels['LEVEL'];
                break;
            case 'XP_MILESTONE':
                $channel_id = $channels['LEVEL'];
                break;
            case 'COLLECTION':
                $channel_id = $channels['COLLECTION'];
                break;
            case 'LOOT':
                if ($extra['category'] == 'NPC'){
                    $npc = new NpcKillController();
                    $npc->storeNew($extra, $player, $user);
                }
                $channel_id = $channels['LOOT'];
                break;
            case 'SLAYER':
                $channel_id = $channels['COLLECTION'];
                break;
            case 'QUEST':
                $channel_id = $channels['QUEST'];
                break;
            case 'CLUE':
                $channel_id = $channels['CLUE'];
                break;
            case 'KILL_COUNT':
                break;
            case 'COMBAT_ACHIEVEMENT':
                $channel_id = $channels['COLLECTION'];
                break;
            case 'ACHIEVEMENT_DIARY':
                $channel_id = $channels['COLLECTION'];
                break;
            case 'PET':
                $channel_id = $channels['PET'];
                break;
            case 'SPEEDRUN':
                break;
            case 'BARBARIAN_ASSAULT_GAMBLE':
                $channel_id = $channels['BARBARIAN_ASSAULT_GAMBLE'];
                break;
            case 'PLAYER_KILL':
                $channel_id = $channels['PLAYER_KILL'];
                break;
            case 'GRAND_EXCHANGE':
                break;
            case 'TRADE':
                break;
            case 'CHAT':
                break;
            case 'LOGIN':
                $playerStatus = new PlayerStatusController();
                $playerStatus->login($extra, $player, $user);
                break;
                
            case 'LOGOUT':
                $playerStatus = new PlayerStatusController();
                $playerStatus->logout($player, $user);
                break;
            default:
                $channel_id = 1107960124871544946;
                break;
        }
        //$channel_id = 1107960124871544946;
        // Process the request as needed
        // ...
        // Get the uploaded file (temporary file)
        $uploadedFile = $request->file('file');
        if($uploadedFile){
            // Move the temporary file to a proper location (optional)
            // Or pass the temporary file to your function
            $tempPath = $uploadedFile->getRealPath();
            $disc = new AttitudeDiscord(env('DISCORD_GUILD_ID'),env('DISCORD_BOT_TOKEN'));
            // Example: Send the file to Discord
            $disc->sendImageToDiscord2($channel_id , $tempPath, $data);
        }
        

        // Return a success response
        return response()->json(['status' => 'success', 'message' => 'Webhook processed']);
    }
}