<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::inertia('/home', 'Welcome')->name('home');

Route::redirect('/', 'todo');
// Route::inertia('todo', 'Todo')->name('todo');

Route::get('todo', [TodoController::class, 'index'])->name('todo.index');
Route::post('todo/create', [TodoController::class, 'create'])->name('todo.create');
Route::post('todo/reorder', [TodoController::class, 'reorder'])->name('todo.reorder');
Route::put('todo/update/{item}', [TodoController::class, 'update'])->name('todo.update');
Route::delete('todo/delete/{item}', [TodoController::class, 'delete'])->name('todo.delete');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
