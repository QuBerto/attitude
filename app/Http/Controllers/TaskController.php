<?php

namespace App\Http\Controllers;
use App\Models\Team;
use App\Models\Task;
use App\Models\Tile;
use App\Models\BingoCard;
use Illuminate\Http\Request;
use App\Models\TaskCompletion;
use App\Models\DiscordUser;
class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        $teams = Team::with('users')->get();
        $allteams = Team::all();
        return view('bingo-cards.tasks', compact('tasks', 'teams', 'allteams'));
    }

    public function completeTask(Request $request, Task $task)
    {
        $request->validate([
            'discord_user_id' => 'required|exists:discord_users,id',
            'team_id' => 'required|exists:teams,id',
           
        ]);

        TaskCompletion::updateOrCreate(
            ['task_id' => $task->id, 'team_id' => $request->input('team_id')],
            ['discord_user_id' => $request->input('discord_user_id'),
             'description' => $request->input('description')]
        );

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'tile_id' => 'required|exists:tiles,id',
            'description' => 'required|string|max:255',
           
        ]);
       
        $tile = Tile::find($request->input('tile_id'));
        $task = Task::create([
            'tile_id' => $tile->id,
            'description' => $request->input('description'),
        ]);


  

        return response()->json(['success' => true, 'task' => $task]);
    }

    public function undoTask(Request $request, Task $task)
    {
        $request->validate([
            'discord_user_id' => '',
            'team_id' => 'required|exists:teams,id',
        ]);
        $discord_id = $request->input('discord_user_id');
        if ($discord_id){
            TaskCompletion::where('task_id', $task->id)
            ->where('team_id', $request->input('team_id'))
            ->where('discord_user_id', $request->input('discord_user_id'))
            ->delete();

            return response()->json(['success' => true]);
        }
        else{
            TaskCompletion::where('task_id', $task->id)
            ->where('team_id', $request->input('team_id'))
            
            ->delete();

            return response()->json(['success' => true]);
        }
      
    }

    public function team(BingoCard $bingo, Team $team){
        $tasks = Task::all();
        $teams = [$team];
        $allteams = Team::all();
        return view('bingo-cards.tasks', compact('tasks', 'teams', 'allteams'));
    }
   
}
