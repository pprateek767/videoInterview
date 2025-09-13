<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hireflix')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine CDN for small widgets (optional if installed via npm) -->
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body class="bg-gray-50 text-gray-800">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-4">
                    <a href="{{ route('interviews.index') }}" class="flex items-center gap-2">
                        <div
                            class="w-10 h-10 bg-indigo-600 rounded-md flex items-center justify-center text-white font-bold">
                            HF</div>
                        <div>
                            <div class="text-lg font-semibold">Hireflix</div>
                            <div class="text-xs text-gray-500">One-way video interviews</div>
                        </div>
                    </a>
                </div>

                <div class="flex items-center gap-4">
                    <form action="{{ route('interviews.index') }}" method="GET" class="hidden sm:block">
                        <div class="relative">
                            <input name="q" value="{{ request('q') }}" placeholder="Search interviews..."
                                class="w-72 pl-3 pr-10 py-2 rounded-md border border-gray-200 bg-white text-sm focus:ring-1 focus:ring-indigo-400" />
                            <button type="submit"
                                class="absolute right-1 top-1/2 -translate-y-1/2 p-1.5 rounded-md text-gray-500 hover:bg-gray-100">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z" />
                                </svg>
                            </button>
                        </div>
                    </form>

                    @auth
                    <a href="{{ route('interviews.create') }}"
                        class="hidden sm:inline-flex items-center gap-2 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-md">Create
                        Interview</a>

                    <!-- user dropdown -->
                    <div x-data="{ open:false }" class="relative">
                        <button @click="open = !open"
                            class="flex items-center gap-2 border border-gray-200 rounded-md px-3 py-1.5 bg-white">
                            <div
                                class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-sm font-medium text-gray-700">
                                {{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                            <div class="text-sm text-gray-700">{{ auth()->user()->name }}</div>
                            <svg class="w-4 h-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414L10 13.414 5.293 8.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open=false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-md shadow-lg py-1 z-50">
                            <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" href="#">Profile</a>
                            <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" href="#">Settings</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">Logout</button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Login</a>
                    <a href="{{ route('register') }}" class="ml-2 text-sm text-indigo-600 hover:underline">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- flash -->
            @if(session('success'))
            <div class="mb-4 p-4 rounded-md bg-green-50 border border-green-100 text-green-700">{{ session('success') }}
            </div>
            @endif
            @yield('content')
        </div>
    </main>

    <footer class="bg-white border-t mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-sm text-gray-500">
            © {{ date('Y') }} Hireflix clone — built with ❤️
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
