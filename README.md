# Taskly — Aplicação de Gestão de Tarefas
 
Aplicação web para gestão de tarefas pessoais, desenvolvida com Laravel 12, Tailwind CSS e Vue 3. Permite criar, editar, eliminar e organizar tarefas por prioridade, estado e data de vencimento, com autenticação de utilizadores via Laravel Breeze.
 
---
 
## Tecnologias
 
- **Backend:** Laravel 12 (PHP)
- **Frontend:** Tailwind CSS, Vue 3 (Composition API)
- **Base de dados:** MySQL local / PostgreSQL no Render
- **Autenticação:** Laravel Breeze
- **Build:** Vite
---
 
## Funcionalidades
 
- Autenticação completa (registo, login, perfil)
- Criação de tarefas com título, descrição, prioridade e data de vencimento
- Listagem de tarefas com filtros por estado, prioridade e data
- Vista de planeamento — tarefas agrupadas por Hoje, Amanhã e Esta Semana
- Edição e eliminação de tarefas via painel lateral (sem refresh)
- Marcação de tarefas como concluídas em tempo real
- Interface totalmente responsiva (desktop, tablet, mobile)
---
 
## Requisitos
 
- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL
---
 
## Instalação
 
```bash
# 1. Clonar o repositório
git clone https://github.com/GustavoRodrigues-Inovcorp/To-Do.git
cd To-Do
 
# 2. Instalar dependências PHP
composer install
 
# 3. Instalar dependências JS
npm install
 
# 4. Copiar e configurar o ficheiro de ambiente
cp .env.example .env
php artisan key:generate
```
 
Edita o ficheiro `.env` com as credenciais da tua base de dados:
 
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo
DB_USERNAME=root
DB_PASSWORD=
```
 
```bash
# 5. Executar as migrações
php artisan migrate
 
# 6. Compilar os assets
npm run dev
```
 
---
 
## Utilização
 
```bash
# Iniciar o servidor de desenvolvimento
php artisan serve
```
 
Acede a [http://localhost:8000](http://localhost:8000), regista uma conta e começa a gerir as tuas tarefas.
 
---

## Deploy No Render

Este projeto já inclui um `render.yaml` e um `Dockerfile` para deploy no Render.

1. Faz push do projeto para GitHub.
2. No Render, cria um novo Blueprint a partir deste repositório.
3. O Render vai criar a aplicação web e a base de dados PostgreSQL automaticamente.
4. As variáveis `APP_KEY`, `DB_URL` e restantes settings de produção são configuradas pelo Blueprint.
5. A URL pública do Render é lida automaticamente via `RENDER_EXTERNAL_URL`, por isso não precisas de definir `APP_URL` manualmente.

Se preferires configurar manualmente, usa uma Web Service do tipo Docker, liga um banco PostgreSQL e define `DB_CONNECTION=pgsql`, `SESSION_DRIVER=cookie`, `CACHE_STORE=database` e `QUEUE_CONNECTION=sync`.

---
 
## Estrutura do Projeto
 
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/               # Controladores de autenticação (Breeze)
│   │   ├── ProfileController   # Gestão de perfil
│   │   └── TaskController      # CRUD de tarefas
│   └── Requests/
│       ├── StoreTaskRequest    # Validação ao criar tarefa
│       └── UpdateTaskRequest   # Validação ao editar tarefa
├── Models/
│   ├── Task.php
│   └── User.php
resources/
├── js/
│   ├── components/
│   │   └── TaskPanel.vue       # Painel de criar/editar (Vue 3)
│   ├── app.js
│   ├── slide-create.js
│   └── slide-edit.js
└── views/
    ├── layouts/
    │   └── app.blade.php       # Layout principal responsivo
    └── tasks/
        ├── _upcoming-row.blade.php
        ├── _slide-create.blade.php
        ├── _slide-edit.blade.php
        ├── index.blade.php     # Todas as tarefas
        └── upcoming.blade.php  # Vista de planeamento
```
 
---
 
## Padrão de Commits
 
Este projeto segue o padrão [Conventional Commits](https://www.conventionalcommits.org/):
 
| Prefixo | Utilização |
|---|---|
| `feat:` | Nova funcionalidade |
| `fix:` | Correção de bug |
| `style:` | Alterações de CSS/UI |
| `refactor:` | Reorganização de código |
| `chore:` | Configurações e dependências |
 
---
 
## Licença
 
Projeto desenvolvido para fins académicos.