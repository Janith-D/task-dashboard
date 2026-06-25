<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController
{
    public function index(): View
    {
        $tasks = Task::latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:pending,in_progress,completed'],
        ]);

        $task = Task::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['task' => $task->fresh(), 'message' => 'Task created successfully.'], 201);
        }

        return redirect()
            ->route('task.index')
            ->with('success', 'Task created successfully.');
    }

    public function updateStatus(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_progress,completed'],
        ]);

        $task->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'message' => 'Task status updated successfully.',
        ]);
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()
            ->route('task.index')
            ->with('success', 'Task deleted successfully.');
    }
}
