// Optimization script for performance improvements
import React, { lazy } from 'react';

export const StrictModeToggle = process.env.NODE_ENV === 'development' ?
    ({ children }: { children: React.ReactNode }) => { children } :
    React.StrictMode;

// Disable warnings in development
if (process.env.NODE_ENV === 'development') {
    const originalError = console.error;
    console.error = (...args) => {
        if (
            typeof args[0] === 'string' &&
            (args[0].includes('findDOMNode is deprecated') ||
                args[0].includes('bodyStyle` is deprecated'))
        ) {
            return;
        }
        originalError.call(console, ...args);
    };
}

// Lazy load các component lớn
export const LazyRoomGridView = lazy(() => import('../components/room-management/RoomGridView'));
export const LazyRoomTimelineView = lazy(() => import('../components/room-management/RoomTimelineView'));
export const LazyConfirmRepresentativePayment = lazy(() => import('../pages/reception/room-management/ConfirmRepresentativePayment'));
export const LazyBookingManagement = lazy(() => import('../pages/reception/booking-management/BookingManagement'));

// Performance optimization utilities
export const performanceOptimization = {
    // Debounce function cho search
    debounce: <T extends (...args: any[]) => any>(func: T, delay: number) => {
        let timeoutId: NodeJS.Timeout;
        return (...args: Parameters<T>) => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => func.apply(null, args), delay);
        };
    },

    // Throttle function cho scroll events
    throttle: <T extends (...args: any[]) => any>(func: T, delay: number) => {
        let inThrottle: boolean;
        return (...args: Parameters<T>) => {
            if (!inThrottle) {
                func.apply(null, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, delay);
            }
        };
    },

    // Memoization helper
    memoize: <T extends (...args: any[]) => any>(fn: T): T => {
        const cache = new Map();
        return ((...args: Parameters<T>) => {
            const key = JSON.stringify(args);
            if (cache.has(key)) {
                return cache.get(key);
            }
            const result = fn(...args);
            cache.set(key, result);
            return result;
        }) as T;
    }
};

export default performanceOptimization;
