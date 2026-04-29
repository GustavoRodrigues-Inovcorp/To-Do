@php
    $pColors = ['high'=>'text-red-400','medium'=>'text-yellow-400','low'=>'text-green-400'];
    $sColors = ['pending'=>'bg-gray-100 text-gray-500','completed'=>'bg-blue-100 text-blue-600'];
    $sLabels = ['pending'=>'Pendente','completed'=>'Concluída'];
@endphp

<div class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50 transition group last:rounded-b-2xl>

    {{-- Toggle concluída --}}
    <form method="POST" action="{{ route('tasks.updateStatus', $task) }}">
        @csrf @method('PATCH')
        <input type="hidden" name="status" value="completed">
        <button type="submit"
                class="w-4 h-4 rounded border-2 border-[#e7e7e7] hover:border-gray-300
                       flex items-center justify-center shrink-0 transition">
        </button>
    </form>

    {{-- Título --}}
    <a href="{{ route('tasks.show', $task) }}" class="flex-1 min-w-0">
        <span class="text-sm text-[#444444] truncate block">
            {{ $task->title }}
        </span>

        @if($task->description)
            <span class="text-xs text-gray-400 truncate block">{{ $task->description }}</span>
        @endif
    </a>

    {{-- Meta --}}
    <div class="flex items-center gap-3 shrink-0">
        @if($task->due_date)
            <span class="text-xs text-gray-400 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $task->due_date->format('d/m') }}
            </span>
        @endif

        <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $sColors[$task->status] ?? '' }}">
            {{ $sLabels[$task->status] ?? '' }}
        </span>

        {{-- Dot prioridade --}}
        <span class="w-2 h-2 rounded-full
                     {{ $task->priority === 'high' ? 'bg-red-400' : ($task->priority === 'medium' ? 'bg-yellow-400' : 'bg-green-400') }}">
        </span>

        {{-- Seta detalhe --}}
        <a href="{{ route('tasks.show', $task) }}"
           class="text-gray-300 hover:text-gray-500 transition opacity-0 group-hover:opacity-100">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</div>