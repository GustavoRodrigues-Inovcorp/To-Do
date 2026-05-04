<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('tasks.upcoming')
        : view('welcome');
});

// Dashboard do Breeze (podes redirecionar para upcoming)
Route::get('/dashboard', function () {
    return redirect()->route('tasks.upcoming');
})->middleware(['auth', 'verified'])->name('dashboard');

// Perfil do Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rotas das tarefas — protegidas por auth
Route::middleware('auth')->group(function () {
    Route::get('/tasks/upcoming', [TaskController::class, 'upcoming'])->name('tasks.upcoming');
    Route::get('/tasks/{task}/json', [TaskController::class, 'showJson'])->name('tasks.showJson');
    Route::resource('tasks', TaskController::class);
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
});

require __DIR__.'/auth.php';