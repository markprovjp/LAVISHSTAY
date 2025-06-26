import React, { createContext, useContext, useState, ReactNode } from 'react';
import { Dayjs } from 'dayjs';
import dayjs from 'dayjs';

// Types for search data
export interface GuestDetails {
    adults: number;
    children: number;
}

export interface SearchData {
    dateRange: [Dayjs, Dayjs] | null;
    guests: number;
    guestDetails: GuestDetails;
    guestType: "business" | "couple" | "solo" | "family_young" | "group";
    searchDate?: string;
}

// Context interface
interface SearchContextType {
    searchData: SearchData;
    updateSearchData: (data: Partial<SearchData>) => void;
    clearSearchData: () => void;
    hasSearchData: boolean;
}

// Default search data
const defaultSearchData: SearchData = {
    dateRange: null,
    guests: 1,
    guestDetails: {
        adults: 1,
        children: 0,
    },
    guestType: 'solo',
};

// Create context
const SearchContext = createContext<SearchContextType | undefined>(undefined);

// Provider component
interface SearchProviderProps {
    children: ReactNode;
}

export const SearchProvider: React.FC<SearchProviderProps> = ({ children }) => {
    const [searchData, setSearchData] = useState<SearchData>(() => {
        // Try to load from localStorage on initial render
        try {
            const savedData = localStorage.getItem('searchData');
            if (savedData) {
                const parsed = JSON.parse(savedData);
                // Convert dateRange strings back to dayjs objects
                let dateRange = null;
                if (parsed.dateRange && Array.isArray(parsed.dateRange)) {
                    dateRange = [
                        dayjs(parsed.dateRange[0]),
                        dayjs(parsed.dateRange[1])
                    ];
                }
                return {
                    ...defaultSearchData,
                    ...parsed,
                    dateRange, // Use the converted dayjs objects
                };
            }
        } catch (error) {
            console.error('Error loading search data from localStorage:', error);
        }
        return defaultSearchData;
    });

    const updateSearchData = (data: Partial<SearchData>) => {
        const newSearchData = {
            ...searchData,
            ...data,
            searchDate: new Date().toISOString(),
        };

        setSearchData(newSearchData);

        // Save to localStorage
        try {
            const dataToSave = {
                ...newSearchData,
                // Convert dateRange to serializable format
                dateRange: newSearchData.dateRange ? [
                    newSearchData.dateRange[0].toISOString(),
                    newSearchData.dateRange[1].toISOString(),
                ] : null,
            };
            localStorage.setItem('searchData', JSON.stringify(dataToSave));
        } catch (error) {
            console.error('Error saving search data to localStorage:', error);
        }
    };

    const clearSearchData = () => {
        setSearchData(defaultSearchData);
        localStorage.removeItem('searchData');
    };

    const hasSearchData = Boolean(
        searchData.dateRange &&
        searchData.guests > 0
    );

    return (
        <SearchContext.Provider
            value={{
                searchData,
                updateSearchData,
                clearSearchData,
                hasSearchData,
            }}
        >
            {children}
        </SearchContext.Provider>
    );
};

// Custom hook to use search context
export const useSearch = (): SearchContextType => {
    const context = useContext(SearchContext);
    if (context === undefined) {
        throw new Error('useSearch must be used within a SearchProvider');
    }
    return context;
};

export default SearchContext;
