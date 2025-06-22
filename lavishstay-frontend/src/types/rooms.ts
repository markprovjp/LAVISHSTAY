// src/types/rooms.ts
export interface Room {
    id: number;
    name: string;
    room_type_id: number;
    price: number;
    discount?: number;
    description?: string;
    images?: string[];
    amenities?: string[];
    capacity: number;
    area?: number;
    status: 'available' | 'occupied' | 'maintenance';
    created_at?: string;
    updated_at?: string;
    room_type?: RoomType;
    isSale?: boolean; // For display purposes
}

export interface RoomType {
    id: number;
    name: string;
    slug: string;
    description?: string;
    base_price: number;
    images?: string[]; // Thêm trường images
    created_at?: string;
    updated_at?: string;
    rooms?: Room[];
    rooms_count?: number;
}

export interface RoomFilters {
    search?: string;
    room_type_id?: number;
    min_price?: number;
    max_price?: number;
    capacity?: number;
    status?: string;
    page?: number;
    per_page?: number;
    sort_by?: string;
    sort_order?: 'asc' | 'desc';
}

export interface RoomTypeFilters {
    search?: string;
    page?: number;
    per_page?: number;
    sort_by?: string;
    sort_order?: 'asc' | 'desc';
}
