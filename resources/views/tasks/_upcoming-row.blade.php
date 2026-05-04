@php
    $isCompleted = $task->status === 'completed';
    $sColors = ['pending'=>'bg-gray-100 text-gray-500','completed'=>'bg-blue-100 text-blue-600'];
    $sLabels = ['pending'=>'Pendente','completed'=>'Concluída'];
@endphp

<div data-task-id="{{ $task->id }}"
     data-status="{{ $task->status }}"
     class="task-row flex items-center gap-3 px-3 sm:px-5 py-3 sm:py-3.5 hover:bg-gray-50
            transition group last:rounded-b-2xl {{ $isCompleted ? 'opacity-60' : '' }}">

    {{-- Checkbox --}}
    <button type="button"
            onclick="toggleStatus({{ $task->id }}, this)"
            class="w-4 h-4 rounded border-2 flex items-center justify-center shrink-0 transition
                   {{ $isCompleted
                       ? 'bg-[#059669] border-[#059669]'
                       : 'border-gray-300 hover:border-[#059669]' }}"
            aria-label="Marcar como {{ $isCompleted ? 'pendente' : 'concluída' }}">
        @if($isCompleted)
            <svg class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="3.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        @endif
    </button>

    {{-- Título --}}
    <div class="flex-1 min-w-0 cursor-pointer" onclick="openTask({{ $task->id }})">
        <span class="task-title text-sm text-gray-700 group-hover:text-gray-900 truncate block
                     {{ $isCompleted ? 'text-gray-400' : '' }}">
            {{ $task->title }}
        </span>
        @if($task->description)
            <span class="text-xs text-gray-400 truncate block">{{ $task->description }}</span>
        @endif
    </div>

    {{-- Meta --}}
    <div class="flex items-center gap-2 sm:gap-3 shrink-0">

        {{-- Data — escondida em mobile muito pequeno --}}
        @if($task->due_date)
            <span class="hidden xs:flex text-xs text-gray-400 items-center gap-1">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $task->due_date->format('d/m') }}
            </span>
        @endif

        {{-- Badge — escondido em ecrãs pequenos --}}
        <span class="task-status-badge hidden sm:inline-flex text-xs font-medium px-2 py-0.5 rounded-full {{ $sColors[$task->status] ?? '' }}">
            {{ $sLabels[$task->status] ?? '' }}
        </span>

        {{-- Ponto de prioridade --}}
        <span class="w-2 h-2 rounded-full shrink-0
                     {{ $task->priority === 'high' ? 'bg-red-400' : ($task->priority === 'medium' ? 'bg-yellow-400' : 'bg-green-400') }}">
        </span>

        {{-- Seta --}}
        <button onclick="openTask({{ $task->id }})"
                class="text-[#7c7c7c] hover:text-gray-500 transition sm:opacity-0 sm:group-hover:opacity-100"
                aria-label="Editar tarefa">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
</div>