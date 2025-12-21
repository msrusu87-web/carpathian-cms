<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'conversation_id',
        'user_id',
        'is_admin',
        'message',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatConversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getSenderNameAttribute(): string
    {
        if ($this->is_admin) {
            return $this->user?->name ?? 'Support Team';
        }
        return $this->conversation?->participant_name ?? 'Guest';
    }
}
