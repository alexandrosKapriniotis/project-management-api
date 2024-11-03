<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    /**
     * Get all users.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return User::all();
    }

    /**
     * Store a new user and assign the default role.
     *
     * @param array $data
     * @return User
     */
    public function store(array $data): User
    {
        $user = User::create($data);
        $user->assignRole('User');

        return $user;
    }

    /**
     * Update a user.
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }

    /**
     * Delete a user.
     *
     * @param User $user
     * @return void
     */
    public function delete(User $user): void
    {
        $user->delete();
    }
}
