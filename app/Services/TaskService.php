<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class TaskService
{
    /**
     * Get paginated tasks with optional filters and ordering.
     */
    public function getPaginated(Request $request, int $perPage = 10): LengthAwarePaginator
    {
        $query = Task::query();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        // Default order: due_at ascending with NULLs last
        $query->orderByRaw('due_at IS NULL, due_at ASC');

        return $query->paginate($perPage);
    }

    /**
     * Create a new task from validated data.
     */
    public function create(array $validated): Task
    {
        return Task::create($validated);
    }

    /**
     * Update an existing task with validated data.
     */
    public function update(Task $task, array $validated): Task
    {
        $task->update($validated);
        return $task->refresh();
    }

    /**
     * Delete a task instance.
     */
    public function delete(Task $task): void
    {
        $task->delete();
    }

    /**
     * Toggle task status and return the updated model.
     */
    public function toggleStatus(Task $task): Task
    {
        $statuses = Task::getStatuses();
        $currentIndex = array_search($task->status, $statuses, true);
        $nextIndex = ($currentIndex === false) ? 0 : (($currentIndex + 1) % count($statuses));
        $task->update(['status' => $statuses[$nextIndex]]);
        return $task->refresh();
    }
}


