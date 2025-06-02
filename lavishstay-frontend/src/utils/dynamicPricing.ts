// src/utils/dynamicPricing.ts

import dayjs, { Dayjs } from 'dayjs';
import { RoomOption } from '../mirage/roomoption';

/**
 * DYNAMIC PRICING ENGINE cho LavishStay Hotel Booking System
 * 
 * Logic phức tạp:
 * 1. Dynamic pricing dựa trên thời điểm đặt (booking timing)
 * 2. Weekend/Peak pricing (giá cuối tuần/cao điểm)
 * 3. Early payment discounts (giảm giá thanh toán sớm)
 * 4. Seasonal pricing adjustments
 * 5. Cancellation policy flexibility based on booking patterns
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

        // 2. WEEKEND/PEAK PRICING
        const weekendAdjustment = this.calculateWeekendPeakAdjustment(context);
        currentPrice *= weekendAdjustment.factor;
        adjustments.push(weekendAdjustment);

        // 3. SEASONAL PRICING
        const seasonalAdjustment = this.calculateSeasonalAdjustment(context);
        currentPrice *= seasonalAdjustment.factor;
        adjustments.push(seasonalAdjustment);

        // 4. ROOM TYPE PREMIUM
        const roomTypeAdjustment = this.calculateRoomTypePremium(config.roomType);
        currentPrice *= roomTypeAdjustment.factor;
        adjustments.push(roomTypeAdjustment);

        // 5. LENGTH OF STAY DISCOUNT
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
     * 1. BOOKING TIMING ADJUSTMENT
     * - Same day/next day booking: cheaper but non-refundable
     * - 2-7 days advance: normal pricing
     * - 7+ days advance: slight premium but flexible cancellation
     */
    private static calculateBookingTimingAdjustment(context: BookingContext): PricingAdjustment {
        const daysInAdvance = context.checkInDate.diff(context.bookingDate, 'day');

        if (daysInAdvance <= 1) {
            // Same day/next day = 10% discount but non-refundable
            return {
                factor: 0.9,
                reason: 'Đặt phòng cùng ngày/ngày mai - Giá đặc biệt (không hoàn tiền)',
                type: 'decrease'
            };
        } else if (daysInAdvance <= 3) {
            // Last minute booking = 5% discount 
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
            // Early booking = small premium for flexibility
            return {
                factor: 1.05,
                reason: 'Đặt trước sớm - Hủy linh hoạt (+5%)',
                type: 'increase'
            };
        } else {
            // Very early booking = premium but maximum flexibility
            return {
                factor: 1.1,
                reason: 'Đặt trước rất sớm - Chính sách hủy tốt nhất (+10%)',
                type: 'increase'
            };
        }
    }

    /**
     * 2. WEEKEND/PEAK PRICING
     * - Weekdays (Mon-Thu): base price
     * - Friday/Sunday: +15% 
     * - Saturday: +25%
     * - Holiday periods: +30-50%
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
     * Calculate urgency level based on booking patterns
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
     * Helper: Check if date is in holiday period
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
 * ROOM OPTION GENERATOR với Dynamic Pricing
 */
export class RoomOptionGenerator {

    /**
     * Generate multiple room options với different pricing strategies
     */
    static generateDynamicRoomOptions(
        baseConfig: DynamicPricingConfig,
        context: BookingContext
    ): RoomOption[] {
        const options: RoomOption[] = [];

        // 1. BASIC OPTION - Pay at hotel, free cancellation (higher price)
        const basicPricing = DynamicPricingEngine.calculateDynamicPrice(
            { ...baseConfig, basePrice: baseConfig.basePrice * 1.1 },
            context
        );

        options.push(this.createBasicOption(baseConfig, basicPricing, context));

        // 2. ADVANCE PAYMENT OPTION - Pay now, lower price, limited cancellation
        const advancePricing = DynamicPricingEngine.calculateDynamicPrice(
            { ...baseConfig, basePrice: baseConfig.basePrice * 0.95 },
            context
        );

        options.push(this.createAdvancePaymentOption(baseConfig, advancePricing, context));

        // 3. NON-REFUNDABLE OPTION - Lowest price, no cancellation
        const nonRefundablePricing = DynamicPricingEngine.calculateDynamicPrice(
            { ...baseConfig, basePrice: baseConfig.basePrice * 0.85 },
            context
        );

        options.push(this.createNonRefundableOption(baseConfig, nonRefundablePricing, context));

        // 4. PREMIUM FLEXIBLE OPTION - Highest price, maximum flexibility
        if (context.checkInDate.diff(context.bookingDate, 'day') > 7) {
            const flexiblePricing = DynamicPricingEngine.calculateDynamicPrice(
                { ...baseConfig, basePrice: baseConfig.basePrice * 1.2 },
                context
            );

            options.push(this.createFlexibleOption(baseConfig, flexiblePricing, context));
        }

        return options.sort((a, b) => a.pricePerNight.vnd - b.pricePerNight.vnd);
    }

