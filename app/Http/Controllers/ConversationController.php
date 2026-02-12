<?php

namespace App\Http\Controllers;

use App\Events\MessageRead;
use App\Events\NewNotification;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class ConversationController extends Controller
{
    /**
     * List all conversations for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();
        $conversations = Conversation::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with(['userOne', 'userTwo', 'latestMessage.sender'])
            ->get()
            ->sortByDesc(function ($conversation) {
                return $conversation->latestMessage?->created_at;
            })
            ->values();

        // Add unread counts and other user info
        $conversations = $conversations->map(function ($conversation) use ($user) {
            $conversation->unread_count = $conversation->unreadCountFor($user->id);
            $conversation->other_user = $conversation->getOtherUser($user->id);
            return $conversation;
        });

        return view('conversations.index', compact('conversations'));
    }

    /**
     * Show a single conversation.
     */
    public function show(Conversation $conversation)
    {
        $user = Auth::user();

        // Authorization: only participants can view
        if (!$conversation->hasParticipant($user->id)) {
            abort(403, 'Vous n\'avez pas accès à cette conversation.');
        }

        // Mark messages as read
        $updated = Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        if ($updated > 0) {
            $this->broadcastSafely(new MessageRead($conversation->id, $user->id));

            $this->broadcastSafely(new NewNotification(
                $user->id,
                'messages_read',
                [
                    'silent' => true,
                    'conversation_id' => $conversation->id,
                    'updated' => $updated,
                ]
            ));
        }

        // Load messages with sender
        $messages = $conversation->messages()->with('sender')->get();
        $otherUser = $conversation->getOtherUser($user->id);

        return view('conversations.show', compact('conversation', 'messages', 'otherUser'));
    }

    /**
     * Start or get an existing conversation with a friend.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        $otherUserId = (int) $request->user_id;

        // Cannot start conversation with yourself
        if ($otherUserId === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas démarrer une conversation avec vous-même.');
        }

        // Check friendship status - must be accepted friends
        if (!$user->isFriendWith($otherUserId)) {
            return back()->with('error', 'Vous devez être amis pour envoyer des messages.');
        }

        // Create or find existing conversation
        $conversation = Conversation::between($user->id, $otherUserId);

        return redirect()->route('conversations.show', $conversation);
    }

    private function broadcastSafely(object $event): void
    {
        try {
            broadcast($event);
        } catch (Throwable $exception) {
            Log::warning('Real-time broadcast failed in conversation flow', [
                'event' => $event::class,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
