<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
    x-data="{ 
        darkMode: localStorage.getItem('darkMode') === 'true', 
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        isMobile: window.innerWidth <= 768,
        notificationsOpen: false 
    }" 
    x-init="
        darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark'); 
        $watch('darkMode', value => { 
            localStorage.setItem('darkMode', value); 
            document.documentElement.classList.toggle('dark', value); 
        }); 
        $watch('sidebarCollapsed', value => { 
            localStorage.setItem('sidebarCollapsed', value); 
        });
        window.addEventListener('resize', () => { isMobile = window.innerWidth <= 768; });
    ">
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
            overflow-x: hidden; /* Prevent horizontal scrolling */
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
            transition: all 0.3s ease;
            animation: slideInLeft 0.5s ease-out;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar-header {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .sidebar-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            transition: all 0.3s ease;
        }

        .sidebar-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.75rem;
            margin-top: 1rem;
            width: 100%;
        }

        .toggle-sidebar, .dark-mode-toggle {
            background: none;
            border: none;
            color: var(--text-sidebar);
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 0.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
        }

        .toggle-sidebar:hover, .dark-mode-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }

        .toggle-sidebar.active i {
            transform: rotate(180deg);
        }

        .sidebar-nav {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            flex-grow: 1;
            overflow-y: auto;
            scroll-behavior: smooth;
            align-items: center;
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
            transition: all 0.2s ease;
            font-size: 0.95rem;
            white-space: nowrap;
            width: 100%;
            justify-content: flex-start;
        }

        .sidebar.collapsed .sidebar-nav a, 
        .sidebar.collapsed .sidebar-nav button {
            justify-content: center;
            padding: 0.75rem;
        }

        .sidebar-nav a:hover, .sidebar-nav button:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .sidebar-nav a.active {
            background: var(--bg-sidebar-active);
            color: var(--text-sidebar);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            font-weight: 500;
        }

        .sidebar-nav i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
            min-width: 1.5rem;
            text-align: center;
        }

        .sidebar.collapsed .sidebar-nav i {
            margin-right: 0;
        }

        .sidebar-nav span {
            transition: opacity 0.3s ease, width 0.3s ease;
        }

        .sidebar.collapsed .sidebar-nav span {
            opacity: 0;
            width: 0;
            overflow: hidden;
            display: none;
        }

        .user-info-container {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 0.75rem;
        }

        .user-info {
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            display: inline-block;
        }

        .logout-container {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: auto;
        }

        .logout-btn {
            background: var(--bg-logout);
            color: var(--text-sidebar);
            flex-shrink: 0;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin: 1rem 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease;
            width: 90%;
        }

        .sidebar.collapsed .logout-btn {
            width: 50px;
            height: 50px;
            padding: 0;
            border-radius: 50%;
        }

        .logout-btn:hover {
            background: var(--bg-logout-hover);
        }

        .content {
            margin-left: 260px;
            padding: 1rem;
            width: calc(100% - 260px);
            transition: margin-left 0.3s ease, width 0.3s ease;
            animation: fadeInUp 0.5s ease-out;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .sidebar.collapsed ~ .content {
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
                transform: translateX(-100%);
                width: 260px;
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: 1050;
            }

            .sidebar.active {
                transform: translateX(0);
                width: 260px;
            }

            .sidebar.collapsed:not(.active) {
                transform: translateX(-100%);
                width: 80px;
            }

            .content {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 1rem;
                transition: all 0.3s ease;
            }

            body.sidebar-open {
                overflow: hidden;
            }

            .sidebar-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }

            .sidebar-backdrop.active {
                opacity: 1;
                visibility: visible;
            }

            .mobile-toggle {
                display: block;
                position: fixed;
                top: 1rem;
                left: 1rem;
                background: var(--bg-sidebar-mobile-collapsed);
                border: none;
                color: var(--text-sidebar);
                padding: 0.75rem;
                border-radius: 8px;
                z-index: 1051;
                cursor: pointer;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
                transition: all 0.3s ease;
            }

            .mobile-toggle:hover {
                background: var(--bg-sidebar-active);
            }

            .mobile-toggle.active {
                left: 280px;
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
<body class="flex min-h-screen" :class="{'sidebar-open': !sidebarCollapsed && isMobile}">
<!-- Backdrop for mobile -->
<div class="sidebar-backdrop" 
     :class="{'active': !sidebarCollapsed && isMobile}" 
     @click="sidebarCollapsed = true">
</div>

<button class="mobile-toggle" 
        :class="{'active': !sidebarCollapsed && isMobile}"
        @click="sidebarCollapsed = !sidebarCollapsed" 
        x-show="isMobile">
    <i class="fas" :class="sidebarCollapsed ? 'fa-bars' : 'fa-times'" class="text-white"></i>
</button>

<div class="sidebar" 
     :class="{ 
        'collapsed': sidebarCollapsed && !isMobile, 
        'active': !sidebarCollapsed && isMobile 
     }">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="{{ asset('storage/logo/mangos-logo--.png') }}" alt="Mangos Inventory Logo" 
                 class="h-20 w-auto md:h-24 transition-all duration-300"
                 :class="{'h-16': sidebarCollapsed && !isMobile}">
        </div>
        <div class="sidebar-controls">
            <button class="dark-mode-toggle" @click="darkMode = !darkMode" title="Toggle Dark Mode">
                <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'" class="text-lg"></i>
            </button>
            <button class="toggle-sidebar" 
                    @click="sidebarCollapsed = !sidebarCollapsed" 
                    :class="{ 'active': sidebarCollapsed }" 
                    title="Toggle Sidebar"
                    x-show="!isMobile">
                <i class="fas fa-chevron-left text-white transition-transform duration-300"></i>
            </button>
        </div>
        @auth
            <div class="user-info-container" x-show="!sidebarCollapsed || isMobile">
                <span class="text-sm font-medium user-info">{{ auth()->user()->username }}</span>
            </div>
        @endauth
    </div>
    <div class="sidebar-nav">
        @auth
            <!-- User-specific routes -->
            <a href="{{ route('inventory.index') }}" class="{{ request()->routeIs('inventory.index') ? 'active' : '' }}">
                <i class="fas fa-warehouse"></i>
                <span x-show="!sidebarCollapsed || isMobile">Inventory</span>
            </a>
            {{-- <a href="{{ route('tasks.sections') }}" class="{{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                <i class="fas fa-tasks"></i>
                <span x-show="!sidebarCollapsed || isMobile">My Sections</span>
            </a> --}}

            <div class="logout-container">
                <form method="POST" action="{{ route('logout') }}" class="w-full flex justify-center">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt" :class="{'mr-2': !sidebarCollapsed || isMobile}"></i>
                        <span x-show="!sidebarCollapsed || isMobile">Logout</span>
                    </button>
                </form>
            </div>
        @endauth
    </div>
</div>

<div class="content" :class="{'pl-0': isMobile}">
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

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('sidebar', {
            init() {
                // Check if we're on mobile on page load
                this.checkMobile();
                
                // Add resize listener
                window.addEventListener('resize', this.checkMobile.bind(this));
            },
            
            checkMobile() {
                const isMobile = window.innerWidth <= 768;
                
                // If on mobile, ensure sidebar is collapsed by default
                if (isMobile && !Alpine.$data(document.body).sidebarInitialized) {
                    Alpine.$data(document.body).sidebarCollapsed = true;
                    Alpine.$data(document.body).sidebarInitialized = true;
                }
            }
        });
        
        // Initialize the store
        Alpine.store('sidebar').init();
    });
</script>
</body>
</html>
