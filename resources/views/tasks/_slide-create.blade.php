<div id="create-panel"
     class="bg-[#f4f4f4] rounded-xl flex flex-col fixed z-30
            top-4 bottom-4 right-4 w-80
            translate-x-[calc(100%+2rem)] transition-transform duration-300">

    {{-- Header --}}
    <div class="flex items-center justify-between px-5 py-4">
        <span class="text-lg font-bold text-[#444444] tracking-tight">Nova Tarefa</span>
        <button onclick="closeCreate()" class="text-[#7c7c7c] hover:text-gray-600 transition p-1">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Conteúdo --}}
    <div class="flex-1 overflow-y-auto">
        <div class="px-5 py-2 space-y-4">

            <input id="create-title" type="text"
                   class="w-full text-sm text-[#7c7c7c] font-medium border border-[#e8e8e8]
                          rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#059669] bg-white"
                   placeholder="Título da tarefa"/>
            <p id="create-title-error" class="text-xs text-red-500 hidden">O título é obrigatório.</p>

            <textarea id="create-description" rows="4"
                      class="w-full text-sm text-[#7c7c7c] font-medium border border-[#e8e8e8]
                             rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#059669]
                             resize-none bg-white"
                      placeholder="Descrição (opcional)"></textarea>

            <div class="flex items-center justify-between">
                <span class="text-sm text-[#444444]">Prioridade</span>
                <select id="create-priority"
                        class="text-sm text-[#444444] border border-[#e8e8e8] rounded-lg px-3 py-1.5
                               focus:outline-none bg-white min-w-32">
                    <option value="low">Baixa</option>
                    <option value="medium" selected>Média</option>
                    <option value="high">Alta</option>
                </select>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-sm text-[#444444]">Data</span>
                <input id="create-due-date" type="date"
                       class="text-sm border border-[#e8e8e8] rounded-lg px-3 py-1.5
                              focus:outline-none bg-white"/>
            </div>
        </div>
    </div>

    {{-- Ações --}}
    <div class="px-5 py-4">
        <button onclick="submitCreate()"
                class="w-full text-sm bg-[#059669] hover:bg-[#047d57] text-white
                       py-2.5 rounded-xl transition font-semibold cursor-pointer">
            Criar Tarefa
        </button>
    </div>
</div>