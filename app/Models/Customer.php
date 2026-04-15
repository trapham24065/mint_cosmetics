<?php

/**
 * @project mint_cosmetics
 *
 * @author PhamTra
 *
 * @email trapham24065@gmail.com
 *
 * @date 8/22/2025
 *
 * @time 3:24 PM
 */

namespace App\Models;

use App\Notifications\CustomerResetPasswordNotification;
use App\Traits\HasCustomerStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Musonza\Chat\Traits\Messageable;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Musonza\Chat\Models\Conversation;

class Customer extends Authenticatable
{

    use HasCustomerStatus;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use Messageable;

    use \Illuminate\Auth\Passwords\CanResetPassword;

    protected $guard = 'customer';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'address',
        'city',
        'shipping_province_id',
        'shipping_district_id',
        'shipping_ward_code',
        'shipping_province_name',
        'shipping_district_name',
        'shipping_ward_name',
        'status',
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'    => 'datetime',
        'password'             => 'hashed',
        'shipping_province_id' => 'integer',
        'shipping_district_id' => 'integer',
        'status'               => 'boolean',
    ];

    public function orders(): Customer|HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return "bg-{$this->status_color} bg-opacity-10 text-{$this->status_color}";
    }

    public function getParticipantDetails(): array
    {
        return [
            'name'   => $this->full_name,
            'avatar' => asset('assets/storefront/images/users/dummy-avatar.jpg'),
        ];
    }

    public function cartItems(): Customer|HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomerResetPasswordNotification($token));
    }

    public function conversations(): MorphToMany
    {
        return $this->morphToMany(Conversation::class, 'messageable', 'chat_participation')
            ->withPivot('created_at', 'updated_at');
    }

}
