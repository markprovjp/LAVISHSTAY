import React, { useEffect, useState } from 'react';
import { Card, Typography, Button, Result, Descriptions, List, Row, Col, Divider, Spin, Alert, notification, Modal, Form, Input } from 'antd';
import { CheckCircleOutlined, HomeOutlined, RedoOutlined, WarningOutlined } from '@ant-design/icons';
import { useSelector, useDispatch } from 'react-redux';
import { RootState } from '../../store';
import authService from '../../services/authService';
import axiosInstance from '../../config/axios';

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
    const [loading, setLoading] = useState(true);
    const [missingData, setMissingData] = useState<string[]>([]);
    const [backendBookingData, setBackendBookingData] = useState<any>(null);
    const [api, contextHolder] = notification.useNotification();
    const [showPasswordModal, setShowPasswordModal] = useState(false);

    const [passwordError, setPasswordError] = useState<string | null>(null);
    const dispatch = useDispatch();
    const bookingState = useSelector((state: RootState) => state.booking);
    const searchState = useSelector((state: RootState) => state.search);

    // Nếu bạn có action fetchUserBookings và setUser, import ở trên:
    // import { fetchUserBookings } from '../../store/bookingSlice';
    // import { setUser } from '../../store/authSlice';

    // Use fallback data from Redux or backend
    const finalCustomerInfo = customerInfo ||
        (backendBookingData?.booking ? {
            fullName: backendBookingData.booking.guest_name,
            email: backendBookingData.booking.guest_email,
            phone: backendBookingData.booking.guest_phone
        } : null);

    // Check if user is logged in using authService
    const isLoggedIn = authService.isAuthenticated();

    // Show notification and modal after booking success
    useEffect(() => {
        if (!loading && !missingData.length) {
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
    }, [loading, missingData.length, api, isLoggedIn]);
    // Handle password submit for guest (with password confirmation)
    const handlePasswordFinish = async (values: { password: string; password_confirmation: string }) => {
        setPasswordError(null);
        if (values.password !== values.password_confirmation) {
            setPasswordError('Mật khẩu nhập lại không khớp!');
            return;
        }
        try {
            // 1. Đăng ký tài khoản
            const registerData = {
                name: finalCustomerInfo?.fullName,
                email: finalCustomerInfo?.email,
                phone: finalCustomerInfo?.phone,
                password: values.password,
                password_confirmation: values.password_confirmation
            };
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
                bookingCode,
                userId: user.id
            }, {
                headers: { Authorization: `Bearer ${token}` }
            });

            setShowPasswordModal(false);
            // Tự động fetch lại danh sách booking cho user
            if (dispatch && typeof dispatch === 'function') {
                try {
                    // Nếu bạn có action fetchUserBookings
                    // @ts-ignore
                    dispatch(fetchUserBookings());
                } catch (e) { /* ignore */ }
                try {
                    // Nếu bạn có action setUser
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
            console.error('[Đăng ký] Lỗi trả về:', err?.response?.data || err);
            let errorMsg = 'Vui lòng thử lại hoặc liên hệ hỗ trợ.';
            if (err?.response?.data) {
                const data = err.response.data;
                if (data?.errors?.password) {
                    setPasswordError(Array.isArray(data.errors.password) ? data.errors.password.join(' ') : data.errors.password);
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
    useEffect(() => {
        const fetchBookingDetails = async () => {
            if (bookingCode && (!customerInfo || !selectedRoomsSummary)) {
                try {
                    console.log('🔍 Fetching booking details from backend for:', bookingCode);
                    const response = await fetch(`http://localhost:8888/api/payment/booking-with-rooms/${bookingCode}`);

                    if (response.ok) {
                        const result = await response.json();
                        if (result.success) {
                            console.log('📦 Backend booking data:', result.data);
                            setBackendBookingData(result.data);
                        }
                    }
                } catch (error) {
                    console.error('Error fetching booking details:', error);
                }
            }
        };

        fetchBookingDetails();
    }, [bookingCode, customerInfo, selectedRoomsSummary]);

    // ...existing code...

    const finalSelectedRoomsSummary = selectedRoomsSummary ||
        (backendBookingData?.rooms?.map((room: any) => ({
            room: {
                name: room.room_name,
                image_url: '/images/default-room.jpg' // Default image
            },
            option: {
                name: room.option_name || room.selected_option_name
            },
            pricePerNight: room.price_per_night || room.selected_option_price || 0
        })) || (bookingState.selectedRooms ? Object.values(bookingState.selectedRooms) : []));

    const finalSearchData = searchData || searchState ||
        (backendBookingData?.booking ? {
            checkIn: backendBookingData.booking.check_in_date,
            checkOut: backendBookingData.booking.check_out_date
        } : null);

    const finalTotals = totals ||
        (backendBookingData?.booking ? {
            finalTotal: backendBookingData.booking.total_price_vnd
        } : bookingState.totals);

    const finalNights = nights ||
        (backendBookingData?.rooms?.[0]?.nights) || 1;

    useEffect(() => {
        // Check what data is missing
        const missing: string[] = [];

        if (!finalCustomerInfo || !finalCustomerInfo.fullName) {
            missing.push('Thông tin khách hàng');
        }

        if (!finalSelectedRoomsSummary || finalSelectedRoomsSummary.length === 0) {
            missing.push('Thông tin phòng đã đặt');
        }

        if (!finalSearchData || (!finalSearchData.checkIn && !finalSearchData.dateRange)) {
            missing.push('Thông tin ngày nhận/trả phòng');
        }

        if (!finalTotals || typeof finalTotals.finalTotal !== 'number') {
            missing.push('Thông tin thanh toán');
        }

        setMissingData(missing);
        setLoading(false);
    }, [finalCustomerInfo, finalSelectedRoomsSummary, finalSearchData, finalTotals]);

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

    // Get check-in and check-out dates
    const getCheckInDate = () => {
        if (finalSearchData?.checkIn) {
            return new Date(finalSearchData.checkIn).toLocaleDateString('vi-VN');
        }
        if (finalSearchData?.dateRange && Array.isArray(finalSearchData.dateRange) && finalSearchData.dateRange[0]) {
            return new Date(finalSearchData.dateRange[0]).toLocaleDateString('vi-VN');
        }
        return 'Chưa xác định';
    };

    const getCheckOutDate = () => {
        if (finalSearchData?.checkOut) {
            return new Date(finalSearchData.checkOut).toLocaleDateString('vi-VN');
        }
        if (finalSearchData?.dateRange && Array.isArray(finalSearchData.dateRange) && finalSearchData.dateRange[1]) {
            return new Date(finalSearchData.dateRange[1]).toLocaleDateString('vi-VN');
        }
        return 'Chưa xác định';
    };

    if (loading) {
        return (
            <div className="text-center p-8">
                <Spin size="large" />
                <div className="mt-4">Đang tải thông tin đặt phòng...</div>
            </div>
        );
    }

    // If we have missing critical data, show warning but still show success
    if (missingData.length > 0) {
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
                            <Button key="bookings" icon={<RedoOutlined />} onClick={() => window.location.href = '/profile/bookings'}>
                                Xem đặt phòng của tôi
                            </Button>,
                        ]}
                    />

                    <Alert
                        message="Thông báo"
                        description={
                            <div>
                                <p>Đặt phòng của bạn đã được xác nhận thành công!</p>
                                <p>Một số thông tin chi tiết có thể không hiển thị đầy đủ do vấn đề kỹ thuật, nhưng đặt phòng của bạn đã được ghi nhận.</p>
                                <p>Bạn có thể xem chi tiết đầy đủ trong mục "Đặt phòng của tôi" hoặc kiểm tra email xác nhận.</p>
                                {missingData.length > 0 && (
                                    <p className="text-sm text-gray-600 mt-2">
                                        Dữ liệu bị thiếu: {missingData.join(', ')}
                                    </p>
                                )}
                            </div>
                        }
                        type="info"
                        showIcon
                        icon={<WarningOutlined />}
                        className="mt-4"
                    />
                </div>
            </div>
        );
    }

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
                            <Button key="bookings" icon={<RedoOutlined />} onClick={() => window.location.href = '/profile/bookings'}>
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
                                    <Descriptions.Item label="Họ và tên">{finalCustomerInfo?.fullName || 'Chưa có thông tin'}</Descriptions.Item>
                                    <Descriptions.Item label="Email">{finalCustomerInfo?.email || 'Chưa có thông tin'}</Descriptions.Item>
                                    <Descriptions.Item label="Số điện thoại">{finalCustomerInfo?.phone || 'Chưa có thông tin'}</Descriptions.Item>
                                </Descriptions>
                            </Col>
                            <Col xs={24} md={12}>
                                <Descriptions title="Thông tin chung" bordered column={1} size="small">
                                    <Descriptions.Item label="Mã đặt phòng"><Text strong copyable>{bookingCode}</Text></Descriptions.Item>
                                    <Descriptions.Item label="Ngày nhận phòng">{getCheckInDate()}</Descriptions.Item>
                                    <Descriptions.Item label="Ngày trả phòng">{getCheckOutDate()}</Descriptions.Item>
                                    <Descriptions.Item label="Phương thức thanh toán">
                                        {selectedPaymentMethod === 'pay_at_hotel' ? 'Thanh toán tại khách sạn' : 'Đã thanh toán'}
                                    </Descriptions.Item>
                                </Descriptions>
                            </Col>
                        </Row>

                        <Divider />

                        {/* Room Details */}
                        <Title level={4} className="mt-6 mb-4">Chi tiết các phòng đã đặt</Title>

                        {finalSelectedRoomsSummary && finalSelectedRoomsSummary.length > 0 ? (
                            <List
                                grid={{ gutter: 16, xs: 1, sm: 1, md: 1, lg: 1, xl: 1, xxl: 1 }}
                                dataSource={finalSelectedRoomsSummary}
                                renderItem={(roomSummary: any, index: number) => (
                                    <List.Item>
                                        <Card type="inner" title={`Phòng ${index + 1}: ${roomSummary.room?.name || roomSummary.name || 'Phòng không xác định'}`}>
                                            <Row gutter={16}>
                                                <Col span={12}>
                                                    {roomSummary.room?.image_url || roomSummary.image_url ? (
                                                        <img
                                                            src={roomSummary.room?.image_url || roomSummary.image_url}
                                                            alt={roomSummary.room?.name || roomSummary.name}
                                                            className="w-full h-auto rounded-md"
                                                            style={{ maxHeight: '200px', objectFit: 'cover' }}
                                                        />
                                                    ) : (
                                                        <div className="w-full h-32 bg-gray-200 rounded-md flex items-center justify-center">
                                                            Không có hình ảnh
                                                        </div>
                                                    )}
                                                </Col>
                                                <Col span={12}>
                                                    <Descriptions column={1} size="small">
                                                        <Descriptions.Item label="Khách đứng tên">{finalCustomerInfo?.fullName || 'Chưa có thông tin'}</Descriptions.Item>
                                                        <Descriptions.Item label="Gói dịch vụ">{roomSummary.option?.name || roomSummary.packageType || 'Standard'}</Descriptions.Item>
                                                        <Descriptions.Item label="Số đêm">{finalNights}</Descriptions.Item>
                                                        <Descriptions.Item label="Giá mỗi đêm">
                                                            {formatVND(roomSummary.pricePerNight || roomSummary.price || 0)}
                                                        </Descriptions.Item>
                                                    </Descriptions>
                                                </Col>
                                            </Row>
                                        </Card>
                                    </List.Item>
                                )}
                            />
                        ) : (
                            <Card type="inner">
                                <Text type="secondary">Thông tin phòng đã đặt không có sẵn</Text>
                            </Card>
                        )}

                        <Divider />

                        {/* Payment Summary */}
                        <Title level={4} className="mt-6 mb-4">Tổng kết chi phí</Title>
                        <Row justify="end">
                            <Col xs={24} sm={16} md={12}>
                                {finalTotals && typeof finalTotals.finalTotal === 'number' ? (
                                    <Descriptions bordered column={1} size="small">
                                        <Descriptions.Item label={`Tiền phòng (${finalNights} đêm)`}>
                                            {formatVND(finalTotals.roomsTotal || finalTotals.finalTotal)}
                                        </Descriptions.Item>
                                        {finalTotals.serviceFee && (
                                            <Descriptions.Item label="Phí dịch vụ">{formatVND(finalTotals.serviceFee)}</Descriptions.Item>
                                        )}
                                        {finalTotals.taxAmount && (
                                            <Descriptions.Item label="Thuế VAT">{formatVND(finalTotals.taxAmount)}</Descriptions.Item>
                                        )}
                                        <Descriptions.Item label="Tổng cộng">
                                            <Title level={4} style={{ margin: 0, color: '#1890ff' }}>
                                                {formatVND(finalTotals.finalTotal)}
                                            </Title>
                                        </Descriptions.Item>
                                    </Descriptions>
                                ) : (
                                    <Card type="inner">
                                        <Text type="secondary">Thông tin thanh toán không có sẵn</Text>
                                    </Card>
                                )}
                            </Col>
                        </Row>

                        <Paragraph className="text-center mt-8 text-gray-500">
                            {finalCustomerInfo?.email ? (
                                <>Một email xác nhận đã được gửi đến {finalCustomerInfo.email}. Vui lòng kiểm tra hộp thư của bạn.</>
                            ) : (
                                <>Vui lòng lưu lại mã đặt phòng: <Text strong>{bookingCode}</Text></>
                            )}
                            <br />
                            Nếu có bất kỳ câu hỏi nào, xin vui lòng liên hệ với chúng tôi.
                        </Paragraph>
                    </Card>
                </div>
            </div>
        </>
    );
};

export default CompletionStep;
