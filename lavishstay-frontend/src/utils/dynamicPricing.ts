// src/utils/dynamicPricing.ts

import dayjs, { Dayjs } from 'dayjs';
import { RoomOption } from '../mirage/roomoption';


/**
 * Động cơ định giá động cho LavishStay Hotel Booking System
 * 
 * Logic phức tạp:
 * 1. Dynamic pricing dựa trên thời điểm đặt (booking timing)
 * 2. Weekend/Peak pricing (giá cuối tuần/cao điểm)
 * 3. Early payment discounts (giảm giá thanh toán sớm)
 * 4. Điều chỉnh giá theo mùa
 * 5. Tính linh hoạt của chính sách hủy bỏ dựa trên các mẫu đặt phòng
 */

export interface DynamicPricingConfig {
    basePrice: number;
    roomType: "deluxe" | "premium" | "suite" | "presidential" | "theLevel";
    maxGuests: number;
    minGuests: number;
}

export interface BookingContext {
    checkInDate: Dayjs;
    checkOutDate: Dayjs;
    bookingDate: Dayjs; // Ngày đặt phòng
    nights: number;
    guestCount: number;
}

export interface PricingAdjustment {
    factor: number;
    reason: string;
    type: 'increase' | 'decrease';
}

export interface DynamicPricingResult {
    basePrice: number;
    finalPrice: number;
    adjustments: PricingAdjustment[];
    savings: number;
    urgencyLevel: 'low' | 'medium' | 'high' | 'urgent';
    recommendationScore: number; // 0-100
}

/**
 * MAIN DYNAMIC PRICING ENGINE
 */
export class DynamicPricingEngine {

    /**
     * Calculate dynamic price với multiple factors
     */
    static calculateDynamicPrice(
        config: DynamicPricingConfig,
        context: BookingContext
    ): DynamicPricingResult {
        let currentPrice = config.basePrice;
        const adjustments: PricingAdjustment[] = [];

        // 1. BOOKING TIMING ADJUSTMENT (Thời điểm đặt)
        const timingAdjustment = this.calculateBookingTimingAdjustment(context);
        currentPrice *= timingAdjustment.factor;
        adjustments.push(timingAdjustment);

        // 2. WEEKEND/PEAK PRICING ( Giá cuối tuần/cao điểm)
        const weekendAdjustment = this.calculateWeekendPeakAdjustment(context);
        currentPrice *= weekendAdjustment.factor;
        adjustments.push(weekendAdjustment);

        // 3. SEASONAL PRICING (Điều chỉnh theo mùa)
        const seasonalAdjustment = this.calculateSeasonalAdjustment(context);
        currentPrice *= seasonalAdjustment.factor;
        adjustments.push(seasonalAdjustment);

        // 4. ROOM TYPE PREMIUM (Phụ phí theo loại phòng)
        const roomTypeAdjustment = this.calculateRoomTypePremium(config.roomType);
        currentPrice *= roomTypeAdjustment.factor;
        adjustments.push(roomTypeAdjustment);

        // 5. LENGTH OF STAY DISCOUNT (Ưu đãi lưu trú dài hạn)
        const lengthStayAdjustment = this.calculateLengthOfStayDiscount(context.nights);
        currentPrice *= lengthStayAdjustment.factor;
        adjustments.push(lengthStayAdjustment);

        const finalPrice = Math.round(currentPrice);
        const savings = config.basePrice - finalPrice;

        return {
            basePrice: config.basePrice,
            finalPrice,
            adjustments: adjustments.filter(adj => adj.factor !== 1),
            savings,
            urgencyLevel: this.calculateUrgencyLevel(context),
            recommendationScore: this.calculateRecommendationScore(adjustments, context)
        };
    }

