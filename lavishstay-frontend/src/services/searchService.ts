import { SearchData } from '../store/slices/searchSlice';
import dayjs from 'dayjs';

interface RoomPackageApiResponse {
    success: boolean;
    data: RoomPackageData[];
    summary: {
        total_room_types: number;
        total_packages: number;
        search_criteria: {
            check_in_date: string;
            check_out_date: string;
            guest_count: string;
        };
        rooms_needed: number;
        nights: number;
    };
    message: string;
}

interface RoomPackageData {
    room_type_id: number;
    bed_type_name: string; // Added bed_type_name
    room_type_name: string;
    room_code: string;
    description: string;
    size: number;
    max_guests: number;
    rating: number;
    base_price: string;
    adjusted_price: number;
    available_rooms: string;
    rooms_needed: number;
    images: Array<{
        id: number;
        room_type_id: number;
        image_url: string;
        alt_text: string;
        is_main: number;
    }>;
    main_image: {
        id: number;
        room_type_id: number;
        image_url: string;
        alt_text: string;
        is_main: number;
    } | null;
    amenities: Array<{
        id: number;
        name: string;
        icon: string;
        category: string;
        description: string;
    }>;
    highlighted_amenities: Array<{
        id: number;
        name: string;
        icon: string;
        category: string;
        description: string;
    }>;
    package_options: Array<{
        package_id: number;
        package_name: string;
        package_description: string;
        price_modifier_vnd: string;
        price_per_room_per_night: number;
        total_package_price: number;
        services: any[];
        pricing_breakdown: {
            base_price_per_night: string;
            adjusted_price_per_night: number;
            package_modifier: string;
            final_price_per_room_per_night: number;
            rooms_needed: number;
            nights: number;
            total_price: number;
            currency: string;
        };
    }>;
    cheapest_package_price: number;
    search_criteria: {
        guest_count: string;
        check_in_date: string;
        check_out_date: string;
        nights: number;
    };
}

interface FrontendSearchResult {
    rooms: any[];
    total: number;
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
            console.log('🔍 Calling room packages API with search data:', searchData);

            // Xử lý ngày nhận và trả
            const checkInDate = searchData.dateRange?.[0]
                ? (typeof searchData.dateRange[0] === 'string'
                    ? searchData.dateRange[0]
                    : dayjs(searchData.dateRange[0]).format('YYYY-MM-DD'))
                : dayjs().format('YYYY-MM-DD');

            const checkOutDate = searchData.dateRange?.[1]
                ? (typeof searchData.dateRange[1] === 'string'
                    ? searchData.dateRange[1]
                    : dayjs(searchData.dateRange[1]).format('YYYY-MM-DD'))
                : dayjs().add(1, 'day').format('YYYY-MM-DD');

            // Tạo danh sách rooms từ dữ liệu đầu vào
            const rooms = searchData.rooms && searchData.rooms.length > 0
                ? searchData.rooms.map(r => ({
                    adults: r.adults || 2,
                    children: r.children || 0,
                    childrenAges: r.childrenAges || [] // 👈 bổ sung dòng này
                }))
                : [{
                    adults: searchData.guestDetails?.adults || 2,
                    children: searchData.guestDetails?.children || 0,
                    childrenAges: searchData.guestDetails?.childrenAges || [] // 👈 bổ sung dòng này
                }];


            const totalGuests = rooms.reduce((sum, r) => sum + r.adults + r.children, 0);

            const requestBody = {
                check_in_date: checkInDate,
                check_out_date: checkOutDate,
                rooms: rooms
            };

            console.log('📤 Room packages API body:', requestBody);

            const response = await fetch(`http://localhost:8888/api/room-packages/search`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestBody)
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const apiResponse: RoomPackageApiResponse = await response.json();
            console.log('📥 Room packages API response:', apiResponse);

            if (!apiResponse.success) {
                throw new Error(apiResponse.message || 'Search failed');
            }

            const transformedRooms = this.transformRoomPackageData(apiResponse.data, apiResponse.summary);

