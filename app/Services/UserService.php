<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

final class UserService
{
    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data, string $role): User
    {
        return DB::transaction(
            function () use ($data, $role): User {
                $user = User::create($data);

                $user->assignRole($role);

                return $user->fresh();
            },
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(
        User $user,
        array $data,
        ?string $role = null,
    ): User {
        return DB::transaction(
            function () use ($user, $data, $role): User {
                $user->update($data);

                if ($role !== null) {
                    $user->syncRoles([$role]);
                }

                return $user->fresh();
            },
        );
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
