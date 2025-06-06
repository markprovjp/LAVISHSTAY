import { RoomOption, DynamicPricingConfig, BookingContext } from '../dynamicPricing';
import { ROOM_PRICING } from './roomPricing';

/**
 * Tạo options cho phòng The Level Premium (2 options cho 1 người, 4 options cho 2 người)
 */
export const createTheLevelPremiumOptions = (
    _baseConfig: DynamicPricingConfig,
    context: BookingContext,
    isUrgentBooking: boolean,
    priceMultiplier: number = 1.0
): RoomOption[] => {
    const options: RoomOption[] = [];
    const pricing = ROOM_PRICING.theLevelPremium;

    // 2 options cho 1 người
    options.push({
        id: `theLevel_premium_single_basic`,
        name: `The Level Premium - 1 khách`,
        pricePerNight: { vnd: Math.round(pricing.singleGuest.basic * priceMultiplier) },
        maxGuests: 1,
        minGuests: 1,
        roomType: 'theLevel',
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
            total: 3,
            remaining: Math.floor(Math.random() * 2) + 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Concierge service", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Executive lounge", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true }
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
        id: `theLevel_premium_single_premium`,
        name: `The Level Premium Plus - 1 khách`,
        pricePerNight: { vnd: Math.round(pricing.singleGuest.premium * priceMultiplier) },
        maxGuests: 1,
        minGuests: 1,
        roomType: 'theLevel',
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
            remaining: 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Butler service", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Executive lounge", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true },
            { icon: "HomeOutlined", name: "Spa access", included: true }
        ],
        promotion: {
            type: "member",
            message: "The Level Premium - Đẳng cấp cao"
        },
        dynamicPricing: {
            basePrice: pricing.singleGuest.premium,
            finalPrice: Math.round(pricing.singleGuest.premium * priceMultiplier),
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'high',
            recommendationScore: 85
        }
    });

    // 4 options cho 2 người
    options.push({
        id: `theLevel_premium_double_basic`,
        name: `The Level Premium Basic - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.basic * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'theLevel',
        cancellationPolicy: {
            type: "conditional",
            penalty: 20,
            description: "Hủy có điều kiện"
        },
        paymentPolicy: {
            type: "pay_now_with_vietQR",
            description: "Thanh toán ngay - Giá tốt nhất"
        },
        availability: {
            total: 4,
            remaining: Math.floor(Math.random() * 3) + 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Concierge service", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Executive lounge", included: true }
        ],
        promotion: {
            type: "hot",
            message: "The Level giá tốt!",
            discount: 8
        },
        dynamicPricing: {
            basePrice: Math.round(pricing.doubleGuest.basic * 1.08),
            finalPrice: Math.round(pricing.doubleGuest.basic * priceMultiplier),
            adjustments: [{ factor: 0.93, reason: "Ưu đãi The Level", type: 'decrease' }],
            savings: Math.round(pricing.doubleGuest.basic * 0.08),
            urgencyLevel: isUrgentBooking ? 'urgent' : 'low',
            recommendationScore: 95
        }
    });

    options.push({
        id: `theLevel_premium_double_standard`,
        name: `The Level Premium Standard - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.standard * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'theLevel',
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
            total: 3,
            remaining: Math.floor(Math.random() * 2) + 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Butler service", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Executive lounge", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true }
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
        id: `theLevel_premium_double_premium`,
        name: `The Level Premium Plus - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.premium * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'theLevel',
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
            total: 2,
            remaining: Math.floor(Math.random() * 2) + 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Butler service", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Executive lounge", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true },
            { icon: "HomeOutlined", name: "Spa access", included: true }
        ],
        recommended: true,
        dynamicPricing: {
            basePrice: pricing.doubleGuest.premium,
            finalPrice: Math.round(pricing.doubleGuest.premium * priceMultiplier),
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'high',
            recommendationScore: 82
        }
    });

    options.push({
        id: `theLevel_premium_double_luxury`,
        name: `The Level Premium Luxury - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.luxury * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'theLevel',
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
            total: 1,
            remaining: 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Butler service 24/7", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Executive lounge", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true },
            { icon: "HomeOutlined", name: "Spa access", included: true },
            { icon: "EyeOutlined", name: "Personal assistant", included: true }
        ],
        promotion: {
            type: "member",
            message: "The Level Premium Luxury - Đỉnh cao"
        },
        dynamicPricing: {
            basePrice: pricing.doubleGuest.luxury,
            finalPrice: Math.round(pricing.doubleGuest.luxury * priceMultiplier),
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'high',
            recommendationScore: 75
        }
    });

    return options;
};

