import axios from 'axios';

// Base API configuration
const API_BASE_URL = process.env.REACT_APP_API_BASE_URL || 'http://localhost:8000/api';

const apiClient = axios.create({
    baseURL: API_BASE_URL,
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

// Request interceptor for adding auth token
apiClient.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('auth_token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => Promise.reject(error)
);

// Response interceptor for error handling
apiClient.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Token expired or invalid
            localStorage.removeItem('auth_token');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

// Types for API responses
export interface ImageItem {
    id: number;
    title: string;
    description: string;
    category: string;
    url: string;
    alt_text?: string;
    created_at: string;
    updated_at: string;
}

export interface RoomItem {
    id: number;
    name: string;
    type: string;
    size: string;
    price: number;
    currency: string;
    rating: number;
    description: string;
    features: string[];
    images: ImageItem[];
    amenities: string[];
    created_at: string;
    updated_at: string;
}

export interface ServiceItem {
    id: number;
    title: string;
    description: string;
    icon: string;
    category: string;
    is_active: boolean;
    created_at: string;
    updated_at: string;
}

export interface LoungeItem {
    id: number;
    title: string;
    description: string;
    features: string[];
    images: ImageItem[];
    category: string;
    is_featured: boolean;
    created_at: string;
    updated_at: string;
}

// API Service class
class TheLevelApiService {
    // Get hero section images
    async getHeroImages(): Promise<ImageItem[]> {
        try {
            const response = await apiClient.get('/the-level/hero-images');
            return response.data.data || [];
        } catch (error) {
            console.error('Error fetching hero images:', error);
            return [];
        }
    }

    // Get gallery images
    async getGalleryImages(page: number = 1, limit: number = 20): Promise<{
        data: ImageItem[];
        total: number;
        current_page: number;
        last_page: number;
    }> {
        try {
            const response = await apiClient.get('/the-level/gallery', {
                params: { page, limit }
            });
            return response.data;
        } catch (error) {
            console.error('Error fetching gallery images:', error);
            return {
                data: [],
                total: 0,
                current_page: 1,
                last_page: 1
            };
        }
    }

    // Get premium rooms
    async getPremiumRooms(): Promise<RoomItem[]> {
        try {
            const response = await apiClient.get('/the-level/premium-rooms');
            return response.data.data || [];
        } catch (error) {
            console.error('Error fetching premium rooms:', error);
            return [];
        }
    }

    // Get room details by ID
    async getRoomDetails(roomId: number): Promise<RoomItem | null> {
        try {
            const response = await apiClient.get(`/the-level/rooms/${roomId}`);
            return response.data.data || null;
        } catch (error) {
            console.error('Error fetching room details:', error);
            return null;
        }
    }

    // Get services
    async getServices(): Promise<ServiceItem[]> {
        try {
            const response = await apiClient.get('/the-level/services');
            return response.data.data || [];
        } catch (error) {
            console.error('Error fetching services:', error);
            return [];
        }
    }

    // Get lounge information
    async getLoungeData(): Promise<LoungeItem[]> {
        try {
            const response = await apiClient.get('/the-level/lounges');
            return response.data.data || [];
        } catch (error) {
            console.error('Error fetching lounge data:', error);
            return [];
        }
    }

    // Get featured images by category
    async getImagesByCategory(category: string): Promise<ImageItem[]> {
        try {
            const response = await apiClient.get('/the-level/images', {
                params: { category }
            });
            return response.data.data || [];
        } catch (error) {
            console.error('Error fetching images by category:', error);
            return [];
        }
    }

    // Upload image (for admin purposes)
    async uploadImage(formData: FormData): Promise<ImageItem | null> {
        try {
            const response = await apiClient.post('/the-level/images/upload', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            return response.data.data || null;
        } catch (error) {
            console.error('Error uploading image:', error);
            return null;
        }
    }

    // Get room availability
    async checkRoomAvailability(roomId: number, checkIn: string, checkOut: string): Promise<{
        available: boolean;
        price?: number;
        message?: string;
    }> {
        try {
            const response = await apiClient.post('/the-level/rooms/check-availability', {
                room_id: roomId,
                check_in: checkIn,
                check_out: checkOut,
            });
            return response.data;
        } catch (error) {
            console.error('Error checking room availability:', error);
            return {
                available: false,
                message: 'Không thể kiểm tra tình trạng phòng'
            };
        }
    }

    // Create booking inquiry
    async createBookingInquiry(data: {
        room_id: number;
        guest_name: string;
        guest_email: string;
        guest_phone: string;
        check_in: string;
        check_out: string;
        guests: number;
        special_requests?: string;
    }): Promise<{ success: boolean; message: string; booking_id?: string }> {
        try {
            const response = await apiClient.post('/the-level/booking-inquiry', data);
            return response.data;
        } catch (error) {
            console.error('Error creating booking inquiry:', error);
            return {
                success: false,
                message: 'Không thể gửi yêu cầu đặt phòng'
            };
        }
    }
}

// Create and export service instance
export const theLevelApi = new TheLevelApiService();

// Utility functions for image handling
export const getImageUrl = (imagePath: string): string => {
    if (!imagePath) return '';

    // If it's already a full URL, return as is
    if (imagePath.startsWith('http')) {
        return imagePath;
    }

    // Construct full URL from API base
    const baseUrl = API_BASE_URL.replace('/api', '');
    return `${baseUrl}/storage/${imagePath}`;
};

export const formatPrice = (price: number, currency: string = 'VNĐ'): string => {
    return new Intl.NumberFormat('vi-VN').format(price) + ' ' + currency;
};

export const getOptimizedImageUrl = (
    imagePath: string,
    width?: number,
    height?: number,
    quality: number = 80
): string => {
    const baseUrl = getImageUrl(imagePath);
    if (!baseUrl || baseUrl.startsWith('http')) return baseUrl;

    const params = new URLSearchParams();
    if (width) params.append('w', width.toString());
    if (height) params.append('h', height.toString());
    params.append('q', quality.toString());

    return `${baseUrl}?${params.toString()}`;
};

export default theLevelApi;
