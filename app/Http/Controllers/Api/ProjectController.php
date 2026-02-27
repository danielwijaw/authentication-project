<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::where('owner_id', $request->user()->id)->get();

        return response()->json([
            'success' => true,
            'data' => $projects,
            'message' => 'Project list'
        ]);
    }

    public function store(StoreProjectRequest $request)
    {
        $project = Project::create([
            'name' => $request->name,
            'owner_id' => $request->user()->id
        ]);

        return response()->json([
            'success' => true,
            'data' => $project,
            'message' => 'Project created'
        ], 201);
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Project deleted'
        ]);
    }
}
