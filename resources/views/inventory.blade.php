@extends('layouts.app')

@section('title', 'Inventory Barcoding')
<header class="bg-white shadow">

</header>
@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-center my-6 text-gray-800">Mangos Inventory</h1>
        <div class="bg-white shadow-lg rounded-lg p-6 max-w-3xl mx-auto">
            <form method="GET" action="{{ route('inventory.index') }}">
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Select Category</label>
                    <select id="category" name="category" onchange="this.form.submit()"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring focus:ring-blue-300 focus:border-blue-500">
                        <option value="">-- Choose Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $selectedCategoryId == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
            <form method="POST" action="{{ route('inventory.update') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    @forelse($items as $index => $item)
                        <div class="border rounded-lg shadow-md p-4 bg-white">
                            <img src="{{ $item['image_url'] }}" alt="Item Image" class="w-full h-40 object-center rounded-md">
                            <h2 class="text-lg font-bold mt-2">{{ $item['name'] }}</h2>
                            <label class="block text-sm font-medium text-gray-700 mt-2">We have</label>
                            <input type="number" name="items[{{ $index }}][quantity]" value="0" min="0" step="1" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3">
                            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item['id'] }}">
                            <input type="hidden" name="category" value="{{ $selectedCategoryId }}">
                        </div>
                    @empty
                        <div class="col-span-full text-center text-gray-500">
                            @if($selectedCategoryId)
                                No items found for this category.
                            @else
                                Please select a category to view items.
                            @endif
                        </div>
                    @endforelse
                </div>
                @if($items->isNotEmpty())
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 px-6 rounded-md shadow-md hover:bg-blue-700 transition duration-300 mt-6">
                        Submit Inventory
                    </button>
                @endif
            </form>
            @if(session('success'))
                <div class="mt-4 text-green-500 text-center">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>
@endsection
