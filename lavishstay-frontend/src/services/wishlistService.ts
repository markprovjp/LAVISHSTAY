// Wishlist service with Mirage integration
import { sampleRooms, type Room } from '../mirage/models';

export interface WishlistItem {
    id: string;
    name: string;
    rating: number;
    reviews: number;
    image: string;
    amenities: string[];
    mainAmenities?: string[];
    addedDate: string;
}

// Convert Room to WishlistItem
const convertRoomToWishlistItem = (room: Room): WishlistItem => {
    return {
        id: room.id.toString(),
        name: room.name,
        rating: room.rating || 4.5, // Default rating if not specified
        reviews: Math.floor(Math.random() * 500) + 50, // Random review count 50-550
        image: room.image,
        amenities: room.amenities,
        mainAmenities: room.mainAmenities,
        addedDate: new Date(Date.now() - Math.random() * 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0], // Random date within last 30 days
    };
};

// Mock wishlist data from sample rooms
const createMockWishlistData = (): WishlistItem[] => {
    // Select 6 random rooms for wishlist
    const shuffledRooms = [...sampleRooms].sort(() => Math.random() - 0.5);
    const selectedRooms = shuffledRooms.slice(0, 6);

    return selectedRooms.map(convertRoomToWishlistItem);
};

class WishlistService {
    private wishlistItems: WishlistItem[] = createMockWishlistData();

    // Get user's wishlist
    async getWishlist(): Promise<WishlistItem[]> {
        // Simulate API delay
        await new Promise(resolve => setTimeout(resolve, 500));
        return [...this.wishlistItems];
    }

    // Add item to wishlist
    async addToWishlist(roomId: string): Promise<void> {
        await new Promise(resolve => setTimeout(resolve, 300));

        const room = sampleRooms.find(r => r.id.toString() === roomId);
        if (!room) {
            throw new Error('Room not found');
        }

        // Check if already in wishlist
        const exists = this.wishlistItems.some(item => item.id === roomId);
        if (exists) {
            throw new Error('Item already in wishlist');
        }

        const wishlistItem = convertRoomToWishlistItem(room);
        this.wishlistItems.unshift(wishlistItem); // Add to beginning
    }

    // Remove item from wishlist
    async removeFromWishlist(itemId: string): Promise<void> {
        await new Promise(resolve => setTimeout(resolve, 200));

        const index = this.wishlistItems.findIndex(item => item.id === itemId);
        if (index === -1) {
            throw new Error('Item not found in wishlist');
        }

        this.wishlistItems.splice(index, 1);
    }

    // Remove multiple items from wishlist
    async removeMultipleFromWishlist(itemIds: string[]): Promise<void> {
        await new Promise(resolve => setTimeout(resolve, 300));

        this.wishlistItems = this.wishlistItems.filter(
            item => !itemIds.includes(item.id)
        );
    }

    // Check if item is in wishlist
    async isInWishlist(roomId: string): Promise<boolean> {
        await new Promise(resolve => setTimeout(resolve, 100));
        return this.wishlistItems.some(item => item.id === roomId);
    }

    // Get wishlist count
    async getWishlistCount(): Promise<number> {
        await new Promise(resolve => setTimeout(resolve, 100));
        return this.wishlistItems.length;
    }

    // Clear entire wishlist
    async clearWishlist(): Promise<void> {
        await new Promise(resolve => setTimeout(resolve, 200));
        this.wishlistItems = [];
    }
}

export const wishlistService = new WishlistService();
