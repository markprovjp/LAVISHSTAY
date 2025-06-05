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
    } return (
        <Card
            className="bs-booking-summary-card shadow-lg border border-gray-200"
            style={{
                borderRadius: '12px',
                overflow: 'hidden'
            }}
            bodyStyle={{
                padding: '20px',
            }}
        >
            <div className="mb-2">

                <div className=" p-3 rounded-lg">
                    <div className="flex items-center justify-between text-sm">
                        <div className="flex items-center gap-2">
                            <CalendarOutlined className="" />
                            <Text className=" font-medium">{nights} đêm</Text>
                        </div>
                        <div className="flex items-center gap-2">
                            <Users size={14} className="" />
                            <Text className=" font-medium">
                                {(searchData.guestDetails?.adults || 0) + (searchData.guestDetails?.children || 0)} khách
                            </Text>
                        </div>
                    </div>
                </div>
            </div>            {/* Selected Rooms */}
            <div className="mb-6">
                <div className="flex items-center justify-between mb-4">
                    <div className="flex items-center gap-2">
                        <div className="w-2 h-2  rounded-full"></div>
                        <Text strong className=" font-semibold">Phòng đã chọn</Text>
                    </div>
                    <Badge
                        count={summary.length}
                        style={{
                            fontSize: '11px',
                            height: '18px',
                            minWidth: '18px',
                            lineHeight: '18px'
                        }}
                    />
                </div>

                <div
                    className={`space-y-3 ${summary.length > 2 ? 'max-h-40 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-300 scrollbar-track-slate-100 pr-2' : ''}`}
                    style={summary.length > 2 ? {
                        border: '1px solid #e2e8f0',
                        borderRadius: '12px',
                        padding: '12px'
                    } : {}}
                >
                    {summary.map((item, index) => (
                        <div key={index} className="group  border border-slate-200  p-4 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 ease-in-out">
                            <div className="flex justify-between items-start mb-3">
                                <div className="flex-1 min-w-0">
                                    <div className="flex items-center gap-2 mb-1">
                                        <Bed size={14} className=" flex-shrink-0" />
                                        <Text strong className=" font-semibold truncate">
                                            {item.roomName}
                                        </Text>
                                    </div>
                                    <Text className="text-xs  font-medium ml-5 truncate">
                                        {item.optionName}
                                    </Text>
                                </div>
                                <div className="flex items-center gap-2 ml-3">
                                    <div className="  px-3 py-1 rounded-full text-xs font-semibold">
                                        ×{item.quantity}
                                    </div>
                                    <Button
                                        type="text"
                                        size="small"
                                        icon={<CloseOutlined />}
                                        onClick={() => handleRemoveRoom(item.roomId, item.optionId)}
                                        className="w-7 h-7 flex items-center justify-center  hover:text-red-500 hover:bg-red-50 rounded-full p-0 transition-all duration-200 opacity-0 group-hover:opacity-100"
                                        style={{ minWidth: '28px' }}
                                    />
                                </div>
                            </div>
                            <div className="flex justify-between items-center pt-2 border-t border-slate-100">
                                <Text className="text-xs  font-medium">
                                    {formatVND(item.pricePerNight)}/đêm
                                </Text>
                                <Text strong className=" font-bold">
                                    {formatVND(item.total)}
                                </Text>
                            </div>
                        </div>
                    ))}
                </div>
            </div><Divider className="my-4" />        {/* Breakfast Options */}
            <div className="mb-4">
                <div className="flex items-center gap-2 mb-3">
                    <Coffee size={14} className="text-amber-600" />
                    <Text strong className=" text-sm">Bữa sáng</Text>
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
                    <Text strong className=" text-sm">Ưu Tiên Giường</Text>
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

            <Divider className="" />            {/* Total */}
            <div className="space-y-3">
                {breakfastPrice > 0 && (
                    <div className="flex justify-between items-center text-sm">
                        <Text className="">Phụ thu bữa sáng:</Text>
                        <Text strong className="text-amber-600">{formatVND(breakfastPrice)}</Text>
                    </div>
                )}            <div className=" p-3   rounded-xl border-2 border-blue-200 shadow-sm">
                    <div className="flex justify-between items-center">
                        <Text strong className="text-sm ">Tổng thanh toán:</Text>
                        <Text strong className="text-lg  ">{formatVND(finalTotal)}</Text>
                    </div>
                    {nights > 1 && (
                        <Text className="text-sm  mt-1">
                            Trung bình {formatVND(finalTotal / nights)}/đêm
                        </Text>
                    )}
                </div>
            </div>

            <Button
                type="primary"
                size="large"
                className="w-full mt-6 h-12    border-none shadow-lg  rounded-xl font-bold text-white transition-all duration-300 transform "
                icon={<GiftOutlined />}
            >
                TIẾP TỤC ĐẶT PHÒNG
            </Button>
        </Card>
    );
};

export default BookingSummary;
