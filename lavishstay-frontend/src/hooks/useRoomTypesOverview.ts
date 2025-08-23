import { useState, useEffect, useCallback, useMemo } from 'react';
import { RoomTypesApiResponse, RoomTypesFilters, RoomType } from '../types/roomTypes';

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8888';
const CACHE_TTL = 60 * 1000; // 60 seconds

interface CacheEntry {
    data: RoomTypesApiResponse;
    timestamp: number;
}

interface UseRoomTypesReturn {
    data: RoomType[];
    loading: boolean;
    error: string | null;
    refetch: () => void;
    total: number;
    meta: RoomTypesApiResponse['meta'] | null;
}

// Simple in-memory cache
const cache = new Map<string, CacheEntry>();

// Debounce utility
const useDebounce = <T>(value: T, delay: number): T => {
    const [debouncedValue, setDebouncedValue] = useState<T>(value);

    useEffect(() => {
        const handler = setTimeout(() => {
            setDebouncedValue(value);
        }, delay);

        return () => {
            clearTimeout(handler);
        };
    }, [value, delay]);

    return debouncedValue;
};

export const useRoomTypes = (filters: RoomTypesFilters = {}): UseRoomTypesReturn => {
    const [data, setData] = useState<RoomType[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const [meta, setMeta] = useState<RoomTypesApiResponse['meta'] | null>(null);

    // Debounce filters to avoid too many API calls
    const debouncedFilters = useDebounce(filters, 300);

    // Create cache key from filters
    const cacheKey = useMemo(() => {
        return JSON.stringify(debouncedFilters);
    }, [debouncedFilters]);

    // Build query string from filters
    const buildQueryString = useCallback((filters: RoomTypesFilters): string => {
        const params = new URLSearchParams();

        Object.entries(filters).forEach(([key, value]) => {
            if (value !== undefined && value !== null && value !== '') {
                params.append(key, String(value));
            }
        });

        return params.toString();
    }, []);

    // Fetch room types from API
    const fetchRoomTypes = useCallback(async (filters: RoomTypesFilters): Promise<RoomTypesApiResponse> => {
        // Ensure we never send a per-request limit greater than backend allows
        const safeFilters = { ...filters } as RoomTypesFilters;
        if (safeFilters.limit && safeFilters.limit > 50) {
            safeFilters.limit = 50;
        }

        const queryString = buildQueryString(safeFilters);
        const url = `${API_BASE_URL}/api/room-types/overview${queryString ? `?${queryString}` : ''}`;

        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data: RoomTypesApiResponse | any = await response.json();

        if (!data.success) {
            throw new Error(data.message || 'Unknown API error');
        }

        return data;
    }, [buildQueryString]);

    /**
     * Fetch all room types by paging the backend (backend limit per-page = 50)
     */
    const fetchAllRoomTypes = useCallback(async (filters: RoomTypesFilters): Promise<RoomTypesApiResponse> => {
        const perPage = 50; // backend max
        let page = 1;
        let collected: RoomType[] = [];
        let total = 0;

        while (true) {
            const pageFilters = { ...filters, limit: perPage, page };
            const res = await fetchRoomTypes(pageFilters);

            if (res && Array.isArray(res.data)) {
                collected = collected.concat(res.data);
            }

            total = res.meta?.total || total || 0;

            // If we've collected all items or backend returned less than perPage, stop
            if (collected.length >= total || (res.data?.length || 0) < perPage) {
                break;
            }

            page += 1;
        }

        return {
            success: true,
            meta: {
                total,
                page: 1,
                per_page: collected.length,
                total_pages: Math.max(1, Math.ceil(total / perPage))
            },
            data: collected
        } as RoomTypesApiResponse;
    }, [fetchRoomTypes]);

    // Main fetch function with caching
    const fetchData = useCallback(async () => {
        try {
            setLoading(true);
            setError(null);

            // Check cache first
            const cached = cache.get(cacheKey);
            if (cached && Date.now() - cached.timestamp < CACHE_TTL) {
                setData(cached.data.data);
                setMeta(cached.data.meta);
                setLoading(false);
                return;
            }

            // Fetch from API
            let response: RoomTypesApiResponse;

            // If caller asked for more than backend per-request limit, fetch all pages
            const requestedLimit = debouncedFilters.limit ?? 100;
            if (requestedLimit > 50) {
                response = await fetchAllRoomTypes(debouncedFilters);
            } else {
                response = await fetchRoomTypes(debouncedFilters);
            }

            // Update cache
            cache.set(cacheKey, {
                data: response,
                timestamp: Date.now(),
            });

            // Update state
            setData(response.data);
            setMeta(response.meta);
        } catch (err) {
            console.error('Error fetching room types:', err);
            setError(err instanceof Error ? err.message : 'Unknown error occurred');
            setData([]);
            setMeta(null);
        } finally {
            setLoading(false);
        }
    }, [cacheKey, debouncedFilters, fetchRoomTypes, fetchAllRoomTypes]);

    // Refetch function (bypasses cache)
    const refetch = useCallback(() => {
        cache.delete(cacheKey);
        fetchData();
    }, [cacheKey, fetchData]);

    // Effect to fetch data when filters change
    useEffect(() => {
        fetchData();
    }, [fetchData]);

    // Cleanup cache periodically
    useEffect(() => {
        const interval = setInterval(() => {
            const now = Date.now();
            for (const [key, entry] of cache.entries()) {
                if (now - entry.timestamp > CACHE_TTL) {
                    cache.delete(key);
                }
            }
        }, CACHE_TTL);

        return () => clearInterval(interval);
    }, []);

    return {
        data,
        loading,
        error,
        refetch,
        total: meta?.total || 0,
        meta,
    };
};
