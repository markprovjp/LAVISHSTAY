import React from 'react';
import { useNavigate } from 'react-router-dom';
import { useSelector } from 'react-redux';
import {
    Card,
    Typography,
    Button,
    Space,
    Divider,
    Tag,
    Image,
    message
} from 'antd';
import {
    ArrowLeftOutlined,
    HomeOutlined,
    TeamOutlined,
    StarOutlined,
    CreditCardOutlined
} from '@ant-design/icons';
import { RootState } from '../store';
import dayjs from 'dayjs';

const { Title, Text, Paragraph } = Typography;

const BookingConfirmation: React.FC = () => {
    const navigate = useNavigate();
    const searchData = useSelector((state: RootState) => state.search);
    const bookingData = useSelector((state: RootState) => state.booking.simpleBookingData);

    // Format VND currency
    const formatVND = (price: number) => {
        return new Intl.NumberFormat("vi-VN", {
            style: "currency",
            currency: "VND",
        }).format(price);
    };

    if (!bookingData || !bookingData.selectedRoom) {
        return (
            <div className="min-h-screen bg-gray-50 py-8">
                <div className="max-w-4xl mx-auto px-4">
                    <Card className="text-center py-12">
                        <Title level={3}>Không có thông tin đặt phòng</Title>
                        <Button type="primary" onClick={() => navigate('/')}>
                            Quay lại trang chủ
                        </Button>
                    </Card>
                </div>
            </div>
        );
    }

    const { selectedRoom, searchData: bookingSearchData } = bookingData;
    const currentSearchData = bookingSearchData || searchData;
    const room = selectedRoom;
    const option = selectedRoom.package;

    const handleBookNow = () => {
        message.success('Đặt phòng thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.');
        navigate('/');
    };

    // Calculate nights
    const getNights = () => {
        if (!currentSearchData.dateRange?.[0] || !currentSearchData.dateRange?.[1]) return 1;
        const startDate = typeof currentSearchData.dateRange[0] === 'string' ? dayjs(currentSearchData.dateRange[0]) : currentSearchData.dateRange[0];
        const endDate = typeof currentSearchData.dateRange[1] === 'string' ? dayjs(currentSearchData.dateRange[1]) : currentSearchData.dateRange[1];
        return Math.abs(endDate.diff(startDate, 'day'));
    };

    const formatDate = (date: any) => {
        if (!date) return 'Chưa chọn';
        const dateObj = typeof date === 'string' ? dayjs(date) : date;
        return dateObj.format('DD/MM/YYYY');
    };

    const getRoomsNeeded = () => {
        return option.roomsNeeded || room.searchCriteria?.roomsNeeded || Math.ceil((currentSearchData.guestDetails?.adults || 1) / 2);
    };

    return (
        <div className="min-h-screen bg-gray-50">
            {/* Header */}
            <div className="bg-white shadow-sm">
                <div className="max-w-4xl mx-auto px-4 py-4">
                    <div className="flex items-center gap-4">
                        <Button
                            icon={<ArrowLeftOutlined />}
                            onClick={() => navigate(-1)}
                            type="text"
                        >
                            Quay lại
                        </Button>
                        <Title level={3} className="mb-0">
                            Xác nhận đặt phòng
                        </Title>
                    </div>
                </div>
            </div>

            {/* Main Content */}
            <div className="max-w-4xl mx-auto px-4 py-8">
                <Space direction="vertical" size="large" className="w-full">
                    {/* Room Details */}
                    <Card title="Thông tin phòng đã chọn">
                        <div className="flex gap-6">
                            <div className="flex-shrink-0">
                                <Image
                                    src={option.image || '/images/rooms/default.jpg'}
                                    alt={room.name}
                                    width={200}
                                    height={150}
                                    className="rounded-lg object-cover"
                                />
                            </div>
                            <div className="flex-1">
                                <div className="flex items-center gap-2 mb-2">
                                    <Tag color="blue">{room.roomType.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</Tag>
                                    <Tag color="gold">
                                        <StarOutlined /> 4.5/5
                                    </Tag>
                                </div>
                                <Title level={4} className="mb-2">
                                    {room.name}
                                </Title>
                                <Paragraph className="text-gray-600 mb-4">
                                    Phòng sang trọng với thiết kế hiện đại và đầy đủ tiện nghi cao cấp
                                </Paragraph>
                                <div className="flex items-center gap-4 text-sm text-gray-600">
                                    <span className="flex items-center gap-1">
                                        <HomeOutlined />
                                        35m²
                                    </span>
                                    <span className="flex items-center gap-1">
                                        <TeamOutlined />
                                        Tối đa 2 khách
                                    </span>
                                    {option.roomsNeeded && option.roomsNeeded > 1 && (
                                        <span className="flex items-center gap-1">
                                            <Tag color="blue">
                                                {option.roomsNeeded} phòng
                                            </Tag>
                                        </span>
                                    )}
                                </div>
                            </div>
                        </div>
                    </Card>

                    {/* Package Details */}
                    <Card title="Gói dịch vụ đã chọn">
                        <div className="flex justify-between items-start">
                            <div className="flex-1">
                                <Title level={5} className="mb-2">
                                    {option?.name || 'Gói Standard'}
                                    {option?.recommended && (
                                        <Tag color="gold" className="ml-2">
                                            Khuyến nghị
                                        </Tag>
                                    )}
                                    {option?.mostPopular && (
                                        <Tag color="red" className="ml-2">
                                            Phổ biến
                                        </Tag>
                                    )}
                                </Title>
                                <Text className="text-gray-600">
                                    {(option as any)?.description || 'Gói dịch vụ tiêu chuẩn với đầy đủ tiện nghi.'}
                                </Text>
                            </div>
                            <div className="text-right">
                                <Title level={4} className="text-blue-600 mb-0">
                                    {formatVND((option as any)?.totalPrice || (option?.pricePerNight?.vnd || 0) * getNights())}
                                </Title>
                                <Text className="text-gray-500">
                                    {formatVND(option?.pricePerNight?.vnd || 0)}/đêm
                                </Text>
                            </div>
                        </div>
                    </Card>

                    {/* Booking Summary */}
                    <Card title="Thông tin đặt phòng">
                        <div className="space-y-4">
                            <div className="flex justify-between">
                                <Text>Ngày nhận phòng:</Text>
                                <Text strong>{formatDate(searchData.dateRange?.[0])}</Text>
                            </div>
                            <div className="flex justify-between">
                                <Text>Ngày trả phòng:</Text>
                                <Text strong>{formatDate(searchData.dateRange?.[1])}</Text>
                            </div>
                            <div className="flex justify-between">
                                <Text>Số đêm:</Text>
                                <Text strong>{getNights()} đêm</Text>
                            </div>
                            <div className="flex justify-between">
                                <Text>Số phòng cần đặt:</Text>
                                <Text strong>{getRoomsNeeded()} phòng</Text>
                            </div>
                            <div className="flex justify-between">
                                <Text>Số khách:</Text>
                                <Text strong>
                                    {(currentSearchData.guestDetails?.adults || 0) + (currentSearchData.guestDetails?.children || 0)} khách
                                    ({currentSearchData.guestDetails?.adults || 0} người lớn
                                    {currentSearchData.guestDetails?.children ? `, ${currentSearchData.guestDetails.children} trẻ em` : ''})
                                </Text>
                            </div>
                            <Divider />
                            <div className="flex justify-between items-center">
                                <Title level={4} className="mb-0">Tổng cộng:</Title>
                                <Title level={3} className="text-red-600 mb-0">
                                    {formatVND((option as any)?.totalPrice * getRoomsNeeded() || (option?.pricePerNight?.vnd || 0) * getNights() * getRoomsNeeded())}
                                </Title>
                            </div>
                        </div>
                    </Card>

                    {/* Payment & Policies */}
                    <Card title="Chính sách thanh toán & hủy phòng">
                        <Space direction="vertical" className="w-full">
                            <div>
                                <Text strong>Thanh toán:</Text>
                                <div className="mt-2">
                                    <Text>{(option as any)?.paymentPolicy || 'Có thể thanh toán tại khách sạn hoặc online'}</Text>
                                </div>
                            </div>
                            <div>
                                <Text strong>Hủy phòng:</Text>
                                <div className="mt-2">
                                    <Text>{(option as any)?.cancellationPolicy || 'Miễn phí hủy phòng trước 24h'}</Text>
                                </div>
                            </div>
                        </Space>
                    </Card>

                    {/* Action Buttons */}
                    <Card>
                        <div className="flex gap-4 justify-center">
                            <Button
                                size="large"
                                onClick={() => navigate(-1)}
                            >
                                Quay lại chỉnh sửa
                            </Button>
                            <Button
                                type="primary"
                                size="large"
                                icon={<CreditCardOutlined />}
                                onClick={handleBookNow}
                                className="px-8"
                            >
                                Xác nhận đặt phòng
                            </Button>
                        </div>
                    </Card>
                </Space>
            </div>
        </div>
    );
};

export default BookingConfirmation;
