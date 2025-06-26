import { BookingContext } from '../dynamicPricing';
import dayjs from 'dayjs';

/**
 * Helper function để tính toán chính sách hủy phòng theo logic mới:
 * - Nếu đặt phòng cho ngày hôm nay hoặc ngày mai: Hủy phòng mất toàn bộ tiền đặt
 * - Nếu đặt phòng dài ngày (nhiều đêm): Phí hủy chỉ là tiền đêm đầu tiên
 * - Nếu đặt phòng 1 đêm: Hủy miễn phí trước 1 ngày
 */
export const calculateCancellationPolicy = (context: BookingContext, pricePerNight: number) => {
    const today = dayjs().startOf('day');
    const tomorrow = today.add(1, 'day');
    const checkInDate = context.checkInDate.startOf('day');

    // Nếu đặt phòng cho hôm nay hoặc ngày mai
    const isUrgentBooking = checkInDate.isSame(today) || checkInDate.isSame(tomorrow);

    if (isUrgentBooking) {
        // Tính tổng tiền đặt (tất cả các đêm)
        const totalBookingAmount = Math.round(pricePerNight * context.nights);
        return {
            type: "non_refundable" as const,
            penalty: 100,
            description: `Hủy phòng mất toàn bộ tiền đặt (${totalBookingAmount.toLocaleString('vi-VN')}đ cho ${context.nights} đêm)`
        };
    }

    // Nếu ở nhiều đêm (>= 2 đêm), phí hủy là tiền đêm đầu
    if (context.nights >= 2) {
        const firstNightFee = Math.round(pricePerNight);
        return {
            type: "conditional" as const,
            penalty: Math.round((firstNightFee / (pricePerNight * context.nights)) * 100), // Tính % phí hủy
            freeUntil: context.checkInDate.subtract(2, 'day').toISOString(),
            description: `Phí hủy: tiền đêm đầu (${firstNightFee.toLocaleString('vi-VN')}đ) - Hủy miễn phí trước 2 ngày`
        };
    }

    // Ở 1 đêm thôi, hủy miễn phí trước 1 ngày
    return {
        type: "free" as const,
        penalty: 0,
        freeUntil: context.checkInDate.subtract(1, 'day').toISOString(),
        description: "Hủy miễn phí trước 1 ngày"
    };
};
