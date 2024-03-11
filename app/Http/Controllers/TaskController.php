<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    // Add new task
    public function addTask(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|integer|between:1,5',
            'due_date' => 'required|date',

        ]);

        $task = Task::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'priority' => $request->input('priority'),
            'due_date' => \Carbon\Carbon::parse($request->input('due_date')),
        ]);

        return response()->json($task, 201);
    }

    // update task
    public function updateTask(Request $request,$id){
        $task=Task::find($id);
        if(is_null($task)){
            return response()->json(['messages'=>'task doesnt exist'],404);
        }
        $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'priority' => 'integer|between:1,5',
            'due_date' => 'date',
        ]);
        $task->update($request->all());
        return response()->json(['message' => 'Task updated successfully', 'data' => $task], 200);


    }
    // hethi mark task as completed
    public function markAsCompleted(Request $request, $id)
   {
    $task = Task::find($id);

    if (is_null($task)) {
        return response()->json(['message' => 'Task not found'], 404);
    }

    $request->validate([
        'completed' => 'required|boolean',
    ]);
    \Log::info('Task before update:', $task->toArray());

    $task->update([
        'completed' => $request->input('completed'),
    ]);
    \Log::info('Task before update:', $task->toArray());

    return response()->json(['message' => 'Task marked as completed successfully', 'data' => $task], 200);
}

// get all task function

public function getAllTasks()
{
    $tasks = Task::all();
    return response()->json($tasks, 200);
}
// delete function
public function deleteTask($id)
{
    $task = Task::find($id);

    if (is_null($task)) {
        return response()->json(['message' => 'Task not found'], 404);
    }

    $task->delete();

    return response()->json(['message' => 'Task deleted successfully'], 200);
}
}
