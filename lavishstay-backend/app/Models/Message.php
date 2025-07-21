<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_type',
        'sender_id',
        'message',
        'is_from_bot',
    ];

    public function conversation(): BelongsTo {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): BelongsTo {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
