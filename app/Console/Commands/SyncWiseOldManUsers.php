<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WiseOldManService;
use App\Models\RSAccount;

class SyncWiseOldManUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:wiseoldman-users {groupId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync users from Wise Old Man API to the application';

    protected $wiseOldManService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(WiseOldManService $wiseOldManService)
    {
        parent::__construct();
        $this->wiseOldManService = $wiseOldManService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $groupId = $this->argument('groupId');
        $this->wiseOldManService->setGroupId($groupId);

        try {
            $members = $this->wiseOldManService->getGroupMembers();

            if ($members) {
                foreach ($members as $member) {
                    $player = $member['player'];
                    
                    RSAccount::updateOrCreate(
                        ['wom_id' => $player['id']],
                        [
                            'username' => $player['username'],
                            'display_name' => $player['displayName'] ?? null,
                            'type' => $player['type'] ?? null,
                            'build' => $player['build'] ?? null,
                            'status' => $player['status'] ?? null,
                            'country' => $player['country'] ?? null,
                            'patron' => $player['patron'] ?? false,
                            'exp' => $player['exp'] ?? null,
                            'ehp' => $player['ehp'] ?? null,
                            'ehb' => $player['ehb'] ?? null,
                            'ttm' => $player['ttm'] ?? null,
                            'tt200m' => $player['tt200m'] ?? null,
                            'registered_at' => isset($player['registeredAt']) ? \Carbon\Carbon::parse($player['registeredAt']) : null,
                            'wom_updated_at' => isset($player['updatedAt']) ? \Carbon\Carbon::parse($player['updatedAt']) : null,
                            'last_changed_at' => isset($player['lastChangedAt']) ? \Carbon\Carbon::parse($player['lastChangedAt']) : null,
                            'last_imported_at' => isset($player['lastImportedAt']) ? \Carbon\Carbon::parse($player['lastImportedAt']) : null,
                        ]
                    );
                }

                $this->info('Wise Old Man users synchronized successfully.');
            } else {
                $this->error('No members found for the specified group.');
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }
}
