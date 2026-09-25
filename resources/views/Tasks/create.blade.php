<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Task</title>
    <style>
        :root { --navy: #17253d; --ink: #1b2738; --muted: #718096; --line: #e8edf3; --blue: #2463d4; --canvas: #f5f7fb; }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--canvas); color: var(--ink); font-family: Inter, ui-sans-serif, system-ui, sans-serif; min-height: 100vh; display: grid; place-items: center; padding: 20px; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 12px; width: 100%; max-width: 460px; padding: 32px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        h2 { font-size: 20px; margin: 0 0 6px; }
        p { color: var(--muted); font-size: 13px; margin: 0 0 20px; }
        .field { margin-bottom: 16px; }
        label { display: block; font-size: 12px; font-weight: 700; color: #4a5568; margin-bottom: 6px; }
        input, textarea { width: 100%; padding: 10px 12px; border: 1px solid var(--line); border-radius: 7px; font-size: 13px; font-family: inherit; }
        input:focus, textarea:focus { outline: 2px solid var(--blue); border-color: transparent; }
        .actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; }
        .btn { padding: 10px 16px; border-radius: 7px; font-size: 12px; font-weight: 700; text-decoration: none; cursor: pointer; border: 0; }
        .btn-secondary { background: #edf2f7; color: #4a5568; }
        .btn-primary { background: var(--blue); color: #fff; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Add New Task</h2>
        <p>Create a task item to track in your workspace.</p>

        <form action="{{ route('tasks.store', [], false) }}" method="POST">
            @csrf
            <div class="field">
                <label for="task_name">Task Name *</label>
                <input type="text" id="task_name" name="task_name" placeholder="e.g., Complete Project Documentation" required>
            </div>

            <div class="field">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" placeholder="Optional details..."></textarea>
            </div>

            <div class="field">
                <label for="due_date">Due Date</label>
                <input type="date" id="due_date" name="due_date">
            </div>

            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>

            <div class="actions">
                <a href="{{ route('tasks.index', [], false) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Task</button>
            </div>
        </form>
    </div>
</body>
</html>