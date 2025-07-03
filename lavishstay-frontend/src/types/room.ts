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

// Room Management Dashboard interfaces
export interface RoomManagement {
    id: string;
    number: string;
    floor: number;
    roomType: string;
    status: RoomStatus;
    guestName?: string;
    guestCount?: number;
    checkInDate?: string;
    checkOutDate?: string;
    shortCode?: string;
}

export interface RoomTypeManagement {
    id: string;
    name: string;
    description?: string;
}

export interface BookingManagement {
    id: string;
    roomId: string;
    guestName: string;
    guestCount: number;
    checkInDate: string;
    checkOutDate: string;
    status: BookingStatus;
}

export type RoomStatus = 'occupied' | 'available' | 'empty' | 'cleaning' | 'maintenance' | 'no_show' | 'check_in' | 'check_out' | 'deposited';

export type BookingStatus = 'confirmed' | 'checked_in' | 'checked_out' | 'cancelled' | 'no_show';

export type ViewMode = 'grid' | 'timeline';

export interface RoomFilters {
    customerName?: string;
    dateRange?: [string, string];
    roomNumber?: string;
    roomType?: string;
    roomStatus?: RoomStatus;
}

export interface FullCalendarEvent {
    id: string;
    resourceId: string;
    title: string;
    start: string;
    end: string;
    backgroundColor: string;
    guestCount?: number;
    status?: BookingStatus;
}

export interface FullCalendarResource {
    id: string;
    title: string;
    group: string;
}
