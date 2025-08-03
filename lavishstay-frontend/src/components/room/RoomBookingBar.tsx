// src/components/room/RoomBookingBar.tsx
import React, { useState, useEffect } from 'react';
import { Card, Button, DatePicker, InputNumber, Select, Divider, Tag, Space, Affix } from 'antd';
import { motion, AnimatePresence } from 'framer-motion';
import {
    Calendar,
    Users,
    CreditCard,
    Gift,
    Clock,
    Info,
    Phone,
    MessageCircle
} from 'lucide-react';
import { RoomTypeDetail, RoomBookingInfo, RoomOffer } from '../../types/roomDetail';
import { useRoomDetailStore } from '../../stores/roomDetailStore';
import dayjs, { Dayjs } from 'dayjs';

const { RangePicker } = DatePicker;
const { Option } = Select;

interface RoomBookingBarProps {
    room: RoomTypeDetail;
    className?: string;
    position?: 'fixed' | 'sticky' | 'static';
}

const RoomBookingBar: React.FC<RoomBookingBarProps> = ({
    room,
    className = '',
    position = 'sticky'
}) => {
    const [affixed, setAffixed] = useState(false);
    const [dates, setDates] = useState<[Dayjs | null, Dayjs | null] | null>(null);
    const { bookingInfo, updateBookingInfo } = useRoomDetailStore();

    const formatPrice = (price: number) => {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND',
        }).format(price);
    };

    const calculateTotalPrice = () => {
        if (!dates || !dates[0] || !dates[1]) return 0;
        const nights = dates[1].diff(dates[0], 'day');
        return room.basePrice * nights;
    };

    const calculateNights = () => {
        if (!dates || !dates[0] || !dates[1]) return 0;
        return dates[1].diff(dates[0], 'day');
    };

    const handleDateChange = (values: [Dayjs | null, Dayjs | null] | null) => {
        setDates(values);
        if (values && values[0] && values[1]) {
            updateBookingInfo({
                checkInDate: values[0].format('YYYY-MM-DD'),
                checkOutDate: values[1].format('YYYY-MM-DD'),
                nights: values[1].diff(values[0], 'day'),
                totalPrice: calculateTotalPrice(),
            });
        }
    };

    const handleGuestsChange = (guests: number | null) => {
        if (guests) {
            updateBookingInfo({ guests });
        }
    };

    const handleBooking = () => {
        // Navigate to booking page or open booking modal
        console.log('Booking data:', {
            roomId: room.id,
            roomName: room.name,
            ...bookingInfo,
            checkInDate: dates?.[0]?.format('YYYY-MM-DD'),
            checkOutDate: dates?.[1]?.format('YYYY-MM-DD'),
            nights: calculateNights(),
            totalPrice: calculateTotalPrice(),
        });
    };

    const handleContactUs = () => {
        // Open contact modal or navigate to contact page
        console.log('Contact us clicked');
    };

    const disabledDate = (current: Dayjs) => {
        return current && current < dayjs().startOf('day');
    };

    const mockOffers: RoomOffer[] = [
        {
            id: '1',
            title: 'Đặt sớm - Giảm 10%',
            description: 'Đặt trước 30 ngày để nhận ưu đãi',
            discount: 10,
            validUntil: '2024-12-31',
        },
        {
            id: '2',
            title: 'Lưu trú dài hạn',
            description: 'Giảm 15% cho lưu trú từ 5 đêm trở lên',
            discount: 15,
        }
    ];

    const BookingContent = () => (
        <Card className="border-0 shadow-xl rounded-2xl overflow-hidden bg-white dark:bg-gray-800">
            <div className="space-y-6">
                {/* Header */}
                <div className="text-center">
                    <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-2">
                        Đặt phòng ngay 
                    </h3>
                    <div className="flex items-center justify-center gap-2">
                        <span className="text-2xl font-bold text-blue-600 dark:text-blue-400">
                            {formatPrice(room.basePrice)}
                        </span>
                        <span className="text-gray-500 dark:text-gray-400">/đêm</span>
                    </div>
                    {room.originalPrice && (
                        <div className="text-center mt-1">
                            <span className="text-sm text-gray-500 line-through">
                                {formatPrice(room.originalPrice)}
                            </span>
                            <Tag color="red" className="ml-2 text-xs">
                                -{Math.round(((room.originalPrice - room.basePrice) / room.originalPrice) * 100)}%
                            </Tag>
                        </div>
                    )}
                </div>

                {/* Status */}
                <div className="text-center">
                    <Tag
                        color={room.availableRooms > 0 ? 'green' : 'red'}
                        className="px-3 py-1 text-sm font-medium rounded-full"
                    >
                        {room.availableRooms > 0
                            ? `Còn ${room.availableRooms} phòng trống`
                            : 'Hết phòng'
                        }
                    </Tag>
                    {room.availableRooms > 0 && room.availableRooms <= 3 && (
                        <p className="text-xs text-orange-600 dark:text-orange-400 mt-1">
                            Đặt ngay để không bỏ lỡ!
                        </p>
                    )}
                </div>

                {/* Date Selection */}
                <div>
                    <div className="flex items-center gap-2 mb-3">
                        <Calendar size={16} className="text-blue-600 dark:text-blue-400" />
                        <span className="font-medium text-gray-900 dark:text-white">
                            Chọn ngày
                        </span>
                    </div>
                    <RangePicker
                        className="w-full h-12"
                        placeholder={['Ngày nhận phòng', 'Ngày trả phòng']}
                        disabledDate={disabledDate}
                        value={dates}
                        onChange={handleDateChange}
                        format="DD/MM/YYYY"
                    />
                </div>

                {/* Guests Selection */}
                <div>
                    <div className="flex items-center gap-2 mb-3">
                        <Users size={16} className="text-blue-600 dark:text-blue-400" />
                        <span className="font-medium text-gray-900 dark:text-white">
                            Số khách
                        </span>
                    </div>
                    <InputNumber
                        className="w-full h-12"
                        placeholder="Chọn số khách"
                        min={1}
                        max={room.maxGuests}
                        value={bookingInfo.guests}
                        onChange={handleGuestsChange}
                        addonAfter={`tối đa ${room.maxGuests}`}
                    />
                </div>

                {/* Special Offers */}
                {mockOffers.length > 0 && (
                    <div>
                        <div className="flex items-center gap-2 mb-3">
                            <Gift size={16} className="text-green-600 dark:text-green-400" />
                            <span className="font-medium text-gray-900 dark:text-white">
                                Ưu đãi đặc biệt
                            </span>
                        </div>
                        <div className="space-y-2">
                            {mockOffers.map((offer) => (
                                <div
                                    key={offer.id}
                                    className="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg"
                                >
                                    <div className="flex items-center justify-between">
                                        <div>
                                            <h4 className="font-medium text-green-800 dark:text-green-200 text-sm">
                                                {offer.title}
                                            </h4>
                                            <p className="text-xs text-green-600 dark:text-green-400">
                                                {offer.description}
                                            </p>
                                        </div>
                                        <Tag color="green" className="text-xs">
                                            -{offer.discount}%
                                        </Tag>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                )}

                {/* Price Summary */}
                <AnimatePresence>
                    {dates && dates[0] && dates[1] && (
                        <motion.div
                            initial={{ opacity: 0, height: 0 }}
                            animate={{ opacity: 1, height: 'auto' }}
                            exit={{ opacity: 0, height: 0 }}
                            className="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl"
                        >
                            <h4 className="font-semibold text-gray-900 dark:text-white mb-2">
                                Chi tiết giá
                            </h4>
                            <div className="space-y-2 text-sm">
                                <div className="flex justify-between">
                                    <span className="text-gray-600 dark:text-gray-400">
                                        {formatPrice(room.basePrice)} × {calculateNights()} đêm
                                    </span>
                                    <span className="font-medium">
                                        {formatPrice(calculateTotalPrice())}
                                    </span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-gray-600 dark:text-gray-400">
                                        Phí dịch vụ
                                    </span>
                                    <span className="font-medium">Miễn phí</span>
                                </div>
                                <Divider className="my-2" />
                                <div className="flex justify-between text-lg font-bold">
                                    <span>Tổng cộng</span>
                                    <span className="text-blue-600 dark:text-blue-400">
                                        {formatPrice(calculateTotalPrice())}
                                    </span>
                                </div>
                            </div>
                        </motion.div>
                    )}
                </AnimatePresence>

                {/* Action Buttons */}
                <div className="space-y-3">
                    <Button
                        type="primary"
                        size="large"
                        block
                        icon={<CreditCard size={18} />}
                        className="h-12 font-semibold"
                        disabled={!dates || !dates[0] || !dates[1] || room.availableRooms === 0}
                        onClick={handleBooking}
                    >
                        {room.availableRooms === 0 ? 'Hết phòng' : 'Đặt ngay'}
                    </Button>

                    <div className="grid grid-cols-2 gap-2">
                        <Button
                            icon={<Phone size={16} />}
                            className="h-10"
                            onClick={handleContactUs}
                        >
                            Gọi ngay
                        </Button>
                        <Button
                            icon={<MessageCircle size={16} />}
                            className="h-10"
                            onClick={handleContactUs}
                        >
                            Chat
                        </Button>
                    </div>
                </div>

                {/* Info */}
                <div className="text-center text-xs text-gray-500 dark:text-gray-400">
                    <div className="flex items-center justify-center gap-1 mb-1">
                        <Info size={12} />
                        <span>Miễn phí hủy trong 24h</span>
                    </div>
                    <div className="flex items-center justify-center gap-1">
                        <Clock size={12} />
                        <span>Xác nhận ngay lập tức</span>
                    </div>
                </div>
            </div>
        </Card>
    );

    if (position === 'fixed') {
        return (
            <div className={`fixed bottom-0 left-0 right-0 z-50 p-4 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 ${className}`}>
                <div className="max-w-md mx-auto">
                    <BookingContent />
                </div>
            </div>
        );
    }

    if (position === 'sticky') {
        return (
            <Affix offsetTop={100} onChange={setAffixed}>
                <motion.div
                    animate={{
                        scale: affixed ? 0.95 : 1,
                        y: affixed ? -5 : 0
                    }}
                    transition={{ duration: 0.2 }}
                    className={`room-booking-bar ${className}`}
                >
                    <BookingContent />
                </motion.div>
            </Affix>
        );
    }

    return (
        <div className={`room-booking-bar ${className}`}>
            <BookingContent />
        </div>
    );
};

export default RoomBookingBar;
