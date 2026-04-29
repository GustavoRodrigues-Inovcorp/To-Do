@extends('layouts.app')
@section('title', 'Tarefas')

@section('content')

    {{-- Filtros --}}
    <form method="GET" action="{{ route('tasks.index') }}"
          class="flex flex-wrap gap-3 mb-6">

        <select name="status"
                class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Todos os estados</option>
            <option value="pending"     {{ request('status') === 'pending'     ? 'selected' : '' }}>Pendente</option>
            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Em progresso</option>
            <option value="completed"   {{ request('status') === 'completed'   ? 'selected' : '' }}>Concluída</option>
        </select>

        <select name="priority"
                class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Todas as prioridades</option>
            <option value="high"   {{ request('priority') === 'high'   ? 'selected' : '' }}>Alta</option>
            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Média</option>
            <option value="low"    {{ request('priority') === 'low'    ? 'selected' : '' }}>Baixa</option>
        </select>

        <input type="date" name="due_date" value="{{ request('due_date') }}"
               class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">

        <button type="submit"
                class="text-sm bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
            Filtrar
        </button>

        @if(request()->hasAny(['status', 'priority', 'due_date']))
            <a href="{{ route('tasks.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2">
                Limpar
            </a>
        @endif
    </form>

    {{-- Lista de tarefas --}}
    @forelse($tasks as $task)
        <div class="bg-white border border-gray-200 rounded-xl px-5 py-4 mb-3 flex items-start gap-4
                    {{ $task->status === 'completed' ? 'opacity-60' : '' }}">

            {{-- Toggle de status rápido --}}
            <form method="POST" action="{{ route('tasks.updateStatus', $task) }}" class="mt-0.5">
                @csrf @method('PATCH')
                <input type="hidden" name="status"
                       value="{{ $task->status === 'completed' ? 'pending' : 'completed' }}">
                <button type="submit"
                        class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition
                               {{ $task->status === 'completed'
                                    ? 'bg-blue-500 border-blue-500'
                                    : 'border-gray-300 hover:border-blue-400' }}">
                    @if($task->status === 'completed')
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    @endif
                </button>
            </form>

            {{-- Conteúdo --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('tasks.show', $task) }}"
                       class="font-medium text-gray-800 hover:text-blue-600 truncate
                              {{ $task->status === 'completed' ? 'line-through text-gray-400' : '' }}">
                        {{ $task->title }}
                    </a>
                    <x-priority-badge :priority="$task->priority"/>
                    <x-status-badge :status="$task->status"/>
                </div>

                @if($task->description)
                    <p class="text-sm text-gray-500 mt-1 truncate">{{ $task->description }}</p>
                @endif

                @if($task->due_date)
                    <p class="text-xs text-gray-400 mt-1">
                        Vence em {{ $task->due_date->format('d/m/Y') }}
                        @if($task->due_date->isPast() && $task->status !== 'completed')
                            <span class="text-red-500 font-medium">(em atraso)</span>
                        @endif
                    </p>
                @endif
            </div>

            {{-- Ações --}}
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('tasks.edit', $task) }}"
                   class="text-xs text-gray-500 hover:text-gray-700 px-2 py-1 rounded hover:bg-gray-100 transition">
                    Editar
                </a>
                <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                      onsubmit="return confirm('Eliminar esta tarefa?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="text-xs text-red-400 hover:text-red-600 px-2 py-1 rounded hover:bg-red-50 transition">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>

    @empty
        <div class="text-center py-16 text-gray-400">
            <p class="text-lg">Nenhuma tarefa encontrada.</p>
            <a href="{{ route('tasks.create') }}" class="text-blue-500 text-sm mt-2 inline-block hover:underline">
                Criar primeira tarefa
            </a>
        </div>
    @endforelse

    {{-- Paginação --}}
    <div class="mt-6">
        {{ $tasks->withQueryString()->links() }}
    </div>

@endsection