<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
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
// Route::get('/', [DiscordUserController::class, 'index']);
use App\Http\Controllers\ScreenshotController;



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
Route::get('/', [BingoCardController::class, 'frontend_temp']);
Route::post('/submit-guess', [WordGuessController::class, 'submitGuess']);

Route::get('/dashboard', function () {
    return redirect()->route('tasks.team', ['team' => 1, 'bingo' => 1]);
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    
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

    // Routes for creating events
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
});

require __DIR__.'/auth.php';
