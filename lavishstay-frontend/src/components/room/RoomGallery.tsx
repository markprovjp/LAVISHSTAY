// src/components/room/RoomGallery.tsx
import React from 'react';
import { Image } from 'antd';
import { RoomImage } from '../../types/roomDetail';

interface RoomGalleryProps {
    images: RoomImage[];
    roomName: string;
    className?: string;
}

const RoomGallery: React.FC<RoomGalleryProps> = ({
    images,
    roomName,
    className = ''
}) => {
    const sortedImages = [...images].sort((a, b) => a.order - b.order);

    return (
        <div className={`room-gallery ${className}`}>
            {/* Main Gallery with PreviewGroup */}
            <div className="">
                <Image.PreviewGroup
                    items={sortedImages.map(img => ({ src: img.url, alt: img.alt }))}
                >
                    <div className="flex flex-col items-center">
                        <Image
                            src={sortedImages[0]?.url}
                            alt={sortedImages[0]?.alt || roomName}
                           
                            className="rounded-xl object-cover w-full "
                            preview={{
                                src: sortedImages[0]?.url,
                                visible: false // handled by PreviewGroup
                            }}
                        />
                        {/* Thumbnails */}
                        <div className="flex gap-2 mt-4 overflow-x-auto w-full justify-center">
                            {sortedImages.map((img, idx) => (
                                <Image
                                    key={img.id}
                                    src={img.url}
                                    alt={img.alt}
                                    width={90}
                                    height={60}
                                    className="rounded-lg object-cover border border-gray-200 hover:border-blue-500 transition-all duration-200 cursor-pointer h-20 w-24"
                                    preview={{
                                        src: img.url,
                                        visible: false // handled by PreviewGroup
                                    }}
                                    style={{ objectFit: 'cover' }}
                                />
                            ))}
                        </div>
                    </div>
                </Image.PreviewGroup>
            </div>
        </div>
    );
};

export default RoomGallery;
