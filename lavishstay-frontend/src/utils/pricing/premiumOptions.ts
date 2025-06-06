import { RoomOption, DynamicPricingConfig, BookingContext } from '../dynamicPricing';
import { ROOM_PRICING } from './roomPricing';

/**
 * Tạo options cho phòng Premium (2 options cho 1 người, 4 options cho 2 người)
 */
export const createPremiumOptions = (
    _baseConfig: DynamicPricingConfig,
    context: BookingContext,
    isUrgentBooking: boolean,
    priceMultiplier: number = 1.0
): RoomOption[] => {
    const options: RoomOption[] = [];
    const pricing = ROOM_PRICING.premium;

    // 2 options cho 1 người
    options.push({
        id: `premium_single_basic`,
        name: `Premium Basic - 1 khách`,
        pricePerNight: { vnd: Math.round(pricing.singleGuest.basic * priceMultiplier) },
        maxGuests: 1,
        minGuests: 1,
        roomType: 'premium',
        cancellationPolicy: {
            type: "free",
            penalty: 0,
            freeUntil: context.checkInDate.subtract(1, 'day').toISOString(),
            description: "Hủy miễn phí trước 1 ngày"
        },
        paymentPolicy: {
            type: "pay_now_with_vietQR",
            description: "Thanh toán ngay"
        },
        availability: {
            total: 6,
            remaining: Math.floor(Math.random() * 5) + 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
            { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true },
            { icon: "UserOutlined", name: "Welcome drink", included: true }
        ],
        dynamicPricing: {
            basePrice: pricing.singleGuest.basic,
            finalPrice: Math.round(pricing.singleGuest.basic * priceMultiplier),
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'low',
            recommendationScore: 85
        }
    });

    options.push({
        id: `premium_single_premium`,
        name: `Premium Luxury - 1 khách`,
        pricePerNight: { vnd: Math.round(pricing.singleGuest.premium * priceMultiplier) },
        maxGuests: 1,
        minGuests: 1,
        roomType: 'premium',
        cancellationPolicy: {
            type: "free",
            penalty: 0,
            freeUntil: context.checkInDate.subtract(2, 'day').toISOString(),
            description: "Hủy miễn phí trước 2 ngày"
        },
        paymentPolicy: {
            type: "pay_at_hotel",
            description: "Thanh toán tại khách sạn"
        },
        availability: {
            total: 4,
            remaining: Math.floor(Math.random() * 3) + 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi cao cấp", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Late check-out", included: true },
            { icon: "GiftOutlined", name: "Minibar", included: true }
        ],
        recommended: true,
        dynamicPricing: {
            basePrice: pricing.singleGuest.premium,
            finalPrice: Math.round(pricing.singleGuest.premium * priceMultiplier),
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'low',
            recommendationScore: 90
        }
    });

    // 4 options cho 2 người
    options.push({
        id: `premium_double_basic`,
        name: `Premium Basic - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.basic * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'premium',
        cancellationPolicy: {
            type: "non_refundable",
            penalty: 100,
            description: "Không hoàn tiền"
        },
        paymentPolicy: {
            type: "pay_now_with_vietQR",
            description: "Thanh toán ngay - Giá tốt nhất"
        },
        availability: {
            total: 8,
            remaining: Math.floor(Math.random() * 6) + 2
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
            { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true },
            { icon: "UserOutlined", name: "Welcome drink", included: true }
        ],
        promotion: {
            type: "hot",
            message: "Giá tốt nhất!",
            discount: 15
        },
        dynamicPricing: {
            basePrice: Math.round(pricing.doubleGuest.basic * 1.15),
            finalPrice: Math.round(pricing.doubleGuest.basic * priceMultiplier),
            adjustments: [{ factor: 0.87, reason: "Ưu đãi đặc biệt", type: 'decrease' }],
            savings: Math.round(pricing.doubleGuest.basic * 0.15),
            urgencyLevel: isUrgentBooking ? 'urgent' : 'low',
            recommendationScore: 95
        }
    });

    options.push({
        id: `premium_double_standard`,
        name: `Premium Standard - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.standard * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'premium',
        cancellationPolicy: {
            type: "conditional",
            penalty: 50,
            description: "Hủy có điều kiện"
        },
        paymentPolicy: {
            type: "pay_now_with_vietQR",
            description: "Thanh toán ngay"
        },
        availability: {
            total: 7,
            remaining: Math.floor(Math.random() * 5) + 2
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
            { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true },
            { icon: "UserOutlined", name: "Welcome drink", included: true },
            { icon: "GiftOutlined", name: "Minibar", included: true }
        ],
        mostPopular: true,
        dynamicPricing: {
            basePrice: pricing.doubleGuest.standard,
            finalPrice: Math.round(pricing.doubleGuest.standard * priceMultiplier),
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'low',
            recommendationScore: 88
        }
    });

    options.push({
        id: `premium_double_premium`,
        name: `Premium Plus - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.premium * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'premium',
        cancellationPolicy: {
            type: "free",
            penalty: 0,
            freeUntil: context.checkInDate.subtract(1, 'day').toISOString(),
            description: "Hủy miễn phí trước 1 ngày"
        },
        paymentPolicy: {
            type: "pay_at_hotel",
            description: "Thanh toán tại khách sạn"
        },
        availability: {
            total: 5,
            remaining: Math.floor(Math.random() * 4) + 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi cao cấp", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Late check-out", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Room service", included: true }
        ],
        dynamicPricing: {
            basePrice: pricing.doubleGuest.premium,
            finalPrice: Math.round(pricing.doubleGuest.premium * priceMultiplier),
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'low',
            recommendationScore: 82
        }
    });

    options.push({
        id: `premium_double_luxury`,
        name: `Premium Luxury - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.luxury * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'premium',
        cancellationPolicy: {
            type: "free",
            penalty: 0,
            freeUntil: context.checkInDate.subtract(3, 'day').toISOString(),
            description: "Hủy miễn phí trước 3 ngày"
        },
        paymentPolicy: {
            type: "pay_at_hotel",
            description: "Thanh toán tại khách sạn"
        },
        availability: {
            total: 3,
            remaining: Math.floor(Math.random() * 2) + 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Butler service", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Room upgrade", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true }
        ],
        promotion: {
            type: "member",
            message: "Gói Luxury - Đẳng cấp"
        },
        dynamicPricing: {
            basePrice: pricing.doubleGuest.luxury,
            finalPrice: Math.round(pricing.doubleGuest.luxury * priceMultiplier),
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'low',
            recommendationScore: 75
        }
    });

    return options;
};
