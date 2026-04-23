<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'society_id',
        'phone',
        'is_active',
        'is_approved',
        'unit_number',
        'document_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role_id === Role::SUPER_ADMIN;
    }

    public function isSocietyAdmin(): bool
    {
        return $this->role_id === Role::SOCIETY_ADMIN;
    }

    public function isUser(): bool
    {
        return $this->role_id === Role::USER;
    }
}
