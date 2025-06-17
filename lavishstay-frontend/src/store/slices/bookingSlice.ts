import { createSlice, PayloadAction, createSelector } from '@reduxjs/toolkit';

// Types for booking
export interface BookingRoomOption {
    id: string;
    name: string;
    pricePerNight: {
        vnd: number;
    };
    maxGuests: number;
    minGuests: number;
    roomType: "deluxe" | "premium" | "suite" | "presidential" | "theLevelPremium" | "theLevelPremiumCorner" | "theLevelSuite";
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
    roomType: "deluxe" | "premium" | "suite" | "presidential" | "theLevelPremium" | "theLevelPremiumCorner" | "theLevelSuite";
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

// Define SelectedRooms as a record/dictionary
export interface SelectedRooms {
    [roomId: string]: {
        [optionId: string]: number; // quantity
    };
}

// Define BookingTotals interface
export interface BookingTotals {
    roomsTotal: number;
    breakfastTotal: number;
    serviceFee: number;
    taxAmount: number;
    discountAmount: number;
    finalTotal: number;
    nights: number;
}

export interface BookingPreferences {
    breakfastOption: 'none' | 'standard' | 'premium';
    bedPreference: 'double' | 'single';
    specialRequests?: string;
}

export interface BookingState {
    selectedRooms: SelectedRooms;
    roomsData: BookingRoom[];
    preferences: BookingPreferences;
    totals: BookingTotals;
    currentStep: 'selection' | 'payment' | 'completion';
    lastUpdated: string;
    isLoading?: boolean;
    error?: string | null;

    // New field for extending existing bookings
    extendingBooking?: {
        originalBookingCode: string;
        customerInfo: {
            name: string;
            email: string;
            phone: string;
        };
        searchData: {
            checkIn: string;
            checkOut: string;
            guests: number;
        };
        isExtending: boolean;
    } | null;
}

const initialState: BookingState = {
    selectedRooms: {},
    roomsData: [],
    preferences: {
        breakfastOption: 'none',
        bedPreference: 'double',
    },
    totals: {
        roomsTotal: 0,
        breakfastTotal: 0,
        serviceFee: 0,
        taxAmount: 0,
        discountAmount: 0,
        finalTotal: 0,
        nights: 1,
    },
    currentStep: 'selection',
    lastUpdated: new Date().toISOString(),
    extendingBooking: null,
};

// Helper function to calculate totals
const calculateTotals = (
    selectedRooms: BookingState['selectedRooms'],
    roomsData: BookingRoom[],
    preferences: BookingPreferences,
    nights: number,
    guestCount: number
): BookingTotals => {
    let roomsTotal = 0;

    // Calculate rooms total
    Object.entries(selectedRooms).forEach(([roomId, options]) => {
        const room = roomsData.find(r => r.id.toString() === roomId);
        if (!room) return;

        Object.entries(options).forEach(([optionId, quantity]) => {
            const numericQuantity = Number(quantity);
            if (numericQuantity > 0) {
                const option = room.options.find(opt => opt.id === optionId);
                if (option) {
                    const pricePerNight = option.dynamicPricing?.finalPrice || option.pricePerNight.vnd;
                    roomsTotal += pricePerNight * numericQuantity * nights;
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

    const serviceFee = 0; // Removed service fee
    const taxAmount = 0; // Removed VAT tax
    const discountAmount = 0; // Can be updated later
    const finalTotal = roomsTotal + breakfastTotal - discountAmount;

    return {
        roomsTotal,
        breakfastTotal,
        serviceFee,
        taxAmount,
        discountAmount,
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

        // Start adding rooms to existing booking
        startAddingRoomsToBooking: (
            state,
            action: PayloadAction<{
                existingBookingCode: string;
                customerInfo: {
                    name: string;
                    email: string;
                    phone: string;
                };
                searchData: {
                    checkIn: string;
                    checkOut: string;
                    guests: number;
                };
            }>
        ) => {
            const { existingBookingCode, customerInfo, searchData } = action.payload;

            // Reset selected rooms but keep other info
            state.selectedRooms = {};
            state.currentStep = 'selection';
            state.extendingBooking = {
                originalBookingCode: existingBookingCode,
                customerInfo,
                searchData,
                isExtending: true
            };

            // Recalculate totals
            state.totals = calculateTotals(
                state.selectedRooms,
                state.roomsData,
                state.preferences,
                1, // will be updated with real nights
                searchData.guests
            );

            state.lastUpdated = new Date().toISOString();
            saveStateToStorage(state);
        },

        // Finish extending booking
        finishExtendingBooking: (state) => {
            state.extendingBooking = null;
            state.currentStep = 'selection';
            saveStateToStorage(state);
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
    startAddingRoomsToBooking,
    finishExtendingBooking,
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

// Complex selectors with memoization
const selectSelectedRoomsBase = (state: { booking: BookingState }) => state.booking.selectedRooms;
const selectRoomsDataBase = (state: { booking: BookingState }) => state.booking.roomsData;
const selectTotalsBase = (state: { booking: BookingState }) => state.booking.totals;

export const selectSelectedRoomsSummary = createSelector(
    [selectSelectedRoomsBase, selectRoomsDataBase, selectTotalsBase],
    (selectedRooms, roomsData, totals) => {
        const summary: SelectedRoom[] = [];

        Object.entries(selectedRooms).forEach(([roomId, options]) => {
            const room = roomsData.find(r => r.id.toString() === roomId);
            if (!room) return;

            Object.entries(options).forEach(([optionId, quantity]) => {
                const numericQuantity = Number(quantity);
                if (numericQuantity > 0) {
                    const option = room.options.find(opt => opt.id === optionId);
                    if (option) {
                        const pricePerNight = option.dynamicPricing?.finalPrice || option.pricePerNight.vnd;
                        const totalPrice = pricePerNight * numericQuantity * totals.nights;

                        summary.push({
                            roomId,
                            optionId,
                            quantity: numericQuantity,
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
    }
);

export const selectHasSelectedRooms = createSelector(
    [selectSelectedRoomsBase],
    (selectedRooms) => {
        return Object.keys(selectedRooms).length > 0;
    }
);

export const selectSelectedRoomsCount = createSelector(
    [selectSelectedRoomsBase],
    (selectedRooms) => {
        let count = 0;
        Object.values(selectedRooms).forEach(options => {
            Object.values(options).forEach(quantity => {
                count += Number(quantity);
            });
        });
        return count;
    }
);

export default bookingSlice.reducer;
