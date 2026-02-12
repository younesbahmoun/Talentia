<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'prenom',
        'role',
        'specialite',
        'photo',
        'bio',
        'email',
        'password',
        'is_online',
        'last_seen_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_online' => 'boolean',
            'last_seen_at' => 'datetime',
        ];
    }

    /**
     * Relation One-to-One avec Profile
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function friend()
    {
        return $this->hasMany(Friend::class);
    }

    public function offre()
    {
        return $this->hasMany(Offre::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Conversations where the user is participant.
     */
    public function conversations()
    {
        return Conversation::where('user_one_id', $this->id)
            ->orWhere('user_two_id', $this->id);
    }

    /**
     * Messages sent by this user.
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Check if this user is friends with another user (accepted).
     */
    public function isFriendWith(int $userId): bool
    {
        return Friend::where('status', 'accepted')
            ->where(function ($query) use ($userId) {
                $query->where(function ($q) use ($userId) {
                    $q->where('user_id', $this->id)
                      ->where('friend_id', $userId);
                })->orWhere(function ($q) use ($userId) {
                    $q->where('user_id', $userId)
                      ->where('friend_id', $this->id);
                });
            })
            ->exists();
    }

    /**
     * Get total unread message count across all conversations.
     */
    public function totalUnreadMessages(): int
    {
        $conversationIds = Conversation::where('user_one_id', $this->id)
            ->orWhere('user_two_id', $this->id)
            ->pluck('id');

        return Message::whereIn('conversation_id', $conversationIds)
            ->where('sender_id', '!=', $this->id)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Mark the user as online.
     */
    public function markOnline(): void
    {
        $this->update([
            'is_online' => true,
            'last_seen_at' => now(),
        ]);
    }

    /**
     * Mark the user as offline.
     */
    public function markOffline(): void
    {
        $this->update([
            'is_online' => false,
            'last_seen_at' => now(),
        ]);
    }
}
