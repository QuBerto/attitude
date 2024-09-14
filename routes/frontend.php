<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ClanController;
use App\Http\Controllers\DiscordUserController;
use App\Http\Controllers\RSAccountController;
use App\Http\Controllers\BingoCardController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\WordGuessController;
use App\Http\Controllers\ScreenshotController;
use App\Http\Controllers\DropController;
use App\Http\Controllers\NpcKillController;
use App\Http\Controllers\PlayerStatusController;
use App\Http\Controllers\NpcController;



// Page Routes
Route::resource('clan', ClanController::class)->only('show');

Route::get('/npckills', [NpcKillController::class, 'index']);
Route::resource('/drops', DropController::class)->only(['store', 'index']);
// Custom route to filter drops by event code
Route::get('/drops/event/{eventcode}', [DropController::class, 'showByEventCode'])->name('drops.byEventCode');
Route::get('/config', function () {
    return view('config');
});


Route::get('/dashboard-clan', function () {
    return view('dashboardClan');
})->middleware(['auth', 'verified'])->name('dashboard-jjjj');


Route::get('/golden-spoon', [FrontendController::class, 'kills'])->name('frontend-kills');
Route::get('/online', [PlayerStatusController::class, 'getRecentUpdates']);
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

Route::get('/discord-users/roles', [DiscordUserController::class, 'fixUserRoles'])->name('discord-users.fixUserRoles');