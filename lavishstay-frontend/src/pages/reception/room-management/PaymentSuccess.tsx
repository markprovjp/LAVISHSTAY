import React from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import { Button, Card, Col, Descriptions, Row, Typography, App, Layout, Space, Result } from 'antd';
import { CheckCircleOutlined, HomeOutlined, CalendarOutlined, UserOutlined } from '@ant-design/icons';
import { formatCurrency } from '../../../utils/helpers';
import dayjs from 'dayjs';

const { Title, Text } = Typography;
const { Content } = Layout;

const PaymentSuccess: React.FC = () => {
    const location = useLocation();
    const navigate = useNavigate();
    const { message } = App.useApp();

    const { bookingCode, bookingDetails } = location.state || {};

    if (!bookingCode || !bookingDetails) {
        return (
            <Content className="p-6 bg-gray-50 min-h-screen flex items-center justify-center">
                <Result
                    status="warning"
                    title="Không có thông tin đặt phòng."
                    subTitle="Không thể hiển thị chi tiết vì thiếu dữ liệu. Vui lòng thử lại từ đầu."
                    extra={[
                        <Button type="primary" key="console" onClick={() => navigate('/reception/room-management-list')}>
                            Về trang quản lý
                        </Button>,
                    ]}
                />
            </Content>
        );
    }

    const handleNewBooking = () => {
        navigate('/reception/room-management-list');
    };

    const handlePrint = () => {
        message.info('Chức năng in đang được phát triển!');
        window.print();
    };

    return (
        <Content className="p-10 bg-gray-50 min-h-screen flex items-center justify-center">
            <Card className="w-full max-w-4xl shadow-lg" bordered={false}>
                <Result
                    status="success"
                    icon={<CheckCircleOutlined />}
                    title={<Title level={2}>Đặt phòng thành công!</Title>}
                    subTitle={`Mã đặt phòng của bạn là: ${bookingCode}`}
                    extra={[
                        <Button type="primary" key="new" onClick={handleNewBooking}>
                            Tạo đặt phòng mới
                        </Button>,
                        <Button key="print" onClick={handlePrint}>
                            In xác nhận
                        </Button>,
                    ]}
                />
                <div className="mt-6">
                    <Title level={4} className="mb-4 text-center">Tóm tắt thông tin đặt phòng</Title>
                    <Descriptions bordered column={{ xs: 1, sm: 1, md: 2 }}>
                        <Descriptions.Item label={<><UserOutlined /> Tên khách hàng</>}>
                            <Text strong>{bookingDetails.representative_info?.details?.fullName || 'Không có'}</Text>
                        </Descriptions.Item>
                        <Descriptions.Item label="Email">
                            {bookingDetails.representative_info?.details?.email || 'Không có'}
                        </Descriptions.Item>
                        <Descriptions.Item label="Số điện thoại">
                            {bookingDetails.representative_info?.details?.phoneNumber || 'Không có'}
                        </Descriptions.Item>
                        <Descriptions.Item label={<><CalendarOutlined /> Ngày nhận phòng</>}>
                            {dayjs(bookingDetails.check_in_date).format('DD/MM/YYYY')}
                        </Descriptions.Item>
                        <Descriptions.Item label={<><CalendarOutlined /> Ngày trả phòng</>}>
                            {dayjs(bookingDetails.check_out_date).format('DD/MM/YYYY')}
                        </Descriptions.Item>
                        <Descriptions.Item label={<><HomeOutlined /> Số đêm</>}>
                            {bookingDetails.nights}
                        </Descriptions.Item>
                        <Descriptions.Item label="Số khách">
                            {bookingDetails.adults} người lớn, {bookingDetails.children.length} trẻ em
                        </Descriptions.Item>
                        <Descriptions.Item label="Tổng tiền" span={2}>
                            <Text strong className="text-blue-600 text-lg">
                                {formatCurrency(bookingDetails.total_price)}
                            </Text>
                        </Descriptions.Item>
                    </Descriptions>
                </div>
            </Card>
        </Content>
    );
};

export default PaymentSuccess;
