<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TaskController;

Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
// Route::post('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');

// Route::get('/', function () {
//     $user = Auth::user();
//     return view('dashboard', [
//         'currentPage' => 'dashboard',
//         'user' => $user,
//     ]);
// });

// Route::get('/users', [ProfileController::class, 'index'])->name('tasks.index');

// Route::get('/profile', function () {
//     // $user = Auth::user();
//     return view('profile', [
//         'currentPage' => 'profile',
//         // 'user' => $user,
//         ProfileController::class, 'edit',
//     ]);
// });

// Route::post('/profile', function () {
//     return view('profile' , [
//         'currentPage' => 'profile',
//         // Controller::class, 'update',
//         ProfileController::class, 'edit',
//     ])->name('profile.edit');
// });

Route::get('/dashboard', function () {
    $user = Auth::user();
    return view('dashboard', [
        'currentPage' => 'dashboard',
        'users' => $user,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';