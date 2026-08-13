<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Alert;
use App\Models\AlertRecipient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AlertRecipient>
 */
final class AlertRecipientFactory extends Factory
{
    protected $model = AlertRecipient::class;

    public function definition(): array
    {
        return [
            'alert_id' => Alert::factory(),
            'user_id' => User::factory(),
            'recipient_type' => fake()->randomElement([
                'doctor',
                'caregiver',
            ]),
            'notified_at' => null,
            'read_at' => null,
        ];
    }
}