    /**
     * 1. BOOKING TIMING ADJUSTMENT (Thời điểm đặt phòng)
     * - Cùng ngày/ngày hôm sau đặt phòng: rẻ hơn nhưng không hoàn lại
* - tiến bộ 2-7 ngày: Giá bình thường
* - 7+ ngày tiến bộ: Hủy bỏ phí bảo hiểm nhưng linh hoạt nhẹ
     */
    private static calculateBookingTimingAdjustment(context: BookingContext): PricingAdjustment {
        const daysInAdvance = context.checkInDate.diff(context.bookingDate, 'day');

        if (daysInAdvance <= 1) {
            // Cùng ngày/ngày hôm sau = giảm giá 10% nhưng không hoàn lại
            return {
                factor: 0.9,
                reason: 'Đặt phòng cùng ngày/ngày mai - Giá đặc biệt (không hoàn tiền)',
                type: 'decrease'
            };
        } else if (daysInAdvance <= 3) {
            // Đặt phòng vào phút cuối = giảm giá 5% 
            return {
                factor: 0.95,
                reason: 'Đặt phòng phút cuối - Giảm giá 5%',
                type: 'decrease'
            };
        } else if (daysInAdvance <= 7) {
            // Normal pricing
            return {
                factor: 1,
                reason: 'Giá chuẩn - Đặt trước 2-7 ngày',
                type: 'decrease'
            };
        } else if (daysInAdvance <= 30) {
            // Đặt phòng sớm = phí bảo hiểm nhỏ cho sự linh hoạt
            return {
                factor: 1.05,
                reason: 'Đặt trước sớm - Hủy linh hoạt (+5%)',
                type: 'increase'
            };
        } else {
            // Đặt chỗ rất sớm = Tính linh hoạt cao cấp nhưng tối đa
            return {
                factor: 1.1,
                reason: 'Đặt trước rất sớm - Chính sách hủy tốt nhất (+10%)',
                type: 'increase'
            };
        }
    }

    /**
     * 2. WEEKEND/PEAK PRICING
* - Các ngày trong tuần (Thứ Hai -Thứ 6): Giá cơ sở
* - Thứ Sáu/Chủ nhật: +15%
* - Thứ bảy: +25%
* - Thời gian nghỉ mát: +30-50%
     */
    private static calculateWeekendPeakAdjustment(context: BookingContext): PricingAdjustment {
        let maxMultiplier = 1;
        let peakDays = 0;
        let currentDate = context.checkInDate.clone();

        // Check each night for weekend/peak pricing
        for (let i = 0; i < context.nights; i++) {
            const dayOfWeek = currentDate.day(); // 0 = Sunday, 6 = Saturday

            if (dayOfWeek === 6) { // Saturday
                maxMultiplier = Math.max(maxMultiplier, 1.25);
                peakDays++;
            } else if (dayOfWeek === 0 || dayOfWeek === 5) { // Sunday or Friday
                maxMultiplier = Math.max(maxMultiplier, 1.15);
                peakDays++;
            }

            // Check for holiday periods
            if (this.isHolidayPeriod(currentDate)) {
                maxMultiplier = Math.max(maxMultiplier, 1.4); // +40% for holidays
                peakDays++;
            }

            currentDate = currentDate.add(1, 'day');
        }

        // Average the multiplier based on peak nights ratio
        const peakRatio = peakDays / context.nights;
        const finalMultiplier = 1 + (maxMultiplier - 1) * peakRatio;

        if (finalMultiplier > 1) {
            return {
                factor: finalMultiplier,
                reason: `Giá cuối tuần/cao điểm (+${Math.round((finalMultiplier - 1) * 100)}%)`,
                type: 'increase'
            };
        }

        return {
            factor: 1,
            reason: 'Giá ngày thường',
            type: 'decrease'
        };
    }

    /**
     * 3. SEASONAL PRICING
     * - Low season (May-Sept): -5% to -10%
     * - High season (Oct-Apr): base price to +15%
     * - Peak season (Dec-Jan, Tet): +20% to +30%
     */
    private static calculateSeasonalAdjustment(context: BookingContext): PricingAdjustment {
        const month = context.checkInDate.month() + 1; // dayjs months are 0-indexed

        // Peak season (December, January, Tet period)
        if (month === 12 || month === 1 || this.isTetPeriod(context.checkInDate)) {
            return {
                factor: 1.25,
                reason: 'Mùa cao điểm (+25%)',
                type: 'increase'
            };
        }

        // High season (October, November, February, March)
        if ([10, 11, 2, 3].includes(month)) {
            return {
                factor: 1.1,
                reason: 'Mùa du lịch (+10%)',
                type: 'increase'
            };
        }

        // Low season (May-September)
        if ([5, 6, 7, 8, 9].includes(month)) {
            return {
                factor: 0.9,
                reason: 'Mùa thấp điểm (-10%)',
                type: 'decrease'
            };
        }

        // Normal season
        return {
            factor: 1,
            reason: 'Giá theo mùa bình thường',
            type: 'decrease'
        };
    }

