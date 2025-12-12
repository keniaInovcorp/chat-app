<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Admin\RoomsIndex;
use App\Livewire\Admin\UsersIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('chat.index');
});

// Authenticated user routes
Route::middleware('auth')->group(function () {
    // Single-page chat, using the dashboard layout as the chat container
    Route::get('/chat', function () {
        return view('dashboard');
    })->name('chat.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes (user and room management)
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/users', UsersIndex::class)->name('admin.users');
        Route::get('/rooms', RoomsIndex::class)->name('admin.rooms');
});

require __DIR__ . '/auth.php';
