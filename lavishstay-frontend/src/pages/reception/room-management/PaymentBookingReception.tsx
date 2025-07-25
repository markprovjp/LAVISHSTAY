import React, { useState, useMemo, useEffect, useCallback, useRef } from 'react';
import { Card, Button, Radio, Space, Row, Col, Divider, Alert, Descriptions, Typography, Image, App, Layout, Timeline } from 'antd';
import { QrcodeOutlined, BankOutlined, CheckCircleOutlined, CalendarOutlined, HomeOutlined, UserSwitchOutlined, ClockCircleOutlined, ExclamationCircleOutlined } from '@ant-design/icons';
import { useLocation, useNavigate } from 'react-router-dom';
import dayjs from 'dayjs';
import { useCreateBooking } from '../../../hooks/useReception'; // Updated hook
import { formatCurrency } from '../../../utils/helpers';
import { CreateMultiRoomBookingRequest } from '../../../types/booking';
import { paymentAPI } from '../../../utils/api';

const { Title, Text, Paragraph } = Typography;
const { Content } = Layout;

const generateVietQRUrl = (amount: number, content: string) => {
    if (!amount || !content) return '';
    return `https://img.vietqr.io/image/MB-0335920306-compact2.png?amount=${amount}&addInfo=${encodeURIComponent(content)}`;
};

type CheckHistoryStatus = 'checking' | 'not_found' | 'found' | 'error' | 'stopped';

interface CheckHistoryItem {
    time: string;
    status: CheckHistoryStatus;
    message: string;
}

