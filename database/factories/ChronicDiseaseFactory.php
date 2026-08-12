<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ChronicDisease;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChronicDisease>
 */
final class ChronicDiseaseFactory extends Factory
{
    protected $model = ChronicDisease::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'دیابت نوع ۲',
                'فشار خون بالا',
                'بیماری قلبی',
                'آسم',
                'بیماری مزمن کلیه',
            ]),
            'code' => fake()->unique()->bothify('D-####'),
            'description' => fake()->optional()->sentence(),
            'is_active' => true,
        ];
    }
}
