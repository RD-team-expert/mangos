@extends('layouts.app')

@section('title', 'Task Sections')



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
