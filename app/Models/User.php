<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'department', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class, 'borrower_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
