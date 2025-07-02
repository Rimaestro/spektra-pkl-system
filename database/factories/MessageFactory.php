<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sender_id' => User::factory(),
            'receiver_id' => User::factory(),
            'subject' => fake()->sentence(),
            'message' => fake()->paragraphs(2, true),
            'attachments' => fake()->optional()->passthrough([
                'document.pdf' => 'attachments/doc_' . fake()->uuid() . '.pdf',
                'image.jpg' => 'attachments/img_' . fake()->uuid() . '.jpg'
            ]),
            'read_at' => fake()->optional()->dateTimeBetween('-1 week', 'now'),
            'priority' => fake()->randomElement(['low', 'normal', 'high']),
        ];
    }

    public function unread(): static
    {
        return $this->state(fn (array $attributes) => [
            'read_at' => null,
        ]);
    }

    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'read_at' => fake()->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'high',
            'subject' => '[URGENT] ' . fake()->sentence(),
        ]);
    }

    public function withAttachments(): static
    {
        return $this->state(fn (array $attributes) => [
            'attachments' => [
                'document.pdf' => 'attachments/doc_' . fake()->uuid() . '.pdf',
                'image.jpg' => 'attachments/img_' . fake()->uuid() . '.jpg',
                'spreadsheet.xlsx' => 'attachments/sheet_' . fake()->uuid() . '.xlsx'
            ],
        ]);
    }

    public function fromMahasiswaToDosen(): static
    {
        return $this->state(fn (array $attributes) => [
            'sender_id' => User::factory()->mahasiswa(),
            'receiver_id' => User::factory()->dosen(),
            'subject' => 'Konsultasi PKL - ' . fake()->sentence(),
        ]);
    }
}
