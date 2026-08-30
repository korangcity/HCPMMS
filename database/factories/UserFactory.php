<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Sex;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * @var string|null
     */
    protected static ?string $password = null;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),

            'email' => fake()->unique()->safeEmail(),

            'password' => static::$password ??= Hash::make('password'),

            'mobile' => fake()->unique()->numerify('09#########'),

            'sex' => fake()->randomElement(Sex::cases())->value,

            'civilization_code' => fake()
                ->unique()
                ->numerify('##########'),

            'age' => fake()->numberBetween(18, 90),

            'remember_token' => Str::random(10),
        ];
    }

    public function male(): static
    {
        return $this->state([
            'sex' => Sex::Male->value,
        ]);
    }

    public function female(): static
    {
        return $this->state([
            'sex' => Sex::Female->value,
        ]);
    }

    public function withoutEmail(): static
    {
        return $this->state([
            'email' => null,
        ]);
    }

    public function withRole(string $role): static
    {
        return $this->afterCreating(
            static function (User $user) use ($role): void {
                $user->assignRole($role);
            },
        );
    }
}
