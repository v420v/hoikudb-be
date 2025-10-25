<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Preschool;

class PreschoolFactory extends Factory
{
    protected $model = Preschool::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name . '保育園',
            'building_code' => fake()->unique()->numerify(str_repeat('#', 8)),
            'status' => fake()->randomElement([Preschool::STATUS_ACTIVE, Preschool::STATUS_INACTIVE]),
        ];
    }
}
