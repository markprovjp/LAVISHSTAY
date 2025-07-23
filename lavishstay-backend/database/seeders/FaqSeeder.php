<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run()
    {
        $faqs = [
            [
                'question' => 'Làm thế nào để đặt phòng?',
                'answer' => 'Bạn có thể đặt phòng trực tuyến thông qua website của chúng tôi bằng cách: 1) Chọn ngày nhận và trả phòng, 2) Chọn loại phòng phù hợp, 3) Điền thông tin cá nhân, 4) Thanh toán để hoàn tất đặt phòng.',
                'category' => 'booking',
                'keywords' => ['đặt phòng', 'booking', 'reservation', 'book room'],
                'priority' => 10,
            ],
            [
                'question' => 'Chính sách hủy phòng như thế nào?',
                'answer' => 'Chính sách hủy phòng của chúng tôi: - Hủy trước 24h: Hoàn tiền 100%, - Hủy trong vòng 24h: Hoàn tiền 50%, - Hủy trong ngày: Không hoàn tiền. Các trường hợp đặc biệt sẽ được xem xét riêng.',
                'category' => 'policy',
                'keywords' => ['hủy phòng', 'cancel', 'cancellation', 'hoàn tiền', 'refund'],
                'priority' => 9,
            ],
            [
                'question' => 'Giờ nhận và trả phòng là khi nào?',
                'answer' => 'Giờ nhận phòng: 14:00 - 23:00. Giờ trả phòng: 06:00 - 12:00. Nếu bạn cần nhận/trả phòng ngoài giờ quy định, vui lòng liên hệ trước để chúng tôi hỗ trợ.',
                'category' => 'checkin',
                'keywords' => ['check in', 'check out', 'nhận phòng', 'trả phòng', 'giờ'],
                'priority' => 8,
            ],
            [
                'question' => 'Khách sạn có wifi miễn phí không?',
                'answer' => 'Có, chúng tôi cung cấp wifi miễn phí tốc độ cao trong toàn bộ khách sạn. Thông tin đăng nhập wifi sẽ được cung cấp khi bạn nhận phòng.',
                'category' => 'amenities',
                'keywords' => ['wifi', 'internet', 'miễn phí', 'free'],
                'priority' => 7,
            ],
            [
                'question' => 'Có dịch vụ đưa đón sân bay không?',
                'answer' => 'Có, chúng tôi cung cấp dịch vụ đưa đón sân bay với phí 200.000 VNĐ/lượt. Vui lòng đặt trước ít nhất 2 giờ để chúng tôi sắp xếp xe.',
                'category' => 'services',
                'keywords' => ['đưa đón', 'sân bay', 'airport', 'shuttle', 'transfer'],
                'priority' => 6,
            ],
            [
                'question' => 'Khách sạn có chỗ đậu xe không?',
                'answer' => 'Có, chúng tôi có bãi đậu xe miễn phí cho khách lưu trú. Bãi xe được bảo vệ 24/7 và có camera an ninh.',
                'category' => 'parking',
                'keywords' => ['đậu xe', 'parking', 'bãi xe', 'xe hơi'],
                'priority' => 5,
            ],
            [
                'question' => 'Có cho phép mang thú cưng không?',
                'answer' => 'Rất tiếc, khách sạn hiện tại không cho phép mang thú cưng. Chúng tôi khuyến khích bạn tìm dịch vụ giữ thú cưng gần đó.',
                'category' => 'policy',
                'keywords' => ['thú cưng', 'pet', 'chó', 'mèo', 'dog', 'cat'],
                'priority' => 4,
            ],
            [
                'question' => 'Làm thế nào để liên hệ với khách sạn?',
                'answer' => 'Bạn có thể liên hệ với chúng tôi qua: - Hotline: 1900-xxxx (24/7), - Email: info@lavishstay.com, - Địa chỉ: [Địa chỉ khách sạn], - Hoặc chat trực tuyến tại website.',
                'category' => 'contact',
                'keywords' => ['liên hệ', 'contact', 'hotline', 'email', 'phone'],
                'priority' => 8,
            ],
            [
                'question' => 'Khách sạn có nhà hàng không?',
                'answer' => 'Có, chúng tôi có nhà hàng phục vụ các món ăn Việt Nam và quốc tế. Giờ phục vụ: 6:00-22:00. Chúng tôi cũng có dịch vụ room service 24/7.',
                'category' => 'dining',
                'keywords' => ['nhà hàng', 'restaurant', 'ăn uống', 'food', 'room service'],
                'priority' => 6,
            ],
            [
                'question' => 'Có dịch vụ giặt ủi không?',
                'answer' => 'Có, chúng tôi cung cấp dịch vụ giặt ủi với giá cạnh tranh. Thời gian xử lý: 24-48 giờ tùy theo loại vải và yêu cầu.',
                'category' => 'services',
                'keywords' => ['giặt ủi', 'laundry', 'dry cleaning', 'giặt khô'],
                'priority' => 3,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
