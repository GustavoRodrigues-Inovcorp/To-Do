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

## Servidor

Esta secção documenta a parte do servidor — a API e as rotinas de backend associadas — e como a usar localmente e em produção.

- **Resumo:** foi adicionada uma camada API REST para gerir `tasks` programaticamente, juntamente com migrações que relacionam tarefas a utilizadores, factories e testes automatizados.

- **Endpoints principais (API REST):**
    - `GET /api/tasks` — lista tarefas do utilizador autenticado
    - `POST /api/tasks` — cria nova tarefa
    - `GET /api/tasks/{id}` — mostra detalhe de uma tarefa
    - `PUT /api/tasks/{id}` — atualiza uma tarefa
    - `DELETE /api/tasks/{id}` — elimina uma tarefa
    - `POST /api/auth/login` — autenticação (se aplicável)
    - `POST /api/auth/register` — registo de utilizador (se aplicável)
    - `GET /api/user` — dados do utilizador autenticado

- **Migrações e dados:**
    - Executa `php artisan migrate` para aplicar as migrações (inclui a migração que adiciona `user_id` a `tasks`).
    - Para popular com dados de exemplo, executa `php artisan db:seed` ou usa as factories (`database/factories/TaskFactory.php`).

- **Variáveis de ambiente importantes:**
    - `APP_KEY`, `APP_ENV`, `APP_DEBUG`, `APP_URL`
    - `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (ou `DATABASE_URL`)
    - `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD` (se enviares emails)
    - `CACHE_DRIVER`, `SESSION_DRIVER`, `QUEUE_CONNECTION` (ajusta conforme produção)

- **Comandos úteis de desenvolvimento e operação:**
    - `php artisan migrate --seed` — aplicar migrações e seeders
    - `php artisan queue:work` — processar filas (se usares jobs)
    - `php artisan test` — correr testes automatizados
    - `npm run dev` / `npm run build` — assets front-end

- **Autenticação e segurança:**
    - A API está protegida por autenticação; usa cookies de sessão ou tokens (conforme configuração actual). Garante que `APP_URL` e cookies `SameSite` estão configurados para produção.

- **Dicas de deploy:**
    - Confirma que `APP_KEY` está definida em produção.
    - Configura corretamente `DB_*` ou `DATABASE_URL` para apontar para a base de dados de produção.
    - Se usares filas/cron, assegura que `php artisan queue:work` e `php artisan schedule:run` estão configurados no ambiente de produção.

Se quiseres, adapto esta secção com detalhes mais específicos (ex.: exemplos de respostas JSON, política de autenticação usada — Sanctum/Passport/session — ou documentação OpenAPI).

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