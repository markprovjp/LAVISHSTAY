/**
 * Utility functions for handling image paths in the application
 */

/**
 * Get the correct image URL for assets in the public folder
 * @param imagePath - The relative path from public folder (e.g., "images/room/1.jpg")
 * @returns Absolute URL for the image
 */
export const getImageUrl = (imagePath: string): string => {
    // Remove leading slash if present
    const cleanPath = imagePath.startsWith('/') ? imagePath.slice(1) : imagePath;

    // In development, use relative path with leading slash
    // In production, use the base URL
    const baseUrl = import.meta.env.BASE_URL || '/';

    return `${baseUrl}${cleanPath}`;
};

/**
 * Get image URL with fallback for error handling
 * @param imagePath - The relative path from public folder
 * @param fallbackUrl - Fallback URL if original fails
 * @returns Image URL with fallback
 */
export const getImageUrlWithFallback = (imagePath: string, fallbackUrl?: string): string => {
    const imageUrl = getImageUrl(imagePath);

    // Return the fallback if provided, otherwise use placeholder
    const defaultFallback = "https://via.placeholder.com/400x250/8B7CF6/ffffff?text=Hotel+Image";

    return imageUrl || fallbackUrl || defaultFallback;
};

/**
 * Preload an image to check if it exists
 * @param src - Image URL to check
 * @returns Promise that resolves if image loads successfully
 */
export const preloadImage = (src: string): Promise<string> => {
    return new Promise((resolve, reject) => {
        const img = new Image();
        img.onload = () => resolve(src);
        img.onerror = () => reject(new Error(`Failed to load image: ${src}`));
        img.src = src;
    });
};

/**
 * Get optimized image URL based on device pixel ratio and screen size
 * @param imagePath - The relative path from public folder
 * @param width - Desired width
 * @param height - Desired height
 * @returns Optimized image URL
 */
export const getOptimizedImageUrl = (
    imagePath: string,
    width?: number,
    height?: number
): string => {
    const baseUrl = getImageUrl(imagePath);

    // For now, return the base URL. In future, we can add image optimization logic
    // such as adding query parameters for CDN optimization or using different image sizes
    return baseUrl;
};
