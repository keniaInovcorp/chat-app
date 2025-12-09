<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if the current user has the administrator role.
     *
     * @return bool Returns true when the "role" attribute is equal to "admin".
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the current user is a regular (non-admin) user.
     *
     * @return bool Returns true when the "role" attribute is equal to "user".
     */
    public function isRegularUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get all chat rooms that the user belongs to.
     *
     * The relationship uses the `chat_room_user` pivot table and exposes the
     * `last_read_at` timestamp for each room membership.
     *
     * @return BelongsToMany
     */
    public function chatRooms()
    {
        return $this->belongsToMany(ChatRoom::class, 'chat_room_user')
                    ->withPivot('last_read_at')
                    ->withTimestamps();
    }
}
