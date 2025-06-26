import { useSelector, useDispatch } from 'react-redux';
import { useCallback, useMemo } from 'react';
import { RootState, AppDispatch } from '../store';
import {
    updateSearchData,
    setDateRange,
    setGuestType,
    updateGuestDetails,
    updateChildAge,
    setLoading,
    setError,
    clearSearchData,
    resetError,
    selectSearchData,
    selectDateRange,
    selectGuestDetails,
    selectGuestType,
    selectIsLoading,
    selectError,
    selectHasSearchData,
    SearchData,
    GuestDetails,
} from '../store/slices/searchSlice';
import { searchService } from '../services/searchService';
import { Dayjs } from 'dayjs';
import dayjs from 'dayjs';

// Helper function to validate search data
const validateSearchData = (searchData: SearchData): string[] => {
    const errors: string[] = [];

    if (!searchData.dateRange) {
        errors.push('Vui lòng chọn ngày nhận phòng và trả phòng');
    } else {
        const [checkIn, checkOut] = searchData.dateRange;
        const today = dayjs().startOf('day');

        if (checkIn.isBefore(today)) {
            errors.push('Ngày nhận phòng không thể là ngày trong quá khứ');
        }

        if (checkOut.isBefore(checkIn)) {
            errors.push('Ngày trả phòng phải sau ngày nhận phòng');
        }

        if (checkIn.isSame(checkOut)) {
            errors.push('Ngày trả phòng phải khác ngày nhận phòng');
        }
    }

    if (searchData.guestDetails.adults < 1) {
        errors.push('Phải có ít nhất 1 người lớn');
    }

    if (searchData.guestDetails.children < 0) {
        errors.push('Số lượng trẻ em không thể âm');
    }

    if (searchData.guests > 10) {
        errors.push('Số lượng khách tối đa là 10 người');
    }

    return errors;
};

// Custom hook for search functionality
export const useSearch = () => {
    const dispatch = useDispatch<AppDispatch>();

    // Selectors
    const searchData = useSelector((state: RootState) => selectSearchData(state));
    const dateRange = useSelector((state: RootState) => selectDateRange(state));
    const guestDetails = useSelector((state: RootState) => selectGuestDetails(state));
    const guestType = useSelector((state: RootState) => selectGuestType(state));
    const isLoading = useSelector((state: RootState) => selectIsLoading(state));
    const error = useSelector((state: RootState) => selectError(state));
    const hasSearchData = useSelector((state: RootState) => selectHasSearchData(state));

    // Action dispatchers
    const updateSearch = useCallback((data: Partial<SearchData>) => {
        dispatch(updateSearchData(data));
    }, [dispatch]); const setSearchDateRange = useCallback((range: [Dayjs, Dayjs] | null) => {
        // Convert Dayjs objects to strings for Redux serialization
        const stringRange = range ? [range[0].format('YYYY-MM-DD'), range[1].format('YYYY-MM-DD')] as [string, string] : null;
        dispatch(setDateRange(stringRange));
    }, [dispatch]);

    const setSearchGuestType = useCallback((type: SearchData['guestType']) => {
        dispatch(setGuestType(type));
    }, [dispatch]);

    const updateGuests = useCallback((details: Partial<GuestDetails>) => {
        dispatch(updateGuestDetails(details));
    }, [dispatch]);

    const clearSearch = useCallback(() => {
        dispatch(clearSearchData());
    }, [dispatch]);

    const clearError = useCallback(() => {
        dispatch(resetError());
    }, [dispatch]);    // Search functions with backend API integration
    const performSearch = useCallback(async () => {
        try {
            dispatch(setLoading(true));
            dispatch(resetError());

            // Validate search data
            const validationErrors = validateSearchData(searchData);
            if (validationErrors.length > 0) {
                throw new Error(validationErrors.join(', '));
            }

            console.log('🔍 Search data being sent to backend:', searchData);

            // Use backend API directly with current search data format
            const results = await searchService.searchRooms(searchData);

            dispatch(setLoading(false));
            return results;
        } catch (error: any) {
            dispatch(setError(error.message));
            dispatch(setLoading(false));
            throw error;
        }
    }, [searchData, dispatch]);

    // TODO: Implement these features with backend API later
    // const checkRoomAvailability = useCallback(async (roomId: string) => {
    //     try {
    //         dispatch(setLoading(true));

    //         if (!searchData.dateRange) {
    //             throw new Error('Date range is required');
    //         }

    //         const [checkIn, checkOut] = searchData.dateRange;
    //         // Convert Dayjs objects to strings for the API call
    //         const availability = await searchService.checkAvailability(
    //             roomId,
    //             checkIn.format('YYYY-MM-DD'),
    //             checkOut.format('YYYY-MM-DD')
    //         );
    //         dispatch(setLoading(false));
    //         return availability;
    //     } catch (error: any) {
    //         dispatch(setError(error.message));
    //         dispatch(setLoading(false));
    //         throw error;
    //     }
    // }, [searchData, dispatch]);

    // const getSearchSuggestions = useCallback(async (query: string) => {
    //     try {
    //         return await searchService.getSearchSuggestions(query);
    //     } catch (error: any) {
    //         console.warn('Could not get search suggestions:', error);
    //         return [];
    //     }
    // }, []);

    // const getSearchHistory = useCallback(async () => {
    //     try {
    //         return await searchService.getSearchHistory();
    //     } catch (error: any) {
    //         console.warn('Could not get search history:', error);
    //         return [];
    //     }
    // }, []);

    // Helper functions
    const formatGuestSelection = useCallback(() => {
        switch (searchData.guestType) {
            case "solo":
                return "1 người";
            case "couple":
                return "2 người";
            case "business":
                return "1 người (Công tác)";
            case "family_young":
            case "group":
                const totalPeople = searchData.guestDetails.adults + searchData.guestDetails.children;
                return `${totalPeople} người`;
            default:
                return "Số lượng khách";
        }
    }, [searchData.guestType, searchData.guestDetails]);

    const isValidSearchData = useMemo(() => {
        return validateSearchData(searchData).length === 0;
    }, [searchData]);

    // Guest count handlers
    const handleGuestCountChange = useCallback((
        type: "adults" | "children",
        operation: "increase" | "decrease"
    ) => {
        const currentDetails = searchData.guestDetails;
        const newDetails = { ...currentDetails };

        if (operation === "increase") {
            newDetails[type] += 1;
        } else if (
            operation === "decrease" &&
            newDetails[type] > (type === "adults" ? 1 : 0)
        ) {
            newDetails[type] -= 1;
        } dispatch(updateGuestDetails(newDetails));
    }, [searchData.guestDetails, dispatch]);

    // Update child age
    const updateChildAgeHandler = useCallback((childId: string, age: number) => {
        dispatch(updateChildAge({ childId, age }));
    }, [dispatch]);

    return {
        // State
        searchData,
        dateRange,
        guestDetails,
        guestType,
        isLoading,
        error,
        hasSearchData,
        isValidSearchData,        // Actions
        updateSearch,
        setSearchDateRange,
        setSearchGuestType,
        updateGuests,
        clearSearch,
        clearError,
        handleGuestCountChange,
        updateChildAgeHandler,        // API functions
        performSearch,
        // TODO: Implement these features with backend API later
        // checkRoomAvailability,
        // getSearchSuggestions,
        // getSearchHistory,

        // Helpers
        formatGuestSelection,
    };
};

export default useSearch;
