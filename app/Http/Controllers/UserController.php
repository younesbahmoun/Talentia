<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friend;
use App\Events\NewNotification;
use App\Events\UserStatusChanged;
use App\Notifications\FriendRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Affiche le profil public d'un utilisateur.
     */
    public function show()
    {
        $user = User::find(Auth::id());
        return view('utilisateur/profile/profile', compact('user'));
    }

    public function ajouterAmie(Request $request)
    {
        // Prevent adding yourself as a friend
        if ($request->friend_id == Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas vous ajouter comme ami.');
        }

        // Check if request already exists
        $existing = Friend::where('user_id', Auth::id())
            ->where('friend_id', $request->friend_id)
            ->first();

        if ($existing) {
            return back()->with('info', 'Demande d\'ami déjà envoyée.');
        }

        Friend::create([
            'user_id' => Auth::id(),
            'friend_id' => $request->friend_id,
            'status' => 'pending',
        ]);

        // Send notification to the recipient
        $receiver = User::find($request->friend_id);
        $sender = Auth::user();

        if ($receiver) {
            $receiver->notify(new FriendRequestNotification($sender));

            // Broadcast real-time notification
            broadcast(new NewNotification(
                $receiver->id,
                'friend_request',
                [
                    'message' => $sender->name . ' ' . ($sender->prenom ?? '') . ' vous a envoyé une demande d\'ami.',
                    'sender_id' => $sender->id,
                    'sender_name' => $sender->name . ' ' . ($sender->prenom ?? ''),
                    'sender_photo' => $sender->photo,
                ]
            ));
        }

        return back()->with('success', 'Demande d\'ami envoyée avec succès.');
    }

    public function accepterAmie(Request $request)
    {
        $friend = Friend::where('friend_id', Auth::id())
            ->where('user_id', $request->friend_id)
            ->where('status', 'pending')
            ->first();

        if ($friend) {
            $friend->update(['status' => 'accepted']);

            // Notify the original sender that their request was accepted
            $sender = User::find($request->friend_id);
            if ($sender) {
                broadcast(new NewNotification(
                    $sender->id,
                    'friend_accepted',
                    [
                        'message' => Auth::user()->name . ' ' . (Auth::user()->prenom ?? '') . ' a accepté votre demande d\'ami.',
                        'sender_id' => Auth::id(),
                        'sender_name' => Auth::user()->name . ' ' . (Auth::user()->prenom ?? ''),
                    ]
                ));
            }
        }

        return back()->with('success', 'Demande d\'ami acceptée.');
    }

    public function refuserAmie(Request $request)
    {
        $friend = Friend::where('friend_id', Auth::id())
            ->where('user_id', $request->friend_id)
            ->where('status', 'pending')
            ->first();

        if ($friend) {
            $friend->delete();
        }

        return back()->with('success', 'Demande d\'ami refusée.');
    }

    public function network()
    {
        return view('utilisateur.amie');
    }

    public function notifications()
    {
        $notifications = Auth::user()->notifications;
        Auth::user()->unreadNotifications->markAsRead();
        return view('utilisateur.notifications', compact('notifications'));
    }

    /**
     * Get the counts for unread notifications (JSON API).
     */
    public function unreadNotificationCount()
    {
        return response()->json([
            'count' => Auth::user()->unreadNotifications->count(),
        ]);
    }
}