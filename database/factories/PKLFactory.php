<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PKL>
 */
class PKLFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now', '+1 month');
        $endDate = fake()->dateTimeBetween($startDate, '+6 months');

        return [
            'user_id' => User::factory()->mahasiswa(),
            'company_id' => Company::factory()->active(),
            'supervisor_id' => User::factory()->dosen(),
            'field_supervisor_id' => User::factory()->pembimbingLapangan(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => fake()->randomElement(['pending', 'approved', 'ongoing', 'completed']),
            'description' => fake()->paragraph(),
            'documents' => [
                'proposal' => 'documents/proposal_' . fake()->uuid() . '.pdf',
                'surat_pengantar' => 'documents/surat_' . fake()->uuid() . '.pdf',
                'cv' => 'documents/cv_' . fake()->uuid() . '.pdf'
            ],
            'final_score' => null,
            'defense_date' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    public function ongoing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'ongoing',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'final_score' => fake()->randomFloat(2, 60, 100),
            'defense_date' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}
