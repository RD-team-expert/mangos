<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mangos Inventory - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
<!-- Header with Navbar -->


<!-- Main Content -->
<main>
    <div class="py-6">
        @yield('content')
    </div>
</main>

<!-- Footer (optional) -->
<footer class="bg-white shadow mt-6">
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-gray-500">
        © {{ date('Y') }} Mangos Inventory. All rights reserved.made by R&D with ❤️
    </div>
</footer>
</body>
</html>
