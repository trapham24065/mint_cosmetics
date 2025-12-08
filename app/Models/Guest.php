<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Musonza\Chat\Traits\Messageable;
use Musonza\Chat\Models\Conversation;

class Guest extends Model
{

    use HasFactory, Messageable;

    protected $fillable = [
        'session_id',
        'name',
        'ip_address',
    ];

    /**
     * Thuộc tính phụ để lấy thông tin người tham gia (nếu cần hiển thị avatar/tên)
     */
    public function getParticipantDetails(): array
    {
        return [
            'name'   => $this->name,
            'avatar' => asset('assets/storefront/images/blog/default-avatar.png'),
        ];
    }

    public function conversations(): MorphToMany
    {
        return $this->morphToMany(Conversation::class, 'messageable', 'chat_participation')
            ->withPivot('created_at', 'updated_at');
    }

}
