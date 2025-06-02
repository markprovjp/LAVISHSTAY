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

    // Ngày hủy miễn phí (1 ngày trước check-in)
    const freeUntilDate = new Date(checkInDate);
    freeUntilDate.setDate(freeUntilDate.getDate() - 1);
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
        // Đặt phòng cùng ngày hoặc ngày mai: Chỉ có option không hoàn tiền

        // Option duy nhất: Không hoàn tiền
        options.push({
            id: `${roomType}_last_minute`,
            name: "Đặt phòng gấp - Không hoàn tiền",
            pricePerNight: { vnd: basePrice },
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
    }

    return options;
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
