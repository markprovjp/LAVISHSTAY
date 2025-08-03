// src/api/roomDetailApi.ts
import { useQuery } from '@tanstack/react-query';
import { RoomTypeDetail, RoomComment, RelatedRoomType, RoomRatingStats } from '../types/roomDetail';

// Mock data
const mockRoomDetail: RoomTypeDetail = {
    id: '1',
    name: 'Deluxe Ocean View Suite',
    slug: 'deluxe-ocean-view-suite',
    description: 'Luxurious suite with breathtaking ocean views and premium amenities',
    fullDescription: `Discover the epitome of luxury in our Deluxe Ocean View Suite. This elegantly appointed suite offers unparalleled comfort with sweeping views of the pristine coastline. 

    Every detail has been carefully crafted to provide an unforgettable experience, from the plush king-size bed to the marble-appointed bathroom with deep soaking tub. The suite features a separate living area with contemporary furnishings, perfect for relaxation or entertaining.

    Wake up to stunning sunrise views over the ocean, and end your day watching the sunset from your private balcony. This is more than accommodation - it's your gateway to an extraordinary coastal experience.`,
    area: 85,
    maxGuests: 4,
    bedType: 'King Size Bed + Sofa Bed',
    view: 'Ocean View',
    basePrice: 3500000,
    originalPrice: 4200000,
    discount: 17,
    currency: 'VND',
    images: [
        { id: '1', url: 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800', alt: 'Suite Overview', type: 'main', order: 1 },
        { id: '2', url: 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=800', alt: 'Bedroom', type: 'detail', order: 2 },
        { id: '3', url: 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800', alt: 'Bathroom', type: 'detail', order: 3 },
        { id: '4', url: 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800', alt: 'Living Area', type: 'detail', order: 4 },
        { id: '5', url: 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=800', alt: 'Ocean View', type: 'view', order: 5 },
        { id: '6', url: 'https://images.unsplash.com/photo-1595576508898-0ad5c879a061?w=800', alt: 'Balcony', type: 'detail', order: 6 },
    ],
    facilities: [
        { id: '1', name: 'Free WiFi', icon: 'Wifi', category: 'technology', isHighlighted: true },
        { id: '2', name: 'Air Conditioning', icon: 'Wind', category: 'comfort', isHighlighted: true },
        { id: '3', name: 'Ocean View', icon: 'Waves', category: 'basic', isHighlighted: true },
        { id: '4', name: 'King Size Bed', icon: 'Bed', category: 'basic', isHighlighted: true },
        { id: '5', name: 'Mini Bar', icon: 'Wine', category: 'kitchen', isHighlighted: false },
        { id: '6', name: 'Safe Box', icon: 'Lock', category: 'basic', isHighlighted: false },
        { id: '7', name: 'Smart TV', icon: 'Monitor', category: 'entertainment', isHighlighted: false },
        { id: '8', name: 'Coffee Machine', icon: 'Coffee', category: 'kitchen', isHighlighted: false },
        { id: '9', name: 'Bathtub', icon: 'Bath', category: 'bathroom', isHighlighted: false },
        { id: '10', name: 'Hair Dryer', icon: 'Wind', category: 'bathroom', isHighlighted: false },
        { id: '11', name: 'Room Service', icon: 'Bell', category: 'basic', isHighlighted: false },
        { id: '12', name: 'Balcony', icon: 'DoorOpen', category: 'basic', isHighlighted: true },
    ],
    mainAmenities: ['Ocean View', 'Free WiFi', 'Air Conditioning', 'King Size Bed', 'Balcony'],
    rating: 4.8,
    totalReviews: 247,
    status: 'available',
    policies: [
        {
            id: '1',
            type: 'checkin',
            title: 'Check-in Time',
            description: 'Check-in is available from 3:00 PM. Early check-in may be available upon request.',
            icon: 'Clock'
        },
        {
            id: '2',
            type: 'checkout',
            title: 'Check-out Time',
            description: 'Check-out is until 12:00 PM. Late check-out may be available for an additional fee.',
            icon: 'Clock'
        },
        {
            id: '3',
            type: 'cancellation',
            title: 'Cancellation Policy',
            description: 'Free cancellation up to 24 hours before check-in. Cancellations within 24 hours are subject to a one-night charge.',
            icon: 'X'
        },
        {
            id: '4',
            type: 'pets',
            title: 'Pet Policy',
            description: 'Pets are not allowed in this room type.',
            icon: 'Ban'
        },
        {
            id: '5',
            type: 'smoking',
            title: 'Smoking Policy',
            description: 'This is a non-smoking room. Smoking is only permitted on the balcony.',
            icon: 'Cigarette'
        }
    ],
    specifications: [
        { id: '1', label: 'Room Size', value: '85 m²', icon: 'Square', category: 'room' },
        { id: '2', label: 'Max Guests', value: '4 Adults', icon: 'Users', category: 'room' },
        { id: '3', label: 'Bed Type', value: 'King Size + Sofa Bed', icon: 'Bed', category: 'bed' },
        { id: '4', label: 'View', value: 'Ocean View', icon: 'Eye', category: 'room' },
        { id: '5', label: 'Bathroom', value: 'Marble Bath with Tub', icon: 'Bath', category: 'bathroom' },
        { id: '6', label: 'Floor', value: '15th - 20th Floor', icon: 'Building', category: 'room' },
    ],
    isBookmarkable: true,
    isLikeable: true,
    tags: ['Ocean View', 'Luxury', 'Popular', 'Romantic'],
    location: 'Tower A',
    floor: 18,
    roomCount: 12,
    availableRooms: 3,
    lastUpdated: '2024-12-15T10:30:00Z',
    createdAt: '2024-01-15T08:00:00Z',
};

const mockComments: RoomComment[] = [
    {
        id: '1',
        userId: '1',
        userName: 'Nguyen Van A',
        userAvatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150',
        rating: 5,
        title: 'Absolutely Perfect!',
        content: 'The ocean view was breathtaking, especially during sunrise. The room was spacious, clean, and beautifully decorated. Staff service was exceptional. Will definitely come back!',
        images: ['https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=400'],
        helpful: 24,
        createdAt: '2024-12-10T14:30:00Z',
        isVerified: true,
        stayDuration: '3 nights',
        roomNumber: 'A1801',
        replies: [
            {
                id: '1',
                userId: 'staff1',
                userName: 'Hotel Manager',
                userAvatar: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=150',
                content: 'Thank you so much for your wonderful review! We\'re delighted you enjoyed your stay.',
                createdAt: '2024-12-11T09:15:00Z',
                isStaff: true,
            }
        ]
    },
    {
        id: '2',
        userId: '2',
        userName: 'Tran Thi B',
        userAvatar: 'https://images.unsplash.com/photo-1494790108755-2616b612b789?w=150',
        rating: 4,
        title: 'Great Experience',
        content: 'Very comfortable room with amazing view. The balcony was perfect for morning coffee. Only minor issue was the AC was a bit noisy at night.',
        helpful: 18,
        createdAt: '2024-12-08T16:45:00Z',
        isVerified: true,
        stayDuration: '2 nights',
        roomNumber: 'A1805',
    },
    {
        id: '3',
        userId: '3',
        userName: 'Le Van C',
        userAvatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150',
        rating: 5,
        title: 'Honeymoon Paradise',
        content: 'Perfect for our honeymoon! The sunset view from the balcony was romantic. Room service was prompt and the bed was incredibly comfortable.',
        helpful: 31,
        createdAt: '2024-12-05T20:20:00Z',
        isVerified: true,
        stayDuration: '4 nights',
        roomNumber: 'A1812',
    }
];

const mockRelatedRooms: RelatedRoomType[] = [
    {
        id: '2',
        name: 'Premium City View Suite',
        slug: 'premium-city-view-suite',
        basePrice: 2800000,
        originalPrice: 3200000,
        discount: 13,
        mainImage: 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=400',
        rating: 4.6,
        totalReviews: 156,
        area: 70,
        maxGuests: 3,
        mainAmenities: ['City View', 'Free WiFi', 'Smart TV'],
        availableRooms: 5,
    },
    {
        id: '3',
        name: 'Executive Ocean Suite',
        slug: 'executive-ocean-suite',
        basePrice: 4200000,
        originalPrice: 4800000,
        discount: 13,
        mainImage: 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=400',
        rating: 4.9,
        totalReviews: 89,
        area: 120,
        maxGuests: 6,
        mainAmenities: ['Ocean View', 'Kitchen', 'Jacuzzi'],
        isPopular: true,
        availableRooms: 1,
    },
    {
        id: '4',
        name: 'Standard Garden View',
        slug: 'standard-garden-view',
        basePrice: 1800000,
        mainImage: 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=400',
        rating: 4.3,
        totalReviews: 203,
        area: 45,
        maxGuests: 2,
        mainAmenities: ['Garden View', 'Free WiFi', 'AC'],
        availableRooms: 8,
    }
];

const mockRatingStats: RoomRatingStats = {
    overall: 4.8,
    cleanliness: 4.9,
    comfort: 4.8,
    location: 4.7,
    facilities: 4.6,
    staff: 5.0,
    valueForMoney: 4.5,
    totalReviews: 247,
    ratingDistribution: {
        5: 189,
        4: 42,
        3: 12,
        2: 3,
        1: 1,
    },
};

// API Functions
export const useRoomDetail = (roomId: string) => {
    return useQuery({
        queryKey: ['room-detail', roomId],
        queryFn: async () => {
            // Simulate API delay
            await new Promise(resolve => setTimeout(resolve, 1000));
            return mockRoomDetail;
        },
        staleTime: 5 * 60 * 1000, // 5 minutes
        gcTime: 10 * 60 * 1000, // 10 minutes
    });
};

export const useRoomComments = (roomId: string, page = 1, limit = 10) => {
    return useQuery({
        queryKey: ['room-comments', roomId, page, limit],
        queryFn: async () => {
            await new Promise(resolve => setTimeout(resolve, 800));
            return {
                comments: mockComments,
                total: mockComments.length,
                page,
                limit,
                hasMore: false,
            };
        },
        staleTime: 2 * 60 * 1000,
    });
};

export const useRelatedRooms = (roomId: string) => {
    return useQuery({
        queryKey: ['related-rooms', roomId],
        queryFn: async () => {
            await new Promise(resolve => setTimeout(resolve, 600));
            return mockRelatedRooms;
        },
        staleTime: 10 * 60 * 1000,
    });
};

export const useRoomRatingStats = (roomId: string) => {
    return useQuery({
        queryKey: ['room-rating-stats', roomId],
        queryFn: async () => {
            await new Promise(resolve => setTimeout(resolve, 500));
            return mockRatingStats;
        },
        staleTime: 5 * 60 * 1000,
    });
};
