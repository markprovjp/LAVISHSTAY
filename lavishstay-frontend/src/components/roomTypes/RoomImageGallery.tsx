import React, { useState } from "react";
import { Image, Button } from "antd";
import { ExpandOutlined } from "@ant-design/icons";

interface RoomImageGalleryProps {
    images: string[];
    roomName: string;
}

const RoomImageGallery: React.FC<RoomImageGalleryProps> = ({
    images,
    roomName,
}) => {
    const [currentIndex, setCurrentIndex] = useState(0);

    const handleImageClick = (index: number) => {
        setCurrentIndex(index);
        // Trigger preview bằng cách click vào image đầu tiên
        setTimeout(() => {
            const imgElement = document.querySelector('.room-image-gallery .ant-image img') as HTMLElement;
            if (imgElement) {
                imgElement.click();
            }
        }, 100);
    }; return (
        <div className="room-image-gallery w-full">
            {/* Layout đơn giản: 50% trái - 50% phải */}
            <div className="flex gap-2 h-[410px] rounded-lg overflow-hidden">
                {/* Ảnh chính bên trái - 50% */}
                <div className="w-1/2 relative group">
                    <Image.PreviewGroup
                        preview={{
                            current: currentIndex,
                            onChange: setCurrentIndex,
                        }}
                    >
                        <Image
                            src={images[0]}
                            alt={`${roomName} - Ảnh chính`}
                            className=" object-cover cursor-pointer"
                            preview={{
                                mask: false
                            }}
                        />

                        {/* Hidden images cho preview */}
                        {images.slice(1).map((image, index) => (
                            <Image
                                key={index + 1}
                                src={image}
                                alt={`${roomName} - Ảnh ${index + 2}`}
                                style={{ display: 'none' }}
                            />
                        ))}
                    </Image.PreviewGroup>
                </div>

                {/* Bên phải - 50% chia thành 4 ảnh nhỏ */}
                <div className="w-1/2 grid grid-cols-2 gap-2">
                    {images.slice(1, 5).map((image, index) => (
                        <div
                            key={index + 1}
                            className="relative group cursor-pointer overflow-hidden"
                            onClick={() => handleImageClick(index + 1)}
                        >
                            <img
                                src={image}
                                alt={`${roomName} - Ảnh ${index + 2}`}
                                className="w-full h-full object-cover transition-all duration-300 group-hover:scale-105"
                            />

                            {/* Hover overlay */}
                            <div className="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center">
                                <ExpandOutlined className="text-white text-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                            </div>

                            {/* Overlay "+X ảnh nữa" trên ảnh cuối cùng */}
                            {index === 3 && images.length > 5 && (
                                <div className="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center">
                                    <div className="text-white text-center">
                                        <div className="text-lg font-bold">
                                            +{images.length - 5}
                                        </div>
                                        <div className="text-sm">
                                            ảnh nữa
                                        </div>
                                    </div>
                                </div>
                            )}
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
};

export default RoomImageGallery;
