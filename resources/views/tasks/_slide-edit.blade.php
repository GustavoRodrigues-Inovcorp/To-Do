<div id="task-panel"
     class="bg-[#f4f4f4] rounded-xl flex flex-col fixed z-30
            top-4 bottom-4 right-4 w-80
            translate-x-[calc(100%+2rem)] transition-transform duration-300">

    {{-- Header --}}
    <div class="flex items-center justify-between px-5 py-4">
        <span class="text-lg font-bold text-[#444444] tracking-tight">Tarefa</span>
        <button onclick="closeTask()" class="text-[#7c7c7c] hover:text-gray-600 transition p-1">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Loading --}}
    <div id="task-loading" class="flex-1 flex items-center justify-center">
        <div class="w-5 h-5 border-2 border-[#059669] border-t-transparent rounded-full animate-spin"></div>
    </div>

    {{-- Conteúdo --}}
    <div id="task-content" class="flex-1 overflow-y-auto" style="display:none">
        <div class="px-5 py-2 space-y-4">

            <input id="task-title" type="text"
                   class="w-full text-sm text-[#7c7c7c] font-medium border border-[#e8e8e8]
                          rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#059669] bg-white"
                   placeholder="Título da tarefa"/>

            <textarea id="task-description" rows="4"
                      class="w-full text-sm text-[#7c7c7c] font-medium border border-[#e8e8e8]
                             rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#059669]
                             resize-none bg-white"
                      placeholder="Descrição (opcional)"></textarea>

            <div class="flex items-center justify-between">
                <span class="text-sm text-[#444444]">Prioridade</span>
                <select id="task-priority"
                        class="text-sm text-[#444444] border border-[#e8e8e8] rounded-lg px-3 py-1.5
                               focus:outline-none bg-white min-w-32">
                    <option value="low">Baixa</option>
                    <option value="medium">Média</option>
                    <option value="high">Alta</option>
                </select>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-sm text-[#444444]">Estado</span>
                <select id="task-status"
                        class="text-sm text-[#444444] border border-[#e8e8e8] rounded-lg px-3 py-1.5
                               focus:outline-none bg-white min-w-32">
                    <option value="pending">Pendente</option>
                    <option value="completed">Concluída</option>
                </select>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-sm text-[#444444]">Data</span>
                <input id="task-due-date" type="date"
                       class="text-sm border border-[#e8e8e8] rounded-lg px-3 py-1.5
                              focus:outline-none bg-white"/>
            </div>

            <p id="task-created" class="text-[0.7rem] text-[#7c7c7c] pt-2 border-t border-[#e8e8e8]"></p>
        </div>
    </div>

    {{-- Ações --}}
    <div id="task-actions" class="px-5 py-4" style="display:none">
        <div class="flex gap-2">
            <button onclick="deleteTask()"
                    class="flex-1 text-sm text-gray-500 border border-gray-200 py-2.5 rounded-xl
                           transition font-medium cursor-pointer hover:border-red-400 hover:text-red-500">
                Eliminar
            </button>
            <button onclick="saveTask()"
                    class="flex-1 text-sm bg-[#059669] hover:bg-[#047d57] text-white
                           py-2.5 rounded-xl transition font-semibold cursor-pointer">
                Guardar
            </button>
        </div>
    </div>
</div>

{{-- Modal de confirmação --}}
<div id="confirm-modal"
     class="fixed inset-0 z-50 items-center justify-center"
     style="display:none; background: rgba(0,0,0,0.15);">
    <div class="bg-white rounded-2xl shadow-lg p-6 w-80 mx-4">
        <h3 class="text-base font-semibold text-gray-800 mb-2">Eliminar tarefa</h3>
        <p class="text-sm text-gray-500 mb-6">Tens a certeza? Esta ação não pode ser desfeita.</p>
        <div class="flex gap-3">
            <button onclick="closeConfirm()"
                    class="flex-1 text-sm text-gray-500 border border-gray-200
                           hover:bg-gray-50 py-2.5 rounded-xl transition font-medium">
                Cancelar
            </button>
            <button onclick="confirmDelete()"
                    class="flex-1 text-sm bg-red-500 hover:bg-red-600 text-white
                           py-2.5 rounded-xl transition font-semibold">
                Eliminar
            </button>
        </div>
    </div>
</div>