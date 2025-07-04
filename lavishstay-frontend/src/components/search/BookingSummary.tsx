import React, { useState } from 'react';
import { Card, Typography, Divider, Button, Tag, Badge, Radio, Collapse, Space } from 'antd';
import {
    GiftOutlined,
    CalendarOutlined,
    CheckCircleOutlined,
    CloseOutlined,
    EyeOutlined
} from '@ant-design/icons';
import { Coffee, Users, Bed, ShoppingBag } from 'lucide-react';

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
    const [showRoomDetails, setShowRoomDetails] = useState(false);
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
                    if (option) {                        // Use correct price: dynamicPricing.finalPrice or fallback to pricePerNight.vnd
                        const pricePerNight = option.dynamicPricing?.finalPrice || option.pricePerNight?.vnd || option.price || 0;
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
    const finalTotal = totalPrice + breakfastPrice; if (summary.length === 0) {
        return (
            <div className="text-center py-12">
                <ShoppingBag size={48} className="text-gray-300 mx-auto mb-4" />
                <Title level={4} className="text-gray-500 mb-2">Chưa có phòng nào được chọn</Title>
                <Text type="secondary">Hãy chọn phòng yêu thích của bạn</Text>
            </div>
        );
    } return (
        <div className="space-y-4">
            {/* Booking Info Header */}
            <Card
                size="small"
                className="bg-gradient-to-r from-blue-50 to-indigo-50 border-blue-200"
            >
                <div className="flex items-center justify-between text-sm">
                    <div className="flex items-center gap-2">
                        <CalendarOutlined className="text-blue-600" />
                        <Text className="font-medium">{nights} đêm</Text>
                    </div>
                    <div className="flex items-center gap-2">
                        <Users size={14} className="text-blue-600" />
                        <Text className="font-medium">
                            {(searchData.guestDetails?.adults || 0) + (searchData.guestDetails?.children || 0)} khách
                        </Text>
                    </div>
                </div>
            </Card>

            {/* Selected Rooms */}
            <div className="space-y-3">
                <div className="flex items-center justify-between">
                    <div className="flex items-center gap-2">
                        <Text strong className="text-lg">Phòng đã chọn</Text>
                        <Badge
                            count={summary.length}
                            style={{
                                backgroundColor: '#1890ff',
                                fontSize: '11px',
                                height: '18px',
                                minWidth: '18px',
                            }}
                        />
                    </div>
                    <Button
                        type="link"
                        size="small"
                        icon={<EyeOutlined />}
                        onClick={() => setShowRoomDetails(!showRoomDetails)}
                    >
                        {showRoomDetails ? 'Ẩn chi tiết' : 'Xem chi tiết'}
                    </Button>
                </div>

                <Collapse
                    activeKey={showRoomDetails ? ['1'] : []}
                    
                    ghost
                    size="small"
                    items={[{
                        key: '1',
                        label: `Chi tiết ${summary.length} phòng đã chọn`,
                        children: (
                            <div className="space-y-3 max-h-60 overflow-y-auto">
                                {summary.map((item, index) => (
                                    <div key={index} className="group bg-white border border-gray-200 p-3 rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
                                        <div className="flex justify-between items-start mb-2">
                                            <div className="flex-1 min-w-0">
                                                <div className="flex items-center gap-2 mb-1">
                                                    <Bed size={14} className="text-indigo-600 flex-shrink-0" />
                                                    <Text strong className="font-semibold truncate text-sm">
                                                        {item.roomName}
                                                    </Text>
                                                </div>
                                                <Text className="text-xs text-gray-600 ml-5 truncate">
                                                    {item.optionName}
                                                </Text>
                                            </div>
                                            <div className="flex items-center gap-2 ml-3">
                                                <Tag color="blue" className="text-xs">
                                                    ×{item.quantity}
                                                </Tag>
                                                <Button
                                                    type="text"
                                                    size="small"
                                                    icon={<CloseOutlined />}
                                                    onClick={() => handleRemoveRoom(item.roomId, item.optionId)}
                                                    className="opacity-0 group-hover:opacity-100 text-red-500 hover:text-red-600"
                                                />
                                            </div>
                                        </div>
                                        <div className="flex justify-between items-center pt-2 border-t border-gray-100">
                                            <Text className="text-xs text-gray-500">
                                                {formatVND(item.pricePerNight)}/đêm
                                            </Text>
                                            <Text strong className="text-sm font-bold text-green-600">
                                                {formatVND(item.total)}
                                            </Text>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )
                    }]}
                />
            </div>

            {/* Room Summary */}
            <Card size="small" className="bg-gray-50">
                <div className="text-center">
                    <Text strong className="text-lg text-gray-800">
                        Tổng phòng: {formatVND(totalPrice)}
                    </Text>
                </div>
            </Card>

            {/* Breakfast & Bed Preferences */}
            <Space direction="vertical" size="middle" className="w-full">
                {/* Breakfast Options */}
                <Card size="small" title={
                    <div className="flex items-center gap-2">
                        <Coffee size={16} className="text-amber-600" />
                        <span>Bữa sáng</span>
                    </div>
                }>
                    <Radio.Group
                        value={breakfastOption}
                        onChange={(e) => setBreakfastOption(e.target.value)}
                        className="w-full"
                    >
                        <div className="grid grid-cols-1 gap-3">
                            <div className="flex items-center justify-between p-2 border rounded hover:bg-gray-50">
                                <Radio value="none">Không bữa sáng</Radio>
                                <Badge count="Miễn phí" style={{ backgroundColor: '#52c41a', fontSize: '10px' }} />
                            </div>
                            <div className="flex items-center justify-between p-2 border rounded hover:bg-gray-50">
                                <Radio value="standard">Bữa sáng cơ bản</Radio>
                                <Badge count="260K" style={{ backgroundColor: '#1890ff', fontSize: '10px' }} />
                            </div>
                            <div className="flex items-center justify-between p-2 border rounded hover:bg-gray-50">
                                <Radio value="premium">Bữa sáng và tối</Radio>
                                <Badge count="500K" style={{ backgroundColor: '#fa541c', fontSize: '10px' }} />
                            </div>
                        </div>
                    </Radio.Group>
                    {breakfastPrice > 0 && (
                        <div className="mt-3 p-3 bg-amber-50 rounded-lg border border-amber-200">
                            <div className="flex justify-between items-center">
                                <Text className="text-sm text-amber-700">
                                    Tổng bữa sáng ({(searchData.guestDetails?.adults || 0) + (searchData.guestDetails?.children || 0)} người × {nights} đêm)
                                </Text>
                                <Text strong className="text-amber-700">{formatVND(breakfastPrice)}</Text>
                            </div>
                        </div>
                    )}
                </Card>

                {/* Bed Preference */}
                <Card size="small" title={
                    <div className="flex items-center gap-2">
                        <Bed size={16} className="text-indigo-600" />
                        <span>Ưu tiên giường</span>
                    </div>
                }>
                    <Radio.Group
                        value={bedPreference}
                        onChange={(e) => setBedPreference(e.target.value)}
                        className="w-full"
                    >
                        <div className="flex gap-4">
                            <Radio value="double">Giường đôi</Radio>
                            <Radio value="single">Giường đơn</Radio>
                        </div>
                    </Radio.Group>
                </Card>
            </Space>            {/* Total Section */}
            <Card className="bg-gradient-to-r from-green-50 to-emerald-50 border-green-200">
                <div className="space-y-3">
                    {breakfastPrice > 0 && (
                        <>
                            <div className="flex justify-between items-center text-sm">
                                <Text>Phụ thu bữa sáng:</Text>
                                <Text strong className="text-amber-600">{formatVND(breakfastPrice)}</Text>
                            </div>
                            <Divider className="my-2" />
                        </>
                    )}
                    <div className="flex justify-between items-center">
                        <Text strong className="text-lg">Tổng thanh toán:</Text>
                        <Text strong className="text-xl text-green-600">{formatVND(finalTotal)}</Text>
                    </div>
                    {nights > 1 && (
                        <Text className="text-sm text-gray-600 text-center">
                            Trung bình {formatVND(finalTotal / nights)}/đêm
                        </Text>
                    )}
                </div>
            </Card>

            {/* Action Buttons */}
            <Space direction="vertical" size="middle" className="w-full">
                <Button
                    type="primary"
                    size="large"
                    className="w-full h-12  border-none shadow-lg rounded-xl font-bold text-white"
                    icon={<GiftOutlined />}
                >
                    TIẾP TỤC ĐẶT PHÒNG
                </Button>

            </Space>
        </div>
    );
};

export default BookingSummary;