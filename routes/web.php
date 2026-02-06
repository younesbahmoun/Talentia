<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AmieController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TaskController;
Route::get('/', function () {
    $user = Auth::user();
    return view('utilisateur/dashboard', [
        'currentPage' => 'dashboard',
        'users' => $user,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');
// Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
// Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
// Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

// profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/home', function(Request $request) {
    $languages = ['PHP', 'JavaScript', 'Python', 'Java', 'C#'];
    // $name = "Younes";
    $name = null;
    // $languages = [];
    return view('home', compact('languages', 'name'));
})->name('home');

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
    return view('utilisateur/dashboard', [
        'currentPage' => 'dashboard',
        'users' => $user,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Profil public d'un utilisateur
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show')->middleware(['auth', 'verified']);

// Route::middleware('auth')->group(function () {
//     // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// })

Route::get('/profiles', [ProfileController::class, 'allProfiles'])->name('profiles.all')->middleware(['auth', 'verified']);
Route::post('/profiles', [ProfileController::class, 'allProfiles'])->name('profiles.all')->middleware(['auth', 'verified']);
Route::get('/profiles/{id}', [ProfileController::class, 'showDetails'])
->where('id', '\d+')
->name('profile.detail')
->middleware(['auth', 'verified']);

// Route pour ajouter un ami
Route::get('/friends/add', [UserController::class, 'ajouterAmie'])
->name('ajouter.amie')
->middleware(['auth', 'verified']);

Route::get('/friends/accept', [UserController::class, 'accepterAmie'])
->name('accepter.amie')
->middleware(['auth', 'verified']);

Route::get('/friends/decline', [UserController::class, 'refuserAmie'])
->name('refuser.amie')
->middleware(['auth', 'verified']);

Route::get('/network', [AmieController::class, 'network'])->name('network');
Route::get('/notifications', [UserController::class, 'notifications'])->name('notifications')->middleware(['auth', 'verified']);

Route::get('/offres', [OffreController::class, 'index'])->name('offres.index');

require __DIR__.'/auth.php';