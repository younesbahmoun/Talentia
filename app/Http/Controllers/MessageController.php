<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\MessageRead;
use App\Events\NewNotification;
use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MessageController extends Controller
{
    /**
     * Send a text message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'body' => 'required|string|max:5000',
        ]);

        $user = Auth::user();
        $conversation = Conversation::findOrFail($request->conversation_id);

        // Authorization: only participants can send
        if (!$conversation->hasParticipant($user->id)) {
            abort(403);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => $request->body,
        ]);

        $message->load('sender');

        // Broadcast the message
        $this->broadcastSafely(new MessageSent($message), true);

        // Send notification to the other user
        $otherUser = $conversation->getOtherUser($user->id);
        $otherUser->notify(new NewMessageNotification($message));

        // Broadcast notification event
        $this->broadcastSafely(new NewNotification(
            $otherUser->id,
            'new_message',
            [
                'message' => $user->name . ' ' . ($user->prenom ?? '') . ' vous a envoyé un message.',
                'conversation_id' => $conversation->id,
                'sender_name' => $user->name . ' ' . ($user->prenom ?? ''),
                'sender_photo' => $user->photo,
            ]
        ));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => $this->messagePayload($message),
                'status' => 'success',
            ]);
        }

        return back();
    }

    /**
     * Upload a file in a conversation.
     */
    public function uploadFile(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,csv',
            'body' => 'nullable|string|max:5000',
        ]);

        $user = Auth::user();
        $conversation = Conversation::findOrFail($request->conversation_id);

        if (!$conversation->hasParticipant($user->id)) {
            abort(403);
        }

        $file = $request->file('file');
        $path = $file->store('chat-files', 'public');

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => $request->body,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getMimeType(),
        ]);

        $message->load('sender');

        // Broadcast the message
        $this->broadcastSafely(new MessageSent($message), true);

        // Send notification
        $otherUser = $conversation->getOtherUser($user->id);
        $otherUser->notify(new NewMessageNotification($message));

        $this->broadcastSafely(new NewNotification(
            $otherUser->id,
            'new_message',
            [
                'message' => $user->name . ' vous a envoyé un fichier.',
                'conversation_id' => $conversation->id,
                'sender_name' => $user->name . ' ' . ($user->prenom ?? ''),
                'sender_photo' => $user->photo,
            ]
        ));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => $this->messagePayload($message),
                'status' => 'success',
            ]);
        }

        return back();
    }

    /**
     * Mark all messages in a conversation as read.
     */
    public function markAsRead(Conversation $conversation)
    {
        $user = Auth::user();

        if (!$conversation->hasParticipant($user->id)) {
            abort(403);
        }

        $updated = Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        if ($updated > 0) {
            $this->broadcastSafely(new MessageRead($conversation->id, $user->id), true);

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

        return response()->json(['status' => 'ok', 'updated' => $updated]);
    }

    /**
     * Get total unread message count for the authenticated user.
     */
    public function unreadCount()
    {
        $count = Auth::user()->totalUnreadMessages();
        return response()->json(['count' => $count]);
    }

    /**
     * Retrieve latest conversation messages after a given message id.
     */
    public function latest(Request $request, Conversation $conversation)
    {
        $user = Auth::user();

        if (!$conversation->hasParticipant($user->id)) {
            abort(403);
        }

        $afterId = max((int) $request->query('after_id', 0), 0);

        $messages = Message::with('sender')
            ->where('conversation_id', $conversation->id)
            ->when($afterId > 0, function ($query) use ($afterId) {
                $query->where('id', '>', $afterId);
            })
            ->orderBy('id', 'asc')
            ->limit(50)
            ->get()
            ->map(function (Message $message) {
                return $this->messagePayload($message);
            })
            ->values();

        return response()->json(['messages' => $messages]);
    }

    private function broadcastSafely(object $event, bool $toOthers = false): void
    {
        try {
            $pendingBroadcast = broadcast($event);

            if ($toOthers) {
                $pendingBroadcast->toOthers();
            }
        } catch (Throwable $exception) {
            Log::warning('Real-time broadcast failed', [
                'event' => $event::class,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    private function messagePayload(Message $message): array
    {
        if (!$message->relationLoaded('sender')) {
            $message->load('sender');
        }

        return [
            'id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'sender_id' => $message->sender_id,
            'sender_name' => $message->sender->name . ' ' . ($message->sender->prenom ?? ''),
            'sender_photo' => $message->sender->photo,
            'body' => $message->body,
            'file_path' => $message->file_path,
            'file_name' => $message->file_name,
            'file_type' => $message->file_type,
            'is_read' => (bool) $message->is_read,
            'created_at' => $message->created_at?->toISOString(),
        ];
    }
}
