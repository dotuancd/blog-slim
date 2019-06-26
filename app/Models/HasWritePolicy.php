<?php

namespace App\Models;

interface HasWritePolicy
{
    public function writePolicy(User $user);
}