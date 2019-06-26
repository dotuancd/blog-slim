<?php

namespace App\Models;

interface HasReadPolicy
{
    public function readPolicy(User $user);
}