    /**
     * 4. ROOM TYPE PREMIUM MULTIPLIER
     */
    private static calculateRoomTypePremium(roomType: string): PricingAdjustment {
        const premiums: Record<string, number> = {
            deluxe: 1,       // Base 
            premium: 1.3,    // +30%
            suite: 1.8,      // +80%
            presidential: 3.2, // +220%
            theLevel: 2.5    // +150%
        };

        const multiplier = premiums[roomType] || 1;

        if (multiplier > 1) {
            return {
                factor: multiplier,
                reason: `Premium ${roomType} (+${Math.round((multiplier - 1) * 100)}%)`,
                type: 'increase'
            };
        }

        return {
            factor: 1,
            reason: 'Phòng tiêu chuẩn',
            type: 'decrease'
        };
    }

    /**
     * 5. LENGTH OF STAY DISCOUNT
     * - 1-2 nights: no discount
     * - 3-6 nights: -3%
     * - 7+ nights: -5% to -10%
     */
    private static calculateLengthOfStayDiscount(nights: number): PricingAdjustment {
        if (nights >= 14) {
            return {
                factor: 0.9,
                reason: 'Ưu đãi lưu trú dài hạn (-10%)',
                type: 'decrease'
            };
        } else if (nights >= 7) {
            return {
                factor: 0.95,
                reason: 'Ưu đãi lưu trú tuần (-5%)',
                type: 'decrease'
            };
        } else if (nights >= 3) {
            return {
                factor: 0.97,
                reason: 'Ưu đãi lưu trú nhiều đêm (-3%)',
                type: 'decrease'
            };
        }

        return {
            factor: 1,
            reason: 'Lưu trú ngắn hạn',
            type: 'decrease'
        };
    }

    /**
     * Tính toán mức độ khẩn cấp dựa trên các mẫu đặt phòng
     */
    private static calculateUrgencyLevel(context: BookingContext): 'low' | 'medium' | 'high' | 'urgent' {
        const daysInAdvance = context.checkInDate.diff(context.bookingDate, 'day');

        if (daysInAdvance <= 1) return 'urgent';
        if (daysInAdvance <= 3) return 'high';
        if (daysInAdvance <= 7) return 'medium';
        return 'low';
    }

    /**
     * Calculate recommendation score (0-100)
     */
    private static calculateRecommendationScore(
        adjustments: PricingAdjustment[],
        context: BookingContext
    ): number {
        let score = 50; // Base score

        // Bonus for savings
        const totalDiscount = adjustments
            .filter(adj => adj.type === 'decrease' && adj.factor < 1)
            .reduce((sum, adj) => sum + (1 - adj.factor), 0);

        score += totalDiscount * 100; // Each 1% discount adds 1 point

        // Bonus for early booking (more flexible cancellation)
        const daysInAdvance = context.checkInDate.diff(context.bookingDate, 'day');
        if (daysInAdvance > 7) {
            score += 15; // Flexibility bonus
        }

        // Bonus for longer stays
        if (context.nights >= 3) {
            score += 10;
        }

        return Math.min(100, Math.max(0, score));
    }

    /**
     * Helper: Kiểm tra xem ngày có đang trong kỳ nghỉ không
     */
    private static isHolidayPeriod(date: Dayjs): boolean {
        const month = date.month() + 1;
        const day = date.date();

        // Vietnamese holidays
        const holidays = [
            { month: 4, day: 30 }, // Liberation Day
            { month: 5, day: 1 },  // Labor Day
            { month: 9, day: 2 },  // Independence Day
            // Add more holidays as needed
        ];

        return holidays.some(holiday =>
            holiday.month === month && holiday.day === day
        );
    }

    /**
     * Helper: Check if date is in Tet period
     */
    private static isTetPeriod(date: Dayjs): boolean {
        // Simplified Tet check - usually late January to mid February
        const month = date.month() + 1;
        const day = date.date();

        return (month === 1 && day >= 20) || (month === 2 && day <= 15);
    }
}

/**
 * ROOM OPTION GENERATOR với Dynamic Pricing theo yêu cầu mới
 */
