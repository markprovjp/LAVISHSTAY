import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import {
    Layout, Typography, Form, Input, Button, Card, Row, Col,
    Steps, Radio, Space, Tag, Alert, Descriptions, Divider,
    Checkbox, Progress, Badge, Image, message
} from 'antd';
import {
    CalendarOutlined, TeamOutlined, QrcodeOutlined, BankOutlined,
    CheckCircleOutlined, WifiOutlined, CarOutlined, UserOutlined,
    GiftOutlined, StarOutlined, CoffeeOutlined, HomeOutlined, EyeOutlined
} from '@ant-design/icons';
import { useSelector } from 'react-redux';
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

// VietQR Configuration
const VIETQR_CONFIG = {
    bankId: 'MB', // MB Bank - shortName
    bankBin: '970422', // MB Bank BIN
    accountNo: '0335920306',
    accountName: 'NGUYEN VAN QUYEN',
    template: 'compact2' // or 'print', 'compact', etc.
};

// Generate VietQR URL using correct format
const generateVietQRUrl = (amount: number, content: string) => {

    const baseUrl = 'https://img.vietqr.io/image';
    const imagePath = `${VIETQR_CONFIG.bankId}-${VIETQR_CONFIG.accountNo}-${VIETQR_CONFIG.template}.png`;

    const params = new URLSearchParams({
        amount: amount.toString(),
        addInfo: encodeURIComponent(content),
        accountName: encodeURIComponent(VIETQR_CONFIG.accountName)
    });

    return `${baseUrl}/${imagePath}?${params.toString()}`;
};

// Payment Methods - Chỉ có 2 phương thức
const paymentMethods = [
    {
        id: 'vietqr',
        name: 'VietQR - Quét mã QR',
        icon: <QrcodeOutlined />,
        description: 'Chuyển khoản qua quét mã QR',
        fee: 0
    },
    {
        id: 'pay_at_hotel',
        name: 'Thanh toán tại khách sạn',
        icon: <BankOutlined />,
        description: 'Thanh toán khi nhận phòng',
        fee: 0
    }
];

