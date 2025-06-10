import { createSlice, PayloadAction } from '@reduxjs/toolkit';

// Types for booking
export interface BookingRoomOption {
    id: string;
    name: string;
    pricePerNight: {
        vnd: number;
    };
    maxGuests: number;
    minGuests: number;
    roomType: "deluxe" | "premium" | "suite" | "presidential" | "theLevel";
    cancellationPolicy: {
        type: "free" | "non_refundable" | "conditional";
        freeUntil?: string;
        penalty?: number;
        description: string;
    };
    paymentPolicy: {
        type: "pay_now_with_vietQR" | "pay_at_hotel";
        description: string;
        prepaymentRequired?: boolean;
    };
    availability: {
        total: number;
        remaining: number;
        urgencyMessage?: string;
    };
    additionalServices?: {
        icon: string;
        name: string;
        price?: string;
        included: boolean;
    }[];
    promotion?: {
        type: "hot" | "limited" | "member" | "lowest" | "deal";
        message: string;
        discount?: number;
    };
    recommended?: boolean;
    mostPopular?: boolean;
    dynamicPricing?: {
        basePrice: number;
        finalPrice: number;
        adjustments: Array<{
            factor: number;
            reason: string;
            type: 'increase' | 'decrease';
        }>;
        savings: number;
        urgencyLevel: 'low' | 'medium' | 'high' | 'urgent';
        recommendationScore: number;
    };
}

export interface BookingRoom {
    id: number;
    name: string;
    image: string;
    images?: string[]; // Add images array for multiple room images
    size?: number;
    view?: string;
    bedType?: string;
    amenities?: string[];
    mainAmenities?: string[];
    roomType: "deluxe" | "premium" | "suite" | "presidential" | "theLevel";
    rating?: number;
    maxGuests?: number;
    availableRooms?: number;
    options: BookingRoomOption[];
}

export interface SelectedRoom {
    roomId: string;
    optionId: string;
    quantity: number;
    room: BookingRoom;
    option: BookingRoomOption;
    pricePerNight: number;
    totalPrice: number;
}

export interface BookingPreferences {
    breakfastOption: 'none' | 'standard' | 'premium';
    bedPreference: 'double' | 'single';
    specialRequests?: string;
}

export interface BookingState {
    // Selected rooms with quantities
    selectedRooms: { [roomId: string]: { [optionId: string]: number } };

    // Room data cache
    roomsData: BookingRoom[];

    // Booking preferences
    preferences: BookingPreferences;

    // Calculated totals
    totals: {
        roomsTotal: number;
        breakfastTotal: number;
        finalTotal: number;
        nights: number;
    };

    // Booking progress
    currentStep: 'selection' | 'summary' | 'payment' | 'confirmation';

    // Loading states
    isLoading: boolean;
    error: string | null;

    // Last updated timestamp
    lastUpdated?: string;
}

const initialState: BookingState = {
    selectedRooms: {},
    roomsData: [],
    preferences: {
        breakfastOption: 'none',
        bedPreference: 'double',
        specialRequests: '',
    },
    totals: {
        roomsTotal: 0,
        breakfastTotal: 0,
        finalTotal: 0,
        nights: 0,
    },
    currentStep: 'selection',
    isLoading: false,
    error: null,
};

// Helper function to calculate totals
const calculateTotals = (
    selectedRooms: BookingState['selectedRooms'],
    roomsData: BookingRoom[],
    preferences: BookingPreferences,
    nights: number,
    guestCount: number
) => {
    let roomsTotal = 0;

    // Calculate rooms total
    Object.entries(selectedRooms).forEach(([roomId, options]) => {
        const room = roomsData.find(r => r.id.toString() === roomId);
        if (!room) return;

        Object.entries(options).forEach(([optionId, quantity]) => {
            if (quantity > 0) {
                const option = room.options.find(opt => opt.id === optionId);
                if (option) {
                    const pricePerNight = option.dynamicPricing?.finalPrice || option.pricePerNight.vnd;
                    roomsTotal += pricePerNight * quantity * nights;
                }
            }
        });
    });

    // Calculate breakfast total
    let breakfastTotal = 0;
    switch (preferences.breakfastOption) {
        case 'standard':
            breakfastTotal = 260000 * guestCount * nights;
            break;
        case 'premium':
            breakfastTotal = 500000 * guestCount * nights;
            break;
        default:
            breakfastTotal = 0;
    }

    const finalTotal = roomsTotal + breakfastTotal;

    return {
        roomsTotal,
        breakfastTotal,
        finalTotal,
        nights,
    };
};

