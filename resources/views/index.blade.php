<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mangos Inventory - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
<!-- Header -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900">Mangos Inventory</h1>
    </div>
</header>

<!-- Main Content -->
<main>
    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Category Dropdown -->
        <div x-data="{ selectedCategory: '' }" class="mb-8">
            <label for="category" class="block text-sm font-medium text-gray-700">Select Category</label>
            <select id="category" x-model="selectedCategory" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">-- Select a Category --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Items Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($items as $item)
                <!-- Show items only if they belong to the selected category -->
                <div x-show="selectedCategory === '' || selectedCategory === '{{ $item->category_id }}'" class="bg-white shadow-md rounded-lg overflow-hidden">
                    <!-- Item Image -->
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-48 object-cover">

                    <!-- Item Details -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $item->name }}</h3>
                        <p class="text-sm text-gray-500">Available: {{ $item->quantity }}</p>
                    </div>

                    <!-- Borrow Button -->
                    <div class="p-4 bg-gray-50">
                        <form action="{{ route('items.borrow', $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Borrow Item
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="bg-white shadow mt-6">
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-gray-500">
        &copy; {{ date('Y') }} Mangos Inventory. All rights reserved.
    </div>
</footer>
</body>
</html>
