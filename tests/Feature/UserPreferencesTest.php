<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserPreferencesTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_save_preferences()
{
    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum');

    $response = $this->postJson('/api/preferences', [
        'categories' => ['Technology', 'Sports'],
        'sources' => ['CNN', 'BBC']
    ]);

    $response->assertStatus(201)
             ->assertJson([
                 'message' => 'Preferences saved successfully' // ✅ Updated to match actual response
             ])
             ->assertJsonStructure([
                 'data' => [
                     'user_id',
                     'created_at',
                     'updated_at',
                     'id'
                 ]
             ]);
}


public function test_user_can_fetch_preferences()
{
    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum');

    $this->postJson('/api/preferences', [
        'categories' => ['Technology', 'Sports'],
        'sources' => ['CNN', 'BBC']
    ]);

    $response = $this->getJson('/api/preferences');

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [ // ✅ Wrapped inside 'data'
                     'category',
                     'source'
                 ]
             ]);
}
}
