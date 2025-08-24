import React, { useEffect, useState } from 'react';
import {
    Card, Typography, Button, Descriptions, List, Row, Col, Divider, Spin,
    notification, Modal, Form, Input, Tag, Space, Statistic, Image, Tooltip, Collapse,
    Avatar, Result, Badge
} from 'antd';
import {
    CheckCircleOutlined, HomeOutlined, RedoOutlined, CopyOutlined, DownloadOutlined,
    PrinterOutlined, MailOutlined, DollarOutlined, UserOutlined, CalendarOutlined,
    PhoneOutlined, IdcardOutlined, TeamOutlined
} from '@ant-design/icons';
import { useDispatch } from 'react-redux';
import authService from '../../services/authService';
import axiosInstance from '../../config/axios';

/**
 * CompletionStep Component - Displays full booking details after successful booking
 * 
 * Backend Endpoint: GET /api/payment/booking-details/{bookingCode}
 * Sample Response: {
 *   success: true,
 *   data: {
 *     booking: { booking_code, booking_id, created_at, status, guest_name, guest_email, guest_phone, check_in_date, check_out_date, total_price_vnd, payment_status },
 *     rooms: [{ room_name, selected_option_name, selected_option_price, adults, children, representative_name, representative_phone, representative_email }],
 *     payment: { payment_method, status, payment_url, qr_code_url },
 *     summary: { total_rooms, total_guests, total_amount, payment_status }
 *   }
 * }
 */

// Helper to format currency
const formatVND = (amount: number) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);

// TypeScript interfaces for booking data
interface BookingData {
    booking_code: string;
    booking_id: number;
    created_at: string;
    status: 'pending' | 'confirmed' | 'cancelled';
    guest_name: string;
    guest_email: string;
    guest_phone: string;
    check_in_date: string;
    check_out_date: string;
    total_price_vnd: number;
    payment_status: 'pending' | 'completed' | 'failed';
}

interface RoomData {
    room_name: string;
    selected_option_name: string;
    selected_option_price: number;
    adults: number;
    children: number;
    representative_name?: string;
    representative_phone?: string;
    representative_email?: string;
    cancellation_policy_type?: string;
    payment_policy_type?: string;
}

interface PaymentData {
    payment_method: string;
    status: string;
    payment_url?: string;
    qr_code_url?: string;
}

interface BookingSummary {
    total_rooms: number;
    total_guests: number;
    total_amount: number;
    payment_status: string;
}

interface BookingDetailsResponse {
    success: boolean;
    message?: string;
    data: {
        booking: BookingData;
        rooms: RoomData[];
        payment: PaymentData;
        summary: BookingSummary;
    };
}

interface CompletionStepProps {
    bookingCode: string;
    selectedPaymentMethod: string;
    onNewBooking: () => void;
}

