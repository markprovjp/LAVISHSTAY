export interface RoomOption {
    id: string;
    name: string;
    pricePerNight: {
        vnd: number;
    };
    maxGuests: number;
    minGuests: number;
    roomType: "deluxe" | "premium" | "suite" | "presidential" | "theLevel";

 
    cancellationPolicy: {
        type: "free" | "non_refundable" | "conditional";
        freeUntil?: string;
        penalty?: number;
        description: string;
    };

    paymentPolicy: {
        type: "pay_now_with_vietQR" | "pay_at_hotel";
        description: string;
        prepaymentRequired?: boolean;
    };

    availability: {
        total: number;
        remaining: number;
        urgencyMessage?: string;
    };

    additionalServices?: {
        icon: string;
        name: string;
        price?: string;
        included: boolean;
    }[];

    promotion?: {
        type: "hot" | "limited" | "member" | "lowest" | "deal";
        message: string;
        discount?: number;
    }; recommended?: boolean;
    mostPopular?: boolean;

    guestCountWarning?: string; // Thêm warning cho guest count

    guestCountWarningDetail?: {
        type: "exceeds_capacity" | "below_minimum";
        message: string;
        suggestedAction: string;
    };

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