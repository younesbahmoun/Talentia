<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AmieController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard (Home)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('utilisateur/dashboard', [
        'currentPage' => 'dashboard',
        'users' => Auth::user(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    return redirect()->route('dashboard');
})->middleware(['auth', 'verified']);

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/profiles', [ProfileController::class, 'allProfiles'])->name('profiles.all')->middleware(['auth', 'verified']);
Route::get('/profiles/{id}', [ProfileController::class, 'showDetails'])
    ->where('id', '\d+')
    ->name('profile.detail')
    ->middleware(['auth', 'verified']);

/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
*/
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show')->middleware(['auth', 'verified']);

/*
|--------------------------------------------------------------------------
| Friends / Network
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/friends/add', [UserController::class, 'ajouterAmie'])->name('ajouter.amie');
    Route::get('/friends/accept', [UserController::class, 'accepterAmie'])->name('accepter.amie');
    Route::get('/friends/decline', [UserController::class, 'refuserAmie'])->name('refuser.amie');
    Route::get('/network', [AmieController::class, 'network'])->name('network');
    Route::get('/notifications', [UserController::class, 'notifications'])->name('notifications');
    Route::get('/notifications/unread-count', [UserController::class, 'unreadNotificationCount'])->name('notifications.unread-count');
});

/*
|--------------------------------------------------------------------------
| Conversations & Messaging
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations', [ConversationController::class, 'store'])->name('conversations.store');

    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/upload', [MessageController::class, 'uploadFile'])->name('messages.upload');
    Route::get('/messages/{conversation}/latest', [MessageController::class, 'latest'])->whereNumber('conversation')->name('messages.latest');
    Route::post('/messages/{conversation}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
    Route::get('/messages/unread-count', [MessageController::class, 'unreadCount'])->name('messages.unread-count');
});

/*
|--------------------------------------------------------------------------
| Offres (Job Offers)
|--------------------------------------------------------------------------
*/
Route::get('/offres', [OffreController::class, 'index'])->name('offres.index');
Route::get('/offres/detail/{id}', [OffreController::class, 'show'])->name('offres.detail');
Route::get('/offres/create', [OffreController::class, 'create'])->name('offres.create')->middleware(['auth', 'verified', 'role:recruteur']);
Route::post('/offres/create', [OffreController::class, 'store'])->name('offres.store')->middleware(['auth', 'verified', 'role:recruteur']);
Route::post('/offres/{id}/apply', [OffreController::class, 'apply'])->name('offres.apply')->middleware(['auth', 'verified', 'role:candidat']);
Route::get('/recruiter/candidats', [OffreController::class, 'candidats'])->name('recruiter.candidats')->middleware(['auth', 'verified', 'role:recruteur']);
Route::post('/applications/{application}/status', [OffreController::class, 'updateApplicationStatus'])->name('applications.update-status')->middleware(['auth', 'verified', 'role:recruteur']);

require __DIR__.'/auth.php';
