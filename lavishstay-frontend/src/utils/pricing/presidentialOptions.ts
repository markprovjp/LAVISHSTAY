import { RoomOption, DynamicPricingConfig, BookingContext } from '../dynamicPricing';
import { ROOM_PRICING } from './roomPricing';
import { calculateCancellationPolicy } from './cancellationPolicyUtils';
/**
 * Tạo option cho phòng Presidential (chỉ có 1 option duy nhất cho 4 người)
 */
export const createPresidentialOptions = (
    _baseConfig: DynamicPricingConfig,
    context: BookingContext,
    isUrgentBooking: boolean,
    priceMultiplier: number = 1.0
): RoomOption[] => {
    const options: RoomOption[] = [];
    const pricing = ROOM_PRICING.presidential;

    // Chỉ có 1 option cho 4 người - phòng tổng thống
    options.push({
        id: `presidential_suite_4guest`,
        name: `Presidential Suite - 4 khách`,
        pricePerNight: { vnd: Math.round(pricing.fourGuest * priceMultiplier) },
        maxGuests: 4,
        minGuests: 1,
        roomType: 'presidential',
        cancellationPolicy: calculateCancellationPolicy(context, Math.round(pricing.fourGuest * priceMultiplier)),
        paymentPolicy: {
            type: "pay_at_hotel",
            description: "Thanh toán tại khách sạn - Dịch vụ VIP"
        },
        availability: {
            total: 1,
            remaining: 1,
            urgencyMessage: "Chỉ còn 1 phòng duy nhất!"
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP unlimited", included: true },
            { icon: "CarOutlined", name: "Valet parking", included: true },
            { icon: "UserOutlined", name: "Butler service 24/7", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Presidential amenities", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true },
            { icon: "HomeOutlined", name: "Spa access", included: true },
            { icon: "EyeOutlined", name: "City panoramic view", included: true },
            { icon: "CarOutlined", name: "Limousine service", included: true },
            { icon: "GiftOutlined", name: "Personal shopping", included: true }
        ],
        promotion: {
            type: "member",
            message: "Presidential Suite - Đã Sale 47 triệu"
        },
        recommended: true,
        mostPopular: false, // Không phổ biến vì giá cao
        dynamicPricing: {
            basePrice: 55000000, // Giá gốc 55 triệu
            finalPrice: Math.round(pricing.fourGuest * priceMultiplier), // Đã sale còn 47 triệu
            adjustments: [
                {
                    factor: 0.85,
                    reason: "Ưu đãi đặc biệt Presidential Suite",
                    type: 'decrease'
                }
            ],
            savings: 8000000, // Tiết kiệm 8 triệu
            urgencyLevel: isUrgentBooking ? 'urgent' : 'high',
            recommendationScore: 100 // Score cao nhất vì là phòng VIP nhất
        }
    });

    return options;
};
