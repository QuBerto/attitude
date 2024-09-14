<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\ClanController;

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







require __DIR__.'/auth.php';
// Load additional route files
require __DIR__.'/backend.php';
require __DIR__.'/frontend.php';
require __DIR__.'/webhook.php';
// Additional route files as needed...

