<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    const ADMIN = 'admin';

    const USER = 'user';

    protected $attributes = [
        'role' => self::USER,
    ];

    protected $hidden = ['password', 'remember_token'];

    public function isAdmin()
    {
        return $this->hasRole(self::ADMIN);
    }

    public function isUser()
    {
        return $this->hasRole(self::USER);
    }

    public function hasRole($role)
    {
        return ($this->role === $role);
    }
}