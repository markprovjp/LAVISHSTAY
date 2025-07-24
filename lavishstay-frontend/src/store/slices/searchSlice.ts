import { createSlice, PayloadAction } from '@reduxjs/toolkit';
import dayjs from 'dayjs';

// Types for search data
export interface ChildAgeInfo {
    age: number;
    id: string; // unique identifier for each child
}

export interface RoomGuests {
    adults: number;
    children: number;
    childrenAges: ChildAgeInfo[]; // Array to store each child's age
}

export interface GuestDetails {
    adults: number;
    children: number;
    childrenAges: ChildAgeInfo[]; // Array to store each child's age
    rooms: RoomGuests[]; // Array of room configurations
    totalRooms: number;
}

export interface SearchData {
    location?: string;
    dateRange: [string, string] | null; // Changed to string for serialization
    checkIn?: string;
    checkOut?: string;
    guests: number;
    guestDetails: GuestDetails; // legacy, keep for compatibility
    guestType: "business" | "couple" | "solo" | "family_young" | "group"; // legacy
    rooms: RoomGuests[]; // New room-based structure
    totalRooms: number; // New total rooms count
    searchDate?: string;
    isLoading?: boolean;
    error?: string | null;
}

// Search state interface
interface SearchState extends SearchData {
    isLoading: boolean;
    error: string | null;
    searchResults: any | null; // Store search results
    hasSearched: boolean; // Track if search has been performed
}

// Initial state
const initialState: SearchState = {
    location: '',
    dateRange: null,
    checkIn: undefined,
    checkOut: undefined,
    guests: 2,
    guestDetails: {
        adults: 2,
        children: 0,
        childrenAges: [],
        rooms: [
            {
                adults: 2,
                children: 0,
                childrenAges: []
            }
        ],
        totalRooms: 1
    },
    rooms: [
        {
            adults: 2,
            children: 0,
            childrenAges: []
        }
    ],
    totalRooms: 1,
    guestType: 'couple',
    searchDate: undefined,
    isLoading: false,
    error: null,
    searchResults: null,
    hasSearched: false,
};

