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
use Illuminate\Support\Facades\Storage;

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
        broadcast(new MessageSent($message))->toOthers();

        // Send notification to the other user
        $otherUser = $conversation->getOtherUser($user->id);
        $otherUser->notify(new NewMessageNotification($message));

        // Broadcast notification event
        broadcast(new NewNotification(
            $otherUser->id,
            'new_message',
            [
                'message' => $user->name . ' ' . ($user->prenom ?? '') . ' vous a envoyé un message.',
                'conversation_id' => $conversation->id,
                'sender_name' => $user->name . ' ' . ($user->prenom ?? ''),
                'sender_photo' => $user->photo,
            ]
        ));

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
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
        broadcast(new MessageSent($message))->toOthers();

        // Send notification
        $otherUser = $conversation->getOtherUser($user->id);
        $otherUser->notify(new NewMessageNotification($message));

        broadcast(new NewNotification(
            $otherUser->id,
            'new_message',
            [
                'message' => $user->name . ' vous a envoyé un fichier.',
                'conversation_id' => $conversation->id,
                'sender_name' => $user->name . ' ' . ($user->prenom ?? ''),
                'sender_photo' => $user->photo,
            ]
        ));

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
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
            broadcast(new MessageRead($conversation->id, $user->id))->toOthers();
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
}
