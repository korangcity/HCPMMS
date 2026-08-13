<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Visit;
use Illuminate\Database\Seeder;

final class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $doctor = Doctor::query()->first();

        $patient = Patient::query()->first();

        if ($doctor === null || $patient === null) {
            return;
        }

        $appointment = Appointment::factory()
            ->confirmed()
            ->create([
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
            ]);

        Visit::factory()->create([
            'appointment_id' => $appointment->id,
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
        ]);
    }
}
