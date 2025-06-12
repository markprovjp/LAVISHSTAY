import React from 'react';
import { Card, Typography, Descriptions, Image, Row, Col, Divider, Space } from 'antd';
import { CalendarOutlined, TeamOutlined } from '@ant-design/icons';
import { Coffee, Bed } from 'lucide-react';

const { Text, Title } = Typography;

interface RoomSummary {
    roomId: string;
    room: {
        id: string | number;
        name: string;
        image: string;
    };
    option: {
        id: string;
        name: string;
        additionalServices?: any[];
    };
    quantity: number;
    totalPrice: number;
}

interface SearchData {
    checkIn?: string;
    checkOut?: string;
    guestDetails?: {
        adults: number;
        children: number;
    };
}

interface BookingPreferences {
    breakfastOption?: 'none' | 'standard' | 'premium';
    bedPreference?: 'single' | 'double' | 'twin';
}

interface PaymentSummaryProps {
    bookingCode: string;
    selectedRoomsSummary: RoomSummary[];
    searchData: SearchData;
    nights: number;
    totals: {
        roomsTotal: number;
        breakfastTotal?: number;
        finalTotal?: number;
        total: number;
    };
    preferences?: BookingPreferences;
    formatVND: (amount: number) => string;
}

const PaymentSummary: React.FC<PaymentSummaryProps> = ({
    bookingCode,
    selectedRoomsSummary,
    searchData,
    nights,
    totals,
    preferences,
    formatVND
}) => {
    const totalGuests = (searchData.guestDetails?.adults || 0) + (searchData.guestDetails?.children || 0) || 2;

    const getBreakfastText = () => {
        switch (preferences?.breakfastOption) {
            case 'standard':
                return 'Bữa sáng tiêu chuẩn';
            case 'premium':
                return 'Bữa sáng cao cấp';
            default:
                return 'Không bao gồm bữa sáng';
        }
    };

    const getBedText = () => {
        switch (preferences?.bedPreference) {
            case 'single':
                return 'Giường đơn';
            case 'double':
                return 'Giường đôi';
            case 'twin':
                return 'Hai giường đơn';
            default:
                return 'Theo phòng';
        }
    };

    return (
        <Card title="Chi tiết đặt phòng" className="sticky top-4">
            <Space direction="vertical" className="w-full" size="middle">
                {/* Hotel Info */}
                <div>
                    <Text strong>LavishStay Thanh Hóa</Text>
                    <br />
                    <Text type="secondary">Thanh Hóa, Việt Nam</Text>
                </div>

                {/* Booking Details */}
                <Descriptions column={1} size="small">
                    <Descriptions.Item label="Mã đặt phòng">
                        <Text strong code style={{ color: '#52c41a' }}>
                            {bookingCode}
                        </Text>
                    </Descriptions.Item>
                    <Descriptions.Item label="Check-in">
                        <CalendarOutlined /> {searchData.checkIn}
                    </Descriptions.Item>
                    <Descriptions.Item label="Check-out">
                        <CalendarOutlined /> {searchData.checkOut}
                    </Descriptions.Item>
                    <Descriptions.Item label="Số đêm">
                        {nights} đêm
                    </Descriptions.Item>
                    <Descriptions.Item label="Khách">
                        <TeamOutlined /> {totalGuests} khách
                    </Descriptions.Item>
                </Descriptions>

                <Divider />

                {/* Selected Rooms */}
                <div>
                    <Title level={5}>Phòng đã chọn</Title>
                    {selectedRoomsSummary.map((summary, index) => (
                        <Card key={index} size="small" className="mb-3">
                            <Row gutter={12}>
                                <Col span={8}>
                                    <Image
                                        src={summary.room.image}
                                        alt={summary.room.name}
                                        width="100%"
                                        height={60}
                                        style={{ objectFit: 'cover', borderRadius: 4 }}
                                        fallback="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMIAAADDCAYAAADQvc6UAAABRWlDQ1BJQ0MgUHJvZmlsZQAAKJFjYGASSSwoyGFhYGDIzSspCnJ3UoiIjFJgf8LAwSDCIMogwMCcmFxc4BgQ4ANUwgCjUcG3awyMIPqyLsis7PPOq3QdDFcvjV3jOD1boQVTPQrgSkktTgbSf4A4LbmgqISBgTEFyFYuLykAsTuAbJEioKOA7DkgdjqEvQHEToKwj4DVhAQ5A9k3gGyB5IxEoBmML4BsnSQk8XQkNtReEOBxcfXxUQg1Mjc0dyHgXNJBSWpFCYh2zi+oLMpMzyhRcASGUqqCZ16yno6CkYGRAQMDKMwhqj/fAIcloxgHQqxAjIHBEugw5sUIsSQpBobtQPdLciLEVJYzMPBHMDBsayhILEqEO4DxG0txmrERhM29nYGBddr//5/DGRjYNRkY/l7////39v///y4Dmn+LgeHANwDrkl1AuO+pmgAAADhlWElmTU0AKgAAAAgAAYdpAAQAAAABAAAAGgAAAAAAAqACAAQAAAABAAAAwqADAAQAAAABAAAAwwAAAAD9b/HnAAAHlklEQVR4Ae3dP3Ik1RnG4W+FgYxN"
                                    />
                                </Col>
                                <Col span={16}>
                                    <Text strong className="text-sm">{summary.room.name}</Text>
                                    <br />
                                    <Text type="secondary" className="text-xs">
                                        {summary.option.name}
                                    </Text>
                                    <br />
                                    <Text className="text-xs">
                                        {summary.quantity} phòng × {nights} đêm
                                    </Text>
                                    <br />
                                    <Text strong className="text-sm" style={{ color: '#f5222d' }}>
                                        {formatVND(summary.totalPrice)}
                                    </Text>
                                </Col>
                            </Row>
                        </Card>
                    ))}
                </div>

                {/* Preferences */}
                {preferences && (preferences.breakfastOption !== 'none' || preferences.bedPreference) && (
                    <>
                        <Divider />
                        <div>
                            <Title level={5}>Tùy chọn khách hàng</Title>
                            <Space direction="vertical" size="small" className="w-full">
                                {preferences.breakfastOption && preferences.breakfastOption !== 'none' && (
                                    <div className="flex items-center gap-2">
                                        <Coffee size={16} className="text-orange-500" />
                                        <Text className="text-sm">{getBreakfastText()}</Text>
                                    </div>
                                )}
                                {preferences.bedPreference && (
                                    <div className="flex items-center gap-2">
                                        <Bed size={16} className="text-blue-500" />
                                        <Text className="text-sm">{getBedText()}</Text>
                                    </div>
                                )}
                            </Space>
                        </div>
                    </>
                )}

                <Divider />

                {/* Payment Summary */}
                <div>
                    <div className="flex justify-between mb-2">
                        <Text>Tổng tiền phòng:</Text>
                        <Text>{formatVND(totals.roomsTotal)}</Text>
                    </div>
                    <div className="flex justify-between mb-2">
                        <Text>Phụ thu bữa sáng:</Text>
                    {totals.breakfastTotal && totals.breakfastTotal > 0 && (
                            <Text className="text-amber-600">{formatVND(totals.breakfastTotal)}</Text>
                        )}
                        </div>

                    <Divider />
                    <div className="flex justify-between">
                        <Text strong>Tổng thanh toán:</Text>
                        <Text strong style={{ color: '#f5222d', fontSize: '1.1em' }}>
                            {formatVND(totals.total)}
                        </Text>
                    </div>
                </div>
            </Space>
        </Card>
    );
};

export default PaymentSummary;
