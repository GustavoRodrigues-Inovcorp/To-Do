<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    // ===== AUTENTICAÇÃO =====

    public function test_guest_cannot_access_tasks(): void
    {
        auth()->logout();

        $this->get(route('tasks.index'))->assertRedirect(route('login'));
        $this->get(route('tasks.upcoming'))->assertRedirect(route('login'));
    }

    // ===== INDEX =====

    public function test_index_page_loads_successfully(): void
    {
        $this->get(route('tasks.index'))
            ->assertOk()
            ->assertViewIs('tasks.index');
    }

    public function test_index_shows_all_tasks(): void
    {
        Task::factory()->count(3)->create();

        $this->get(route('tasks.index'))
            ->assertOk()
            ->assertViewHas('tasks');
    }

    public function test_index_filters_by_status(): void
    {
        Task::factory()->create(['status' => 'pending']);
        Task::factory()->create(['status' => 'completed']);

        $response = $this->get(route('tasks.index', ['status' => 'pending']));

        $response->assertOk();
        $tasks = $response->viewData('tasks');
        $this->assertTrue($tasks->every(fn($t) => $t->status === 'pending'));
    }

    public function test_index_filters_by_priority(): void
    {
        Task::factory()->create(['priority' => 'high']);
        Task::factory()->create(['priority' => 'low']);

        $response = $this->get(route('tasks.index', ['priority' => 'high']));

        $tasks = $response->viewData('tasks');
        $this->assertTrue($tasks->every(fn($t) => $t->priority === 'high'));
    }

    public function test_index_filters_by_due_date(): void
    {
        Task::factory()->create(['due_date' => '2026-05-04']);
        Task::factory()->create(['due_date' => '2026-06-01']);

        $response = $this->get(route('tasks.index', ['due_date' => '2026-05-04']));

        $tasks = $response->viewData('tasks');
        $this->assertTrue($tasks->every(fn($t) => $t->due_date->toDateString() === '2026-05-04'));
    }

    // ===== UPCOMING =====

    public function test_upcoming_page_loads_successfully(): void
    {
        $this->get(route('tasks.upcoming'))
            ->assertOk()
            ->assertViewIs('tasks.upcoming');
    }

    public function test_upcoming_shows_today_tasks(): void
    {
        Task::factory()->create(['due_date' => now()->toDateString()]);

        $response = $this->get(route('tasks.upcoming'));

        $this->assertCount(1, $response->viewData('todayTasks'));
    }

    public function test_upcoming_shows_tomorrow_tasks(): void
    {
        Task::factory()->create(['due_date' => now()->addDay()->toDateString()]);

        $response = $this->get(route('tasks.upcoming'));

        $this->assertCount(1, $response->viewData('tomorrowTasks'));
    }

    // ===== STORE =====

    public function test_can_create_task(): void
    {
        $data = [
            'title'    => 'Nova tarefa de teste',
            'priority' => 'high',
            'status'   => 'pending',
        ];

        $this->post(route('tasks.store'), $data)
            ->assertRedirect();

        $this->assertDatabaseHas('tasks', ['title' => 'Nova tarefa de teste']);
    }

    public function test_cannot_create_task_without_title(): void
    {
        $this->post(route('tasks.store'), [
            'priority' => 'medium',
            'status'   => 'pending',
        ])->assertSessionHasErrors('title');
    }

    public function test_cannot_create_task_with_invalid_priority(): void
    {
        $this->post(route('tasks.store'), [
            'title'    => 'Teste',
            'priority' => 'invalid',
            'status'   => 'pending',
        ])->assertSessionHasErrors('priority');
    }

    public function test_cannot_create_task_with_past_due_date(): void
    {
        $this->post(route('tasks.store'), [
            'title'    => 'Teste',
            'priority' => 'medium',
            'status'   => 'pending',
            'due_date' => '2000-01-01',
        ])->assertSessionHasErrors('due_date');
    }

    public function test_can_create_task_via_json(): void
    {
        $response = $this->postJson(route('tasks.store'), [
            'title'    => 'Tarefa via API',
            'priority' => 'low',
            'status'   => 'pending',
        ]);

        $response->assertCreated()
            ->assertJsonFragment(['title' => 'Tarefa via API']);
    }

    // ===== UPDATE =====

    public function test_can_update_task(): void
    {
        $task = Task::factory()->create(['title' => 'Título original']);

        $this->put(route('tasks.update', $task), [
            'title'    => 'Título atualizado',
            'priority' => 'high',
            'status'   => 'pending',
        ])->assertRedirect();

        $this->assertDatabaseHas('tasks', ['title' => 'Título atualizado']);
    }

    public function test_cannot_update_task_without_title(): void
    {
        $task = Task::factory()->create();

        $this->put(route('tasks.update', $task), [
            'priority' => 'medium',
            'status'   => 'pending',
        ])->assertSessionHasErrors('title');
    }

    public function test_can_update_task_via_json(): void
    {
        $task = Task::factory()->create();

        $this->putJson(route('tasks.update', $task), [
            'title'    => 'Atualizado via JSON',
            'priority' => 'medium',
            'status'   => 'completed',
        ])->assertOk()
          ->assertJsonFragment(['title' => 'Atualizado via JSON']);
    }

    // ===== UPDATE STATUS =====

    public function test_can_update_task_status(): void
    {
        $task = Task::factory()->create(['status' => 'pending']);

        $this->patchJson(route('tasks.updateStatus', $task), [
            'status' => 'completed',
        ])->assertOk()
          ->assertJsonFragment(['status' => 'completed']);

        $this->assertDatabaseHas('tasks', [
            'id'     => $task->id,
            'status' => 'completed',
        ]);
    }

    public function test_cannot_update_status_with_invalid_value(): void
    {
        $task = Task::factory()->create();

        $this->patchJson(route('tasks.updateStatus', $task), [
            'status' => 'invalid_status',
        ])->assertUnprocessable();
    }

    // ===== SHOW JSON =====

    public function test_can_fetch_task_as_json(): void
    {
        $task = Task::factory()->create(['title' => 'Tarefa JSON']);

        $this->getJson(route('tasks.showJson', $task))
            ->assertOk()
            ->assertJsonFragment(['title' => 'Tarefa JSON']);
    }

    // ===== DESTROY =====

    public function test_can_delete_task(): void
    {
        $task = Task::factory()->create();

        $this->delete(route('tasks.destroy', $task))
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_deleted_task_no_longer_appears_in_index(): void
    {
        $task = Task::factory()->create(['title' => 'Tarefa a eliminar']);

        $this->delete(route('tasks.destroy', $task));

        $this->get(route('tasks.index'))
            ->assertDontSee('Tarefa a eliminar');
    }
}