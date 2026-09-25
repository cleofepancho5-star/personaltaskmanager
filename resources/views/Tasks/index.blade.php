<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f8fafc; color: #1e293b; font-family: Arial, sans-serif; }
        .page { max-width: 1100px; margin: 0 auto; padding: 32px 20px; }
        .header, .actions, .stats { display: flex; align-items: center; }
        .header { justify-content: space-between; gap: 20px; margin-bottom: 24px; }
        h1, h2, p { margin: 0; }
        h1 { font-size: 28px; color: #0f172a; }
        .subtitle { margin-top: 6px; color: #64748b; }
        .button { border: 0; border-radius: 6px; padding: 10px 16px; cursor: pointer; font-weight: 700; text-decoration: none; }
        .button-primary { background: #2563eb; color: #fff; }
        .button-muted { background: #e2e8f0; color: #334155; }
        .stats { gap: 16px; margin-bottom: 24px; }
        .stat, .panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; }
        .stat { flex: 1; padding: 18px; }
        .stat-label { color: #64748b; font-size: 13px; }
        .stat-value { display: block; margin-top: 6px; font-size: 26px; font-weight: 700; }
        .panel { padding: 20px; overflow-x: auto; }
        .task-header, .task-row { min-width: 720px; display: grid; grid-template-columns: 1.4fr 2fr 110px 130px 150px; gap: 12px; align-items: center; }
        .task-header { margin-top: 16px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase; }
        .task-row { padding: 14px 0; border-bottom: 1px solid #f1f5f9; }
        .task-row:last-child { border-bottom: 0; }
        .task-description { color: #64748b; }
        .badge { width: max-content; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 700; }
        .pending { background: #fef3c7; color: #b45309; }
        .completed { background: #dcfce7; color: #15803d; }
        .actions { gap: 8px; }
        .link-button { padding: 0; border: 0; background: transparent; color: #2563eb; cursor: pointer; font-weight: 700; }
        .delete { color: #dc2626; }
        .empty { padding: 28px; text-align: center; color: #64748b; }
        dialog { width: min(460px, calc(100% - 32px)); border: 0; border-radius: 8px; padding: 24px; box-shadow: 0 20px 50px #0f172a33; }
        dialog::backdrop { background: #0f172a66; }
        .field { margin-top: 14px; }
        label { display: block; margin-bottom: 5px; font-size: 13px; font-weight: 700; }
        input, textarea { width: 100%; padding: 9px 10px; border: 1px solid #cbd5e1; border-radius: 5px; font: inherit; }
        .modal-actions { justify-content: flex-end; margin-top: 20px; }
        .alert { margin-bottom: 18px; padding: 12px; border-radius: 6px; background: #dcfce7; color: #166534; }
        @media (max-width: 640px) { .header, .stats { align-items: stretch; flex-direction: column; } .stats { gap: 10px; } }
    </style>
</head>
<body>
<main class="page">
    <header class="header">
        <div>
            <h1>My Tasks</h1>
        </div>
        <button class="button button-primary" type="button" onclick="document.getElementById('create-task').showModal()">Add task</button>
    </header>

    @if (session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <section class="stats" aria-label="Task summary">
        <div class="stat"><span class="stat-label">Total tasks</span><span class="stat-value">{{ $totalTasks }}</span></div>
        <div class="stat"><span class="stat-label">Pending</span><span class="stat-value">{{ $pendingTasks }}</span></div>
        <div class="stat"><span class="stat-label">Completed</span><span class="stat-value">{{ $completedTasks }}</span></div>
    </section>

    <section class="panel">
        <h2>Task list</h2>
        <div class="task-header"><span>Task</span><span>Description</span><span>Status</span><span>Due date</span><span>Actions</span></div>
        @forelse ($tasks as $task)
            <div class="task-row">
                <strong>{{ $task->task_name }}</strong>
                <span class="task-description">{{ $task->description ?: 'No description' }}</span>
                <span class="badge {{ strtolower($task->status) }}">{{ $task->status }}</span>
                <span>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'No date' }}</span>
                <span class="actions">
                    <a class="link-button" href="{{ route('tasks.edit', [$task], false) }}">Edit</a>
                    <form action="{{ route('tasks.toggleStatus', [$task], false) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="link-button" type="submit">{{ $task->status === 'Pending' ? 'Done' : 'Undo' }}</button>
                    </form>
                    <form action="{{ route('tasks.destroy', [$task], false) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="link-button delete" type="submit">Delete</button>
                    </form>
                </span>
            </div>
        @empty
            <div class="empty">No tasks yet. Add your first task to get started.</div>
        @endforelse
    </section>
</main>

<dialog id="create-task">
    <h2>Add task</h2>
    <form action="{{ route('tasks.store', [], false) }}" method="POST">
        @csrf
        <div class="field"><label for="task_name">Task name</label><input id="task_name" name="task_name" required></div>
        <div class="field"><label for="description">Description</label><textarea id="description" name="description" rows="3"></textarea></div>
        <div class="field"><label for="due_date">Due date</label><input id="due_date" name="due_date" type="date"></div>
        <div class="field"><label for="status">Status</label><select id="status" name="status"><option value="Pending">Pending</option><option value="Completed">Completed</option></select></div>
        <div class="actions modal-actions"><button class="button button-muted" type="button" onclick="document.getElementById('create-task').close()">Cancel</button><button class="button button-primary" type="submit">Save task</button></div>
    </form>
</dialog>
</body>
</html>