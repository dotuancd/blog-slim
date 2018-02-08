<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $api_token
 * @property string $role
 */
class User extends Model
{
    const ADMIN = 'admin';

    const USER = 'user';

    protected $attributes = [
        'role' => self::USER,
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token'
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