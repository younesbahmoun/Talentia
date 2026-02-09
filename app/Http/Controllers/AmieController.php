<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Friend;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;


class AmieController extends Controller {

    public function getAllInvitation() {
        $friends = Friend::with('friend.profile')
            ->where('friend_id', Auth::id())
            ->where('status', 'pending')
            ->get();
        return $friends;
    }

    public function getAllAmie() {
        $friends = Friend::with('friend.profile')
            ->where(function($query) {
                $query->where('user_id', Auth::id())
                    ->orWhere('friend_id', Auth::id());
            })
            ->where('status', 'accepted')
            ->get();
            
        return $friends;
    }

    public function network() {
        $invitations = $this->getAllInvitation();
        $friends = $this->getAllAmie();

        return view('utilisateur.amie', [
            'invitations' => $invitations,
            'friends' => $friends,
        ]);
    }
}
