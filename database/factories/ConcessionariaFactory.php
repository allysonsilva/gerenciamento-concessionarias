<?php

namespace Database\Factories;

use App\Models\Concessionaria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Concessionaria>
 */
class ConcessionariaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Concessionaria::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'symbol' => fake()->unique()->regexify('[A-Z]{5}[0-4]{3}'),
            'cnpj' => fake()->unique()->cnpj(false),
        ];
    }
}
