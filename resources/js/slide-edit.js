// expose currentTaskId on window so other scripts (and Vue) can set/read it
window.currentTaskId = null

function isMobile() {
    return window.innerWidth < 640
}

function openPanelLayout() {
    if (isMobile()) {
        // Mobile: painel ocupa ecrã completo, sem padding
        document.getElementById('main-content').style.paddingRight = '0'
    } else {
        document.getElementById('main-content').style.paddingRight = '25rem'
    }
}

function closePanelLayout() {
    document.getElementById('main-content').style.paddingRight = '0'
}

window.openTask = function (id) {
    window.currentTaskId = id

    // Fecha o painel de criar se estiver aberto
    document.getElementById('create-panel').style.transform = 'translateX(calc(100% + 2rem))'

    // Abre o painel de editar
    document.getElementById('task-panel').style.transform = 'translateX(0)'

    openPanelLayout()

    // Mostra loading, esconde conteúdo
    document.getElementById('task-loading').style.display = 'flex'
    document.getElementById('task-content').style.display = 'none'
    document.getElementById('task-actions').style.display = 'none'

    fetch(`/tasks/${id}/json`)
        .then(res => {
            if (!res.ok) throw new Error('Erro ' + res.status)
            return res.json()
        })
        .then(task => {
            document.getElementById('task-title').value       = task.title ?? ''
            document.getElementById('task-description').value = task.description ?? ''
            document.getElementById('task-priority').value    = task.priority ?? 'medium'
            document.getElementById('task-status').value      = task.status ?? 'pending'
            document.getElementById('task-due-date').value    = task.due_date
                ? task.due_date.substring(0, 10) : ''
            document.getElementById('task-created').textContent =
                'Criada em ' + new Date(task.created_at).toLocaleDateString('pt-PT')

            document.getElementById('task-loading').style.display = 'none'
            document.getElementById('task-content').style.display = 'block'
            document.getElementById('task-actions').style.display = 'flex'
            document.getElementById('task-actions').style.flexDirection = 'column'
        })
        .catch(err => console.error('Erro ao carregar tarefa:', err))
}

window.closeTask = function () {
    document.getElementById('task-panel').style.transform = 'translateX(calc(100% + 2rem))'
    closePanelLayout()
    window.currentTaskId = null
}

window.saveTask = function () {
    const btn = document.querySelector('[onclick="saveTask()"]')
    btn.textContent = 'A guardar...'
    btn.disabled = true

    const data = {
        title:       document.getElementById('task-title').value,
        description: document.getElementById('task-description').value,
        priority:    document.getElementById('task-priority').value,
        status:      document.getElementById('task-status').value,
        due_date:    document.getElementById('task-due-date').value || null,
        _method:     'PUT',
    }

    fetch(`/tasks/${window.currentTaskId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(res => {
        if (!res.ok) throw new Error('Erro ' + res.status)
        return res.json()
    })
    .then(task => {
        btn.textContent = 'Guardado'
        applyStatusToRow(task.id, task.status)
        setTimeout(() => {
            btn.textContent = 'Guardar'
            btn.disabled = false
        }, 1200)
    })
    .catch(err => {
        console.error('Erro ao guardar:', err)
        btn.textContent = 'Guardar'
        btn.disabled = false
    })
}

window.toggleStatus = function (id, btn) {
    const row = document.querySelector(`[data-task-id="${id}"]`)
    const currentStatus = row.dataset.status
    const newStatus = currentStatus === 'completed' ? 'pending' : 'completed'

    fetch(`/tasks/${id}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ status: newStatus, _method: 'PATCH' }),
    })
    .then(res => {
        if (!res.ok) throw new Error('Erro ' + res.status)
        return res.json()
    })
    .then(task => {
        const isUpcoming = document.querySelector('[data-page="upcoming"]') !== null
        if (isUpcoming) {
            applyStatusToRow(task.id, task.status)
        } else {
            moveRowToSection(row, task.status)
        }
    })
    .catch(err => console.error('Erro ao atualizar estado:', err))
}

function applyStatusToRow(id, status) {
    const row = document.querySelector(`[data-task-id="${id}"]`)
    if (!row) return

    const isCompleted = status === 'completed'
    row.dataset.status = status
    row.classList.toggle('opacity-60', isCompleted)

    const titleEl = row.querySelector('.task-title')
    if (titleEl) {
        titleEl.classList.toggle('text-gray-400', isCompleted)
        titleEl.classList.toggle('text-gray-700', !isCompleted)
    }

    const badge = row.querySelector('.task-status-badge')
    if (badge) {
        badge.textContent = isCompleted ? 'Concluída' : 'Pendente'
        badge.className = 'task-status-badge hidden sm:inline-flex text-xs font-medium px-2 py-0.5 rounded-full '
            + (isCompleted ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500')
    }

    const checkbox = row.querySelector('button[onclick^="toggleStatus"]')
    if (checkbox) {
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
}

function moveRowToSection(row, newStatus) {
    applyStatusToRow(row.dataset.taskId, newStatus)

    const sectionLabel = newStatus === 'completed' ? 'Concluídas' : 'Pendentes'
    const sections = document.querySelectorAll('.mb-8')
    let targetSection = null

    sections.forEach(s => {
        const h2 = s.querySelector('h2')
        if (h2 && h2.textContent.trim() === sectionLabel) targetSection = s
    })

    if (!targetSection) return

    const wrapper = row.closest('.border-b') ?? row
    const container = targetSection.querySelector('.border-b')?.parentElement
        ?? targetSection.querySelector('button[onclick^="openCreate"]')?.parentElement

    if (container) {
        row.style.transition = 'opacity 0.25s'
        row.style.opacity = '0'
        setTimeout(() => {
            const newWrapper = document.createElement('div')
            newWrapper.className = 'border-b border-[#f0f0f0] last:border-0'
            newWrapper.appendChild(row)
            row.style.opacity = '1'
            container.appendChild(newWrapper)
        }, 250)

        if (wrapper !== row) wrapper.remove()
    }

    updateSectionCounters()
}

function updateSectionCounters() {
    document.querySelectorAll('.mb-8').forEach(section => {
        const counter = section.querySelector('h2 + span')
        if (!counter) return
        const rows = section.querySelectorAll('[data-task-id]')
        const count = rows.length
        counter.textContent = `${count} ${count === 1 ? 'tarefa' : 'tarefas'}`
    })
}

window.deleteTask = function () {
    document.getElementById('confirm-modal').style.display = 'flex'
}

window.closeConfirm = function () {
    document.getElementById('confirm-modal').style.display = 'none'
}

window.confirmDelete = function () {
    closeConfirm()

    fetch(`/tasks/${window.currentTaskId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
    })
    .then(res => {
        if (!res.ok) throw new Error('Erro ' + res.status)
        const row = document.querySelector(`[data-task-id="${window.currentTaskId}"]`)
        if (row) {
            const wrapper = row.closest('.border-b') ?? row
            wrapper.remove()
        }
        closeTask()
        window.currentTaskId = null
        updateSectionCounters()
        window.location.reload()
    })
    .catch(err => console.error('Erro ao eliminar:', err))
}

// Ajusta padding ao redimensionar janela
window.addEventListener('resize', () => {
    const taskPanel = document.getElementById('task-panel')
    const createPanel = document.getElementById('create-panel')

    const taskOpen = taskPanel.style.transform === 'translateX(0px)' || taskPanel.style.transform === 'translateX(0)'
    const createOpen = createPanel.style.transform === 'translateX(0px)' || createPanel.style.transform === 'translateX(0)'

    if (taskOpen || createOpen) {
        openPanelLayout()
    }
})