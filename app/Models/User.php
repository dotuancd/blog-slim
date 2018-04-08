<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $username
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
        'username',
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

    /**
     * @param $username
     * @return User|null
     */
    public static function findByUsername($username)
    {
        return static::where('username', $username)->first();
    }
}