<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BingoCard;
use App\Models\Team;

class BingoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function teams(BingoCard $bingo)
    {
        $data = $bingo->teams->map(function($team) {
            return [
                'id' => $team->id,
                'name' => $team->name,
                'created_at' => $team->created_at,
                'updated_at' => $team->updated_at,
                'pivot' => $team->pivot,  // You can adjust the pivot data if necessary
            ];
        });
        return $data;

        // Create the message string with ID and name, separated by <br>
        $message = $bingo->teams->map(function($team) {
            return $team->id . ' ' . $team->name;
        })->implode('\n');

        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => 200
        ], 200);
    }

     /**
     * Display a listing of the uncompleted tasks by a team.
     */
    public function tiles_team(BingoCard $bingo, Team $team)
    {
        $uncompletedTasks = [];

        foreach ($bingo->tiles as $tile) {
            $tile[] = [
                'tile_id' => $tile->id,
                'tile_name' => $tile->title,
            ];
        }
        return $uncompletedTasks;
    }

    /**
     * Display a listing of the uncompleted tasks by a team.
     */
    public function tasks_team(BingoCard $bingo, Team $team)
    {
        $uncompletedTasks = [];

        foreach ($bingo->tiles as $tile) {
            foreach ($tile->tasks as $task) {
                // Check if the task has been completed by the team
                $isCompleted = $team->completions->contains('task_id', $task->id);

                // If the task is not completed, add it to the uncompleted tasks array
                if (!$isCompleted) {
                 
                    $uncompletedTasks[] = [
                        'task_id' => $task->id,
                        'task_name' => $task->description,
                        'tile_id' => $tile->id,
                        'tile_name' => $tile->title,
                    ];
                }
            }
        }
        return $uncompletedTasks;

        // Create the message string with uncompleted task IDs and names, separated by <br>
        $message = collect($uncompletedTasks)->map(function ($task) {
            return $task['task_id'] . ' ' . $task['task_name'];
        })->implode('\n');

        return response()->json([
            'data' => $uncompletedTasks,
            'message' => $message,
            'status' => 200
        ], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function team_players(BingoCard $bingo, Team $team)
    {
        $data = $team->users->map(function($player) {
            return [
                'id' => $player->id,
                'username' => $player->username,
                'created_at' => $player->created_at,
                'updated_at' => $player->updated_at,
              
            ];
        });
        return $data;

        // Create the message string with ID and name, separated by <br>
        $message =  $team->users->map(function($player) {
            return $player->id . ' ' . $player->username;
        })->implode('\n');

        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => 200
        ], 200);
    }

    /**
 * Display a listing of the resource based on the type.
 */
public function showTeam(BingoCard $bingo, Team $team = null)
{
     // Collect data for teams
     $teamsData = $bingo->teams->map(function($team) {
        return [
            'id' => $team->id,
            'name' => $team->name,
            'created_at' => $team->created_at,
            'updated_at' => $team->updated_at,
            'pivot' => $team->pivot,
        ];
    });

    $teamsMessage = $bingo->teams->map(function($team) {
        return $team->id . ' ' . $team->name;
    })->implode('\n');

    // Collect data for uncompleted tasks
    $uncompletedTasks = [];
    foreach ($bingo->tiles as $tile) {
        foreach ($tile->tasks as $task) {
            $isCompleted = $team->completions->contains('task_id', $task->id);
            if (!$isCompleted) {
                $uncompletedTasks[] = [
                    'task_id' => $task->id,
                    'task_name' => $task->description,
                    'tile_id' => $tile->id,
                    'tile_name' => $tile->title,
                ];
            }
        }
    }

    $uncompletedTasksMessage = collect($uncompletedTasks)->map(function ($task) {
        return $task['task_id'] . ' ' . $task['task_name'];
    })->implode('\n');

    // Collect data for team players
    $teamPlayersData = $team->users->map(function($player) {
        return [
            'id' => $player->id,
            'username' => $player->username,
            'created_at' => $player->created_at,
            'updated_at' => $player->updated_at,
        ];
    });

    $teamPlayersMessage = $team->users->map(function($player) {
        return $player->id . ' ' . $player->username;
    })->implode('\n');

    // Combine all data and messages
    $data = [
        'teams' => $teamsData,
        'uncompleted_tasks' => $uncompletedTasks,
        'team_players' => $teamPlayersData,
    ];

    $message = implode('\n', [
        $teamsMessage,
        $uncompletedTasksMessage,
        $teamPlayersMessage
    ]);

    return response()->json([
        'data' => $data,
        'message' => $message,
        'status' => 200
    ], 200);
}


}
