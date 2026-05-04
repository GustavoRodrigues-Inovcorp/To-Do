@extends('layouts.app')
@section('title', 'Planeamento')

@section('content')
<div data-page="upcoming"></div>

{{-- HOJE --}}
<div class="mb-8 ml-3 border border-[#f0f0f0] rounded-md p-4">
    <div class="flex items-center gap-3 mb-3">
        <h2 class="text-base font-semibold text-gray-700">Hoje</h2>
        <span class="text-xs text-gray-400">{{ now()->format('d/m/Y') }}</span>
    </div>

    <div>
        {{-- Linha de adicionar --}}
    <button onclick="openCreate('{{ now()->format('Y-m-d') }}')"
            class="w-full flex items-center border border-[#f3f3f3] rounded-md gap-3 px-4 py-3 text-xs font-semibold text-[#7c7c7c] cursor-pointer">
        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Adicionar Nova Tarefa
    </button>

        @forelse($todayTasks as $task)
            <div class="border-b border-[#f0f0f0] last:border-0">
                @include('tasks._upcoming-row', ['task' => $task])
            </div>
        @empty
            <div class="section-empty px-5 py-4 text-xs text-gray-400 italic">
                Sem tarefas para hoje.
            </div>
        @endforelse
    </div>
</div>

{{-- AMANHÃ --}}
<div class="mb-8 ml-3 border border-[#f0f0f0] rounded-md p-4">
    <div class="flex items-center gap-3 mb-3">
        <h2 class="text-base font-semibold text-gray-700">Amanhã</h2>
        <span class="text-xs text-gray-400">{{ now()->addDay()->format('d/m/Y') }}</span>
    </div>

    <div>
        <button onclick="openCreate('{{ now()->addDay()->format('Y-m-d') }}')"
            class="w-full flex items-center border border-[#f3f3f3] rounded-md gap-3 px-4 py-3 text-xs font-semibold text-[#7c7c7c] cursor-pointer">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Adicionar Nova Tarefa
        </button>

        @forelse($tomorrowTasks as $task)
            <div class="border-b border-[#f0f0f0] last:border-0">
                @include('tasks._upcoming-row', ['task' => $task])
            </div>
        @empty
            <div class="section-empty px-5 py-4 text-xs text-gray-400 italic">
                Sem tarefas para amanhã.
            </div>
        @endforelse
    </div>
</div>

{{-- ESTA SEMANA --}}
<div class="mb-8 ml-3 border border-[#f0f0f0] rounded-md p-4">
    <div class="flex items-center gap-3 mb-3">
        <h2 class="text-base font-semibold text-gray-700">Esta semana</h2>
        <span class="text-xs text-gray-400">
            até {{ now()->endOfWeek()->format('d/m/Y') }}
        </span>
    </div>

    <div>
        <button onclick="openCreate()"
            class="w-full flex items-center border border-[#f3f3f3] rounded-md gap-3 px-4 py-3 text-xs font-semibold text-[#7c7c7c] cursor-pointer">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Adicionar tarefa
        </button>

        @forelse($thisWeekTasks as $task)
            <div class="border-b border-[#f0f0f0] last:border-0">
                @include('tasks._upcoming-row', ['task' => $task])
            </div>
        @empty
            <div class="section-empty px-5 py-4 text-xs text-gray-400 italic">
                Sem tarefas para esta semana.
            </div>
        @endforelse
    </div>
</div>

@endsection