<?php

namespace Tests\Feature;

use App\Models\News\News;
use App\Models\News\NewsCategory;
use App\Models\News\NewsComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed basic data
        $this->seed([
            \Database\Seeders\NewsCategorySeeder::class,
            \Database\Seeders\NewsSeeder::class,
        ]);
    }

    /** @test */
    public function it_can_list_news_articles()
    {
        $response = $this->getJson('/api/news');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'slug',
                            'summary',
                            'category',
                            'author',
                            'thumbnail',
                            'published_at'
                        ]
                    ],
                    'current_page',
                    'last_page',
                    'total'
                ]);
    }

    /** @test */
    public function it_can_show_single_news_article()
    {
        $news = News::first();
        
        $response = $this->getJson("/api/news/{$news->slug}");
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'id',
                        'title',
                        'content',
                        'user_action',
                        'stats'
                    ]
                ]);
    }

    /** @test */
    public function it_can_create_news_article()
    {
        $category = NewsCategory::first();
        $user = User::factory()->create();
        
        $newsData = [
            'title' => 'Test News Article',
            'slug' => 'test-news-article',
            'summary' => 'Test summary',
            'content' => '<p>Test content</p>',
            'category_id' => $category->id,
            'tags' => ['test', 'sample'],
            'status' => 1,
            'published_at' => now()->format('Y-m-d H:i:s')
        ];
        
        $this->actingAs($user);
        $response = $this->postJson('/api/news', $newsData);
        
        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'title',
                        'slug'
                    ]
                ]);
                
        $this->assertDatabaseHas('news', [
            'title' => 'Test News Article',
            'slug' => 'test-news-article'
        ]);
    }

    /** @test */
    public function it_can_search_news_by_title()
    {
        $response = $this->getJson('/api/news?search_title=LavishStay');
        
        $response->assertStatus(200);
        
        $data = $response->json('data');
        foreach ($data as $news) {
            $this->assertTrue(
                stripos($news['title'], 'LavishStay') !== false ||
                stripos($news['content'], 'LavishStay') !== false
            );
        }
    }

    /** @test */
    public function it_can_filter_news_by_category()
    {
        $category = NewsCategory::first();
        
        $response = $this->getJson("/api/news?category_id={$category->id}");
        
        $response->assertStatus(200);
        
        $data = $response->json('data');
        foreach ($data as $news) {
            $this->assertEquals($category->id, $news['category']['id']);
        }
    }

    /** @test */
    public function it_can_create_comment_on_news()
    {
        $news = News::first();
        $user = User::factory()->create();
        
        $commentData = [
            'content' => 'This is a test comment'
        ];
        
        $this->actingAs($user);
        $response = $this->postJson("/api/news/{$news->id}/comments", $commentData);
        
        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'content',
                        'user'
                    ]
                ]);
                
        $this->assertDatabaseHas('news_comments', [
            'news_id' => $news->id,
            'content' => 'This is a test comment',
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function it_can_like_news_article()
    {
        $news = News::first();
        $user = User::factory()->create();
        
        $this->actingAs($user);
        $response = $this->postJson("/api/news-actions/{$news->id}/like");
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'is_liked',
                        'likes_count'
                    ]
                ]);
                
        $this->assertDatabaseHas('news_user_actions', [
            'news_id' => $news->id,
            'user_id' => $user->id,
            'is_liked' => true
        ]);
    }

    /** @test */
    public function it_can_rate_news_article()
    {
        $news = News::first();
        $user = User::factory()->create();
        
        $ratingData = ['rating' => 4.5];
        
        $this->actingAs($user);
        $response = $this->postJson("/api/news-actions/{$news->id}/rate", $ratingData);
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'rating',
                        'average_rating'
                    ]
                ]);
                
        $this->assertDatabaseHas('news_user_actions', [
            'news_id' => $news->id,
            'user_id' => $user->id,
            'rating' => 4.5
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_news()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user);
        $response = $this->postJson('/api/news', []);
        
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['title', 'slug', 'content', 'category_id']);
    }

    /** @test */
    public function it_can_get_popular_news()
    {
        $response = $this->getJson('/api/news/popular?limit=5');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'views'
                        ]
                    ]
                ]);
        
        // Check that results are ordered by views descending
        $data = $response->json('data');
        if (count($data) > 1) {
            $this->assertGreaterThanOrEqual($data[1]['views'], $data[0]['views']);
        }
    }
}

// Run tests with:
// php artisan test --filter NewsApiTest
