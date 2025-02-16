<?php

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsAggregationTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetch_and_store_news()
    {
        $initialCount = Article::count(); // Get the initial count of articles

        $this->artisan('news:fetch'); // Run the news fetching command

        $newCount = Article::count(); // Get the new count after fetching

        $this->assertGreaterThan($initialCount, $newCount); // Ensure new articles were added
    }
}
