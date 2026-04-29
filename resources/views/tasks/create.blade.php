@extends('layouts.app')
@section('title', 'Nova Tarefa')

@section('content')
    <div class="max-w-xl">
        <h1 class="text-xl font-semibold text-gray-800 mb-6">Nova tarefa</h1>

        <form method="POST" action="{{ route('tasks.store') }}" class="space-y-5">
            @csrf

            {{-- Título --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Título <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                              {{ $errors->has('title') ? 'border-red-400' : 'border-gray-200' }}">
                @error('title')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Descrição --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                <textarea name="description" rows="3"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('description') }}</textarea>
            </div>

            {{-- Prioridade e Status --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Prioridade <span class="text-red-500">*</span>
                    </label>
                    <select name="priority"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="low"    {{ old('priority') === 'low'    ? 'selected' : '' }}>Baixa</option>
                        <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }} selected>Média</option>
                        <option value="high"   {{ old('priority') === 'high'   ? 'selected' : '' }}>Alta</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <select name="status"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending"     {{ old('status') === 'pending'     ? 'selected' : '' }}>Pendente</option>
                        <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>Em progresso</option>
                        <option value="completed"   {{ old('status') === 'completed'   ? 'selected' : '' }}>Concluída</option>
                    </select>
                </div>
            </div>

            {{-- Data de vencimento --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Data de vencimento</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Ações --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                    Criar tarefa
                </button>
                <a href="{{ route('tasks.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection