@extends('layouts.app')
@section('title', $task->title)

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-gray-100 p-8">

        {{-- Badges --}}
        <div class="flex items-center gap-2 mb-6">
            @php
                $pColors = ['high'=>'bg-red-100 text-red-600','medium'=>'bg-yellow-100 text-yellow-600','low'=>'bg-green-100 text-green-600'];
                $pLabels = ['high'=>'Alta','medium'=>'Média','low'=>'Baixa'];
                $sColors = ['pending'=>'bg-gray-100 text-gray-500','completed'=>'bg-blue-100 text-blue-600'];
                $sLabels = ['pending'=>'Pendente','completed'=>'Concluída'];
            @endphp
            <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $pColors[$task->priority] ?? '' }}">
                {{ $pLabels[$task->priority] ?? '' }}
            </span>
            <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $sColors[$task->status] ?? '' }}">
                {{ $sLabels[$task->status] ?? '' }}
            </span>
            @if($task->due_date)
                <span class="text-xs text-gray-400 flex items-center gap-1 ml-auto">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $task->due_date->format('d/m/Y') }}
                    @if($task->due_date->isPast() && $task->status !== 'completed')
                        <span class="text-red-400 font-medium">· em atraso</span>
                    @endif
                </span>
            @endif
        </div>

        {{-- Título --}}
        <h2 class="text-2xl font-bold text-gray-900 mb-3
                   {{ $task->status === 'completed' ? 'line-through text-gray-400' : '' }}">
            {{ $task->title }}
        </h2>

        {{-- Descrição --}}
        @if($task->description)
            <p class="text-gray-500 leading-relaxed mb-8">{{ $task->description }}</p>
        @else
            <p class="text-gray-300 italic mb-8 text-sm">Sem descrição.</p>
        @endif

        <p class="text-xs text-gray-300 mb-8">
            Criada em {{ $task->created_at->format('d/m/Y \à\s H:i') }}
        </p>

        {{-- Ações --}}
        <div class="flex gap-3 pt-4 border-t border-gray-50">
            <a href="{{ route('tasks.edit', $task) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                Editar
            </a>
            <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                  onsubmit="return confirm('Eliminar esta tarefa?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="text-sm text-red-400 hover:text-red-600 px-5 py-2.5 rounded-xl hover:bg-red-50 transition">
                    Eliminar
                </button>
            </form>
            <a href="{{ route('tasks.index') }}"
               class="text-sm text-gray-400 hover:text-gray-600 px-4 py-2.5 rounded-xl hover:bg-gray-50 transition ml-auto">
                ← Voltar
            </a>
        </div>
    </div>
</div>
@endsection