const PaymentBookingReception: React.FC = () => {
    const location = useLocation();
    const navigate = useNavigate();
    const { message } = App.useApp();
    const createBookingMutation = useCreateBooking(); // Use the new unified hook

    const { bookingDetails: initialBookingDetails } = (location.state || {}) as { bookingDetails?: CreateMultiRoomBookingRequest };

    const [bookingDetails] = useState(initialBookingDetails);
    const [bookingCode, setBookingCode] = useState<string | null>(null);
    const [selectedPaymentMethod, setSelectedPaymentMethod] = useState('vietqr');

    const [isCheckingPayment, setIsCheckingPayment] = useState(false);
    const [checkHistory, setCheckHistory] = useState<CheckHistoryItem[]>([]);
    const [stopCheck, setStopCheck] = useState(false);
    const [isSubmitting, setIsSubmitting] = useState(false);

    const bookingCreationCalled = useRef(false);

    const addToHistory = (status: CheckHistoryStatus, msg: string) => {
        const newEntry: CheckHistoryItem = { time: new Date().toLocaleTimeString('vi-VN'), status, message: msg };
        setCheckHistory(prev => [newEntry, ...prev.slice(0, 4)]);
    };

    const summary = useMemo(() => {
        if (!bookingDetails) return null;
        const { booking_details } = bookingDetails;
        const nights = dayjs(booking_details.check_out_date).diff(dayjs(booking_details.check_in_date), 'day');
        // Booking code will be set after creation, so we might not have it here initially
        const paymentContent = `Thanh toan dat phong ${bookingCode || '...'}`;
        return { ...booking_details, nights, bookingCode, paymentContent };
    }, [bookingDetails, bookingCode]);

    const navigateToSuccess = useCallback((finalBookingCode: string) => {
        message.success({ content: `Thanh toán thành công! Mã đặt phòng: ${finalBookingCode}`, key: 'booking', duration: 4 });
        navigate('/reception/payment-success', { state: { bookingCode: finalBookingCode, bookingDetails: summary } });
    }, [navigate, summary, message]);

    const handleCreateBooking = async (paymentMethod: 'vietqr' | 'at_hotel') => {
        if (!bookingDetails || bookingCreationCalled.current) {
            message.error('Không có thông tin đặt phòng hoặc đang xử lý.');
            return;
        }

        setIsSubmitting(true);
        bookingCreationCalled.current = true;
        message.loading({ content: 'Đang tạo đặt phòng...', key: 'booking' });

        const payload = {
            ...bookingDetails,
            rooms: bookingDetails.roomsWithGuests || bookingDetails.rooms || [],
            payment_method: paymentMethod,
        };

        try {
            const result = await createBookingMutation.mutateAsync(payload);
            const newBookingCode = result.booking_code;
            setBookingCode(newBookingCode);

            if (paymentMethod === 'at_hotel') {
                message.success({ content: `Đặt phòng thành công! Mã: ${newBookingCode}`, key: 'booking', duration: 4 });
                navigate('/reception/payment-success', { state: { bookingCode: newBookingCode, bookingDetails: summary } });
            } else { // For vietqr
                message.info({ content: `Đã tạo booking tạm thời: ${newBookingCode}. Vui lòng thanh toán.`, key: 'booking', duration: 5 });
                // The user can now proceed with QR payment check
            }
        } catch (error: any) {
            const errorMessage = error.response?.data?.message || 'Tạo đặt phòng thất bại.';
            message.error({ content: errorMessage, key: 'booking', duration: 3 });
            bookingCreationCalled.current = false; // Allow retry
        } finally {
            setIsSubmitting(false);
        }
    };

    const handlePaymentCheck = useCallback(async () => {
        if (!bookingCode || !summary?.total_price) {
            message.error("Chưa có mã đặt phòng hoặc tổng tiền, không thể kiểm tra.");
            return;
        }
        setStopCheck(false);
        setIsCheckingPayment(true);
        setCheckHistory([]);
        addToHistory('checking', 'Bắt đầu kiểm tra...');

        const maxAttempts = 12;
        for (let attempt = 1; attempt <= maxAttempts; attempt++) {
            if (stopCheck) {
                addToHistory('stopped', 'Đã dừng kiểm tra.');
                setIsCheckingPayment(false);
                break;
            }
            try {
                addToHistory('checking', `Đang kiểm tra lần ${attempt}/${maxAttempts}...`);
                const response = await paymentAPI.checkCPayPayment(bookingCode, summary.total_price);
                if (response.data.success) {
                    addToHistory('found', 'Thanh toán đã được xác nhận!');
                    setIsCheckingPayment(false);
                    // No need to call confirmBooking here anymore, just navigate
                    navigateToSuccess(bookingCode);
                    return;
                } else {
                    addToHistory('not_found', response.data.message || 'Chưa tìm thấy giao dịch.');
                }
                if (attempt < maxAttempts) await new Promise(resolve => setTimeout(resolve, 5000));
            } catch (error: any) {
                const errorMessage = error.response?.data?.message || 'Lỗi kết nối server.';
                addToHistory('error', errorMessage);
                if (attempt === maxAttempts) message.error("Không thể xác nhận thanh toán tự động.");
                if (attempt < maxAttempts) await new Promise(resolve => setTimeout(resolve, 5000));
            }
        }
        setIsCheckingPayment(false);
    }, [bookingCode, summary, stopCheck, navigateToSuccess, message]);

    useEffect(() => {
        // If VietQR is selected and we don't have a booking code yet, create the booking.
        if (selectedPaymentMethod === 'vietqr' && !bookingCode && !bookingCreationCalled.current) {
            handleCreateBooking('vietqr');
        }
    }, [selectedPaymentMethod, bookingCode]); // Removed handleCreateBooking from deps

    if (!summary || !bookingDetails) {
        return (
            <Content className="p-6 bg-gray-50 min-h-screen flex items-center justify-center">
                <Alert message="Lỗi" description="Không có thông tin đặt phòng." type="error" showIcon action={<Button type="primary" onClick={() => navigate('/reception/room-management-list')}>Quay lại</Button>} />
            </Content>
        );
    }

    const { total_price, check_in_date, check_out_date, nights, adults, children } = summary;
    const paymentContent = `Thanh toan dat phong ${bookingCode}`;

    const getStatusIcon = (status: CheckHistoryStatus) => {
        switch (status) {
            case 'checking': return <ClockCircleOutlined style={{ color: '#1890ff' }} />;
            case 'found': return <CheckCircleOutlined style={{ color: '#52c41a' }} />;
            case 'error': return <ExclamationCircleOutlined style={{ color: '#ff4d4f' }} />;
            case 'stopped': return <ExclamationCircleOutlined style={{ color: '#faad14' }} />;
            default: return <ClockCircleOutlined style={{ color: '#faad14' }} />;
        }
    };

    return (
        <Content className="p-10  min-h-screen">
            <div>
                <Title level={2} className="mb-6 text-center">Hoàn tất đặt phòng</Title>
                <Row gutter={[24, 24]}>
                    <Col xs={24} lg={16}>
                        <Space direction="vertical" size="large" className="w-full">
                            <Card title="1. Thông tin người đại diện">
                                {bookingDetails.representative_info.mode === 'all' ? (
                                    <Descriptions column={2} bordered size="small">
                                        <Descriptions.Item label="Họ tên" span={2}>{bookingDetails.representative_info.details.fullName}</Descriptions.Item>
                                        <Descriptions.Item label="Email">{bookingDetails.representative_info.details.email}</Descriptions.Item>
                                        <Descriptions.Item label="Điện thoại">{bookingDetails.representative_info.details.phoneNumber}</Descriptions.Item>
                                    </Descriptions>
                                ) : (
                                    <Alert message="Thông tin người đại diện cho từng phòng đã được lưu." type="info" showIcon />
                                )}
                            </Card>

                            <Card title="2. Chọn phương thức thanh toán">
                                <Radio.Group value={selectedPaymentMethod} onChange={e => setSelectedPaymentMethod(e.target.value)} style={{ width: '100%' }}>
                                    <Space direction="vertical" style={{ width: '100%' }}>
                                        <Radio value="vietqr" className="w-full">
                                            <Card size="small" hoverable><Space><QrcodeOutlined /> <Text strong>Chuyển khoản VietQR (Tự động)</Text></Space></Card>
                                        </Radio>
                                        <Radio value="at_hotel" className="w-full">
                                            <Card size="small" hoverable><Space><BankOutlined /> <Text strong>Thanh toán tại khách sạn</Text></Space></Card>
                                        </Radio>
                                    </Space>
                                </Radio.Group>
                            </Card>

                            {selectedPaymentMethod === 'vietqr' && (
                                <Card title="3. Thực hiện thanh toán & Kiểm tra">
                                    <Row gutter={[24, 24]} align="top">
                                        <Col xs={24} md={10} className="text-center">
                                            <Image src={generateVietQRUrl(total_price, paymentContent)} alt="VietQR Code" width={250} preview={false} />
                                            <Paragraph type="secondary" style={{ marginTop: 8 }}>Quét mã để thanh toán</Paragraph>
                                        </Col>
                                        <Col xs={24} md={14}>
                                            <Descriptions column={1} bordered size="small" className="mb-4">
                                                <Descriptions.Item label="Số tiền"><Text strong copyable className="text-red-600 text-lg">{formatCurrency(total_price)}</Text></Descriptions.Item>
                                                <Descriptions.Item label="Nội dung"><Text strong copyable>{paymentContent}</Text></Descriptions.Item>
                                            </Descriptions>
                                            <Space direction="vertical" className="w-full">
                                                <Button type="primary" onClick={handlePaymentCheck} loading={isCheckingPayment} disabled={isCheckingPayment || !bookingCode}>
                                                    {isCheckingPayment ? 'Đang kiểm tra...' : 'Kiểm tra thanh toán'}
                                                </Button>
                                                {isCheckingPayment && (
                                                    <Button onClick={() => setStopCheck(true)}>Dừng kiểm tra</Button>
                                                )}
                                                {checkHistory.length > 0 && (
                                                    <Timeline style={{ marginTop: 16 }} items={checkHistory.map((entry, index) => ({
                                                        dot: getStatusIcon(entry.status),
                                                        children: <Text type={index === 0 ? 'success' : 'secondary'}>{entry.time} - {entry.message}</Text>
                                                    }))} />
                                                )}
                                            </Space>
                                        </Col>
                                    </Row>
                                </Card>
                            )}

                            {selectedPaymentMethod === 'at_hotel' && (
                                <Card title="3. Hoàn tất">
                                    <Alert message="Khách hàng sẽ thanh toán tại quầy lễ tân. Nhấn 'Hoàn tất' để xác nhận đặt phòng." type="info" showIcon />
                                    <Button 
                                        type="primary" 
                                        icon={<CheckCircleOutlined />} 
                                        loading={isSubmitting} 
                                        onClick={() => handleCreateBooking('at_hotel')} 
                                        style={{ marginTop: 16 }}
                                    >
                                        Hoàn tất
                                    </Button>
                                </Card>
                            )}
                        </Space>
                    </Col>
                    <Col xs={24} lg={8}>
                        <Card title="Tóm tắt đặt phòng" className="sticky top-6">
                            <Space direction="vertical" className="w-full" size="middle">
                                <Descriptions column={1} bordered size="small">
                                    <Descriptions.Item label="Mã Booking (tạm thời)"><Text strong>{bookingCode || "Đang tạo..."}</Text></Descriptions.Item>
                                    <Descriptions.Item label={<><CalendarOutlined /> Nhận phòng</>}><Text strong>{dayjs(check_in_date).format('DD/MM/YYYY')}</Text></Descriptions.Item>
                                    <Descriptions.Item label={<><CalendarOutlined /> Trả phòng</>}><Text strong>{dayjs(check_out_date).format('DD/MM/YYYY')}</Text></Descriptions.Item>
                                    <Descriptions.Item label={<><HomeOutlined /> Số đêm</>}><Text strong>{nights}</Text></Descriptions.Item>
                                    <Descriptions.Item label={<><UserSwitchOutlined /> Số khách</>}><Text strong>{adults} NL, {children.length} TE</Text></Descriptions.Item>
                                </Descriptions>
                                <Divider className="my-0" />
                                <div className="flex justify-between items-center">
                                    <Title level={4} className="!mb-0">Tổng cộng:</Title>
                                    <Title level={4} className="!mb-0 text-blue-600">{formatCurrency(total_price)}</Title>
                                </div>
                            </Space>
                        </Card>
                    </Col>
                </Row>
            </div>
        </Content>
    );
};

export default PaymentBookingReception;