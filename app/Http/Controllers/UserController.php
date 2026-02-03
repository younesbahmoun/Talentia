<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Affiche le profil public d'un utilisateur.
     */
    public function show(User $user)
    {
        $user = Auth::user();
//                 $user = User::create([
//     'name' => 'Doe',
//     'prenom' => 'John',
//     'role' => 'developer',
//     'specialite' => 'Laravel',
//     'photo' => 'path/to/photo.jpg',
//     'bio' => 'A passionate developer',
//     'email' => 'joh@example.com',
//     'password' => bcrypt('password'),
// ]);

// // Profile is automatically created!
// dd($user->profile); // Will show the created profile
        return view('utilisateur/profile/profile', compact('user'));
    }

    public function ajouterAmie() {
        Friend::create([
            'user_id' => Auth::id(),
            'friend_id' => request('friend_id'),
            'status' => 'pending',
        ]);
        
    }
}