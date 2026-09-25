<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task | Task Manager</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f8fafc; color: #1e293b; font-family: Arial, sans-serif; }
        .page { max-width: 1100px; margin: 0 auto; padding: 32px 20px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; margin-bottom: 24px; }
        h1, h2, p { margin: 0; }
        h1 { font-size: 28px; color: #0f172a; }
        .subtitle { margin-top: 6px; color: #64748b; }
        .button { display: inline-block; border: 0; border-radius: 6px; padding: 10px 16px; cursor: pointer; font-weight: 700; text-decoration: none; }
        .button-primary { background: #2563eb; color: #fff; }
        .button-muted { background: #e2e8f0; color: #334155; }
        .panel { max-width: 720px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px; }
        .panel-heading { padding-bottom: 16px; border-bottom: 1px solid #e2e8f0; }
        .panel-heading h2 { color: #0f172a; font-size: 18px; }
        .panel-heading p { margin-top: 5px; color: #64748b; font-size: 14px; }
        .form-grid { display: grid; gap: 16px; margin-top: 20px; }
        .field label { display: block; margin-bottom: 6px; color: #475569; font-size: 13px; font-weight: 700; }
        input, textarea, select { width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; color: #1e293b; font: inherit; }
        textarea { min-height: 110px; resize: vertical; }
        input:focus, textarea:focus, select:focus { outline: 2px solid #93c5fd; border-color: #2563eb; }
        .actions { display: flex; justify-content: flex-end; gap: 10px; padding-top: 4px; }
        .error { margin-top: 6px; color: #dc2626; font-size: 13px; }
        @media (max-width: 640px) {
            .header { align-items: stretch; flex-direction: column; }
            .header .button { width: max-content; }
            .panel { padding: 20px; }
            .actions { justify-content: stretch; flex-direction: column-reverse; }
            .actions .button { text-align: center; }
        }
    </style>
</head>
<body>
<main class="page">
    <header class="header">
        <div>
            <h1>My Tasks</h1>
        </div>
        <a class="button button-muted" href="{{ route('tasks.index', [], false) }}">Back to task list</a>
    </header>

    <section class="panel">
        <div class="panel-heading">
            <h2>Edit task</h2>
        </div>

        <form action="{{ route('tasks.update', [$task], false) }}" method="POST" class="form-grid">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="task_name">Task name</label>
                <input id="task_name" type="text" name="task_name" value="{{ old('task_name', $task->task_name) }}" required>
                @error('task_name')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="description">Description</label>
                <textarea id="description" name="description">{{ old('description', $task->description) }}</textarea>
                @error('description')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="due_date">Due date</label>
                <input id="due_date" type="date" name="due_date" value="{{ old('due_date', $task->due_date) }}">
                @error('due_date')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="Pending" {{ old('status', $task->status) === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Completed" {{ old('status', $task->status) === 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
                @error('status')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="actions">
                <a class="button button-muted" href="{{ route('tasks.index', [], false) }}">Cancel</a>
                <button class="button button-primary" type="submit">Update task</button>
            </div>
        </form>
    </section>
</main>
</body>
</html>