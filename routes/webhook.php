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

Route::prefix('webhook')->group(function () {
    Route::post('/npc_kill', [NpcKillController::class, 'store']);
    Route::get('/npc_kills', [NpcKillController::class, 'index']); 
    Route::get('/npc_kills/{id}', [NpcKillController::class, 'show']);
    Route::post('/player_status', [PlayerStatusController::class, 'store']);
    Route::post('/loot', [LootController::class, 'stroe']);
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



