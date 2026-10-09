<?php

namespace App\Services;

use App\Models\User;
use App\Requests\UserRequest;
use App\Exceptions\ApiException;

class UserService
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserById(int $id)
    {
        $user = User::find($id);

        if (!$user) {
            throw new ApiException('کاربر یافت نشد.', 404);
        }

        return $user;
    }

    public function createUser(array $data): User
    {
        $validated = UserRequest::validateStore($data);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => password_hash($validated['password'], PASSWORD_BCRYPT),
        ]);

        return $user;
    }

    public function updateUser(int $id, array $data): User
    {
        $user = $this->getUserById($id);

        $validated = UserRequest::validateUpdate($data, $id);

        if (isset($validated['password'])) {
            $validated['password'] = password_hash($validated['password'], PASSWORD_BCRYPT);
        }

        $user->update($validated);

        return $user;
    }

    public function deleteUser(int $id): bool
    {
        $user = $this->getUserById($id);

        return $user->delete();
    }
}
