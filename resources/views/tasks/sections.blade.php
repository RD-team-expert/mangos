@extends('layouts.app')

@section('title', 'Task Sections')

<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <img src="{{ asset('storage/logo/WhatsApp Image 2025-03-13 at 17.34.05_18177d51.jpg') }}" alt="Logo" style="width: 100px; height: auto;">
        <!-- User Info -->
        @auth
            <div class="text-sm text-gray-700">
                Welcome, {{ Auth::user()->name }}
                <a href="{{ route('logout') }}" class="ml-4 text-blue-600 hover:text-blue-800"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        @else
            <div>
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 mr-4">Login</a>
            </div>
        @endauth
    </div>
</header>

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-center my-6 text-gray-800">Choose a Task Section</h1>
        <div class="bg-white shadow-lg rounded-lg p-6 max-w-3xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('tasks.section', 'open') }}"
                   class="border rounded-lg shadow-md p-4 bg-white text-center text-lg font-medium text-gray-700 hover:bg-blue-50 transition duration-300">
                    Open
                </a>
                <a href="{{ route('tasks.section', 'middle_work') }}"
                   class="border rounded-lg shadow-md p-4 bg-white text-center text-lg font-medium text-gray-700 hover:bg-blue-50 transition duration-300">
                    Middle Work
                </a>
                <a href="{{ route('tasks.section', 'close') }}"
                   class="border rounded-lg shadow-md p-4 bg-white text-center text-lg font-medium text-gray-700 hover:bg-blue-50 transition duration-300">
                    Close
                </a>
            </div>
        </div>
    </div>
@endsection
