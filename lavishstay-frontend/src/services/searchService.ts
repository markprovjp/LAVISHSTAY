import { SearchData } from '../store/slices/searchSlice';
import { ApiService, API_ENDPOINTS } from './apiService';

interface BackendSearchResponse {
    success: boolean;
    message?: string;
    data: {
        results: any[];
        search_params: {
            check_in: string;
            check_out: string;
            nights: number;
            guests: {
                adults: number;
                children: number;
                guest_type: string;
                total: number;
            };
        };
    };
}

interface FrontendSearchResult {
    rooms: any[];
    searchSummary: {
        totalRooms: number;
        nights: number;
        checkIn: string;
        checkOut: string;
        adults: number;
        children: number;
        childrenAges: number[];
        guestType: string;
        totalGuests: number;
    };
}

export const searchService = {
    async searchRooms(searchData: SearchData): Promise<FrontendSearchResult> {
        try {
            console.log('🔍 Calling backend API with search data:', searchData);

            // Prepare search parameters for backend API
            const searchParams = {
                check_in: searchData.checkIn,
                check_out: searchData.checkOut,
                adults: searchData.guestDetails.adults,
                children: searchData.guestDetails.children,
                children_ages: searchData.guestDetails.childrenAges.map(child => child.age),
                guest_type: searchData.guestType
            };

            console.log('📤 Backend API params:', searchParams);

            // Call backend API using ApiService POST method
            const response: BackendSearchResponse = await ApiService.post(API_ENDPOINTS.SEARCH.ROOMS, searchParams);
            console.log('📥 Backend API response:', response);

            if (!response.success) {
                throw new Error(response.message || 'Search failed');
            }

            // Transform backend response to frontend format
            const transformedRooms = this.transformBackendRooms(response.data.results);

            return {
                rooms: transformedRooms,
                searchSummary: {
                    totalRooms: response.data.results.length,
                    nights: response.data.search_params.nights,
                    checkIn: response.data.search_params.check_in,
                    checkOut: response.data.search_params.check_out,
                    adults: response.data.search_params.guests.adults,
                    children: response.data.search_params.guests.children,
                    childrenAges: searchData.guestDetails.childrenAges.map(child => child.age),
                    guestType: response.data.search_params.guests.guest_type,
                    totalGuests: response.data.search_params.guests.total,
                }
            };
        } catch (error) {
            console.error('❌ Search service error:', error);
            throw error;
        }
    },

    // Transform backend room data to frontend format
    transformBackendRooms(backendRooms: any[]): any[] {
        return backendRooms.map(result => {
            const roomType = result.room_type;
            const pricing = result.pricing;
            const bookingDetails = result.booking_details;

            // Create room options for frontend (simplified to one main option per room type)
            const mainOption = {
                id: `${roomType.id}-main`,
                name: 'Standard Booking',
                pricePerNight: {
                    vnd: pricing.total_per_night,
                    originalVnd: pricing.base_price_per_night
                },
                maxGuests: roomType.max_guests,
                minGuests: 1,
                roomType: roomType.room_code || 'standard',
                cancellationPolicy: 'Free cancellation before 24h',
                paymentPolicy: 'Pay at hotel or online',
                availability: roomType.available_rooms || 0,
                additionalServices: [],
                promotion: pricing.discount_amount > 0 ? {
                    type: 'discount',
                    value: pricing.discount_amount,
                    description: `Giảm ${pricing.discount_amount.toLocaleString('vi-VN')}đ`
                } : null,
                recommended: false,
                mostPopular: false,
                pricing: {
                    basePrice: pricing.base_price_per_night,
                    totalPerNight: pricing.total_per_night,
                    totalPrice: pricing.total_price,
                    breakdown: pricing.breakdown,
                    seasonalMultiplier: pricing.seasonal_multiplier,
                    discountAmount: pricing.discount_amount,
                    nights: bookingDetails.nights
                }
            };

            // Get images from backend, fallback to default
            const images = roomType.images && roomType.images.length > 0
                ? roomType.images.map((img: string) => {
                    // Handle relative paths from backend
                    if (img.startsWith('/storage/')) {
                        return `http://localhost:8888${img}`;
                    }
                    return img;
                })
                : ['/images/rooms/default.jpg'];

            return {
                id: roomType.id.toString(),
                name: roomType.name,
                roomType: roomType.room_code || 'standard',
                room_code: roomType.room_code,
                description: roomType.description || '',
                image: images[0],
                images: images,
                size: roomType.room_size,
                roomSize: roomType.room_size,
                view: roomType.view_type,
                viewType: roomType.view_type,
                bedType: roomType.specifications?.bed_options?.[0] || 'King bed',
                amenities: roomType.amenities?.map((a: any) => a.name || a.amenity_name) || [],
                mainAmenities: roomType.highlighted_amenities ? Object.values(roomType.highlighted_amenities).map((a: any) => a.name) : [],
                highlighted_amenities: roomType.highlighted_amenities ? Object.values(roomType.highlighted_amenities).map((a: any) => a.name) : [],
                rating: 4.5, // Default rating
                maxGuests: roomType.max_guests,
                availableRooms: roomType.available_rooms || 0,
                specifications: roomType.specifications || {},

                // Pricing from backend
                priceVND: pricing.total_price,
                pricePerNight: pricing.total_per_night,
                originalPrice: pricing.base_price_per_night,

                // Main option for booking
                options: [mainOption],

                // Additional data for frontend
                pricing: {
                    ...pricing,
                    nights: bookingDetails.nights,
                    breakdown: pricing.breakdown
                },
                bookingDetails: {
                    checkIn: bookingDetails.check_in,
                    checkOut: bookingDetails.check_out,
                    nights: bookingDetails.nights,
                    guests: bookingDetails.guests
                }
            };
        });
    }
};
