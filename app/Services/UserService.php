<?php

namespace App\Services;
use App\Models\User;

final class UserService
{

    public function findOrFail($id): User
    {
        return User::findOrFail($id);
    }

}