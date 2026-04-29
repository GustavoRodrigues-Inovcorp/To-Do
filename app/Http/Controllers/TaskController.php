<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Listagem com filtros
    public function index(Request $request)
    {
        $query = Task::query();

        // Filtro por status (substitui o match anterior)
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

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => ['required', 'in:pending,in_progress,completed'],
        ]);

        $task->update(['status' => $request->status]);

        return back()->with('success', 'Estado atualizado.');
    }
}