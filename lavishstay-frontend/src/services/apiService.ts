/**
 * API Service tập trung - Quản lý tất cả API calls
 * Sử dụng config từ env.ts để đảm bảo consistent
 */

import { env } from '../config/env';

// Base API configuration
export const API_CONFIG = {
    BASE_URL: env.API_URL, // http://localhost:8888/api
    TIMEOUT: 5000, // 5 seconds
    HEADERS: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    }
};

// Helper để tạo full URL
export const createApiUrl = (endpoint: string): string => {
    // Remove leading slash if present to avoid double slashes
    const cleanEndpoint = endpoint.startsWith('/') ? endpoint.slice(1) : endpoint;
    return `${API_CONFIG.BASE_URL}/${cleanEndpoint}`;
};

// Helper để tạo fetch options
export const createFetchOptions = (options: RequestInit = {}): RequestInit => {
    // Attach auth token from localStorage if present (accessToken / token / authToken)
    let token: string | null = null;
    if (typeof window !== 'undefined') {
        token = localStorage.getItem('authToken') || localStorage.getItem('accessToken') || localStorage.getItem('token') || null;

        // Fallback: some flows persist entire redux store under 'persist:root' (stringified). Try to extract auth.token from it.
        if (!token) {
            try {
                const persistRoot = localStorage.getItem('persist:root');
                if (persistRoot) {
                    const parsed = JSON.parse(persistRoot);
                    // parsed may contain nested serialized JSON strings per slice
                    if (parsed?.auth) {
                        try {
                            const authSlice = JSON.parse(parsed.auth);
                            token = authSlice?.token || null;
                        } catch (e) {
                            // auth slice might already be an object
                            token = parsed.auth?.token || null;
                        }
                    }
                }
            } catch (e) {
                // ignore parse errors
            }
        }
    }

    const authHeaders = token ? { Authorization: `Bearer ${token}` } : {};

    // Debug: print token presence for troubleshooting auth flow
    try {
        // eslint-disable-next-line no-console
        console.log('[ApiService] resolved token:', token ? '***REDACTED***' : null);
        // eslint-disable-next-line no-console
        console.log('[ApiService] authHeaders set:', authHeaders);
    } catch (e) { }

    return {
        headers: {
            ...API_CONFIG.HEADERS,
            ...authHeaders,
            ...(options.headers as Record<string, string> | undefined),
        } as Record<string, string>,
        ...options,
    } as RequestInit;
};

// Custom fetch function that bypasses Mirage.js for certain endpoints
export const apiFetch = async (endpoint: string, options: RequestInit = {}): Promise<Response> => {
    const url = createApiUrl(endpoint);
    const fetchOptions = createFetchOptions(options);

    console.log('🌐 API Call:', url, fetchOptions);

    // For payment APIs, use XMLHttpRequest to bypass Mirage.js completely
    if (endpoint.includes('payment/')) {
        console.log('💳 Using XMLHttpRequest for payment API to bypass Mirage.js');

        return new Promise<Response>((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open(fetchOptions.method as string || 'GET', url);

            // Set headers
            if (fetchOptions.headers) {
                Object.entries(fetchOptions.headers as Record<string, string>).forEach(([key, value]) => {
                    xhr.setRequestHeader(key, value);
                });
            }

            xhr.onload = () => {
                console.log('✅ XHR Response:', xhr.status, xhr.responseText);

                // Create Response object
                const response = new Response(xhr.responseText, {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    headers: new Headers()
                });

                // Add json() method
                (response as any).json = async () => {
                    try {
                        return JSON.parse(xhr.responseText);
                    } catch (e) {
                        throw new Error('Invalid JSON response');
                    }
                };

                resolve(response);
            };

            xhr.onerror = () => {
                console.error('❌ XHR Error for:', url);
                reject(new Error('Network request failed'));
            };

            xhr.ontimeout = () => {
                console.error('⏱️ XHR Timeout for:', url);
                reject(new Error('Request timeout'));
            };

            xhr.timeout = API_CONFIG.TIMEOUT;
            xhr.send(fetchOptions.body as string);
        });
    }

    // For other APIs, use normal fetch (Mirage.js will handle)
    console.log('🔄 Using normal fetch for non-payment API');
    return fetch(url, fetchOptions);
};

// API endpoints constants
export const API_ENDPOINTS = {
    // Search endpoints
    SEARCH: {
        ROOMS: 'search/rooms',
        PRICING: (roomTypeId: string) => `search/room-types/${roomTypeId}/pricing`,
        PRICING_RULES: 'search/pricing-rules',
    },

    // Payment endpoints
    PAYMENT: {
        CREATE_BOOKING: 'payment/create-booking',
        CONFIRM_PAYMENT: (bookingCode: string) => `payment/admin/confirm/${bookingCode}`,
        GET_PENDING: 'payment/admin/pending',
        GET_STATS: 'payment/admin/stats',
    },

    // Room endpoints
    ROOMS: {
        LIST: 'rooms',
        BY_TYPE: (roomType: string) => `rooms-type/${roomType}`,
        BY_ID: (id: string) => `rooms/${id}`,
        OPTIONS: (id: string) => `rooms/${id}/options`,
        SIMILAR: (id: string) => `rooms/${id}/similar`,
        REVIEWS: (id: string) => `rooms/${id}/reviews`,
    },

    // Auth endpoints
    AUTH: {
        LOGIN: 'auth/login',
        LOGOUT: 'auth/logout',
        USER: 'auth/user',
    },

    // Booking endpoints
    BOOKINGS: {
        CREATE: 'bookings',
        CANCEL: (id: string) => `bookings/${id}/cancel`,
    },

    // Availability endpoints
    AVAILABILITY: {
        ROOMS: 'rooms/available',
    },


};

// Utility functions for common API patterns
export const ApiService = {
    // GET request
    get: async <T = any>(endpoint: string): Promise<T> => {
        const response = await apiFetch(endpoint, { method: 'GET' });
        if (!response.ok) {
            throw new Error(`API Error: ${response.status} ${response.statusText}`);
        }
        return response.json();
    },

    // POST request
    post: async <T = any>(endpoint: string, data: any): Promise<T> => {
        const response = await apiFetch(endpoint, {
            method: 'POST',
            body: JSON.stringify(data),
        });
        if (!response.ok) {
            throw new Error(`API Error: ${response.status} ${response.statusText}`);
        }
        return response.json();
    },

    // PUT request
    put: async <T = any>(endpoint: string, data: any): Promise<T> => {
        const response = await apiFetch(endpoint, {
            method: 'PUT',
            body: JSON.stringify(data),
        });
        if (!response.ok) {
            throw new Error(`API Error: ${response.status} ${response.statusText}`);
        }
        return response.json();
    },

    // DELETE request
    delete: async <T = any>(endpoint: string): Promise<T> => {
        const response = await apiFetch(endpoint, { method: 'DELETE' });
        if (!response.ok) {
            throw new Error(`API Error: ${response.status} ${response.statusText}`);
        }
        return response.json();
    },
};

export default ApiService;
