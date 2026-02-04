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
    public function show()
    {
        $user = Auth::user();
        return view('utilisateur/profile/profile', compact('user'));
    }

    // public function ajouterAmie()
    // {
    //     $request = request();
        
    //     // Vérifier qu'on ne s'ajoute pas soi-même
    //     if ($request->friend_id == Auth::id()) {
    //         return back()->with('error', 'Vous ne pouvez pas vous ajouter comme ami.');
    //     }

    //     // Vérifier si la demande existe déjà
    //     $existing = Friend::where('user_id', Auth::id())
    //         ->where('friend_id', $request->friend_id)
    //         ->first();

    //     if ($existing) {
    //         return back()->with('info', 'Demande d\'ami déjà envoyée.');
    //     }

    //     Friend::create([
    //         'user_id' => Auth::id(),
    //         'friend_id' => $request->friend_id,
    //         'status' => 'pending',
    //     ]);

    //     return back()->with('success', 'Demande d\'ami envoyée avec succès.');
    // }

    // public function listeAmis()
    // {
    //     $friends = Friend::where('user_id', Auth::id())
    //         ->where('status', 'accepted')
    //         ->with('friend') // Assurez-vous que la relation 'friend' est définie dans le modèle Friend
    //         ->get();

    //     return view('utilisateur/friends/list', compact('friends'));
    // }

    public function ajouterAmie(Request $request) {
        Friend::create([
            'user_id' => Auth::id(),
            'friend_id' => $request->friend_id,
            'status' => 'pending',
        ]);
        return back()->with('success', 'Demande d\'ami envoyée avec succès.');
    }

    public function accepterAmie(Request $request) {
        $friend = Friend::where('user_id', Auth::id())
            ->where('friend_id', $request->friend_id)
            ->first();

        if ($friend) {
            $friend->update(['status' => 'accepted']);
        }

        return back()->with('success', 'Demande d\'ami acceptée.');
    }

    public function refuserAmie(Request $request) {
        $friend = Friend::where('user_id', Auth::id())
            ->where('friend_id', $request->friend_id)
            ->first();

        if ($friend) {
            $friend->delete();
        }

        return back()->with('success', 'Demande d\'ami refusée.');
    }

}