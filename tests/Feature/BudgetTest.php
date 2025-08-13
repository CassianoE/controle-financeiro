<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Budget;
use Laravel\Sanctum\Sanctum;

class BudgetTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_authenticated_user_can_list_their_budgets()
    {
        Sanctum::actingAs($this->user);

        Budget::factory()->count(3)->create(['user_id' => $this->user->id, 'category_id' => $this->category->id]);

        $response = $this->getJson('/api/budgets');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_unauthenticated_user_cannot_list_budgets()
    {
        $response = $this->getJson('/api/budgets');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_a_budget_with_valid_data()
    {
        Sanctum::actingAs($this->user);

        $budgetData = [
            'category_id' => $this->category->id,
            'amount' => 1000.00,
            'date' => '2025-08-13'
        ];

        $response = $this->postJson('/api/budgets', $budgetData);

        $response->assertStatus(201)
            ->assertJsonFragment($budgetData);

        $this->assertDatabaseHas('budgets', $budgetData);
    }

    public function test_authenticated_user_cannot_create_a_budget_with_invalid_data()
    {
        Sanctum::actingAs($this->user);

        $budgetData = [
            'category_id' => 999, // Invalid category
            'amount' => -100, // Invalid amount
            'date' => 'invalid-date' // Invalid date
        ];

        $response = $this->postJson('/api/budgets', $budgetData);

        $response->assertStatus(422);
    }

    public function test_unauthenticated_user_cannot_create_a_budget()
    {
        $budgetData = [
            'category_id' => $this->category->id,
            'amount' => 1000.00,
            'date' => '2025-08-13'
        ];

        $response = $this->postJson('/api/budgets', $budgetData);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_view_their_own_budget()
    {
        Sanctum::actingAs($this->user);

        $budget = Budget::factory()->create(['user_id' => $this->user->id, 'category_id' => $this->category->id]);

        $response = $this->getJson("/api/budgets/{$budget->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $budget->id]);
    }

    public function test_authenticated_user_cannot_view_another_users_budget()
    {
        Sanctum::actingAs($this->user);

        $otherUser = User::factory()->create();
        $otherCategory = Category::factory()->create(['user_id' => $otherUser->id]);
        $otherBudget = Budget::factory()->create(['user_id' => $otherUser->id, 'category_id' => $otherCategory->id]);

        $response = $this->getJson("/api/budgets/{$otherBudget->id}");

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_view_any_budget()
    {
        $budget = Budget::factory()->create(['user_id' => $this->user->id, 'category_id' => $this->category->id]);

        $response = $this->getJson("/api/budgets/{$budget->id}");

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_update_their_own_budget_with_valid_data()
    {
        Sanctum::actingAs($this->user);

        $budget = Budget::factory()->create(['user_id' => $this->user->id, 'category_id' => $this->category->id]);

        $updateData = [
            'amount' => 1500.00,
            'category_id' => $this->category->id,
            'date' => $budget->date,
        ];

        $response = $this->putJson("/api/budgets/{$budget->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);

        $this->assertDatabaseHas('budgets', $updateData);
    }

    public function test_authenticated_user_cannot_update_their_own_budget_with_invalid_data()
    {
        Sanctum::actingAs($this->user);

        $budget = Budget::factory()->create(['user_id' => $this->user->id, 'category_id' => $this->category->id]);

        $updateData = [
            'amount' => -1500.00, // Invalid amount
        ];

        $response = $this->putJson("/api/budgets/{$budget->id}", $updateData);

        $response->assertStatus(422);
    }

    public function test_authenticated_user_cannot_update_another_users_budget()
    {
        Sanctum::actingAs($this->user);

        $otherUser = User::factory()->create();
        $otherCategory = Category::factory()->create(['user_id' => $otherUser->id]);
        $otherBudget = Budget::factory()->create(['user_id' => $otherUser->id, 'category_id' => $otherCategory->id]);

        $updateData = ['amount' => 1500.00];

        $response = $this->putJson("/api/budgets/{$otherBudget->id}", $updateData);

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_update_any_budget()
    {
        $budget = Budget::factory()->create(['user_id' => $this->user->id, 'category_id' => $this->category->id]);

        $updateData = ['amount' => 1500.00];

        $response = $this->putJson("/api/budgets/{$budget->id}", $updateData);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_delete_their_own_budget()
    {
        Sanctum::actingAs($this->user);

        $budget = Budget::factory()->create(['user_id' => $this->user->id, 'category_id' => $this->category->id]);

        $response = $this->deleteJson("/api/budgets/{$budget->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('budgets', ['id' => $budget->id]);
    }

    public function test_authenticated_user_cannot_delete_another_users_budget()
    {
        Sanctum::actingAs($this->user);

        $otherUser = User::factory()->create();
        $otherCategory = Category::factory()->create(['user_id' => $otherUser->id]);
        $otherBudget = Budget::factory()->create(['user_id' => $otherUser->id, 'category_id' => $otherCategory->id]);

        $response = $this->deleteJson("/api/budgets/{$otherBudget->id}");

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_delete_any_budget()
    {
        $budget = Budget::factory()->create(['user_id' => $this->user->id, 'category_id' => $this->category->id]);
        $response = $this->deleteJson("/api/budgets/{$budget->id}");
        $response->assertStatus(401);
    }
}
