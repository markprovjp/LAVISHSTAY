// Search service with Mirage.js integration for development
import { SearchData } from '../store/slices/searchSlice';

// Import mock service for fallback
import { mockSearchService } from './mockSearchService';
import type {
    SearchResponse,
    AvailabilityResponse,
    SearchSuggestionsResponse,
    SearchHistoryResponse
} from './mockSearchService';

// Configuration
const USE_MIRAGE_API = true; // Set to false to use mockSearchService directly
const API_BASE_URL = '/api'; // Mirage intercepts this

// Room interface from Mirage (adjusted for single hotel)
interface MirageRoom {
    id: number;
    name: string;
    roomType: string;
    description: string;
    priceVND: number;
    amenities: string[];
    images: string[];
    maxGuests: number;
    discount?: number;
}

// Search service class
class SearchService {
    // Search for rooms using Mirage API
    async searchRooms(searchData: SearchData, page: number = 1, limit: number = 10): Promise<SearchResponse> {
        if (!USE_MIRAGE_API) {
            return mockSearchService.searchRooms(searchData, page, limit);
        }

        try {
            // Fetch all rooms from Mirage
            const response = await fetch(`${API_BASE_URL}/rooms`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            } const data = await response.json();
            let filteredRooms = data.rooms || [];            // Apply client-side filtering to match search criteria
            filteredRooms = this.filterRoomsBySearchData(filteredRooms, searchData);

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
        } catch (error) {
            console.error('Error fetching rooms from Mirage:', error);
            // Fallback to mock service
            return mockSearchService.searchRooms(searchData, page, limit);
        }
    }    // Helper method to filter rooms based on search criteria (adjusted for single hotel)
    private filterRoomsBySearchData(rooms: MirageRoom[], searchData: SearchData): MirageRoom[] {
        let filtered = [...rooms];

        // Filter by location field (used as room search in single hotel context)
        if (searchData.location && searchData.location.trim()) {
            const searchTerm = searchData.location.toLowerCase();
            filtered = filtered.filter(room =>
                room.name.toLowerCase().includes(searchTerm) ||
                room.roomType.toLowerCase().includes(searchTerm)
            );
        }        // DON'T FILTER by guest count - just prioritize in sorting later
        // Allow all rooms to be shown, warnings will be handled in dynamic pricing
        // The filtering below was preventing 5+ guests from seeing any rooms

        // const totalGuests = searchData.guestDetails.adults + searchData.guestDetails.children;
        // filtered = filtered.filter(room => room.maxGuests >= totalGuests);

        // Instead of filtering, we'll store guest count for prioritization
        if (searchData.guestDetails) {
            const totalGuests = searchData.guestDetails.adults + searchData.guestDetails.children;
            // Add guest count as property for later sorting, but don't filter
            filtered = filtered.map(room => ({
                ...room,
                _searchGuestCount: totalGuests
            }));
        }        // DON'T FILTER by guest type preferences - show all rooms with prioritization
        // This allows users to see all available options and make informed decisions
        // Warnings and calculations will be handled in the room options

        if (searchData.guestType) {
            // Instead of filtering, we'll add preference scoring for sorting
            switch (searchData.guestType) {
                case 'business':
                    filtered = filtered.map(room => ({
                        ...room,
                        _preferenceScore: (room.roomType === 'deluxe' || room.roomType === 'premium') ? 10 :
                            room.amenities.some(amenity =>
                                amenity.toLowerCase().includes('wifi') ||
                                amenity.toLowerCase().includes('work') ||
                                amenity.toLowerCase().includes('desk') ||
                                amenity.toLowerCase().includes('bàn làm việc')
                            ) ? 5 : 0
                    }));
                    break;
                case 'couple':
                    filtered = filtered.map(room => ({
                        ...room,
                        _preferenceScore: (room.roomType === 'suite' || room.roomType === 'presidential' || room.roomType === 'theLevel') ? 10 :
                            room.maxGuests <= 2 ? 8 : 3
                    }));
                    break;
                case 'solo':
                    filtered = filtered.map(room => ({
                        ...room,
                        _preferenceScore: room.roomType === 'deluxe' ? 10 :
                            room.maxGuests <= 2 ? 8 : 3
                    }));
                    break;
                case 'family_young':
                    filtered = filtered.map(room => ({
                        ...room,
                        _preferenceScore: (room.roomType === 'suite' || room.roomType === 'presidential') ? 10 :
                            room.maxGuests >= 4 ? 8 : 5
                    }));
                    break;
                case 'group':
                    filtered = filtered.map(room => ({
                        ...room,
                        _preferenceScore: room.roomType === 'presidential' ? 10 :
                            room.roomType === 'suite' ? 8 :
                                room.maxGuests >= 4 ? 6 : 3
                    }));
                    break;
                default:
                    filtered = filtered.map(room => ({
                        ...room,
                        _preferenceScore: 5 // Default neutral score
                    }));
                    break;
            }
        } else {
            // No guest type specified, give all rooms equal preference
            filtered = filtered.map(room => ({
                ...room,
                _preferenceScore: 5
            }));
        }

        // Sort rooms by preference and capacity match
        filtered.sort((a, b) => {
            const aPreference = (a as any)._preferenceScore || 5;
            const bPreference = (b as any)._preferenceScore || 5;
            const aGuestCount = (a as any)._searchGuestCount || 2;

            // First priority: preference score
            if (aPreference !== bPreference) {
                return bPreference - aPreference; // Higher preference first
            }

            // Second priority: capacity match (for guest count)
            if (aGuestCount > 0) {
                const aMatch = a.maxGuests >= aGuestCount;
                const bMatch = b.maxGuests >= aGuestCount;

                if (aMatch && !bMatch) return -1;
                if (!aMatch && bMatch) return 1;

                // If both match or both don't match, prefer Presidential (higher capacity)
                if (a.roomType === 'presidential' && b.roomType !== 'presidential') return -1;
                if (a.roomType !== 'presidential' && b.roomType === 'presidential') return 1;
            }

            // Third priority: price (ascending)
            return a.priceVND - b.priceVND;
        });

        // Clean up temporary properties
        filtered = filtered.map(room => {
            const { _preferenceScore, _searchGuestCount, ...cleanRoom } = room as any;
            return cleanRoom;
        });

        return filtered;
    }// Check room availability
    async checkAvailability(
        roomId: string,
        checkIn: string,
        checkOut: string
    ): Promise<AvailabilityResponse> {
        if (!USE_MIRAGE_API) {
            return mockSearchService.checkAvailability(roomId, checkIn, checkOut);
        }

        try {
            // For Mirage, we'll use the mock service logic since Mirage doesn't have
            // a specific availability endpoint yet
            return mockSearchService.checkAvailability(roomId, checkIn, checkOut);
        } catch (error) {
            console.error('Error checking availability:', error);
            // Fallback to mock service
            return mockSearchService.checkAvailability(roomId, checkIn, checkOut);
        }
    }    // Get search suggestions (adjusted for single hotel room types)
    async getSearchSuggestions(query: string): Promise<SearchSuggestionsResponse> {
        if (!USE_MIRAGE_API) {
            return mockSearchService.getSearchSuggestions(query);
        }

        try {
            // Generate suggestions based on available room types and names from Mirage
            const response = await fetch(`${API_BASE_URL}/rooms`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            const rooms = data.rooms || [];

            if (!query || query.length < 2) {
                return { suggestions: [] };
            }

            const queryLower = query.toLowerCase();
            const roomSuggestions = new Set<string>();

            // Extract unique room names and types that match the query
            rooms.forEach((room: MirageRoom) => {
                if (room.name && room.name.toLowerCase().includes(queryLower)) {
                    roomSuggestions.add(room.name);
                }
                if (room.roomType && room.roomType.toLowerCase().includes(queryLower)) {
                    roomSuggestions.add(room.roomType);
                }
            });

            const suggestions = Array.from(roomSuggestions).slice(0, 8);
            return { suggestions };
        } catch (error) {
            console.error('Error fetching search suggestions:', error);
            // Fallback to mock service
            return mockSearchService.getSearchSuggestions(query);
        }
    }

    // Get search history - delegate to mock service for now
    async getSearchHistory(): Promise<SearchHistoryResponse> {
        return mockSearchService.getSearchHistory();
    }

    // Save search to history - delegate to mock service for now
    async saveSearchToHistory(location: string): Promise<void> {
        return mockSearchService.saveSearchToHistory(location);
    }

    // Clear search history - delegate to mock service for now
    async clearSearchHistory(): Promise<void> {
        return mockSearchService.clearSearchHistory();
    }
}

// Export singleton instance
export const searchService = new SearchService();

// Re-export types from mock service for convenience
export type {
    SearchResponse,
    AvailabilityResponse,
    SearchSuggestionsResponse,
    SearchHistoryResponse
} from './mockSearchService';
