<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'client_token',
        'status',
        'is_bot_only',
        'assigned_staff_id',
        'priority',
        'tags',
        'notes',
    ];

    protected $casts = [
        'is_bot_only' => 'boolean',
        'tags' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the conversation
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the assigned staff member
     */
    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    /**
     * Get all messages for the conversation
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the latest message
     */
    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    /**
     * Get unread messages count
     */
    public function getUnreadCountAttribute()
    {
        return $this->messages()
            ->where('sender_type', '!=', 'staff')
            ->where('is_read', false)
            ->count();
    }

    /**
     * Scope for active conversations
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for pending conversations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for closed conversations
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    /**
     * Get display name for the conversation
     */
    public function getDisplayNameAttribute()
    {
        if ($this->user) {
            return $this->user->name;
        }
        
        return 'Ẩn danh #' . substr($this->client_token, 0, 6);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'active' => 'green',
            'closed' => 'gray',
            default => 'blue',
        };
    }

    /**
     * Get status display text
     */
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Chờ xử lý',
            'active' => 'Đang xử lý',
            'closed' => 'Đã đóng',
            default => 'Không xác định',
        };
    }
}
