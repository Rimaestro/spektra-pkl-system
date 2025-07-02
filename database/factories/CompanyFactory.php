<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companyTypes = ['PT', 'CV', 'Startup', 'Koperasi'];
        $businessTypes = ['Teknologi', 'Digital', 'Software', 'Sistem', 'Data', 'Web', 'Mobile', 'Cloud', 'AI'];

        $companyType = fake()->randomElement($companyTypes);
        $businessType = fake()->randomElement($businessTypes);
        $companyName = $companyType . '. ' . $businessType . ' ' . fake()->company();

        return [
            'name' => $companyName,
            'address' => fake()->address(),
            'contact_person' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'description' => fake()->paragraph(3),
            'website' => fake()->url(),
            'status' => fake()->randomElement(['active', 'inactive']),
            'max_students' => fake()->numberBetween(2, 10),
        ];
    }

    /**
     * Create an active company
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Create an inactive company
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Create a company with high capacity
     */
    public function highCapacity(): static
    {
        return $this->state(fn (array $attributes) => [
            'max_students' => fake()->numberBetween(8, 15),
        ]);
    }

    /**
     * Create a company with low capacity
     */
    public function lowCapacity(): static
    {
        return $this->state(fn (array $attributes) => [
            'max_students' => fake()->numberBetween(1, 3),
        ]);
    }
}
