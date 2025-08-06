// src/types/roomDetail.ts
export interface RoomTypeDetail {
    id: string;
    name: string;
    slug: string;
    description: string;
    fullDescription: string;
    area: number;
    maxGuests: number;
    bedType: string;
    view: string;
    basePrice: number;
    originalPrice?: number;
    discount?: number;
    currency: string;
    images: RoomImage[];
    facilities: RoomFacility[];
    mainAmenities: string[];
    rating: number;
    totalReviews: number;
    status: 'available' | 'sold_out' | 'coming_soon';
    policies: RoomPolicy[];
    specifications: RoomSpecification[];
    isBookmarkable: boolean;
    isLikeable: boolean;
    tags: string[];
    location?: string;
    floor?: number;
    roomCount: number;
    availableRooms: number;
    lastUpdated: string;
    createdAt: string;
}

export interface RoomImage {
    id: string;
    url: string;
    alt: string;
    type: 'main' | 'detail' | 'amenity' | 'view';
    order: number;
}

export interface RoomFacility {
    id: string;
    name: string;
    icon: string;
    category: 'basic' | 'entertainment' | 'bathroom' | 'kitchen' | 'technology' | 'comfort';
    description?: string;
    isHighlighted: boolean;
}

export interface RoomPolicy {
    id: string;
    type: 'checkin' | 'checkout' | 'cancellation' | 'pets' | 'smoking' | 'children' | 'payment';
    title: string;
    description: string;
    icon?: string;
}

export interface RoomSpecification {
    id: string;
    label: string;
    value: string;
    icon?: string;
    category: 'room' | 'bed' | 'bathroom' | 'technology' | 'other';
}

export interface RoomComment {
    id: string;
    userId: string;
    userName: string;
    userAvatar?: string;
    rating: number;
    title: string;
    content: string;
    images?: string[];
    helpful: number;
    createdAt: string;
    updatedAt?: string;
    isVerified: boolean;
    stayDuration?: string;
    roomNumber?: string;
    replies?: RoomCommentReply[];
}

export interface RoomCommentReply {
    id: string;
    userId: string;
    userName: string;
    userAvatar?: string;
    content: string;
    createdAt: string;
    isStaff: boolean;
}

export interface RelatedRoomType {
    id: string;
    name: string;
    slug: string;
    basePrice: number;
    originalPrice?: number;
    discount?: number;
    mainImage: string;
    rating: number;
    totalReviews: number;
    area: number;
    maxGuests: number;
    mainAmenities: string[];
    isPopular?: boolean;
    availableRooms: number;
}

export interface RoomActionState {
    roomId: string;
    isLiked: boolean;
    isBookmarked: boolean;
    likeCount: number;
    bookmarkCount: number;
}

export interface RoomBookingInfo {
    checkInDate?: string;
    checkOutDate?: string;
    guests: number;
    nights?: number;
    totalPrice?: number;
    availableRooms: number;
    isAvailable: boolean;
    specialOffers?: RoomOffer[];
}

export interface RoomOffer {
    id: string;
    title: string;
    description: string;
    discount: number;
    validUntil?: string;
    terms?: string[];
}

export interface RoomRatingStats {
    overall: number;
    cleanliness: number;
    comfort: number;
    location: number;
    facilities: number;
    staff: number;
    valueForMoney: number;
    totalReviews: number;
    ratingDistribution: {
        [key: number]: number; // key: rating (1-5), value: count
    };
}
