<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} — @yield('title', 'Tarefas')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <a href="{{ route('tasks.index') }}" class="text-lg font-semibold text-gray-800">
                To-Do
            </a>
            <a href="{{ route('tasks.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                + Nova tarefa
            </a>
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="max-w-4xl mx-auto mt-4 px-6">
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- Conteúdo --}}
    <main class="max-w-4xl mx-auto px-6 py-8">
        @yield('content')
    </main>

</body>
</html>