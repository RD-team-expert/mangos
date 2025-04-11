<!DOCTYPE html>
<html>
<head>
    <title>Tasks - {{ ucfirst(str_replace('_', ' ', $section)) }}</title>
    <style>
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .task { padding: 15px; margin: 10px 0; background: #f9f9f9; }
        .upload-form { margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ ucfirst(str_replace('_', ' ', $section)) }} Tasks</h1>
        @if (session('message'))
            <script>alert("{{ session('message') }}")</script>
        @endif
        
        @foreach ($tasks as $task)
            <div class="task">
                <form action="{{ route('tasks.update', $task) }}" method="POST">
                    @csrf
                    <h3>{{ $task->name }}</h3>
                    <p>{{ $task->description }}</p>
                    <select name="language" onchange="this.form.submit()">
                        <option value="english" {{ $task->language === 'english' ? 'selected' : '' }}>English</option>
                        <option value="arabic" {{ $task->language === 'arabic' ? 'selected' : '' }}>Arabic</option>
                    </select>
                    <input type="checkbox" name="is_completed" {{ $task->is_completed ? 'checked' : '' }} onchange="this.form.submit()">
                    <label>Completed</label>
                </form>

                @if ($task->is_completed && ($section === 'open' || $section === 'close'))
                    <form class="upload-form" action="{{ route('tasks.upload', $task) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="image" accept="image/*">
                        <button type="submit">Upload Image</button>
                    </form>
                @endif
            </div>
        @endforeach
        <a href="{{ route('sections') }}">Back to Sections</a>
    </div>
</body>
</html>