<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CaregiverStatus;
use App\Models\Caregiver;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Caregiver>
 */
class CaregiverFactory extends Factory
{
    protected $model = Caregiver::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),


            'relationship' => fake()->randomElement([
                'پدر',
                'مادر',
                'همسر',
                'فرزند',
                'خواهر',
                'برادر',
                'سایر',
            ]),

            'address' => fake()->address(),

            'notes' => fake()->optional()->sentence(),

            'status' => CaregiverStatus::ACTIVE,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => [
            'status' => CaregiverStatus::INACTIVE,
        ]);
    }
}
