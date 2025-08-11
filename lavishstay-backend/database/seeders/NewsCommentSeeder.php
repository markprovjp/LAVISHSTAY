<?php

namespace Database\Seeders;

use App\Models\News\NewsComment;
use App\Models\News\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class NewsCommentSeeder extends Seeder
{
    public function run()
    {
        $news = News::all();
        $users = User::all();

        if ($news->isEmpty() || $users->isEmpty()) {
            $this->command->error('Please run NewsSeeder and make sure users exist first!');
            return;
        }

        $comments = [
            'Bài viết rất hay và bổ ích! Cảm ơn admin đã chia sẻ.',
            'Thông tin rất hữu ích, tôi sẽ book phòng ngay hôm nay.',
            'Khách sạn nhìn có vẻ sang trọng quá, mình phải đi thử mới được.',
            'Giá có hợp lý không ạ? Có ưu đãi gì cho khách lần đầu không?',
            'Tôi đã ở đây rồi và thật sự rất hài lòng với dịch vụ.',
            'Địa điểm tuyệt vời cho kỳ nghỉ gia đình!',
            'Staff thân thiện, phòng ốc sạch sẽ. Sẽ quay lại lần sau.',
            'Bữa sáng buffet đa dạng và ngon miệng.',
            'Pool và spa rất tuyệt vời!',
            'Check-in nhanh chóng, không phải chờ đợi.',
            'View từ phòng rất đẹp, đặc biệt là lúc sunset.',
            'Có shuttle bus đến sân bay không ạ?',
            'Parking miễn phí không? Tôi sẽ lái xe đến.',
            'Phòng gym có đầy đủ thiết bị không?',
            'WiFi có nhanh không? Tôi cần làm việc online.',
            'Pet-friendly không ạ?',
            'Có dịch vụ giặt ủi không?',
            'Late checkout có tính phí không?',
            'Gần trung tâm thương mại không ạ?',
            'Có tour du lịch địa phương không?',
        ];

        $replies = [
            'Cảm ơn bạn! Chúng tôi rất vui khi nhận được feedback tích cực.',
            'Chào bạn! Hiện tại chúng tôi có chương trình ưu đãi 20% cho khách lần đầu đặt phòng.',
            'Rất cảm ơn bạn đã tin tưởng và lựa chọn dịch vụ của chúng tôi.',
            'Shuttle bus miễn phí từ 6:00 AM đến 10:00 PM, cách 30 phút một chuyến.',
            'Parking hoàn toàn miễn phí trong suốt thời gian lưu trú.',
            'Phòng gym mở cửa 24/7 với đầy đủ thiết bị hiện đại.',
            'WiFi tốc độ cao miễn phí trong toàn bộ khu resort.',
            'Chúng tôi chào đón thú cưng với phụ phí 200.000 VND/đêm.',
            'Có dịch vụ giặt ủi với thời gian hoàn thành trong 24 giờ.',
            'Late checkout đến 2:00 PM miễn phí, sau đó tính phí 50% giá phòng.',
        ];

        foreach ($news as $newsItem) {
            // Create 3-8 comments for each news
            $numComments = rand(3, 8);
            
            for ($i = 0; $i < $numComments; $i++) {
                $comment = NewsComment::create([
                    'news_id' => $newsItem->id,
                    'user_id' => $users->random()->id,
                    'content' => $comments[array_rand($comments)],
                    'likes' => rand(0, 25),
                    'parent_id' => null,
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                ]);

                // 40% chance to have a reply
                if (rand(1, 100) <= 40) {
                    NewsComment::create([
                        'news_id' => $newsItem->id,
                        'user_id' => $users->random()->id,
                        'content' => $replies[array_rand($replies)],
                        'likes' => rand(0, 10),
                        'parent_id' => $comment->id,
                        'created_at' => Carbon::now()->subDays(rand(1, 25)),
                    ]);
                }
            }
        }
    }
}