            return {
                rooms: transformedRooms,
                total: transformedRooms.length,
                searchSummary: {
                    totalRooms: apiResponse.summary.total_room_types,
                    nights: apiResponse.summary.nights,
                    checkIn: checkInDate,
                    checkOut: checkOutDate,
                    adults: searchData.guestDetails?.adults || 2,
                    children: searchData.guestDetails?.children || 0,
                    childrenAges: (searchData.guestDetails?.childrenAges || []).map((child: any) =>
                        typeof child === 'object' ? child.age : child
                    ),
                    guestType: searchData.guestType,
                    totalGuests: totalGuests,
                }
            };
        } catch (error) {
            console.error('❌ Search service error:', error);
            throw error;
        }
    },

    // Transform room package data to frontend format
    transformRoomPackageData(roomPackages: RoomPackageData[], summary: any): any[] {
        return roomPackages.map(packageData => {
            // Get main image
            const mainImage = packageData.main_image?.image_url || packageData.images?.[0]?.image_url ;

            // Get all images
            const images = packageData.images?.map(img => img.image_url) || [mainImage];

            // Create options from package options
            const options = packageData.package_options.map(pkg => ({
                id: `pkg-${pkg.package_id}`,
                name: pkg.package_name,
                description: pkg.package_description,
                pricePerNight: {
                    vnd: pkg.price_per_room_per_night,
                    originalVnd: packageData.adjusted_price
                },
                totalPrice: pkg.total_package_price,
                maxGuests: packageData.max_guests,
                minGuests: 1,
                roomType: packageData.room_code,
                cancellationPolicy: 'Free cancellation before 24h',
                paymentPolicy: 'Pay at hotel or online',
                availability: parseInt(packageData.available_rooms) || 0,
                additionalServices: pkg.services || [],
                promotion: pkg.price_modifier_vnd !== "0.00" ? {
                    type: 'package',
                    value: parseFloat(pkg.price_modifier_vnd),
                    description: `Gói ${pkg.package_name}`
                } : null,
                recommended: pkg.package_name.toLowerCase().includes('standard'),
                mostPopular: pkg.package_name.toLowerCase().includes('premium'),
                pricing: pkg.pricing_breakdown
            }));

            // Add base option if no package options exist
            if (options.length === 0) {
                options.push({
                    id: `base-${packageData.room_type_id}`,
                    name: 'Standard Room',
                    description: 'Basic room booking',
                    pricePerNight: {
                        vnd: packageData.adjusted_price,
                        originalVnd: parseFloat(packageData.base_price)
                    },
                    totalPrice: packageData.adjusted_price * summary.nights,
                    maxGuests: packageData.max_guests,
                    minGuests: 1,
                    roomType: packageData.room_code,
                    cancellationPolicy: 'Free cancellation before 24h',
                    paymentPolicy: 'Pay at hotel or online',
                    availability: parseInt(packageData.available_rooms) || 0,
                    additionalServices: [],
                    promotion: null,
                    recommended: true,
                    mostPopular: false,
                    pricing: {
                        base_price_per_night: packageData.base_price,
                        adjusted_price_per_night: packageData.adjusted_price,
                        package_modifier: "0.00",
                        final_price_per_room_per_night: packageData.adjusted_price,
                        rooms_needed: packageData.rooms_needed,
                        nights: summary.nights,
                        total_price: packageData.adjusted_price * summary.nights,
                        currency: "VND"
                    }
                });
            }

            return {
                id: packageData.room_type_id.toString(),
                name: packageData.room_type_name,
                roomType: packageData.room_code,
                room_code: packageData.room_code,
                description: packageData.description ,
                image: mainImage,
                images: images,
                size: packageData.size,
                roomSize: packageData.size,
                view: 'Tầm nhìn thành phố', // Default view
                bedType: packageData.bed_type_name, // Default bed type
                amenities: packageData.amenities?.map(a => a.name) || [],
                mainAmenities: packageData.highlighted_amenities?.slice(0, 5).map(a => a.name) || [],
                highlighted_amenities: packageData.highlighted_amenities?.map(a => a.name) || [],
                rating: packageData.rating,
                maxGuests: packageData.max_guests,
                availableRooms: parseInt(packageData.available_rooms) || 0,
                roomsNeeded: packageData.rooms_needed,

                // Pricing info
                priceVND: packageData.cheapest_package_price || (packageData.adjusted_price * summary.nights),
                pricePerNight: packageData.adjusted_price,
                originalPrice: parseFloat(packageData.base_price),
                cheapestPrice: packageData.cheapest_package_price,

                // Options for booking
                options: options,

                // Additional data
                packageData: packageData,
                searchCriteria: packageData.search_criteria
            };
        });
    }
};
