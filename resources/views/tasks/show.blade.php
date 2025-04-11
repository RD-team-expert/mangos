<!DOCTYPE html>
<html>
<head>
    <title>Task Details - {{ $task->name }}</title>
    <style>
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .task { padding: 20px; background: #f9f9f9; border-radius: 5px; }
        .task h2 { margin-top: 0; }
        .status { margin: 15px 0; }
        .upload-form { margin-top: 20px; }
        .images { margin-top: 20px; }
        .image { max-width: 200px; margin: 10px; }
        .back-link { display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
<div class="container">
    <div class="task">
        <h2>{{ $task->name }}</h2>
        <p><strong>Description:</strong> {{ $task->description }}</p>
        <p><strong>Section:</strong> {{ ucfirst(str_replace('_', ' ', $task->section)) }}</p>
        <p><strong>Language:</strong> {{ ucfirst($task->language) }}</p>
        <p><strong>Status:</strong> {{ $task->is_completed ? 'Completed' : 'Not Completed' }}</p>

        <div class="status">
            <form action="{{ route('tasks.status', $task) }}" method="POST">
                @csrf
                <select name="language" onchange="this.form.submit()">
                    <option value="english" {{ $task->language === 'english' ? 'selected' : '' }}>English</option>
                    <option value="arabic" {{ $task->language === 'arabic' ? 'selected' : '' }}>Arabic</option>
                </select>
                <input type="checkbox" name="is_completed" {{ $task->is_completed ? 'checked' : '' }} onchange="this.form.submit()">
                <label>Mark as Completed</label>
            </form>
        </div>

        @if ($task->is_completed && in_array($task->section, ['open', 'close']))
            <div class="upload-form">
                <form action="{{ route('tasks.upload', $task) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="image" accept="image/*" required>
                    <button type="submit">Upload Image</button>
                </form>
            </div>
        @endif

        @if ($task->images->count() > 0)
            <div class="images">
                <h3>Uploaded Images</h3>
                @foreach ($task->images as $image)
                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Task Image" class="image">
                @endforeach
            </div>
        @endif
    </div>

    <a href="{{ route('tasks.section', $task->section) }}" class="back-link">Back to {{ ucfirst(str_replace('_', ' ', $task->section)) }} Tasks</a>
</div>

@if (session('success'))
    <script>alert("{{ session('success') }}")</script>
@endif
</body>
</html>
