<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(): View
    {
        $tasks = Task::orderByRaw('due_date IS NULL')->orderBy('due_date')->latest()->get();
        $totalTasks = $tasks->count();
        $pendingTasks = $tasks->where('status', 'Pending')->count();
        $completedTasks = $tasks->where('status', 'Completed')->count();

        return view('Tasks.index', compact('tasks', 'totalTasks', 'pendingTasks', 'completedTasks'));
    }

    public function create(): View
    {
        return view('Tasks.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'task_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:Pending,Completed'],
        ]);

        $validated['status'] = $validated['status'] ?? 'Pending';

        Task::create($validated);

        return $this->taskListRedirect('Task added successfully.');
    }

    public function edit(Task $task): View
    {
        return view('Tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'task_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'status' => ['required', 'in:Pending,Completed'],
        ]);

        $task->update($validated);

        return $this->taskListRedirect('Task updated successfully.', '/');
    }

    public function toggleStatus(Task $task): RedirectResponse
    {
        $task->update([
            'status' => $task->status === 'Pending' ? 'Completed' : 'Pending',
        ]);

        return $this->taskListRedirect();
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return $this->taskListRedirect('Task deleted successfully.');
    }

    private function taskListRedirect(?string $message = null, string $path = '/tasks'): RedirectResponse
    {
        $redirect = redirect($path);

        if ($message !== null) {
            $redirect->with('success', $message);
        }

        $redirect->setTargetUrl($path);

        return $redirect;
    }
}