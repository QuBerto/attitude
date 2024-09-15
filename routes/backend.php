<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DiscordUserController;
use App\Http\Controllers\DiscordRoleController;
use App\Http\Controllers\RSAccountController;
use App\Http\Controllers\BingoCardController;
use App\Http\Controllers\TileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OsrsItemController;
use App\Http\Controllers\NpcController;
use App\Http\Controllers\EmojiController;

Route::get('/dashboard', function () {
    return redirect()->route('tasks.team', ['team' => 1, 'bingo' => 1]);
})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->prefix('backend')->group(function () {
    Route::get('/findApiItem/{item_id}', [OsrsItemController::class, 'findApiItem'])->name('find-item');
    //Discord Users
    Route::get('/discord', [DiscordUserController::class, 'index'])->name('discord.index');
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

