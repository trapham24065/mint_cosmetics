<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotRule extends Model
{

    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['keyword', 'reply', 'is_active'];

}
