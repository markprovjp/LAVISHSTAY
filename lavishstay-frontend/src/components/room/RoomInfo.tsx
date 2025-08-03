// src/components/room/RoomInfo.tsx
import React from 'react';
import { Card, Tag, Badge, Avatar, Rate, Tooltip, Space, Divider , Button } from 'antd';
import { motion } from 'framer-motion';
import {
    Users,
    Square,
    Bed,
    Eye,
    MapPin,
    Clock,
    Star,
    TrendingUp,
    Calendar
} from 'lucide-react';
import { getAmenityIcon } from '../../constants/Icons';
import { RoomTypeDetail } from '../../types/roomDetail';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';

dayjs.extend(relativeTime);

interface RoomInfoProps {
    room: RoomTypeDetail;
    className?: string;
}

const RoomInfo: React.FC<RoomInfoProps> = ({ room, className = '' }) => {
    const formatPrice = (price: number) => {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND',
        }).format(price);
    };

    const discountPercent = room.originalPrice
        ? Math.round(((room.originalPrice - room.basePrice) / room.originalPrice) * 100)
        : 0;

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'available': return 'green';
            case 'sold_out': return 'red';
            case 'coming_soon': return 'orange';
            default: return 'default';
        }
    };

    const getStatusText = (status: string) => {
        switch (status) {
            case 'available': return 'Còn trống';
            case 'sold_out': return 'Hết phòng';
            case 'coming_soon': return 'Sắp mở';
            default: return status;
        }
    };

    return (
        <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5 }}
            className={`room-info ${className}`}
        >
             {room.tags.includes('Popular') && (
                                    <Badge.Ribbon text="Phổ biến" color="red">
            <Card className="border-0 shadow-lg rounded-2xl overflow-hidden">
                <div className="space-y-6">
                    {/* Header with Name and Rating */}
                    <div className="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div className="flex-1">
                            <div className="flex items-center gap-3 mb-2">
                               
                                        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
                                            {room.name}
                                        </h1>
                                   
                            </div>

                            <div className="flex items-center gap-4 flex-wrap">
                                <div className="flex items-center gap-2">
                                    <Rate
                                        disabled
                                        value={room.rating}
                                        allowHalf
                                        className="text-yellow-400"
                                    />
                                    <span className="text-lg font-semibold text-gray-900 dark:text-white">
                                        {room.rating}
                                    </span>
                                    <span className="text-gray-600 dark:text-gray-400">
                                        ({room.totalReviews} đánh giá)
                                    </span>
                                </div>

                                {room.location && (
                                    <div className="flex items-center gap-1 text-gray-600 dark:text-gray-400">
                                        <MapPin size={16} />
                                        <span>{room.location}</span>
                                        {room.floor && <span>• Tầng {room.floor}</span>}
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Status Badge */}
                        <div className="flex items-center gap-2 mt-3">
                            <Tag
                                color={getStatusColor(room.status)}
                                className="px-3 py-1 text-sm font-medium rounded-full"
                            >
                                {getStatusText(room.status)}
                            </Tag>
                            {room.availableRooms <= 3 && room.availableRooms > 0 && (
                                <Tag color="orange" className="px-3 py-1 text-sm font-medium rounded-full">
                                    Chỉ còn {room.availableRooms} phòng
                                </Tag>
                            )}
                        </div>
                    </div>

                    {/* Tags */}
                    <div className="flex flex-wrap gap-2">
                        {room.tags.map((tag, index) => (
                            <motion.div
                                key={tag}
                                initial={{ opacity: 0, scale: 0.9 }}
                                animate={{ opacity: 1, scale: 1 }}
                                transition={{ duration: 0.3, delay: index * 0.1 }}
                            >
                                <Tag
                                    color="blue"
                                    className="px-3 py-1 text-sm rounded-full border-blue-200 bg-blue-50 text-blue-700"
                                >
                                    {tag}
                                </Tag>
                            </motion.div>
                        ))}
                    </div>

                    {/* Quick Specs */}
                    <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <Tooltip title="Diện tích phòng">
                            <div className="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <div className="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                    <Square size={20} className="text-blue-600 dark:text-blue-400" />
                                </div>
                                <div>
                                    <p className="text-xs text-gray-600 dark:text-gray-400">Diện tích</p>
                                    <p className="font-semibold text-gray-900 dark:text-white">{room.area} m²</p>
                                </div>
                            </div>
                        </Tooltip>

                        <Tooltip title="Số lượng khách tối đa">
                            <div className="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <div className="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                                    <Users size={20} className="text-green-600 dark:text-green-400" />
                                </div>
                                <div>
                                    <p className="text-xs text-gray-600 dark:text-gray-400">Khách</p>
                                    <p className="font-semibold text-gray-900 dark:text-white">{room.maxGuests} người</p>
                                </div>
                            </div>
                        </Tooltip>

                        <Tooltip title="Loại giường">
                            <div className="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <div className="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                    <Bed size={20} className="text-purple-600 dark:text-purple-400" />
                                </div>
                                <div>
                                    <p className="text-xs text-gray-600 dark:text-gray-400">Giường</p>
                                    <p className="font-semibold text-gray-900 dark:text-white text-sm">{room.bedType}</p>
                                </div>
                            </div>
                        </Tooltip>

                        <Tooltip title="Tầm nhìn">
                            <div className="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <div className="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                                    <Eye size={20} className="text-orange-600 dark:text-orange-400" />
                                </div>
                                <div>
                                    <p className="text-xs text-gray-600 dark:text-gray-400">View</p>
                                    <p className="font-semibold text-gray-900 dark:text-white text-sm">{room.view}</p>
                                </div>
                            </div>
                        </Tooltip>
                    </div>

                    {/* Pricing */}
                    <div className="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-6 rounded-xl">
                        <div className="flex items-center justify-between flex-wrap gap-4">
                            <div className="flex items-baseline gap-3">
                                <span className="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                    {formatPrice(room.basePrice)}
                                </span>
                                <span className="text-gray-600 dark:text-gray-400">/ đêm</span>

                                {room.originalPrice && (
                                    <div className="flex items-center gap-2">
                                        <span className="text-lg text-gray-500 line-through">
                                            {formatPrice(room.originalPrice)}
                                        </span>
                                        <Tag color="red" className="text-sm font-medium">
                                            -{discountPercent}%
                                        </Tag>
                                    </div>
                                )}
                            </div>

                            <div className="text-right">
                                <div className="flex items-center gap-1 text-green-600 dark:text-green-400 mb-1">
                                    <TrendingUp size={16} />
                                    <span className="text-sm font-medium">Giá tốt</span>
                                </div>
                                <p className="text-xs text-gray-600 dark:text-gray-400">
                                    So với giá trung bình
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* Main Amenities */}
                    <div>
                        <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                            Tiện ích nổi bật
                        </h3>
                        <div className="flex flex-wrap gap-2">
                            {room.mainAmenities.map((amenity, index) => (
                                <Tag
                                    key={amenity}
                                    icon={getAmenityIcon(amenity)}
                                    className="px-3 py-1 border-blue-200 bg-blue-50 text-blue-700 rounded-full text-sm font-medium flex items-center"
                                    style={{ display: 'flex', alignItems: 'center', gap: 4 }}
                                >
                                    {amenity}
                                </Tag>
                            ))}
                        </div>
                    </div>

                    <Divider className="my-4" />

                    {/* Additional Info */}
                    <div className="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                        <div className="flex items-center gap-1">
                            <Calendar size={14} />
                            <span>Cập nhật: {dayjs(room.lastUpdated).fromNow()}</span>
                        </div>
                        <div className="flex items-center gap-1">
                            <Clock size={14} />
                            <span>Tổng số phòng: {room.roomCount}</span>
                        </div>
                    </div>
                </div>
            </Card>
             </Badge.Ribbon>
                                )}
        </motion.div>
    );
};

export default RoomInfo;
