// Common Room interface for search results
export interface Room {
    id: string;
    name: string;
    roomType: string;
    room_code?: string;
    description: string;
    image: string;
    images: string[];
    size: number;
    roomSize: number;
    view: string;
    viewType: string;
    bedType: string;
    amenities: string[];
    mainAmenities: string[];
    highlighted_amenities: string[];
    rating: number;
    maxGuests: number;
    availableRooms: number;
    specifications: any;
    priceVND: number;
    pricePerNight: number;
    originalPrice: number;
    options: any[];
    pricing: any;
    bookingDetails: any;
    // Additional properties for UI
    isSale?: boolean;
    discount?: number;
    urgencyRoomMessage?: string;
}
