import React from 'react';
import { Card, Typography, Divider, Alert, Badge, Space, Row, Col } from 'antd';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Navigation, Pagination } from 'swiper/modules';
import {
    StarFilled,
    GiftOutlined,
    HomeOutlined,
    UserOutlined,
    EyeOutlined
} from '@ant-design/icons';
import { Eye } from 'lucide-react';
import { Room } from '../../mirage/models';
import { formatAmenitiesForDisplay } from '../../constants/amenities';
import RoomOptionsSection from './RoomOptionsSection';

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/autoplay';

const { Title, Text } = Typography;

interface RoomCardProps {
    room: Room;
    selectedRooms: { [roomId: string]: { [optionId: string]: number } };
    onQuantityChange: (roomId: string, optionId: string, quantity: number) => void;
    onShowImageGallery: (room: Room, index: number) => void;
    shouldShowSuggestion: (room: Room) => boolean;
    searchData: any;
    formatVND: (price: number) => string;
    getNights: () => number;
}

const RoomCard: React.FC<RoomCardProps> = ({
    room,
    selectedRooms,
    onQuantityChange,
    onShowImageGallery,
    shouldShowSuggestion,
    searchData,
    formatVND,
    getNights
}) => {
    const isSuitable = searchData.guestDetails
        ? room.maxGuests >= (searchData.guestDetails.adults + searchData.guestDetails.children)
        : true; const getMainAmenities = (amenities: string[]) => {
            return formatAmenitiesForDisplay(amenities);
        };

    return (
        <Card
            className="room-card hover:shadow-md transition-shadow duration-200"
            bodyStyle={{ padding: '16px' }}
        >
            <Row gutter={16}>
                {/* Left Side - Images */}
                <Col xs={24} lg={8}>
                    <div className="relative h-48 lg:h-52 rounded-lg overflow-hidden group">
                        <Swiper
                            modules={[Autoplay, Navigation, Pagination]}
                            autoplay={{
                                delay: 4000,
                                disableOnInteraction: false,
                                pauseOnMouseEnter: true
                            }}
                            navigation={false}
                            pagination={{
                                clickable: true,
                                dynamicBullets: true
                            }}
                            loop={true}
                            className="h-full"
                        >
                            {room.images?.map((image, index) => (
                                <SwiperSlide key={index}>
                                    <div
                                        className="w-full h-full bg-cover bg-center cursor-pointer relative"
                                        style={{ backgroundImage: `url(${image})` }}
                                        onClick={() => onShowImageGallery(room, index)}
                                    >
                                        <div className="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-200 flex items-center justify-center">
                                            <div className="opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-lg">
                                                <div className="flex items-center gap-2 text-gray-700">
                                                    <Eye size={14} />
                                                    <Text className="text-xs font-medium">Xem {room.images?.length || 1} ảnh</Text>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </SwiperSlide>
                            ))}
                        </Swiper>

                        {/* Badges */}
                        <div className="absolute top-2 left-2 z-10 flex gap-1">
                            {room.isSale && (
                                <Badge count={`-${room.discount}%`} style={{ backgroundColor: '#ef4444', fontSize: '10px' }} />
                            )}
                            {shouldShowSuggestion(room) && (
                                <Badge count="Gợi ý" style={{ backgroundColor: '#10b981', fontSize: '15px' }} />
                            )}
                        </div>
                    </div>
                </Col>

                {/* Right Side - Room Details */}
                <Col xs={24} lg={16}>
                    <div className="h-full flex flex-col">
                        {/* Room Title and TOP CHOICE badge */}
                        <div className="flex items-start justify-between gap-2 mb-3">
                            <Title
                                level={4}
                                className="mb-0 hover:text-blue-600 transition-colors cursor-pointer text-gray-800 flex-1"
                                onClick={() => onShowImageGallery(room, 0)}
                                style={{ lineHeight: '1.3', fontSize: '20px' }}
                            >
                                {room.name}
                            </Title>
                            {shouldShowSuggestion(room) && (

                                <Badge
                                    count="TOP CHOICE"
                                    style={{
                                        backgroundColor: '#f59e0b',
                                        fontSize: '15px',
                                        height: '20px',
                                        lineHeight: '20px',
                                        borderRadius: '10px'
                                    }}
                                />
                            )}
                        </div>

                        {/* Room Stats */}
                        <Space size={12} wrap className="mb-3">
                            <div className="flex items-center gap-1.5 text-gray-600">
                                <HomeOutlined style={{ fontSize: '14px', color: '#6b7280' }} />
                                <Text className="text-sm">{room.size}m²</Text>
                            </div>
                            <div className="flex items-center gap-1.5 text-gray-600">
                                <EyeOutlined style={{ fontSize: '14px', color: '#6b7280' }} />
                                <Text className="text-sm">{room.view}</Text>
                            </div>
                            <div className="flex items-center gap-1.5 text-gray-600">
                                <UserOutlined style={{ fontSize: '14px', color: '#6b7280' }} />
                                <Text className="text-sm">Tối đa {room.maxGuests} khách</Text>
                            </div>
                            {room.rating && (
                                <div className="flex items-center gap-1.5 text-gray-600">
                                    <StarFilled style={{ fontSize: '14px', color: '#f59e0b' }} />
                                    <Text className="text-sm font-medium text-gray-800">{room.rating}/10</Text>
                                </div>
                            )}
                        </Space>                        {/* Alerts */}
                        <div className="mb-3">
                            {room.urgencyRoomMessage && (
                                <Alert
                                    message={room.urgencyRoomMessage}
                                    type="warning"
                                    showIcon
                                    className="mb-2"
                                />
                            )}
                            {!isSuitable && (
                                <Alert
                                    message="Phòng này có thể không phù hợp với số lượng khách của bạn"
                                    type="info"
                                    showIcon
                                />
                            )}
                        </div>

                        {/* Amenities */}
                        <div className="mb-3">
                            <div className="flex items-center gap-1.5 mb-2">
                                <GiftOutlined style={{ fontSize: '14px', color: '#3b82f6' }} />
                                <Text className="text-sm font-medium text-gray-700">Tiện ích nổi bật</Text>
                            </div>
                            <div className="flex flex-wrap gap-1">
                                {getMainAmenities(room.mainAmenities || room.amenities).slice(0, 7).map((amenity, index) => (
                                    <span
                                        key={index}
                                        className="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs border border-blue-100"
                                    >
                                        {amenity.icon && <span style={{ fontSize: '10px' }}>{amenity.icon}</span>}
                                        <span>{amenity.name}</span>
                                    </span>
                                ))}
                                {getMainAmenities(room.mainAmenities || room.amenities).length > 7 && (
                                    <span className="inline-flex items-center px-2 py-1 bg-gray-50 text-gray-600 rounded text-xs">
                                        +{getMainAmenities(room.mainAmenities || room.amenities).length - 7} khác
                                    </span>
                                )}
                            </div>
                        </div>
                    </div>
                </Col>
            </Row>

            {/* Room Options Section */}
            <Divider style={{ margin: '16px 0 12px 0' }} />
            <div>
                <div className="flex items-center gap-2 mb-3">
                    <GiftOutlined style={{ fontSize: '14px', color: '#6366f1' }} />
                    <Text className="text-sm font-medium text-gray-700">Lựa chọn đặt phòng</Text>
                    <Badge
                        count={room.options.length}
                        style={{ backgroundColor: '#6366f1', fontSize: '10px' }}
                    />
                </div>
                <RoomOptionsSection
                    room={room}
                    selectedRooms={selectedRooms}
                    onQuantityChange={onQuantityChange}
                    formatVND={formatVND}
                    getNights={getNights}
                />
            </div>
        </Card>
    );
};

export default RoomCard;
