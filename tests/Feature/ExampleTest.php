<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200)->assertSee('css/style.css');
    }

    public function test_a_new_task_is_saved_and_shown_in_the_task_list(): void
    {
        $response = $this->post(route('tasks.store'), [
            'task_name' => 'Prepare project proposal',
            'description' => 'Review the brief and draft the first version.',
            'due_date' => '2026-10-01',
        ]);

        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', [
            'task_name' => 'Prepare project proposal',
            'status' => 'Pending',
        ]);

        $this->get(route('tasks.index'))
            ->assertOk()
            ->assertSee('Prepare project proposal')
            ->assertSee('Review the brief and draft the first version.');
    }

    public function test_multiple_tasks_can_be_added_and_displayed_together(): void
    {
        $this->post(route('tasks.store'), [
            'task_name' => 'First task',
            'description' => 'First task description',
        ])->assertRedirect('/tasks');

        $this->post(route('tasks.store'), [
            'task_name' => 'Second task',
            'description' => 'Second task description',
        ])->assertRedirect('/tasks');

        $this->assertDatabaseCount('tasks', 2);

        $this->get(route('tasks.index'))
            ->assertOk()
            ->assertSee('First task')
            ->assertSee('Second task');
    }

    public function test_create_page_is_available_for_adding_tasks(): void
    {
        $this->get(route('tasks.create'))
            ->assertOk()
            ->assertSee('Save Task')
            ->assertSee('name="status"', false);
    }

    public function test_edit_page_displays_the_task_and_local_stylesheet(): void
    {
        $task = Task::create([
            'task_name' => 'Original task',
            'description' => 'Original description',
            'status' => 'Pending',
            'due_date' => '2026-10-01',
        ]);

        $this->get(route('tasks.edit', $task))
            ->assertOk()
            ->assertSee('Original task')
            ->assertSee('css/style.css');

        $this->assertFileExists(public_path('css/style.css'));
    }

    public function test_task_update_persists_and_appears_on_the_task_list(): void
    {
        $task = Task::create([
            'task_name' => 'Original task',
            'description' => 'Original description',
            'status' => 'Pending',
            'due_date' => '2026-10-01',
        ]);

        $response = $this->put(route('tasks.update', $task), [
            'task_name' => 'Updated task',
            'description' => 'Updated description',
            'status' => 'Completed',
            'due_date' => '2026-10-15',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'task_name' => 'Updated task',
            'description' => 'Updated description',
            'status' => 'Completed',
            'due_date' => '2026-10-15',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Updated task')
            ->assertSee('Updated description');
    }

    public function test_task_status_can_be_toggled_and_task_can_be_deleted(): void
    {
        $task = Task::create([
            'task_name' => 'Task to manage',
            'description' => 'Task description',
            'status' => 'Pending',
        ]);

        $this->patch(route('tasks.toggleStatus', $task))
            ->assertRedirect();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'Completed',
        ]);

        $this->delete(route('tasks.destroy', $task))
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
