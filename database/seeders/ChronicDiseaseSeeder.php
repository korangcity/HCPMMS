<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ChronicDisease;
use Illuminate\Database\Seeder;

final class ChronicDiseaseSeeder extends Seeder
{
    public function run(): void
    {
        $diseases = [
            [
                'name' => 'دیابت نوع ۱',
                'code' => 'E10',
            ],
            [
                'name' => 'دیابت نوع ۲',
                'code' => 'E11',
            ],
            [
                'name' => 'فشار خون بالا',
                'code' => 'I10',
            ],
            [
                'name' => 'بیماری عروق کرونر قلب',
                'code' => 'I25',
            ],
            [
                'name' => 'نارسایی قلبی',
                'code' => 'I50',
            ],
            [
                'name' => 'آسم',
                'code' => 'J45',
            ],
            [
                'name' => 'بیماری مزمن انسدادی ریه',
                'code' => 'J44',
            ],
            [
                'name' => 'بیماری مزمن کلیه',
                'code' => 'N18',
            ],
        ];

        foreach ($diseases as $disease) {
            ChronicDisease::query()->updateOrCreate(
                ['code' => $disease['code']],
                [
                    'name' => $disease['name'],
                    'is_active' => true,
                ],
            );
        }
    }
}
