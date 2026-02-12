<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    protected $fillable = ['user_one_id', 'user_two_id'];

    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    /**
     * Get the other participant in the conversation.
     */
    public function getOtherUser(int $userId): User
    {
        return $this->user_one_id === $userId
            ? $this->userTwo
            : $this->userOne;
    }

    /**
     * Get unread message count for a specific user.
     */
    public function unreadCountFor(int $userId): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Check if a user is a participant in this conversation.
     */
    public function hasParticipant(int $userId): bool
    {
        return $this->user_one_id === $userId || $this->user_two_id === $userId;
    }

    /**
     * Find or create a conversation between two users.
     */
    public static function between(int $userOneId, int $userTwoId): self
    {
        $ids = [min($userOneId, $userTwoId), max($userOneId, $userTwoId)];

        return self::firstOrCreate(
            ['user_one_id' => $ids[0], 'user_two_id' => $ids[1]]
        );
    }
}
