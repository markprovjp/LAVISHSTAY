/**
 * Format currency based on locale and currency type
 */
export const formatCurrency = (amount: number, currency: string = 'VND'): string => {
    if (currency === 'VND') {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(amount);
    }

    if (currency === 'USD') {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(amount);
    }

    // Fallback for other currencies
    return `${amount.toLocaleString()} ${currency}`;
};

/**
 * Truncate text with ellipsis
 */
export const truncateText = (text: string, maxLength: number): string => {
    if (text.length <= maxLength) return text;
    return text.slice(0, maxLength).trim() + '...';
};

/**
 * Get image URL with fallback
 */
const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8888';

export const getImageUrl = (url: string | undefined, fallback: string = '/images/placeholder.jpg'): string => {
    if (!url) return fallback;

    // If already absolute URL, return as-is
    if (url.startsWith('http')) {
        try {
            const parsed = new URL(url);
            // If host is localhost or 127.0.0.1 but port is missing or differs, rewrite to API_BASE_URL
            const host = parsed.hostname;
            if ((host === 'localhost' || host === '127.0.0.1')) {
                // If API_BASE_URL has host+port, use that base and keep the path
                const apiBase = new URL(API_BASE_URL);
                return apiBase.origin + parsed.pathname + parsed.search + parsed.hash;
            }
        } catch (e) {
            // If URL constructor fails, fallthrough and return as-is
            return url;
        }
        return url;
    }

    // If path points to storage (starts with /storage or storage/), prefix backend base
    if (url.startsWith('/storage') || url.startsWith('storage')) {
        // ensure single slash
        const path = url.startsWith('/') ? url : `/${url}`;
        return `${API_BASE_URL}${path}`;
    }

    // For other relative paths, return as absolute path on backend to avoid resolving to frontend origin
    const path = url.startsWith('/') ? url : `/${url}`;
    return `${API_BASE_URL}${path}`;
};

/**
 * Generate aria-label for room type card
 */
export const generateRoomAriaLabel = (title: string, price: number | null, currency: string, rating: number): string => {
    const priceText = price ? `${formatCurrency(price, currency)} per night` : 'Contact for price';
    return `${title}, ${priceText}, rated ${rating} out of 5 stars`;
};
