<?php

namespace Database\Seeders;

use App\Models\News\NewsUserAction;
use App\Models\News\News;
use App\Models\User;
use Illuminate\Database\Seeder;

class NewsUserActionSeeder extends Seeder
{
    public function run()
    {
        $news = News::all();
        $users = User::all();

        if ($news->isEmpty() || $users->isEmpty()) {
            $this->command->error('Please run NewsSeeder and make sure users exist first!');
            return;
        }

        foreach ($news as $newsItem) {
            // Randomly select 30-70% of users to interact with each news
            $interactingUsers = $users->random(rand(ceil($users->count() * 0.3), ceil($users->count() * 0.7)));
            
            foreach ($interactingUsers as $user) {
                $isLiked = rand(1, 100) <= 60; // 60% chance to like
                $isBookmarked = rand(1, 100) <= 25; // 25% chance to bookmark
                $rating = rand(1, 100) <= 40 ? rand(3, 5) + (rand(0, 10) / 10) : null; // 40% chance to rate (3.0-5.0)

                NewsUserAction::create([
                    'news_id' => $newsItem->id,
                    'user_id' => $user->id,
                    'is_liked' => $isLiked,
                    'is_bookmarked' => $isBookmarked,
                    'rating' => $rating,
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
