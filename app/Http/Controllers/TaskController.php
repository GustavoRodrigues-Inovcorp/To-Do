<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query();

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('priority')) {
            $query->byPriority($request->priority);
        }

        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        $tasks = $query->orderBy('due_date')->orderBy('created_at', 'desc')->paginate(15);

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(StoreTaskRequest $request)
    {
        Task::create($request->validated());

        return redirect()->route('tasks.index')
            ->with('success', 'Tarefa criada com sucesso!');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return redirect()->route('tasks.index')
            ->with('success', 'Tarefa atualizada!');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Tarefa eliminada.');
    }

    public function upcoming()
    {
        $today = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();
        $endOfWeek = now()->endOfWeek()->toDateString();

        $todayTasks = Task::whereDate('due_date', $today)
            ->where('status', '!=', 'completed')
            ->orderBy('priority')
            ->get();

        $tomorrowTasks = Task::whereDate('due_date', $tomorrow)
            ->where('status', '!=', 'completed')
            ->orderBy('priority')
            ->get();

        $thisWeekTasks = Task::whereDate('due_date', '>', $tomorrow)
            ->whereDate('due_date', '<=', $endOfWeek)
            ->where('status', '!=', 'completed')
            ->orderBy('due_date')
            ->get();

        $totalUpcoming = $todayTasks->count() + $tomorrowTasks->count() + $thisWeekTasks->count();

        return view('tasks.upcoming', compact(
            'todayTasks', 'tomorrowTasks', 'thisWeekTasks', 'totalUpcoming'
        ));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => ['required', 'in:pending,completed'],
        ]);

        $task->update(['status' => $request->status]);

        return back()->with('success', 'Estado atualizado.');
    }
}