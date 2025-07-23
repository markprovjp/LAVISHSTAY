<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_type',
        'sender_id',
        'message',
        'is_from_bot',
        'is_read',
        'message_type',
        'attachments',
        'metadata',
    ];

    protected $casts = [
        'is_from_bot' => 'boolean',
        'is_read' => 'boolean',
        'attachments' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the conversation that owns the message
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the sender (user or staff)
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Scope for staff messages
     */
    public function scopeFromStaff($query)
    {
        return $query->where('sender_type', 'staff');
    }

    /**
     * Scope for user messages
     */
    public function scopeFromUser($query)
    {
        return $query->where('sender_type', 'user');
    }

    /**
     * Scope for bot messages
     */
    public function scopeFromBot($query)
    {
        return $query->where('is_from_bot', true);
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Get sender display name
     */
    public function getSenderNameAttribute()
    {
        if ($this->is_from_bot) {
            return 'Bot tự động';
        }

        if ($this->sender_type === 'staff' && $this->sender) {
            return $this->sender->name;
        }

        if ($this->sender_type === 'user' && $this->sender) {
            return $this->sender->name;
        }

        return 'Ẩn danh';
    }

    /**
     * Get message bubble class for styling
     */
    public function getBubbleClassAttribute()
    {
        if ($this->sender_type === 'staff') {
            return 'bg-blue-600 text-white rounded-br-md';
        }

        if ($this->is_from_bot) {
            return 'bg-purple-100 dark:bg-purple-900/30 text-purple-900 dark:text-purple-100 rounded-bl-md border border-purple-200 dark:border-purple-700';
        }

        return 'bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-bl-md shadow-sm border border-gray-200 dark:border-gray-600';
    }
}
