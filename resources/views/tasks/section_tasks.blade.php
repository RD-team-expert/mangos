@extends('layouts.app')

@section('title', 'Tasks - ' . ucfirst(str_replace('_', ' ', $section)))

<header class="bg-white shadow">

</header>

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-center my-6 text-gray-800">Tasks - {{ ucfirst(str_replace('_', ' ', $section)) }}</h1>
        <div class="bg-white shadow-lg rounded-lg p-6 max-w-3xl mx-auto">
            @if (session('success'))
                <div class="mb-4 text-green-500 text-center">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6">
                @forelse($tasks as $task)
                    <div class="border rounded-lg shadow-md p-4 bg-white">
                        <h2 class="text-lg font-bold">{{ $task->name }}</h2>
                        <p class="text-gray-600 mt-1">
                            @if ($task->language === 'arabic' && !empty($task->description_ar))
                                {{ $task->description_ar }}
                            @else
                                {{ $task->description_en ?? $task->description }}
                            @endif
                        </p>
                        <div class="mt-3">
                            <form action="{{ route('tasks.status', $task) }}" method="POST">
                                @csrf
                                <div class="flex items-center space-x-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Language</label>
                                        <select name="language" onchange="this.form.submit()"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-blue-300 focus:border-blue-500">
                                            <option value="english" {{ $task->language === 'english' ? 'selected' : '' }}>English</option>
                                            <option value="arabic" {{ $task->language === 'arabic' ? 'selected' : '' }}>Arabic</option>
                                        </select>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="is_completed" {{ $task->is_completed ? 'checked' : '' }} onchange="this.form.submit()"
                                               class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <label class="ml-2 text-sm font-medium text-gray-700">Completed</label>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @if ($task->is_completed && in_array($task->section, ['open', 'close']))
                            <div class="mt-4">
                                <form id="upload-form-{{ $task->id }}" action="{{ route('tasks.upload', $task) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <label class="block text-sm font-medium text-gray-700">Take a Photo</label>
                                    <input type="file" name="image" accept="image/*" capture="environment" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                           onchange="previewImage(event, {{ $task->id }})">
                                    <div id="preview-{{ $task->id }}" class="mt-2"></div>
                                    <button type="submit" class="mt-2 bg-blue-600 text-white py-2 px-4 rounded-md shadow-md hover:bg-blue-700 transition duration-300">
                                        Upload Photo
                                    </button>
                                </form>
                            </div>
                        @endif
                        @if ($task->images->count() > 0)
                            <div class="mt-4">
                                <h3 class="text-sm font-medium text-gray-700">Uploaded Images</h3>
                                <div class="flex flex-wrap mt-2">
                                    @foreach ($task->images as $image)
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Task Image" class="w-24 h-24 object-cover rounded-md mr-2 mb-2">
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center text-gray-500">
                        No tasks found for this section.
                    </div>
                @endforelse
            </div>

            @if ($tasks->isNotEmpty())
                <div class="mt-6">
                    {{ $tasks->links() }}
                </div>
            @endif

            <a href="{{ route('tasks.sections') }}" class="mt-6 inline-block text-blue-600 hover:text-blue-800">Back to Sections</a>
        </div>
    </div>

    <script>
        // Check if the device is mobile
        const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
        const inputs = document.querySelectorAll('input[type="file"][capture]');
        if (!isMobile) {
            inputs.forEach(input => {
                input.disabled = true;
                input.insertAdjacentHTML('afterend', '<p class="text-red-500 text-sm">Camera upload available on mobile only.</p>');
            });
        }

        // Preview the captured image
        function previewImage(event, taskId) {
            const file = event.target.files[0];
            const preview = document.getElementById(`preview-${taskId}`);
            preview.innerHTML = '';

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-24 h-24 object-cover rounded-md';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
