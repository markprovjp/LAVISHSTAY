// src/utils/dynamicPricing.ts

import dayjs, { Dayjs } from 'dayjs';
import { RoomOption } from '../mirage/roomoption';
export type { RoomOption };
import {
    PRICING_MULTIPLIERS,
    createDeluxeOptions,
    createPremiumOptions,
    createSuiteOptions,
    createTheLevelPremiumOptions,
    createTheLevelPremiumCornerOptions,
    createTheLevelSuiteOptions,
    createPresidentialOptions
} from './pricing';


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
    roomType: "deluxe" | "premium" | "suite" | "presidential" | "theLevelPremium" | "theLevelPremiumCorner" | "theLevelSuite";
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
     * 2. Giá cuối tuần/đỉnh cao
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
export class RoomOptionGenerator {
    /**
     * Generate multiple room options theo logic nghiệp vụ mới
     */
    static generateDynamicRoomOptions(
        baseConfig: DynamicPricingConfig,
        context: BookingContext
    ): RoomOption[] {
        const daysInAdvance = context.checkInDate.diff(context.bookingDate, 'day');
        const isUrgentBooking = daysInAdvance <= 1; // Hôm nay hoặc ngày mai

        // Tính hệ số điều chỉnh giá theo ngày đặc biệt
        const priceMultiplier = this.calculatePriceMultiplier(context);

        // Tạo options theo roomType
        let options: RoomOption[] = [];

        switch (baseConfig.roomType) {
            case 'deluxe':
                options = createDeluxeOptions(baseConfig, context, isUrgentBooking, priceMultiplier);
                break;
            case 'premium':
                options = createPremiumOptions(baseConfig, context, isUrgentBooking, priceMultiplier);
                break;
            case 'suite':
                options = createSuiteOptions(baseConfig, context, isUrgentBooking, priceMultiplier);
                break;
            case 'presidential':
                options = createPresidentialOptions(baseConfig, context, isUrgentBooking, priceMultiplier);
                break;
            case 'theLevelPremium':
                options = createTheLevelPremiumOptions(baseConfig, context, isUrgentBooking, priceMultiplier);
                break;
            case 'theLevelPremiumCorner':
                options = createTheLevelPremiumCornerOptions(baseConfig, context, isUrgentBooking, priceMultiplier);
                break;
            case 'theLevelSuite':
                options = createTheLevelSuiteOptions(baseConfig, context, isUrgentBooking, priceMultiplier);
                break;
            default:
                options = createDeluxeOptions(baseConfig, context, isUrgentBooking, priceMultiplier);
        }

        return options.sort((a, b) => {
            const priceA = a.dynamicPricing?.finalPrice || a.pricePerNight.vnd;
            const priceB = b.dynamicPricing?.finalPrice || b.pricePerNight.vnd;
            return priceA - priceB;
        });
    }    /**
     * Tính hệ số điều chỉnh giá theo ngày đặc biệt
     */
    private static calculatePriceMultiplier(context: BookingContext): number {
        const checkInDate = context.checkInDate;

        // Kiểm tra Tết
        if (this.isTetPeriod(checkInDate)) {
            return PRICING_MULTIPLIERS.tet;
        }

        // Kiểm tra ngày lễ
        if (this.isHolidayPeriod(checkInDate)) {
            return PRICING_MULTIPLIERS.holiday;
        }

        // Kiểm tra cuối tuần
        if (checkInDate.day() === 0 || checkInDate.day() === 6) {
            return PRICING_MULTIPLIERS.weekend;
        }

        // Ngày bình thường
        return PRICING_MULTIPLIERS.normal;
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
            { month: 1, day: 1 },  // New Year's Day
            { month: 12, day: 25 }, // Christmas Day
            { month: 2, day: 17 },  // Lunar New Year (Tet)
            { month: 2, day: 18 },  // Tet Holiday
            { month: 2, day: 19 },  // Tet Holiday
            { month: 1, day: 20 },  // Ngày Giải phóng miền Nam
            { month: 1, day: 21 },  // Ngày Quốc tế Lao động
            { month: 3, day: 8 },  // Ngày Quốc tế Phụ nữ
            { month: 4, day: 30 },  // Ngày Giải phóng miền Nam
            { month: 5, day: 19 },  // Ngày sinh Chủ tịch Hồ Chí Minh
            { month: 6, day: 1 },   // Ngày Quốc tế Thi
            { month: 9, day: 2 },   // Ngày Quốc khánh
            { month: 10, day: 20 }, // Ngày Phụ nữ Việt
            { month: 12, day: 31 }  // Ngày cuối năm

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
 * EXPORT MAIN FUNCTIONS
 */
export const generateRoomOptionsWithDynamicPricing = (
    basePrice: number,
    roomType: "deluxe" | "premium" | "suite" | "presidential" | "theLevelPremium" | "theLevelPremiumCorner" | "theLevelSuite",
    maxGuests: number,
    checkInDate: Dayjs,
    checkOutDate: Dayjs,
    bookingDate: Dayjs = dayjs(),
    guestCount: number = 2
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
    };

    const allOptions = RoomOptionGenerator.generateDynamicRoomOptions(config, context);

    // Áp dụng các quy tắc năng lực và cảnh báo số lượng khách
    return allOptions.map(option => {
        const isPresidentialRoom = option.roomType === 'presidential';
        const actualMaxGuests = isPresidentialRoom ? 4 : 2;

        const updatedOption = {
            ...option,
            maxGuests: actualMaxGuests
        };

        if (guestCount !== actualMaxGuests) {
            let warning = '';
            let warningDetail = null;

            if (guestCount > actualMaxGuests) {
                const remainingGuests = guestCount - actualMaxGuests;
                const additionalRoomsNeeded = Math.ceil(remainingGuests / 2);

                warning = `Phòng này phù hợp cho ${actualMaxGuests} khách. Bạn có thể đặt phòng này và cần thêm chỗ ở cho ${remainingGuests} khách.`;

                let suggestedAction = '';
                if (isPresidentialRoom) {
                    suggestedAction = `Đặt thêm ${additionalRoomsNeeded} phòng nữa cho ${remainingGuests} khách còn lại`;
                } else {
                    if (guestCount <= 4) {
                        suggestedAction = `Đặt thêm ${additionalRoomsNeeded} phòng nữa hoặc chọn phòng Presidential Suite (4 khách)`;
                    } else {
                        suggestedAction = `Đặt thêm ${additionalRoomsNeeded} phòng nữa cho ${remainingGuests} khách còn lại`;
                    }
                }

                warningDetail = {
                    type: "exceeds_capacity" as const,
                    message: `Cần thêm chỗ ở cho ${remainingGuests} khách`,
                    suggestedAction: suggestedAction
                };
            } else {
                warning = `Phòng này phù hợp cho ${actualMaxGuests} khách, bạn đang tìm cho ${guestCount} khách`;

                warningDetail = {
                    type: "below_minimum" as const,
                    message: `Phòng có thể chứa thêm ${actualMaxGuests - guestCount} khách`,
                    suggestedAction: "Phòng này vẫn phù hợp cho nhu cầu của bạn"
                };
            }

            if (warning && updatedOption.dynamicPricing) {
                return {
                    ...updatedOption,
                    guestCountWarning: warning,
                    guestCountWarningDetail: warningDetail,
                    dynamicPricing: {
                        ...updatedOption.dynamicPricing,
                        recommendationScore: guestCount === actualMaxGuests
                            ? updatedOption.dynamicPricing.recommendationScore + 20
                            : guestCount > actualMaxGuests
                                ? updatedOption.dynamicPricing.recommendationScore - 15
                                : updatedOption.dynamicPricing.recommendationScore - 5
                    }
                } as RoomOption;
            }
        }

        if (guestCount === actualMaxGuests && updatedOption.dynamicPricing) {
            return {
                ...updatedOption,
                dynamicPricing: {
                    ...updatedOption.dynamicPricing,
                    recommendationScore: updatedOption.dynamicPricing.recommendationScore + 20
                }
            } as RoomOption;
        }

        return updatedOption;
    }).sort((a, b) => {
        const aIsPresidential = a.roomType === 'presidential';
        const bIsPresidential = b.roomType === 'presidential';
        const aActualMaxGuests = aIsPresidential ? 4 : 2;
        const bActualMaxGuests = bIsPresidential ? 4 : 2;

        const aMatchExact = aActualMaxGuests === guestCount;
        const bMatchExact = bActualMaxGuests === guestCount;
        const aCanAccommodate = aActualMaxGuests >= guestCount;
        const bCanAccommodate = bActualMaxGuests >= guestCount;

        if (aMatchExact && !bMatchExact) return -1;
        if (!aMatchExact && bMatchExact) return 1;

        if (aCanAccommodate && !bCanAccommodate) return -1;
        if (!aCanAccommodate && bCanAccommodate) return 1; if (!aCanAccommodate && !bCanAccommodate) {
            if (aIsPresidential && !bIsPresidential) return -1;
            if (!aIsPresidential && bIsPresidential) return 1;
        }

        return a.pricePerNight.vnd - b.pricePerNight.vnd;
    });
};

export const calculateDynamicPrice = DynamicPricingEngine.calculateDynamicPrice;
