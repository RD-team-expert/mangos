<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarCollapsed: false, notificationsOpen: false }" x-init="darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark'); $watch('darkMode', value => { localStorage.setItem('darkMode', value); document.documentElement.classList.toggle('dark', value); })">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mangos Inventory')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
    <style>
        :root {
            --bg-body: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            --bg-sidebar: linear-gradient(180deg, #1e3a8a 0%, #3b82f6 100%);
            --bg-sidebar-active: #2563eb;
            --bg-sidebar-mobile-collapsed: #3b82f6;
            --bg-notification-btn: #3b82f6;
            --bg-notification-btn-hover: #2563eb;
            --bg-logout: #dc2626;
            --bg-logout-hover: #b91c1c;
            --text-primary: #1f2937;
            --text-secondary: #e5e7eb;
            --text-sidebar: #ffffff;
            --scrollbar-thumb: #2563eb;
            --scrollbar-thumb-hover: #1e40af;
            --scrollbar-track: rgba(255, 255, 255, 0.1);
            --bg-footer: #ffffff;
        }

        .dark {
            --bg-body: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            --bg-sidebar: linear-gradient(180deg, #1f2937 0%, #374151 100%);
            --bg-sidebar-mobile-collapsed: #1e3a8a;
            --bg-sidebar-active: #4b5563;
            --bg-notification-btn: #4b5563;
            --bg-notification-btn-hover: #6b7280;
            --bg-logout: #991b1b;
            --bg-logout-hover: #7f1d1d;
            --text-primary: #e5e7eb;
            --text-secondary: #9ca3af;
            --text-sidebar: #d1d5db;
            --scrollbar-thumb: #4b5563;
            --scrollbar-thumb-hover: #6b7280;
            --scrollbar-track: rgba(255, 255, 255, 0.05);
            --bg-footer: #1f2937;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-body);
            color: var(--text-primary);
            transition: background 0.3s ease, color 0.3s ease;
        }

        @keyframes slideInLeft {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes fadeInUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes scaleIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(90deg); }
        }

        .sidebar {
            width: 260px;
            background: var(--bg-sidebar);
            color: var(--text-sidebar);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            transition: width 0.3s ease, transform 0.3s ease;
            animation: slideInLeft 0.5s ease-out;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            z-index: 1000; /* Ensure sidebar is above content on mobile */
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar-header {
            padding: 1rem; /* Reduced padding for mobile */
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .toggle-sidebar, .dark-mode-toggle {
            background: none;
            border: none;
            color: var(--text-sidebar);
            cursor: pointer;
            transition: transform 0.3s ease, color 0.3s ease;
            padding: 0.5rem; /* Larger touch target */
        }

        .toggle-sidebar.active {
            animation: rotate 0.3s ease forwards;
        }

        .sidebar-nav {
            padding: 1rem; /* Reduced padding for mobile */
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex-grow: 1;
            overflow-y: auto;
            scroll-behavior: smooth;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 8px;

        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: var(--scrollbar-track);
            border-radius: 4px;

        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: var(--scrollbar-thumb);
            border-radius: 4px;

        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: var(--scrollbar-thumb-hover);

        }

        .sidebar-nav {
            scrollbar-width: thin;
            scrollbar-color: var(--scrollbar-thumb) var(--scrollbar-track);

        }

        .sidebar-nav a, .sidebar-nav button {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-sidebar);
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.2s ease, transform 0.2s ease;
            font-size: 0.95rem;

        }

        .sidebar-nav a:hover, .sidebar-nav button:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
            animation: bounce 0.4s ease;
        }

        .sidebar-nav a.active {
            background: var(--bg-sidebar-active);
            color: var(--text-sidebar);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .sidebar-nav i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }

        .sidebar-nav span {
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .sidebar-nav span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .logout-btn {
            margin-top: auto;
            background: var(--bg-logout);
            color: var(--text-sidebar);
            flex-shrink: 0;
            padding: 0.75rem 1rem; /* Larger touch target */
        }

        .logout-btn:hover {
            background: var(--bg-logout-hover);
        }

        .content {
            margin-left: 260px;
            padding: 1rem; /* Reduced padding for mobile */
            width: calc(100% - 260px);
            transition: margin-left 0.3s ease, width 0.3s ease;
            animation: fadeInUp 0.5s ease-out;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .sidebar.collapsed~.content {
            margin-left: 80px;
            width: calc(100% - 80px);
        }

        main {
            flex-grow: 1;
        }

        footer {
            background: var(--bg-footer);
            color: var(--text-primary);
            padding: 0.75rem; /* Reduced padding for mobile */
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            transition: background 0.3s ease, color 0.3s ease;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0; /* Hidden by default on mobile */
                transform: translateX(-100%);

            }

            .sidebar.active {
                width: 260px;
                transform: translateX(0);

            }

            .content {
                margin-left: 0;
                width: 100%;
                padding: 1rem;
            }

            .sidebar-header {
                padding: 1rem;
            }

            .sidebar-nav {
                padding: 1rem;
            }

            .sidebar-nav span {
                opacity: 0;
                width: 0;
                overflow: hidden;
            }

            .sidebar-nav a, .sidebar-nav button {
                padding: 1rem; /* Larger touch target on mobile */
            }

            .logout-btn {
                padding: 1rem; /* Larger touch target on mobile */
            }

            footer {
                text-align: center;
                padding: 0.75rem;
            }

            /* Toggle button for mobile */
            .mobile-toggle {
                display: block;
                position: fixed;
                top: 1rem;
                left: 1rem;
                background: var(--bg-sidebar);
                border: none;
                color: var(--text-sidebar);
                padding: 0.75rem;
                border-radius: 8px;
                z-index: 1001;
                cursor: pointer;
            }

            .mobile-toggle.hidden {
                display: none;
            }
        }

        @media (min-width: 769px) {
            .mobile-toggle {
                display: none;
            }
        }
    </style>
</head>
<body class="flex min-h-screen">
<button class="mobile-toggle" @click="sidebarCollapsed = !sidebarCollapsed" x-show="window.innerWidth <= 768">
    <i class="fas fa-bars text-white"></i>
</button>
<div class="sidebar" :class="{ collapsed: sidebarCollapsed, active: sidebarCollapsed && window.innerWidth <= 768 }">
    <div class="sidebar-header">
        <div class="flex flex-col items-center justify-between">
            <img src="{{ asset('storage/logo/mangos-logo--.png') }}" alt="Mangos Inventory Logo" class="h-24 w-auto md:h-40">
            <div class="flex items-center space-x-2 mt-2">
                <button class="dark-mode-toggle" @click="darkMode = !darkMode">
                    <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'" class="text-lg"></i>
                </button>
                <button class="toggle-sidebar" @click="sidebarCollapsed = !sidebarCollapsed" :class="{ 'active': sidebarCollapsed }">
                    <i class="fas fa-bars text-white"></i>
                </button>
            </div>
        </div>
        @auth
            <div class="flex flex-col space-y-3 mt-4 text-center">
                <span class="text-sm user-info" x-show="!sidebarCollapsed">{{ auth()->user()->username }}</span>
            </div>
        @endauth
    </div>
    <div class="sidebar-nav">
        @auth
                <!-- User-specific routes -->
                <a href="{{ route('inventory.index') }}" class="{{ request()->routeIs('inventory.index') ? 'active' : '' }}">
                    <i class="fas fa-warehouse"></i>
                    <span x-show="!sidebarCollapsed">Inventory</span>
                </a>
{{--                <a href="{{ route('tasks.sections') }}" class="{{ request()->routeIs('tasks.*') ? 'active' : '' }}">--}}
{{--                    <i class="fas fa-tasks"></i>--}}
{{--                    <span x-show="!sidebarCollapsed">My Sections</span>--}}
{{--                </a>--}}

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    <span x-show="!sidebarCollapsed">Logout</span>
                </button>
            </form>
        @endauth
    </div>
</div>
<div class="content">
    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>
    <footer class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <div class="flex items-center space-x-2">
            <span class="text-gray-500 dark:text-gray-400 text-sm">© {{ date('Y') }} Mangos . All rights reserved.</span>
        </div>
        <span class="text-gray-500 dark:text-gray-400 text-sm">Made by R&D with ❤️</span>
    </footer>
</div>
</body>
</html>
