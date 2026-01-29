<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $user = Auth::user();
    return view('dashboard', [
        'currentPage' => 'dashboard',
        'user' => $user,
    ]);
});

Route::get('/profile', function () {
    $user = Auth::user();
    return view('profile', [
        'currentPage' => 'profile',
        'user' => $user,
    ]);
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    return view('dashboard', [
        'currentPage' => 'dashboard',
        'users' => $user,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';