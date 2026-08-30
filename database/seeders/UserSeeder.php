<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->withRole('admin')
            ->create([
                'name' => 'System Administrator',
                'email' => 'admin@example.com',
                'mobile' => '09000000000',
                'civilization_code' => '0000000000',
                'age' => 30,
            ]);

        User::factory()
            ->withRole('doctor')
            ->create([
                'name' => 'Dr. Test Doctor',
                'email' => 'doctor@example.com',
                'mobile' => '09111111111',
                'civilization_code' => '1111111111',
                'age' => 45,
            ]);

        User::factory()
            ->withRole('caregiver')
            ->create([
                'name' => 'Test Caregiver',
                'email' => 'caregiver@example.com',
                'mobile' => '09222222222',
                'civilization_code' => '2222222222',
                'age' => 35,
            ]);

        User::factory()
            ->withRole('patient')
            ->create([
                'name' => 'Test Patient',
                'email' => null,
                'mobile' => '09333333333',
                'civilization_code' => '3333333333',
                'age' => 65,
            ]);
    }
}
