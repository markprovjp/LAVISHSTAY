<?php

namespace Database\Seeders;

use App\Models\News\News;
use App\Models\News\NewsCategory;
use App\Models\MediaFile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class NewsSeeder extends Seeder
{
    public function run()
    {
        // Get random categories, media files, and users
        $categories = NewsCategory::all();
        $mediaFiles = MediaFile::all();
        $users = User::all();

        if ($categories->isEmpty() || $users->isEmpty()) {
            $this->command->error('Please run NewsCategorySeeder and make sure users exist first!');
            return;
        }

        $newsData = [
            [
                'title' => 'Khám Phá Không Gian Sang Trọng Tại LavishStay Resort',
                'slug' => 'kham-pha-khong-gian-sang-trong-tai-lavishstay-resort',
                'summary' => 'Trải nghiệm không gian nghỉ dưỡng đẳng cấp với thiết kế hiện đại và dịch vụ 5 sao tại LavishStay Resort.',
                'content' => '<p>LavishStay Resort mang đến cho du khách một trải nghiệm nghỉ dưỡng đẳng cấp với không gian sang trọng và dịch vụ tận tâm. Tọa lạc tại vị trí đắc địa, resort sở hữu kiến trúc hiện đại hòa quyện với thiên nhiên.</p><p>Các phòng nghỉ được thiết kế tinh tế với đầy đủ tiện nghi cao cấp, mang đến sự thoải mái tối đa cho khách hàng. Từ phòng Deluxe đến Suite Presidential, mỗi không gian đều được chăm chút kỹ lưỡng về từng chi tiết.</p><p>Resort còn sở hữu hệ thống tiện ích đa dạng bao gồm nhà hàng fine dining, spa cao cấp, hồ bơi infinity và trung tâm thể dục hiện đại.</p>',
                'tags' => ['resort', 'luxury', 'accommodation', 'travel'],
                'meta_title' => 'LavishStay Resort - Không Gian Nghỉ Dưỡng Đẳng Cấp 5 Sao',
                'meta_description' => 'Khám phá LavishStay Resort với không gian sang trọng, dịch vụ 5 sao và trải nghiệm nghỉ dưỡng đẳng cấp. Đặt phòng ngay để nhận ưu đãi đặc biệt.',
                'meta_keywords' => 'lavishstay, resort, luxury hotel, 5 star, nghỉ dưỡng, khách sạn cao cấp',
                'canonical_url' => '/news/kham-pha-khong-gian-sang-trong-tai-lavishstay-resort',
                'views' => rand(1000, 5000),
                'status' => 1,
                'published_at' => Carbon::now()->subDays(rand(1, 30)),
            ],
            [
                'title' => 'Ưu Đãi Mùa Hè 2024 - Giảm Giá Lên Đến 40%',
                'slug' => 'uu-dai-mua-he-2024-giam-gia-len-den-40-phan-tram',
                'summary' => 'Chương trình ưu đãi mùa hè đặc biệt với mức giảm giá lên đến 40% cho tất cả các hạng phòng tại LavishStay.',
                'content' => '<p>Mùa hè đã đến và LavishStay mang đến chương trình ưu đãi đặc biệt dành cho tất cả du khách. Với mức giảm giá lên đến 40%, đây là cơ hội tuyệt vời để bạn trải nghiệm kỳ nghỉ trong mơ.</p><h3>Ưu đãi bao gồm:</h3><ul><li>Giảm 40% cho phòng Suite và Presidential</li><li>Giảm 30% cho phòng Deluxe và Superior</li><li>Giảm 20% cho tất cả dịch vụ spa</li><li>Buffet sáng miễn phí cho trẻ em dưới 12 tuổi</li><li>Late check-out đến 14:00 miễn phí</li></ul><p>Chương trình có hiệu lực từ ngày 1/6 đến 31/8/2024. Áp dụng cho các đêm nghỉ từ Chủ Nhật đến Thứ Năm.</p>',
                'tags' => ['promotion', 'summer', 'discount', 'offer'],
                'meta_title' => 'Ưu Đãi Mùa Hè 2024 - Giảm Đến 40% Tại LavishStay Resort',
                'meta_description' => 'Đừng bỏ lỡ chương trình ưu đãi mùa hè với giảm giá lên đến 40% tất cả hạng phòng. Đặt ngay để nhận ưu đãi tốt nhất!',
                'meta_keywords' => 'ưu đãi, khuyến mãi, giảm giá, mùa hè, summer promotion',
                'canonical_url' => '/news/uu-dai-mua-he-2024-giam-gia-len-den-40-phan-tram',
                'views' => rand(2000, 8000),
                'status' => 1,
                'published_at' => Carbon::now()->subDays(rand(1, 15)),
            ],
            [
                'title' => 'Top 10 Địa Điểm Du Lịch Không Thể Bỏ Qua Gần LavishStay',
                'slug' => 'top-10-dia-diem-du-lich-khong-the-bo-qua-gan-lavishstay',
                'summary' => 'Khám phá những địa điểm du lịch hấp dẫn xung quanh khu vực LavishStay Resort với hướng dẫn chi tiết từ A đến Z.',
                'content' => '<p>Khi lưu trú tại LavishStay Resort, bạn sẽ có cơ hội khám phá nhiều địa điểm du lịch tuyệt vời xung quanh. Dưới đây là danh sách 10 địa điểm không thể bỏ qua:</p><h3>1. Bãi Biển Paradise</h3><p>Chỉ cách resort 5 phút đi bộ, bãi biển Paradise với làn nước trong xanh và bãi cát trắng mịn là nơi lý tưởng để thư giãn và tắm nắng.</p><h3>2. Chợ Đêm Địa Phương</h3><p>Trải nghiệm văn hóa địa phương qua những món ăn đường phố đặc sắc và các sản phẩm thủ công truyền thống.</p><h3>3. Đảo San Hô</h3><p>Tour lặn ngắm san hô với nhiều loài cá nhiệt đới đầy màu sắc, phù hợp cho cả người mới bắt đầu và chuyên nghiệp.</p><p>... và còn 7 địa điểm thú vị khác đang chờ bạn khám phá!</p>',
                'tags' => ['travel guide', 'attractions', 'tourism', 'local'],
                'meta_title' => 'Top 10 Địa Điểm Du Lịch Gần LavishStay Resort - Hướng Dẫn Chi Tiết',
                'meta_description' => 'Khám phá 10 địa điểm du lịch tuyệt vời xung quanh LavishStay Resort. Hướng dẫn đầy đủ về các hoạt động và điểm tham quan không thể bỏ qua.',
                'meta_keywords' => 'du lịch, điểm tham quan, hướng dẫn, tourism, attractions, travel guide',
                'canonical_url' => '/news/top-10-dia-diem-du-lich-khong-the-bo-qua-gan-lavishstay',
                'views' => rand(1500, 4500),
                'status' => 1,
                'published_at' => Carbon::now()->subDays(rand(5, 25)),
            ],
            [
                'title' => 'Grand Opening - Lễ Khai Trương Nhà Hàng Rooftop Mới',
                'slug' => 'grand-opening-le-khai-truong-nha-hang-rooftop-moi',
                'summary' => 'Tham gia lễ khai trương nhà hàng rooftop mới với không gian 360 độ và thực đơn fine dining độc đáo.',
                'content' => '<p>LavishStay Resort hân hạnh giới thiệu nhà hàng rooftop mới - Sky Lounge với tầm nhìn 360 độ tuyệt đẹp ra toàn thành phố và biển cả.</p><h3>Điểm đặc biệt của Sky Lounge:</h3><ul><li>Không gian mở với tầm nhìn panoramic</li><li>Thực đơn fusion cuisine do chef Michelin star thiết kế</li><li>Bar cocktail với hơn 200 loại đồ uống cao cấp</li><li>Live music mỗi tối từ 19:00-22:00</li><li>Không gian riêng tư cho các sự kiện đặc biệt</li></ul><p>Lễ khai trương sẽ diễn ra vào 20:00 ngày 15/12/2024 với sự tham gia của các celebrity và food blogger nổi tiếng. Khách mời sẽ được thưởng thức cocktail welcome drink và canapé miễn phí.</p>',
                'tags' => ['event', 'restaurant', 'opening', 'rooftop'],
                'meta_title' => 'Khai Trương Sky Lounge - Nhà Hàng Rooftop Đẳng Cấp Tại LavishStay',
                'meta_description' => 'Tham gia lễ khai trương Sky Lounge - nhà hàng rooftop với tầm nhìn 360 độ và thực đơn fine dining độc đáo tại LavishStay Resort.',
                'meta_keywords' => 'nhà hàng rooftop, khai trương, sky lounge, fine dining, event',
                'canonical_url' => '/news/grand-opening-le-khai-truong-nha-hang-rooftop-moi',
                'views' => rand(3000, 7000),
                'status' => 1,
                'published_at' => Carbon::now()->subDays(rand(3, 20)),
            ],
            [
                'title' => 'Thực Đơn Mùa Đông Đặc Biệt - Hương Vị Âm Thực Châu Á',
                'slug' => 'thuc-don-mua-dong-dac-biet-huong-vi-am-thuc-chau-a',
                'summary' => 'Khám phá thực đơn mùa đông với những món ăn truyền thống châu Á được chế biến bởi đội ngũ chef chuyên nghiệp.',
                'content' => '<p>Mùa đông đã đến và LavishStay Restaurant mang đến thực đơn đặc biệt với hương vị ấm áp của ẩm thực châu Á truyền thống.</p><h3>Món khai vị:</h3><ul><li>Dumpling tôm hấp với sốt gừng</li><li>Salad đu đủ Thái cay nhẹ</li><li>Chả cá Lã Vọng truyền thống</li></ul><h3>Món chính:</h3><ul><li>Lẩu Thái tôm hùm chua cay</li><li>Bún bò Huế chính hiệu</li><li>Cơm niêu Singapore với tôm rang</li><li>Mì Udon Nhật Bản nước dashi đậm đà</li></ul><h3>Tráng miệng:</h3><ul><li>Chè đậu xanh nước cốt dừa</li><li>Mochi ice cream vị matcha</li><li>Bánh flan caramen</li></ul><p>Thực đơn có hiệu lực từ 1/12/2024 đến 28/2/2025. Đặt bàn trước để được ưu tiên phục vụ.</p>',
                'tags' => ['cuisine', 'asian food', 'winter menu', 'restaurant'],
                'meta_title' => 'Thực Đơn Mùa Đông Châu Á - Ẩm Thực Đặc Sắc Tại LavishStay',
                'meta_description' => 'Thưởng thức thực đơn mùa đông đặc biệt với hương vị ẩm thực châu Á truyền thống tại nhà hàng LavishStay Resort.',
                'meta_keywords' => 'ẩm thực châu á, thực đơn mùa đông, nhà hàng, món ăn đặc sắc',
                'canonical_url' => '/news/thuc-don-mua-dong-dac-biet-huong-vi-am-thuc-chau-a',
                'views' => rand(800, 3000),
                'status' => 1,
                'published_at' => Carbon::now()->subDays(rand(7, 35)),
            ],
        ];

        foreach ($newsData as $index => $newsItem) {
            News::create([
                'title' => $newsItem['title'],
                'slug' => $newsItem['slug'],
                'summary' => $newsItem['summary'],
                'content' => $newsItem['content'],
                'tags' => $newsItem['tags'],
                'thumbnail_id' => $mediaFiles->random()->id ?? null,
                'author_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'meta_title' => $newsItem['meta_title'],
                'meta_description' => $newsItem['meta_description'],
                'meta_keywords' => $newsItem['meta_keywords'],
                'canonical_url' => $newsItem['canonical_url'],
                'schema_json' => [
                    '@context' => 'https://schema.org',
                    '@type' => 'Article',
                    'headline' => $newsItem['title'],
                    'description' => $newsItem['summary'],
                    'author' => [
                        '@type' => 'Organization',
                        'name' => 'LavishStay Resort'
                    ],
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => 'LavishStay Resort'
                    ]
                ],
                'views' => $newsItem['views'],
                'status' => $newsItem['status'],
                'published_at' => $newsItem['published_at'],
            ]);
        }

        // Create additional random news
        for ($i = 0; $i < 15; $i++) {
            News::create([
                'title' => 'Bài viết mẫu số ' . ($i + 6),
                'slug' => 'bai-viet-mau-so-' . ($i + 6),
                'summary' => 'Đây là bài viết mẫu số ' . ($i + 6) . ' để test dữ liệu.',
                'content' => '<p>Nội dung chi tiết của bài viết mẫu số ' . ($i + 6) . '.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>',
                'tags' => ['sample', 'test', 'demo'],
                'thumbnail_id' => $mediaFiles->random()->id ?? null,
                'author_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'meta_title' => 'Bài viết mẫu số ' . ($i + 6),
                'meta_description' => 'Mô tả bài viết mẫu số ' . ($i + 6),
                'meta_keywords' => 'sample, test, demo',
                'canonical_url' => '/news/bai-viet-mau-so-' . ($i + 6),
                'schema_json' => [],
                'views' => rand(50, 1000),
                'status' => 1,
                'published_at' => Carbon::now()->subDays(rand(1, 60)),
            ]);
        }
    }
}
