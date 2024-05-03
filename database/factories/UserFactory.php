<?php

namespace Database\Factories;

use App\Enums\UserStatus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'status' => UserStatus::ENABLE,
            'email_verified_at' => now(),
            'password' => static::$password ??= 'password',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
            'email' => 'user_unverified@example.org',
        ]);
    }

    /**
     * Indicates that the user is deactivated and does not have access to the API.
     */
    public function disabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => UserStatus::DISABLE,
            'email' => 'user_disabled@example.org',
        ]);
    }
}
