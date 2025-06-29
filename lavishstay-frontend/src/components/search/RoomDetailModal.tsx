import React from 'react';
import { Modal, Typography, Space, Row, Col, Image, Carousel, Button, Tag, Divider } from 'antd';
import {
    LeftOutlined,
    RightOutlined,
    StarFilled,
    EnvironmentOutlined,
    UserOutlined,
    HomeOutlined
} from '@ant-design/icons';
import { Room } from '../../types/room';
import {  } from '../../constants/Icons';

const { Title, Text } = Typography;

interface RoomDetailModalProps {
    visible: boolean;
    room: Room | null;
    onClose: () => void;
    onViewDetail: (roomId: string) => void;
    onBookNow: (roomId: string) => void;
    formatVND: (price: number) => string;
}

const RoomDetailModal: React.FC<RoomDetailModalProps> = ({
    visible,
    room,
    onClose,
    onViewDetail,
    onBookNow,
    formatVND
}) => {
    if (!room) return null;

    const mainAmenities = formatAmenitiesForDisplay(room.mainAmenities || room.amenities);

    return (
        <Modal
            title={
                <div className="flex items-center gap-3">
                    <HomeOutlined className="text-blue-600" />
                    <span className="text-xl font-bold text-gray-800">{room.name}</span>
                </div>
            }
            open={visible}
            onCancel={onClose}
            footer={[
                <Button key="back" onClick={onClose} size="large">
                    Đóng
                </Button>,
                <Button
                    key="detail"
                    type="default"
                    onClick={() => {
                        onViewDetail(room.id.toString());
                        onClose();
                    }}
                    size="large"
                    className="bg-blue-100 text-blue-600 hover:bg-blue-200 border-blue-300"
                >
                    Xem chi tiết đầy đủ
                </Button>,
                <Button
                    key="book"
                    type="primary"
                    onClick={() => {
                        onBookNow(room.id.toString());
                        onClose();
                    }}
                    size="large"
                    className="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 border-none shadow-lg"
                >
                    Đặt phòng ngay
                </Button>
            ]}
            width={1000}
            className="room-detail-modal"
        >
            <div>
                {/* Room Images Carousel */}
                {room.images && room.images.length > 0 ? (
                    <Carousel
                        dots={{ className: 'custom-dots' }}
                        arrows
                        prevArrow={<LeftOutlined />}
                        nextArrow={<RightOutlined />}
                        className="mb-6 rounded-xl overflow-hidden"
                        autoplay
                        autoplaySpeed={4000}
                    >
                        {room.images.map((img, index) => (
                            <div key={index}>
                                <Image
                                    src={img}
                                    alt={`${room.name} ${index + 1}`}
                                    className="w-full h-80 object-cover"
                                    fallback="https://via.placeholder.com/800x320?text=Room+Image"
                                />
                            </div>
                        ))}
                    </Carousel>
                ) : (
                    <Image
                        src={room.image}
                        alt={room.name}
                        className="w-full h-80 object-cover rounded-xl mb-6"
                        fallback="https://via.placeholder.com/800x320?text=Room+Image"
                    />
                )}

                {/* Room Details */}
                <Row gutter={[24, 16]}>
                    <Col span={14}>
                        <Space direction="vertical" size="small" className="w-full">
                            <div className="flex items-center gap-2">
                                <EnvironmentOutlined className="text-gray-500" />
                                <Text strong>Diện tích: </Text>
                                <Text>{room.size}m²</Text>
                            </div>
                            <div className="flex items-center gap-2">
                                <EnvironmentOutlined className="text-gray-500" />
                                <Text strong>Hướng view: </Text>
                                <Text>{room.view}</Text>
                            </div>
                            <div className="flex items-center gap-2">
                                <UserOutlined className="text-gray-500" />
                                <Text strong>Số khách tối đa: </Text>
                                <Text>{room.maxGuests} khách</Text>
                            </div>
                            {room.rating && (
                                <div className="flex items-center gap-2">
                                    <StarFilled className="text-yellow-500" />
                                    <Text strong>Đánh giá: </Text>
                                    <Space>
                                        <Text>{room.rating}/10</Text>
                                        <Tag color="gold">Tuyệt vời</Tag>
                                    </Space>
                                </div>
                            )}
                        </Space>
                    </Col>
                    <Col span={10}>
                        <div className="text-right">
                            <Text type="secondary" className="block mb-2">Giá từ</Text>
                            <div className="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg">
                                <Title level={3} className="text-blue-600 mb-1">
                                    {formatVND(room.priceVND)}
                                </Title>
                                <Text type="secondary">/đêm</Text>
                            </div>
                            {room.discount && (
                                <div className="mt-2">
                                    <Tag color="red" className="text-sm">
                                        Giảm {room.discount}%
                                    </Tag>
                                </div>
                            )}
                        </div>
                    </Col>
                </Row>

                <Divider />

                {/* Amenities */}
                <div>
                    <Title level={5} className="mb-3 text-gray-800">Tiện ích phòng</Title>
                    <div className="grid grid-cols-2 md:grid-cols-3 gap-3">
                        {mainAmenities.map((amenity, index) => (
                            <div key={index} className="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                                <span className="text-blue-600">{amenity.icon}</span>
                                <Text className="text-sm">{amenity.name}</Text>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Description */}
                {room.description && (
                    <>
                        <Divider />
                        <div>
                            <Title level={5} className="mb-3 text-gray-800">Mô tả phòng</Title>
                            <Text className="text-gray-600 leading-relaxed">
                                {room.description}
                            </Text>
                        </div>
                    </>
                )}
            </div>
        </Modal>
    );
};

export default RoomDetailModal;
