@props(['priority'])

@php
    $styles = match($priority) {
        'high'   => 'bg-red-100 text-red-700',
        'medium' => 'bg-yellow-100 text-yellow-700',
        'low'    => 'bg-green-100 text-green-700',
        default  => 'bg-gray-100 text-gray-600',
    };
    $labels = ['high' => 'Alta', 'medium' => 'Média', 'low' => 'Baixa'];
@endphp

<span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $styles }}">
    {{ $labels[$priority] ?? $priority }}
</span>