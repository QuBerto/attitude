<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Services\AttitudeDiscord;
class CaptureScreen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'capture:screen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Capture a screenshot of a specific URL with a given CSS selector';

    /**
     * Execute the console command.
     *
     * @return int
     */
    protected $discord;
     public function __construct(AttitudeDiscord $discord)
     {
         parent::__construct();
         $this->discord = $discord;
     }
 
    public function handle()
    {
        // Retrieve arguments from the command
        $url = route('frontend-kills');
        $outputPath = 'public/screenshots/screenshot_golden_spoon.png';
        $selector = '.osrs-container';

        // Define the node process
        $process = new Process(['node', base_path('resources/js/capture.js'), $url, $outputPath, $selector]);

        // Run the process
        $process->run();

        // Check for process success
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Output success message
        $this->info('Screenshot captured successfully.');
        $this->discord->deleteMessagesByUser(1284338735231537162, 1232346840578654318, 10, $this);
        $this->sendImageToDiscord(1284338735231537162, $outputPath);
        return 0;
    }

    private function sendImageToDiscord($channel, $image)
    {
        $this->discord->sendImageToDiscord($channel, $image, $this);
    }
}
