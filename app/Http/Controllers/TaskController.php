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
        $query = auth()->user()->tasks();

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
        return view('tasks._slide-create');
    }

    public function store(StoreTaskRequest $request)
    {
        $task = auth()->user()->tasks()->create($request->validated());

        if ($request->expectsJson()) {
            return response()->json($task, 201);
        }

        return redirect()->back()->with('success', 'Tarefa criada com sucesso!');
    }

    public function show(Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);
        return view('tasks.edit', compact('task'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);
        $task->update($request->validated());

        if ($request->expectsJson()) {
            return response()->json($task->fresh());
        }

        return redirect()->back()->with('success', 'Tarefa atualizada!');
    }

    public function destroy(Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tarefa eliminada.');
    }

    public function upcoming()
    {
        $user = auth()->user();
        $today     = now()->toDateString();
        $tomorrow  = now()->addDay()->toDateString();
        $endOfWeek = now()->endOfWeek()->toDateString();

        $todayTasks = $user->tasks()->whereDate('due_date', $today)
            ->where('status', '!=', 'completed')->orderBy('priority')->get();

        $tomorrowTasks = $user->tasks()->whereDate('due_date', $tomorrow)
            ->where('status', '!=', 'completed')->orderBy('priority')->get();

        $thisWeekTasks = $user->tasks()->whereDate('due_date', '>', $tomorrow)
            ->whereDate('due_date', '<=', $endOfWeek)
            ->where('status', '!=', 'completed')->orderBy('due_date')->get();

        $totalUpcoming = $todayTasks->count() + $tomorrowTasks->count() + $thisWeekTasks->count();

        return view('tasks.upcoming', compact('todayTasks', 'tomorrowTasks', 'thisWeekTasks', 'totalUpcoming'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);
        $request->validate([
            'status' => ['required', 'in:pending,completed'],
        ]);

        $task->update(['status' => $request->status]);

        if ($request->expectsJson()) {
            return response()->json($task->fresh());
        }

        return back()->with('success', 'Estado atualizado.');
    }

    public function showJson(Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);
        return response()->json($task);
    }
}