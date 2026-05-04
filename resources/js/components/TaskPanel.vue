<template>
    <!-- Painel de editar -->
    <div id="task-panel"
         :style="{ transform: editOpen ? 'translateX(0)' : 'translateX(calc(100% + 2rem))' }"
         class="bg-[#f4f4f4] rounded-xl flex flex-col fixed z-30 top-4 bottom-4 right-4 w-80 transition-transform duration-300">

        <div class="flex items-center justify-between px-5 py-4">
            <span class="text-lg font-bold text-[#444444]">Tarefa</span>
            <button @click="closeEdit" class="text-[#7c7c7c] hover:text-gray-600 p-1">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex-1 flex items-center justify-center">
            <div class="w-5 h-5 border-2 border-[#059669] border-t-transparent rounded-full animate-spin"></div>
        </div>

        <!-- Conteúdo -->
        <div v-else class="flex-1 overflow-y-auto">
            <div class="px-5 py-2 space-y-4">
                
                <div class="space-y-1">
                    <label class="text-[0.7rem] font-semibold text-gray-600 uppercase tracking-wider">
                        Título <span class="text-red-400" aria-hidden="true">*</span>
                    </label>
                    <input v-model="form.title" type="text"
                        class="w-full text-sm text-[#7c7c7c] font-medium border border-[#e8e8e8]
                            rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#059669] bg-white"
                        placeholder="Título"/>
                </div>

                 <!-- Descrição -->
                <div class="space-y-1">
                    <label for="create-description" class="text-[0.7rem] font-semibold text-gray-600 uppercase tracking-wider">Descrição (Opcional)</label>
                    <textarea v-model="form.description" rows="4"
                        class="w-full text-sm text-[#7c7c7c] font-medium border border-[#e8e8e8]
                            rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#059669]
                            resize-none bg-white"
                        placeholder="Descrição">
                    </textarea>
                </div>

                
                <!-- Prioridade -->
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[#444444]">Prioridade</span>
                    <select v-model="form.priority"
                            class="text-sm text-[#444444] border border-[#e8e8e8] rounded-lg px-3 py-1.5
                                   focus:outline-none bg-white min-w-32">
                        <option value="low">Baixa</option>
                        <option value="medium">Média</option>
                        <option value="high">Alta</option>
                    </select>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-[#444444]">Data</span>
                    <input v-model="form.due_date" type="date"
                           class="text-sm border border-[#e8e8e8] rounded-lg px-3 py-1.5
                                  focus:outline-none bg-white min-w-32"/>
                </div>

                <p class="text-[0.7rem] text-[#7c7c7c] pt-2 border-t border-[#e8e8e8]">
                    {{ createdAt }}
                </p>
            </div>
        </div>

        <!-- Ações -->
        <div v-if="!loading" class="px-5 py-4 flex gap-2">
            <button @click="deleteTask"
                    class="flex-1 text-sm text-gray-500 border border-gray-200 py-2.5 rounded-xl
                           transition font-medium hover:border-red-400 hover:text-red-500">
                {{ deleting ? 'A eliminar...' : 'Eliminar' }}
            </button>
            <button @click="saveTask"
                    class="flex-1 text-sm bg-[#059669] hover:bg-[#047d57] text-white
                           py-2.5 rounded-xl transition font-semibold">
                {{ saving ? 'A guardar...' : 'Guardar' }}
            </button>
        </div>
    </div>

    <!-- Painel de criar -->
    <div id="create-panel"
         :style="{ transform: createOpen ? 'translateX(0)' : 'translateX(calc(100% + 2rem))' }"
         class="bg-[#f4f4f4] rounded-xl flex flex-col fixed z-30 top-4 bottom-4 right-4 w-80 transition-transform duration-300">

        <div class="flex items-center justify-between px-5 py-4">
            <span class="text-lg font-bold text-[#444444]">Nova Tarefa</span>
            <button @click="closeCreate" class="text-[#7c7c7c] hover:text-gray-600 p-1">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto">
            <div class="px-5 py-2 space-y-4">

                <!-- Título -->
                <div class="space-y-1">
                    <label class="text-[0.7rem] font-semibold text-gray-600 uppercase tracking-wider">
                        Título <span class="text-red-400">*</span>
                    </label>
                    <input v-model="newForm.title" type="text"
                        class="w-full text-sm text-[#7c7c7c] font-normal border border-[#e8e8e8] rounded-lg px-3 py-2
                                focus:outline-none focus:ring-2 focus:ring-[#059669] bg-white"
                        placeholder="Título"/>
                    <p v-if="titleError" class="text-xs text-red-500">O título é obrigatório.</p>
                </div>

                <!-- Descrição -->
                <div class="space-y-1">
                    <label class="text-[0.7rem] font-semibold text-gray-600 uppercase tracking-wider">Descrição (Opcional)</label>
                    <textarea v-model="newForm.description" rows="4"
                            class="w-full text-sm text-[#7c7c7c] font-normal border border-[#e8e8e8] rounded-lg px-3 py-2
                                    focus:outline-none focus:ring-2 focus:ring-[#059669] resize-none bg-white"
                            placeholder="Descrição"></textarea>
                </div>

                <!-- Prioridade -->
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[#444444]">Prioridade</span>
                    <select v-model="newForm.priority"
                            class="text-sm text-[#444444] border border-[#e8e8e8] rounded-lg px-3 py-1.5
                                focus:outline-none bg-white min-w-32">
                        <option value="low">Baixa</option>
                        <option value="medium">Média</option>
                        <option value="high">Alta</option>
                    </select>
                </div>

                <!-- Data -->
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[#444444]">Data</span>
                    <input v-model="newForm.due_date" type="date"
                        class="text-sm border border-[#e8e8e8] rounded-lg px-3 py-1.5
                                focus:outline-none bg-white min-w-32"/>
                </div>
            </div>
        </div>

        <div class="px-5 py-4">
            <button @click="submitCreate"
                    class="w-full text-sm bg-[#059669] hover:bg-[#047d57] text-white
                           py-2.5 rounded-xl transition font-semibold">
                {{ creating ? 'A criar...' : 'Criar Tarefa' }}
            </button>
        </div>
    </div>

    <!-- Modal de confirmação -->
    <div v-if="confirmOpen"
         class="fixed inset-0 z-50 flex items-center justify-center"
         style="background: rgba(0,0,0,0.15)">
        <div class="bg-white rounded-2xl shadow-lg p-6 w-80 mx-4">
            <h3 class="text-base font-semibold text-gray-800 mb-2">Eliminar tarefa</h3>
            <p class="text-sm text-gray-500 mb-6">Tens a certeza? Esta ação não pode ser desfeita.</p>
            <div class="flex gap-3">
                <button @click="confirmOpen = false"
                        class="flex-1 text-sm text-gray-500 border border-gray-200
                               hover:bg-gray-50 py-2.5 rounded-xl transition font-medium">
                    Cancelar
                </button>
                <button @click="confirmDelete"
                        class="flex-1 text-sm bg-red-500 hover:bg-red-600 text-white
                               py-2.5 rounded-xl transition font-semibold">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'

const csrf = () => document.querySelector('meta[name="csrf-token"]').content

// ===== ESTADO =====
const editOpen   = ref(false)
const createOpen = ref(false)
const confirmOpen = ref(false)
const loading    = ref(false)
const saving     = ref(false)
const deleting   = ref(false)
const creating   = ref(false)
const titleError = ref(false)
const createdAt  = ref('')
const currentTaskId = ref(null)

const form = reactive({
    title: '', description: '', priority: 'medium', status: 'pending', due_date: ''
})

const newForm = reactive({
    title: '', description: '', priority: 'medium', status: 'pending', due_date: ''
})

// ===== LAYOUT =====
function openLayout() {
    const main = document.getElementById('main-content')
    if (main && window.innerWidth >= 640) main.style.paddingRight = '21rem'
}
function closeLayout() {
    const main = document.getElementById('main-content')
    if (main) main.style.paddingRight = '0'
}

// ===== EDITAR =====
async function openTask(id) {
    currentTaskId.value = id
    // also set global id for legacy scripts that rely on window.currentTaskId
    try { window.currentTaskId = id } catch (e) {}
    createOpen.value = false
    editOpen.value = true
    loading.value = true
    openLayout()

    try {
        const res = await fetch(`/tasks/${id}/json`)
        if (!res.ok) throw new Error('Erro ' + res.status)
        const task = await res.json()

        form.title       = task.title ?? ''
        form.description = task.description ?? ''
        form.priority    = task.priority ?? 'medium'
        form.status      = task.status ?? 'pending'
        form.due_date    = task.due_date ? task.due_date.substring(0, 10) : ''
        createdAt.value  = 'Criada em ' + new Date(task.created_at).toLocaleDateString('pt-PT')
    } catch (e) {
        console.error(e)
    } finally {
        loading.value = false
    }
}

function closeEdit() {
    editOpen.value = false
    closeLayout()
    currentTaskId.value = null
}

async function saveTask() {
    saving.value = true
    try {
        const res = await fetch(`/tasks/${currentTaskId.value}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf(),
                'Accept': 'application/json',
            },
            body: JSON.stringify({ ...form, _method: 'PUT' }),
        })
        if (!res.ok) throw new Error('Erro ' + res.status)
        const task = await res.json()

        syncTaskRow(task)

        setTimeout(() => closeEdit(), 800)
    } catch (e) {
        console.error(e)
    } finally {
        saving.value = false
    }
}

// ===== ELIMINAR =====
function deleteTask() {
    confirmOpen.value = true
}

async function confirmDelete() {
    confirmOpen.value = false
    deleting.value = true
    const idToDelete = currentTaskId.value

    try {
        const res = await fetch(`/tasks/${idToDelete}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf(),
                'Accept': 'application/json',
            },
            body: JSON.stringify({ _method: 'DELETE' }),
        })
        if (!res.ok) throw new Error('Erro ' + res.status)

        const row = document.querySelector(`[data-task-id="${idToDelete}"]`)
        if (row) {
            const wrapper = row.closest('.border-b') ?? row.parentElement ?? row
            wrapper.style.transition = 'opacity 0.2s, transform 0.2s'
            wrapper.style.opacity = '0'
            wrapper.style.transform = 'translateX(1rem)'
            setTimeout(() => {
                wrapper.remove()
                updateSectionCounters()

                // Mostra mensagem de vazio se a secção não tiver mais tarefas
                const section = wrapper.closest?.('.mb-8') ?? document.querySelector('.mb-8')
                // Como o wrapper já foi removido, procura pela secção que contém a row
                const allSections = document.querySelectorAll('.mb-8')
                allSections.forEach(sec => {
                    const rows = sec.querySelectorAll('[data-task-id]')
                    const emptyEl = sec.querySelector('.section-empty')
                    if (emptyEl) {
                        emptyEl.style.display = rows.length === 0 ? 'block' : 'none'
                    }
                })
            }, 200)
        }

        closeEdit()
    } catch (e) {
        console.error(e)
    } finally {
        deleting.value = false
    }
}

// ===== CRIAR =====
function openCreate(dueDate = null) {
    editOpen.value = false
    newForm.title       = ''
    newForm.description = ''
    newForm.priority    = 'medium'
    newForm.status      = 'pending'
    newForm.due_date    = dueDate ?? ''
    titleError.value    = false
    createOpen.value    = true
    openLayout()
}

function closeCreate() {
    createOpen.value = false
    closeLayout()
}

async function submitCreate() {
    if (!newForm.title.trim()) {
        titleError.value = true
        return
    }

    creating.value = true
    const currentUrl = window.location.href
    try {
        const res = await fetch('/tasks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf(),
                'Accept': 'application/json',
            },
            body: JSON.stringify({ ...newForm }),
        })
        if (!res.ok) throw new Error('Erro ' + res.status)

        setTimeout(() => { window.location.href = currentUrl }, 600)
    } catch (e) {
        console.error(e)
        creating.value = false
    }
}

// ===== TOGGLE STATUS =====
async function toggleStatus(id, btn) {
    const row = document.querySelector(`[data-task-id="${id}"]`)
    if (!row) return

    const currentStatus = row.dataset.status
    const newStatus = currentStatus === 'completed' ? 'pending' : 'completed'

    try {
        const res = await fetch(`/tasks/${id}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf(),
                'Accept': 'application/json',
            },
            body: JSON.stringify({ status: newStatus }),
        })
        if (!res.ok) throw new Error('Erro ' + res.status)

        const task = await res.json()
        syncTaskRow(task)
    } catch (e) {
        console.error(e)
    }
}

function syncTaskRow(task) {
    const row = document.querySelector(`[data-task-id="${task.id}"]`)
    if (!row) return

    const isCompleted = task.status === 'completed'

    row.dataset.status = task.status
    row.classList.toggle('opacity-60', isCompleted)

    const titleEl = row.querySelector('.task-title')
    if (titleEl) {
        titleEl.classList.toggle('line-through', isCompleted)
        titleEl.classList.toggle('text-gray-400', isCompleted)
        titleEl.classList.toggle('text-gray-700', !isCompleted)
        titleEl.textContent = task.title ?? ''
    }

    const descriptionEl = titleEl?.nextElementSibling
    if (descriptionEl && descriptionEl.classList.contains('text-xs')) {
        descriptionEl.textContent = task.description ?? ''
        descriptionEl.classList.toggle('hidden', !task.description)
    }

    const badge = row.querySelector('.task-status-badge')
    if (badge) {
        badge.textContent = isCompleted ? 'Concluída' : 'Pendente'
        badge.className = 'task-status-badge hidden sm:inline-flex text-xs font-medium px-2 py-0.5 rounded-full '
            + (isCompleted ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500')
    }

    const checkbox = row.querySelector('button[onclick^="toggleStatus"]')
    if (checkbox) {
        checkbox.setAttribute('aria-label', `Marcar como ${isCompleted ? 'pendente' : 'concluída'}`)

        if (isCompleted) {
            checkbox.classList.remove('border-gray-300', 'hover:border-[#059669]')
            checkbox.classList.add('bg-[#059669]', 'border-[#059669]')
            checkbox.innerHTML = `<svg class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="3.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>`
        } else {
            checkbox.classList.add('border-gray-300', 'hover:border-[#059669]')
            checkbox.classList.remove('bg-[#059669]', 'border-[#059669]')
            checkbox.innerHTML = ''
        }
    }

    moveTaskRow(task, row)
}

function moveTaskRow(task, row) {
    const isCompleted = task.status === 'completed'
    const targetLabel = isCompleted ? 'Concluídas' : 'Pendentes'
    const targetSection = [...document.querySelectorAll('.mb-8')].find(section => {
        const heading = section.querySelector('h2')
        return heading && heading.textContent.trim() === targetLabel
    })

    const wrapper = row.closest('.border-b') ?? row
    const currentSection = wrapper.parentElement

    if (!targetSection) {
        if (document.querySelector('[data-page="upcoming"]') && isCompleted) {
            wrapper.remove()
            updateSectionCounters()
        }
        return
    }

    const targetContainer = targetSection.querySelector('.border-b')?.parentElement
        ?? targetSection.querySelector('button[onclick^="openCreate"]')?.parentElement
        ?? targetSection

    if (currentSection === targetContainer) {
        updateSectionCounters()
        return
    }

    wrapper.style.transition = 'opacity 0.25s'
    wrapper.style.opacity = '0'

    setTimeout(() => {
        targetContainer.appendChild(wrapper)
        wrapper.style.opacity = '1'
        updateSectionCounters()
    }, 250)
}

function updateSectionCounters() {
    document.querySelectorAll('.mb-8').forEach(section => {
        const counter = section.querySelector('h2 + span')
        if (!counter) return

        const rows = section.querySelectorAll('[data-task-id]')
        const count = rows.length
        counter.textContent = `${count} ${count === 1 ? 'tarefa' : 'tarefas'}`

        // show or hide the empty placeholder when present
        const emptyEl = section.querySelector('.section-empty')
        if (emptyEl) {
            emptyEl.style.display = count === 0 ? 'block' : 'none'
        }
    })
}

// ===== EXPÕE GLOBALMENTE PARA O BLADE =====
onMounted(() => {
    window.openTask     = openTask
    window.openCreate   = openCreate
    window.toggleStatus = toggleStatus

    // Fecha painéis com tecla Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (confirmOpen.value) {
                confirmOpen.value = false
            } else if (editOpen.value) {
                closeEdit()
            } else if (createOpen.value) {
                closeCreate()
            }
        }
    })
})
</script>