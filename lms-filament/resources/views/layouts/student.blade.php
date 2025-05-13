<!-- resources/views/layouts/student.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LMS') }} - {{ $title ?? 'Mahasiswa' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-md">
            <div class="flex items-center justify-center h-16 border-b">
                <h1 class="text-xl font-bold text-gray-800">LMS ITK</h1>
            </div>
            <nav class="mt-5 px-2">
                <a href="{{ route('student.dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('student.dashboard') ? 'bg-amber-500 text-white' : 'text-gray-600 hover:bg-amber-100 hover:text-gray-900' }}">
                    <svg class="mr-3 h-6 w-6 {{ request()->routeIs('student.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('student.courses.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('student.courses.*') ? 'bg-amber-500 text-white' : 'text-gray-600 hover:bg-amber-100 hover:text-gray-900' }}">
                    <svg class="mr-3 h-6 w-6 {{ request()->routeIs('student.courses.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Mata Kuliah
                </a>
                <a href="{{ route('student.assignments.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('student.assignments.*') ? 'bg-amber-500 text-white' : 'text-gray-600 hover:bg-amber-100 hover:text-gray-900' }}">
                    <svg class="mr-3 h-6 w-6 {{ request()->routeIs('student.assignments.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Tugas
                </a>
                <a href="{{ route('student.quizzes.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('student.quizzes.*') ? 'bg-amber-500 text-white' : 'text-gray-600 hover:bg-amber-100 hover:text-gray-900' }}">
                    <svg class="mr-3 h-6 w-6 {{ request()->routeIs('student.quizzes.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Kuis & Test
                </a>
                <a href="{{ route('student.attendances') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('student.attendances') ? 'bg-amber-500 text-white' : 'text-gray-600 hover:bg-amber-100 hover:text-gray-900' }}">
                    <svg class="mr-3 h-6 w-6 {{ request()->routeIs('student.attendances') ? 'text-white' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Presensi
                </a>
                <a href="{{ route('student.grades') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('student.grades') ? 'bg-amber-500 text-white' : 'text-gray-600 hover:bg-amber-100 hover:text-gray-900' }}">
                    <svg class="mr-3 h-6 w-6 {{ request()->routeIs('student.grades') ? 'text-white' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Nilai
                </a>
            </nav>
        </div>

        <!-- Page Content -->
        <div class="md:pl-64">
            <header class="bg-white shadow-sm h-16 flex items-center">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex justify-between items-center">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $header ?? $title ?? 'Dashboard' }}
                    </h2>
                    <div class="flex items-center">
                        <span class="text-gray-700 mr-2">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>