<?php

namespace Tests\Unit;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_is_completed_returns_true_when_completed(): void
    {
        $task = Task::factory()->make(['status' => 'completed']);
        $this->assertTrue($task->isCompleted());
    }

    public function test_task_is_completed_returns_false_when_pending(): void
    {
        $task = Task::factory()->make(['status' => 'pending']);
        $this->assertFalse($task->isCompleted());
    }

    public function test_scope_by_status_filters_correctly(): void
    {
        Task::factory()->create(['status' => 'pending']);
        Task::factory()->create(['status' => 'completed']);

        $pending = Task::byStatus('pending')->get();
        $this->assertTrue($pending->every(fn($t) => $t->status === 'pending'));
    }

    public function test_scope_by_priority_filters_correctly(): void
    {
        Task::factory()->create(['priority' => 'high']);
        Task::factory()->create(['priority' => 'low']);

        $high = Task::byPriority('high')->get();
        $this->assertTrue($high->every(fn($t) => $t->priority === 'high'));
    }

    public function test_due_date_is_cast_to_date(): void
    {
        $task = Task::factory()->make(['due_date' => '2026-05-04']);
        $this->assertInstanceOf(\Carbon\Carbon::class, $task->due_date);
    }

    public function test_task_fillable_fields(): void
    {
        $task = Task::factory()->make([
            'title'       => 'Teste',
            'description' => 'Descrição',
            'priority'    => 'high',
            'status'      => 'pending',
            'due_date'    => '2026-12-31',
        ]);

        $this->assertEquals('Teste', $task->title);
        $this->assertEquals('high', $task->priority);
    }
}