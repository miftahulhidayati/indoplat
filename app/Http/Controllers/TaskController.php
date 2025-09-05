<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Services\TaskService;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = $this->taskService->getPaginated($request, 10);

        if ($request->ajax()) {
            return response()->json([
                'tasks' => $tasks->items(),
                'pagination' => [
                    'current_page' => $tasks->currentPage(),
                    'last_page' => $tasks->lastPage(),
                    'per_page' => $tasks->perPage(),
                    'total' => $tasks->total(),
                ]
            ]);
        }

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->create($request->validated());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task created successfully!',
                'task' => $task
            ]);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task, Request $request)
    {
        if ($request->ajax()) {
            return response()->json($task);
        }

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->taskService->update($task, $request->validated());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully!',
                'task' => $task
            ]);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, Request $request)
    {
        $this->taskService->delete($task);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully!'
            ]);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }

    /**
     * Toggle task status (bonus feature)
     */
    public function toggleStatus(Task $task, Request $request)
    {
        $this->taskService->toggleStatus($task);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully!',
                'task' => $task
            ]);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task status updated successfully!');
    }
}
