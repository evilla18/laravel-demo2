<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = auth('api')->user()->tasks()->latest()->get();

        return response()->json([
            'data' => $tasks,
            'message' => 'Tareas obtenidas exitosamente',
            'status' => 200,
        ]);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = auth('api')->user()->tasks()->create($request->validated());

        return response()->json([
            'data' => $task,
            'message' => 'Tarea creada exitosamente',
            'status' => 201,
        ], 201);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        if ($task->user_id !== auth('api')->id()) {
            return response()->json([
                'data' => null,
                'message' => 'No autorizado',
                'status' => 403,
            ], 403);
        }

        $task->update($request->validated());

        return response()->json([
            'data' => $task,
            'message' => 'Tarea actualizada exitosamente',
            'status' => 200,
        ]);
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== auth('api')->id()) {
            return response()->json([
                'data' => null,
                'message' => 'No autorizado',
                'status' => 403,
            ], 403);
        }

        $task->delete();

        return response()->json([
            'data' => null,
            'message' => 'Tarea eliminada exitosamente',
            'status' => 200,
        ]);
    }
}