    /**
     * Create Basic Option
     */
    private static createBasicOption(
        config: DynamicPricingConfig,
        pricing: DynamicPricingResult,
        context: BookingContext
    ): RoomOption {
        const daysInAdvance = context.checkInDate.diff(context.bookingDate, 'day');
        const freeUntilDate = context.checkInDate.subtract(2, 'day').toISOString();

        return {
            id: `${config.roomType}_basic_${config.maxGuests}guest`,
            name: `Tiêu chuẩn - ${config.maxGuests} khách`,
            pricePerNight: { vnd: pricing.finalPrice },
            maxGuests: config.maxGuests,
            minGuests: config.minGuests,
            roomType: config.roomType,

            mealOptions: {
                breakfast: {
                    included: false,
                    price: 260000,
                    description: "Bữa sáng Tuyệt hảo - VND 260.000"
                }
            },

            cancellationPolicy: {
                type: "free",
                freeUntil: freeUntilDate,
                description: `Hủy miễn phí trước ${dayjs(freeUntilDate).format('DD/MM/YYYY')}`
            },

            paymentPolicy: {
                type: "pay_at_hotel",
                description: "Thanh toán tại khách sạn"
            },

            availability: {
                total: 10,
                remaining: Math.floor(Math.random() * 8) + 1,
                urgencyMessage: pricing.urgencyLevel === 'urgent' ? "Chỉ còn ít phòng!" : undefined
            },
            additionalServices: [
                { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
                { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true }
            ],

            recommended: pricing.recommendationScore > 75,

            // Add dynamic pricing information
            dynamicPricing: {
                basePrice: pricing.basePrice,
                finalPrice: pricing.finalPrice,
                adjustments: pricing.adjustments,
                savings: pricing.savings,
                urgencyLevel: pricing.urgencyLevel,
                recommendationScore: pricing.recommendationScore
            }
        };
    }

    /**
     * Create Advance Payment Option với Early Payment Discount
     */
    private static createAdvancePaymentOption(
        config: DynamicPricingConfig,
        pricing: DynamicPricingResult,
        context: BookingContext
    ): RoomOption {
        const daysInAdvance = context.checkInDate.diff(context.bookingDate, 'day');
        let earlyPaymentDiscount = 0;

        // Early payment discount logic
        if (daysInAdvance <= 1) {
            earlyPaymentDiscount = 0.05; // 5% same day booking
        } else if (daysInAdvance <= 3) {
            earlyPaymentDiscount = 0.03; // 3% next day booking
        }

        const finalPrice = Math.round(pricing.finalPrice * (1 - earlyPaymentDiscount));

        return {
            id: `${config.roomType}_advance_${config.maxGuests}guest`,
            name: `Thanh toán trước - ${config.maxGuests} khách`,
            pricePerNight: { vnd: finalPrice },
            maxGuests: config.maxGuests,
            minGuests: config.minGuests,
            roomType: config.roomType,

            mealOptions: {
                breakfast: {
                    included: true,
                    description: "Bao gồm bữa sáng tuyệt hảo"
                }
            },

            cancellationPolicy: {
                type: daysInAdvance <= 1 ? "non_refundable" : "conditional",
                penalty: daysInAdvance <= 1 ? 100 : 50,
                description: daysInAdvance <= 1
                    ? "Không hoàn tiền - Giá đặc biệt"
                    : "Hủy trong 24h trước check-in: phí 50%"
            },

            paymentPolicy: {
                type: "pay_now_with_vietQR",
                description: "Thanh toán ngay với VietQR"
            },

            availability: {
                total: 8,
                remaining: Math.floor(Math.random() * 6) + 1
            },

            additionalServices: [
                { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
                { icon: "CoffeeOutlined", name: "Bữa sáng", included: true },
                { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true }
            ],
            promotion: earlyPaymentDiscount > 0 ? {
                type: "deal",
                message: `Thanh toán sớm - Tiết kiệm ${Math.round(earlyPaymentDiscount * 100)}%`,
                discount: Math.round(earlyPaymentDiscount * 100)
            } : undefined,

            mostPopular: true,

            // Add dynamic pricing information
            dynamicPricing: {
                basePrice: pricing.basePrice,
                finalPrice: finalPrice,
                adjustments: [
                    ...pricing.adjustments,
                    ...(earlyPaymentDiscount > 0 ? [{
                        factor: 1 - earlyPaymentDiscount,
                        reason: `Thanh toán sớm (-${Math.round(earlyPaymentDiscount * 100)}%)`,
                        type: 'decrease' as const
                    }] : [])
                ],
                savings: pricing.savings + (pricing.finalPrice - finalPrice),
                urgencyLevel: pricing.urgencyLevel,
                recommendationScore: pricing.recommendationScore + (earlyPaymentDiscount > 0 ? 10 : 0)
            }
        };
    }

    /**
     * Create Non-Refundable Option - Lowest price, no flexibility
     */
    private static createNonRefundableOption(
        config: DynamicPricingConfig,
        pricing: DynamicPricingResult,
        context: BookingContext
    ): RoomOption {
        return {
            id: `${config.roomType}_nonrefund_${config.maxGuests}guest`,
            name: `Giá tốt nhất - ${config.maxGuests} khách`,
            pricePerNight: { vnd: pricing.finalPrice },
            maxGuests: config.maxGuests,
            minGuests: config.minGuests,
            roomType: config.roomType,

            mealOptions: {
                breakfast: {
                    included: true,
                    description: "Bao gồm bữa sáng"
                }
            },

            cancellationPolicy: {
                type: "non_refundable",
                penalty: 100,
                description: "Không hoàn tiền - Giá thấp nhất"
            },

            paymentPolicy: {
                type: "pay_now_with_vietQR",
                description: "Thanh toán ngay - Giá ưu đãi"
            },

            availability: {
                total: 5,
                remaining: Math.floor(Math.random() * 4) + 1,
                urgencyMessage: "Ưu đãi có hạn!"
            },

            additionalServices: [
                { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
                { icon: "CoffeeOutlined", name: "Bữa sáng", included: true }
            ],
            promotion: {
                type: "hot",
                message: "Giá tốt nhất! Không hoàn tiền",
                discount: Math.round(((config.basePrice - pricing.finalPrice) / config.basePrice) * 100)
            },

            // Add dynamic pricing information
            dynamicPricing: {
                basePrice: pricing.basePrice,
                finalPrice: pricing.finalPrice,
                adjustments: pricing.adjustments,
                savings: pricing.savings,
                urgencyLevel: pricing.urgencyLevel,
                recommendationScore: pricing.recommendationScore
            }
        };
    }

    /**
     * Create Flexible Option - Maximum flexibility, premium price
     */
    private static createFlexibleOption(
        config: DynamicPricingConfig,
        pricing: DynamicPricingResult,
        context: BookingContext
    ): RoomOption {
        const freeUntilDate = context.checkInDate.subtract(1, 'day').toISOString();

        return {
            id: `${config.roomType}_flexible_${config.maxGuests}guest`,
            name: `Linh hoạt - ${config.maxGuests} khách`,
            pricePerNight: { vnd: pricing.finalPrice },
            maxGuests: config.maxGuests,
            minGuests: config.minGuests,
            roomType: config.roomType,

            mealOptions: {
                breakfast: {
                    included: true,
                    description: "Bữa sáng cao cấp"
                },
                dinner: {
                    included: false,
                    price: 450000,
                    description: "Bữa tối cao cấp - VND 450.000"
                }
            },

            cancellationPolicy: {
                type: "free",
                freeUntil: freeUntilDate,
                description: `Hủy miễn phí đến 18:00 ngày ${dayjs(freeUntilDate).format('DD/MM/YYYY')}`
            },

            paymentPolicy: {
                type: "pay_at_hotel",
                description: "Thanh toán tại khách sạn - Linh hoạt tối đa"
            },

            availability: {
                total: 12,
                remaining: Math.floor(Math.random() * 8) + 2
            },

            additionalServices: [
                { icon: "WifiOutlined", name: "Wi-Fi cao cấp", included: true },
                { icon: "CoffeeOutlined", name: "Bữa sáng cao cấp", included: true },
                { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
                { icon: "UserOutlined", name: "Late check-out miễn phí", included: true }
            ],
            promotion: {
                type: "member",
                message: "Linh hoạt tối đa - Thành viên ưu tiên"
            },

            // Add dynamic pricing information
            dynamicPricing: {
                basePrice: pricing.basePrice,
                finalPrice: pricing.finalPrice,
                adjustments: pricing.adjustments,
                savings: pricing.savings,
                urgencyLevel: pricing.urgencyLevel,
                recommendationScore: pricing.recommendationScore + 5 // Bonus for flexibility
            }
        };
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
    bookingDate: Dayjs = dayjs()
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
        guestCount: maxGuests
    };

    return RoomOptionGenerator.generateDynamicRoomOptions(config, context);
};

export const calculateDynamicPrice = DynamicPricingEngine.calculateDynamicPrice;
