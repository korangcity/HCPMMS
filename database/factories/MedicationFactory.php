<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MedicationForm;
use App\Models\Medication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Medication>
 */
final class MedicationFactory extends Factory
{
    protected $model = Medication::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Metformin',
                'Amlodipine',
                'Losartan',
                'Atorvastatin',
                'Omeprazole',
                'Salbutamol',
            ]),
            'generic_name' => fake()->word(),
            'brand_name' => fake()->company(),
            'form' => fake()->randomElement(MedicationForm::cases()),
            'strength' => fake()->randomElement([
                '5 mg',
                '10 mg',
                '20 mg',
                '500 mg',
            ]),
            'manufacturer' => fake()->company(),
            'description' => fake()->optional()->sentence(),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state([
            'is_active' => false,
        ]);
    }
}
