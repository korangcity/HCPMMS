<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DoseUnit;
use App\Enums\MedicationRoute;
use App\Models\Medication;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PrescriptionItem>
 */
final class PrescriptionItemFactory extends Factory
{
    protected $model = PrescriptionItem::class;

    public function definition(): array
    {
        return [
            'prescription_id' => Prescription::factory(),
            'medication_id' => Medication::factory(),
            'dose' => fake()->randomElement([1, 2, 5, 10]),
            'dose_unit' => DoseUnit::Tablet,
            'route' => MedicationRoute::Oral,
            'quantity' => fake()->numberBetween(10, 90),
            'duration_days' => fake()->numberBetween(5, 30),
            'instructions' => fake()->optional()->sentence(),
        ];
    }
}
