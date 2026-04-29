@extends('layouts.app')
@section('title', 'Todas as tarefas')

@section('content')

{{-- Header da página --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm text-gray-400 mt-0.5">
            {{ $tasks->total() }} {{ $tasks->total() === 1 ? 'tarefa' : 'tarefas' }}
        </p>
    </div>

    {{-- Filtro de data --}}
    <form method="GET" action="{{ route('tasks.index') }}" class="flex items-center gap-2">
        @foreach(request()->except('due_date') as $key => $val)
            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
        @endforeach
        <input type="date" name="due_date" value="{{ request('due_date') }}"
               onchange="this.form.submit()"
               class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
        @if(request('due_date'))
            <a href="{{ route('tasks.index', request()->except('due_date')) }}"
               class="text-xs text-gray-400 hover:text-gray-600">✕</a>
        @endif
    </form>
</div>

{{-- Lista --}}
<div class="space-y-2">
    @forelse($tasks as $task)
        <div class="bg-white rounded-xl border border-gray-100 hover:border-gray-200
                    hover:shadow-sm transition-all duration-150 group
                    {{ $task->status === 'completed' ? 'opacity-60' : '' }}">
            <div class="flex items-center gap-4 px-5 py-4">

                {{-- Checkbox / Toggle --}}
                <form method="POST" action="{{ route('tasks.updateStatus', $task) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status"
                           value="{{ $task->status === 'completed' ? 'pending' : 'completed' }}">
                    <button type="submit"
                            class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                   shrink-0 transition-all duration-150
                                   {{ $task->status === 'completed'
                                        ? 'bg-blue-500 border-blue-500'
                                        : 'border-gray-300 hover:border-blue-400' }}">
                        @if($task->status === 'completed')
                            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        @endif
                    </button>
                </form>

                {{-- Conteúdo clicável --}}
                <a href="{{ route('tasks.show', $task) }}" class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-medium text-gray-800 truncate
                                     {{ $task->status === 'completed' ? 'line-through text-gray-400' : '' }}">
                            {{ $task->title }}
                        </span>

                        {{-- Prioridade --}}
                        @php
                            $pColors = ['high'=>'bg-red-100 text-red-600','medium'=>'bg-yellow-100 text-yellow-600','low'=>'bg-green-100 text-green-600'];
                            $pLabels = ['high'=>'Alta','medium'=>'Média','low'=>'Baixa'];
                        @endphp
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $pColors[$task->priority] ?? '' }}">
                            {{ $pLabels[$task->priority] ?? '' }}
                        </span>

                        {{-- Status --}}
                        @php
                            $sColors = ['pending'=>'bg-gray-100 text-gray-500','completed'=>'bg-blue-100 text-blue-600'];
                            $sLabels = ['pending'=>'Pendente','completed'=>'Concluída'];
                        @endphp
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $sColors[$task->status] ?? '' }}">
                            {{ $sLabels[$task->status] ?? '' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-3 mt-1">
                        @if($task->description)
                            <p class="text-sm text-gray-400 truncate max-w-md">{{ $task->description }}</p>
                        @endif
                        @if($task->due_date)
                            <span class="text-xs text-gray-400 shrink-0 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $task->due_date->format('d/m/Y') }}
                                @if($task->due_date->isPast() && $task->status !== 'completed')
                                    <span class="text-red-400 font-medium">· atraso</span>
                                @endif
                            </span>
                        @endif
                    </div>
                </a>

                {{-- Ações --}}
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity shrink-0">
                    <a href="{{ route('tasks.edit', $task) }}"
                       class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                          onsubmit="return confirm('Eliminar esta tarefa?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    @empty
        <div class="text-center py-20">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-gray-400 font-medium">Nenhuma tarefa encontrada</p>
            <a href="{{ route('tasks.create') }}"
               class="inline-block mt-3 text-sm text-blue-500 hover:text-blue-700 font-medium">
                + Criar primeira tarefa
            </a>
        </div>
    @endforelse
</div>

{{-- Paginação --}}
@if($tasks->hasPages())
    <div class="mt-6">{{ $tasks->withQueryString()->links() }}</div>
@endif

@endsection