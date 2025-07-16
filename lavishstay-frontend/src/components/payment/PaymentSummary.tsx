import React from 'react';
import { Card, Typography, Descriptions, Image, Row, Col, Divider, Space, Tag, List, Badge, Statistic } from 'antd';
import { CalendarOutlined, TeamOutlined, HomeOutlined, CopyOutlined, ClockCircleOutlined } from '@ant-design/icons';
import dayjs from 'dayjs';

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
    rooms?: { adults: number; children: number }[];
    guestDetails?: { // This might be legacy, we'll prefer `rooms`
        adults: number;
        children: number;
    };
}

interface PaymentSummaryProps {
    bookingCode: string;
    selectedRoomsSummary: RoomSummary[];
    searchData: SearchData;
    nights: number;
    totals: {
        roomsTotal: number;
        breakfastTotal: number;
        serviceFee: number;
        taxAmount: number;
        discountAmount: number;
        finalTotal: number;
        nights: number;
    };
    formatVND: (amount: number) => string;
}

const PaymentSummary: React.FC<PaymentSummaryProps> = ({
    bookingCode,
    selectedRoomsSummary,
    searchData,
    nights,
    totals,
    formatVND
}) => {
    // Helper function to format dates safely
    const formatDate = (date: any): string => {
        if (!date) return 'Chưa xác định';

        // If it's already a string, try to parse and format it
        if (typeof date === 'string') {
            try {
                const parsed = dayjs(date);
                return parsed.isValid() ? parsed.format('DD/MM/YYYY') : date;
            } catch {
                return date;
            }
        }

        // If it's a dayjs object or Date, format it
        try {
            if (dayjs.isDayjs(date)) {
                return date.format('DD/MM/YYYY');
            }
            if (date instanceof Date) {
                return dayjs(date).format('DD/MM/YYYY');
            }
            // Try to parse as dayjs
            return dayjs(date).format('DD/MM/YYYY');
        } catch (error) {
            console.warn('Error formatting date:', date, error);
            return 'Chưa xác định';
        }
    };

    // Consolidate guest calculation logic
    const totalGuests = React.useMemo(() => {
        if (searchData?.rooms && searchData.rooms.length > 0) {
            return searchData.rooms.reduce((acc, room) => acc + room.adults + room.children, 0);
        }
        if (searchData?.guestDetails) {
            return (searchData.guestDetails.adults || 0) + (searchData.guestDetails.children || 0);
        }
        // Fallback for safety, though it should not be reached in normal flow
        return selectedRoomsSummary.reduce((acc, room) => acc + room.quantity * 2, 0) || 2;
    }, [searchData, selectedRoomsSummary]);

    // Safe render guard
    if (!selectedRoomsSummary || selectedRoomsSummary.length === 0) {
        return (
            <Card
                title={<Title level={4}>Chi tiết đặt phòng</Title>}
                className="sticky top-4 shadow-lg"
                bordered={false}
            >
                <div className="text-center py-8">
                    <Text type="secondary">Không có thông tin đặt phòng</Text>
                </div>
            </Card>
        );
    }

    return (
        <Card
            title={<Title level={4} style={{ margin: 0 }}>Chi tiết đặt phòng</Title>}
            className="sticky top-4 shadow-lg"
            bordered={false}
            style={{ borderRadius: '12px' }}
        >
            <Space direction="vertical" className="w-full" size="large">
                {/* Hotel Info */}
                <Card size="small" style={{ backgroundColor: '#f8f9fa', border: 'none' }}>
                    <div style={{ textAlign: 'center' }}>
                        <Title level={5} style={{ margin: 0, color: '#1890ff' }}>
                            <HomeOutlined /> LavishStay Thanh Hóa
                        </Title>
                        <Text type="secondary">Thanh Hóa, Việt Nam</Text>
                    </div>
                </Card>

                {/* Booking Code */}
                {bookingCode && (
                    <Card size="small" style={{ backgroundColor: '#f6ffed', border: '1px solid #b7eb8f' }}>
                        <div style={{ textAlign: 'center' }}>
                            <Text type="secondary" style={{ fontSize: '12px' }}>Mã đặt phòng</Text>
                            <br />
                            <Text
                                strong
                                copyable={{
                                    icon: <CopyOutlined />,
                                    tooltips: ['Sao chép mã', 'Đã sao chép!']
                                }}
                                style={{
                                    fontSize: '16px',
                                    color: '#52c41a',
                                    fontFamily: 'monospace'
                                }}
                            >
                                {bookingCode}
                            </Text>
                        </div>
                    </Card>
                )}

                {/* Booking Details */}
                <Descriptions
                    column={1}
                    size="small"
                    bordered
                    style={{ backgroundColor: '#fff' }}
                >
                    <Descriptions.Item
                        label={<><CalendarOutlined /> Thời gian</>}
                        labelStyle={{ fontWeight: 500 }}
                    >
                        <div>
                            <div><strong>Nhận:</strong> {formatDate(searchData?.checkIn)}</div>
                            <div><strong>Trả:</strong> {formatDate(searchData?.checkOut)}</div>
                            <Badge count={`${nights} đêm`} style={{ backgroundColor: '#108ee9' }} />
                        </div>
                    </Descriptions.Item>
                    <Descriptions.Item
                        label={<><TeamOutlined /> Khách</>}
                        labelStyle={{ fontWeight: 500 }}
                    >
                        <Badge count={totalGuests} style={{ backgroundColor: '#52c41a' }} showZero />
                        <span style={{ marginLeft: 8 }}>khách</span>
                    </Descriptions.Item>
                </Descriptions>

                {/* Selected Rooms */}
                <div>
                    <Title level={5} style={{ marginBottom: 16 }}>
                        Phòng đã chọn ({selectedRoomsSummary.length} phòng)
                    </Title>
                    <List
                        dataSource={selectedRoomsSummary}
                        renderItem={(summary, index) => (
                            <List.Item style={{ padding: 0, marginBottom: 12 }}>
                                <Card
                                    size="small"
                                    hoverable
                                    style={{
                                        width: '100%',
                                        borderRadius: '8px',
                                        border: '1px solid #e8e8e8'
                                    }}
                                    bodyStyle={{ padding: '12px' }}
                                >
                                    <Row gutter={12} align="middle">
                                        <Col span={8}>
                                            <Image
                                                src={summary.room?.image || ''}
                                                alt={summary.room?.name || 'Phòng'}
                                                style={{
                                                    width: '100%',
                                                    height: '60px',
                                                    objectFit: 'cover',
                                                    borderRadius: '6px'
                                                }}
                                                fallback="https://dam.melia.com/melia/file/iXGwjwBVnTHehdUyTT57.jpg?im=RegionOfInterestCrop=(1920,1281),regionOfInterest=(1771.5,1181.5)"
                                            />
                                        </Col>
                                        <Col span={16}>
                                            <div style={{ marginBottom: '4px' }}>
                                                <Text strong style={{ fontSize: '14px' }}>
                                                    {summary.room?.name || 'Phòng không xác định'}
                                                </Text>
                                            </div>
                                            <div style={{ marginBottom: '4px' }}>
                                                <Tag color="blue" style={{ fontSize: '11px' }}>
                                                    {summary.option?.name || 'Tùy chọn không xác định'}
                                                </Tag>
                                            </div>
                                            <div style={{ marginBottom: '4px' }}>
                                                <Text type="secondary" style={{ fontSize: '12px' }}>
                                                    {summary.quantity || 0} phòng × {nights} đêm
                                                </Text>
                                            </div>
                                            <div>
                                                <Text strong style={{ color: '#f5222d', fontSize: '14px' }}>
                                                    {formatVND(summary.totalPrice || 0)}
                                                </Text>
                                            </div>
                                        </Col>
                                    </Row>
                                </Card>
                            </List.Item>
                        )}
                    />
                </div>

                <Divider />

                {/* Payment Summary */}
                <Card
                    size="small"
                    style={{
                        backgroundColor: '#fff2e8',
                        border: '1px solid #ffbb96',
                        borderRadius: '8px'
                    }}
                >
                    <Space direction="vertical" className="w-full" size="small">
                        {totals.roomsTotal > 0 && (
                            <div className="flex justify-between">
                                <Text>Tiền phòng:</Text>
                                <Text>{formatVND(totals.roomsTotal)}</Text>
                            </div>
                        )}
                        {totals.breakfastTotal > 0 && (
                            <div className="flex justify-between">
                                <Text>Tiền ăn sáng:</Text>
                                <Text>{formatVND(totals.breakfastTotal)}</Text>
                            </div>
                        )}
                        {totals.serviceFee > 0 && (
                            <div className="flex justify-between">
                                <Text>Phí dịch vụ:</Text>
                                <Text>{formatVND(totals.serviceFee)}</Text>
                            </div>
                        )}
                        {totals.taxAmount > 0 && (
                            <div className="flex justify-between">
                                <Text>Thuế VAT:</Text>
                                <Text>{formatVND(totals.taxAmount)}</Text>
                            </div>
                        )}
                        {totals.discountAmount > 0 && (
                            <div className="flex justify-between">
                                <Text>Giảm giá:</Text>
                                <Text style={{ color: '#52c41a' }}>-{formatVND(totals.discountAmount)}</Text>
                            </div>
                        )}
                        <Divider style={{ margin: '8px 0' }} />
                        <div className="flex justify-between">
                            <Text strong style={{ fontSize: '16px' }}>Tổng thanh toán:</Text>
                            <Text strong style={{ color: '#f5222d', fontSize: '18px' }}>
                                {formatVND(totals?.finalTotal || 0)}
                            </Text>
                        </div>
                    </Space>
                </Card>
            </Space>
        </Card>
    );
};

export default PaymentSummary;
