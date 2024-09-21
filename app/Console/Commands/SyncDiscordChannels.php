<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AttitudeDiscord;
use App\Models\Channel;
use Illuminate\Support\Facades\Log;

class SyncDiscordChannels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:channels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Discord channels with the database and delete old ones';

    /**
     * Execute the console command.
     */
    public function handle(AttitudeDiscord $discord)
    {
        $channels = $discord->get_channels();

        // Store the Discord channel IDs from the API response for deletion check later
        $currentChannelIds = [];

        foreach ($channels as $channelData) {
            $channelId = $channelData['id'];
            $currentChannelIds[] = $channelId;

            // Update or create channel in the database
            $channel = Channel::updateOrCreate(
                ['channel_id' => $channelId],
                [
                    'name' => $channelData['name'],
                    'type' => $channelData['type'],
                    'guild_id' => $channelData['guild_id'],
                    'position' => $channelData['position'],
                ]
            );
     
            $this->info("Synced channel: {$channelData['name']} (ID: {$channelId})");
        }

        // Delete channels that are no longer present in the API response
        Channel::whereNotIn('channel_id', $currentChannelIds)->delete();
        $this->info('Deleted channels that no longer exist.');

        $this->info('Channels synchronized successfully.');
    }
}