// Helper function to save state to localStorage
const saveStateToStorage = (state: BookingState) => {
    try {
        const dataToSave = {
            selectedRooms: state.selectedRooms,
            preferences: state.preferences,
            totals: state.totals,
            currentStep: state.currentStep,
            lastUpdated: new Date().toISOString(),
        };
        localStorage.setItem('lavishstay_booking_data', JSON.stringify(dataToSave));
    } catch (error) {
        console.error('Error saving booking data to localStorage:', error);
    }
};

// Helper function to load state from localStorage
const loadStateFromStorage = (): Partial<BookingState> => {
    try {
        const savedData = localStorage.getItem('lavishstay_booking_data');
        if (savedData) {
            const parsed = JSON.parse(savedData);
            return {
                selectedRooms: parsed.selectedRooms || {},
                preferences: parsed.preferences || initialState.preferences,
                totals: parsed.totals || initialState.totals,
                currentStep: parsed.currentStep || 'selection',
                lastUpdated: parsed.lastUpdated,
            };
        }
    } catch (error) {
        console.error('Error loading booking data from localStorage:', error);
    }
    return {};
};

// Create booking slice
const bookingSlice = createSlice({
    name: 'booking',
    initialState: {
        ...initialState,
        ...loadStateFromStorage(),
    },
    reducers: {
        // Set rooms data
        setRoomsData: (state, action: PayloadAction<BookingRoom[]>) => {
            state.roomsData = action.payload;
            saveStateToStorage(state);
        },

        // Add or update room selection
        updateRoomSelection: (
            state,
            action: PayloadAction<{
                roomId: string;
                optionId: string;
                quantity: number;
                nights: number;
                guestCount: number;
            }>
        ) => {
            const { roomId, optionId, quantity, nights, guestCount } = action.payload;

            if (!state.selectedRooms[roomId]) {
                state.selectedRooms[roomId] = {};
            }

            if (quantity <= 0) {
                delete state.selectedRooms[roomId][optionId];
                if (Object.keys(state.selectedRooms[roomId]).length === 0) {
                    delete state.selectedRooms[roomId];
                }
            } else {
                state.selectedRooms[roomId][optionId] = quantity;
            }

            // Recalculate totals
            state.totals = calculateTotals(
                state.selectedRooms,
                state.roomsData,
                state.preferences,
                nights,
                guestCount
            );

            state.lastUpdated = new Date().toISOString();
            saveStateToStorage(state);
        },

        // Remove room selection
        removeRoomSelection: (
            state,
            action: PayloadAction<{
                roomId: string;
                optionId: string;
                nights: number;
                guestCount: number;
            }>
        ) => {
            const { roomId, optionId, nights, guestCount } = action.payload;

            if (state.selectedRooms[roomId]) {
                delete state.selectedRooms[roomId][optionId];
                if (Object.keys(state.selectedRooms[roomId]).length === 0) {
                    delete state.selectedRooms[roomId];
                }
            }

            // Recalculate totals
            state.totals = calculateTotals(
                state.selectedRooms,
                state.roomsData,
                state.preferences,
                nights,
                guestCount
            );

            state.lastUpdated = new Date().toISOString();
            saveStateToStorage(state);
        },

        // Update preferences
        updatePreferences: (
            state,
            action: PayloadAction<{
                preferences: Partial<BookingPreferences>;
                nights: number;
                guestCount: number;
            }>
        ) => {
            const { preferences, nights, guestCount } = action.payload;
            state.preferences = { ...state.preferences, ...preferences };

            // Recalculate totals when preferences change
            state.totals = calculateTotals(
                state.selectedRooms,
                state.roomsData,
                state.preferences,
                nights,
                guestCount
            );

            state.lastUpdated = new Date().toISOString();
            saveStateToStorage(state);
        },

        // Set current step
        setCurrentStep: (state, action: PayloadAction<BookingState['currentStep']>) => {
            state.currentStep = action.payload;
            saveStateToStorage(state);
        },

        // Recalculate totals (useful when search data changes)
        recalculateTotals: (
            state,
            action: PayloadAction<{
                nights: number;
                guestCount: number;
            }>
        ) => {
            const { nights, guestCount } = action.payload;
            state.totals = calculateTotals(
                state.selectedRooms,
                state.roomsData,
                state.preferences,
                nights,
                guestCount
            );
            saveStateToStorage(state);
        },

        // Clear all booking data
        clearBookingData: (state) => {
            Object.assign(state, initialState);
            localStorage.removeItem('lavishstay_booking_data');
        },

        // Set loading state
        setLoading: (state, action: PayloadAction<boolean>) => {
            state.isLoading = action.payload;
        },

        // Set error
        setError: (state, action: PayloadAction<string | null>) => {
            state.error = action.payload;
            state.isLoading = false;
        },

        // Clear error
        clearError: (state) => {
            state.error = null;
        },
    },
});

