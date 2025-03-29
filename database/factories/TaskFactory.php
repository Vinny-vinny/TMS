<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $taskTitles = [
            'Complete project documentation',
            'Review pull requests',
            'Schedule team meeting',
            'Update API endpoints',
            'Fix bug in payment module',
            'Prepare presentation slides',
            'Conduct code review',
            'Optimize database queries',
            'Write unit tests',
            'Implement authentication',
        ];
        // Randomly decide if the task is overdue
        $isOverdue = $this->faker->boolean(20); // 20% chance of being overdue
        $dueDate = $isOverdue ? $this->faker->dateTimeBetween('-1 year', 'now') : $this->faker->dateTimeBetween('now', '+1 year');
        return [
            "title" => $this->faker->randomElement($taskTitles),
            "description" => $this->faker->text(),
            "due_date" => $dueDate,
            'user_id' =>  User::inRandomOrder()->first()->id,
        ];
    }
}
