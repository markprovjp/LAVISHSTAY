// roomOptionGenerator.ts - Logic tạo room options dựa trên thời gian đặt phòng

import { RoomOption } from './roomoption';

// Tính số ngày giữa 2 thời điểm
export const calculateDaysDifference = (checkInDate: string, currentDate: Date = new Date()): number => {
    const checkIn = new Date(checkInDate);
    const today = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate());
    const checkInDay = new Date(checkIn.getFullYear(), checkIn.getMonth(), checkIn.getDate());

    const diffTime = checkInDay.getTime() - today.getTime();
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    return diffDays;
};

// Generate room options dựa trên logic nghiệp vụ
export const generateRoomOptions = (
    roomType: "deluxe" | "premium" | "suite" | "presidential" | "theLevel",
    checkInDate: string,
    basePrice: number
): RoomOption[] => {
    const daysDifference = calculateDaysDifference(checkInDate);
    const isAdvanceBooking = daysDifference >= 2; // Đặt trước 2 ngày

    // Ngày hủy miễn phí (2 ngày trước check-in)
    const freeUntilDate = new Date(checkInDate);
    freeUntilDate.setDate(freeUntilDate.getDate() - 2);
    const freeUntilString = freeUntilDate.toISOString();

    const options: RoomOption[] = [];

    if (isAdvanceBooking) {
        // Đặt trước >= 2 ngày: Có nhiều option linh hoạt

        // Option 1: Hủy miễn phí (giá cao hơn)
        options.push({
            id: `${roomType}_free_cancel`,
            name: "Linh hoạt - Hủy miễn phí",
            pricePerNight: { vnd: Math.round(basePrice * 1.15) }, // Tăng 15%
            maxGuests: roomType === "deluxe" ? 2 : roomType === "premium" ? 3 : 4,
            minGuests: 1,
            roomType,
            mealOptions: {
                breakfast: {
                    included: false,
                    price: 260000,
                    description: "Bữa sáng Tuyệt hảo - VND 260.000"
                }
            },
            cancellationPolicy: {
                type: "free",
                freeUntil: freeUntilString,
                description: `Hủy miễn phí trước ${freeUntilDate.toLocaleDateString('vi-VN')}`
            },
            paymentPolicy: {
                type: "pay_at_hotel",
                description: "Không cần thanh toán trước - thanh toán tại chỗ nghỉ"
            },
            availability: {
                total: 10,
                remaining: Math.floor(Math.random() * 8) + 2 // Số phòng còn lại từ 2 đến 9
            },
            additionalServices: [
                { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
                { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true }
            ]
        });

        // Option 2: Không hoàn tiền (giá rẻ hơn)
        options.push({
            id: `${roomType}_non_refund`,
            name: "Tiết kiệm - Không hoàn tiền",
            pricePerNight: { vnd: Math.round(basePrice * 0.85) }, // Giảm 15%
            maxGuests: roomType === "deluxe" ? 2 : roomType === "premium" ? 3 : 4,
            minGuests: 1,
            roomType,
            mealOptions: {
                breakfast: {
                    included: false,
                    price: 260000,
                    description: "Bữa sáng Tuyệt hảo - VND 260.000"
                }
            },
            cancellationPolicy: {
                type: "non_refundable",
                penalty: 100,
                description: "Phí hủy: Toàn bộ tiền phòng"
            },
            paymentPolicy: {
                type: "pay_at_hotel",
                description: "Không cần thanh toán trước - thanh toán tại chỗ nghỉ"
            },
            availability: {
                total: 15,
                remaining: Math.floor(Math.random() * 12) + 3
            },
            additionalServices: [
                { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
                { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true }
            ],
            mostPopular: true // Đánh dấu là phổ biến nhất
        });

        // Option 3: Bao gồm bữa sáng + hủy miễn phí
        options.push({
            id: `${roomType}_breakfast_included`,
            name: "Bao gồm bữa sáng - Hủy miễn phí",
            pricePerNight: { vnd: Math.round(basePrice * 1.3) }, // Tăng 30%
            maxGuests: roomType === "deluxe" ? 2 : roomType === "premium" ? 3 : 4,
            minGuests: 1,
            roomType,
            mealOptions: {
                breakfast: {
                    included: true,
                    price: 0,
                    description: "Bữa sáng buffet miễn phí"
                }
            },
            cancellationPolicy: {
                type: "free",
                freeUntil: freeUntilString,
                description: `Hủy miễn phí trước ${freeUntilDate.toLocaleDateString('vi-VN')}`
            },
            paymentPolicy: {
                type: "pay_at_hotel",
                description: "Không cần thanh toán trước - thanh toán tại chỗ nghỉ"
            },
            availability: {
                total: 8,
                remaining: Math.floor(Math.random() * 6) + 1
            },
            additionalServices: [
                { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
                { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true },
                { icon: "GiftOutlined", name: "Bữa sáng buffet", included: true }
            ]
        });
    } else {
        // Đặt phòng cùng ngày hoặc ngày mai: CHỈ có các option không hoàn tiền với pricing tiers khác nhau

        // Option 1: Giá rẻ nhất cho 2 guests (1.2M base)
        options.push({
            id: `${roomType}_urgent_basic_2guests`,
            name: "Đặt gấp - Tiết kiệm (2 khách)",
            pricePerNight: { vnd: 1200000 },
            maxGuests: 2,
            minGuests: 1,
            roomType,
            mealOptions: {
                breakfast: {
                    included: false,
                    price: 260000,
                    description: "Bữa sáng Tuyệt hảo - VND 260.000"
                },
                dinner: {
                    included: false,
                    price: 1300000, // Để tổng cộng = 2.5M khi có breakfast + dinner
                    description: "Bữa tối + Bữa sáng - VND 1.300.000"
                }
            },
            cancellationPolicy: {
                type: "non_refundable",
                penalty: 100,
                description: "Không hoàn tiền - Phí hủy: Toàn bộ tiền phòng"
            },
            paymentPolicy: {
                type: "pay_at_hotel",
                description: "Thanh toán tại chỗ nghỉ"
            },
            availability: {
                total: 5,
                remaining: Math.floor(Math.random() * 3) + 1,
                urgencyMessage: daysDifference === 0 ? "Chỉ còn vài phòng cho hôm nay!" : "Phòng cho ngày mai sắp hết!"
            },
            additionalServices: [
                { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
                { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true }
            ],
            mostPopular: true
        });

        // Option 2: Cho 1 guest với breakfast included (1.5M)
        options.push({
            id: `${roomType}_urgent_1guest_breakfast`,
            name: "Đặt gấp - 1 khách có sáng",
            pricePerNight: { vnd: 1500000 },
            maxGuests: 1,
            minGuests: 1,
            roomType,
            mealOptions: {
                breakfast: {
                    included: true,
                    price: 0,
                    description: "Bữa sáng buffet miễn phí"
                }
            },
            cancellationPolicy: {
                type: "non_refundable",
                penalty: 100,
                description: "Không hoàn tiền - Phí hủy: Toàn bộ tiền phòng"
            },
            paymentPolicy: {
                type: "pay_at_hotel",
                description: "Thanh toán tại chỗ nghỉ"
            },
            availability: {
                total: 3,
                remaining: Math.floor(Math.random() * 2) + 1,
                urgencyMessage: daysDifference === 0 ? "Ưu đãi đặc biệt cho 1 khách!" : "Gói 1 khách sắp hết!"
            },
            additionalServices: [
                { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
                { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true },
                { icon: "GiftOutlined", name: "Bữa sáng buffet", included: true }
            ]
        });

        // Option 3: Premium package với breakfast + dinner (2.5M total)
        options.push({
            id: `${roomType}_urgent_premium_meals`,
            name: "Đặt gấp - Gói ăn đầy đủ",
            pricePerNight: { vnd: 2500000 },
            maxGuests: roomType === "deluxe" ? 2 : roomType === "premium" ? 3 : 4,
            minGuests: 1,
            roomType,
            mealOptions: {
                breakfast: {
                    included: true,
                    price: 0,
                    description: "Bữa sáng buffet miễn phí"
                },
                dinner: {
                    included: true,
                    price: 0,
                    description: "Bữa tối + Bữa sáng - Đã bao gồm"
                }
            },
            cancellationPolicy: {
                type: "non_refundable",
                penalty: 100,
                description: "Không hoàn tiền - Phí hủy: Toàn bộ tiền phòng"
            },
            paymentPolicy: {
                type: "pay_at_hotel",
                description: "Thanh toán tại chỗ nghỉ"
            },
            availability: {
                total: 4,
                remaining: Math.floor(Math.random() * 2) + 1,
                urgencyMessage: daysDifference === 0 ? "Gói cao cấp cuối cùng!" : "Gói đầy đủ bữa ăn sắp hết!"
            },
            additionalServices: [
                { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
                { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true },
                { icon: "GiftOutlined", name: "Bữa sáng buffet", included: true },
                { icon: "RestaurantOutlined", name: "Bữa tối cao cấp", included: true }
            ]
        });
    }

    return options;
};

// Helper function để sắp xếp options theo số lượng khách
export const prioritizeOptionsByGuestCount = (
    options: RoomOption[],
    guestCount: number
): RoomOption[] => {
    return [...options].sort((a, b) => {
        // Ưu tiên options phù hợp với số khách
        const aMatches = guestCount >= a.minGuests && guestCount <= a.maxGuests;
        const bMatches = guestCount >= b.minGuests && guestCount <= b.maxGuests;

        if (aMatches && !bMatches) return -1;
        if (!aMatches && bMatches) return 1;

        // Nếu cả hai đều phù hợp hoặc không phù hợp, sắp xếp theo giá
        return a.pricePerNight.vnd - b.pricePerNight.vnd;
    });
};

// Helper function để thêm warnings cho guest count
export const addGuestCountWarning = (
    option: RoomOption,
    guestCount: number
): RoomOption => {
    const isExceedsCapacity = guestCount > option.maxGuests;
    const isBelowMinimum = guestCount < option.minGuests;

    if (isExceedsCapacity || isBelowMinimum) {
        return {
            ...option,
            guestCountWarning: {
                type: isExceedsCapacity ? "exceeds_capacity" : "below_minimum",
                message: isExceedsCapacity
                    ? `Phòng này chỉ phù hợp cho tối đa ${option.maxGuests} khách`
                    : `Phòng này yêu cầu tối thiểu ${option.minGuests} khách`,
                suggestedAction: isExceedsCapacity
                    ? "Vui lòng chọn phòng khác hoặc giảm số khách"
                    : "Vui lòng chọn phòng khác hoặc tăng số khách"
            }
        };
    }

    return option;
};

// Main function để generate và prioritize options
export const generatePrioritizedRoomOptions = (
    roomType: "deluxe" | "premium" | "suite" | "presidential" | "theLevel",
    checkInDate: string,
    basePrice: number,
    guestCount: number = 2
): RoomOption[] => {
    // Generate basic options
    const options = generateRoomOptions(roomType, checkInDate, basePrice);

    // Add guest count warnings
    const optionsWithWarnings = options.map(option =>
        addGuestCountWarning(option, guestCount)
    );

    // Prioritize by guest count
    const prioritizedOptions = prioritizeOptionsByGuestCount(optionsWithWarnings, guestCount);

    return prioritizedOptions;
};

// Helper function để lấy thông báo khẩn cấp dựa trên thời gian
export const getUrgencyMessage = (checkInDate: string): string | undefined => {
    const daysDifference = calculateDaysDifference(checkInDate);

    if (daysDifference === 0) {
        return "Chỉ còn vài phòng cho hôm nay! Hãy nhanh tay đặt!";
    } else if (daysDifference === 1) {
        return "Phòng cho ngày mai sắp hết! Đặt ngay!";
    } else if (daysDifference <= 3) {
        return "Phòng đang có nhiều người quan tâm!";
    }

    return undefined;
};
