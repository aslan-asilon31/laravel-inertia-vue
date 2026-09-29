<?php

namespace Database\Factories;

use App\Models\MsPosition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MsPosition>
 */
class MsPositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'name' => $this->faker->jobTitle(),
            'created_by' => 'system',
            'updated_by' => 'system',
        ];
    }
}
