<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TaskController::class, 'index'])->name('task.index');

Route::post('/tasks', [TaskController::class, 'store'])->name('task.store');

Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])
    ->name('task.updateStatus');

Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
    ->name('task.destroy');
