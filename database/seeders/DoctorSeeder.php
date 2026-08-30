<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AcademicDegree;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()
            ->role('doctor')
            ->get();

        if ($users->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($users): void {
            foreach ($users as $user) {
                Doctor::query()->updateOrCreate(
                    [
                        'user_id' => $user->id,
                    ],
                    [
                        'medical_license_number' => 'MED-' . str_pad(
                                (string) $user->id,
                                6,
                                '0',
                                STR_PAD_LEFT,
                            ),

                        'specialty' => fake()->randomElement([
                            'پزشکی عمومی',
                            'داخلی',
                            'قلب و عروق',
                            'غدد',
                            'مغز و اعصاب',
                            'ریه',
                        ]),

                        'academic_degree' => fake()->randomElement(
                            array_column(
                                AcademicDegree::cases(),
                                'value',
                            ),
                        ),

                        'years_of_experience' => fake()->numberBetween(
                            1,
                            30,
                        ),

                        'bio' => fake()->optional(0.8)->paragraph(),
                    ],
                );
            }
        });
    }
}