/**
 * Tạo options cho phòng The Level Premium Corner (2 options cho 1 người, 4 options cho 2 người)
 */
export const createTheLevelPremiumCornerOptions = (
    _baseConfig: DynamicPricingConfig,
    context: BookingContext,
    isUrgentBooking: boolean,
    priceMultiplier: number = 1.0
): RoomOption[] => {
    const options: RoomOption[] = [];
    const pricing = ROOM_PRICING.theLevelPremiumCorner;

    // 2 options cho 1 người
    options.push({
        id: `theLevel_corner_single_basic`,
        name: `The Level Premium Corner - 1 khách`,
        pricePerNight: { vnd: Math.round(pricing.singleGuest.basic * priceMultiplier) },
        maxGuests: 1,
        minGuests: 1,
        roomType: 'theLevel',
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
            total: 2,
            remaining: Math.floor(Math.random() * 2) + 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Concierge service", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Executive lounge", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true },
            { icon: "EyeOutlined", name: "Corner view", included: true }
        ],
        recommended: true,
        dynamicPricing: {
            basePrice: pricing.singleGuest.basic,
            finalPrice: Math.round(pricing.singleGuest.basic * priceMultiplier),
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'high',
            recommendationScore: 90
        }
    });

    options.push({
        id: `theLevel_corner_single_premium`,
        name: `The Level Premium Corner Plus - 1 khách`,
        pricePerNight: { vnd: Math.round(pricing.singleGuest.premium * priceMultiplier) },
        maxGuests: 1,
        minGuests: 1,
        roomType: 'theLevel',
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
            total: 1,
            remaining: 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Butler service", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Executive lounge", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true },
            { icon: "HomeOutlined", name: "Spa access", included: true },
            { icon: "EyeOutlined", name: "Premium corner view", included: true }
        ],
        promotion: {
            type: "member",
            message: "Corner Premium - View tuyệt đẹp"
        },
        dynamicPricing: {
            basePrice: pricing.singleGuest.premium,
            finalPrice: Math.round(pricing.singleGuest.premium * priceMultiplier),
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'high',
            recommendationScore: 85
        }
    });

    // 4 options cho 2 người (tương tự như Premium nhưng giá cao hơn)
    options.push({
        id: `theLevel_corner_double_basic`,
        name: `The Level Premium Corner Basic - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.basic * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'theLevel',
        cancellationPolicy: {
            type: "conditional",
            penalty: 20,
            description: "Hủy có điều kiện"
        },
        paymentPolicy: {
            type: "pay_now_with_vietQR",
            description: "Thanh toán ngay - Giá tốt nhất"
        },
        availability: {
            total: 3,
            remaining: Math.floor(Math.random() * 2) + 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Concierge service", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Executive lounge", included: true },
            { icon: "EyeOutlined", name: "Corner view", included: true }
        ],
        promotion: {
            type: "hot",
            message: "Corner view giá tốt!",
            discount: 8
        },
        dynamicPricing: {
            basePrice: Math.round(pricing.doubleGuest.basic * 1.08),
            finalPrice: Math.round(pricing.doubleGuest.basic * priceMultiplier),
            adjustments: [{ factor: 0.93, reason: "Ưu đãi Corner", type: 'decrease' }],
            savings: Math.round(pricing.doubleGuest.basic * 0.08),
            urgencyLevel: isUrgentBooking ? 'urgent' : 'high',
            recommendationScore: 95
        }
    });

    options.push({
        id: `theLevel_corner_double_standard`,
        name: `The Level Premium Corner Standard - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.standard * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'theLevel',
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
            total: 2,
            remaining: Math.floor(Math.random() * 2) + 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Butler service", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Executive lounge", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true },
            { icon: "EyeOutlined", name: "Corner view", included: true }
        ],
        mostPopular: true,
        dynamicPricing: {
            basePrice: pricing.doubleGuest.standard,
            finalPrice: Math.round(pricing.doubleGuest.standard * priceMultiplier),
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'high',
            recommendationScore: 88
        }
    });
    options.push({
        id: `theLevel_corner_plus_double_premium`,
        name: `The Level Premium Corner Plus - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.premium * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'theLevel',
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
            total: 1,
            remaining: 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Butler service", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Executive lounge", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true },
            { icon: "EyeOutlined", name: "Corner view", included: true }
        ],
        recommended: true,
        dynamicPricing: {
            basePrice: pricing.doubleGuest.premium,
            finalPrice: Math.round(pricing.doubleGuest.premium * priceMultiplier),
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'high',
            recommendationScore: 90
        }
    });

    options.push({
        id: `theLevel_corner_double_luxury`,
        name: `The Level Premium Corner Luxury - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.luxury * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'theLevel',
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
            total: 1,
            remaining: 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Butler service 24/7", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Executive lounge", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true },
            { icon: "HomeOutlined", name: "Spa access", included: true },
            { icon: "EyeOutlined", name: "Premium corner view", included: true }
        ],
        promotion: {
            type: "member",
            message: "Corner Luxury - View đỉnh cao"
        },
        dynamicPricing: {
            basePrice: pricing.doubleGuest.luxury,
            finalPrice: Math.round(pricing.doubleGuest.luxury * priceMultiplier),
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'high',
            recommendationScore: 75
        }
    });


    return options;
};