// Export actions
export const {
    setRoomsData,
    updateRoomSelection,
    removeRoomSelection,
    updatePreferences,
    setCurrentStep,
    recalculateTotals,
    clearBookingData,
    setLoading,
    setError,
    clearError,
} = bookingSlice.actions;

// Selectors
export const selectBookingState = (state: { booking: BookingState }) => state.booking;
export const selectSelectedRooms = (state: { booking: BookingState }) => state.booking.selectedRooms;
export const selectRoomsData = (state: { booking: BookingState }) => state.booking.roomsData;
export const selectBookingPreferences = (state: { booking: BookingState }) => state.booking.preferences;
export const selectBookingTotals = (state: { booking: BookingState }) => state.booking.totals;
export const selectCurrentStep = (state: { booking: BookingState }) => state.booking.currentStep;
export const selectBookingLoading = (state: { booking: BookingState }) => state.booking.isLoading;
export const selectBookingError = (state: { booking: BookingState }) => state.booking.error;

// Complex selectors
export const selectSelectedRoomsSummary = (state: { booking: BookingState }) => {
    const { selectedRooms, roomsData } = state.booking;
    const summary: SelectedRoom[] = [];

    Object.entries(selectedRooms).forEach(([roomId, options]) => {
        const room = roomsData.find(r => r.id.toString() === roomId);
        if (!room) return;

        Object.entries(options).forEach(([optionId, quantity]) => {
            if (quantity > 0) {
                const option = room.options.find(opt => opt.id === optionId);
                if (option) {
                    const pricePerNight = option.dynamicPricing?.finalPrice || option.pricePerNight.vnd;
                    const totalPrice = pricePerNight * quantity * state.booking.totals.nights;

                    summary.push({
                        roomId,
                        optionId,
                        quantity,
                        room,
                        option,
                        pricePerNight,
                        totalPrice,
                    });
                }
            }
        });
    });

    return summary;
};

export const selectHasSelectedRooms = (state: { booking: BookingState }) => {
    return Object.keys(state.booking.selectedRooms).length > 0;
};

export const selectSelectedRoomsCount = (state: { booking: BookingState }) => {
    let count = 0;
    Object.values(state.booking.selectedRooms).forEach(options => {
        Object.values(options).forEach(quantity => {
            count += quantity;
        });
    });
    return count;
};

export default bookingSlice.reducer;
