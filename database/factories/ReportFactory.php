<?php

namespace Database\Factories;

use App\Models\PKL;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reportType = fake()->randomElement(['daily', 'weekly', 'monthly', 'final']);

        return [
            'pkl_id' => PKL::factory(),
            'report_type' => $reportType,
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(3, true),
            'file_path' => 'reports/report_' . fake()->uuid() . '.pdf',
            'attachments' => [
                'photo1.jpg' => 'attachments/photo1_' . fake()->uuid() . '.jpg',
                'photo2.jpg' => 'attachments/photo2_' . fake()->uuid() . '.jpg'
            ],
            'report_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'status' => fake()->randomElement(['draft', 'submitted', 'reviewed', 'approved', 'rejected']),
            'feedback' => fake()->optional()->paragraph(),
        ];
    }

    public function daily(): static
    {
        return $this->state(fn (array $attributes) => [
            'report_type' => 'daily',
            'title' => 'Laporan Harian - ' . fake()->date(),
        ]);
    }

    public function weekly(): static
    {
        return $this->state(fn (array $attributes) => [
            'report_type' => 'weekly',
            'title' => 'Laporan Mingguan - Minggu ke-' . fake()->numberBetween(1, 12),
        ]);
    }

    public function final(): static
    {
        return $this->state(fn (array $attributes) => [
            'report_type' => 'final',
            'title' => 'Laporan Akhir PKL',
            'status' => 'submitted',
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'submitted',
        ]);
    }
}
