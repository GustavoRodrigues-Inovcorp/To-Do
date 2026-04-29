<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly — @yield('title', 'Tarefas')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#fafafa]">

<div class="flex h-screen overflow-hidden">

    {{-- ===== SIDEBAR ===== --}}
    <aside id="sidebar"
           class="w-64 bg-[#f4f4f4] rounded-xl m-4 flex flex-col fixed inset-y-0 left-0 z-30
                  transform -translate-x-full lg:translate-x-0 transition-transform duration-200">

        {{-- Logo --}}
        <div class="px-3 py-2.5">
            <span class="text-lg font-bold text-[#444444] tracking-tight">Taskly</span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <p class="text-[0.7rem] font-semibold text-gray-600 uppercase tracking-wider px-1 mb-2">Tarefas</p>

            <a href="{{ route('tasks.upcoming') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('tasks.upcoming')
                            ? 'bg-[#ebebeb] text-[#444444]'
                            : 'text-[#444444] hover:bg-[#ebebeb]' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                Planeamento
                <span class="ml-auto text-xs bg-[#fafafa] text-[#444444] px-2 py-0.5 rounded-full">
                    {{ \App\Models\Task::whereDate('due_date', '>=', now()->toDateString())->where('status','!=','completed')->count() }}
                </span>
            </a>

            <a href="{{ route('tasks.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('tasks.index') && !request('status')
                            ? 'bg-[#ebebeb] text-[#444444]'
                            : 'text-[#444444] hover:bg-[#ebebeb]' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                Todas
                <span class="ml-auto text-xs bg-[#fafafa] text-[#444444] px-2 py-0.5 rounded-full">
                    {{ \App\Models\Task::count() }}
                </span>
            </a>

            <a href="{{ route('tasks.index', ['status' => 'pending']) }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request('status') === 'pending'
                            ? 'bg-[#ebebeb] text-[#444444]'
                            : 'text-[#444444] hover:bg-[#ebebeb]' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4l3 3"/>
                </svg>
                Pendentes
                <span class="ml-auto text-xs bg-[#fafafa] text-[#444444] px-2 py-0.5 rounded-full">
                    {{ \App\Models\Task::where('status','pending')->count() }}
                </span>
            </a>

            <a href="{{ route('tasks.index', ['status' => 'completed']) }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request('status') === 'completed'
                            ? 'bg-[#ebebeb] text-[#444444]'
                            : 'text-[#444444] hover:bg-[#ebebeb]' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Concluídas
                <span class="ml-auto text-xs bg-[#fafafa] text-[#444444] px-2 py-0.5 rounded-full">
                    {{ \App\Models\Task::where('status','completed')->count() }}
                </span>
            </a>

            <div class="border-b border-[#e8e8e8] pt-4"></div>

            <div class="pt-3">
                <p class="text-[0.7rem] font-semibold text-gray-600 uppercase tracking-wider px-1 mb-2">Prioridade</p>

                <a href="{{ route('tasks.index', ['priority' => 'high']) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                          {{ request('priority') === 'high'
                                ? 'bg-[#ebebeb] text-[#444444]'
                                : 'text-[#444444] hover:bg-[#ebebeb]' }}">
                    <span class="w-2 h-2 rounded-full bg-red-400"></span>
                    Alta
                    <span class="ml-auto text-xs bg-[#fafafa] text-[#444444] px-2 py-0.5 rounded-full">
                        {{ \App\Models\Task::where('priority','high')->count() }}
                    </span>
                </a>

                <a href="{{ route('tasks.index', ['priority' => 'medium']) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                          {{ request('priority') === 'medium'
                                ? 'bg-[#ebebeb] text-[#444444]'
                                : 'text-[#444444] hover:bg-[#ebebeb]' }}">
                    <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                    Média
                    <span class="ml-auto text-xs bg-[#fafafa] text-[#444444] px-2 py-0.5 rounded-full">
                        {{ \App\Models\Task::where('priority','medium')->count() }}
                    </span>
                </a>

                <a href="{{ route('tasks.index', ['priority' => 'low']) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                          {{ request('priority') === 'low'
                                ? 'bg-[#ebebeb] text-[#444444]'
                                : 'text-[#444444] hover:bg-[#ebebeb]' }}">
                    <span class="w-2 h-2 rounded-full bg-green-400"></span>
                    Baixa
                    <span class="ml-auto text-xs bg-[#fafafa] text-[#444444] px-2 py-0.5 rounded-full">
                        {{ \App\Models\Task::where('priority','low')->count() }}
                    </span>
                </a>
            </div>
        </nav>

        {{-- Nova tarefa (bottom) --}}
        <div class="p-4">
            <a href="{{ route('tasks.create') }}"
               class="flex items-center justify-center gap-2 w-full bg-blue-600 hover:bg-blue-700
                      text-white text-sm font-medium py-2.5 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Nova tarefa
            </a>
        </div>
    </aside>

    {{-- Overlay mobile --}}
    <div id="sidebar-overlay"
         class="fixed inset-0 bg-black bg-opacity-30 z-20 hidden lg:hidden"
         onclick="closeSidebar()"></div>

    {{-- ===== CONTEÚDO PRINCIPAL ===== --}}
    <div class="flex-1 flex flex-col min-h-screen lg:ml-64">

        {{-- Topbar --}}
        <header class="bg-[#fafafa] px-6 py-4 flex items-center gap-4 sticky top-0 z-10">
            {{-- Botão menu mobile --}}
            <button onclick="openSidebar()"
                    class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <h1 class="text-4xl font-bold text-[#212529] ml-3">@yield('title', 'Tarefas')</h1>
            
            <div class="ml-auto flex items-center gap-3">
                @if(session('success'))
                    <span class="text-sm text-green-600 bg-green-50 px-3 py-1 rounded-full">
                        {{ session('success') }}
                    </span>
                @endif
            </div>
        </header>

        {{-- Página --}}
        <main class="flex-1 p-6 overflow-y-auto">
            @yield('content')
        </main>
    </div>
</div>

<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.remove('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.remove('hidden');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.add('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.add('hidden');
    }
</script>

</body>
</html>