<?php

namespace App\Business;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserBo
{
    // Business logic for creating/updating users
    public function prepareForCreate(array $data): array
    {
        // ensure password hashed
        $data['password'] = Hash::make($data['password']);
        return $data;
    }

    public function prepareForUpdate(User $user, array $data): array
    {
        // If password provided, hash it, otherwise remove
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $data;
    }
}
