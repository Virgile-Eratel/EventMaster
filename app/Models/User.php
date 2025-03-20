<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'role' => Role::class,
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

    public function organizedEvents()
    {
        return $this->hasMany(Event::class, 'organisateur_id');
    }
    public function registeredEvents()
    {
        return $this->belongsToMany(Event::class, 'event_user');
    }
    public function isAdmin(): bool
    {
        return $this->role === Role::Admin->value;
    }

    public function isOrganisateur(): bool
    {
        return $this->role === Role::Organisateur->value;
    }

    public function isClient(): bool
    {
        return $this->role === Role::Client->value;
    }
}
