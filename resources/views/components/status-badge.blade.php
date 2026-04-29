@props(['status'])

@php
    $styles = match($status) {
        'completed'   => 'bg-blue-100 text-blue-700',
        'pending'     => 'bg-gray-100 text-gray-600',
        default       => 'bg-gray-100 text-gray-600',
    };
    $labels = [
        'pending'     => 'Pendente',
        'completed'   => 'Concluída',
    ];
@endphp

<span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $styles }}">
    {{ $labels[$status] ?? $status }}
</span>