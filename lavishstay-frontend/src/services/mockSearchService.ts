// Mock search service for development without backend
import { SearchData } from '../store/slices/searchSlice';
import { sampleRooms, Room } from '../mirage/models';

// Response interfaces
export interface SearchResponse {
    rooms: Room[];
    total: number;
    page: number;
    limit: number;
    totalPages: number;
}

export interface AvailabilityResponse {
    available: boolean;
    unavailableDates: string[];
}

export interface SearchSuggestionsResponse {
    suggestions: string[];
}

export interface SearchHistoryResponse {
    history: string[];
}

// Mock data for search history and suggestions (since this is a single hotel)
const mockSearchHistory: string[] = [
    "Deluxe Room",
    "Premium Corner Room",
    "The Level Premium Room",
    "Suite",
    "Phòng view thành phố"
];

const mockSearchSuggestions: string[] = [
    "Deluxe Room",
    "Premium Corner Room",
    "The Level Premium Room",
    "Phòng Loại Sang",
    "Phòng cao cấp trong góc",
    "Phòng The Level Cao cấp",
    "Suite",
    "View thành phố",
    "Phòng đôi",
    "Phòng gia đình"
];

// Simulate API delay
const simulateDelay = (ms: number = 500): Promise<void> => {
    return new Promise(resolve => setTimeout(resolve, ms));
};

// Mock search service class
class MockSearchService {
    // Search for rooms in single hotel
    async searchRooms(searchData: SearchData, page: number = 1, limit: number = 10): Promise<SearchResponse> {
        await simulateDelay(800);

        let filteredRooms = [...sampleRooms];

        // For single hotel, we don't filter by location since all rooms are in the same hotel
        // Instead, we can filter by room name/type if location field is used as room search
        if (searchData.location && searchData.location.trim()) {
            const searchTerm = searchData.location.toLowerCase();
            filteredRooms = filteredRooms.filter(room =>
                room.name.toLowerCase().includes(searchTerm) ||
                room.roomType.toLowerCase().includes(searchTerm)
            );
        }        // Filter by guest count - removed hard filtering to show all rooms
        // Capacity warnings will be handled by room options themselves
        // if (searchData.guestDetails) {
        //     const totalGuests = searchData.guestDetails.adults + searchData.guestDetails.children;
        //     filteredRooms = filteredRooms.filter(room => room.maxGuests >= totalGuests);
        // }        // Filter by availability (simplified for single hotel)
        if (searchData.dateRange && searchData.dateRange.length === 2) {
            // For single hotel, we assume rooms are available unless specifically unavailable
            // You can add more complex availability logic here if needed
            // Removed availableRooms filter to show all rooms
        }

        // Filter by guest type preferences (adjusted for single hotel room types)
        if (searchData.guestType) {
            switch (searchData.guestType) {
                case 'business':
                    filteredRooms = filteredRooms.filter(room =>
                        room.roomType === 'deluxe' ||
                        room.roomType === 'premium' ||
                        room.amenities.includes('Bàn làm việc') ||
                        room.amenities.includes('WiFi')
                    );
                    break;
                case 'couple':
                    filteredRooms = filteredRooms.filter(room =>
                        room.roomType === 'suite' ||
                        room.roomType === 'presidential' ||
                        room.roomType === 'theLevel' ||
                        room.maxGuests <= 2
                    );
                    break;
                case 'solo':
                    filteredRooms = filteredRooms.filter(room =>
                        room.roomType === 'deluxe' ||
                        room.maxGuests <= 2
                    );
                    break;
                case 'family_young':
                    filteredRooms = filteredRooms.filter(room =>
                        room.roomType === 'suite' ||
                        room.roomType === 'presidential' ||
                        room.maxGuests >= 4
                    );
                    break;
                case 'group':
                    filteredRooms = filteredRooms.filter(room =>
                        room.roomType === 'suite' ||
                        room.roomType === 'presidential' ||
                        room.maxGuests >= 6
                    );
                    break;
            }
        }

        // Pagination
        const total = filteredRooms.length;
        const totalPages = Math.ceil(total / limit);
        const startIndex = (page - 1) * limit;
        const endIndex = startIndex + limit;
        const paginatedRooms = filteredRooms.slice(startIndex, endIndex);

        return {
            rooms: paginatedRooms,
            total,
            page,
            limit,
            totalPages
        };
    }    // Check room availability
    async checkAvailability(
        roomId: string,
        _checkIn: string,
        _checkOut: string
    ): Promise<AvailabilityResponse> {
        await simulateDelay(300);

        const room = sampleRooms.find((r: Room) => r.id.toString() === roomId);
        if (!room) {
            throw new Error('Room not found');
        }        // For single hotel, simplified availability check
        // In real implementation, this would check actual booking database
        // Always assume available for now
        const isAvailable = true;

        return {
            available: isAvailable,
            unavailableDates: [] // For simplified implementation
        };
    }

    // Get search suggestions
    async getSearchSuggestions(query: string): Promise<SearchSuggestionsResponse> {
        await simulateDelay(200);

        if (!query || query.length < 2) {
            return { suggestions: [] };
        }

        const queryLower = query.toLowerCase();
        const filteredSuggestions = mockSearchSuggestions.filter((suggestion: string) =>
            suggestion.toLowerCase().includes(queryLower)
        );

        return {
            suggestions: filteredSuggestions.slice(0, 8) // Limit to 8 suggestions
        };
    }

    // Get search history
    async getSearchHistory(): Promise<SearchHistoryResponse> {
        await simulateDelay(100);

        // In a real app, this would come from user's stored history
        return {
            history: mockSearchHistory.slice(0, 5) // Last 5 searches
        };
    }

    // Save search to history
    async saveSearchToHistory(location: string): Promise<void> {
        await simulateDelay(100);

        // In a real app, this would save to user's history
        console.log(`Saving search to history: ${location}`);

        // Mock implementation - add to front of array if not already there
        const index = mockSearchHistory.indexOf(location);
        if (index > -1) {
            mockSearchHistory.splice(index, 1);
        }
        mockSearchHistory.unshift(location);

        // Keep only last 10 searches
        if (mockSearchHistory.length > 10) {
            mockSearchHistory.splice(10);
        }
    }

    // Clear search history
    async clearSearchHistory(): Promise<void> {
        await simulateDelay(100);

        // In a real app, this would clear user's history
        console.log('Clearing search history');
        mockSearchHistory.length = 0;
    }
}

// Export singleton instance
export const mockSearchService = new MockSearchService();

// Export types for use in components
export type { Room };
