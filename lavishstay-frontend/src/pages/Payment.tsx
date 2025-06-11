import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { 
    Layout, 
    Steps, 
    Form, 
    message, 
    Card, 
    Row, 
    Col, 
    Divider, 
    Tag, 
    Typography, 
    Space, 
    Image, 
    Button,
    Input,
    Checkbox,
    Radio,
    Descriptions,
    Progress,
    Alert,
    Badge
} from "antd";
import { 
    WifiOutlined, 
    CarOutlined, 
    UserOutlined, 
    GiftOutlined, 
    StarOutlined, 
    CoffeeOutlined,
    HomeOutlined,
    EyeOutlined,
    CheckCircleOutlined,
    CreditCardOutlined,
    BankOutlined,
    QrcodeOutlined,
    CalendarOutlined,
    TeamOutlined
} from "@ant-design/icons";
import { useSelector } from "react-redux";
import { RootState } from "../store";
import { selectBookingState, selectSelectedRoomsSummary, selectHasSelectedRooms } from "../store/slices/bookingSlice";
import { selectSearchData } from "../store/slices/searchSlice";

const { Content } = Layout;
const { Title, Text } = Typography;
const { TextArea } = Input;

// Service Icons Mapping
const iconMap: { [key: string]: React.ReactNode } = {
    WifiOutlined: <WifiOutlined />,
    CarOutlined: <CarOutlined />,
    UserOutlined: <UserOutlined />,
    GiftOutlined: <GiftOutlined />,
    StarOutlined: <StarOutlined />,
    CoffeeOutlined: <CoffeeOutlined />,
    HomeOutlined: <HomeOutlined />,
    EyeOutlined: <EyeOutlined />,
};

// Format VND Currency
const formatVND = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
};

// Payment Methods
const paymentMethods = [
    {
        id: 'vietqr',
        name: 'VietQR',
        icon: <QrcodeOutlined />,
        description: 'Quét mã QR để thanh toán',
        fee: 0
    },
    {
        id: 'bank_transfer',
        name: 'Chuyển khoản ngân hàng',
        icon: <BankOutlined />,
        description: 'Chuyển khoản trực tiếp',
        fee: 0
    },
    {
        id: 'credit_card',
        name: 'Thẻ tín dụng/ghi nợ',
        icon: <CreditCardOutlined />,
        description: 'Visa, Mastercard, JCB',
        fee: 15000
    }
];

