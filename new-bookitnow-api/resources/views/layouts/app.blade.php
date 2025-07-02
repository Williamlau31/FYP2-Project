<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BookItNow') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Custom scrollbar for better aesthetics */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" xintegrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0V4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Alpine.js for simple interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 antialiased">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation Bar -->
        <nav class="bg-white shadow-md py-4">
            <div class="container mx-auto px-4 flex justify-between items-center">
                <a href="{{ Auth::check() ? route('dashboard') : route('login') }}" class="text-2xl font-bold text-blue-600">BookItNow</a>

                <div class="flex items-center space-x-6">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 transition duration-300">
                            <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                        </a>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('patients.index') }}" class="text-gray-700 hover:text-blue-600 transition duration-300">
                                <i class="fas fa-users mr-1"></i> Patients
                            </a>
                            <a href="{{ route('staff.index') }}" class="text-gray-700 hover:text-blue-600 transition duration-300">
                                <i class="fas fa-user-md mr-1"></i> Staff
                            </a>
                        @endif
                        <a href="{{ route('appointments.index') }}" class="text-gray-700 hover:text-blue-600 transition duration-300">
                            <i class="fas fa-calendar-check mr-1"></i> Appointments
                        </a>
                        <a href="{{ route('queue.index') }}" class="text-gray-700 hover:text-blue-600 transition duration-300">
                            <i class="fas fa-list-ol mr-1"></i> Queue
                        </a>
                        <a href="{{ route('payments.index') }}" class="text-gray-700 hover:text-blue-600 transition duration-300">
                            <i class="fas fa-credit-card mr-1"></i> Payments
                        </a>

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-gray-700 hover:text-blue-600 focus:outline-none transition duration-300">
                                <i class="fas fa-user-circle mr-2 text-xl"></i> {{ Auth::user()->name }}
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile (Coming Soon)</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition duration-300">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300">Register</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="flex-grow container mx-auto px-4 py-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none';">
                            <title>Close</title>
                            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697L11.819 10l3.029 2.651a1.2 1.2 0 0 1 0 1.698z"/>
                        </svg>
                    </span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none';">
                            <title>Close</title>
                            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697L11.819 10l3.029 2.651a1.2 1.2 0 0 1 0 1.698z"/>
                        </svg>
                    </span>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white shadow-inner py-6 mt-8">
            <div class="container mx-auto px-4 text-center text-gray-600">
                &copy; {{ date('Y') }} BookItNow. All rights reserved.
            </div>
        </footer>
    </div>
</body>
</html>