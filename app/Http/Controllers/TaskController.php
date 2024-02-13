<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Services\TaskService;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{

    public TaskService $taskService;

    public function __construct(TaskService $TaskService)
    {
        $this->taskService = $TaskService;
    }    

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json(['data' => $this->taskService->index($request) ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        return response()->json(['data' => Task::create($request->validated()) ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {    
        return response()->json(['data' => Task::findOrFail($id) ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, string $id)
    {
        return response()->json(['data' => $this->taskService->update( $request->validated() , $id) ]);      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json(['data' => 'destroy' ]);
    }

}
