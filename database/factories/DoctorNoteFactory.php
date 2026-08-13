<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\DoctorNote>
 */
final class DoctorNoteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'visit_id' => Visit::factory(),
            'doctor_id' => Doctor::factory(),
            'title' => fake()->sentence(4),
            'content' => fake()->paragraphs(2, true),
            'is_private' => false,
        ];
    }

    public function private(): static
    {
        return $this->state([
            'is_private' => true,
        ]);
    }
}
