import React from 'react';
import { Card, Typography, Divider, Alert, Space, Row, Col, Tag } from 'antd';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Navigation, Pagination } from 'swiper/modules';
import {
    StarFilled,
    GiftOutlined,
    HomeOutlined,
    UserOutlined,
    EyeOutlined,
    InfoCircleOutlined,
    CrownOutlined,
    FireOutlined,
    ThunderboltOutlined
} from '@ant-design/icons';
import { Eye } from 'lucide-react';
import { formatAmenitiesForDisplay } from '../../constants/amenities';
import RoomOptionsSection from './RoomOptionsSection';
import { Room } from '../../types/room';

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
    onShowImageGallery: (room: Room, index?: number) => void;
    onShowRoomDetail?: (room: Room) => void;
    onViewDetail?: (roomId: string) => void;
    onBookNow?: (roomId: string) => void;
    shouldShowSuggestion: () => boolean;
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
        : true;

    const getCapacityWarningMessage = () => {
        if (!searchData.guestDetails) return null;
        const totalGuests = searchData.guestDetails.adults + searchData.guestDetails.children;
        if (totalGuests > room.maxGuests) {
            const additionalGuests = totalGuests - room.maxGuests;
            return `Phòng này phù hợp cho ${room.maxGuests} khách. Bạn có thể đặt phòng này và cần thêm chỗ ở cho ${additionalGuests} khách.`;
        }
        return null;
    }; const getMainAmenities = (amenities: string[]) => {
        return formatAmenitiesForDisplay(amenities);
    }; return (
        <Card
            className="room-card transition-shadow duration-200 w-full"
            bodyStyle={{ padding: '20px' }}
        >
            <Row gutter={24} className="w-full">
                {/* Left Side - Images */}
                <Col xs={24} lg={8}>
                    <div className="relative h-48 lg:h-64 rounded-lg overflow-hidden group">
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
                                        <div className="absolute inset-0 transition-colors duration-200 flex items-center justify-center">
                                            <div className="opacity-0 group-hover:opacity-100 transition-opacity duration-200 backdrop-blur-sm px-3 py-1.5 rounded-lg">
                                                <div className="flex items-center gap-2">
                                                    <Eye size={14} />
                                                    <Text className="text-xs font-medium text-white">Xem {room.images?.length || 1} ảnh</Text>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </SwiperSlide>
                            ))}
                        </Swiper>

                        {/* Badges */}
                        <div className="absolute top-3 left-3 z-10 flex flex-col gap-2">
                            {room.isSale && (
                                <div className="relative">
                                    <div className="bg-gradient-to-r from-red-500 to-pink-500 text-white px-3 py-1.5 rounded-lg shadow-lg flex items-center gap-1.5 border border-red-300">
                                        <FireOutlined className="text-sm animate-pulse" />
                                        <span className="text-sm font-bold">-{room.discount}%</span>
                                    </div>
                                    <div className="absolute -top-1 -right-1 w-2 h-2 bg-yellow-400 rounded-full animate-ping"></div>                                </div>
                            )}
                            {shouldShowSuggestion() && (
                                <div className="relative">
                                    <div className="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-3 py-1.5 rounded-lg shadow-lg flex items-center gap-1.5 border border-emerald-300">
                                        <ThunderboltOutlined className="text-sm" />
                                        <span className="text-sm font-bold">Gợi ý</span>
                                    </div>
                                    <div className="absolute inset-0 bg-gradient-to-r from-emerald-400 to-teal-400 rounded-lg blur-sm opacity-50 -z-10"></div>
                                </div>
                            )}
                        </div>
                    </div>
                </Col>

                {/* Right Side - Room Details */}
                <Col xs={24} lg={16}>
                    <div className="h-full flex flex-col">
                        {/* Room Title and TOP CHOICE badge */}
                        <div className="flex items-start justify-between gap-3 mb-4">
                            <Title
                                level={3}
                                className="mb-0 transition-colors cursor-pointer flex-1"
                                onClick={() => onShowImageGallery(room, 0)}
                                style={{ lineHeight: '1.3', fontSize: '24px' }}                            >
                                {room.name}
                            </Title>

                            {shouldShowSuggestion() && (
                                <Tag
                                    color="gold"
                                    icon={<CrownOutlined />}
                                    style={{
                                        borderRadius: '20px',
                                        padding: '6px 16px',
                                        fontSize: '12px',
                                        fontWeight: '600',
                                        border: '2px solid #ffd700',
                                        background: 'linear-gradient(135deg, #fff9c4 0%, #fef3c7 100%)',
                                        color: '#b45309',
                                        boxShadow: '0 2px 8px rgba(245, 158, 11, 0.15)',
                                        textTransform: 'uppercase',
                                        letterSpacing: '0.5px'
                                    }}
                                >
                                    TOP CHOICE
                                </Tag>
                            )}
                        </div>

                        {/* Room Stats */}
                        <Space size={16} wrap className="mb-4">
                            <div className="flex items-center gap-2">
                                <HomeOutlined style={{ fontSize: '16px', color: '#6b7280' }} />
                                <Text className="text-base">{room.size}m²</Text>
                            </div>
                            <div className="flex items-center gap-2">
                                <EyeOutlined style={{ fontSize: '16px', color: '#6b7280' }} />
                                <Text className="text-base">{room.view}</Text>
                            </div>
                            <div className="flex items-center gap-2">
                                <UserOutlined style={{ fontSize: '16px', color: '#6b7280' }} />
                                <Text className="text-base">Tối đa {room.maxGuests} khách</Text>
                            </div>
                            {room.rating && (
                                <div className="flex items-center gap-2">
                                    <StarFilled style={{ fontSize: '16px', color: '#f59e0b' }} />
                                    <Text className="text-base font-medium">{room.rating}/10</Text>
                                </div>
                            )}
                        </Space>

                        {/* Alerts */}
                        <div className="mb-4">
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
                                    message={getCapacityWarningMessage() || "Phòng này có thể không phù hợp với số lượng khách của bạn"}
                                    type="info"
                                    showIcon
                                    className="mb-2"
                                />
                            )}
                        </div>

                        {/* Amenities */}
                        <div className="mb-4">
                            <div className="flex items-center gap-2 mb-3">
                                <GiftOutlined style={{ fontSize: '16px', color: '#3b82f6' }} />
                                <Text className="text-base font-medium">Tiện ích nổi bật</Text>
                            </div>
                            <div className="flex flex-wrap gap-2">
                                {getMainAmenities(room.mainAmenities || room.amenities).slice(0, 12).map((amenity, index) => (
                                    <span
                                        key={index}
                                        className="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm border border-blue-100"
                                    >
                                        {amenity.icon && <span style={{ fontSize: '12px' }}>{amenity.icon}</span>}
                                        <span>{amenity.name}</span>
                                    </span>
                                ))}
                                {getMainAmenities(room.mainAmenities || room.amenities).length > 12 && (
                                    <span className="inline-flex items-center px-3 py-2 bg-gray-50 text-gray-600 rounded-lg text-sm">
                                        +{getMainAmenities(room.mainAmenities || room.amenities).length - 12} khác
                                    </span>
                                )}
                            </div>
                        </div>
                    </div>
                </Col>
            </Row>

            {/* Room Options Section - Full Width */}
            <Divider style={{ margin: '20px 0 16px 0' }} />
            <div>
                <div className="flex items-center gap-2 mb-4">
                    <GiftOutlined style={{ fontSize: '16px', color: '#6366f1' }} />
                    <Text className="text-base font-medium">Lựa chọn đặt phòng</Text>
                    {room.options.length > 2 && (
                        <Tag color="blue" icon={<InfoCircleOutlined />}>
                            Cuộn để xem thêm
                        </Tag>
                    )}
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