@extends('layouts.app')
@section('title', $task->title)

@section('content')
    <div class="max-w-xl">
        <div class="flex items-center gap-3 mb-6">
            <x-priority-badge :priority="$task->priority"/>
            <x-status-badge :status="$task->status"/>
            @if($task->due_date)
                <span class="text-xs text-gray-400">
                    Vence {{ $task->due_date->format('d/m/Y') }}
                </span>
            @endif
        </div>

        <h1 class="text-2xl font-semibold text-gray-800 mb-3
                   {{ $task->status === 'completed' ? 'line-through text-gray-400' : '' }}">
            {{ $task->title }}
        </h1>

        @if($task->description)
            <p class="text-gray-600 leading-relaxed mb-6">{{ $task->description }}</p>
        @endif

        <p class="text-xs text-gray-400 mb-8">
            Criada em {{ $task->created_at->format('d/m/Y \à\s H:i') }}
        </p>

        <div class="flex gap-3">
            <a href="{{ route('tasks.edit', $task) }}"
               class="bg-gray-800 hover:bg-gray-700 text-white text-sm px-4 py-2 rounded-lg transition">
                Editar
            </a>
            <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                  onsubmit="return confirm('Eliminar esta tarefa?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="text-sm text-red-500 hover:text-red-700 px-4 py-2 rounded-lg hover:bg-red-50 transition">
                    Eliminar
                </button>
            </form>
            <a href="{{ route('tasks.index') }}"
               class="text-sm text-gray-500 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                Voltar
            </a>
        </div>
    </div>
@endsection