export class RoomOptionGenerator {    /**
     * Generate multiple room options theo logic nghiệp vụ mới
     */
    static generateDynamicRoomOptions(
        baseConfig: DynamicPricingConfig,
        context: BookingContext
    ): RoomOption[] {
        const options: RoomOption[] = [];
        const daysInAdvance = context.checkInDate.diff(context.bookingDate, 'day');
        const isUrgentBooking = daysInAdvance <= 1; // Hôm nay hoặc ngày mai

        // Tạo options theo roomType và guest count
        if (baseConfig.roomType === 'deluxe') {
            options.push(...this.createDeluxeOptions(baseConfig, context, isUrgentBooking));
        } else if (baseConfig.roomType === 'premium') {
            options.push(...this.createPremiumOptions(baseConfig, context, isUrgentBooking));
        } else if (baseConfig.roomType === 'suite') {
            options.push(...this.createSuiteOptions(baseConfig, context, isUrgentBooking));
        } else if (baseConfig.roomType === 'presidential') {
            options.push(...this.createPresidentialOptions(baseConfig, context, isUrgentBooking));
        } else if (baseConfig.roomType === 'theLevel') {
            options.push(...this.createTheLevelOptions(baseConfig, context, isUrgentBooking));
        }

        return options.sort((a, b) => a.pricePerNight.vnd - b.pricePerNight.vnd);
    }    /**
     * Tạo options cho phòng Deluxe theo yêu cầu
     */    private static createDeluxeOptions(
        baseConfig: DynamicPricingConfig,
        context: BookingContext,
        isUrgentBooking: boolean
    ): RoomOption[] {
        const options: RoomOption[] = [];

        // Luôn tạo options cho cả 1 người và 2 người
        // Option cho 1 người
        options.push(this.createDeluxeSingleGuestOption(baseConfig, context, isUrgentBooking));
        // Options cho 2 người (3 options khác nhau)
        options.push(...this.createDeluxeDoubleGuestOptions(baseConfig, context, isUrgentBooking));

        return options;
    }/**
     * Tạo option cho 1 người - phòng Deluxe
     */    private static createDeluxeSingleGuestOption(
        baseConfig: DynamicPricingConfig,
        _context: BookingContext,
        isUrgentBooking: boolean
    ): RoomOption {
        const basePrice = 1500000; // 1.5 triệu cho 1 người

        return {
            id: `${baseConfig.roomType}_single_1guest`,
            name: `Deluxe Classic - 1 khách`,
            pricePerNight: { vnd: basePrice },
            maxGuests: 1,
            minGuests: 1,
            roomType: baseConfig.roomType,

            cancellationPolicy: {
                type: "non_refundable",
                penalty: 100,
                description: isUrgentBooking
                    ? "Phí hủy: Toàn bộ tiền phòng"
                    : "Phí hủy: Toàn bộ tiền phòng"
            },

            paymentPolicy: {
                type: "pay_now_with_vietQR",
                description: "Thanh toán ngay"
            },

            availability: {
                total: 5,
                remaining: Math.floor(Math.random() * 4) + 1,
                urgencyMessage: isUrgentBooking ? "Đặt gấp - Chỉ còn ít phòng!" : undefined
            },

            additionalServices: [
                { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
                { icon: "CoffeeOutlined", name: "Bữa sáng tuyệt hảo", included: true },
                { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true }
            ],

            recommended: true, // Ưu tiên cho 1 người

            dynamicPricing: {
                basePrice: basePrice,
                finalPrice: basePrice,
                adjustments: [],
                savings: 0,
                urgencyLevel: isUrgentBooking ? 'urgent' : 'low',
                recommendationScore: 90 // High score vì match với guest count
            }
        };
    }

    /**
     * Tạo 3 options cho 2 người - phòng Deluxe
     */    private static createDeluxeDoubleGuestOptions(
        baseConfig: DynamicPricingConfig,
        context: BookingContext,
        isUrgentBooking: boolean
    ): RoomOption[] {
        const options: RoomOption[] = [];        // Option 1: Giá rẻ nhất - 1.2 triệu, chỉ có bữa sáng tuyệt hảo thêm 260k
        options.push({
            id: `${baseConfig.roomType}_basic_2guest`,
            name: `Deluxe Standard - 2 khách`,
            pricePerNight: { vnd: 1200000 },
            maxGuests: 2,
            minGuests: 1,
            roomType: baseConfig.roomType,


            cancellationPolicy: {
                type: "non_refundable",
                penalty: 100,
                description: isUrgentBooking
                    ? "Phí hủy: Toàn bộ tiền phòng"
                    : context.nights > 1
                        ? "Phí hủy: Đêm đầu tiên"
                        : "Phí hủy: Toàn bộ tiền phòng"
            },

            paymentPolicy: {
                type: "pay_now_with_vietQR",
                description: "Thanh toán ngay - Giá tốt nhất"
            },

            availability: {
                total: 8,
                remaining: Math.floor(Math.random() * 6) + 1,
                urgencyMessage: isUrgentBooking ? "Đặt gấp!" : undefined
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
                basePrice: 1400000,
                finalPrice: 1200000,
                adjustments: [{ factor: 0.86, reason: "Giá ưu đãi đặc biệt", type: 'decrease' }],
                savings: 200000,
                urgencyLevel: isUrgentBooking ? 'urgent' : 'low',
                recommendationScore: 75
            }
        });        // Option 2: Bao gồm bữa sáng tuyệt hảo
        options.push({
            id: `${baseConfig.roomType}_breakfast_2guest`,
            name: `Deluxe Premium - 2 khách`,
            pricePerNight: { vnd: 1460000 },
            maxGuests: 2,
            minGuests: 1,
            roomType: baseConfig.roomType,


            cancellationPolicy: {
                type: isUrgentBooking ? "non_refundable" : "conditional",
                penalty: 100,
                description: isUrgentBooking
                    ? "Phí hủy: Toàn bộ tiền phòng"
                    : context.nights > 1
                        ? "Phí hủy: Đêm đầu tiên"
                        : "Phí hủy: Toàn bộ tiền phòng"
            },

            paymentPolicy: {
                type: "pay_now_with_vietQR",
                description: "Thanh toán ngay"
            },

            availability: {
                total: 8,
                remaining: Math.floor(Math.random() * 6) + 1
            },

            additionalServices: [
                { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
                { icon: "CoffeeOutlined", name: "Bữa sáng tuyệt hảo", included: true },
                { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true }
            ],

            mostPopular: true,

            dynamicPricing: {
                basePrice: 1720000,
                finalPrice: 1460000,
                adjustments: [{ factor: 0.85, reason: "Combo bữa sáng ưu đãi", type: 'decrease' }],
                savings: 260000,
                urgencyLevel: isUrgentBooking ? 'urgent' : 'low',
                recommendationScore: 85
            }
        });        // Option 3: Bao gồm cả bữa sáng và tối - 2.5 triệu
        options.push({
            id: `${baseConfig.roomType}_fullboard_2guest`,
            name: `Deluxe Executive - 2 khách`,
            pricePerNight: { vnd: 2500000 },
            maxGuests: 2,
            minGuests: 1,
            roomType: baseConfig.roomType,


            cancellationPolicy: {
                type: isUrgentBooking ? "non_refundable" : "free",
                penalty: isUrgentBooking ? 100 : 0,
                freeUntil: isUrgentBooking ? undefined : context.checkInDate.subtract(2, 'day').toISOString(),
                description: isUrgentBooking
                    ? "Phí hủy: Toàn bộ tiền phòng"
                    : context.nights > 1
                        ? "Hủy miễn phí trước 2 ngày / Phí hủy muộn: Đêm đầu tiên"
                        : "Hủy miễn phí trước 2 ngày / Phí hủy muộn: Toàn bộ tiền phòng"
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
                { icon: "CoffeeOutlined", name: "Bữa sáng tuyệt hảo", included: true },
                { icon: "UtensilsOutlined", name: "Bữa tối cao cấp", included: true },
                { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
                { icon: "UserOutlined", name: "Late check-out miễn phí", included: true }
            ],

            promotion: {
                type: "member",
                message: "Gói Full Board - Tiện lợi nhất"
            },

            dynamicPricing: {
                basePrice: 2800000,
                finalPrice: 2500000,
                adjustments: [{ factor: 0.89, reason: "Gói combo ưu đãi", type: 'decrease' }],
                savings: 300000,
                urgencyLevel: isUrgentBooking ? 'urgent' : 'low',
                recommendationScore: 70
            }
        });

        return options;
    }

    /**
     * Tạo options cho phòng Premium 
     */
    private static createPremiumOptions(
        baseConfig: DynamicPricingConfig,
        context: BookingContext,
        isUrgentBooking: boolean
    ): RoomOption[] {
        // Premium room có giá cao hơn Deluxe khoảng 50%
        return this.createStandardRoomOptions(baseConfig, context, isUrgentBooking, {
            singleGuestPrice: 2200000, // 2.2M cho 1 người
            doubleGuestBasic: 1800000, // 1.8M cho 2 người basic
            doubleGuestBreakfast: 2060000, // 2.06M với breakfast
            doubleGuestFullboard: 3500000, // 3.5M full board
        });
    }

    /**
     * Tạo options cho phòng Suite
     */
    private static createSuiteOptions(
        baseConfig: DynamicPricingConfig,
        context: BookingContext,
        isUrgentBooking: boolean
    ): RoomOption[] {
        // Suite có giá cao hơn Premium khoảng 70%
        return this.createStandardRoomOptions(baseConfig, context, isUrgentBooking, {
            singleGuestPrice: 3500000, // 3.5M cho 1 người
            doubleGuestBasic: 2800000, // 2.8M cho 2 người basic
            doubleGuestBreakfast: 3060000, // 3.06M với breakfast
            doubleGuestFullboard: 5000000, // 5M full board
        });
    }

    /**
     * Tạo options cho phòng Presidential - chỉ có option cho 4 người
     */
    private static createPresidentialOptions(
        baseConfig: DynamicPricingConfig,
        context: BookingContext,
        isUrgentBooking: boolean
    ): RoomOption[] {
        const options: RoomOption[] = [];        // Chỉ có 1 option cho 4 người - phòng tổng thống
        options.push({
            id: `${baseConfig.roomType}_presidential_4guest`,
            name: `Presidential Suite - 4 khách`,
            pricePerNight: { vnd: 50000000 }, // 50M cho 4 người
            maxGuests: 4,
            minGuests: 1,
            roomType: baseConfig.roomType,

            cancellationPolicy: {
                type: isUrgentBooking ? "non_refundable" : "free",
                penalty: isUrgentBooking ? 100 : 0,
                freeUntil: isUrgentBooking ? undefined : context.checkInDate.subtract(3, 'day').toISOString(),
                description: isUrgentBooking
                    ? "Phí hủy: Toàn bộ tiền phòng"
                    : context.nights > 1
                        ? "Hủy miễn phí trước 3 ngày / Phí hủy muộn: 2 đêm đầu"
                        : "Hủy miễn phí trước 3 ngày / Phí hủy muộn: Toàn bộ tiền phòng"
            },

            paymentPolicy: {
                type: "pay_at_hotel",
                description: "Thanh toán tại khách sạn - Dịch vụ VIP"
            },

            availability: {
                total: 2,
                remaining: 1,
                urgencyMessage: "Chỉ còn 1 phòng cuối cùng!"
            },

            additionalServices: [
                { icon: "WifiOutlined", name: "Wi-Fi VIP", included: true },
                { icon: "CoffeeOutlined", name: "Bữa sáng VIP", included: true },
                { icon: "UtensilsOutlined", name: "Bữa tối cao cấp", included: true },
                { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
                { icon: "UserOutlined", name: "Butler service 24/7", included: true },
                { icon: "StarOutlined", name: "Spa miễn phí", included: true }
            ],

            promotion: {
                type: "member",
                message: "Phòng Presidential - Đẳng cấp nhất"
            },

            recommended: true,

            dynamicPricing: {
                basePrice: 50000000,
                finalPrice: 4700000, // Giảm giá đặc biệt
                adjustments: [{ factor: 0.8, reason: "Ưu đãi đặc biệt Presidential", type: 'decrease' }],
                savings: 2000000,
                urgencyLevel: isUrgentBooking ? 'urgent' : 'high',
                recommendationScore: 95
            }
        });

        return options;
    }

    /**
     * Tạo options cho phòng The Level
     */
    private static createTheLevelOptions(
        baseConfig: DynamicPricingConfig,
        context: BookingContext,
        isUrgentBooking: boolean
    ): RoomOption[] {
        // The Level có giá cao hơn Suite khoảng 50%
        return this.createStandardRoomOptions(baseConfig, context, isUrgentBooking, {
            singleGuestPrice: 5000000, // 5M cho 1 người
            doubleGuestBasic: 4200000, // 4.2M cho 2 người basic
            doubleGuestBreakfast: 4460000, // 4.46M với breakfast
            doubleGuestFullboard: 7000000, // 7M full board
        });
    }

    /**
     * Helper method để tạo standard room options với pricing khác nhau
     */
    private static createStandardRoomOptions(
        baseConfig: DynamicPricingConfig,
        context: BookingContext,
        isUrgentBooking: boolean,
        pricing: {
            singleGuestPrice: number;
            doubleGuestBasic: number;
            doubleGuestBreakfast: number;
            doubleGuestFullboard: number;
        }
    ): RoomOption[] {
        const options: RoomOption[] = [];        // Option cho 1 người
        options.push({
            id: `${baseConfig.roomType}_single_1guest`,
            name: `${baseConfig.roomType.charAt(0).toUpperCase() + baseConfig.roomType.slice(1)} Classic - 1 khách`,
            pricePerNight: { vnd: pricing.singleGuestPrice },
            maxGuests: 1,
            minGuests: 1,
            roomType: baseConfig.roomType,


            cancellationPolicy: {
                type: "non_refundable",
                penalty: 100,
                description: isUrgentBooking
                    ? "Phí hủy: Toàn bộ tiền phòng"
                    : "Phí hủy: Toàn bộ tiền phòng"
            },

            paymentPolicy: {
                type: "pay_now_with_vietQR",
                description: "Thanh toán ngay"
            },

            availability: {
                total: 5,
                remaining: Math.floor(Math.random() * 4) + 1,
                urgencyMessage: isUrgentBooking ? "Đặt gấp - Chỉ còn ít phòng!" : undefined
            },

            additionalServices: [
                { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
                { icon: "CoffeeOutlined", name: "Bữa sáng tuyệt hảo", included: true },
                { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true }
            ],

            recommended: true,

            dynamicPricing: {
                basePrice: pricing.singleGuestPrice,
                finalPrice: pricing.singleGuestPrice,
                adjustments: [],
                savings: 0,
                urgencyLevel: isUrgentBooking ? 'urgent' : 'low',
                recommendationScore: 90
            }
        });

        // 3 options cho 2 người
        options.push(...this.createDoubleGuestOptionsGeneric(baseConfig, context, isUrgentBooking, pricing));

        return options;
    }

    /**
     * Helper method để tạo options cho 2 người (generic)
     */
    private static createDoubleGuestOptionsGeneric(
        baseConfig: DynamicPricingConfig,
        context: BookingContext,
        isUrgentBooking: boolean,
        pricing: {
            doubleGuestBasic: number;
            doubleGuestBreakfast: number;
            doubleGuestFullboard: number;
        }
    ): RoomOption[] {
        const options: RoomOption[] = [];        // Option 1: Basic (rẻ nhất)
        options.push({
            id: `${baseConfig.roomType}_basic_2guest`,
            name: `${baseConfig.roomType.charAt(0).toUpperCase() + baseConfig.roomType.slice(1)} Standard - 2 khách`,
            pricePerNight: { vnd: pricing.doubleGuestBasic },
            maxGuests: 2,
            minGuests: 1,
            roomType: baseConfig.roomType,


            cancellationPolicy: {
                type: "non_refundable",
                penalty: 100,
                description: isUrgentBooking
                    ? "Phí hủy: Toàn bộ tiền phòng"
                    : context.nights > 1
                        ? "Phí hủy: Đêm đầu tiên"
                        : "Phí hủy: Toàn bộ tiền phòng"
            },

            paymentPolicy: {
                type: "pay_now_with_vietQR",
                description: "Thanh toán ngay - Giá tốt nhất"
            },

            availability: {
                total: 8,
                remaining: Math.floor(Math.random() * 6) + 1,
                urgencyMessage: isUrgentBooking ? "Đặt gấp!" : undefined
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
                basePrice: pricing.doubleGuestBasic * 1.15,
                finalPrice: pricing.doubleGuestBasic,
                adjustments: [{ factor: 0.87, reason: "Giá ưu đãi đặc biệt", type: 'decrease' }],
                savings: pricing.doubleGuestBasic * 0.15,
                urgencyLevel: isUrgentBooking ? 'urgent' : 'low',
                recommendationScore: 75
            }
        });        // Option 2: Với breakfast
        options.push({
            id: `${baseConfig.roomType}_breakfast_2guest`,
            name: `${baseConfig.roomType.charAt(0).toUpperCase() + baseConfig.roomType.slice(1)} Premium - 2 khách`,
            pricePerNight: { vnd: pricing.doubleGuestBreakfast },
            maxGuests: 2,
            minGuests: 1,
            roomType: baseConfig.roomType,



            cancellationPolicy: {
                type: isUrgentBooking ? "non_refundable" : "conditional",
                penalty: 100,
                description: isUrgentBooking
                    ? "Phí hủy: Toàn bộ tiền phòng"
                    : context.nights > 1
                        ? "Phí hủy: Đêm đầu tiên"
                        : "Phí hủy: Toàn bộ tiền phòng"
            },

            paymentPolicy: {
                type: "pay_now_with_vietQR",
                description: "Thanh toán ngay"
            },

            availability: {
                total: 8,
                remaining: Math.floor(Math.random() * 6) + 1
            },

            additionalServices: [
                { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
                { icon: "CoffeeOutlined", name: "Bữa sáng tuyệt hảo", included: true },
                { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true }
            ],

            mostPopular: true,

            dynamicPricing: {
                basePrice: pricing.doubleGuestBreakfast * 1.18,
                finalPrice: pricing.doubleGuestBreakfast,
                adjustments: [{ factor: 0.85, reason: "Combo bữa sáng ưu đãi", type: 'decrease' }],
                savings: pricing.doubleGuestBreakfast * 0.18,
                urgencyLevel: isUrgentBooking ? 'urgent' : 'low',
                recommendationScore: 85
            }
        });        // Option 3: Full board
        options.push({
            id: `${baseConfig.roomType}_fullboard_2guest`,
            name: `${baseConfig.roomType.charAt(0).toUpperCase() + baseConfig.roomType.slice(1)} Executive - 2 khách`,
            pricePerNight: { vnd: pricing.doubleGuestFullboard },
            maxGuests: 2,
            minGuests: 1,
            roomType: baseConfig.roomType,



            cancellationPolicy: {
                type: isUrgentBooking ? "non_refundable" : "free",
                penalty: isUrgentBooking ? 100 : 0,
                freeUntil: isUrgentBooking ? undefined : context.checkInDate.subtract(2, 'day').toISOString(),
                description: isUrgentBooking
                    ? "Phí hủy: Toàn bộ tiền phòng"
                    : context.nights > 1
                        ? "Hủy miễn phí trước 2 ngày / Phí hủy muộn: Đêm đầu tiên"
                        : "Hủy miễn phí trước 2 ngày / Phí hủy muộn: Toàn bộ tiền phòng"
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
                { icon: "CoffeeOutlined", name: "Bữa sáng tuyệt hảo", included: true },
                { icon: "UtensilsOutlined", name: "Bữa tối cao cấp", included: true },
                { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
                { icon: "UserOutlined", name: "Late check-out miễn phí", included: true }
            ],

            promotion: {
                type: "member",
                message: "Gói Full Board - Tiện lợi nhất"
            },

            dynamicPricing: {
                basePrice: pricing.doubleGuestFullboard * 1.12,
                finalPrice: pricing.doubleGuestFullboard,
                adjustments: [{ factor: 0.89, reason: "Gói combo ưu đãi", type: 'decrease' }],
                savings: pricing.doubleGuestFullboard * 0.12,
                urgencyLevel: isUrgentBooking ? 'urgent' : 'low',
                recommendationScore: 70
            }
        });

        return options;
    }
}

/**
 * EXPORT MAIN FUNCTIONS
 */
export const generateRoomOptionsWithDynamicPricing = (
    basePrice: number,
    roomType: "deluxe" | "premium" | "suite" | "presidential" | "theLevel",
    maxGuests: number,
    checkInDate: Dayjs,
    checkOutDate: Dayjs,
    bookingDate: Dayjs = dayjs(),
    guestCount: number = 2 // Thêm parameter để biết số khách thực tế
): RoomOption[] => {
    const config: DynamicPricingConfig = {
        basePrice,
        roomType,
        maxGuests,
        minGuests: 1
    };

    const context: BookingContext = {
        checkInDate,
        checkOutDate,
        bookingDate,
        nights: checkOutDate.diff(checkInDate, 'day'),
        guestCount
    }; const allOptions = RoomOptionGenerator.generateDynamicRoomOptions(config, context);

    // Thêm logic ưu tiên và cảnh báo dựa trên guest count
    return allOptions.map(option => {
        // Nếu guest count khác với room capacity, thêm cảnh báo
        if (guestCount !== option.maxGuests) {
            const warning = guestCount > option.maxGuests
                ? ` Phòng này chỉ phù hợp cho ${option.maxGuests} khách, bạn đang tìm cho ${guestCount} khách`
                : guestCount < option.maxGuests
                    ? ` Phòng này phù hợp cho ${option.maxGuests} khách, bạn đang tìm cho ${guestCount} khách`
                    : undefined;

            if (warning && option.dynamicPricing) {
                return {
                    ...option,
                    guestCountWarning: warning,
                    // Điều chỉnh recommendation score
                    dynamicPricing: {
                        ...option.dynamicPricing,
                        recommendationScore: guestCount === option.maxGuests
                            ? option.dynamicPricing.recommendationScore + 20  // Bonus cho match perfect
                            : option.dynamicPricing.recommendationScore - 10   // Penalty cho không match
                    }
                } as RoomOption;
            }
        }

        // Nếu guest count match perfect, tăng recommendation score
        if (guestCount === option.maxGuests && option.dynamicPricing) {
            return {
                ...option,
                dynamicPricing: {
                    ...option.dynamicPricing,
                    recommendationScore: option.dynamicPricing.recommendationScore + 20
                }
            } as RoomOption;
        }

        return option;
    }).sort((a, b) => {
        // Ưu tiên options match với guest count lên đầu
        const aMatch = a.maxGuests === guestCount;
        const bMatch = b.maxGuests === guestCount;

        if (aMatch && !bMatch) return -1;
        if (!aMatch && bMatch) return 1;

        // Sau đó sort theo giá
        return a.pricePerNight.vnd - b.pricePerNight.vnd;
    });
};

export const calculateDynamicPrice = DynamicPricingEngine.calculateDynamicPrice;
