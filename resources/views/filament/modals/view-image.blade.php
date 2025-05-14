<div class="p-4">
    @if (!empty($images))
        <div class="grid grid-cols-1 gap-4">
            @foreach ($images as $image)
                <img src="{{ asset('storage/'. $image) }}" alt="Task1 Image" class="w-full h-auto rounded-lg shadow-md">
            @endforeach
        </div>
    @else
        <p>No images available.</p>
    @endif
</div>
