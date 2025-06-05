import { createSlice, PayloadAction } from '@reduxjs/toolkit';
import { Dayjs } from 'dayjs';
import dayjs from 'dayjs';

// Types for search data
export interface GuestDetails {
    adults: number;
    children: number;
}

export interface SearchData {
    location?: string;
    dateRange: [Dayjs, Dayjs] | null;
    checkIn?: string;
    checkOut?: string;
    guests: number;
    guestDetails: GuestDetails;
    guestType: "business" | "couple" | "solo" | "family_young" | "group";
    searchDate?: string;
    isLoading?: boolean;
    error?: string | null;
}

// Search state interface
interface SearchState extends SearchData {
    isLoading: boolean;
    error: string | null;
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
    },
    guestType: 'couple',
    searchDate: undefined,
    isLoading: false,
    error: null,
};

// Helper function to safely parse dates from localStorage
const parseStoredDateRange = (storedDateRange: any): [Dayjs, Dayjs] | null => {
    try {
        if (storedDateRange && Array.isArray(storedDateRange) && storedDateRange.length === 2) {
            const [startDate, endDate] = storedDateRange;
            const start = dayjs(startDate);
            const end = dayjs(endDate);

            // Validate dates
            if (start.isValid() && end.isValid() && start.isBefore(end)) {
                return [start, end];
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
            // Convert dateRange to ISO strings for storage
            dateRange: state.dateRange ? [
                state.dateRange[0].format('YYYY-MM-DD'),
                state.dateRange[1].format('YYYY-MM-DD'),
            ] : null,
            // Add checkIn and checkOut as string values
            checkIn: state.checkIn,
            checkOut: state.checkOut,
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
            // Convert strings to Dayjs objects for internal state
            if (action.payload) {
                const [startStr, endStr] = action.payload;
                state.dateRange = [dayjs(startStr), dayjs(endStr)];
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
                    state.guestDetails = { adults: 1, children: 0 };
                    state.guests = 1;
                    break;
                case 'couple':
                    state.guestDetails = { adults: 2, children: 0 };
                    state.guests = 2;
                    break;
                case 'family_young':
                    state.guestDetails = { adults: 2, children: 1 };
                    state.guests = 3;
                    break;
                case 'group':
                    state.guestDetails = { adults: 4, children: 0 };
                    state.guests = 4;
                    break;
            }

            state.searchDate = new Date().toISOString();
            state.error = null;
            saveStateToStorage(state);
        },

        updateGuestDetails: (state, action: PayloadAction<Partial<GuestDetails>>) => {
            state.guestDetails = { ...state.guestDetails, ...action.payload };
            state.guests = state.guestDetails.adults + state.guestDetails.children;
            state.searchDate = new Date().toISOString();
            state.error = null;
            saveStateToStorage(state);
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

        resetError: (state) => {
            state.error = null;
        },
    },
});

// Export actions
export const {
    updateSearchData,
    setDateRange,
    setGuestType,
    updateGuestDetails,
    setLoading,
    setError,
    clearSearchData,
    resetError,
} = searchSlice.actions;

// Selectors
export const selectSearchData = (state: { search: SearchState }) => state.search;
export const selectLocation = (state: { search: SearchState }) => state.search.location;
export const selectDateRange = (state: { search: SearchState }) => state.search.dateRange;
export const selectCheckIn = (state: { search: SearchState }) => state.search.checkIn;
export const selectCheckOut = (state: { search: SearchState }) => state.search.checkOut;
export const selectGuestDetails = (state: { search: SearchState }) => state.search.guestDetails;
export const selectGuestType = (state: { search: SearchState }) => state.search.guestType;
export const selectIsLoading = (state: { search: SearchState }) => state.search.isLoading;
export const selectError = (state: { search: SearchState }) => state.search.error;
export const selectHasSearchData = (state: { search: SearchState }) => {
    const { dateRange, guests } = state.search;
    return Boolean(dateRange && guests > 0);
};

export default searchSlice.reducer;
