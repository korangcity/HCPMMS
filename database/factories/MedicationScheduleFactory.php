<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MedicationScheduleFrequency;
use App\Models\MedicationSchedule;
use App\Models\PrescriptionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MedicationSchedule>
 */
final class MedicationScheduleFactory extends Factory
{
    protected $model = MedicationSchedule::class;

    public function definition(): array
    {
        return [
            'prescription_item_id' => PrescriptionItem::factory(),
            'frequency' => MedicationScheduleFrequency::OnceDaily,
            'scheduled_time' => '08:00:00',
            'interval_hours' => null,
            'starts_at' => now()->toDateString(),
            'ends_at' => now()->addDays(30)->toDateString(),
            'is_active' => true,
            'notes' => null,
        ];
    }

    public function at(string $time): static
    {
        return $this->state([
            'scheduled_time' => $time,
        ]);
    }

    public function inactive(): static
    {
        return $this->state([
            'is_active' => false,
        ]);
    }
}
