<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f0f2f5; margin: 0; padding: 40px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-top: 0; }

        /* Form Styling */
        .form-group { margin-bottom: 15px; }
        input[type="text"], textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        .btn { padding: 10px 15px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .btn-add { background-color: #2ecc71; color: white; width: 100%; }
        .btn-delete { background-color: #e74c3c; color: white; padding: 5px 10px; font-size: 12px; }

        /* Table Styling */
        table { width: 100%; border-collapse: collapse; margin-top: 25px; }
        th { text-align: left; border-bottom: 2px solid #eee; padding: 10px; color: #666; }
        td { padding: 15px 10px; border-bottom: 1px solid #eee; }
        .empty { text-align: center; color: #999; padding: 20px; }
    </style>
</head>
<body>

<div class="container">
    <h2>My Tasks</h2>

    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <input type="text" name="title" placeholder="What needs to be done?" required>
        </div>
        <div class="form-group">
            <textarea name="description" placeholder="Brief description (optional)" rows="2"></textarea>
        </div>
        <button type="submit" class="btn btn-add">Add Task</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Task</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
                <tr>
                    <td>
                        <strong>{{ $task->title }}</strong><br>
                        <small style="color: #777;">{{ $task->description }}</small>
                    </td>
                    <td>
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="empty">No tasks yet. Add one above!</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<a href="{{ route('profile.show') }}">View Profile</a>

</body>
</html>