// Helper function to safely parse dates from localStorage
const parseStoredDateRange = (storedDateRange: any): [string, string] | null => {
    try {
        if (storedDateRange && Array.isArray(storedDateRange) && storedDateRange.length === 2) {
            const [startDate, endDate] = storedDateRange;
            const start = dayjs(startDate);
            const end = dayjs(endDate);

            // Validate dates
            if (start.isValid() && end.isValid() && start.isBefore(end)) {
                return [start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD')];
            }
        }
    } catch (error) {
        console.error('Error parsing stored date range:', error);
    }
    return null;
};

// Helper function to load state from localStorage
const loadStateFromStorage = (): Partial<SearchState> => {
    try {
        const savedData = localStorage.getItem('lavishstay_search_data');
        if (savedData) {
            const parsed = JSON.parse(savedData);

            // Parse and validate date range
            const dateRange = parseStoredDateRange(parsed.dateRange);

            return {
                ...parsed,
                dateRange,
                isLoading: false,
                error: null,
            };
        }
    } catch (error) {
        console.error('Error loading search data from localStorage:', error);
    }
    return {};
};

// Helper function to save state to localStorage
const saveStateToStorage = (state: SearchState) => {
    try {
        const dataToSave = {
            ...state,
            // dateRange is already string array, no conversion needed
            // Don't save loading and error states
            isLoading: undefined,
            error: undefined,
        };
        localStorage.setItem('lavishstay_search_data', JSON.stringify(dataToSave));

        // Clean up old localStorage key if it exists
        if (localStorage.getItem('searchData')) {
            localStorage.removeItem('searchData');
        }
    } catch (error) {
        console.error('Error saving search data to localStorage:', error);
    }
};

// Create search slice
const searchSlice = createSlice({
    name: 'search',
    initialState: {
        ...initialState,
        ...loadStateFromStorage(),
    },
    reducers: {
        updateSearchData: (state, action: PayloadAction<Partial<SearchData>>) => {
            const newData = action.payload;

            // Update state
            Object.assign(state, newData);

            // Update searchDate when any data changes
            state.searchDate = new Date().toISOString();

            // Clear any previous errors
            state.error = null;

            // Save to localStorage
            saveStateToStorage(state);
        }, setDateRange: (state, action: PayloadAction<[string, string] | null>) => {
            // Store as string array for serialization
            if (action.payload) {
                const [startStr, endStr] = action.payload;
                state.dateRange = [startStr, endStr];
                state.checkIn = startStr;
                state.checkOut = endStr;
            } else {
                state.dateRange = null;
                state.checkIn = undefined;
                state.checkOut = undefined;
            }
            state.searchDate = new Date().toISOString();
            state.error = null;
            saveStateToStorage(state);
        },

        setGuestType: (state, action: PayloadAction<SearchData['guestType']>) => {
            const guestType = action.payload;
            state.guestType = guestType;

            // Auto-update guest details based on type
            switch (guestType) {
                case 'business':
                case 'solo':
                    state.guestDetails = {
                        adults: 1,
                        children: 0,
                        childrenAges: [],
                        rooms: [{ adults: 1, children: 0, childrenAges: [] }],
                        totalRooms: 1
                    };
                    state.rooms = [{ adults: 1, children: 0, childrenAges: [] }];
                    state.totalRooms = 1;
                    state.guests = 1;
                    break;
                case 'couple':
                    state.guestDetails = {
                        adults: 2,
                        children: 0,
                        childrenAges: [],
                        rooms: [{ adults: 2, children: 0, childrenAges: [] }],
                        totalRooms: 1
                    };
                    state.rooms = [{ adults: 2, children: 0, childrenAges: [] }];
                    state.totalRooms = 1;
                    state.guests = 2;
                    break;
                case 'family_young':
                    state.guestDetails = {
                        adults: 2,
                        children: 1,
                        childrenAges: [{ age: 5, id: 'child_1' }],
                        rooms: [{ adults: 2, children: 1, childrenAges: [{ age: 5, id: 'child_1' }] }],
                        totalRooms: 1
                    };
                    state.rooms = [{ adults: 2, children: 1, childrenAges: [{ age: 5, id: 'child_1' }] }];
                    state.totalRooms = 1;
                    state.guests = 3;
                    break;
                case 'group':
                    state.guestDetails = {
                        adults: 4,
                        children: 0,
                        childrenAges: [],
                        rooms: [
                            { adults: 2, children: 0, childrenAges: [] },
                            { adults: 2, children: 0, childrenAges: [] }
                        ],
                        totalRooms: 2
                    };
                    state.rooms = [
                        { adults: 2, children: 0, childrenAges: [] },
                        { adults: 2, children: 0, childrenAges: [] }
                    ];
                    state.totalRooms = 2;
                    state.guests = 4;
                    break;
            }

            state.searchDate = new Date().toISOString();
            state.error = null;
            saveStateToStorage(state);
        }, updateGuestDetails: (state, action: PayloadAction<Partial<GuestDetails>>) => {
            const updatedDetails = { ...state.guestDetails, ...action.payload };

            // If children count changes, adjust childrenAges array
            if (action.payload.children !== undefined) {
                const newChildrenCount = action.payload.children;
                const currentAges = state.guestDetails.childrenAges || [];

                if (newChildrenCount > currentAges.length) {
                    // Add new children with default age
                    const newAges = [...currentAges];
                    for (let i = currentAges.length; i < newChildrenCount; i++) {
                        newAges.push({ age: 5, id: `child_${i + 1}` });
                    }
                    updatedDetails.childrenAges = newAges;
                } else if (newChildrenCount < currentAges.length) {
                    // Remove excess children
                    updatedDetails.childrenAges = currentAges.slice(0, newChildrenCount);
                }
            }

            state.guestDetails = updatedDetails;
            state.guests = state.guestDetails.adults + state.guestDetails.children;
            state.searchDate = new Date().toISOString();
            state.error = null;
            saveStateToStorage(state);
        },

        updateChildAge: (state, action: PayloadAction<{ childId: string; age: number }>) => {
            const { childId, age } = action.payload;
            const childIndex = state.guestDetails.childrenAges.findIndex(child => child.id === childId);

            if (childIndex !== -1) {
                state.guestDetails.childrenAges[childIndex].age = age;
                state.searchDate = new Date().toISOString();
                state.error = null;
                saveStateToStorage(state);
            }
        },

        setLoading: (state, action: PayloadAction<boolean>) => {
            state.isLoading = action.payload;
        },

        setError: (state, action: PayloadAction<string | null>) => {
            state.error = action.payload;
            state.isLoading = false;
        }, clearSearchData: (state) => {
            Object.assign(state, initialState);
            localStorage.removeItem('lavishstay_search_data');
            // Also clean up old localStorage key if it exists
            if (localStorage.getItem('searchData')) {
                localStorage.removeItem('searchData');
            }
        },

        resetSearchForm: (state) => {
            // Reset to default values but keep tomorrow + 1 night as default
            const tomorrow = dayjs().add(1, 'day');
            const dayAfter = tomorrow.add(1, 'day');

            state.dateRange = [tomorrow.format('YYYY-MM-DD'), dayAfter.format('YYYY-MM-DD')];
            state.checkIn = tomorrow.format('YYYY-MM-DD');
            state.checkOut = dayAfter.format('YYYY-MM-DD');
            state.guestDetails = {
                adults: 2,
                children: 0,
                childrenAges: [],
                rooms: [{ adults: 2, children: 0, childrenAges: [] }],
                totalRooms: 1
            };
            state.rooms = [{ adults: 2, children: 0, childrenAges: [] }];
            state.totalRooms = 1;
            state.guests = 2;
            state.location = '';
            state.guestType = 'couple';
            state.searchDate = new Date().toISOString();
            state.error = null;

            // Save to localStorage
            saveStateToStorage(state);
        },

        resetError: (state) => {
            state.error = null;
        },

        // New actions for multiple rooms management
        addRoom: (state) => {
            if (state.totalRooms < 6) {
                state.rooms.push({
                    adults: 2,
                    children: 0,
                    childrenAges: []
                });
                state.totalRooms += 1;

                // Recalculate totals
                const totals = state.rooms.reduce(
                    (acc, room) => ({
                        adults: acc.adults + room.adults,
                        children: acc.children + room.children
                    }),
                    { adults: 0, children: 0 }
                );

                state.guests = totals.adults + totals.children;

                // Update legacy guestDetails for compatibility
                state.guestDetails.adults = totals.adults;
                state.guestDetails.children = totals.children;
                state.guestDetails.rooms = [...state.rooms];
                state.guestDetails.totalRooms = state.totalRooms;

                // Update childrenAges array
                const allChildrenAges: ChildAgeInfo[] = [];
                state.rooms.forEach(room => {
                    allChildrenAges.push(...room.childrenAges);
                });
                state.guestDetails.childrenAges = allChildrenAges;

                saveStateToStorage(state);
            }
        },

        removeRoom: (state, action: PayloadAction<number>) => {
            const roomIndex = action.payload;
            if (state.totalRooms > 1 && roomIndex >= 0 && roomIndex < state.rooms.length) {
                state.rooms.splice(roomIndex, 1);
                state.totalRooms -= 1;

                // Recalculate totals
                const totals = state.rooms.reduce(
                    (acc, room) => ({
                        adults: acc.adults + room.adults,
                        children: acc.children + room.children
                    }),
                    { adults: 0, children: 0 }
                );

                state.guests = totals.adults + totals.children;

                // Update legacy guestDetails for compatibility
                state.guestDetails.adults = totals.adults;
                state.guestDetails.children = totals.children;
                state.guestDetails.rooms = [...state.rooms];
                state.guestDetails.totalRooms = state.totalRooms;

                // Update childrenAges array
                const allChildrenAges: ChildAgeInfo[] = [];
                state.rooms.forEach(room => {
                    allChildrenAges.push(...room.childrenAges);
                });
                state.guestDetails.childrenAges = allChildrenAges;

                saveStateToStorage(state);
            }
        },

        updateRoomGuests: (state, action: PayloadAction<{
            roomIndex: number;
            type: 'adults' | 'children';
            operation: 'increase' | 'decrease';
        }>) => {
            const { roomIndex, type, operation } = action.payload;
            const room = state.rooms[roomIndex];

            if (!room) return;

            const maxGuestsPerRoom = 6;
            const currentRoomTotal = room.adults + room.children;

            if (operation === 'increase') {
                if (type === 'adults' && currentRoomTotal < maxGuestsPerRoom) {
                    room.adults += 1;
                } else if (type === 'children' && currentRoomTotal < maxGuestsPerRoom) {
                    room.children += 1;
                    // Add default child age
                    room.childrenAges.push({
                        age: 8,
                        id: `room_${roomIndex}_child_${room.children}`
                    });
                }
            } else if (operation === 'decrease') {
                if (type === 'adults' && room.adults > 1) {
                    room.adults -= 1;
                } else if (type === 'children' && room.children > 0) {
                    room.children -= 1;
                    // Remove last child age
                    room.childrenAges.pop();
                }
            }

            // Recalculate totals
            const totals = state.rooms.reduce(
                (acc, r) => ({
                    adults: acc.adults + r.adults,
                    children: acc.children + r.children
                }),
                { adults: 0, children: 0 }
            );

            state.guests = totals.adults + totals.children;

            // Update legacy guestDetails for compatibility
            state.guestDetails.adults = totals.adults;
            state.guestDetails.children = totals.children;
            state.guestDetails.rooms = [...state.rooms];

            // Update global childrenAges array
            const allChildrenAges: ChildAgeInfo[] = [];
            state.rooms.forEach(r => {
                allChildrenAges.push(...r.childrenAges);
            });
            state.guestDetails.childrenAges = allChildrenAges;

            saveStateToStorage(state);
        },

        updateRoomChildAge: (state, action: PayloadAction<{
            roomIndex: number;
            childIndex: number;
            age: number;
        }>) => {
            const { roomIndex, childIndex, age } = action.payload;
            const room = state.rooms[roomIndex];

            if (room && room.childrenAges[childIndex]) {
                room.childrenAges[childIndex].age = age;

                // Update legacy guestDetails for compatibility
                state.guestDetails.rooms = [...state.rooms];

                // Update global childrenAges array
                const allChildrenAges: ChildAgeInfo[] = [];
                state.rooms.forEach(r => {
                    allChildrenAges.push(...r.childrenAges);
                });
                state.guestDetails.childrenAges = allChildrenAges;

                saveStateToStorage(state);
            }
        },

        // Search results management
        setSearchResults: (state, action: PayloadAction<any>) => {
            state.searchResults = action.payload;
            state.hasSearched = true;
            state.error = null;
            state.isLoading = false;
        },

        clearSearchResults: (state) => {
            state.searchResults = null;
            state.hasSearched = false;
            state.error = null;
        },

        setSearchLoading: (state, action: PayloadAction<boolean>) => {
            state.isLoading = action.payload;
            if (action.payload) {
                state.error = null;
            }
        },

        setSearchError: (state, action: PayloadAction<string>) => {
            state.error = action.payload;
            state.isLoading = false;
        },
    },
});

// Export actions
export const {
    updateSearchData,
    setDateRange,
    setGuestType,
    updateGuestDetails,
    updateChildAge,
    setLoading,
    setError,
    clearSearchData,
    resetSearchForm,
    resetError,
    addRoom,
    removeRoom,
    updateRoomGuests,
    updateRoomChildAge,
    setSearchResults,
    clearSearchResults,
    setSearchLoading,
    setSearchError,
} = searchSlice.actions;

// Selectors
export const selectSearchData = (state: { search: SearchState }) => state.search;
export const selectLocation = (state: { search: SearchState }) => state.search.location;
export const selectDateRange = (state: { search: SearchState }) => state.search.dateRange;
export const selectCheckIn = (state: { search: SearchState }) => state.search.checkIn;
export const selectCheckOut = (state: { search: SearchState }) => state.search.checkOut;
export const selectGuestDetails = (state: { search: SearchState }) => state.search.guestDetails;
export const selectGuestType = (state: { search: SearchState }) => state.search.guestType;
export const selectRooms = (state: { search: SearchState }) => state.search.rooms;
export const selectTotalRooms = (state: { search: SearchState }) => state.search.totalRooms;
export const selectIsLoading = (state: { search: SearchState }) => state.search.isLoading;
export const selectError = (state: { search: SearchState }) => state.search.error;
export const selectHasSearchData = (state: { search: SearchState }) => {
    const { dateRange, guests } = state.search;
    return Boolean(dateRange && guests > 0);
};
export const selectSearchResults = (state: { search: SearchState }) => state.search.searchResults;
export const selectHasSearched = (state: { search: SearchState }) => state.search.hasSearched;

export default searchSlice.reducer;
