<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request, Project $project)
    {
        $tasks = $project->tasks()
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tasks,
            'message' => 'Task list'
        ]);
    }

    public function store(StoreTaskRequest $request, Project $project)
    {
        $task = $project->tasks()->create([
            'title' => $request->title,
            'status' => 'todo'
        ]);

        return response()->json([
            'success' => true,
            'data' => $task,
            'message' => 'Task created'
        ], 201);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('updateTask', $task->project);

        $task->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $task,
            'message' => 'Task updated'
        ]);
    }
}
