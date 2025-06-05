import React, { useState } from 'react';
import { Card, Typography, Divider, Button, Tag, Badge, Radio } from 'antd';
import {
    GiftOutlined,
    CalendarOutlined,
    CheckCircleOutlined,
    CloseOutlined
} from '@ant-design/icons';
import { Coffee, Users, Bed } from 'lucide-react';

const { Title, Text } = Typography;

interface BookingSummaryProps {
    selectedRooms: { [roomId: string]: { [optionId: string]: number } };
    rooms: any[];
    formatVND: (price: number) => string;
    getNights: () => number;
    searchData: any;
    onQuantityChange: (roomId: string, optionId: string, quantity: number) => void;
}

const BookingSummary: React.FC<BookingSummaryProps> = ({
    selectedRooms,
    rooms,
    formatVND,
    getNights,
    searchData,
    onQuantityChange
}) => {
    const [breakfastOption, setBreakfastOption] = useState('none');
    const [bedPreference, setBedPreference] = useState('double');
    const nights = getNights();

    const handleRemoveRoom = (roomId: string, optionId: string) => {
        onQuantityChange(roomId, optionId, 0);
    }; const getSelectedRoomsSummary = () => {
        const summary: any[] = [];
        let totalPrice = 0;

        Object.entries(selectedRooms).forEach(([roomId, options]) => {
            const room = rooms.find(r => r.id.toString() === roomId);
            if (!room) return;

            Object.entries(options).forEach(([optionId, quantity]) => {
                if (quantity > 0) {
                    const option = room.options.find((opt: any) => opt.id === optionId);
                    if (option) {
                        // Use correct price format: pricePerNight.vnd or fallback to price
                        const pricePerNight = option.pricePerNight?.vnd || option.price || 0;
                        const roomTotal = pricePerNight * quantity * nights;
                        totalPrice += roomTotal; summary.push({
                            roomName: room.name,
                            optionName: option.name,
                            quantity,
                            pricePerNight,
                            total: roomTotal,
                            roomId: roomId,
                            optionId: optionId
                        });
                    }
                }
            });
        });

        return { summary, totalPrice };
    };

    const getBreakfastPrice = () => {
        const guestCount = (searchData.guestDetails?.adults || 0) + (searchData.guestDetails?.children || 0);
        switch (breakfastOption) {
            case 'standard':
                return 260000 * guestCount * nights;
            case 'premium':
                return 500000 * guestCount * nights;
            default:
                return 0;
        }
    };

    const { summary, totalPrice } = getSelectedRoomsSummary();
    const breakfastPrice = getBreakfastPrice();
    const finalTotal = totalPrice + breakfastPrice;

    if (summary.length === 0) {
        return null;
    } return (<Card
        className="bs-booking-summary-card sticky top-4 shadow-md border border-gray-200"
        style={{ borderRadius: '8px' }}
        bodyStyle={{ padding: '16px' }}
    >
        <div className="mb-4">
            <div className="flex items-center gap-2 mb-3">
                <CheckCircleOutlined className="text-blue-600 text-lg" />
                <Title level={5} className="mb-0 text-gray-800">Tóm tắt đặt phòng</Title>
            </div>

            <div className="bg-gray-50 p-3 rounded-lg">
                <div className="flex items-center justify-between text-sm">
                    <div className="flex items-center gap-2">
                        <CalendarOutlined className="text-gray-600" />
                        <Text className="text-gray-700 font-medium">{nights} đêm</Text>
                    </div>
                    <div className="flex items-center gap-2">
                        <Users size={14} className="text-gray-600" />
                        <Text className="text-gray-700 font-medium">
                            {(searchData.guestDetails?.adults || 0) + (searchData.guestDetails?.children || 0)} khách
                        </Text>
                    </div>
                </div>
            </div>
        </div>            {/* Selected Rooms */}
        <div className="mb-4">
            <div className="flex items-center justify-between mb-3">
                <Text strong className="text-gray-800 text-sm">Phòng đã chọn</Text>
                <Text className="text-xs text-gray-500">{summary.length} loại</Text>
            </div>

            <div
                className={`space-y-2 ${summary.length > 2 ? 'max-h-32 overflow-y-auto pr-2' : ''}`}
                style={summary.length > 2 ? {
                    scrollbarWidth: 'thin',
                    scrollbarColor: '#cbd5e1 #f1f5f9'
                } : {}}
            >                {summary.map((item, index) => (
                <div key={index} className="bg-white border border-gray-200 p-2 rounded-lg shadow-sm">
                    <div className="flex justify-between items-start mb-1">
                        <div className="flex-1">
                            <Text strong className="text-gray-800 block text-xs leading-tight">{item.roomName}</Text>
                            <Text className="text-xs text-gray-500 leading-tight">{item.optionName}</Text>
                        </div>
                        <div className="flex items-center gap-1">
                            <Tag color="blue" className="text-xs px-1 py-0">x{item.quantity}</Tag>
                            <Button
                                type="text"
                                size="small"
                                icon={<CloseOutlined />}
                                onClick={() => handleRemoveRoom(item.roomId, item.optionId)}
                                className="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full p-0"
                                style={{ minWidth: '24px' }}
                            />
                        </div>
                    </div>
                    <div className="flex justify-between items-center">
                        <Text className="text-xs text-gray-500">
                            {formatVND(item.pricePerNight)}/đêm
                        </Text>
                        <Text strong className="text-blue-600 text-xs">{formatVND(item.total)}</Text>
                    </div>
                </div>
            ))}
            </div>
        </div><Divider className="my-4" />        {/* Breakfast Options */}
        <div className="mb-4">
            <div className="flex items-center gap-2 mb-3">
                <Coffee size={14} className="text-amber-600" />
                <Text strong className="text-gray-800 text-sm">Bữa sáng</Text>
            </div>

            <Radio.Group
                value={breakfastOption}
                onChange={(e) => setBreakfastOption(e.target.value)}
                className="w-full"
            >
                <div className="space-y-2">
                    <div className="flex items-center justify-between">
                        <Radio value="none" className="text-sm">
                            Không ăn sáng
                        </Radio>
                        <Badge count="Miễn phí" style={{ backgroundColor: '#52c41a' }} />
                    </div>

                    <div className="flex items-center justify-between">
                        <Radio value="standard" className="text-sm">
                            Ăn sáng
                        </Radio>
                        <Badge count="260K/người" style={{ backgroundColor: '#1890ff' }} />
                    </div>

                    <div className="flex items-center justify-between">
                        <Radio value="premium" className="text-sm">
                            Ăn sáng VIP
                        </Radio>
                        <Badge count="500K/người" style={{ backgroundColor: '#fa541c' }} />
                    </div>
                </div>
            </Radio.Group>

            {breakfastPrice > 0 && (
                <div className="mt-3 p-2 bg-amber-50 rounded-lg border border-amber-200">
                    <div className="flex justify-between items-center">
                        <Text className="text-xs text-amber-700">
                            Tổng bữa sáng ({(searchData.guestDetails?.adults || 0) + (searchData.guestDetails?.children || 0)} người × {nights} đêm)
                        </Text>
                        <Text strong className="text-amber-700 text-xs">{formatVND(breakfastPrice)}</Text>
                    </div>
                </div>
            )}
        </div>

        <Divider className="my-4" />        {/* Bed Preference */}
        <div className="mb-4">
            <div className="flex items-center gap-2 mb-3">
                <Bed size={14} className="text-indigo-600" />
                <Text strong className="text-gray-800 text-sm">Loại giường</Text>
            </div>

            <Radio.Group
                value={bedPreference}
                onChange={(e) => setBedPreference(e.target.value)}
                className="w-full"
            >
                <div className="grid grid-cols-2 gap-2">
                    <Radio value="double" className="text-sm">
                        Giường đôi
                    </Radio>
                    <Radio value="single" className="text-sm">
                        Giường đơn
                    </Radio>
                </div>
            </Radio.Group>
        </div>

        <Divider className="my-4" />            {/* Total */}
        <div className="space-y-3">
            {breakfastPrice > 0 && (
                <div className="flex justify-between items-center text-sm">
                    <Text className="text-gray-600">Phụ thu bữa sáng:</Text>
                    <Text strong className="text-amber-600">{formatVND(breakfastPrice)}</Text>
                </div>
            )}

            <div className="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                <div className="flex justify-between items-center">
                    <Text strong className="text-lg text-gray-800">Tổng thanh toán:</Text>
                    <Text strong className="text-2xl text-blue-600">{formatVND(finalTotal)}</Text>
                </div>
                {nights > 1 && (
                    <Text className="text-xs text-gray-500 mt-1">
                        Trung bình {formatVND(finalTotal / nights)}/đêm
                    </Text>
                )}
            </div>
        </div><Button
            type="primary"
            size="large"
            className="w-full mt-4 h-10 bg-blue-600 hover:bg-blue-700 border-none shadow-md hover:shadow-lg rounded-lg font-semibold"
            icon={<GiftOutlined />}
        >
            TIẾP TỤC ĐẶT PHÒNG
        </Button>
    </Card>
    );
};

export default BookingSummary;
