// src/components/room/RoomRelated.tsx
import React from 'react';
import { Card, Tag, Rate, Button, Skeleton, Empty, Badge } from 'antd';
import { motion } from 'framer-motion';
import {
    Users,
    Ruler,
    Star,
    Eye,
    ArrowRight
} from 'lucide-react';
import { RelatedRoomType } from '../../types/roomDetail';
import { useNavigate } from 'react-router-dom';

interface RoomRelatedProps {
    rooms: RelatedRoomType[];
    loading?: boolean;
    currentRoomId?: string;
    className?: string;
}

const RoomRelated: React.FC<RoomRelatedProps> = ({
    rooms,
    loading = false,
    currentRoomId,
    className = ''
}) => {
    const navigate = useNavigate();

    const formatPrice = (price: number) => {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND',
        }).format(price);
    };

    const handleRoomClick = (roomSlug: string) => {
        navigate(`/room-types/${roomSlug}`);
    };

    const filteredRooms = rooms.filter(room => room.id !== currentRoomId);

    const RoomCard: React.FC<{ room: RelatedRoomType; index: number }> = ({
        room,
        index
    }) => {
        const discountPercent = room.originalPrice
            ? Math.round(((room.originalPrice - room.basePrice) / room.originalPrice) * 100)
            : 0;

        return (
            <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.5, delay: index * 0.1 }}
                whileHover={{ y: -5 }}
                className="group"
            >
                <Badge.Ribbon
                    text={room.isPopular ? "Phổ biến" : undefined}
                    color="red"
                    placement="end"
                    style={room.isPopular ? { zIndex: 2 } : { display: 'none' }}
                    className='mt-2'
                >
                    <Card
                        className="h-full hover:shadow-xl transition-all duration-300 border-0 overflow-hidden cursor-pointer min-w-[220px] max-w-[320px] p-0"
                        bodyStyle={{ padding: 12 }}
                        onClick={() => handleRoomClick(room.slug)}
                    >
                        {/* Image */}
                        <div className="relative overflow-hidden rounded-lg">
                            <img
                                src={room.mainImage}
                                alt={room.name}
                                className="h-32 w-full object-cover transition-transform duration-500 group-hover:scale-105 rounded-lg"
                            />

                            {/* Overlay */}
                            <div className="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg" />

                            {/* Badges */}
                            <div className="absolute top-2 left-2 flex flex-col gap-1 z-10">
                                {discountPercent > 0 && (
                                    <Tag color="red" className="px-1 py-0.5 text-[11px] font-medium rounded-full">
                                        -{discountPercent}%
                                    </Tag>
                                )}
                            </div>

                            {/* Availability */}
                            {/* <div className="absolute top-2 right-2 z-10">
                                <Tag
                                    color={room.availableRooms > 0 ? 'green' : 'red'}
                                    className="px-1 py-0.5 text-[11px] font-medium rounded-full"
                                >
                                    {room.availableRooms > 0 ? `${room.availableRooms} phòng` : 'Hết phòng'}
                                </Tag>
                            </div> */}

                            {/* Quick Action Button */}
                            <div className="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                                <Button
                                    type="primary"
                                    shape="circle"
                                    icon={<ArrowRight size={14} />}
                                    className="bg-white/90 hover:bg-white text-gray-800 border-0 shadow-lg"
                                    size="small"
                                    onClick={(e) => {
                                        e.stopPropagation();
                                        handleRoomClick(room.slug);
                                    }}
                                />
                            </div>
                        </div>

                        {/* Content */}
                        <div className="pt-2 space-y-2">
                            {/* Title and Rating */}
                            <div>
                                <h3 className="text-base font-semibold text-gray-900 dark:text-white mb-1 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                    {room.name}
                                </h3>
                                <div className="flex items-center gap-1">
                                    <Rate disabled value={room.rating} className="text-xs" style={{ fontSize: 13 }} />
                                    <span className="text-xs font-medium text-gray-600 dark:text-gray-400">
                                        {room.rating}
                                    </span>
                                    <span className="text-[11px] text-gray-500 dark:text-gray-500">
                                        ({room.totalReviews} đánh giá)
                                    </span>
                                </div>
                            </div>

                            {/* Quick Specs */}
                            <div className="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                                <div className="flex items-center gap-1">
                                    <Ruler size={13} />
                                    <span>{room.area} m²</span>
                                </div>
                                <div className="flex items-center gap-1">
                                    <Users size={12} />
                                    <span>{room.maxGuests} khách</span>
                                </div>
                            </div>

                            {/* Main Amenities */}
                            <div className="flex flex-wrap gap-1">
                                {room.mainAmenities.slice(0, 3).map((amenity, idx) => (
                                    <Tag
                                        key={idx}
                                        className="text-[11px] px-1 py-0 border-blue-200 bg-blue-50 text-blue-700 rounded-full"
                                    >
                                        {amenity}
                                    </Tag>
                                ))}
                                {room.mainAmenities.length > 3 && (
                                    <Tag className="text-[11px] px-1 py-0 border-gray-200 bg-gray-50 text-gray-600 rounded-full">
                                        +{room.mainAmenities.length - 3}
                                    </Tag>
                                )}
                            </div>

                            {/* Pricing - vertical, right aligned, small font */}
                            <div className="flex flex-col items-end pt-2 border-t border-gray-100 dark:border-gray-700 gap-0.5 min-h-[44px]">
                                <span className="text-lg font-bold text-blue-600 dark:text-blue-400 whitespace-nowrap">
                                    {formatPrice(room.basePrice)} <span className="text-xs text-gray-500">/đêm</span>
                                </span>
                                {room.originalPrice ? (
                                    <span className="text-xs text-gray-500 line-through whitespace-nowrap block">
                                        {formatPrice(room.originalPrice)}
                                    </span>
                                ) : (
                                    <span className="text-xs opacity-0 select-none block">placeholder</span>
                                )}
                            </div>

                            {/* Action Button */}
                            <Button
                                type="primary"
                                block
                                className="mt-2 h-8 text-sm font-medium"
                                onClick={(e) => {
                                    e.stopPropagation();
                                    handleRoomClick(room.slug);
                                }}
                            >
                                Xem chi tiết
                            </Button>
                        </div>
                    </Card>
                </Badge.Ribbon>
            </motion.div>
        );
    };

    if (loading) {
        return (
            <div className={`room-related ${className}`}>
                <Card className="border-0 shadow-lg rounded-2xl">
                    <div className="space-y-6">
                        <Skeleton active title={{ width: '50%' }} paragraph={{ rows: 1 }} />
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {[...Array(3)].map((_, index) => (
                                <div key={index} className="space-y-4">
                                    <Skeleton.Image className="w-full h-48" />
                                    <Skeleton active paragraph={{ rows: 3 }} />
                                </div>
                            ))}
                        </div>
                    </div>
                </Card>
            </div>
        );
    }

    return (
        <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.5 }}
            className={`room-related ${className}`}
        >
            <Card className="border-0 shadow-lg rounded-2xl overflow-hidden">
                <div className="space-y-6">
                    {/* Header */}
                    <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 className="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <Star size={24} className="text-yellow-500" />
                                Phòng tương tự
                            </h2>
                            <p className="text-gray-600 dark:text-gray-400 mt-1">
                                Khám phá các loại phòng khác có thể bạn quan tâm
                            </p>
                        </div>

                        <div className="flex items-center gap-2">
                            <Tag color="blue" className="px-3 py-1 text-sm font-medium ">
                                {filteredRooms.length} phòng
                            </Tag>
                        </div>
                    </div>

                    {/* Rooms Grid */}
                    {filteredRooms.length > 0 ? (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {filteredRooms.map((room, index) => (
                                <RoomCard
                                    key={room.id}
                                    room={room}
                                    index={index}
                                />
                            ))}
                        </div>
                    ) : (
                        <Empty
                            description="Không có phòng tương tự"
                            className="py-12"
                        />
                    )}

                    {/* View All Button */}
                    {filteredRooms.length > 0 && (
                        <div className="text-center pt-6 border-t border-gray-100 dark:border-gray-700">
                            <Button
                                type="primary"
                                ghost
                                size="large"
                                icon={<Eye size={18} />}
                                className="px-8"
                                onClick={() => navigate('/room-types')}
                            >
                                Xem tất cả loại phòng
                            </Button>
                        </div>
                    )}
                </div>
            </Card>

            <style>{`
                .line-clamp-2 {
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                }
            `}</style>
        </motion.div>
    );
};

export default RoomRelated;
