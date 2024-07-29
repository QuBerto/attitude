<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CaptureWebsite extends Command
{
    protected $signature = 'capture:website {url} {output}';
    protected $description = 'Capture a screenshot of a website';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $url = $this->argument('url');
        $output = $this->argument('output');

        $command = "node resources/js/capture.js " . escapeshellarg($url) . " " . escapeshellarg($output);
        $result = shell_exec($command);

        $this->info($result);
    }
}
