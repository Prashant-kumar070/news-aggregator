<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use Illuminate\Support\Facades\Http;

class FetchNews extends Command
{
    protected $signature = 'news:fetch';
    protected $description = 'Fetch news articles from external APIs and store them in the database';

    public function handle()
    {
        $this->fetchNewsAPI();
        $this->fetchGuardianAPI();
        $this->fetchNYTimesAPI();

        $this->info('News articles fetched successfully!');
    }

    private function fetchNewsAPI()
    {
        $response = Http::get('https://newsapi.org/v2/top-headlines', [
            'apiKey' => env('NEWSAPI_KEY'),
            'country' => 'us',
            'pageSize' => 10,
        ]);
        // print_r($response->json()['articles']);
        // dd('sher');
        if ($response->successful()) {
            foreach ($response->json()['articles'] as $news) {
                Article::updateOrCreate([
                    'url' => $news['url'],
                ], [
                    'title' => $news['title'],
                    'description' => $news['description'],
                    'source' => $news['source']['name'],
                    'author' => $news['author'],
                    'published_at' => date('Y-m-d H:i:s', strtotime($news['publishedAt'])),
                ]);
            }
        }
    }

    private function fetchGuardianAPI()
    {
        $response = Http::get('https://content.guardianapis.com/search', [
            'api-key' => env('GUARDIAN_API_KEY'),
            'show-fields' => 'headline,byline,short-url',
            'page-size' => 10,
        ]);

        if ($response->successful()) {
            foreach ($response->json()['response']['results'] as $news) {
                Article::updateOrCreate([
                    'url' => $news['fields']['shortUrl'],
                ], [
                    'title' => $news['fields']['headline'],
                    'description' => null,
                    'source' => 'The Guardian',
                    'author' => $news['fields']['byline'] ?? 'Unknown',
                    'published_at' => now(),
                ]);
            }
        }
    }

    private function fetchNYTimesAPI()
    {
        $apiKey = env('NYTIMES_API_KEY');
    
        if (!$apiKey) {
            $this->error('NY Times API key is missing!');
            return;
        }
    
        $url = "https://api.nytimes.com/svc/topstories/v2/home.json?api-key=$apiKey";
    
        $response = Http::get($url);
        if ($response->failed()) {
            $this->error('NY Times API Error: ' . $response->status());
            $this->error('Response: ' . $response->body());
            return;
        }
    
        foreach ($response->json()['results'] as $news) {
            Article::updateOrCreate([
                'url' => $news['url'],
            ], [
                'title' => $news['title'],
                'description' => $news['abstract'],
                'source' => 'NY Times',
                'author' => $news['byline'] ?? 'Unknown',
                'published_at' => date('Y-m-d H:i:s', strtotime($news['published_date'])),
            ]);
        }
    
        $this->info('NY Times articles fetched successfully!');
    }
    }
