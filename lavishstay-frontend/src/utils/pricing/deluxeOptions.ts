import { RoomOption, DynamicPricingConfig, BookingContext } from '../dynamicPricing';
import { ROOM_PRICING } from './roomPricing';
import { calculateCancellationPolicy } from './cancellationPolicyUtils';

/**
 * Tạo options cho phòng Deluxe (2 options cho 1 người, 4 options cho 2 người)
 */
export const createDeluxeOptions = (
    _baseConfig: DynamicPricingConfig,
    context: BookingContext,
    isUrgentBooking: boolean,
    priceMultiplier: number = 1.0
): RoomOption[] => {

    const options: RoomOption[] = [];
    const pricing = ROOM_PRICING.deluxe;    // 2 options cho 1 người
    const singleBasicPrice = Math.round(pricing.singleGuest.basic * priceMultiplier); options.push({
        id: `deluxe_single_basic`,
        name: `Deluxe Basic - 1 khách`,
        pricePerNight: { vnd: singleBasicPrice },
        maxGuests: 1,
        minGuests: 1,
        roomType: 'deluxe',
        cancellationPolicy: calculateCancellationPolicy(context, singleBasicPrice),
        paymentPolicy: {
            type: "pay_now_with_vietQR",
            description: "Thanh toán ngay"
        }, availability: {
            total: 25,
            remaining: 18
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
            { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true }
        ],
        dynamicPricing: {
            basePrice: pricing.singleGuest.basic,
            finalPrice: singleBasicPrice,
            adjustments: [],
            savings: 0,
            urgencyLevel: isUrgentBooking ? 'urgent' : 'low',
            recommendationScore: 85
        }
    }); options.push({
        id: `deluxe_single_premium`,
        name: `Deluxe Premium - 1 khách`,
        pricePerNight: { vnd: Math.round(pricing.singleGuest.premium * priceMultiplier) },
        maxGuests: 1,
        minGuests: 1,
        roomType: 'deluxe',
        cancellationPolicy: calculateCancellationPolicy(context, Math.round(pricing.singleGuest.premium * priceMultiplier)),
        paymentPolicy: {
            type: "pay_at_hotel",
            description: "Thanh toán tại khách sạn"
        }, availability: {
            total: 15,
            remaining: 12
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi cao cấp", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Late check-out", included: true }
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
    });    // 4 options cho 2 người
    options.push({
        id: `deluxe_double_basic`,
        name: `Deluxe Basic - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.basic * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'deluxe',
        cancellationPolicy: calculateCancellationPolicy(context, Math.round(pricing.doubleGuest.basic * priceMultiplier)),
        paymentPolicy: {
            type: "pay_now_with_vietQR",
            description: "Thanh toán ngay - Giá tốt nhất"
        }, availability: {
            total: 30,
            remaining: 25
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
            { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true }
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
    }); options.push({
        id: `deluxe_double_standard`,
        name: `Deluxe Standard - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.standard * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'deluxe',
        cancellationPolicy: calculateCancellationPolicy(context, Math.round(pricing.doubleGuest.standard * priceMultiplier)),
        paymentPolicy: {
            type: "pay_now_with_vietQR",
            description: "Thanh toán ngay"
        }, availability: {
            total: 20,
            remaining: 15
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
            { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true },
            { icon: "UserOutlined", name: "Welcome drink", included: true }
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
    }); options.push({
        id: `deluxe_double_premium`,
        name: `Deluxe Premium - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.premium * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'deluxe',
        cancellationPolicy: calculateCancellationPolicy(context, Math.round(pricing.doubleGuest.premium * priceMultiplier)),
        paymentPolicy: {
            type: "pay_at_hotel",
            description: "Thanh toán tại khách sạn"
        }, availability: {
            total: 10,
            remaining: 6
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi cao cấp", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Welcome drink", included: true },
            { icon: "GiftOutlined", name: "Minibar", included: true }
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
        id: `deluxe_double_luxury`,
        name: `Deluxe Luxury - 2 khách`,
        pricePerNight: { vnd: Math.round(pricing.doubleGuest.luxury * priceMultiplier) },
        maxGuests: 2,
        minGuests: 1,
        roomType: 'deluxe',
        cancellationPolicy: calculateCancellationPolicy(context, Math.round(pricing.doubleGuest.luxury * priceMultiplier)),
        paymentPolicy: {
            type: "pay_at_hotel",
            description: "Thanh toán tại khách sạn"
        }, availability: {
            total: 10,
            remaining: 2
        },
        additionalServices: [
            { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
            { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
            { icon: "UserOutlined", name: "Butler service", included: true },
            { icon: "GiftOutlined", name: "Premium minibar", included: true },
            { icon: "StarOutlined", name: "Room upgrade", included: true }
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