const Payment: React.FC = () => {
    const navigate = useNavigate();
    const [form] = Form.useForm();
    const [currentStep, setCurrentStep] = useState(0);
    const [selectedPaymentMethod, setSelectedPaymentMethod] = useState('vietqr');
    const [countdown, setCountdown] = useState(900); // 15 minutes
    const [isProcessing, setIsProcessing] = useState(false);

    // Redux state
    const bookingState = useSelector(selectBookingState);
    const searchData = useSelector(selectSearchData);
    const selectedRoomsSummary = useSelector(selectSelectedRoomsSummary);
    const hasSelectedRooms = useSelector(selectHasSelectedRooms);// Check if we have booking data from navigation or Redux
    useEffect(() => {
        if (!hasSelectedRooms || selectedRoomsSummary.length === 0) {
            message.error('Không có thông tin đặt phòng. Vui lòng chọn phòng trước.');
            navigate('/search');
            return;
        }

        // Start countdown timer
        const timer = setInterval(() => {
            setCountdown(prev => {
                if (prev <= 1) {
                    clearInterval(timer);
                    message.warning('Phiên đặt phòng đã hết hạn. Vui lòng đặt lại.');
                    navigate('/search');
                    return 0;
                }
                return prev - 1;
            });
        }, 1000);

        return () => clearInterval(timer);
    }, [hasSelectedRooms, selectedRoomsSummary, navigate]);

    // Format countdown time
    const formatTime = (seconds: number) => {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    };    // Handle form submission
    const handleSubmit = async () => {
        setIsProcessing(true);
        try {
            // Simulate processing
            await new Promise(resolve => setTimeout(resolve, 2000));
            setCurrentStep(1);
            message.success('Đã xác nhận thông tin. Vui lòng thanh toán.');
        } catch (error) {
            message.error('Có lỗi xảy ra. Vui lòng thử lại.');
        } finally {
            setIsProcessing(false);
        }
    };

    // Handle payment
    const handlePayment = async () => {
        setIsProcessing(true);
        try {
            // Simulate payment processing
            await new Promise(resolve => setTimeout(resolve, 3000));
            setCurrentStep(2);
            message.success('Thanh toán thành công!');
        } catch (error) {
            message.error('Thanh toán thất bại. Vui lòng thử lại.');
        } finally {
            setIsProcessing(false);
        }
    };

    // Calculate totals
    const calculateTotals = () => {
        const roomsTotal = bookingState.totals.roomsTotal;
        const serviceFee = selectedPaymentMethod === 'credit_card' ? 15000 : 0;
        const tax = Math.round(roomsTotal * 0.1);
        const total = roomsTotal + serviceFee + tax;

        return { roomsTotal, serviceFee, tax, total };
    };    const totals = calculateTotals();
    const nights = searchData.checkIn && searchData.checkOut 
        ? Math.ceil((new Date(searchData.checkOut).getTime() - new Date(searchData.checkIn).getTime()) / (1000 * 60 * 60 * 24)) 
        : 1;

    // Steps configuration
    const steps = [
        {
            title: 'Thông tin đặt phòng',
            description: 'Xác nhận thông tin'
        },
        {
            title: 'Thanh toán',
            description: 'Quét mã QR'
        },
        {
            title: 'Hoàn thành',
            description: 'Xác nhận đặt phòng'
        }
    ];

    // Render Booking Info Step
    const renderBookingInfoStep = () => (
        <Row gutter={[24, 24]}>
            <Col span={16}>
                <Card title="Thông tin khách hàng" className="mb-4">
                    <Form form={form} layout="vertical" onFinish={handleSubmit}>
                        <Row gutter={16}>
                            <Col span={12}>
                                <Form.Item
                                    name="firstName"
                                    label="Họ"
                                    rules={[{ required: true, message: 'Vui lòng nhập họ' }]}
                                >
                                    <Input placeholder="Nhập họ" />
                                </Form.Item>
                            </Col>
                            <Col span={12}>
                                <Form.Item
                                    name="lastName"
                                    label="Tên"
                                    rules={[{ required: true, message: 'Vui lòng nhập tên' }]}
                                >
                                    <Input placeholder="Nhập tên" />
                                </Form.Item>
                            </Col>
                        </Row>
                        <Row gutter={16}>
                            <Col span={12}>
                                <Form.Item
                                    name="email"
                                    label="Email"
                                    rules={[
                                        { required: true, message: 'Vui lòng nhập email' },
                                        { type: 'email', message: 'Email không hợp lệ' }
                                    ]}
                                >
                                    <Input placeholder="example@email.com" />
                                </Form.Item>
                            </Col>
                            <Col span={12}>
                                <Form.Item
                                    name="phone"
                                    label="Số điện thoại"
                                    rules={[{ required: true, message: 'Vui lòng nhập số điện thoại' }]}
                                >
                                    <Input placeholder="0123456789" />
                                </Form.Item>
                            </Col>
                        </Row>
                        <Form.Item name="specialRequests" label="Yêu cầu đặc biệt">
                            <TextArea rows={3} placeholder="Nhập yêu cầu đặc biệt (tùy chọn)" />
                        </Form.Item>
                        <Form.Item>
                            <Checkbox>
                                Tôi đồng ý với <a href="/terms">điều khoản dịch vụ</a> và <a href="/privacy">chính sách bảo mật</a>
                            </Checkbox>
                        </Form.Item>
                        <Form.Item>
                            <Button 
                                type="primary" 
                                htmlType="submit" 
                                size="large" 
                                loading={isProcessing}
                                block
                            >
                                Tiếp tục thanh toán
                            </Button>
                        </Form.Item>
                    </Form>
                </Card>
            </Col>

            <Col span={8}>
                {renderBookingSummary()}
            </Col>
        </Row>
    );

    // Render Payment Step
    const renderPaymentStep = () => (
        <Row gutter={[24, 24]}>
            <Col span={16}>
                <Card title="Phương thức thanh toán" className="mb-4">
                    <Radio.Group 
                        value={selectedPaymentMethod} 
                        onChange={(e) => setSelectedPaymentMethod(e.target.value)}
                        className="w-full"
                    >
                        <Space direction="vertical" className="w-full">
                            {paymentMethods.map(method => (
                                <Radio key={method.id} value={method.id} className="w-full">
                                    <Card 
                                        size="small" 
                                        className={`ml-6 ${selectedPaymentMethod === method.id ? 'border-blue-500' : ''}`}
                                    >
                                        <Row align="middle" justify="space-between">
                                            <Col>
                                                <Space>
                                                    {method.icon}
                                                    <div>
                                                        <Text strong>{method.name}</Text>
                                                        <br />
                                                        <Text type="secondary">{method.description}</Text>
                                                    </div>
                                                </Space>
                                            </Col>
                                            <Col>
                                                {method.fee > 0 && (
                                                    <Tag color="orange">Phí: {formatVND(method.fee)}</Tag>
                                                )}
                                            </Col>
                                        </Row>
                                    </Card>
                                </Radio>
                            ))}
                        </Space>
                    </Radio.Group>
                </Card>

                {selectedPaymentMethod === 'vietqr' && (
                    <Card title="Quét mã QR để thanh toán">
                        <Row gutter={24} align="middle">
                            <Col span={12}>
                                <div className="text-center">
                                    <div className="w-64 h-64 border-2 border-dashed border-gray-300 flex items-center justify-center mx-auto mb-4">
                                        <QrcodeOutlined style={{ fontSize: '4rem', color: '#ccc' }} />
                                    </div>
                                    <Text type="secondary">Mã QR sẽ được tạo sau khi xác nhận</Text>
                                </div>
                            </Col>
                            <Col span={12}>
                                <Descriptions column={1} size="small">
                                    <Descriptions.Item label="Ngân hàng">VietComBank</Descriptions.Item>
                                    <Descriptions.Item label="Số tài khoản">1234567890</Descriptions.Item>
                                    <Descriptions.Item label="Chủ tài khoản">LAVISHSTAY HOTEL</Descriptions.Item>
                                    <Descriptions.Item label="Số tiền">{formatVND(totals.total)}</Descriptions.Item>
                                    <Descriptions.Item label="Nội dung">
                                        LAVISH{Date.now().toString().slice(-6)}
                                    </Descriptions.Item>
                                </Descriptions>
                                <Alert
                                    message="Thời gian thanh toán"
                                    description={`Còn lại: ${formatTime(countdown)}`}
                                    type="warning"
                                    showIcon
                                    className="mt-4"
                                />
                            </Col>
                        </Row>
                        <Divider />
                        <Space>
                            <Button onClick={() => setCurrentStep(0)}>
                                Quay lại
                            </Button>
                            <Button type="primary" loading={isProcessing} onClick={handlePayment}>
                                Xác nhận thanh toán
                            </Button>
                            <Button>Kiểm tra thanh toán</Button>
                        </Space>
                    </Card>
                )}
            </Col>

            <Col span={8}>
                {renderBookingSummary()}
            </Col>
        </Row>
    );

    // Render Completion Step
    const renderCompletionStep = () => (
        <div className="text-center">
            <Card>
                <div className="py-8">
                    <CheckCircleOutlined style={{ fontSize: '4rem', color: '#52c41a' }} className="mb-4" />
                    <Title level={2}>Đặt phòng thành công!</Title>
                    <Text type="secondary" className="text-lg">
                        Cảm ơn bạn đã tin tương và sử dụng dịch vụ của LavishStay
                    </Text>
                    
                    <Divider />
                    
                    <Descriptions title="Thông tin đặt phòng" column={2}>
                        <Descriptions.Item label="Mã đặt phòng">
                            <Text strong>LAVISH{Date.now().toString().slice(-8)}</Text>
                        </Descriptions.Item>
                        <Descriptions.Item label="Trạng thái">
                            <Badge status="success" text="Đã xác nhận" />
                        </Descriptions.Item>
                        <Descriptions.Item label="Check-in">
                            {searchData.checkIn}
                        </Descriptions.Item>
                        <Descriptions.Item label="Check-out">
                            {searchData.checkOut}
                        </Descriptions.Item>
                        <Descriptions.Item label="Số phòng">
                            {selectedRoomsSummary.length} phòng
                        </Descriptions.Item>
                        <Descriptions.Item label="Tổng tiền">
                            <Text strong style={{ color: '#f5222d', fontSize: '1.2em' }}>
                                {formatVND(totals.total)}
                            </Text>
                        </Descriptions.Item>
                    </Descriptions>

                    <Divider />

                    <Space size="large">
                        <Button type="primary" size="large" onClick={() => navigate('/bookings')}>
                            Xem đặt phòng
                        </Button>
                        <Button size="large" onClick={() => navigate('/search')}>
                            Đặt phòng mới
                        </Button>
                    </Space>
                </div>
            </Card>
        </div>
    );

    // Render Booking Summary
    const renderBookingSummary = () => (
        <Card title="Chi tiết đặt phòng" className="sticky top-4">
            <Space direction="vertical" className="w-full">
                <div>
                    <Text strong>LavishStay Thanh Hóa</Text>
                    <br />
                    <Text type="secondary">Thanh Hóa, Việt Nam</Text>
                </div>

                <Descriptions column={1} size="small">
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
                        <TeamOutlined /> {(searchData.guestDetails?.adults || 0) + (searchData.guestDetails?.children || 0) || 2} khách
                    </Descriptions.Item>
                </Descriptions>

                <Divider />

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
                                    />
                                </Col>
                                <Col span={16}>
                                    <Text strong className="text-sm">{summary.room.name}</Text>
                                    <br />                                    <Text type="secondary" className="text-xs">
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

                            {/* Room Services */}
                            <div className="mt-2">
                                <Text className="text-xs font-medium">{summary.option.name}</Text>
                                {summary.option.additionalServices && (
                                    <div className="mt-1">
                                        {summary.option.additionalServices
                                            .filter((service: any) => service.included)
                                            .slice(0, 3)
                                            .map((service: any, serviceIndex: number) => (
                                            <Tag key={serviceIndex} color="green" className="text-xs mb-1">
                                                {iconMap[service.icon]} {service.name}
                                            </Tag>
                                        ))}
                                    </div>
                                )}
                            </div>
                        </Card>
                    ))}
                </div>

                <Divider />

                <div>
                    <div className="flex justify-between mb-2">
                        <Text>Tổng tiền phòng:</Text>
                        <Text>{formatVND(totals.roomsTotal)}</Text>
                    </div>
                    <div className="flex justify-between mb-2">
                        <Text>Thuế & phí:</Text>
                        <Text>{formatVND(totals.tax)}</Text>
                    </div>
                    {totals.serviceFee > 0 && (
                        <div className="flex justify-between mb-2">
                            <Text>Phí dịch vụ:</Text>
                            <Text>{formatVND(totals.serviceFee)}</Text>
                        </div>
                    )}
                    <Divider />
                    <div className="flex justify-between">
                        <Text strong>Tổng cộng:</Text>
                        <Text strong style={{ color: '#f5222d', fontSize: '1.1em' }}>
                            {formatVND(totals.total)}
                        </Text>
                    </div>
                </div>
            </Space>
        </Card>
    );

    // Render step content
    const renderStepContent = () => {
        switch (currentStep) {
            case 0:
                return renderBookingInfoStep();
            case 1:
                return renderPaymentStep();
            case 2:
                return renderCompletionStep();
            default:
                return null;
        }
    };    return (
        <Layout style={{ backgroundColor: '#f0f2f5', minHeight: '100vh' }}>
            <Content style={{ padding: '24px' }}>
                <div style={{ maxWidth: 1200, margin: '0 auto' }}>
                    {/* Header */}
                    <div className="text-center mb-8">
                        <Title level={2}>Thanh toán đặt phòng</Title>
                        <div className="bg-white rounded-lg shadow-sm p-4 mb-6">
                            <Progress 
                                percent={(currentStep + 1) * 33.33} 
                                showInfo={false} 
                                strokeColor="#1890ff"
                            />
                            <Steps
                                current={currentStep}
                                items={steps}
                                className="mt-4"
                            />
                        </div>
                    </div>

                    {/* Time remaining alert */}
                    {currentStep < 2 && (
                        <Alert
                            message={`Thời gian còn lại: ${formatTime(countdown)}`}
                            description="Vui lòng hoàn tất thanh toán trong thời gian quy định"
                            type="warning"
                            showIcon
                            closable
                            className="mb-6"
                        />
                    )}

                    {/* Step Content */}
                    {renderStepContent()}
                </div>
            </Content>
        </Layout>
    );
};

export default Payment;
