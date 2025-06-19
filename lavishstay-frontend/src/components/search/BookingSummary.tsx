import React, { useEffect, useMemo } from 'react';
import { Card, Typography, Divider, Button, Tag, Badge, Radio, Collapse, Space, message } from 'antd';
import {
    GiftOutlined,
    CalendarOutlined,
    CloseOutlined,
    EyeOutlined
} from '@ant-design/icons';
import { Coffee, Users, Bed, ShoppingBag } from 'lucide-react';
import { useDispatch, useSelector } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import { RootState } from '../../store';
import {
    updatePreferences,
    removeRoomSelection,
    setCurrentStep,
    recalculateTotals,
    selectBookingState,
    selectSelectedRoomsSummary,
    selectHasSelectedRooms,
    selectSelectedRoomsCount,
} from '../../store/slices/bookingSlice';
import ValidationSummary from '../booking/ValidationSummary';

const { Title, Text } = Typography;

interface BookingSummaryProps {
    formatVND: (price: number) => string;
    getNights: () => number;
}

const BookingSummary: React.FC<BookingSummaryProps> = ({
    formatVND,
    getNights
}) => {
    const dispatch = useDispatch();
    const navigate = useNavigate();

    // Redux state
    const searchData = useSelector((state: RootState) => state.search);
    const booking = useSelector(selectBookingState);
    const selectedRoomsSummary = useSelector(selectSelectedRoomsSummary);
    const hasSelectedRooms = useSelector(selectHasSelectedRooms);
    const selectedRoomsCount = useSelector(selectSelectedRoomsCount);

    // Local state for UI
    const [showRoomDetails, setShowRoomDetails] = React.useState(false);    const nights = getNights();
    const guestCount = (searchData.guestDetails?.adults || 0) + (searchData.guestDetails?.children || 0);
    const totalAdults = searchData.guestDetails?.adults || 0;
    const totalChildren = searchData.guestDetails?.children || 0;

    // Calculate total capacity from selected rooms
    const totalCapacity = useMemo(() => {
        return selectedRoomsSummary.reduce((total, summary) => {
            // Extract capacity from option name (simplified approach)
            const capacityMatch = summary.option.name.match(/(\d+)\s*khách/);
            const roomCapacity = capacityMatch ? parseInt(capacityMatch[1]) : 2; // Default to 2
            return total + (roomCapacity * summary.quantity);
        }, 0);
    }, [selectedRoomsSummary]);// Update totals when dependency data changes
    useEffect(() => {
        if (nights !== booking.totals.nights) {
            // Dispatch action to recalculate totals when nights change
            dispatch(recalculateTotals({ nights, guestCount }));
        }
    }, [nights, guestCount, dispatch, booking.totals.nights]);

    // Memoized computed values
    const breakfastPrice = useMemo(() => {
        switch (booking.preferences.breakfastOption) {
            case 'standard':
                return 260000 * guestCount * nights;
            case 'premium':
                return 500000 * guestCount * nights;
            default:
                return 0;
        }
    }, [booking.preferences.breakfastOption, guestCount, nights]);

    const finalTotal = useMemo(() => {
        return booking.totals.roomsTotal + breakfastPrice;
    }, [booking.totals.roomsTotal, breakfastPrice]);

    // Handle preference changes
    const handleBreakfastChange = (e: any) => {
        dispatch(updatePreferences({
            preferences: { breakfastOption: e.target.value },
            nights,
            guestCount
        }));
    };

    const handleBedPreferenceChange = (e: any) => {
        dispatch(updatePreferences({
            preferences: { bedPreference: e.target.value },
            nights,
            guestCount
        }));
    };

    // Handle remove room
    const handleRemoveRoom = (roomId: string, optionId: string) => {
        dispatch(removeRoomSelection({ roomId, optionId, nights, guestCount }));
    };

    // Handle proceed to payment
    const handleProceedToPayment = () => {
        if (!hasSelectedRooms) {
            message.error('Vui lòng chọn ít nhất một phòng');
            return;
        }

        try {
            // Set booking step to payment
            dispatch(setCurrentStep('payment'));

            // Navigate to payment page with booking data in Redux store
            navigate('/payment', {
                state: {
                    fromBooking: true,
                    bookingData: {
                        id: `BK${Date.now()}`,
                        hotelName: "LavishStay Thanh Hóa",
                        selectedRooms: selectedRoomsSummary,
                        checkIn: searchData.checkIn,
                        checkOut: searchData.checkOut,
                        guests: guestCount,
                        nights: nights,
                        roomsTotal: booking.totals.roomsTotal,
                        breakfastTotal: breakfastPrice,
                        total: finalTotal,
                        preferences: booking.preferences,
                        searchData: searchData
                    }
                }
            });
        } catch (error) {
            message.error('Có lỗi xảy ra khi chuyển đến trang thanh toán');
            console.error('Payment navigation error:', error);
        }
    }; if (!hasSelectedRooms) {
        return (
            <div className="text-center py-12">
                <ShoppingBag size={48} className="text-gray-300 mx-auto mb-4" />
                <Title level={4} className="text-gray-500 mb-2">Chưa có phòng nào được chọn</Title>
                <Text type="secondary">Hãy chọn phòng yêu thích của bạn</Text>
            </div>
        );
    }    return (
        <div className="space-y-4">
            {/* Validation Summary */}
            <ValidationSummary
                totalGuests={guestCount}
                totalAdults={totalAdults}
                totalChildren={totalChildren}
                totalCapacity={totalCapacity}
                selectedRoomsCount={selectedRoomsCount}
            />

            <Divider className="my-2" />

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
                            {guestCount} khách
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
                            count={selectedRoomsCount}
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
                        label: `Chi tiết ${selectedRoomsCount} phòng đã chọn`,
                        children: (
                            <div className="space-y-3 max-h-60 overflow-y-auto">
                                {selectedRoomsSummary.map((item) => (
                                    <div key={`${item.roomId}-${item.optionId}`} className="group bg-white border border-gray-200 p-3 rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
                                        <div className="flex justify-between items-start mb-2">
                                            <div className="flex-1 min-w-0">
                                                <div className="flex items-center gap-2 mb-1">
                                                    <Bed size={14} className="text-indigo-600 flex-shrink-0" />
                                                    <Text strong className="font-semibold truncate text-sm">
                                                        {item.room.name}
                                                    </Text>
                                                </div>
                                                <Text className="text-xs text-gray-600 ml-5 truncate">
                                                    {item.option.name}
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
                                                {formatVND(item.totalPrice)}
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
                        Tổng phòng: {formatVND(booking.totals.roomsTotal)}
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
                        value={booking.preferences.breakfastOption}
                        onChange={handleBreakfastChange}
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
                                    Tổng bữa sáng ({guestCount} người × {nights} đêm)
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
                        value={booking.preferences.bedPreference}
                        onChange={handleBedPreferenceChange}
                        className="w-full"
                    >
                        <div className="flex gap-4">
                            <Radio value="double">Giường đôi</Radio>
                            <Radio value="single">Giường đơn</Radio>
                        </div>
                    </Radio.Group>
                </Card>
            </Space>

            {/* Total Section */}
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
            </Card>            {/* Action Buttons */}
            <Space direction="vertical" size="middle" className="w-full">
                <Button
                    type="primary"
                    size="large"
                    className="w-full h-12 border-none shadow-lg rounded-xl font-bold text-white"
                    icon={<GiftOutlined />}
                    onClick={handleProceedToPayment}
                    disabled={!hasSelectedRooms || totalCapacity < guestCount}
                >
                    {totalCapacity < guestCount 
                        ? `THIẾU ${guestCount - totalCapacity} CHỖ - CHỌN THÊM PHÒNG`
                        : 'TIẾP TỤC ĐẶT PHÒNG'
                    }
                </Button>
                {totalCapacity < guestCount && (
                    <div className="text-center text-xs text-red-500">
                        Vui lòng chọn thêm phòng hoặc loại phòng lớn hơn
                    </div>
                )}
            </Space></div>
    );
};

export default BookingSummary;