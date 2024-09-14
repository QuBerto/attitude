<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClanController;
use App\Http\Controllers\ClanGuestController;
use App\Http\Controllers\ClanSecretController;
use App\Http\Controllers\ClanSettingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\DiscordUserController;
use App\Http\Controllers\DiscordRoleController;
use App\Http\Controllers\RSAccountController;
use App\Http\Controllers\BingoCardController;
use App\Http\Controllers\TileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\WordGuessController;
use App\Http\Controllers\ScreenshotController;
use App\Http\Controllers\DropController;
use \App\Http\Controllers\Api\BingoController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\NpcKillController;
use App\Http\Controllers\PlayerStatusController;
use App\Http\Controllers\OsrsItemController;
use App\Http\Controllers\NpcController;
use App\Http\Controllers\EmojiController;


Route::get('/golden-spoon', [FrontendController::class, 'kills'])->name('frontend-kills');
Route::prefix('webhook')->group(function () {
    Route::post('/npc_kill', [NpcKillController::class, 'store']); // Store a new NPC kill
    Route::get('/npc_kills', [NpcKillController::class, 'index']);  // Retrieve all NPC kills
    Route::get('/npc_kills/{id}', [NpcKillController::class, 'show']); // Retrieve a specific NPC kill
    Route::post('/player_status', [PlayerStatusController::class, 'store']);
});
Route::get('/online', [PlayerStatusController::class, 'getRecentUpdates']);
Route::get('/api/bingo/{bingo}/teams', [\App\Http\Controllers\Api\BingoController::class, 'teams'])->name('api-teams');
Route::get('/api/bingo/{bingo}/team/{team}/tiles', [\App\Http\Controllers\Api\BingoController::class, 'tiles_team'])->name('api-team-tiles');
Route::get('/api/bingo/{bingo}/team/{team}/players', [\App\Http\Controllers\Api\BingoController::class, 'team_players'])->name('api-team-player');
Route::get('/api/bingo/{bingo}/team/{team}/', [\App\Http\Controllers\Api\BingoController::class, 'showTeam'])->name('api-team');



Route::get('/bingo/{bingoCard}/progress/{team}', [BingoCardController::class, 'frontend_progress'])->name('frontend-progress');
Route::get('/bingo/{bingoCard}', [BingoCardController::class, 'frontend'])->name('frontend-overview');
Route::get('/bingo/{bingoCard}/team/{team}', [BingoCardController::class, 'frontend_team'])->name('frontend-teams');
Route::get('/members', [RSAccountController::class, 'frontend'])->name('frontend.members');
//Route::get('/calendar', [RSAccountController::class, 'frontend'])->name('frontend.calendar');
Route::get('/calendar', [CalendarController::class, 'show'])->name('calendar.show');
Route::get('/calendar2', function () {
    return view('frontend.calendar.calendar');
});


Route::post('/capture-screenshot', [ScreenshotController::class, 'capture'])->name('capture.screenshot');
Route::get('/', [FrontendController::class, 'homepage']);
Route::post('/submit-guess', [WordGuessController::class, 'submitGuess']);
Route::get('/all', [NpcController::class, 'all']);
Route::get('/dashboard', function () {
    return redirect()->route('tasks.team', ['team' => 1, 'bingo' => 1]);
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/discord-users/roles', [DiscordUserController::class, 'fixUserRoles'])->name('discord-users.fixUserRoles');

Route::middleware('auth')->prefix('backend')->group(function () {
    Route::get('/findApiItem/{item_id}', [OsrsItemController::class, 'findApiItem'])->name('find-item');
    //Discord Users
    Route::get('/discord-users', [DiscordUserController::class, 'index'])->name('discord-users.index');
    Route::get('/discord-users/show/{discordUser}', [DiscordUserController::class, 'show'])->name('discord-users.show');
    Route::post('/discord-users/{discordUser}/assign-player', [DiscordUserController::class, 'assignPlayer'])->name('discord-users.assign-player');
    Route::delete('/discord-users/{discordUser}/unassign-player/{account}', [DiscordUserController::class, 'unassignPlayer'])->name('discord-users.unassign-player');
    Route::get('/discord-users/unconnected', [DiscordUserController::class, 'unconnected'])->name('discord-users.unconnected');
    
    //Discord Roles
    Route::get('/discord-roles', [DiscordRoleController::class, 'index'])->name('discord-roles.index');
    Route::get('/discord-roles/{discordRole}', [DiscordRoleController::class, 'show'])->name('discord-roles.show');

    //Wom accounts
    Route::get('/rs-accounts', [RSAccountController::class, 'index'])->name('rs-accounts.index');
    Route::get('/rs-accounts/{rSAccount}', [RSAccountController::class, 'show'])->name('rs-accounts.show');

    //Bingo
    Route::resource('bingo-cards', BingoCardController::class);
    Route::resource('tiles', TileController::class)->only(['update']);
    Route::resource('tasks', TaskController::class)->only(['store']);
    Route::resource('/osrs-items', OsrsItemController::class);
    Route::resource('/npcs', NpcController::class);
    Route::resource('emojis', EmojiController::class);
    Route::post('teams/{card}/store', [TeamController::class, 'store'])->name('teams.store');
    Route::post('teams/{team}/addMember', [TeamController::class, 'addMember'])->name('teams.addMember');
    Route::post('/teams/{team}/removeMember', [TeamController::class, 'removeMember'])->name('teams.removeMember');
    Route::post('/tasks/{task}/complete', [TaskController::class, 'completeTask'])->name('tasks.complete');
    Route::post('/tasks/{task}/undo', [TaskController::class, 'undoTask']);

    Route::get('/tasks/{task}', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{bingo}/{team}', [TaskController::class, 'team'])->name('tasks.team');


    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    //Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/tasks/{task}/delete', [TaskController::class, 'deleteTaskCompletion']);
    // Add this route for updating the Discord user
    Route::post('/profile/discord-user', [ProfileController::class, 'updateDiscordUser'])->name('profile.updateDiscordUser');
    // Routes for creating events
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
});


// Page Routes
Route::resource('clan', ClanController::class)->only('show');

Route::get('/npckills', [NpcKillController::class, 'index']);
Route::resource('/drops', DropController::class)->only(['store', 'index']);
// Custom route to filter drops by event code
Route::get('/drops/event/{eventcode}', [DropController::class, 'showByEventCode'])->name('drops.byEventCode');
Route::get('/config', function () {
    return view('config');
});

// Resource Routes
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

    // Custom routes for adding and removing a user to/from a clan
    Route::post('clan/{clan}/add-user', [ClanController::class, 'addUserToClan'])->name('clan.addUser');
    Route::delete('clan/{clan}/remove-user/{user}', [ClanController::class, 'removeUserFromClan'])->name('clan.removeUser');
    Route::get('clan/{clan}', [ClanController::class, 'show']);
});
Route::get('/dashboard-clan', function () {
    return view('dashboardClan');
})->middleware(['auth', 'verified'])->name('dashboard-jjjj');
Route::prefix('webhook')->group(function () {
    Route::post('{clan_secret}', [MessageController::class, 'store']);
});

require __DIR__.'/auth.php';
