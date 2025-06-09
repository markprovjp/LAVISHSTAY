import { sampleUsers } from '../mirage/users';

export interface UserProfile {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    phone?: string;
    address?: string;
    dateOfBirth?: string;
    memberSince: string;
    totalSpent: number;
    totalBookings: number;
    loyaltyPoints: number;
    avgRating: number;
    reviews: number;
    preferences: {
        roomType: string[];
        amenities: string[];
        notifications: {
            email: boolean;
            sms: boolean;
            push: boolean;
        };
    };
}

export interface BookingHistory {
    id: string;
    hotelName: string;
    roomType: string;
    checkIn: string;
    checkOut: string;
    totalAmount: number;
    status: 'completed' | 'upcoming' | 'cancelled';
    rating?: number;
    reviewText?: string;
}

// Mock booking history for LavishStay Premium Hotel (295 rooms)
const mockBookingHistory: BookingHistory[] = [
    {
        id: 'BK001',
        hotelName: 'LavishStay Premium Hotel',
        roomType: 'Deluxe Ocean View',
        checkIn: '2024-01-15',
        checkOut: '2024-01-18',
        totalAmount: 3500000,
        status: 'completed',
        rating: 5,
        reviewText: 'Tuyệt vời! Dịch vụ chu đáo, phòng sạch đẹp.'
    },
    {
        id: 'BK002',
        hotelName: 'LavishStay Premium Hotel',
        roomType: 'Suite Premium',
        checkIn: '2024-02-20',
        checkOut: '2024-02-23',
        totalAmount: 6200000,
        status: 'completed',
        rating: 4,
        reviewText: 'Phòng rộng rãi, view đẹp. Sẽ quay lại.'
    },
    {
        id: 'BK003',
        hotelName: 'LavishStay Premium Hotel',
        roomType: 'Presidential Suite',
        checkIn: '2024-06-15',
        checkOut: '2024-06-20',
        totalAmount: 25000000,
        status: 'upcoming'
    }
];

class ProfileService {
    // Get user profile from Mirage
    async getUserProfile(userId?: number): Promise<UserProfile> {
        // Simulate API delay
        await new Promise(resolve => setTimeout(resolve, 500));

        // Get user from Mirage data
        const user = sampleUsers.find(u => u.id === (userId || 1)) || sampleUsers[0];

        // Calculate mock data based on user
        const totalSpent = this.calculateTotalSpent(user.id);
        const totalBookings = this.calculateTotalBookings(user.id);
        const loyaltyPoints = Math.floor(totalSpent / 10000); // Simple points calculation

        return {
            id: user.id,
            name: user.name,
            email: user.email,
            avatar: user.avatar,
            phone: user.phone,
            address: user.address,
            dateOfBirth: user.dateOfBirth,
            memberSince: user.created_at.split('T')[0],
            totalSpent,
            totalBookings,
            loyaltyPoints,
            avgRating: 4.8 + Math.random() * 0.2, // 4.8-5.0
            reviews: totalBookings - Math.floor(Math.random() * 3),
            preferences: {
                roomType: ['suite', 'deluxe'],
                amenities: ['wifi', 'pool', 'spa', 'restaurant'],
                notifications: {
                    email: true,
                    sms: false,
                    push: true
                }
            }
        };
    }

    // Update user profile
    async updateUserProfile(updates: Partial<UserProfile>): Promise<UserProfile> {
        await new Promise(resolve => setTimeout(resolve, 800));

        // In real app, this would make API call to update user
        // For now, return updated mock data
        const currentProfile = await this.getUserProfile(updates.id);

        return {
            ...currentProfile,
            ...updates
        };
    }

    // Get booking history
    async getBookingHistory(): Promise<BookingHistory[]> {
        await new Promise(resolve => setTimeout(resolve, 300));
        return mockBookingHistory;
    }

    // Private helper methods
    private calculateTotalSpent(userId: number): number {
        // Mock calculation based on user ID - reduced for single hotel
        const baseSpending = userId * 2000000; // Base spending per user
        const randomMultiplier = 1 + Math.random() * 2; // 1x to 3x multiplier
        return Math.floor(baseSpending * randomMultiplier);
    }

    private calculateTotalBookings(_userId: number): number {
        // Reasonable booking count for single hotel guests
        return Math.floor(Math.random() * 15) + 3; // 3-18 bookings
    }
}

export const profileService = new ProfileService();
