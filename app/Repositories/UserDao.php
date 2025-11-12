<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserDao
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    // Create user
    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    // Update user
    public function update(User $user, array $data): User
    {
        $user->fill($data);
        $user->save();
        return $user;
    }

    // Find by id
    public function findById(int $id): ?User
    {
        return $this->model->find($id);
    }

    // List all (with pagination optional)
    public function all(array $columns = ['*'])
    {
        return $this->model->select($columns)->get();
    }
}