const CompletionStep: React.FC<CompletionStepProps> = ({
    bookingCode,
    selectedPaymentMethod,
    onNewBooking,
}) => {
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const [bookingDetails, setBookingDetails] = useState<BookingDetailsResponse | null>(null);
    const [verifyingPayment, setVerifyingPayment] = useState(false);
    const [api, contextHolder] = notification.useNotification();
    const [showPasswordModal, setShowPasswordModal] = useState(false);
    const [passwordError, setPasswordError] = useState<string | null>(null);

    const dispatch = useDispatch();

    // Check if user is logged in using authService
    const isLoggedIn = authService.isAuthenticated();

    // API Base URL
    const API_BASE_URL = 'http://localhost:8888/api';

    // Fetch booking details with timeout and retries
    const fetchBookingDetails = async (attempt = 0): Promise<void> => {
        try {
            setLoading(true);
            setError(null);

            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 10000); // 10s timeout

            const response = await fetch(`${API_BASE_URL}/payment/booking-details/${bookingCode}`, {
                signal: controller.signal,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            clearTimeout(timeoutId);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data: BookingDetailsResponse = await response.json();

            if (!data.success) {
                throw new Error(data.message || 'Failed to fetch booking details');
            }

            setBookingDetails(data);
            console.log('📦 Booking details fetched successfully:', data);

        } catch (err: any) {
            console.error('Error fetching booking details:', err);
            const errorMessage = err.name === 'AbortError'
                ? 'Timeout: Không thể tải thông tin đặt phòng'
                : err.message || 'Lỗi không xác định';

            setError(errorMessage);

            // Exponential backoff retry logic
            if (attempt < 3) {
                const delay = Math.pow(2, attempt) * 1000; // 1s, 2s, 4s
                setTimeout(() => {
                    fetchBookingDetails(attempt + 1);
                }, delay);
            }
        } finally {
            setLoading(false);
        }
    };

    // Verify payment function
    const verifyPayment = async () => {
        if (!bookingDetails?.data.booking.booking_code) return;

        try {
            setVerifyingPayment(true);

            const response = await fetch(`${API_BASE_URL}/payment/verify-vietqr`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    booking_code: bookingDetails.data.booking.booking_code
                })
            });

            const result = await response.json();

            if (result.success) {
                api.success({
                    message: 'Xác thực thanh toán thành công!',
                    description: 'Trạng thái thanh toán đã được cập nhật.',
                    duration: 4
                });

                // Refresh booking details
                await fetchBookingDetails();
            } else {
                throw new Error(result.message || 'Xác thực thất bại');
            }

        } catch (err: any) {
            console.error('Payment verification error:', err);
            api.error({
                message: 'Lỗi xác thực thanh toán',
                description: err.message || 'Vui lòng thử lại sau.',
                duration: 6
            });
        } finally {
            setVerifyingPayment(false);
        }
    };

    // Download invoice PDF
    const downloadInvoice = async () => {
        if (!bookingDetails?.data.booking.booking_id) return;

        try {
            const response = await fetch(`${API_BASE_URL}/reception/bookings/${bookingDetails.data.booking.booking_id}/invoice`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/pdf'
                }
            });

            if (!response.ok) {
                throw new Error('Không thể tải hóa đơn');
            }

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `Invoice-${bookingDetails.data.booking.booking_code}.pdf`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);

            api.success({
                message: 'Tải hóa đơn thành công!',
                description: 'Hóa đơn đã được tải về thiết bị của bạn.',
                duration: 3
            });

        } catch (err: any) {
            console.error('Download invoice error:', err);
            api.error({
                message: 'Lỗi tải hóa đơn',
                description: err.message || 'Vui lòng thử lại sau.',
                duration: 4
            });
        }
    };

    // Copy booking code to clipboard
    const copyBookingCode = async () => {
        if (!bookingDetails?.data.booking.booking_code) return;

        try {
            await navigator.clipboard.writeText(bookingDetails.data.booking.booking_code);
            api.success({
                message: 'Đã sao chép!',
                description: 'Mã đặt phòng đã được sao chép vào clipboard.',
                duration: 3
            });
        } catch (err) {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = bookingDetails.data.booking.booking_code;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);

            api.success({
                message: 'Đã sao chép!',
                description: 'Mã đặt phòng đã được sao chép.',
                duration: 3
            });
        }
    };

    // Print booking details
    const printBooking = () => {
        window.print();
    };

    // Calculate nights between dates
    const calculateNights = (checkIn: string, checkOut: string): number => {
        const start = new Date(checkIn);
        const end = new Date(checkOut);
        const diffTime = Math.abs(end.getTime() - start.getTime());
        return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    };

    // Initial fetch on component mount
    useEffect(() => {
        if (bookingCode) {
            fetchBookingDetails();
        }
    }, [bookingCode]);

    // Show notification and modal after booking success
    useEffect(() => {
        if (!loading && bookingDetails && !error) {
            if (isLoggedIn) {
                api.success({
                    message: 'Đặt phòng thành công!',
                    description: 'Đơn phòng đã được lưu vào tài khoản của bạn.',
                    duration: 4,
                });
            } else {
                api.success({
                    message: 'Đặt phòng thành công!',
                    description: 'Cảm ơn bạn đã đặt phòng tại LavishStay. Vui lòng kiểm tra email hoặc lưu lại mã đặt phòng.',
                    duration: 4,
                });
                setShowPasswordModal(true);
            }
        }
    }, [loading, bookingDetails, error, api, isLoggedIn]);
    // Handle password submit for guest (with password confirmation)
    const handlePasswordFinish = async (values: { password: string; password_confirmation: string }) => {
        setPasswordError(null);
        if (values.password !== values.password_confirmation) {
            setPasswordError('Mật khẩu nhập lại không khớp!');
            return;
        }

        if (!bookingDetails?.data.booking) {
            setPasswordError('Không tìm thấy thông tin đặt phòng!');
            return;
        }

        try {
            // 1. Đăng ký tài khoản
            const registerData = {
                name: bookingDetails.data.booking.guest_name,
                email: bookingDetails.data.booking.guest_email,
                phone: bookingDetails.data.booking.guest_phone,
                password: values.password,
                password_confirmation: values.password_confirmation
            };

            // Basic guard: don't call register API if required fields are missing
            if (!registerData.name || !registerData.email) {
                api.error({
                    message: 'Không thể tạo tài khoản',
                    description: 'Thiếu tên hoặc email khách hàng. Vui lòng kiểm tra thông tin đặt phòng hoặc nhập thủ công.',
                    duration: 6,
                });
                setPasswordError(null);
                return;
            }

            console.log('[Đăng ký] Dữ liệu gửi lên:', registerData);
            const registerRes = await authService.register(registerData);
            console.log('[Đăng ký] Kết quả trả về:', registerRes);

            // 2. Đăng nhập tự động bằng email và password vừa nhập
            const loginRes = await authService.login({
                email: registerData.email,
                password: registerData.password
            });
            console.log('[Đăng nhập] Kết quả trả về:', loginRes);
            const { user, token } = loginRes;

            // Lưu user vào localStorage để đồng bộ Redux state
            localStorage.setItem('authUser', JSON.stringify(user));

            // 3. Gán booking vào tài khoản
            await axiosInstance.post('/booking/assign', {
                bookingCode: bookingDetails.data.booking.booking_code,
                userId: user.id
            }, {
                headers: { Authorization: `Bearer ${token}` }
            });

            setShowPasswordModal(false);

            // Tự động fetch lại danh sách booking cho user
            if (dispatch && typeof dispatch === 'function') {
                try {
                    // @ts-ignore
                    dispatch(fetchUserBookings());
                } catch (e) { /* ignore */ }
                try {
                    // @ts-ignore
                    dispatch(setUser(user));
                } catch (e) { /* ignore */ }
            }

            api.success({
                message: 'Tài khoản đã được tạo!',
                description: 'Bạn đã đăng nhập và đơn phòng đã được gán vào tài khoản.',
                duration: 4,
            });
        } catch (err: any) {
            console.error('[Đăng ký] Lỗi trả về (raw):', err);
            let errorMsg = 'Vui lòng thử lại hoặc liên hệ hỗ trợ.';
            if (err?.response?.data) {
                const data = err.response.data;
                if (data?.errors?.password) {
                    setPasswordError(Array.isArray(data.errors.password) ? data.errors.password.join(' ') : data.errors.password);
                    return;
                }

                if (data?.errors?.email) {
                    const msg = Array.isArray(data.errors.email) ? data.errors.email.join(' ') : data.errors.email;
                    api.error({ message: 'Lỗi email', description: msg, duration: 6 });
                    return;
                }
                if (data?.errors?.phone) {
                    const msg = Array.isArray(data.errors.phone) ? data.errors.phone.join(' ') : data.errors.phone;
                    api.error({ message: 'Lỗi số điện thoại', description: msg, duration: 6 });
                    return;
                }

                if (data?.message) errorMsg = data.message;
                if (data?.errors) {
                    errorMsg += '<br />' + Object.values(data.errors).map((v: any) => Array.isArray(v) ? v.join(', ') : v).join('<br />');
                }
            }

            api.error({
                message: 'Có lỗi xảy ra!',
                description: <span dangerouslySetInnerHTML={{ __html: errorMsg }} />,
                duration: 7,
            });
        }
    };

    // Send confirmation email
    const sendConfirmationEmail = async () => {
        if (!bookingDetails?.data.booking.booking_code) return;

        try {
            const response = await fetch(`${API_BASE_URL}/booking/send-confirmation`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    booking_code: bookingDetails.data.booking.booking_code
                })
            });

            const result = await response.json();

            if (result.success) {
                api.success({
                    message: 'Email xác nhận đã được gửi!',
                    description: 'Vui lòng kiểm tra hộp thư của bạn.',
                    duration: 4
                });
            } else {
                throw new Error(result.message || 'Gửi email thất bại');
            }

        } catch (err: any) {
            console.error('Send email error:', err);
            api.error({
                message: 'Lỗi gửi email',
                description: err.message || 'Vui lòng thử lại sau.',
                duration: 4
            });
        }
    };

    // Retry function for failed fetch
    const handleRetry = () => {
        fetchBookingDetails();
    };

    // Loading state
    if (loading) {
        return (
            <div className="text-center p-8">
                <Spin size="large" />
                <div className="mt-4">Đang tải thông tin đặt phòng...</div>
            </div>
        );
    }

    // Error state
    if (error) {
        return (
            <div className="bg-gray-100 min-h-screen p-4 sm:p-8">
                <div className="max-w-4xl mx-auto">
                    <Result
                        status="error"
                        title="Không thể tải thông tin đặt phòng"
                        subTitle={error}
                        extra={[
                            <Button type="primary" onClick={handleRetry} key="retry">
                                Thử lại
                            </Button>,
                            <Button key="home" onClick={onNewBooking}>
                                Về trang chủ
                            </Button>
                        ]}
                    />
                </div>
            </div>
        );
    }

    // No booking data
    if (!bookingDetails?.data) {
        return (
            <div className="bg-gray-100 min-h-screen p-4 sm:p-8">
                <div className="max-w-4xl mx-auto">
                    <Result
                        status="404"
                        title="Không tìm thấy thông tin đặt phòng"
                        subTitle="Mã đặt phòng không tồn tại hoặc đã bị xóa."
                        extra={[
                            <Button type="primary" onClick={onNewBooking} key="home">
                                Về trang chủ
                            </Button>
                        ]}
                    />
                </div>
            </div>
        );
    }

    const { booking, payment } = bookingDetails.data;
    const nightsCount = calculateNights(booking.check_in_date, booking.check_out_date);

    const getSuccessMessage = () => {
        if (selectedPaymentMethod === 'vietqr' || payment?.payment_method === 'vietqr') {
            return booking.payment_status === 'completed' ? 'Thanh toán thành công!' : 'Đặt phòng thành công!';
        }
        return 'Đặt phòng thành công!';
    };

    const getDescription = () => {
        return `Cảm ơn bạn đã tin tưởng LavishStay. Mã đặt phòng của bạn là: ${booking.booking_code}`;
    };

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'confirmed': return 'success';
            case 'pending': return 'processing';
            case 'cancelled': return 'error';
            case 'completed': return 'success';
            case 'failed': return 'error';
            default: return 'default';
        }
    };

    const getStatusText = (status: string) => {
        switch (status) {
            case 'confirmed': return 'Đã xác nhận';
            case 'pending': return 'Chờ xử lý';
            case 'cancelled': return 'Đã hủy';
            case 'completed': return 'Hoàn thành';
            case 'failed': return 'Thất bại';
            default: return status;
        }
    };

    // Main success view with complete data
    return (
        <>
            {contextHolder}
            <Modal
                title="Nhập mật khẩu để tạo tài khoản quản lý đơn phòng"
                open={showPasswordModal}
                onCancel={() => setShowPasswordModal(false)}
                footer={null}
            >
                <Form onFinish={handlePasswordFinish} layout="vertical">
                    <Form.Item
                        name="password"
                        label="Mật khẩu"
                        rules={[{ required: true, message: 'Vui lòng nhập mật khẩu!' }]}
                    >
                        <Input.Password autoFocus />
                    </Form.Item>
                    <Form.Item
                        name="password_confirmation"
                        label="Nhập lại mật khẩu"
                        rules={[{ required: true, message: 'Vui lòng nhập lại mật khẩu!' }]}
                    >
                        <Input.Password />
                    </Form.Item>
                    {passwordError && (
                        <div style={{ color: 'red', marginBottom: 12 }}>{passwordError}</div>
                    )}
                    <Button type="primary" htmlType="submit" block>
                        Tạo tài khoản & Đăng nhập
                    </Button>
                </Form>
            </Modal>
            <div style={{
                minHeight: '100vh',
                backgroundColor: '#f5f5f5',
                padding: '24px 16px'
            }} className="print:bg-white print:p-4">
                <div style={{ maxWidth: '1200px', margin: '0 auto' }}>
                    {/* Header Section */}
                    <Space direction="vertical" size="large" style={{ width: '100%', textAlign: 'center' }}>
                        <Space direction="vertical" size="middle">
                            <CheckCircleOutlined style={{ fontSize: '64px', color: '#52c41a' }} />
                            <Typography.Title level={2} style={{ margin: 0, color: '#52c41a' }}>
                                {getSuccessMessage()}
                            </Typography.Title>
                            <Typography.Text type="secondary" style={{ fontSize: '16px' }}>
                                {getDescription()}
                            </Typography.Text>
                        </Space>


                        {/* Booking Code Highlight */}
                        <Card
                            style={{
                                background: `linear-gradient(135deg, var(--color-primary) 0%, rgba(21,44,91,0.85) 100%)`,
                                border: 'none',
                                borderRadius: '12px',
                                maxWidth: '500px',
                                margin: '0 auto'
                            }}
                        >
                            <Space direction="vertical" size="small" style={{ width: '100%' }}>
                                <Typography.Text style={{ color: 'white', fontSize: '14px', fontWeight: 500 }}>
                                    MÃ ĐẶT PHÒNG
                                </Typography.Text>
                                <Space size="middle" style={{ width: '100%', justifyContent: 'center' }}>
                                    <Typography.Title
                                        level={1}
                                        style={{
                                            color: 'white',
                                            margin: 0,
                                            fontSize: '36px',
                                            fontWeight: 'bold',
                                            letterSpacing: '2px'
                                        }}
                                    >
                                        {booking.booking_code}
                                    </Typography.Title>
                                    <Tooltip title="Sao chép mã đặt phòng">
                                        <Button
                                            type="primary"
                                            ghost
                                            icon={<CopyOutlined />}
                                            onClick={copyBookingCode}
                                            aria-label="Sao chép mã đặt phòng"
                                            style={{
                                                borderColor: 'white',
                                                color: 'white'
                                            }}
                                            className="hover:bg-white hover:bg-opacity-10"
                                        />
                                    </Tooltip>
                                </Space>
                            </Space>
                        </Card>

                        {/* Action Bar */}
                        <Space
                            wrap
                            size="middle"
                            style={{ justifyContent: 'center' }}
                            className="print:hidden"
                        >
                            <Button
                                icon={<DownloadOutlined />}
                                onClick={downloadInvoice}
                                aria-label="Tải hóa đơn PDF"
                            >
                                Tải hóa đơn
                            </Button>
                            <Button
                                icon={<PrinterOutlined />}
                                onClick={printBooking}
                                aria-label="In thông tin đặt phòng"
                            >
                                In
                            </Button>
                            <Button
                                icon={<MailOutlined />}
                                onClick={sendConfirmationEmail}
                                aria-label="Gửi lại email xác nhận"
                            >
                                Gửi lại email
                            </Button>
                            {booking.payment_status === 'pending' && (payment?.payment_method === 'vietqr' || selectedPaymentMethod === 'vietqr') && (
                                <Button
                                    type="primary"
                                    loading={verifyingPayment}
                                    onClick={verifyPayment}
                                    aria-label="Xác thực thanh toán"
                                >
                                    Đã thanh toán
                                </Button>
                            )}
                            <Button
                                type="primary"
                                icon={<HomeOutlined />}
                                onClick={onNewBooking}
                            >
                                Đặt phòng mới
                            </Button>
                        </Space>
                    </Space>

                    {/* Main Content */}
                    <Row gutter={[24, 24]} style={{ marginTop: '32px' }}>
                        {/* Left Column - Customer Info */}
                        <Col xs={24} md={12}>
                            <Card
                                title={
                                    <Space>
                                        <UserOutlined style={{ color: '#1890ff' }} />
                                        <span>Thông tin khách hàng</span>
                                    </Space>
                                }
                                bordered={false}
                                style={{ height: '100%' }}
                            >
                                <Descriptions column={1} size="middle">
                                    <Descriptions.Item
                                        label={<Space><IdcardOutlined />Họ và tên</Space>}
                                        labelStyle={{ fontWeight: 500 }}
                                    >
                                        <Typography.Text strong>{booking.guest_name}</Typography.Text>
                                    </Descriptions.Item>
                                    <Descriptions.Item
                                        label={<Space><MailOutlined />Email</Space>}
                                        labelStyle={{ fontWeight: 500 }}
                                    >
                                        {booking.guest_email || 'Chưa có thông tin'}
                                    </Descriptions.Item>
                                    <Descriptions.Item
                                        label={<Space><PhoneOutlined />Số điện thoại</Space>}
                                        labelStyle={{ fontWeight: 500 }}
                                    >
                                        {booking.guest_phone || 'Chưa có thông tin'}
                                    </Descriptions.Item>
                                </Descriptions>
                            </Card>
                        </Col>

                        {/* Right Column - Booking Info */}
                        <Col xs={24} md={12}>
                            <Card
                                title={
                                    <Space>
                                        <CalendarOutlined style={{ color: '#1890ff' }} />
                                        <span>Thông tin đặt phòng</span>
                                    </Space>
                                }
                                bordered={false}
                                style={{ height: '100%' }}
                            >
                                <Descriptions column={1} size="middle" bordered>
                                    <Descriptions.Item label="Ngày nhận phòng">
                                        <Typography.Text strong>
                                            {new Date(booking.check_in_date).toLocaleDateString('vi-VN', {
                                                weekday: 'long',
                                                year: 'numeric',
                                                month: 'long',
                                                day: 'numeric'
                                            })}
                                        </Typography.Text>
                                    </Descriptions.Item>
                                    <Descriptions.Item label="Ngày trả phòng">
                                        <Typography.Text strong>
                                            {new Date(booking.check_out_date).toLocaleDateString('vi-VN', {
                                                weekday: 'long',
                                                year: 'numeric',
                                                month: 'long',
                                                day: 'numeric'
                                            })}
                                        </Typography.Text>
                                    </Descriptions.Item>
                                    <Descriptions.Item label="Số đêm">
                                        <Tag color="blue" style={{ fontSize: '14px', padding: '4px 12px' }}>
                                            {nightsCount} đêm
                                        </Tag>
                                    </Descriptions.Item>
                                    <Descriptions.Item label="Trạng thái booking">
                                        <Tag color={getStatusColor(booking.status)} style={{ fontSize: '14px', padding: '4px 12px' }}>
                                            {getStatusText(booking.status)}
                                        </Tag>
                                    </Descriptions.Item>
                                    {/* Payment status removed: booking view is shown only after payment */}
                                    <Descriptions.Item label="Ngày đặt">
                                        {new Date(booking.created_at).toLocaleDateString('vi-VN')}
                                    </Descriptions.Item>
                                </Descriptions>
                            </Card>
                        </Col>
                    </Row>

                    {/* Rooms Section */}
                    <Card
                        title={
                            <Space>
                                <TeamOutlined style={{ color: '#1890ff' }} />
                                <span>Chi tiết phòng đã đặt</span>
                            </Space>
                        }
                        style={{ marginTop: '24px' }}
                        bordered={false}
                    >
                        {bookingDetails.data.rooms && bookingDetails.data.rooms.length > 0 ? (
                            bookingDetails.data.rooms.length > 4 ? (
                                <Collapse
                                    items={[
                                        {
                                            key: '1',
                                            label: `${bookingDetails.data.rooms.length} phòng đã đặt - Click để xem chi tiết`,
                                            children: (
                                                <div>
                                                    <List
                                                        grid={{ gutter: 16, xs: 1, sm: 2, md: 3, lg: 3, xl: 4 }}
                                                        dataSource={bookingDetails.data.rooms}
                                                        renderItem={(room: RoomData) => (
                                                            <List.Item>
                                                                <Card hoverable type="inner">
                                                                    <Card.Meta
                                                                        avatar={<Avatar size={56} style={{ backgroundColor: '#1890ff' }} icon={<HomeOutlined />} />}
                                                                        title={<Typography.Title level={5} style={{ margin: 0 }}>{room.room_name}</Typography.Title>}
                                                                        description={
                                                                            <div>
                                                                                <Typography.Text type="secondary">{room.selected_option_name}</Typography.Text>
                                                                                <div style={{ marginTop: 8 }}>
                                                                                    <Typography.Text>
                                                                                        <TeamOutlined /> {room.adults} người lớn{room.children > 0 && `, ${room.children} trẻ em`}
                                                                                    </Typography.Text>
                                                                                </div>
                                                                                {room.representative_name && (
                                                                                    <div style={{ marginTop: 6 }}>
                                                                                        <Typography.Text type="secondary"><UserOutlined /> {room.representative_name}{room.representative_phone && ` - ${room.representative_phone}`}</Typography.Text>
                                                                                    </div>
                                                                                )}
                                                                                <Divider style={{ margin: '12px 0' }} />
                                                                                <Row justify="space-between" align="middle">
                                                                                    <Col>
                                                                                        <Typography.Text type="secondary">{formatVND(room.selected_option_price)}/đêm</Typography.Text>
                                                                                    </Col>
                                                                                    <Col>
                                                                                        <Statistic value={room.selected_option_price * nightsCount} formatter={(v) => formatVND(Number(v))} valueStyle={{ color: '#1890ff', fontSize: 16 }} />
                                                                                    </Col>
                                                                                </Row>
                                                                            </div>
                                                                        }
                                                                    />
                                                                </Card>
                                                            </List.Item>
                                                        )}
                                                    />
                                                </div>
                                            )
                                        }
                                    ]}
                                />
                            ) : (
                                <List
                                    grid={{ gutter: 16, xs: 1, sm: 2, md: 3, lg: 3, xl: 4 }}
                                    dataSource={bookingDetails.data.rooms}
                                    renderItem={(room: RoomData) => (
                                        <List.Item>
                                            <Card hoverable type="inner">
                                                <Card.Meta
                                                    avatar={<Avatar size={56} style={{ backgroundColor: '#1890ff' }} icon={<HomeOutlined />} />}
                                                    title={<Typography.Title level={5} style={{ margin: 0 }}>{room.room_name}</Typography.Title>}
                                                    description={
                                                        <div>
                                                            <Typography.Text type="secondary">{room.selected_option_name}</Typography.Text>
                                                            <div style={{ marginTop: 8 }}>
                                                                <Typography.Text>
                                                                    <TeamOutlined /> {room.adults} người lớn{room.children > 0 && `, ${room.children} trẻ em`}
                                                                </Typography.Text>
                                                            </div>
                                                            {room.representative_name && (
                                                                <div style={{ marginTop: 6 }}>
                                                                    <Typography.Text type="secondary"><UserOutlined /> {room.representative_name}{room.representative_phone && ` - ${room.representative_phone}`}</Typography.Text>
                                                                </div>
                                                            )}
                                                            <Divider style={{ margin: '12px 0' }} />
                                                            <Row justify="space-between" align="middle">
                                                                <Col>
                                                                    <Typography.Text type="secondary">{formatVND(room.selected_option_price)}/đêm</Typography.Text>
                                                                </Col>
                                                                <Col>
                                                                    <Statistic value={room.selected_option_price * nightsCount} formatter={(v) => formatVND(Number(v))} valueStyle={{ color: '#1890ff', fontSize: 16 }} />
                                                                </Col>
                                                            </Row>
                                                        </div>
                                                    }
                                                />
                                            </Card>
                                        </List.Item>
                                    )}
                                />
                            )
                        ) : (
                            <Card type="inner">
                                <Typography.Text type="secondary">Không có thông tin phòng</Typography.Text>
                            </Card>
                        )}
                    </Card>

                    {/* Payment Summary */}
                    <Card
                        title={
                            <Space>
                                <DollarOutlined style={{ color: '#52c41a' }} />
                                <span>Tổng kết thanh toán</span>
                            </Space>
                        }
                        style={{ marginTop: '24px' }}
                        bordered={false}
                    >
                        <Row gutter={[24, 24]}>
                            <Col xs={24} md={16}>
                                <Descriptions bordered column={1} size="middle">
                                    <Descriptions.Item label="Tổng số phòng">
                                        <Tag color="blue">{bookingDetails.data.summary.total_rooms} phòng</Tag>
                                    </Descriptions.Item>
                                    <Descriptions.Item label="Tổng số khách">
                                        <Tag color="green">{bookingDetails.data.summary.total_guests} người</Tag>
                                    </Descriptions.Item>
                                    <Descriptions.Item label="Phương thức thanh toán">
                                        <Tag color="purple">
                                            {payment?.payment_method === 'vietqr' ? 'VietQR' :
                                                payment?.payment_method === 'vnpay' ? 'VNPay' :
                                                    payment?.payment_method === 'pay_at_hotel' ? 'Thanh toán tại khách sạn' :
                                                        payment?.payment_method || selectedPaymentMethod || 'Chưa xác định'}
                                        </Tag>
                                    </Descriptions.Item>
                                </Descriptions>

                                {payment?.qr_code_url && booking.payment_status === 'pending' && (
                                    <div style={{ marginTop: '16px', textAlign: 'center' }}>
                                        <Typography.Title level={5}>QR Code thanh toán</Typography.Title>
                                        <Image
                                            src={payment.qr_code_url}
                                            alt="QR Code thanh toán"
                                            style={{ maxWidth: '200px' }}
                                            preview={false}
                                        />
                                    </div>
                                )}
                            </Col>
                            <Col xs={24} md={8}>
                                <Card
                                    style={{
                                        background: `linear-gradient(135deg, var(--color-primary) 0%, rgba(21,44,91,0.85) 100%)`,
                                        border: 'none',
                                        textAlign: 'center'
                                    }}
                                >
                                    <Space direction="vertical" size="small">
                                        <Typography.Text style={{ color: 'white', fontSize: '16px' }}>
                                            TỔNG THANH TOÁN
                                        </Typography.Text>
                                        <Statistic
                                            value={booking.total_price_vnd}
                                            formatter={(value) => formatVND(Number(value))}
                                            valueStyle={{
                                                color: 'white',
                                                fontSize: '32px',
                                                fontWeight: 'bold'
                                            }}
                                        />
                                    </Space>
                                </Card>
                            </Col>
                        </Row>
                    </Card>

                    {/* Footer */}
                    <div style={{ textAlign: 'center', marginTop: '32px', paddingBottom: '24px' }}>
                        <Typography.Paragraph style={{ fontSize: '16px', color: '#666' }}>
                            {booking.guest_email ? (
                                <>Chúng tôi đã gửi email xác nhận đến: <Typography.Text strong>{booking.guest_email}</Typography.Text></>
                            ) : (
                                <>Cảm ơn bạn đã tin tưởng và lựa chọn LavishStay</>
                            )}
                        </Typography.Paragraph>
                        <Typography.Text type="secondary">
                            Nếu có bất kỳ câu hỏi nào, xin vui lòng liên hệ với chúng tôi qua hotline hoặc email hỗ trợ.
                        </Typography.Text>


                    </div>
                </div>
            </div>
        </>
    );
};

export default CompletionStep;
