import React from 'react';
import { Image } from 'antd';
import { getImageUrl } from '../../utils/formatUtils';

interface GalleryCarouselProps {
    images: string[];
    alt: string;
    className?: string;
}

const GalleryCarousel: React.FC<GalleryCarouselProps> = ({
    images,
    alt,
    className = ''
}) => {
    if (!images || images.length === 0) {
        return (
            <div className={`w-full h-48 bg-gray-200 flex items-center justify-center ${className}`}>
                <span className="text-gray-500">Không có hình ảnh</span>
            </div>
        );
    }

    return (
        <div className={`relative ${className}`}>
            <Image
                src={getImageUrl(images[0])}
                alt={alt}
                className="w-full h-48 object-cover rounded-lg"
                preview={false}
                loading="lazy"
                placeholder={
                    <div className="w-full h-48 bg-gray-200 animate-pulse rounded-lg" />
                }
            />

            {/* Gallery indicator */}
            {images.length > 1 && (
                <div className="absolute bottom-2 right-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs pointer-events-none">
                    {images.length} ảnh
                </div>
            )}
        </div>
    );
};



export default React.memo(GalleryCarousel);
