// TypeScript interfaces for Room Types Overview API
export interface RoomTypeAmenity {
    id: number;
    name: string;
    icon: string;
}

export interface RoomType {
    room_type_id: number;
    slug: string;
    title: string;
    short_description: string;
    thumbnail: string;
    gallery: string[];
    starting_price: number | null;
    price_unit: string;
    max_adults: number;
    max_children: number;
    total_rooms: number;
    available_rooms: number;
    avg_rating: number;
    review_count: number;
    amenities: RoomTypeAmenity[];
    tags: string[];
    badges: string[];
    slug_url: string;
}

export interface RoomTypesApiResponse {
    success: boolean;
    meta: {
        total: number;
        page: number;
        per_page: number;
        total_pages: number;
    };
    data: RoomType[];
}

export interface RoomTypesFilters {
    featured?: boolean;
    popular?: boolean;
    min_price?: number;
    max_price?: number;
    limit?: number;
    page?: number;
    locale?: string;
    currency?: string;
    check_date?: string;
}

export interface RoomTypesApiError {
    success: false;
    message: string;
    errors?: Record<string, string[]>;
}

// Badge type mapping for UI
export const BADGE_CONFIG = {
    featured: { color: 'gold', text: 'Nổi bật' },
    'best-seller': { color: 'red', text: 'Bán chạy' },
    new: { color: 'green', text: 'Mới' },
    'price-unavailable': { color: 'default', text: 'Liên hệ' },
} as const;

// Tag type mapping for UI
export const TAG_CONFIG = {
    'sea-view': { color: 'blue', text: 'Hướng biển' },
    suite: { color: 'purple', text: 'Suite' },
    premium: { color: 'gold', text: 'Cao cấp' },
    'family-friendly': { color: 'green', text: 'Thân thiện gia đình' },
    'breakfast-included': { color: 'orange', text: 'Có bữa sáng' },
} as const;

export type BadgeType = keyof typeof BADGE_CONFIG;
export type TagType = keyof typeof TAG_CONFIG;
