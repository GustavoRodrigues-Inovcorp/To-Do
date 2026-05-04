<div id="confirm-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center"
     style="background: rgba(0,0,0,0.15);">
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