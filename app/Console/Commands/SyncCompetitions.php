<?php

namespace App\Console\Commands;
use App\Services\WiseOldManService;
use Illuminate\Console\Command;
use App\Models\Competition;
class SyncCompetitions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:competitions  {groupId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
            $competitions = $this->wiseOldManService->getGroupCompetitions();
            foreach ($competitions as $competitionData) {
                Competition::updateOrCreate(
                    ['competition_id' => $competitionData['id']],
                    [
                        'title' => $competitionData['title'],
                        'competition_id' => $competitionData['id'],
                        'metric' => $competitionData['metric'],
                        'type' => $competitionData['type'],
                        'starts_at' => $competitionData['startsAt'],
                        'ends_at' => $competitionData['endsAt'],
                        'group_id' => $competitionData['groupId'],
                        'score' => $competitionData['score'],
                        'participant_count' => $competitionData['participantCount'],
                    ]
                );
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }
}
