<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conversation extends Model
{
    protected $fillable = [
        'user_id',
        'is_bot_only',
        'handover_to_user_id',
        'status',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function handoverTo(): BelongsTo {
        return $this->belongsTo(User::class, 'handover_to_user_id');
    }

    public function messages(): HasMany {
        return $this->hasMany(Message::class);
    }
}

?>