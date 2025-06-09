// Optimization script for performance improvements
import React from 'react';

export const StrictModeToggle = process.env.NODE_ENV === 'development' ?
    ({ children }: { children: React.ReactNode }) => { children } :
    React.StrictMode;

// Disable findDOMNode warnings in development
if (process.env.NODE_ENV === 'development') {
    const originalError = console.error;
    console.error = (...args) => {
        if (
            typeof args[0] === 'string' &&
            args[0].includes('findDOMNode is deprecated')
        ) {
            return;
        }
        originalError.call(console, ...args);
    };
}

export default {};
