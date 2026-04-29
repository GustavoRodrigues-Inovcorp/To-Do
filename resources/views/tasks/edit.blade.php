@extends('layouts.app')
@section('title', 'Editar Tarefa')

@section('content')
    <div class="max-w-xl">
        <h1 class="text-xl font-semibold text-gray-800 mb-6">Editar tarefa</h1>

        <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-5">
            @csrf
            @method('PUT')   {{-- diferença 1: método PUT --}}

            {{-- Campos iguais ao create, mas com value do $task --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                <input type="text" name="title"
                       value="{{ old('title', $task->title) }}"   {{-- diferença 2: old() com fallback --}}
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- ... restantes campos com old('campo', $task->campo) ... --}}

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                    Guardar alterações   {{-- diferença 3: label do botão --}}
                </button>
                <a href="{{ route('tasks.index') }}"
                   class="text-sm text-gray-500 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection