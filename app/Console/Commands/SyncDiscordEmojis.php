<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AttitudeDiscord;
use App\Models\Emoji;
use Illuminate\Support\Facades\Storage;

class SyncDiscordEmojis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:emojis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Discord emojis with the database, delete old ones, and save images';

    /**
     * Execute the console command.
     */
    public function handle(AttitudeDiscord $discord)
    {
        $emojis = $discord->get_emojis();

        // Store the Discord emoji IDs from the API response for deletion check later
        $currentEmojiIds = [];

        foreach ($emojis as $emojiData) {
            $emojiId = $emojiData['id'];
            $currentEmojiIds[] = $emojiId;
            $data = json_encode($emojiData['roles']);
            $this->info("image for emoji: {$data}");
            // Build the image URL from the emoji ID
            $imageUrl = "https://cdn.discordapp.com/emojis/{$emojiId}.png";

            // Update or create emoji in the database
            $emoji = Emoji::updateOrCreate(
                ['emoji_id' => $emojiId],
                ['name' => $emojiData['name']]
            );

            // Fetch and store the emoji image if not already stored
            if (!$emoji->getFirstMedia('images')) {
                try {
                    $emoji->addMediaFromUrl($imageUrl)->toMediaCollection('images');
                    $this->info("Saved image for emoji: {$emojiData['name']} (ID: {$emojiId})");
                } catch (\Exception $e) {
                    $this->error("Failed to save image for emoji: {$emojiData['name']} (ID: {$emojiId}) - Error: {$e->getMessage()}");
                }
            }
        }

        // Delete emojis that are no longer present in the API response
        Emoji::whereNotIn('emoji_id', $currentEmojiIds)->delete();
        $this->info('Deleted emojis that no longer exist.');

        $this->info('Emojis synchronized successfully.');
    }
}
