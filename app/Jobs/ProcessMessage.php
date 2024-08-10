<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class ProcessMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public $message,
        public $clan
    )
    {}

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     */
    // public function middleware(): array
    // {
    //     //return [(new WithoutOverlapping($this->message->generateHash()))->dontRelease()];
    // }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Start');
        $added = Cache::add($this->message->generateHash(), $this->clan->id, 15);
  
        if(!$added) {
                 // Log the response
            Log::info('Not added', [
                
            ]);
            return;
        }

        $webhook = $this->clan->settings()->where('key', 'discord_webhook')->get()->pluck('value')->first();
        Log::info('webhook', [
            
        ]);
        $settings = $this->clan->settings()->get()->mapWithKeys(function($setting, $key) {
            return [$setting->key => $setting->value];
        });
        Log::info('webhook', [
            'message' => $this->message,
            'webhook' => $webhook,
            'added' => $added,
            'settings' => $settings,
        ]);
        // send message to clan chat webhook
        $response = Http::timeout(3)->post($webhook, [
            'content' => $this->message->generateDiscordMessage($settings)
        ]);

        // Log the response
        Log::info('Webhook response', [
            'status' => $response->status(),
            'headers' => $response->headers(),
            'body' => $response->body(),
        ]);
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array<int, string>
     */
    public function tags(): array
    {
        return ['process', 'type:'.$this->message->systemMessageType, 'clan:' . $this->clan->id];
    }
}