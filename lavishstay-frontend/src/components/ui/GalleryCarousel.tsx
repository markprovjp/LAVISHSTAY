import React, { useState } from 'react';
import { Modal, Carousel, Image } from 'antd';
import { getImageUrl } from '../../utils/formatUtils';
import { LeftOutlined, RightOutlined } from '@ant-design/icons';

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
    const [modalVisible, setModalVisible] = useState(false);
    const [currentIndex, setCurrentIndex] = useState(0);

    if (!images || images.length === 0) {
        return (
            <div className={`w-full h-48 bg-gray-200 flex items-center justify-center ${className}`}>
                <span className="text-gray-500">Không có hình ảnh</span>
            </div>
        );
    }

    const openModal = (index: number = 0) => {
        setCurrentIndex(index);
        setModalVisible(true);
    };

    return (
        <>
            {/* Main image display */}
            <div className={`relative cursor-pointer ${className}`}>
                <Image
                    src={getImageUrl(images[0])}
                    alt={alt}
                    className="w-full h-48 object-cover rounded-lg"
                    preview={false}
                    onClick={() => openModal(0)}
                    loading="lazy"
                    placeholder={
                        <div className="w-full h-48 bg-gray-200 animate-pulse rounded-lg" />
                    }
                />

                {/* Gallery indicator */}
                {images.length > 1 && (
                    <div
                        className="absolute bottom-2 right-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs"
                        onClick={(e) => {
                            e.stopPropagation();
                            openModal(0);
                        }}
                    >
                        {images.length} ảnh
                    </div>
                )}
            </div>

            {/* Full screen modal */}
            <Modal
                open={modalVisible}
                onCancel={() => setModalVisible(false)}
                footer={null}
                width="90vw"
                style={{ top: 20 }}
                className="gallery-modal"
            >
                <Carousel
                    initialSlide={currentIndex}
                    arrows
                    prevArrow={<LeftOutlined />}
                    nextArrow={<RightOutlined />}
                    dotPosition="bottom"
                >
                    {images.map((src, index) => {
                        const finalUrl = getImageUrl(src);
                        console.debug('Gallery image URL:', finalUrl);
                        return (
                            <div key={index}>
                                <Image
                                    src={finalUrl}
                                    alt={`${alt} - Ảnh ${index + 1}`}
                                    className="w-full max-h-[70vh] object-contain"
                                    preview={false}
                                />
                            </div>
                        );
                    })}
                </Carousel>
            </Modal>
        </>
    );
};

export default React.memo(GalleryCarousel);
