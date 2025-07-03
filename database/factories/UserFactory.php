<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        $role = fake()->randomElement(['siswa', 'dosen', 'pembimbing_lapangan']);

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => $role,
            'nis' => $role === 'siswa' ? fake()->unique()->numerify('2021######') : null,
            'nip' => in_array($role, ['dosen', 'koordinator', 'admin']) ? fake()->unique()->numerify('19########') : null,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'status' => fake()->randomElement(['active', 'inactive', 'pending']),

            // Authentication enhancement fields
            'last_login_at' => fake()->optional(0.7)->dateTimeBetween('-30 days', 'now'),
            'last_login_ip' => fake()->optional(0.7)->ipv4(),
            'login_attempts' => 0,
            'locked_until' => null,
            'password_changed_at' => fake()->dateTimeBetween('-90 days', 'now'),
            'force_password_change' => fake()->boolean(5), // 5% chance

            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create a siswa user
     */
    public function siswa(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'siswa',
            'nis' => fake()->numerify('2021######'),
            'nip' => null,
        ]);
    }

    /**
     * Create a dosen user
     */
    public function dosen(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'dosen',
            'nis' => null,
            'nip' => fake()->numerify('19########'),
        ]);
    }

    /**
     * Create a pembimbing lapangan user
     */
    public function pembimbingLapangan(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'pembimbing_lapangan',
            'nis' => null,
            'nip' => null,
        ]);
    }

    /**
     * Create an admin user
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'nis' => null,
            'nip' => fake()->numerify('19########'),
            'status' => 'active',
        ]);
    }

    /**
     * Create a koordinator user
     */
    public function koordinator(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'koordinator',
            'nis' => null,
            'nip' => fake()->numerify('19########'),
            'status' => 'active',
        ]);
    }

    /**
     * Create an active user
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }
}
