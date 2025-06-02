export interface RoomOption {
    id: string;
    name: string;
    pricePerNight: {
        vnd: number; // Giá mỗi đêm
    };
    maxGuests: number; // Số khách tối đa cho option này
    minGuests: number; // Số khách tối thiểu cho option này
    roomType: "deluxe" | "premium" | "suite" | "presidential" | "theLevel";

    // Các lựa chọn dịch vụ
    mealOptions: {
        breakfast?: {
            included: boolean; // Có bao gồm bữa sáng không
            price?: number; // VND nếu không bao gồm
            description: string;
        };
        dinner?: {
            included: boolean;
            price?: number;
            description: string;
        };
    };

    // Chính sách hủy
    cancellationPolicy: {
        type: "free" | "non_refundable" | "conditional";
        freeUntil?: string; // Ngày có thể hủy miễn phí (ISO string) 
        penalty?: number; // Phí hủy (VND hoặc %)  (phí huỷ toàn bộ tiền phòng thì dịch vụ option sẽ rẻ hơn so với huỷ miễn phí)
        description: string;
    };

    // Chính sách thanh toán
    paymentPolicy: {
        type: "pay_now_with_vietQR" | "pay_at_hotel";
        description: string;
        prepaymentRequired?: boolean;
    };

    // Số lượng phòng
    availability: {
        total: number; // Tổng số phòng loại này
        remaining: number; // Số phòng còn lại
        urgencyMessage?: string; // "Chỉ còn 2 phòng!" để tạo áp lực
    };

    // Các dịch vụ bổ sung
    additionalServices?: {
        icon: string;
        name: string;
        price?: string;
        included: boolean;
    }[];

    // Khuyến mãi
    promotion?: {
        type: "hot" | "limited" | "member" | "lowest" | "deal";
        message: string;
        discount?: number; // % giảm giá
    }; recommended?: boolean; // Được đề xuất

    mostPopular?: boolean; // Được ưa chuộng nhất

    // Dynamic pricing information
    dynamicPricing?: {
        basePrice: number;
        finalPrice: number;
        adjustments: Array<{
            factor: number;
            reason: string;
            type: 'increase' | 'decrease';
        }>;
        savings: number;
        urgencyLevel: 'low' | 'medium' | 'high' | 'urgent';
        recommendationScore: number;
    };
}
}

// Lưu ý: Các options hiện tại đã được tích hợp vào Room models
// Interface RoomOption được định nghĩa ở trên và sẽ được sử dụng trong models.ts