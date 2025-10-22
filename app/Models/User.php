<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

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
        'firstname',
        'lastname',
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
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    function isAdministrator(): bool
    {

        return $this->role === 'ADMIN';
    }

    /**
     * Mutator to ensure passwords are hashed when set.
     */
    public function setPasswordAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['password'] = $value;
            return;
        }

        // If value already looks like a bcrypt/argon hash, keep it as-is
        if (is_string($value) && preg_match('/^\$(2y|2b|argon2)/', $value)) {
            $this->attributes['password'] = $value;
            return;
        }

        // Otherwise hash the plain text password
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Virtual name attribute for backward compatibility with views.
     * Returns 'firstname lastname' when templates reference $user->name.
     */
    public function getNameAttribute()
    {
        $first = $this->attributes['firstname'] ?? '';
        $last = $this->attributes['lastname'] ?? '';
        return trim($first . ' ' . $last);
    }
}
