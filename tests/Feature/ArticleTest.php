<?php

use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetch_all_articles()
    {
        $user = User::factory()->create(); // ✅ Create a test user
        $this->actingAs($user, 'sanctum'); // ✅ Authenticate user

        Article::factory()->count(5)->create();

        $response = $this->getJson('/api/articles'); // ✅ Now this request is authenticated

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'title', 'description', 'url']
                     ]
                 ]);
    }

    public function test_fetch_single_article()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $article = Article::factory()->create();

        $response = $this->getJson("/api/articles/{$article->id}");

        $response->assertStatus(200)
                 ->assertJson(['title' => $article->title]);
    }

    public function test_search_articles()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Article::factory()->create(['title' => 'Laravel News']);
        Article::factory()->create(['title' => 'PHP Updates']);

        $response = $this->getJson('/api/articles?search=Laravel');

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Laravel News']);
    }
}
