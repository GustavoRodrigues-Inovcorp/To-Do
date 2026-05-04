{{-- resources/views/tasks/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Todas as tarefas')

@section('content')

{{-- Filtros --}}
<div class="flex items-center gap-3 mb-6 ml-3 flex-wrap">

    {{-- Filtro de data --}}
    <form method="GET" action="{{ route('tasks.index') }}" class="flex items-center gap-2">
        @foreach(request()->except('due_date') as $key => $val)
            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
        @endforeach

        <button type="button"
                onclick="document.getElementById('date-filter-input').showPicker()"
                class="flex items-center gap-2 bg-white border border-[#e8e8e8] rounded-lg
                    px-3 py-2 cursor-pointer hover:border-gray-300 transition group">
            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-xs text-gray-400">
                {{ request('due_date')
                    ? \Carbon\Carbon::parse(request('due_date'))->format('d/m/Y')
                    : 'Filtrar por data' }}
            </span>
        </button>
        <input type="date" id="date-filter-input" name="due_date"
            value="{{ request('due_date') }}"
            onchange="this.form.submit()"
            class="sr-only">

        @if(request('due_date'))
            <a href="{{ route('tasks.index', request()->except('due_date')) }}"
               class="flex items-center gap-1 text-xs text-gray-400 hover:text-gray-600
                      bg-white border border-[#e8e8e8] rounded-lg px-2.5 py-2 transition">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Limpar
            </a>
        @endif
    </form>

    {{-- Contador --}}
    <span class="text-xs text-gray-400 ml-auto">
        {{ $tasks->total() }} {{ $tasks->total() === 1 ? 'tarefa' : 'tarefas' }}
    </span>
</div>

@php
    $grouped = $tasks->getCollection()->groupBy('status');
    $sections = [
        'pending'   => ['label' => 'Pendentes',  'empty' => 'Sem tarefas pendentes.'],
        'completed' => ['label' => 'Concluídas', 'empty' => 'Sem tarefas concluídas.'],
    ];

    if (request('status')) {
        $sections = array_intersect_key($sections, [request('status') => true]);
    }
@endphp

@if($tasks->isEmpty())
    <div class="text-center py-20">
        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                         M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <p class="text-gray-400 font-medium">Nenhuma tarefa encontrada</p>
        <button onclick="openCreate()"
                class="inline-block mt-3 text-sm text-[#059669] hover:text-[#047d57] font-medium cursor-pointer">
            Criar Tarefa
        </button>
    </div>

@else

    @foreach($sections as $status => $meta)
        @php $sectionTasks = $grouped->get($status, collect()); @endphp

        <div class="mb-8 ml-3 border border-[#f0f0f0] rounded-md p-4">

            {{-- Cabeçalho --}}
            <div class="flex items-center gap-3 mb-3">
                <h2 class="text-base font-semibold text-gray-700">{{ $meta['label'] }}</h2>
                <span class="text-xs text-gray-400">
                    {{ $sectionTasks->count() }} {{ $sectionTasks->count() === 1 ? 'tarefa' : 'tarefas' }}
                </span>
            </div>

            {{-- Botão adicionar (só em Pendentes) --}}
            @if($status === 'pending')
                <button onclick="openCreate()"
                        class="w-full flex items-center border border-[#f3f3f3] rounded-md gap-3
                               px-4 py-3 text-xs font-semibold text-[#7c7c7c] cursor-pointer">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Adicionar Nova Tarefa
                </button>
            @endif

            {{-- Tarefas --}}
            @forelse($sectionTasks as $task)
                <div class="border-b border-[#f0f0f0] last:border-0">
                    @include('tasks._upcoming-row', ['task' => $task])
                </div>
            @empty
                <div class="section-empty px-5 py-4 text-xs text-gray-400 italic">
                    {{ $meta['empty'] }}
                </div>
            @endforelse
        </div>
    @endforeach

    {{-- Paginação --}}
    @if($tasks->hasPages())
        <div class="mt-6 ml-3">{{ $tasks->withQueryString()->links() }}</div>
    @endif

@endif

@endsection