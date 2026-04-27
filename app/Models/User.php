<?php

declare(strict_types=1);

namespace App\Models;

use App\Notifications\AdminResetPasswordNotification;
use App\Notifications\AdminVerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Musonza\Chat\Traits\Messageable;
use Musonza\Chat\Models\Conversation;

class User extends Authenticatable implements MustVerifyEmail
{

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use Messageable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'avatar',
        'email',
        'pending_email',
        'pending_email_token',
        'pending_email_sent_at',
        'password',
        'role',
        'status',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pending_email_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'     => 'datetime',
            'pending_email_sent_at' => 'datetime',
            'password'              => 'hashed',
        ];
    }

    /**
     * Determine whether the user has a pending email change request.
     */
    public function hasPendingEmailChange(): bool
    {
        return ! empty($this->pending_email) && ! empty($this->pending_email_token);
    }

    /**
     * Determine whether the pending email change link has expired.
     */
    public function isPendingEmailExpired(int $hoursValid = 24): bool
    {
        if (! $this->pending_email_sent_at) {
            return true;
        }

        return $this->pending_email_sent_at->addHours($hoursValid)->isPast();
    }

    /**
     * Clear all pending email change fields.
     */
    public function clearPendingEmailChange(): void
    {
        $this->forceFill([
            'pending_email'         => null,
            'pending_email_token'   => null,
            'pending_email_sent_at' => null,
        ])->save();
    }

    public function conversations(): MorphToMany
    {
        return $this->morphToMany(Conversation::class, 'messageable', 'chat_participation')
            ->withPivot('created_at', 'updated_at');
    }

    public function getParticipantDetails(): array
    {
        return [
            'name'   => $this->name,
            'avatar' => asset('assets/admin/images/users/dummy-avatar.jpg'),
        ];
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is sale
     */
    public function isSale(): bool
    {
        return $this->role === 'sale';
    }

    /**
     * Check if user is warehouse
     */
    public function isWarehouse(): bool
    {
        return $this->role === 'warehouse';
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new AdminVerifyEmailNotification());
    }
}
