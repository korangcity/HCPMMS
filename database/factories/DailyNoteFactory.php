<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\DailyNote;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DailyNote>
 */
final class DailyNoteFactory extends Factory
{
    protected $model = DailyNote::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'created_by' => User::factory(),
            'note_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'content' => fake()->paragraph(),
            'metadata' => null,
        ];
    }
}
