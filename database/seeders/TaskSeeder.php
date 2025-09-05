<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = [
            [
                'title' => 'Complete project documentation',
                'description' => 'Write comprehensive documentation for the Laravel To-Do List project',
                'status' => Task::STATUS_TODO,
                'due_at' => now()->addDays(3),
            ],
            [
                'title' => 'Implement user authentication',
                'description' => 'Add login and registration functionality',
                'status' => Task::STATUS_IN_PROGRESS,
                'due_at' => now()->addDays(1),
            ],
            [
                'title' => 'Design responsive UI',
                'description' => 'Create mobile-friendly interface using Bootstrap',
                'status' => Task::STATUS_DONE,
                'due_at' => now()->subDays(1),
            ],
            [
                'title' => 'Set up database migrations',
                'description' => 'Create and run all necessary database migrations',
                'status' => Task::STATUS_DONE,
                'due_at' => now()->subDays(2),
            ],
            [
                'title' => 'Write unit tests',
                'description' => 'Create comprehensive test suite for all features',
                'status' => Task::STATUS_TODO,
                'due_at' => now()->addDays(5),
            ],
            [
                'title' => 'Deploy to production',
                'description' => 'Deploy the application to production server',
                'status' => Task::STATUS_TODO,
                'due_at' => now()->addDays(7),
            ],
            [
                'title' => 'Code review and refactoring',
                'description' => 'Review code quality and refactor if necessary',
                'status' => Task::STATUS_IN_PROGRESS,
                'due_at' => now()->addDays(2),
            ],
            [
                'title' => 'Performance optimization',
                'description' => 'Optimize database queries and application performance',
                'status' => Task::STATUS_TODO,
                'due_at' => now()->addDays(4),
            ],
            [
                'title' => 'Security audit',
                'description' => 'Conduct security review and implement best practices',
                'status' => Task::STATUS_TODO,
                'due_at' => now()->addDays(6),
            ],
            [
                'title' => 'User feedback collection',
                'description' => 'Gather user feedback and implement improvements',
                'status' => Task::STATUS_DONE,
                'due_at' => now()->subDays(3),
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
