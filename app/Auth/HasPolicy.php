<?php

namespace App\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Auth\Contracts\HasPolicy as Policy;

trait HasPolicy
{
    public function can(User $user, $ability)
    {
        if ($this->hasCustomPolicy($ability)) {
            return $this->callCustomPolicy($user, $ability);
        }

        if ($user->isAdmin()) {
            return true;
        }

        return $this->isOwnedBy($user);
    }

    public function hasCustomPolicy($ability)
    {
        return method_exists($this, 'userCan' . Str::studly($ability));
    }

    public function callCustomPolicy(User $user, $ability)
    {
        $method = 'userCan' . Str::studly($ability);
        return $this->$method($user);
    }

    public function isOwnedBy(User $user)
    {
        return $user->getKey() === $this->getUserForeignKey();
    }

    protected function getUserForeignKey()
    {
        $foreignKey = property_exists($this, 'userForeignKey') ? $this->userForeignKey : 'user_id';

        return $this->$foreignKey;
    }

    protected function getAbilities()
    {
        $abilities = method_exists($this, 'abilities') ? $this->abilities : [];

        $defaults = [
            Policy::ABILITY_CREATE,
            Policy::ABILITY_VIEW,
            Policy::ABILITY_UPDATE,
            Policy::ABILITY_DELETE,
        ];

        return array_merge($defaults, $abilities);
    }
}