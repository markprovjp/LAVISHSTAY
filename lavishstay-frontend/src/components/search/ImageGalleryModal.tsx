import React from 'react';
import { Modal, Image, Button, Typography, Space, Tag } from 'antd';
import { LeftOutlined, RightOutlined, CloseOutlined } from '@ant-design/icons';

const { Text } = Typography;

interface ImageGalleryModalProps {
    visible: boolean;
    images: string[];
    currentIndex: number;
    onClose: () => void;
    onPrevious: () => void;
    onNext: () => void;
    roomName?: string;
}

const ImageGalleryModal: React.FC<ImageGalleryModalProps> = ({
    visible,
    images,
    currentIndex,
    onClose,
    onPrevious,
    onNext,
    roomName = 'Phòng'
}) => {
    if (!images || images.length === 0) return null;

    return (
        <Modal
            open={visible}
            onCancel={onClose}
            footer={null}
            width="90vw"
            style={{ top: 20, maxWidth: '1200px' }}
            className="image-gallery-modal"
            closeIcon={<CloseOutlined className="text-white text-xl" />}
            styles={{
                content: {
                    background: 'linear-gradient(135deg, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.95) 100%)',
                    borderRadius: '16px',
                    overflow: 'hidden'
                }
            }}
        >
            <div className="relative">
                {/* Header */}
                <div className="absolute top-4 left-4 z-20 bg-black bg-opacity-50 rounded-lg px-4 py-2">
                    <Text className="text-white font-medium">{roomName}</Text>
                    <div className="flex items-center gap-2 mt-1">
                        <Tag color="blue" className="text-xs">
                            {currentIndex + 1} / {images.length}
                        </Tag>
                    </div>
                </div>

                {/* Main Image */}
                <div className="text-center mb-4 relative">
                    <Image
                        src={images[currentIndex]}
                        alt={`${roomName} ${currentIndex + 1}`}
                        style={{
                            maxWidth: '100%',
                            maxHeight: '75vh',
                            objectFit: 'contain',
                            borderRadius: '8px'
                        }}
                        preview={false}
                        className="shadow-2xl"
                    />
                </div>

                {/* Navigation Buttons */}
                {images.length > 1 && (
                    <>
                        <Button
                            type="primary"
                            shape="circle"
                            icon={<LeftOutlined />}
                            onClick={onPrevious}
                            className="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 w-12 h-12 bg-black bg-opacity-50 border-white border-2 hover:bg-opacity-70"
                            size="large"
                        />
                        <Button
                            type="primary"
                            shape="circle"
                            icon={<RightOutlined />}
                            onClick={onNext}
                            className="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 w-12 h-12 bg-black bg-opacity-50 border-white border-2 hover:bg-opacity-70"
                            size="large"
                        />
                    </>
                )}

                {/* Thumbnails */}
                {images.length > 1 && (
                    <div className="flex justify-center gap-2 mt-4 overflow-x-auto pb-2">
                        {images.map((img, index) => (
                            <div
                                key={index}
                                className={`w-16 h-16 rounded-lg overflow-hidden cursor-pointer transition-all ${index === currentIndex
                                    ? 'ring-2 ring-blue-500 opacity-100'
                                    : 'opacity-60 hover:opacity-80'
                                    }`}
                                onClick={() => {
                                    const diff = index - currentIndex;
                                    if (diff > 0) {
                                        for (let i = 0; i < diff; i++) onNext();
                                    } else if (diff < 0) {
                                        for (let i = 0; i < Math.abs(diff); i++) onPrevious();
                                    }
                                }}
                            >
                                <img
                                    src={img}
                                    alt={`Thumb ${index + 1}`}
                                    className="w-full h-full object-cover"
                                />
                            </div>
                        ))}
                    </div>
                )}

                {/* Keyboard Navigation Hint */}
                <div className="text-center mt-4">
                    <Text className="text-white text-xs opacity-70">
                        Sử dụng phím ← → để điều hướng, ESC để đóng
                    </Text>
                </div>
            </div>
        </Modal>
    );
};

export default ImageGalleryModal;