const Payment: React.FC = () => {
    const navigate = useNavigate();
    const [form] = Form.useForm();
    const [currentStep, setCurrentStep] = useState(0);
    const [selectedPaymentMethod, setSelectedPaymentMethod] = useState('vietqr');
    const [countdown, setCountdown] = useState(900); // 15 minutes
    const [isProcessing, setIsProcessing] = useState(false);
    const [bookingCode, setBookingCode] = useState('');
    const [paymentStatus, setPaymentStatus] = useState('pending'); // pending, confirmed, failed
    const [isCheckingPayment, setIsCheckingPayment] = useState(false);

    // Redux state
    const bookingState = useSelector(selectBookingState);
    const searchData = useSelector(selectSearchData);
    const selectedRoomsSummary = useSelector(selectSelectedRoomsSummary);
    const hasSelectedRooms = useSelector(selectHasSelectedRooms);

    // Generate booking code
    useEffect(() => {
        const code = `LAVISH${Date.now().toString().slice(-8)}`;
        setBookingCode(code);
    }, []);

    // Check if we have booking data from navigation or Redux
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
    };

    // Handle form submission
    const handleSubmit = async (values: any) => {
        setIsProcessing(true);
        try {
            // Create booking first
            await createBooking(values);
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
        const serviceFee = 0; // Không có phí dịch vụ
        const tax = Math.round(roomsTotal * 0.1);
        const total = roomsTotal + serviceFee + tax;

        return { roomsTotal, serviceFee, tax, total };
    };

    const totals = calculateTotals();
    const nights = searchData.checkIn && searchData.checkOut
        ? Math.ceil((new Date(searchData.checkOut).getTime() - new Date(searchData.checkIn).getTime()) / (1000 * 60 * 60 * 24))
        : 1;

    // Generate payment content for VietQR
    const generatePaymentContent = () => {
        return `LAVISH ${bookingCode}`;
    };

    // API Base URL
    const API_BASE_URL = 'http://localhost:8000/api';

    // Create booking API call
    const createBooking = async (customerData: any) => {
        try {
            const response = await fetch(`${API_BASE_URL}/payment/create-booking`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    booking_code: bookingCode,
                    customer_name: `${customerData.firstName} ${customerData.lastName}`,
                    customer_email: customerData.email,
                    customer_phone: customerData.phone,
                    rooms_data: JSON.stringify(selectedRoomsSummary),
                    total_amount: totals.total,
                    payment_method: selectedPaymentMethod,
                    check_in: searchData.checkIn,
                    check_out: searchData.checkOut,
                    special_requests: customerData.specialRequests || ''
                })
            });

            const result = await response.json();
            if (!result.success) {
                throw new Error(result.message);
            }
            return result;
        } catch (error) {
            console.error('Error creating booking:', error);
            throw error;
        }
    };

    // Check payment status API call  
    const checkPaymentStatus = async () => {
        try {
            const response = await fetch(`${API_BASE_URL}/payment/status/${bookingCode}`);
            const result = await response.json();

            if (result.success) {
                setPaymentStatus(result.payment_status);

                // Nếu thanh toán đã được confirm, chuyển đến step completion
                if (result.payment_status === 'confirmed' && currentStep === 1) {
                    setCurrentStep(2);
                    message.success('Thanh toán đã được xác nhận!');
                }
            }
        } catch (error) {
            console.error('Error checking payment status:', error);
        }
    };

    // Polling payment status every 10 seconds khi ở step payment
    useEffect(() => {
        if (currentStep === 1 && selectedPaymentMethod === 'vietqr' && bookingCode) {
            setIsCheckingPayment(true);

            // Check immediately
            checkPaymentStatus();

            // Then check every 10 seconds
            const interval = setInterval(checkPaymentStatus, 10000);

            return () => {
                clearInterval(interval);
                setIsCheckingPayment(false);
            };
        }
    }, [currentStep, selectedPaymentMethod, bookingCode]);

    // Generate QR Code function
    const generateQRCode = () => {
        // Just log the QR URL for debugging
        const vietQRUrl = generateVietQRUrl(totals.total, generatePaymentContent());
        console.log('VietQR URL:', vietQRUrl);
    };

    // Update QR code when booking details change
    useEffect(() => {
        if (bookingCode && totals.total) {
            generateQRCode();
        }
    }, [bookingCode, totals.total]);

    // Steps configuration
    const steps = [
        {
            title: 'Thông tin đặt phòng',
            description: 'Xác nhận thông tin'
        },
        {
            title: 'Thanh toán',
            description: 'Chọn phương thức thanh toán'
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
                                                <Tag color="green">Miễn phí</Tag>
                                            </Col>
                                        </Row>
                                    </Card>
                                </Radio>
                            ))}
                        </Space>
                    </Radio.Group>
                </Card>

                {selectedPaymentMethod === 'vietqr' && (
                    <Card title="Quét mã QR để thanh toán" className="shadow-sm">
                        <Row gutter={34} align="top">
                            <Col span={12}>
                                <div className="text-center">
                                    <div className=" p-6 rounded-lg mb-4">
                                        <div className="w-77 h-77 border border-gray-200 flex items-center justify-center mx-auto  rounded-lg shadow-sm">
                                            <Image
                                                src={generateVietQRUrl(totals.total, generatePaymentContent())}
                                                alt="VietQR Payment"
                                                width={350}
                                                height={350}
                                                preview={true}
                                                style={{ borderRadius: 8 }}
                                                fallback="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMIAAADDCAYAAADQvc6UAAABRWlDQ1BJQ0MgUHJvZmlsZQAAKJFjYGASSSwoyGFhYGDIzSspCnJ3UoiIjFJgf8LAwSDCIMogwMCcmFxc4BgQ4ANUwgCjUcG3awyMIPqyLsis7PPOq3QdDFcvjV3jOD1boQVTPQrgSkktTgbSf4A4LbmgqISBgTEFyFYuLykAsTuAbJEioKOA7DkgdjqEvQHEToKwj4DVhAQ5A9k3gGyB5IxEoBmML4BsnSQk8XQkNtReEOBxcfXxUQg1Mjc0dyHgXNJBSWpFCYh2zi+oLMpMzyhRcASGUqqCZ16yno6CkYGRAQMDKMwhqj/fAIcloxgHQqxAjIHBEugw5sUIsSQpBobtQPdLciLEVJYzMPBHMDBsayhILEqEO4DxG0txmrERhM29nYGBddr//5/DGRjYNRkY/l7////39v///y4Dmn+LgeHANwDrkl1AuO+pmgAAADhlWElmTU0AKgAAAAgAAYdpAAQAAAABAAAAGgAAAAAAAqACAAQAAAABAAAAwqADAAQAAAABAAAAwwAAAAD9b/HnAAAHlklEQVR4Ae3dP3Ik1RnG4W+FgYxN"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </Col>
                            <Col span={12}>
                                <div className="space-y-4">
                                    <Alert
                                        message="Thông tin chuyển khoản"
                                        type="info"
                                        showIcon={false}
                                        className="mb-4"
                                        style={{
                                            backgroundColor: '#f6f8fa',
                                            border: '1px solid #e1e4e8',
                                            borderRadius: '8px'
                                        }}
                                    />

                                    <div className="space-y-3">
                                        <div className="flex justify-between items-center py-2 border-b border-gray-100">
                                            <Text className="text-gray-600">Ngân hàng:</Text>
                                            <Text strong>MB Bank</Text>
                                        </div>

                                        <div className="flex justify-between items-center py-2 border-b border-gray-100">
                                            <Text className="text-gray-600">Số tài khoản:</Text>
                                            <Text strong code className="bg-blue-50 px-2 py-1 rounded text-blue-700">
                                                0335920306
                                            </Text>
                                        </div>

                                        <div className="flex justify-between items-center py-2 border-b border-gray-100">
                                            <Text className="text-gray-600">Chủ tài khoản:</Text>
                                            <Text strong>NGUYEN VAN QUYEN</Text>
                                        </div>

                                        <div className="flex justify-between items-center py-2 border-b border-gray-100">
                                            <Text className="text-gray-600">Số tiền:</Text>
                                            <Text strong className="text-red-600 text-lg">
                                                {formatVND(totals.total)}
                                            </Text>
                                        </div>

                                        <div className="flex justify-between items-start py-2 border-b border-gray-100">
                                            <Text className="text-gray-600">Nội dung:</Text>
                                            <Text strong code className="bg-green-50 px-2 py-1 rounded text-green-700 text-right">
                                                {generatePaymentContent()}
                                            </Text>
                                        </div>

                                        <div className="flex justify-between items-center py-2">
                                            <Text className="text-gray-600">Mã đặt phòng:</Text>
                                            <Text strong className="text-green-600">
                                                {bookingCode}
                                            </Text>
                                        </div>
                                    </div>

                                    <Alert
                                        message={`Thời gian còn lại: ${formatTime(countdown)}`}
                                        description="Vui lòng hoàn tất thanh toán trong thời gian quy định"
                                        type="warning"
                                        showIcon
                                        className="mt-4"
                                    />
                                </div>
                            </Col>
                        </Row>
                        <Divider />
                        <div className="flex justify-between">
                            <Button size="large" onClick={() => setCurrentStep(0)}>
                                Quay lại
                            </Button>
                            <Button
                                type="primary"
                                size="large"
                                loading={isProcessing}
                                onClick={handlePayment}
                                className="px-8"
                            >
                                Xác nhận đã thanh toán
                            </Button>
                        </div>
                    </Card>
                )}

                {selectedPaymentMethod === 'pay_at_hotel' && (
                    <Card title="Thanh toán tại khách sạn">
                        <Alert
                            message="Thanh toán tại khách sạn"
                            description="Bạn sẽ thanh toán trực tiếp tại quầy lễ tân khi nhận phòng. Vui lòng mang theo giấy tờ tùy thân và thông tin đặt phòng."
                            type="info"
                            showIcon
                            className="mb-4"
                        />
                        <Descriptions column={1} size="small">
                            <Descriptions.Item label="Mã đặt phòng">
                                <Text strong style={{ color: '#52c41a' }}>
                                    {bookingCode}
                                </Text>
                            </Descriptions.Item>
                            <Descriptions.Item label="Tổng tiền cần thanh toán">
                                <Text strong style={{ color: '#f5222d', fontSize: '1.1em' }}>
                                    {formatVND(totals.total)}
                                </Text>
                            </Descriptions.Item>
                            <Descriptions.Item label="Hình thức thanh toán">
                                Tiền mặt hoặc thẻ tín dụng/ghi nợ
                            </Descriptions.Item>
                        </Descriptions>
                        <Divider />
                        <Space>
                            <Button onClick={() => setCurrentStep(0)}>
                                Quay lại
                            </Button>
                            <Button type="primary" loading={isProcessing} onClick={handlePayment}>
                                Xác nhận đặt phòng
                            </Button>
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
                        Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của LavishStay
                    </Text>

                    <Divider />

                    <Descriptions title="Thông tin đặt phòng" column={2}>
                        <Descriptions.Item label="Mã đặt phòng">
                            <Text strong style={{ color: '#52c41a', fontSize: '1.2em' }}>
                                {bookingCode}
                            </Text>
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
                        <Descriptions.Item label="Phương thức thanh toán">
                            {selectedPaymentMethod === 'vietqr' ? 'VietQR' : 'Thanh toán tại khách sạn'}
                        </Descriptions.Item>
                        <Descriptions.Item label="Tổng tiền" span={2}>
                            <Text strong style={{ color: '#f5222d', fontSize: '1.5em' }}>
                                {formatVND(totals.total)}
                            </Text>
                        </Descriptions.Item>
                    </Descriptions>

                    <Divider />

                    <Alert
                        message="Hướng dẫn check-in"
                        description={
                            <div>
                                <p>• Vui lòng mang theo giấy tờ tùy thân và mã đặt phòng: <Text strong>{bookingCode}</Text></p>
                                <p>• Thời gian check-in: 14:00 | Thời gian check-out: 12:00</p>
                                <p>• Liên hệ: 0123456789 nếu có thắc mắc</p>
                            </div>
                        }
                        type="info"
                        showIcon
                        className="mb-6 text-left"
                    />

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
    };

    return (
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
