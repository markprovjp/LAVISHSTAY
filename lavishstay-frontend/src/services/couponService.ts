// src/services/couponService.ts
import api from '../utils/api';

export interface CouponCheckResponse {
    exists: boolean;
    coupon?: {
        code: string;
        type: 'percent' | 'fixed';
        value: number;
        description: string;
    };
    message: string;
}

export interface CouponValidateRequest {
    code: string;
    booking_preview: {
        base_price_vnd: number;
        taxes_vnd?: number;
        fees_vnd?: number;
        room_type_id: number;
        nights?: number;
        total_price_vnd?: number;
        user_id?: number;
    };
}

export interface CouponValidateResponse {
    valid: boolean;
    coupon?: {
        code: string;
        type: 'percent' | 'fixed';
        value: number;
        description: string;
        min_booking_amount_vnd: number;
    };
    discount_vnd?: number;
    new_total_vnd?: number;
    reason?: string;
    message: string;
}

export interface AppliedCoupon {
    code: string;
    type: 'percent' | 'fixed';
    value: number;
    description: string;
    discount_vnd: number;
    new_total_vnd: number;
}

export const couponService = {
    // Quick check if coupon exists
    async checkCode(code: string): Promise<CouponCheckResponse> {
        const response = await api.post('/coupons/check-code', { code });
        return response.data;
    },

    // Validate coupon against booking preview
    async validateCoupon(request: CouponValidateRequest): Promise<CouponValidateResponse> {
        const response = await api.post('/coupons/validate', request);
        return response.data;
    },

    // Get user redemption history (requires auth)
    async getUserRedemptions() {
        const response = await api.get('/coupons/my-redemptions');
        return response.data;
    }
};

// Error message mapping
export const couponErrorMessages: Record<string, string> = {
    not_found: 'Mã giảm giá không tồn tại',
    expired: 'Mã giảm giá đã hết hạn',
    not_started: 'Mã giảm giá chưa có hiệu lực',
    inactive: 'Mã giảm giá không hoạt động',
    usage_limit: 'Mã giảm giá đã hết lượt sử dụng',
    user_limit: 'Bạn đã sử dụng hết lượt cho mã này',
    min_amount: 'Giá trị booking chưa đạt yêu cầu tối thiểu',
    room_type: 'Mã không áp dụng cho loại phòng này',
    already_applied: 'Mã đã được áp dụng cho booking này',
    network_error: 'Lỗi kết nối mạng. Vui lòng thử lại',
    rate_limit: 'Quá nhiều yêu cầu. Vui lòng đợi một chút',
    server_error: 'Lỗi server. Vui lòng thử lại sau'
};

export const getCouponErrorMessage = (reason?: string): string => {
    if (!reason) return 'Có lỗi xảy ra. Vui lòng thử lại';
    return couponErrorMessages[reason] || `Lỗi: ${reason}`;
};
