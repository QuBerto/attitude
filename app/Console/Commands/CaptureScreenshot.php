<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Models\Team;
use App\Models\BingoCard;
use App\Services\AttitudeDiscord;

class CaptureScreenshot extends Command
{
    protected $signature = 'capture:screenshot {bingo} {team_id?}';
    protected $description = 'Capture a screenshot and send it to Discord';

    const CHANNEL_DATA = [
        'main_channel' => 1267270235317080064,
        'bot_id' => 1232346840578654318,
        'parent' => 1269645507332935824,
        'threads' => [
            'Team A' => [
                'thread' => 1269646607528366080,
            ],
            'Team B' => [
                'thread' => 1269646957375258687,
            ],
            'Team C' => [
                'thread' => 1269647133103882260,
            ],
            'Team D' => [
                'thread' => 1269647634789044366,
            ],
        ]
    ];

    protected $discord;

    public function __construct(AttitudeDiscord $discord)
    {
        parent::__construct();
        $this->discord = $discord;
    }

    public function handle()
    {
        $bingoInput = $this->argument('bingo');
        $teamId = $this->argument('team_id');
        $bingo = BingoCard::find($bingoInput);

        $this->discord->deleteMessagesByUser(self::CHANNEL_DATA['main_channel'], self::CHANNEL_DATA['bot_id'], 100, $this);
        
        $this->handleOverview($bingo);
        if ($teamId) {
            $team = Team::find($teamId);
            if ($team) {
                $this->handleTeam($bingo, $team);
            } else {
                $this->error("Team with ID {$teamId} not found.");
            }
        } else {
            foreach ($bingo->teams as $team) {
                $last_image = $team->getMeta('last_image');
                $last_update = $team->getMeta('last_update');
                if (!$last_image || $last_image < $last_update){
                    $this->handleTeam($bingo, $team);
                }
                
            }
        }
    }

    private function handleOverview(BingoCard $bingo)
    {
        $bingoId = $bingo->id;
        $selectors = ['#docs-card'];
        $channel = self::CHANNEL_DATA['parent'];

        $this->discord->deleteMessagesByUser(self::CHANNEL_DATA['parent'], self::CHANNEL_DATA['bot_id'], 100, $this);

        foreach ($selectors as $selector) {
            $url = route('frontend-overview', ['bingoCard' => $bingoId]);
            $outputPath = public_path("screenshots/screenshot_overview_{$selector}.png");

            $process = new Process(['node', base_path('resources/js/capture.js'), $url, $outputPath, $selector]);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $this->sendImageToDiscord($channel, $outputPath);
        }
    }

    private function handleTeam(BingoCard $bingo, Team $team)
    {
        $bingoId = $bingo->id;
        $selectors = ['#docs-card', '#stats-card', '#drops-card'];
        $channel = self::CHANNEL_DATA['threads'][$team->name]['thread'];

        $this->discord->deleteMessagesByUser($channel, self::CHANNEL_DATA['bot_id'], 10, $this);

        foreach ($selectors as $selector) {
            $url = route('frontend-teams', ['bingoCard' => $bingoId, 'team' => $team->id]);
            $outputPath = public_path("screenshots/screenshot_team_{$selector}_{$team->id}.png");

            $process = new Process(['node', base_path('resources/js/capture.js'), $url, $outputPath, $selector]);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $this->sendImageToDiscord($channel, $outputPath);
        }
    }

    private function sendImageToDiscord($channel, $image)
    {
        $this->discord->sendImageToDiscord($channel, $image, $this);
    }
}
