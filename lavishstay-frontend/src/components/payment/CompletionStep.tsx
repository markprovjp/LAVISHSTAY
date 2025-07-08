import React from 'react';
import { Card, Typography, Button, Result, Descriptions, List, Row, Col, Divider } from 'antd';
import { CheckCircleOutlined, HomeOutlined, RedoOutlined } from '@ant-design/icons';

const { Title, Text, Paragraph } = Typography;

// Helper to format currency
const formatVND = (amount: number) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);

interface CompletionStepProps {
    bookingCode: string;
    selectedPaymentMethod: string;
    onViewBookings: () => void;
    onNewBooking: () => void;
    onCompleteBooking?: () => void;
    customerInfo?: any;
    selectedRoomsSummary?: any[];
    searchData?: any;
    totals?: any;
    nights?: number;
}

const CompletionStep: React.FC<CompletionStepProps> = ({
    bookingCode,
    selectedPaymentMethod,
    onViewBookings,
    onNewBooking,
    customerInfo,
    selectedRoomsSummary,
    searchData,
    totals,
    nights = 1
}) => {
    const getSuccessMessage = () => {
        if (selectedPaymentMethod === 'vietqr') {
            return 'Thanh toán thành công!';
        }
        return 'Đặt phòng thành công!';
    };

    const getDescription = () => {
        if (selectedPaymentMethod === 'vietqr') {
            return `Cảm ơn bạn đã tin tưởng LavishStay. Mã đặt phòng của bạn là: ${bookingCode}`;
        }
        return `Cảm ơn bạn đã tin tưởng LavishStay. Mã đặt phòng của bạn là: ${bookingCode}`;
    };

    // If we don't have complete data, show simple success
    if (!customerInfo || !selectedRoomsSummary || !totals) {
        return (
            <div className="text-center">
                <Card className="max-w-2xl mx-auto">
                    <Result
                        status="success"
                        title={getSuccessMessage()}
                        subTitle={getDescription()}
                        icon={<CheckCircleOutlined style={{ color: '#52c41a' }} />}
                        extra={[
                            <Button key="home" type="primary" icon={<HomeOutlined />} onClick={onNewBooking}>
                                Về trang chủ
                            </Button>,
                            <Button key="new" icon={<RedoOutlined />} onClick={onViewBookings}>
                                Xem đặt phòng
                            </Button>,
                        ]}
                    />
                </Card>
            </div>
        );
    }

    return (
        <div className="bg-gray-100 min-h-screen p-4 sm:p-8">
            <div className="max-w-4xl mx-auto">
                <Result
                    icon={<CheckCircleOutlined className="text-green-500" />}
                    status="success"
                    title={getSuccessMessage()}
                    subTitle={getDescription()}
                    extra={[
                        <Button type="primary" key="home" icon={<HomeOutlined />} onClick={onNewBooking}>
                            Về trang chủ
                        </Button>,
                        <Button key="new" icon={<RedoOutlined />} onClick={onViewBookings}>
                            Xem đặt phòng của tôi
                        </Button>,
                    ]}
                />

                <Card bordered={false} className="shadow-lg rounded-lg mt-8">
                    <Title level={3} className="text-center mb-6">Chi tiết đơn đặt phòng</Title>

                    {/* Customer and Booking Info */}
                    <Row gutter={[16, 16]} justify="space-between">
                        <Col xs={24} md={12}>
                            <Descriptions title="Thông tin khách hàng" bordered column={1} size="small">
                                <Descriptions.Item label="Họ và tên">{customerInfo.fullName}</Descriptions.Item>
                                <Descriptions.Item label="Email">{customerInfo.email}</Descriptions.Item>
                                <Descriptions.Item label="Số điện thoại">{customerInfo.phone}</Descriptions.Item>
                            </Descriptions>
                        </Col>
                        <Col xs={24} md={12}>
                            <Descriptions title="Thông tin chung" bordered column={1} size="small">
                                <Descriptions.Item label="Mã đặt phòng"><Text strong copyable>{bookingCode}</Text></Descriptions.Item>
                                <Descriptions.Item label="Ngày nhận phòng">{new Date(searchData.checkIn).toLocaleDateString('vi-VN')}</Descriptions.Item>
                                <Descriptions.Item label="Ngày trả phòng">{new Date(searchData.checkOut).toLocaleDateString('vi-VN')}</Descriptions.Item>
                                <Descriptions.Item label="Phương thức thanh toán">
                                    {selectedPaymentMethod === 'pay_at_hotel' ? 'Thanh toán tại khách sạn' : 'Đã thanh toán'}
                                </Descriptions.Item>
                            </Descriptions>
                        </Col>
                    </Row>

                    <Divider />

                    {/* Room Details */}
                    <Title level={4} className="mt-6 mb-4">Chi tiết các phòng đã đặt</Title>
                    <List
                        grid={{ gutter: 16, xs: 1, sm: 1, md: 1, lg: 1, xl: 1, xxl: 1 }}
                        dataSource={selectedRoomsSummary}
                        renderItem={(roomSummary: any, index: number) => (
                            <List.Item>
                                <Card type="inner" title={`Phòng ${index + 1}: ${roomSummary.room.name}`}>
                                    <Row gutter={16}>
                                        <Col span={12}>
                                            <img src={roomSummary.room.image_url} alt={roomSummary.room.name} className="w-full h-auto rounded-md" />
                                        </Col>
                                        <Col span={12}>
                                            <Descriptions column={1} size="small">
                                                <Descriptions.Item label="Khách đứng tên">{customerInfo.fullName}</Descriptions.Item>
                                                <Descriptions.Item label="Gói dịch vụ">{roomSummary.option.name}</Descriptions.Item>
                                                <Descriptions.Item label="Số đêm">{nights}</Descriptions.Item>
                                                <Descriptions.Item label="Giá mỗi đêm">{formatVND(roomSummary.pricePerNight)}</Descriptions.Item>
                                            </Descriptions>
                                        </Col>
                                    </Row>
                                </Card>
                            </List.Item>
                        )}
                    />

                    <Divider />

                    {/* Payment Summary */}
                    <Title level={4} className="mt-6 mb-4">Tổng kết chi phí</Title>
                    <Row justify="end">
                        <Col xs={24} sm={16} md={12}>
                            <Descriptions bordered column={1} size="small">
                                <Descriptions.Item label={`Tiền phòng (${nights} đêm)`}>{formatVND(totals.roomsTotal)}</Descriptions.Item>
                                <Descriptions.Item label="Phí dịch vụ">{formatVND(totals.serviceFee)}</Descriptions.Item>
                                <Descriptions.Item label="Thuế VAT">{formatVND(totals.taxAmount)}</Descriptions.Item>
                                <Descriptions.Item label="Tổng cộng">
                                    <Title level={4} style={{ margin: 0 }}>{formatVND(totals.finalTotal)}</Title>
                                </Descriptions.Item>
                            </Descriptions>
                        </Col>
                    </Row>

                    <Paragraph className="text-center mt-8 text-gray-500">
                        Một email xác nhận đã được gửi đến {customerInfo.email}. Vui lòng kiểm tra hộp thư của bạn.
                        Nếu có bất kỳ câu hỏi nào, xin vui lòng liên hệ với chúng tôi.
                    </Paragraph>
                </Card>
            </div>
        </div>
    );
};

export default CompletionStep;
