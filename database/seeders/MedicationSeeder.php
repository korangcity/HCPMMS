<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Medication;
use Illuminate\Database\Seeder;

final class MedicationSeeder extends Seeder
{
    public function run(): void
    {
        $medications = [
            [
                'name' => 'Metformin',
                'generic_name' => 'Metformin',
                'brand_name' => null,
                'form' => 'tablet',
                'strength' => '500 mg',
                'manufacturer' => null,
                'description' => 'Oral medication commonly used in type 2 diabetes management.',
                'is_active' => true,
            ],
            [
                'name' => 'Amlodipine',
                'generic_name' => 'Amlodipine',
                'brand_name' => null,
                'form' => 'tablet',
                'strength' => '5 mg',
                'manufacturer' => null,
                'description' => 'Medication used for blood pressure management.',
                'is_active' => true,
            ],
            [
                'name' => 'Losartan',
                'generic_name' => 'Losartan',
                'brand_name' => null,
                'form' => 'tablet',
                'strength' => '50 mg',
                'manufacturer' => null,
                'description' => 'Angiotensin receptor blocker.',
                'is_active' => true,
            ],
            [
                'name' => 'Atorvastatin',
                'generic_name' => 'Atorvastatin',
                'brand_name' => null,
                'form' => 'tablet',
                'strength' => '20 mg',
                'manufacturer' => null,
                'description' => 'Statin medication used for lipid management.',
                'is_active' => true,
            ],
        ];

        foreach ($medications as $medication) {
            Medication::query()->updateOrCreate(
                [
                    'name' => $medication['name'],
                    'strength' => $medication['strength'],
                ],
                $medication
            );
        }
    }
}
