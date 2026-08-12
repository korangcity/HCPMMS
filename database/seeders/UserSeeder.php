<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Caregiver;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'System Administrator',
            'email' => 'admin@example.com',
        ]);

        $admin->assignRole('admin');

        $doctorUser = User::factory()->create([
            'name' => 'Dr. Ali Ahmadi',
            'email' => 'doctor@example.com',
        ]);

        $doctorUser->assignRole('doctor');

        Doctor::factory()->create([
            'user_id' => $doctorUser->id,
        ]);

        $caregiverUser = User::factory()->create([
            'name' => 'Sara Ahmadi',
            'email' => 'caregiver@example.com',
        ]);

        $caregiverUser->assignRole('caregiver');

        Caregiver::factory()->create([
            'user_id' => $caregiverUser->id,
        ]);

        $patientUser = User::factory()->create([
            'name' => 'Reza Ahmadi',
            'email' => 'patient@example.com',
        ]);

        $patientUser->assignRole('patient');

        $patient = Patient::factory()->create([
            'user_id' => $patientUser->id,
        ]);

        $doctor = $doctorUser->doctor;
        $caregiver = $caregiverUser->caregiver;

        $doctor->patients()->attach($patient->id, [
            'is_primary' => true,
        ]);

        $caregiver->patients()->attach($patient->id, [
            'is_primary' => true,
        ]);
    }
}
