<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CaregiverType;
use App\Models\Caregiver;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Caregiver>
 */
final class CaregiverFactory extends Factory
{
    protected $model = Caregiver::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement(CaregiverType::cases()),
            'national_identifier' => fake()->unique()->numerify('##########'),
            'address' => fake()->address(),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
