<?php

namespace App\Auth;

use App\Models\User;

trait NoOneCanRead
{
    use HasPolicy;

    public function userCanRead(User $user)
    {
        return $this->isOwnedBy($user);
    }
}