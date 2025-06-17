import React, { useState, useEffect } from 'react';
import {
    Layout, Typography, Card, Table, Button, Tag, Space,
    message, Alert, Descriptions, Modal, Image
} from 'antd';
import {
    CheckCircleOutlined, ClockCircleOutlined,
    EyeOutlined, BankOutlined
} from '@ant-design/icons';

const { Content, Header } = Layout;
const { Title, Text } = Typography;

// Format VND Currency
const formatVND = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
};

interface Booking {
    id: number;
    booking_code: string;
    customer_name: string;
    customer_email: string;
    customer_phone: string;
    total_amount: number;
    payment_status: 'pending' | 'confirmed' | 'failed';
    created_at: string;
    check_in: string;
    check_out: string;
    rooms_data: string;
}

const AdminPayment: React.FC = () => {
    const [bookings, setBookings] = useState<Booking[]>([]);
    const [loading, setLoading] = useState(false);
    const [selectedBooking, setSelectedBooking] = useState<Booking | null>(null);
    const [modalVisible, setModalVisible] = useState(false);

    const API_BASE_URL = 'http://localhost:8888/api';

    // Fetch pending payments
    const fetchPendingPayments = async () => {
        setLoading(true);
        try {
            const response = await fetch(`${API_BASE_URL}/payment/admin/pending`);
            const result = await response.json();

            if (result.success) {
                setBookings(result.data);
            } else {
                message.error('Lỗi khi tải dữ liệu');
            }
        } catch (error) {
            message.error('Lỗi kết nối API');
            console.error('Error fetching pending payments:', error);
        } finally {
            setLoading(false);
        }
    };

    // Confirm payment
    const confirmPayment = async (bookingCode: string) => {
        try {
            const response = await fetch(`${API_BASE_URL}/payment/admin/confirm/${bookingCode}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            });

            const result = await response.json();

            if (result.success) {
                message.success(`Đã xác nhận thanh toán cho booking ${bookingCode}`);
                fetchPendingPayments(); // Refresh list
                setModalVisible(false);
            } else {
                message.error(result.message || 'Lỗi khi xác nhận thanh toán');
            }
        } catch (error) {
            message.error('Lỗi kết nối API');
            console.error('Error confirming payment:', error);
        }
    };

    // Auto refresh every 30 seconds
    useEffect(() => {
        fetchPendingPayments();

        const interval = setInterval(() => {
            fetchPendingPayments();
        }, 30000);

        return () => clearInterval(interval);
    }, []);

    // Table columns
    const columns = [
        {
            title: 'Mã đặt phòng',
            dataIndex: 'booking_code',
            key: 'booking_code',
            render: (code: string) => (
                <Text strong style={{ color: '#1890ff' }}>{code}</Text>
            )
        },
        {
            title: 'Khách hàng',
            dataIndex: 'customer_name',
            key: 'customer_name',
            render: (name: string, record: Booking) => (
                <div>
                    <Text strong>{name}</Text>
                    <br />
                    <Text type="secondary" style={{ fontSize: '12px' }}>
                        {record.customer_phone}
                    </Text>
                </div>
            )
        },
        {
            title: 'Số tiền',
            dataIndex: 'total_amount',
            key: 'total_amount',
            render: (amount: number) => (
                <Text strong style={{ color: '#f5222d' }}>
                    {formatVND(amount)}
                </Text>
            )
        },
        {
            title: 'Trạng thái',
            dataIndex: 'payment_status',
            key: 'payment_status',
            render: (status: string) => {
                const statusConfig = {
                    pending: { color: 'orange', icon: <ClockCircleOutlined />, text: 'Chờ thanh toán' },
                    confirmed: { color: 'green', icon: <CheckCircleOutlined />, text: 'Đã thanh toán' },
                    failed: { color: 'red', icon: <ClockCircleOutlined />, text: 'Thất bại' }
                };

                const config = statusConfig[status as keyof typeof statusConfig] || statusConfig.pending;

                return (
                    <Tag color={config.color} icon={config.icon}>
                        {config.text}
                    </Tag>
                );
            }
        },
        {
            title: 'Thời gian tạo',
            dataIndex: 'created_at',
            key: 'created_at',
            render: (date: string) => {
                const createdDate = new Date(date);
                return (
                    <div>
                        <Text>{createdDate.toLocaleDateString('vi-VN')}</Text>
                        <br />
                        <Text type="secondary" style={{ fontSize: '12px' }}>
                            {createdDate.toLocaleTimeString('vi-VN')}
                        </Text>
                    </div>
                );
            }
        },
        {
            title: 'Hành động',
            key: 'actions',
            render: (record: Booking) => (
                <Space>
                    <Button
                        type="primary"
                        icon={<EyeOutlined />}
                        size="small"
                        onClick={() => {
                            setSelectedBooking(record);
                            setModalVisible(true);
                        }}
                    >
                        Chi tiết
                    </Button>
                    {record.payment_status === 'pending' && (
                        <Button
                            type="primary"
                            icon={<CheckCircleOutlined />}
                            size="small"
                            style={{ backgroundColor: '#52c41a' }}
                            onClick={() => confirmPayment(record.booking_code)}
                        >
                            Xác nhận
                        </Button>
                    )}
                </Space>
            )
        }
    ];

    return (
        <Layout style={{ minHeight: '100vh', backgroundColor: '#f0f2f5' }}>
            <Header style={{ backgroundColor: '#001529', padding: '0 24px' }}>
                <div style={{ display: 'flex', alignItems: 'center', height: '100%' }}>
                    <BankOutlined style={{ color: 'white', fontSize: '24px', marginRight: '16px' }} />
                    <Title level={3} style={{ color: 'white', margin: 0 }}>
                        Admin - Quản lý thanh toán
                    </Title>
                </div>
            </Header>

            <Content style={{ padding: '24px' }}>
                <div style={{ maxWidth: 1400, margin: '0 auto' }}>
                    {/* Summary Cards */}
                    <div style={{ marginBottom: '24px' }}>
                        <Alert
                            message="Hướng dẫn sử dụng"
                            description={
                                <div>
                                    <p>• <strong>Bước 1:</strong> Khách hàng quét QR và chuyển khoản</p>
                                    <p>• <strong>Bước 2:</strong> Kiểm tra Internet Banking để xác nhận giao dịch</p>
                                    <p>• <strong>Bước 3:</strong> Click "Xác nhận" để hoàn tất booking</p>
                                    <p>• <strong>Lưu ý:</strong> Trang tự động refresh mỗi 30 giây</p>
                                </div>
                            }
                            type="info"
                            showIcon
                            style={{ marginBottom: '16px' }}
                        />

                        <Card>
                            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                                <div>
                                    <Title level={4} style={{ margin: 0 }}>
                                        Danh sách booking chờ thanh toán
                                    </Title>
                                    <Text type="secondary">
                                        Có {bookings.length} booking đang chờ xác nhận
                                    </Text>
                                </div>
                                <Button
                                    type="primary"
                                    onClick={fetchPendingPayments}
                                    loading={loading}
                                >
                                    Refresh
                                </Button>
                            </div>
                        </Card>
                    </div>

                    {/* Bookings Table */}
                    <Card>
                        <Table
                            columns={columns}
                            dataSource={bookings}
                            rowKey="id"
                            loading={loading}
                            pagination={{
                                pageSize: 10,
                                showSizeChanger: true,
                                showQuickJumper: true,
                                showTotal: (total) => `Tổng ${total} booking`
                            }}
                            scroll={{ x: 1000 }}
                        />
                    </Card>

                    {/* Detail Modal */}
                    <Modal
                        title={`Chi tiết booking ${selectedBooking?.booking_code}`}
                        visible={modalVisible}
                        onCancel={() => setModalVisible(false)}
                        width={800}
                        footer={[
                            <Button key="close" onClick={() => setModalVisible(false)}>
                                Đóng
                            </Button>,
                            selectedBooking?.payment_status === 'pending' && (
                                <Button
                                    key="confirm"
                                    type="primary"
                                    style={{ backgroundColor: '#52c41a' }}
                                    icon={<CheckCircleOutlined />}
                                    onClick={() => {
                                        if (selectedBooking) {
                                            confirmPayment(selectedBooking.booking_code);
                                        }
                                    }}
                                >
                                    Xác nhận thanh toán
                                </Button>
                            )
                        ]}
                    >
                        {selectedBooking && (
                            <div>
                                <Descriptions column={2} bordered size="small">
                                    <Descriptions.Item label="Mã booking" span={2}>
                                        <Text strong style={{ color: '#1890ff' }}>
                                            {selectedBooking.booking_code}
                                        </Text>
                                    </Descriptions.Item>
                                    <Descriptions.Item label="Khách hàng">
                                        {selectedBooking.customer_name}
                                    </Descriptions.Item>
                                    <Descriptions.Item label="Email">
                                        {selectedBooking.customer_email}
                                    </Descriptions.Item>
                                    <Descriptions.Item label="Điện thoại">
                                        {selectedBooking.customer_phone}
                                    </Descriptions.Item>
                                    <Descriptions.Item label="Số tiền">
                                        <Text strong style={{ color: '#f5222d' }}>
                                            {formatVND(selectedBooking.total_amount)}
                                        </Text>
                                    </Descriptions.Item>
                                    <Descriptions.Item label="Check-in">
                                        {selectedBooking.check_in}
                                    </Descriptions.Item>
                                    <Descriptions.Item label="Check-out">
                                        {selectedBooking.check_out}
                                    </Descriptions.Item>
                                    <Descriptions.Item label="Nội dung CK" span={2}>
                                        <Text code style={{ backgroundColor: '#f6f8fa', padding: '4px 8px' }}>
                                            LAVISH {selectedBooking.booking_code}
                                        </Text>
                                    </Descriptions.Item>
                                </Descriptions>

                                <div style={{ marginTop: '16px' }}>
                                    <Title level={5}>Thông tin chuyển khoản</Title>
                                    <Card size="small" style={{ backgroundColor: '#fafafa' }}>
                                        <Space direction="vertical" style={{ width: '100%' }}>
                                            <div style={{ display: 'flex', justifyContent: 'space-between' }}>
                                                <Text>Ngân hàng:</Text>
                                                <Text strong>MB Bank</Text>
                                            </div>
                                            <div style={{ display: 'flex', justifyContent: 'space-between' }}>
                                                <Text>Số tài khoản:</Text>
                                                <Text strong>0335920306</Text>
                                            </div>
                                            <div style={{ display: 'flex', justifyContent: 'space-between' }}>
                                                <Text>Chủ TK:</Text>
                                                <Text strong>NGUYEN VAN QUYEN</Text>
                                            </div>
                                            <div style={{ display: 'flex', justifyContent: 'space-between' }}>
                                                <Text>Số tiền:</Text>
                                                <Text strong style={{ color: '#f5222d' }}>
                                                    {formatVND(selectedBooking.total_amount)}
                                                </Text>
                                            </div>
                                            <div style={{ display: 'flex', justifyContent: 'space-between' }}>
                                                <Text>Nội dung:</Text>
                                                <Text strong style={{ color: '#52c41a' }}>
                                                    LAVISH {selectedBooking.booking_code}
                                                </Text>
                                            </div>
                                        </Space>
                                    </Card>
                                </div>
                            </div>
                        )}
                    </Modal>
                </div>
            </Content>
        </Layout>
    );
};

export default AdminPayment;
