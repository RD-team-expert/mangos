<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cron Job Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f0f0f0; }
        .container { max-width: 800px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .message { font-size: 1.2em; color: #333; margin-bottom: 20px; }
        .success { color: green; }
        .error { color: red; }
        .task { margin: 10px 0; padding: 10px; border-bottom: 1px solid #ddd; }
        .time { font-style: italic; color: #666; }
    </style>
</head>
<body>
<div class="container">
    <h1>Cron Job Test</h1>
    <p class="time">Current Time: {{ $current_time }}</p>
    <p class="message {{ strpos($message, 'successful') !== false ? 'success' : 'error' }}">
        {{ $message }}
    </p>
    @if ($tasks->isNotEmpty())
        <h2>Task Status</h2>
        @foreach ($tasks as $task)
            <div class="task">
                <p><strong>Task #{{ $task->id }}</strong> ({{ $task->is_daily ? 'Daily' : 'One-time' }})</p>
                <p>Status: {{ $task->is_completed ? 'Completed' : 'Pending' }}</p>
            </div>
        @endforeach
        <p>Remaining Images: {{ $image_count }}</p>
    @else
        <p>No daily tasks found.</p>
    @endif
    <a href="{{ route('tasks.index') }}">Back to Tasks</a>
</div>
</body>
</html>
