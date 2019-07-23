<?php

namespace App\Auth\Contracts;

use App\Models\User;

interface HasPolicy
{
    const ABILITY_VIEW = 'view';

    const ABILITY_CREATE = 'create';

    const ABILITY_UPDATE = 'update';
    
    const ABILITY_DELETE = 'delete';

    public function can(User $user, $ability);
}