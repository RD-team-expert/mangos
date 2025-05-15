<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark'); $watch('darkMode', value => { localStorage.setItem('darkMode', value); document.documentElement.classList.toggle('dark', value); })">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mangos Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --bg-body: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            --text-primary: #1f2937;
            --text-secondary: #e5e7eb;
            --bg-button: #3b82f6;
            --bg-button-hover: #2563eb;
        }

        .dark {
            --bg-body: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            --text-primary: #e5e7eb;
            --text-secondary: #9ca3af;
            --bg-button: #4b5563;
            --bg-button-hover: #6b7280;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-body);
            color: var(--text-primary);
            transition: background 0.3s ease, color 0.3s ease;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .login-container {
            animation: fadeInUp 0.5s ease-out;
        }

        .dark-mode-toggle {
            background: none;
            border: none;
            color: var(--text-primary);
            cursor: pointer;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .dark-mode-toggle:hover {
            transform: scale(1.1);
        }

        input {
            transition: background 0.3s ease, color 0.3s ease, border 0.3s ease;
        }

        .dark input {
            background: #374151;
            color: #e5e7eb;
            border-color: #4b5563;
        }

        .dark input:focus {
            border-color: #2563eb;
            ring-color: #2563eb;
        }

        button[type="submit"] {
            background: var(--bg-button);
            transition: background 0.2s ease, transform 0.2s ease;
        }

        button[type="submit"]:hover {
            background: var(--bg-button-hover);
            transform: scale(1.02);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
<div class="login-container bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md relative">
    <!-- Dark Mode Toggle -->
    <button class="dark-mode-toggle absolute top-4 right-4" @click="darkMode = !darkMode">
        <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'" class="text-lg"></i>
    </button>
    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100 text-center">Login to Mangos Inventory</h2>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <!-- Email Input -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Enter your email"
                required
            >
            @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <!-- Password Input -->
        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Enter your password"
                required
            >
            @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <!-- Submit Button -->
        <div>
            <button
                type="submit"
                class="w-full text-white py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-blue-500"
            >
                Login
            </button>
        </div>
    </form>

</div>
</body>
</html>
