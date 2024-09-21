<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClanGuestController;
use App\Http\Controllers\ClanSecretController;
use App\Http\Controllers\ClanSettingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ClanController;
use App\Http\Controllers\DiscordUserController;
use App\Http\Controllers\PlayerStatusController;
use App\Http\Controllers\NpcKillController;
use App\Http\Controllers\LootController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


Route::prefix('webhook')->group(function () {
    Route::post('/', function (Request $request) {
        // Log the request details
        Log::info('Request logged:', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'data' => $request->all(),
        ]);
    
        // Pass the request to the WebhookController
        $npc = new WebhookController();
        return $npc->webhook($request); // Pass the request to the webhook method
    });
    // Route::post('/npc_kill', [NpcKillController::class, 'store']);
    // Route::get('/npc_kills', [NpcKillController::class, 'index']); 
    // Route::get('/npc_kills/{id}', [NpcKillController::class, 'show']);
    // Route::post('/player_status', [PlayerStatusController::class, 'store']);
    // Route::post('/loot', [LootController::class, 'store']);
    //Route::post('{clan_secret}', [MessageController::class, 'store']);
});


Route::prefix('api')->group(function () {
    Route::get('get-token', [DiscordUserController::class, 'getToken']);
    Route::resource('clan', ClanController::class)->except('show');
    Route::resource('clan-secret', ClanSecretController::class)->except(['show', 'index', 'edit', 'update', 'create', 'destroy']);
    Route::get('clan-secret/{clan}', [ClanSecretController::class, 'show']);
    Route::delete('clan-secret/{clan_secret_id}', [ClanSecretController::class, 'destroy']);
    Route::resource('clan-settings', ClanSettingController::class);
    Route::get('clan-guest/{clan}', [ClanGuestController::class, 'show']);
    Route::post('clan-guest', [ClanGuestController::class, 'store']);
    Route::delete('clan-guest/{clan_guest_id}', [ClanGuestController::class, 'destroy']);
    Route::post('clan/{clan}/add-user', [ClanController::class, 'addUserToClan'])->name('clan.addUser');
    Route::delete('clan/{clan}/remove-user/{user}', [ClanController::class, 'removeUserFromClan'])->name('clan.removeUser');
    Route::get('clan/{clan}', [ClanController::class, 'show']);

    Route::get('/bingo/{bingo}/teams', [\App\Http\Controllers\Api\BingoController::class, 'teams'])->name('api-teams');
    Route::get('/bingo/{bingo}/team/{team}/tiles', [\App\Http\Controllers\Api\BingoController::class, 'tiles_team'])->name('api-team-tiles');
    Route::get('/bingo/{bingo}/team/{team}/players', [\App\Http\Controllers\Api\BingoController::class, 'team_players'])->name('api-team-player');
    Route::get('/bingo/{bingo}/team/{team}/', [\App\Http\Controllers\Api\BingoController::class, 'showTeam'])->name('api-team');
});