/**
 * Tạo options cho phòng Suite The Level (2 options cho 1 người, 4 options cho 2 người)
 */
export const createSuiteTheLevelOptions = (
    _baseConfig: DynamicPricingConfig,
    context: BookingContext,
    isUrgentBooking: boolean,
    priceMultiplier: number = 1.0
): RoomOption[] => {
    const options: RoomOption[] = [];
    const pricing = ROOM_PRICING.suiteTheLevel;

    // 2 options cho 1 người
    options.push({
        id: `suite_theLevel_single_basic`,
        name: `Suite The Level - 1 khách`,
        pricePerNight: { vnd: Math.round(pricing.singleGuest.basic * priceMultiplier) },
        maxGuests: 1,
        minGuests: 1,
        roomType: 'theLevel',
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
            remaining: 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Butler service 24/7", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Executive lounge", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true },
            { icon: "HomeOutlined", name: "Spa access", included: true },
            { icon: "EyeOutlined", name: "Suite amenities", included: true }
        ],
        recommended: true,
        promotion: {
            type: "member",
            message: "Suite The Level - Đỉnh cao sang trọng"
        },
        dynamicPricing: {
            basePrice: pricing.singleGuest.basic,
            finalPrice: Math.round(pricing.singleGuest.basic * priceMultiplier),
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'high',
            recommendationScore: 90
        }
    });

    options.push({
        id: `suite_theLevel_single_premium`,
        name: `Suite The Level Plus - 1 khách`,
        pricePerNight: { vnd: Math.round(pricing.singleGuest.premium * priceMultiplier) },
        maxGuests: 1,
        minGuests: 1,
        roomType: 'theLevel',
        cancellationPolicy: {
            type: "free",
            penalty: 0,
            freeUntil: context.checkInDate.subtract(4, 'day').toISOString(),
            description: "Hủy miễn phí trước 4 ngày"
        },
        paymentPolicy: {
            type: "pay_now_with_vietQR",
            description: "Thanh toán ngay"
        },
        availability: {
            total: 1,
            remaining: 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Butler service 24/7", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Executive lounge", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true },
            { icon: "HomeOutlined", name: "Spa access", included: true },
            { icon: "EyeOutlined", name: "Suite amenities", included: true }
        ],
        promotion: {
            type: "member",
            message: "Suite The Level Plus - Trải nghiệm đẳng cấp"
        },
        dynamicPricing: {
            basePrice: pricing.singleGuest.premium,
            finalPrice: Math.round(pricing.singleGuest.premium * priceMultiplier),
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'high',
            recommendationScore: 85
        }
    });

    // 4 options cho 2 người

    options.push({
        id: `suite_theLevel_double_basic`,
        name: `Suite The Level Basic - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.basic * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'theLevel',
        cancellationPolicy: {
            type: "conditional",
            penalty: 20,
            description: "Hủy có điều kiện"
        },
        paymentPolicy: {
            type: "pay_now_with_vietQR",
            description: "Thanh toán ngay - Giá tốt nhất"
        },
        availability: {
            total: 3,
            remaining: Math.floor(Math.random() * 2) + 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Butler service 24/7", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Executive lounge", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true },
            { icon: "EyeOutlined", name: "Suite amenities", included: true }
        ],
        promotion: {
            type: "hot",
            message: "Suite The Level giá tốt!",
            discount: 8
        },
        dynamicPricing: {
            basePrice: Math.round(pricing.doubleGuest.basic * 1.08),
            finalPrice: Math.round(pricing.doubleGuest.basic * priceMultiplier),
            adjustments: [{ factor: 0.93, reason: "Ưu đãi Suite The Level", type: 'decrease' }],
            savings: Math.round(pricing.doubleGuest.basic * 0.08),
            urgencyLevel: isUrgentBooking ? 'urgent' : 'high',
            recommendationScore: 95
        }
    });

    options.push({
        id: `suite_theLevel_double_standard`,
        name: `Suite The Level Standard - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.standard * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'theLevel',
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
            total: 2,
            remaining: Math.floor(Math.random() * 2) + 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Butler service 24/7", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Executive lounge", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true },
            { icon: "EyeOutlined", name: "Suite amenities", included: true }
        ],
        mostPopular: true,
        dynamicPricing: {
            basePrice: pricing.doubleGuest.standard,
            finalPrice: Math.round(pricing.doubleGuest.standard * priceMultiplier),
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'high',
            recommendationScore: 88
        }
    });

    options.push({
        id: `suite_theLevel_double_premium`,
        name: `Suite The Level Premium - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.premium * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'theLevel',
        cancellationPolicy: {
            type: "conditional",
            penalty: 20,
            description: "Hủy có điều kiện"
        },
        paymentPolicy: {
            type: "pay_now_with_vietQR",
            description: "Thanh toán ngay - Giá tốt nhất"
        },
        availability: {
            total: 3,
            remaining: Math.floor(Math.random() * 2) + 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Butler service 24/7", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Executive lounge", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true },
            { icon: "EyeOutlined", name: "Suite amenities", included: true }
        ],
        promotion: {
            type: "hot",
            message: "Suite The Level giá tốt!",
            discount: 8
        },
        dynamicPricing: {
            basePrice: Math.round(pricing.doubleGuest.premium * 1.08),
            finalPrice: Math.round(pricing.doubleGuest.premium * priceMultiplier),
            adjustments: [{ factor: 0.93, reason: "Ưu đãi Suite The Level", type: 'decrease' }],
            savings: Math.round(pricing.doubleGuest.premium * 0.08),
            urgencyLevel: isUrgentBooking ? 'urgent' : 'high',
            recommendationScore: 95
        }
    });

    options.push({
        id: `suite_theLevel_double_luxury`,
        name: `Suite The Level Luxury - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.luxury * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'theLevel',
        cancellationPolicy: {
            type: "free",
            penalty: 0,
            freeUntil: context.checkInDate.subtract(5, 'day').toISOString(),
            description: "Hủy miễn phí trước 5 ngày"
        },
        paymentPolicy: {
            type: "pay_at_hotel",
            description: "Thanh toán tại khách sạn"
        },
        availability: {
            total: 1,
            remaining: 1
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Butler service 24/7", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Executive lounge", included: true },
            { icon: "CoffeeOutlined", name: "In-room dining", included: true },
            { icon: "HomeOutlined", name: "Spa access", included: true },
            { icon: "EyeOutlined", name: "Suite amenities", included: true }
        ],
        promotion: {
            type: "member",
            message: "Suite The Level Luxury - Trải nghiệm đỉnh cao"
        },
        dynamicPricing: {
            basePrice: pricing.doubleGuest.luxury,
            finalPrice: Math.round(pricing.doubleGuest.luxury * priceMultiplier),
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'high',
            recommendationScore: 80
        }
    });

    return options;
};
