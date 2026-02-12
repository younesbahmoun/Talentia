<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'body',
        'file_path',
        'file_name',
        'file_type',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Scope: only unread messages.
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    /**
     * Check if the message has a file attachment.
     */
    public function hasFile(): bool
    {
        return !empty($this->file_path);
    }

    /**
     * Check if the file is an image.
     */
    public function isImage(): bool
    {
        return $this->hasFile() && in_array($this->file_type, [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
        ]);
    }

    /**
     * Check if the file is a document (PDF, Word, etc).
     */
    public function isDocument(): bool
    {
        return $this->hasFile() && !$this->isImage();
    }
}
