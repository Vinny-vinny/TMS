<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function testItCanListTasks()
    {
        Task::factory()->count(5)->create();

        $response = $this->getJson('/api/task');
        $response->assertStatus(200);
    }

    public function testItCanStoreATask()
    {
        $taskData = [
            'title' => 'New Task',
            'description' => 'Task description',
            'due_date' => '2025-04-01'
        ];

        $response = $this->postJson('/api/task', $taskData);

        $response->assertStatus(201)
            ->assertJsonFragment($taskData);

        $this->assertDatabaseHas('tasks', $taskData);
    }

    public function testItCanShowATask()
    {
        $task = Task::factory()->create();

        $response = $this->getJson("/api/task/{$task->id}");

        $response->assertStatus(200);
    }

    public function testItCanUpdateATask()
    {
        $task = Task::factory()->create();

        $updatedData = [
            'title' => 'Updated Task',
            'description' => 'Updated description',
            'due_date' => '2025-05-01'
        ];

        $response = $this->putJson("/api/task/{$task->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonFragment($updatedData);

        $this->assertDatabaseHas('tasks', $updatedData);
    }

    public function testItCanDeleteATask()
    {
        $task = Task::factory()->create();
        $response = $this->deleteJson("/api/task/{$task->id}");
        $response->assertStatus(200);
    }
}
