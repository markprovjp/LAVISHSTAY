import { RoomOption, DynamicPricingConfig, BookingContext } from '../dynamicPricing';
import { ROOM_PRICING } from './roomPricing';

/**
 * Tạo options cho phòng Suite (2 options cho 1 người, 4 options cho 2 người)
 */
export const createSuiteOptions = (
    _baseConfig: DynamicPricingConfig,
    context: BookingContext,
    isUrgentBooking: boolean,
    priceMultiplier: number = 1.0
): RoomOption[] => {
    const options: RoomOption[] = [];
    const pricing = ROOM_PRICING.suite;

    // 2 options cho 1 người (chỉ có 1 giá)
    options.push({
        id: `suite_single_basic`,
        name: `Suite Classic - 1 khách`,
        pricePerNight: { vnd: Math.round(pricing.singleGuest.basic * priceMultiplier) },
        maxGuests: 1,
        minGuests: 1,
        roomType: 'suite',
        cancellationPolicy: {
            type: "free",
            penalty: 0,
            freeUntil: context.checkInDate.subtract(2, 'day').toISOString(),
            description: "Hủy miễn phí trước 2 ngày"
        },
        paymentPolicy: {
            type: "pay_now_with_vietQR",
            description: "Thanh toán ngay"
        },
        availability: {
            total: 4,
            remaining: Math.floor(Math.random() * 3) + 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi cao cấp", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Late check-out", included: true },
            { icon: "GiftOutlined", name: "Minibar premium", included: true },
            { icon: "StarOutlined", name: "Room service", included: true }
        ],
        recommended: true,
        dynamicPricing: {
            basePrice: pricing.singleGuest.basic,
            finalPrice: Math.round(pricing.singleGuest.basic * priceMultiplier),
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'low',
            recommendationScore: 90
        }
    });

    options.push({
        id: `suite_single_premium`,
        name: `Suite Luxury - 1 khách`,
        pricePerNight: { vnd: Math.round(pricing.singleGuest.premium * priceMultiplier) },
        maxGuests: 1,
        minGuests: 1,
        roomType: 'suite',
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
            total: 2,
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
            message: "Suite Luxury - Đẳng cấp"
        },
        dynamicPricing: {
            basePrice: pricing.singleGuest.premium,
            finalPrice: Math.round(pricing.singleGuest.premium * priceMultiplier),
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'low',
            recommendationScore: 85
        }
    });

    // 4 options cho 2 người
    options.push({
        id: `suite_double_basic`,
        name: `Suite Basic - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.basic * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'suite',
        cancellationPolicy: {
            type: "conditional",
            penalty: 30,
            description: "Hủy có điều kiện"
        },
        paymentPolicy: {
            type: "pay_now_with_vietQR",
            description: "Thanh toán ngay - Giá tốt nhất"
        },
        availability: {
            total: 6,
            remaining: Math.floor(Math.random() * 4) + 2
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi cao cấp", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Welcome drink", included: true },
            { icon: "GiftOutlined", name: "Minibar", included: true }
        ],
        promotion: {
            type: "hot",
            message: "Suite giá tốt!",
            discount: 10
        },
        dynamicPricing: {
            basePrice: Math.round(pricing.doubleGuest.basic * 1.1),
            finalPrice: Math.round(pricing.doubleGuest.basic * priceMultiplier),
            adjustments: [{ factor: 0.91, reason: "Ưu đãi Suite", type: 'decrease' }],
            savings: Math.round(pricing.doubleGuest.basic * 0.1),
            urgencyLevel: isUrgentBooking ? 'urgent' : 'low',
            recommendationScore: 95
        }
    });

    options.push({
        id: `suite_double_standard`,
        name: `Suite Standard - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.standard * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'suite',
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
        id: `suite_double_premium`,
        name: `Suite Premium - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.premium * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'suite',
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
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Butler service", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Room upgrade", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true }
        ],
        recommended: true,
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
        id: `suite_double_luxury`,
        name: `Suite Luxury - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.luxury * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'suite',
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
            total: 2,
            remaining: Math.floor(Math.random() * 2) + 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Butler service 24/7", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Room upgrade", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true },
            { icon: "HomeOutlined", name: "Spa access", included: true }
        ],
        promotion: {
            type: "member",
            message: "Suite Luxury - Trải nghiệm tuyệt vời"
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
