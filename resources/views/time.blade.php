<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Time</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .time-container {
            text-align: center;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .time {
            font-size: 2em;
            color: #333;
        }
    </style>
</head>
<body>
<div class="time-container">
    <h1>Current Time</h1>
    <p class="time">{{ $formatted_time }}</p>
    <p>Raw Datetime: {{ $current_time }}</p>
    <a href="{{ route('tasks.index') }}">View Tasks</a>
</div>
</body>
</html>
