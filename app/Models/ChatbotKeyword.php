<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a keyword linked to a specific chatbot reply.
 */
class ChatbotKeyword extends Model
{

    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'chatbot_reply_id',
        'keyword',
    ];

    /**
     * Get the reply that this keyword belongs to.
     */
    public function reply(): BelongsTo
    {
        return $this->belongsTo(ChatbotReply::class, 'chatbot_reply_id');
    }

}
