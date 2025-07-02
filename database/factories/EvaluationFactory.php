<?php

namespace Database\Factories;

use App\Models\PKL;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evaluation>
 */
class EvaluationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $evaluatorType = fake()->randomElement(['supervisor', 'field_supervisor']);
        $technicalScore = fake()->randomFloat(2, 60, 100);
        $attitudeScore = fake()->randomFloat(2, 60, 100);
        $communicationScore = fake()->randomFloat(2, 60, 100);
        $finalScore = round(($technicalScore + $attitudeScore + $communicationScore) / 3, 2);

        return [
            'pkl_id' => PKL::factory(),
            'evaluator_id' => $evaluatorType === 'supervisor'
                ? User::factory()->dosen()
                : User::factory()->pembimbingLapangan(),
            'evaluator_type' => $evaluatorType,
            'technical_score' => $technicalScore,
            'attitude_score' => $attitudeScore,
            'communication_score' => $communicationScore,
            'final_score' => $finalScore,
            'comments' => fake()->paragraph(),
            'suggestions' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(['draft', 'submitted', 'final']),
        ];
    }

    public function supervisor(): static
    {
        return $this->state(fn (array $attributes) => [
            'evaluator_type' => 'supervisor',
            'evaluator_id' => User::factory()->dosen(),
        ]);
    }

    public function fieldSupervisor(): static
    {
        return $this->state(fn (array $attributes) => [
            'evaluator_type' => 'field_supervisor',
            'evaluator_id' => User::factory()->pembimbingLapangan(),
        ]);
    }

    public function excellent(): static
    {
        return $this->state(function (array $attributes) {
            $technicalScore = fake()->randomFloat(2, 85, 100);
            $attitudeScore = fake()->randomFloat(2, 85, 100);
            $communicationScore = fake()->randomFloat(2, 85, 100);
            $finalScore = round(($technicalScore + $attitudeScore + $communicationScore) / 3, 2);

            return [
                'technical_score' => $technicalScore,
                'attitude_score' => $attitudeScore,
                'communication_score' => $communicationScore,
                'final_score' => $finalScore,
            ];
        });
    }

    public function final(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'final',
        ]);
    }
}
