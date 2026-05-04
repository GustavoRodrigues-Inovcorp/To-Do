window.openCreate = function (dueDate = null) {
    // Fecha o painel de editar se estiver aberto
    document.getElementById('task-panel').style.transform = 'translateX(calc(100% + 2rem))'

    // Limpa os campos
    document.getElementById('create-title').value       = ''
    document.getElementById('create-description').value = ''
    document.getElementById('create-priority').value    = 'medium'
    document.getElementById('create-status').value      = 'pending'
    document.getElementById('create-due-date').value    = dueDate ?? ''
    document.getElementById('create-title-error').classList.add('hidden')

    // Abre o painel
    document.getElementById('create-panel').style.transform = 'translateX(0)'

    openPanelLayout()

    setTimeout(() => document.getElementById('create-title').focus(), 300)
}

window.closeCreate = function () {
    document.getElementById('create-panel').style.transform = 'translateX(calc(100% + 2rem))'
    closePanelLayout()
}

window.submitCreate = function () {
    const title = document.getElementById('create-title').value.trim()

    if (!title) {
        document.getElementById('create-title-error').classList.remove('hidden')
        document.getElementById('create-title').focus()
        return
    }

    const btn = document.querySelector('[onclick="submitCreate()"]')
    btn.textContent = 'A criar...'
    btn.disabled = true

    const currentUrl = window.location.href

    const data = {
        title,
        description: document.getElementById('create-description').value,
        priority:    document.getElementById('create-priority').value,
        status:      document.getElementById('create-status').value,
        due_date:    document.getElementById('create-due-date').value || null,
    }

    fetch('/tasks', {
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
    .then(() => {
        btn.textContent = 'Criado'
        setTimeout(() => {
            window.location.href = currentUrl
        }, 600)
    })
    .catch(err => {
        console.error('Erro ao criar:', err)
        btn.textContent = 'Criar Tarefa'
        btn.disabled = false
    })
}