<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {}

    public function index(Request $request)
    {
        $tasks = $this->taskService->getTasksForUser($request->user(), $request->all());

        return response()->json($tasks);
    }

    public function store(TaskRequest $request)
    {
        $task = $this->taskService->createTask($request->user(), $request->validated());

        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        return response()->json($task->load('user'));
    }

    public function update(TaskRequest $request, Task $task)
    {
        $updatedTask = $this->taskService->updateTask($request->user(), $task, $request->validated());

        return response()->json($updatedTask);
    }

    public function destroy(Request $request, Task $task)
    {
        $this->taskService->deleteTask($request->user(), $task);

        return response()->json(null, 204);
    }
}
