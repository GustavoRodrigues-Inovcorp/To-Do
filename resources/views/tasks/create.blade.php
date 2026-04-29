@extends('layouts.app')
@section('title', 'Nova tarefa')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-gray-100 p-8">

        <form method="POST" action="{{ route('tasks.store') }}" class="space-y-6">
            @csrf

            {{-- Título --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Título <span class="text-red-400">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}"
                       placeholder="Ex: Reunião com equipa..."
                       class="w-full border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                              {{ $errors->has('title') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
                @error('title')
                    <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Descrição --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                <textarea name="description" rows="3"
                          placeholder="Adiciona detalhes sobre esta tarefa..."
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition resize-none">{{ old('description') }}</textarea>
            </div>

            {{-- Prioridade + Status --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Prioridade <span class="text-red-400">*</span>
                    </label>
                    <select name="priority"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white">
                        <option value="low"    {{ old('priority') === 'low'    ? 'selected' : '' }}>🟢 Baixa</option>
                        <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>🟡 Média</option>
                        <option value="high"   {{ old('priority') === 'high'   ? 'selected' : '' }}>🔴 Alta</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Estado <span class="text-red-400">*</span>
                    </label>
                    <select name="status"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white">
                        <option value="pending"     {{ old('status', 'pending') === 'pending'     ? 'selected' : '' }}>⏳ Pendente</option>
                        <option value="completed"   {{ old('status') === 'completed'   ? 'selected' : '' }}>✅ Concluída</option>
                    </select>
                </div>
            </div>

            {{-- Data --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Data de vencimento</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>

            {{-- Ações --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-3 rounded-xl transition">
                    Criar tarefa
                </button>
                <a href="{{ route('tasks.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 px-5 py-3 rounded-xl hover:bg-gray-50 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection