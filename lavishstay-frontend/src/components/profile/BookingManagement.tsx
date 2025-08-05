

import React, { useEffect, useState, useCallback, useMemo } from 'react';
import {
    Card,
    Row,
    Col,
    Typography,
    Tag,
    Button,
    Space,
    Tabs,
    Divider,
    Modal,
    Timeline,
    Image,
    Empty,
    Input,
    DatePicker,
    Skeleton,
    Select,
    Form,
    message
} from 'antd';
import {
    CalendarOutlined,
    ClockCircleOutlined,
    UserOutlined,
    PhoneOutlined,
    CreditCardOutlined,
    EyeOutlined,
    CheckCircleOutlined,
    ExclamationCircleOutlined,
    CloseCircleOutlined,
    DownloadOutlined,
    TeamOutlined,
    BankOutlined,
    GiftOutlined,
    SafetyOutlined,
    MailOutlined,
    IdcardOutlined,
    CopyOutlined
} from '@ant-design/icons';
import dayjs from 'dayjs';
import bookingService from '../../services/bookingService';
import { getAmenityIcon, getCategoryColor } from '../../constants/Icons';
import { Carousel } from 'antd';

const { Title, Text } = Typography;
const { Search } = Input;
const { RangePicker } = DatePicker;

interface Booking {
    booking_id: number;
    booking_code: string;
    check_in_date: string;
    check_out_date: string;
    status: string;
    total_price_vnd: number;
    created_at: string;
    room_type: string;
    room_name: string;
    room_image?: string;
    room_type_images?: Array<{
        image_id: number;
        image_path: string;
        alt_text: string;
        is_main: number;
    }>;
    payment_amount?: number;
    payment_status?: string;
    guest_name?: string;
    guest_email?: string;
    guest_phone?: string;
    representative_name?: string;
    representative_phone?: string;
    representative_email?: string;
    representative_id_card?: string;
    room_type_amenities?: Array<{
        name: string;
        icon: string;
        category: string;
        is_highlighted: number;
    }>;
    room_id?: number; // Thêm room_id lấy từ backend
    room?: { room_id?: number; room_name?: string; room_type?: string; }; // fallback nếu backend trả về object
}

const BookingManagement: React.FC = () => {
    // State cho modal rời lịch mới (phải đặt trong function component)
    const [rescheduleModalVisible, setRescheduleModalVisible] = useState(false);
    const [rescheduleBooking, setRescheduleBooking] = useState<any>(null);
    const [rescheduleRooms, setRescheduleRooms] = useState<any[]>([]);
    const [rescheduleForm] = Form.useForm();
    // State cho rời lịch (bổ sung nếu thiếu)
    const [reschedulePolicy, setReschedulePolicy] = useState<any | null>(null);
    const [rescheduleLoading, setRescheduleLoading] = useState(false);
    const [rescheduleBookingId, setRescheduleBookingId] = useState<number | string | null>(null);
    const [rescheduleConfirming, setRescheduleConfirming] = useState(false);
    const [rescheduleCheckIn, setRescheduleCheckIn] = useState<dayjs.Dayjs | null>(null);
    const [rescheduleCheckOut, setRescheduleCheckOut] = useState<dayjs.Dayjs | null>(null);
    const [rescheduleReason, setRescheduleReason] = useState<string>('');
    const [activeTab, setActiveTab] = useState('all');
    const [selectedBooking, setSelectedBooking] = useState<Booking | null>(null);
    const [isDetailModalVisible, setIsDetailModalVisible] = useState(false);
    const [searchText, setSearchText] = useState('');
    const [dateRange, setDateRange] = useState<[dayjs.Dayjs | null, dayjs.Dayjs | null] | null>(null);
    const [bookings, setBookings] = useState<Booking[]>([]);
    const [loading, setLoading] = useState(false);
    // State cho modal chính sách huỷ
    const [cancelPolicy, setCancelPolicy] = useState<any | null>(null);
    const [cancelLoading, setCancelLoading] = useState(false);
    const [cancelBookingId, setCancelBookingId] = useState<number | string | null>(null);
    const [cancelConfirming, setCancelConfirming] = useState(false);

    // State cho gia hạn
    const [extendPolicy, setExtendPolicy] = useState<any | null>(null);
    const [extendLoading, setExtendLoading] = useState(false);
    const [extendBookingId, setExtendBookingId] = useState<number | string | null>(null);
    const [extendConfirming, setExtendConfirming] = useState(false);
    const [extendDate, setExtendDate] = useState<dayjs.Dayjs | null>(null);

    // Handler for changing extend date and fetching new policy
    const handleExtendDateChange = useCallback(async (date: dayjs.Dayjs | null) => {
        setExtendDate(date);
        if (!date || !extendBookingId) return;
        setExtendLoading(true);
        try {
            const policy = await bookingService.getExtendPolicy(extendBookingId, date.format('YYYY-MM-DD'));
            setExtendPolicy(policy);
        } catch (err: any) {
            Modal.error({
                title: 'Không thể lấy chính sách gia hạn',
                content: err?.message || 'Đã có lỗi xảy ra',
            });
        } finally {
            setExtendLoading(false);
        }
    }, [extendBookingId]);

    // State cho toàn bộ phòng
    const [allRooms, setAllRooms] = useState<any[]>([]);
    useEffect(() => {
        setLoading(true);
        bookingService.getUserBookings()
            .then((data) => {
                // Lọc trùng booking_code, chỉ lấy booking đầu tiên cho mỗi booking_code
                const uniqueBookings: Booking[] = [];
                const seenCodes = new Set();
                for (const b of data.bookings) {
                    if (!seenCodes.has(b.booking_code)) {
                        uniqueBookings.push(b);
                        seenCodes.add(b.booking_code);
                    }
                }
                setBookings(uniqueBookings);
                setAllRooms(data.all_rooms || []);
            })
            .catch(() => {
                setBookings([]);
                setAllRooms([]);
            })
            .finally(() => setLoading(false));
    }, []);

    // Helper cho status
    const getStatusColor = useCallback((status: string) => {
        switch (status) {
            case 'Confirmed':
            case 'confirmed': return 'blue';
            case 'Completed':
            case 'completed': return 'green';
            case 'Cancelled':
            case 'cancelled': return 'red';
            case 'Pending':
            case 'pending': return 'orange';
            default: return 'default';
        }
    }, []);

    const getStatusText = useCallback((status: string) => {
        switch (status) {
            case 'Confirmed':
            case 'confirmed': return 'Đã xác nhận chuyển tiền';
            case 'Completed':
            case 'completed': return 'Hoàn thành';
            case 'Cancelled':
            case 'cancelled': return 'Đã hủy';
            case 'Pending':
            case 'pending': return 'Chờ xử lý';
            default: return status;
        }
    }, []);

    const getStatusIcon = useCallback((status: string) => {
        switch (status) {
            case 'Confirmed':
            case 'confirmed': return <CheckCircleOutlined />;
            case 'Completed':
            case 'completed': return <CheckCircleOutlined />;
            case 'Cancelled':
            case 'cancelled': return <CloseCircleOutlined />;
            case 'Pending':
            case 'pending': return <ExclamationCircleOutlined />;
            default: return <ClockCircleOutlined />;
        }
    }, []);

    const tabItems = [
        { key: 'all', label: `Tất cả (${bookings.length})` },
        { key: 'confirmed', label: `Đã xác nhận (${bookings.filter(b => b.status?.toLowerCase() === 'confirmed').length})` },
        { key: 'completed', label: `Hoàn thành (${bookings.filter(b => b.status?.toLowerCase() === 'completed').length})` },
        { key: 'cancelled', label: `Đã hủy (${bookings.filter(b => b.status?.toLowerCase() === 'cancelled').length})` },
        { key: 'pending', label: `Chờ xử lý (${bookings.filter(b => b.status?.toLowerCase() === 'pending').length})` }
    ];

    const filteredBookings = useMemo(() => {
        let filtered = bookings;
        if (activeTab !== 'all') {
            filtered = filtered.filter(booking => booking.status?.toLowerCase() === activeTab);
        }
        if (searchText) {
            filtered = filtered.filter(booking =>
                booking.room_name?.toLowerCase().includes(searchText.toLowerCase()) ||
                booking.room_type?.toLowerCase().includes(searchText.toLowerCase()) ||
                booking.booking_code?.toLowerCase().includes(searchText.toLowerCase())
            );
        }
        if (dateRange && dateRange[0] && dateRange[1]) {
            filtered = filtered.filter(booking => {
                const bookingDate = dayjs(booking.created_at);
                return bookingDate.isAfter(dayjs(dateRange[0])) && bookingDate.isBefore(dayjs(dateRange[1]));
            });
        }
        return filtered;
    }, [bookings, activeTab, searchText, dateRange]);
    console.log(filteredBookings);
    const showBookingDetail = useCallback((booking: Booking) => {
        setSelectedBooking(booking);
        setIsDetailModalVisible(true);
    }, []);


    const handleShowCancelPolicy = useCallback(async (booking: Booking) => {
        setCancelLoading(true);
        setCancelPolicy(null);
        setCancelBookingId(booking.booking_id);

        setIsDetailModalVisible(false); // Ẩn modal chi tiết nếu đang mở

        try {
            const policy = await bookingService.getCancelPolicy(booking.booking_id);
            setCancelPolicy(policy);
        } catch (err: any) {
            Modal.error({
                title: 'Không thể lấy chính sách huỷ',
                content: err?.message || 'Đã có lỗi xảy ra',
            });
        } finally {
            setCancelLoading(false);
        }
    }, []);

    // Gia hạn: lấy chính sách
    const handleShowExtendPolicy = useCallback(async (booking: Booking) => {
        setExtendLoading(true);
        setExtendPolicy(null);
        setExtendBookingId(booking.booking_id);
        setExtendDate(dayjs(booking.check_out_date).add(1, 'day'));
        setIsDetailModalVisible(false);
        try {
            // Mặc định đề xuất ngày trả phòng mới +1 ngày
            const newDate = dayjs(booking.check_out_date).add(1, 'day');
            const policy = await bookingService.getExtendPolicy(booking.booking_id, newDate.format('YYYY-MM-DD'));
            setExtendPolicy(policy);
            setExtendDate(newDate);
        } catch (err: any) {
            Modal.error({
                title: 'Không thể lấy chính sách gia hạn',
                content: err?.message || 'Đã có lỗi xảy ra',
            });
        } finally {
            setExtendLoading(false);
        }
    }, []);

    // Xác nhận gia hạn
    const handleConfirmExtend = useCallback(async () => {
        if (!extendBookingId || !extendDate) return;
        setExtendConfirming(true);
        try {
            await bookingService.confirmExtendBooking(extendBookingId, extendDate.format('YYYY-MM-DD'));
            Modal.success({
                title: 'Gia hạn thành công',
                content: 'Đặt phòng đã được gia hạn. Vui lòng kiểm tra lại danh sách đặt phòng.',
            });
            // Reload lại danh sách booking
            setBookings(prev => prev.map(b => b.booking_id === extendBookingId ? { ...b, check_out_date: extendDate.format('YYYY-MM-DD') } : b));
            setExtendPolicy(null);
            setExtendBookingId(null);
        } catch (err: any) {
            Modal.error({
                title: 'Gia hạn thất bại',
                content: err?.message || 'Đã có lỗi xảy ra',
            });
        } finally {
            setExtendConfirming(false);
        }
    }, [extendBookingId, extendDate]);


    const handleConfirmCancel = useCallback(async () => {
        if (!cancelBookingId) return;
        setCancelConfirming(true);
        try {
            await bookingService.confirmCancelBooking(cancelBookingId);
            Modal.success({
                title: 'Huỷ đặt phòng thành công',
                content: 'Đặt phòng đã được huỷ. Vui lòng kiểm tra lại danh sách đặt phòng.',
            });
            // Reload lại danh sách booking
            setBookings(prev => prev.filter(b => b.booking_id !== cancelBookingId));
            setCancelPolicy(null);
            setCancelBookingId(null);
        } catch (err: any) {
            Modal.error({
                title: 'Huỷ đặt phòng thất bại',
                content: err?.message || 'Đã có lỗi xảy ra',
            });
        } finally {
            setCancelConfirming(false);
        }
    }, [cancelBookingId]);

    // Khi đổi ngày hoặc lý do rời lịch
    useEffect(() => {
        if (rescheduleBookingId && rescheduleCheckIn && rescheduleCheckOut) {
            fetchReschedulePolicy(rescheduleBookingId, rescheduleCheckIn, rescheduleCheckOut, rescheduleReason);
        }
        // eslint-disable-next-line
    }, [rescheduleBookingId, rescheduleCheckIn, rescheduleCheckOut, rescheduleReason]);

    // Handler: Lấy chính sách rời lịch
    const fetchReschedulePolicy = useCallback(async (
        bookingId: number | string,
        checkIn: dayjs.Dayjs,
        checkOut: dayjs.Dayjs,
        reason: string
    ) => {
        setRescheduleLoading(true);
        try {
            // Lấy booking hiện tại để lấy room_id
            const booking = bookings.find(b => b.booking_id === bookingId);
            const roomId = booking?.room_id || booking?.room?.room_id || null;
            if (!roomId) {
                throw new Error('Không xác định được phòng hiện tại để rời lịch');
            }
            const policy = await bookingService.getReschedulePolicy(
                bookingId,
                checkIn.format('YYYY-MM-DD'),
                checkOut.format('YYYY-MM-DD'),
                [roomId],
                reason
            );
            setReschedulePolicy(policy);
        } catch (err: any) {
            Modal.error({
                title: 'Không thể lấy chính sách rời lịch',
                content: err?.message || 'Đã có lỗi xảy ra',
            });
            setReschedulePolicy(null);
        } finally {
            setRescheduleLoading(false);
        }
    }, [bookings]);

    // Handler: Hiển thị modal rời lịch
    const handleShowReschedulePolicy = useCallback(async (booking: Booking) => {
        setRescheduleLoading(true);
        setReschedulePolicy(null);
        setRescheduleBookingId(booking.booking_id);
        setRescheduleCheckIn(dayjs(booking.check_in_date));
        setRescheduleCheckOut(dayjs(booking.check_out_date));
        setRescheduleReason('');
        setIsDetailModalVisible(false);
        try {
            const roomId = booking.room_id || booking?.room?.room_id || null;
            if (!roomId) {
                throw new Error('Không xác định được phòng hiện tại để rời lịch');
            }
            const policy = await bookingService.getReschedulePolicy(
                booking.booking_id,
                dayjs(booking.check_in_date).format('YYYY-MM-DD'),
                dayjs(booking.check_out_date).format('YYYY-MM-DD'),
                [roomId],
                ''
            );
            setReschedulePolicy(policy);
        } catch (err: any) {
            Modal.error({
                title: 'Không thể lấy chính sách rời lịch',
                content: err?.message || 'Đã có lỗi xảy ra',
            });
        } finally {
            setRescheduleLoading(false);
        }
    }, []);

    // Xác nhận rời lịch
    const handleConfirmReschedule = useCallback(async () => {
        if (!rescheduleBookingId || !rescheduleCheckIn || !rescheduleCheckOut) return;
        setRescheduleConfirming(true);
        try {
            await bookingService.confirmRescheduleBooking(
                rescheduleBookingId,
                rescheduleCheckIn.format('YYYY-MM-DD'),
                rescheduleCheckOut.format('YYYY-MM-DD'),
                [],
                rescheduleReason
            );
            Modal.success({
                title: 'Rời lịch thành công',
                content: 'Đặt phòng đã được rời lịch. Vui lòng kiểm tra lại danh sách đặt phòng.',
            });
            setBookings(prev => prev.map(b => b.booking_id === rescheduleBookingId ? { ...b, check_in_date: rescheduleCheckIn.format('YYYY-MM-DD'), check_out_date: rescheduleCheckOut.format('YYYY-MM-DD') } : b));
            setReschedulePolicy(null);
            setRescheduleBookingId(null);
        } catch (err: any) {
            Modal.error({
                title: 'Rời lịch thất bại',
                content: err?.message || 'Đã có lỗi xảy ra',
            });
        } finally {
            setRescheduleConfirming(false);
        }
    }, [rescheduleBookingId, rescheduleCheckIn, rescheduleCheckOut, rescheduleReason]);
    return (
        <div style={{ padding: 0, minHeight: '100vh' }}>
            {/* Header */}
            <div style={{ marginBottom: 32 }}>
                <Row align="middle" justify="space-between">
                    <Col>
                        <Title level={2} style={{ margin: 0, color: '#222', fontWeight: 600, letterSpacing: 0 }}>
                            <CalendarOutlined style={{ marginRight: 10, color: '#1890ff', fontSize: 26 }} />
                            Quản lý đặt phòng
                        </Title>
                        <Text style={{ fontSize: 15, color: '#888', marginTop: 6, display: 'block' }}>
                            Theo dõi và quản lý các đặt phòng của bạn tại LavishStay Hotel
                        </Text>
                    </Col>
                    <Col>
                        <Button type="primary" icon={<DownloadOutlined />} size="large" style={{ borderRadius: 4, height: 40, border: 'none', fontWeight: 500, boxShadow: '0 1px 4px rgba(0,0,0,0.04)' }}>
                            Xuất báo cáo
                        </Button>
                    </Col>
                </Row>
            </div>

            {/* Filters */}
            <Card bordered={false} style={{ borderRadius: 6, marginBottom: 24, boxShadow: '0 1px 4px rgba(0,0,0,0.04)', }}>
                <Row gutter={[16, 16]} align="middle">
                    <Col xs={24} sm={12} md={8}>
                        <Search placeholder="Tìm theo tên phòng, mã đặt phòng, loại phòng..." value={searchText} onChange={(e) => setSearchText(e.target.value)} size="large" style={{ borderRadius: '8px' }} />
                    </Col>
                    <Col xs={24} sm={12} md={8}>
                        <RangePicker placeholder={['Từ ngày', 'Đến ngày']} value={dateRange} onChange={setDateRange} size="large" style={{ width: '100%', borderRadius: '8px' }} />
                    </Col>
                    <Col xs={24} md={8}>
                        <div style={{ padding: '12px 16px', borderRadius: '8px', textAlign: 'center' }}>
                            <Text strong>
                                <TeamOutlined style={{ marginRight: '8px' }} />
                                Tổng: {filteredBookings.length} đặt phòng
                            </Text>
                        </div>
                    </Col>
                </Row>
            </Card>

            {/* Booking List */}
            <Card bordered={false} style={{ borderRadius: 6, boxShadow: '0 1px 4px rgba(0,0,0,0.04)', }}>
                <Tabs
                    activeKey={activeTab}
                    onChange={setActiveTab}
                    style={{ marginBottom: '24px' }}
                    items={tabItems}
                />

                <div>
                    {loading ? (
                        <Row gutter={[0, 16]}>
                            {[...Array(3)].map((_, idx) => (
                                <Col xs={24} key={idx}>
                                    <Card style={{ borderRadius: '12px', border: '1px solid #f0f0f0', marginBottom: '16px' }}>
                                        <Skeleton avatar paragraph={{ rows: 2 }} active />
                                    </Card>
                                </Col>
                            ))}
                        </Row>
                    ) : filteredBookings.length > 0 ? (
                        <Row gutter={[0, 16]}>
                            {filteredBookings.map((booking) => (
                                <Col xs={24} key={booking.booking_id}>
                                    <Card
                                        bordered={false}
                                        style={{
                                            borderRadius: '12px',
                                            border: '1px solid #f0f0f0',
                                            overflow: 'hidden',
                                            transition: 'all 0.3s ease',
                                            cursor: 'pointer',
                                            marginBottom: '8px'
                                        }}
                                        hoverable
                                    >
                                        <Row gutter={[16, 16]} align="middle" style={{ minHeight: 140 }}>
                                            {/* Hotel Image */}
                                            <Col xs={24} sm={6} md={4}>

                                                <Carousel dots={true} style={{ width: '100%' }}>
                                                    {booking.room_type_images && booking.room_type_images.length > 0 ? (
                                                        booking.room_type_images.map((img, idx) => (
                                                            <Image key={idx} src={img.image_path} alt={img.alt_text} style={{ width: '100%', height: 120, objectFit: 'cover', borderRadius: '8px' }} preview={false} />
                                                        ))
                                                    ) : booking.room_image ? (
                                                        <Image src={booking.room_image} alt={booking.room_name} style={{ width: '100%', height: 120, objectFit: 'cover', borderRadius: '8px' }} preview={false} />
                                                    ) : (
                                                        <div style={{ width: '100%', height: 120, borderRadius: '8px', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#bbb' }}>
                                                            Không có ảnh
                                                        </div>
                                                    )}
                                                </Carousel>
                                            </Col>
                                            {/* Booking Info */}
                                            <Col xs={24} sm={12} md={14}>
                                                <div>
                                                    <Title level={4} style={{ margin: 0, color: '#222', fontSize: 17, fontWeight: 600 }}>{booking.room_name || 'Chưa gán phòng'}</Title>
                                                    <Text style={{ color: '#1890ff', fontWeight: 500, fontSize: 14 }}>{booking.room_type || 'Loại phòng chưa xác định'}</Text>
                                                    <div style={{ marginTop: 8, display: 'flex', alignItems: 'center', gap: 12 }}>
                                                        <CalendarOutlined style={{ color: '#1890ff', marginRight: 6, fontSize: 15 }} />
                                                        <Text style={{ fontSize: 14 }}>{dayjs(booking.check_in_date).format('DD/MM/YYYY')} - {dayjs(booking.check_out_date).format('DD/MM/YYYY')}</Text>
                                                    </div>

                                                    <div style={{ marginTop: 10, padding: 0 }}>
                                                        <Text style={{ fontSize: 13, color: '#555', fontWeight: 500 }}>Khách & Đại diện:</Text>
                                                        <div style={{ marginTop: 2, fontSize: 13, color: '#888', lineHeight: 1.7 }}>
                                                            <span><UserOutlined style={{ marginRight: 4, fontSize: 13 }} />{booking.guest_name || booking.representative_name}</span>
                                                            {booking.guest_phone && <span style={{ marginLeft: 16 }}><PhoneOutlined style={{ marginRight: 4, fontSize: 13 }} />{booking.guest_phone}</span>}
                                                            {booking.guest_email && <span style={{ marginLeft: 16 }}><MailOutlined style={{ marginRight: 4, fontSize: 13 }} />{booking.guest_email}</span>}
                                                            {booking.representative_id_card && <span style={{ marginLeft: 16 }}><IdcardOutlined style={{ marginRight: 4, fontSize: 13 }} />{booking.representative_id_card}</span>}
                                                        </div>
                                                    </div>
                                                    <div style={{ marginTop: 8 }}>
                                                        <Text style={{ fontSize: 13, color: '#888' }}>Thanh toán: <b style={{ color: '#222' }}>{getStatusText(booking.payment_status || '')}</b> | Số tiền: <b style={{ color: '#222' }}>{Number(booking.total_price_vnd).toLocaleString('vi-VN', { style: 'currency', currency: 'VND', maximumFractionDigits: 0 })}</b></Text>
                                                    </div>
                                                </div>
                                            </Col>
                                            {/* Actions & Status */}
                                            <Col xs={24} sm={6} md={6}>
                                                <div style={{ textAlign: 'right', display: 'flex', flexDirection: 'column', height: '100%', justifyContent: 'space-between' }}>
                                                    <div>
                                                        <Text style={{ fontSize: 13, color: '#888' }}>Ngày đặt: {dayjs(booking.created_at).format('DD/MM/YYYY HH:mm')}</Text>
                                                        <Title level={3} style={{ margin: '4px 0 8px 0', color: '#222', fontSize: 20, fontWeight: 700 }}>{Number(booking.total_price_vnd).toLocaleString('vi-VN', { style: 'currency', currency: 'VND', maximumFractionDigits: 0 })}</Title>
                                                    </div>
                                                    <Tag color={getStatusColor(booking.status)} icon={getStatusIcon(booking.status)} style={{ fontSize: 13, padding: '4px 12px', borderRadius: 4, border: 'none', fontWeight: 500 }}>{getStatusText(booking.status)}</Tag>
                                                    <div style={{ marginTop: 8 }}>
                                                        <Space direction="vertical" size={8} style={{ width: '100%' }}>
                                                            <Space size={8}>
                                                                <Button
                                                                    icon={<EyeOutlined />}
                                                                    size="small"
                                                                    style={{ borderRadius: 4, fontWeight: 500 }}
                                                                    onClick={e => { e.stopPropagation(); showBookingDetail(booking); }}
                                                                >
                                                                    Xem chi tiết
                                                                </Button>
                                                                <Button
                                                                    icon={<CopyOutlined />}
                                                                    size="small"
                                                                    style={{ borderRadius: 4, fontWeight: 500 }}
                                                                    onClick={e => {
                                                                        e.stopPropagation();
                                                                        navigator.clipboard.writeText(booking.booking_code);
                                                                    }}
                                                                >
                                                                    Sao chép mã
                                                                </Button>
                                                            </Space>
                                                            <Space size={8}>
                                                                {/* Nút Huỷ phòng: chỉ hiển thị khi booking.status là Confirmed và chưa check-in */}
                                                                {booking.status?.toLowerCase() === 'confirmed' && dayjs(booking.check_in_date).isAfter(dayjs()) && (
                                                                    <Button
                                                                        danger
                                                                        size="small"
                                                                        loading={cancelLoading && cancelBookingId === booking.booking_id}
                                                                        style={{ borderRadius: 4, fontWeight: 500 }}
                                                                        onClick={e => { e.stopPropagation(); handleShowCancelPolicy(booking); }}
                                                                    >
                                                                        Huỷ phòng
                                                                    </Button>
                                                                )}
                                                                {/* Nút Gia hạn: chỉ hiển thị khi booking.status là Confirmed và chưa check-out */}
                                                                {booking.status?.toLowerCase() === 'confirmed' && dayjs(booking.check_out_date).isAfter(dayjs()) && (
                                                                    <Button
                                                                        type="primary"
                                                                        size="small"
                                                                        loading={extendLoading && extendBookingId === booking.booking_id}
                                                                        style={{ borderRadius: 4, fontWeight: 500 }}
                                                                        onClick={e => { e.stopPropagation(); handleShowExtendPolicy(booking); }}
                                                                    >
                                                                        Gia hạn
                                                                    </Button>
                                                                )}
                                                                {/* Nút Rời lịch: chỉ hiển thị khi booking.status là Confirmed và chưa check-in */}
                                                                {booking.status?.toLowerCase() === 'confirmed' && dayjs(booking.check_in_date).isAfter(dayjs()) && (
                                                                    <Button
                                                                        type="default"
                                                                        size="small"
                                                                        style={{ borderRadius: 4, fontWeight: 500, color: '#722ed1', borderColor: '#b37feb' }}
                                                                        icon={<ClockCircleOutlined style={{ color: '#722ed1' }} />}
                                                                        onClick={e => {
                                                                            e.stopPropagation();
                                                                            // Xác định số lượng phòng đã đặt trong booking này
                                                                            // Nếu booking chỉ có 1 phòng (room_id), chỉ cho chọn 1 phòng mới
                                                                            const bookedRoomIds = booking.room_id ? [booking.room_id] : [];
                                                                            // Lấy danh sách phòng mới (loại bỏ các phòng đã đặt, nếu cần)
                                                                            const rooms = allRooms.filter(r => !bookedRoomIds.includes(r.room_id)).map(r => ({ room_id: r.room_id, room_name: r.name }));
                                                                            if (rooms.length < bookedRoomIds.length) {
                                                                                message.error('Không đủ phòng để dời lịch!');
                                                                                return;
                                                                            }
                                                                            setRescheduleRooms(rooms);
                                                                            setRescheduleModalVisible(true);
                                                                            setRescheduleBooking(booking);
                                                                            setReschedulePolicy(null);
                                                                            // Reset form, nhưng chỉ chọn sẵn đúng số lượng phòng đã đặt
                                                                            rescheduleForm && rescheduleForm.setFieldsValue({
                                                                                new_check_in_date: dayjs(booking.check_in_date),
                                                                                new_check_out_date: dayjs(booking.check_out_date),
                                                                                new_room_id: [], // Không chọn sẵn phòng nào
                                                                                reason: ''
                                                                            });
                                                                        }}
                                                                    >
                                                                        Rời lịch
                                                                    </Button>
                                                                )}
                                                            </Space>
                                                            {/* Modal nhập thông tin rời lịch và xem chính sách */}
                                                            <Modal
                                                                open={rescheduleModalVisible}
                                                                onCancel={() => { setRescheduleModalVisible(false); setReschedulePolicy(null); }}
                                                                title={<div style={{ display: 'flex', alignItems: 'center', fontWeight: 600 }}><ClockCircleOutlined style={{ color: '#722ed1', marginRight: 8 }} />Dời lịch đặt phòng</div>}
                                                                footer={null}
                                                                width={600}
                                                                destroyOnClose
                                                                maskClosable={false}
                                                            >
                                                                {rescheduleBooking && (
                                                                    <Form
                                                                        form={rescheduleForm}
                                                                        layout="vertical"
                                                                        initialValues={{
                                                                            new_check_in_date: dayjs(rescheduleBooking.check_in_date),
                                                                            new_check_out_date: dayjs(rescheduleBooking.check_out_date),
                                                                            new_room_id: rescheduleRooms.map(r => r.room_id),
                                                                            reason: ''
                                                                        }}
                                                                    >
                                                                        <Form.Item label="Ngày check-in mới" name="new_check_in_date" rules={[{ required: true, message: 'Chọn ngày check-in mới' }]}>
                                                                            <DatePicker style={{ width: '100%' }} format="YYYY-MM-DD" />
                                                                        </Form.Item>
                                                                        <Form.Item label="Ngày check-out mới" name="new_check_out_date" rules={[{ required: true, message: 'Chọn ngày check-out mới' }]}>
                                                                            <DatePicker style={{ width: '100%' }} format="YYYY-MM-DD" />
                                                                        </Form.Item>
                                                                        <Form.Item label="Chọn phòng mới" name="new_room_id" rules={[{ required: true, message: 'Chọn phòng mới' }]}>
                                                                            <Select mode="multiple" placeholder="Chọn phòng" style={{ width: '100%' }}>
                                                                                {rescheduleRooms.map(room => (
                                                                                    <Select.Option key={room.room_id} value={room.room_id}>{room.room_name}</Select.Option>
                                                                                ))}
                                                                            </Select>
                                                                        </Form.Item>
                                                                        <Form.Item label="Lý do dời lịch" name="reason" rules={[{ required: true, message: 'Nhập lý do' }]}>
                                                                            <Input.TextArea rows={2} placeholder="Nhập lý do dời lịch" />
                                                                        </Form.Item>
                                                                        <Form.Item>
                                                                            <Button
                                                                                type="primary"
                                                                                loading={rescheduleLoading}
                                                                                onClick={async () => {
                                                                                    try {
                                                                                        const values = await rescheduleForm.validateFields();
                                                                                        setRescheduleLoading(true);
                                                                                        // Validate new_room_id là mảng số hợp lệ
                                                                                        const validRoomIds = Array.isArray(values.new_room_id) ? values.new_room_id.filter((id: any) => typeof id === 'number' && id) : [];
                                                                                        if (!validRoomIds.length) {
                                                                                            message.error('Vui lòng chọn phòng mới hợp lệ!');
                                                                                            return;
                                                                                        }
                                                                                        const policy = await bookingService.getReschedulePolicy(
                                                                                            rescheduleBooking.booking_id,
                                                                                            dayjs(values.new_check_in_date).format('YYYY-MM-DD'),
                                                                                            dayjs(values.new_check_out_date).format('YYYY-MM-DD'),
                                                                                            validRoomIds,
                                                                                            values.reason
                                                                                        );
                                                                                        // Kiểm tra phòng không khả dụng
                                                                                        if (policy.unavailable_room_ids && Array.isArray(policy.unavailable_room_ids) && policy.unavailable_room_ids.length > 0) {
                                                                                            const unavailableNames = rescheduleRooms.filter(r => policy.unavailable_room_ids.includes(r.room_id)).map(r => r.room_name).join(', ');
                                                                                            message.error(`Phòng sau không còn trống: ${unavailableNames}`);
                                                                                            setReschedulePolicy(null);
                                                                                            return;
                                                                                        }
                                                                                        setReschedulePolicy(policy);
                                                                                    } catch (err: any) {
                                                                                        if (err?.message) message.error(err.message);
                                                                                    } finally {
                                                                                        setRescheduleLoading(false);
                                                                                    }
                                                                                }}
                                                                                style={{ marginRight: 8 }}
                                                                            >
                                                                                Xem chính sách
                                                                            </Button>
                                                                            <Button
                                                                                type="primary"
                                                                                danger
                                                                                loading={rescheduleConfirming}
                                                                                onClick={async () => {
                                                                                    try {
                                                                                        const values = await rescheduleForm.validateFields();
                                                                                        setRescheduleConfirming(true);
                                                                                        // Validate new_room_id là mảng số hợp lệ
                                                                                        const validRoomIds = Array.isArray(values.new_room_id) ? values.new_room_id.filter((id: any) => typeof id === 'number' && id) : [];
                                                                                        if (!validRoomIds.length) {
                                                                                            message.error('Vui lòng chọn phòng mới hợp lệ!');
                                                                                            return;
                                                                                        }
                                                                                        await bookingService.confirmRescheduleBooking(
                                                                                            rescheduleBooking.booking_id,
                                                                                            dayjs(values.new_check_in_date).format('YYYY-MM-DD'),
                                                                                            dayjs(values.new_check_out_date).format('YYYY-MM-DD'),
                                                                                            validRoomIds,
                                                                                            values.reason
                                                                                        );
                                                                                        Modal.success({
                                                                                            title: 'Dời lịch thành công',
                                                                                            content: 'Đặt phòng đã được dời lịch. Vui lòng kiểm tra lại danh sách đặt phòng.',
                                                                                        });
                                                                                        setRescheduleModalVisible(false);
                                                                                        setReschedulePolicy(null);
                                                                                        setRescheduleBooking(null);
                                                                                        setBookings(prev => prev.map(b => b.booking_id === rescheduleBooking.booking_id ? { ...b, check_in_date: dayjs(values.new_check_in_date).format('YYYY-MM-DD'), check_out_date: dayjs(values.new_check_out_date).format('YYYY-MM-DD') } : b));
                                                                                    } catch (err: any) {
                                                                                        Modal.error({
                                                                                            title: 'Dời lịch thất bại',
                                                                                            content: err?.message || 'Đã có lỗi xảy ra',
                                                                                        });
                                                                                    } finally {
                                                                                        setRescheduleConfirming(false);
                                                                                    }
                                                                                }}
                                                                                style={{ marginRight: 8 }}
                                                                            >
                                                                                Xác nhận dời lịch
                                                                            </Button>
                                                                            <Button onClick={() => setRescheduleModalVisible(false)}>Đóng</Button>
                                                                        </Form.Item>
                                                                    </Form>
                                                                )}
                                                                {/* Hiển thị chính sách nếu có */}
                                                                {reschedulePolicy && (
                                                                    <div style={{ marginTop: 24, background: '#f6ffed', border: '1px solid #b7eb8f', borderRadius: 8, padding: 16 }}>
                                                                        <Title level={5} style={{ color: '#389e0d', marginBottom: 8 }}>Chính sách dời lịch</Title>
                                                                        <div><b>Thông báo:</b> {reschedulePolicy.message}</div>
                                                                        <div><b>Công thức:</b> {reschedulePolicy.formula}</div>
                                                                        <div><b>Chính sách:</b> {reschedulePolicy.policy} (ID: {reschedulePolicy.policy_id})</div>
                                                                        <div><b>Phí dời lịch:</b> <span style={{ color: '#fa541c', fontWeight: 600 }}>{Number(reschedulePolicy.reschedule_fee || 0).toLocaleString('vi-VN')}₫</span></div>
                                                                        <div><b>Chênh lệch giá:</b> {Number(reschedulePolicy.price_difference || 0).toLocaleString('vi-VN')}₫</div>
                                                                        <div><b>Tổng chênh lệch:</b> {Number(reschedulePolicy.total_price_difference || 0).toLocaleString('vi-VN')}₫</div>
                                                                        <div><b>Loại phí:</b> {reschedulePolicy.fee_type}</div>
                                                                        <div><b>Tỷ lệ phần trăm:</b> {reschedulePolicy.reschedule_percentage}</div>
                                                                        <div><b>Phí cố định:</b> {Number(reschedulePolicy.reschedule_fixed_amount || 0).toLocaleString('vi-VN')}₫</div>
                                                                        <Divider />
                                                                        <div><b>Booking:</b> {reschedulePolicy.booking_info?.booking_code}</div>
                                                                        <div><b>Check-in:</b> {reschedulePolicy.booking_info?.check_in_date}</div>
                                                                        <div><b>Check-out:</b> {reschedulePolicy.booking_info?.check_out_date}</div>
                                                                        <div><b>Tổng giá trị booking:</b> {Number(reschedulePolicy.booking_info?.total_price || 0).toLocaleString('vi-VN')}₫</div>
                                                                        <Divider />
                                                                        <div><b>Phòng mới:</b></div>
                                                                        {Array.isArray(reschedulePolicy.room_info) && reschedulePolicy.room_info.length > 0 ? (
                                                                            <ul style={{ paddingLeft: 20 }}>
                                                                                {reschedulePolicy.room_info.map((room: any, idx: number) => (
                                                                                    <li key={idx}>
                                                                                        <b>{room.room_name}</b> - {room.room_type} (Gói: {room.package_name})<br />
                                                                                        <span style={{ color: '#888' }}>{room.room_description}</span>
                                                                                    </li>
                                                                                ))}
                                                                            </ul>
                                                                        ) : <div>Không có thông tin phòng mới</div>}
                                                                    </div>
                                                                )}
                                                            </Modal>
                                                        </Space>
                                                    </div>
                                                    <Text style={{ fontSize: 13, color: '#888', padding: '4px 8px', borderRadius: 4, display: 'inline-block', marginTop: 8 }}>Mã: {booking.booking_code}</Text>
                                                </div>
                                            </Col>

                                            {/* Modal xác nhận chính sách huỷ */}
                                            <Modal
                                                open={!!cancelPolicy}
                                                onCancel={() => { setCancelPolicy(null); setCancelBookingId(null); }}
                                                title={<div style={{ display: 'flex', alignItems: 'center', fontWeight: 600 }}><SafetyOutlined style={{ color: '#faad14', marginRight: 8 }} />Xác nhận huỷ phòng</div>}
                                                footer={null}
                                                width={500}
                                                destroyOnClose

                                                maskClosable={false}
                                                maskStyle={{ background: 'rgba(0,0,0,0.08)' }}

                                            >
                                                {cancelPolicy && (
                                                    <div>
                                                        <div style={{ marginBottom: 12, color: '#faad14', fontWeight: 500 }}>{cancelPolicy.message}</div>
                                                        <div style={{ marginBottom: 8 }}><b>Chính sách:</b> {cancelPolicy.policy}</div>
                                                        <div style={{ marginBottom: 8 }}><b>Lý do:</b> {cancelPolicy.reason}</div>
                                                        <div style={{ marginBottom: 8 }}><b>Công thức:</b> {cancelPolicy.formula}</div>
                                                        <div style={{ marginBottom: 8 }}><b>Tiền phạt:</b> <span style={{ color: '#d4380d', fontWeight: 600 }}>{Number(cancelPolicy.penalty).toLocaleString('vi-VN')}₫</span></div>
                                                        <div style={{ marginBottom: 8 }}><b>Tổng giá trị booking:</b> {Number(cancelPolicy.booking_info?.total_price || 0).toLocaleString('vi-VN')}₫</div>
                                                        <div style={{ marginBottom: 8 }}><b>Mã booking:</b> {cancelPolicy.booking_info?.booking_code}</div>
                                                        <div style={{ marginTop: 24, display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                                                            <Button onClick={() => { setCancelPolicy(null); setCancelBookingId(null); }}>
                                                                Quay lại
                                                            </Button>
                                                            <Button type="primary" danger loading={cancelConfirming} onClick={handleConfirmCancel}>
                                                                Xác nhận huỷ
                                                            </Button>
                                                        </div>
                                                    </div>
                                                )}
                                            </Modal>

                                            {/* Modal xác nhận chính sách gia hạn */}
                                            <Modal
                                                open={!!extendPolicy}
                                                onCancel={() => { setExtendPolicy(null); setExtendBookingId(null); setExtendDate(null); }}
                                                title={<div style={{ display: 'flex', alignItems: 'center', fontWeight: 600 }}><SafetyOutlined style={{ color: '#1890ff', marginRight: 8 }} />Xác nhận gia hạn phòng</div>}
                                                footer={null}
                                                width={500}
                                                destroyOnClose
                                                maskClosable={false}
                                                maskStyle={{ background: 'rgba(0,0,0,0.08)' }}
                                            >
                                                {extendPolicy && (
                                                    <div>
                                                        <div style={{ marginBottom: 12, color: '#1890ff', fontWeight: 500 }}>{extendPolicy.message}</div>
                                                        <div style={{ marginBottom: 8 }}><b>Lý do:</b> {extendPolicy.reason}</div>
                                                        <div style={{ marginBottom: 8 }}><b>Công thức:</b> {extendPolicy.formula}</div>
                                                        <div style={{ marginBottom: 8 }}><b>Chính sách:</b> {extendPolicy.policy} (ID: {extendPolicy.policy_id})</div>
                                                        <div style={{ marginBottom: 8 }}><b>Phí gia hạn:</b> <span style={{ color: '#1890ff', fontWeight: 600 }}>{Number(extendPolicy.extension_fee || 0).toLocaleString('vi-VN')}₫</span></div>
                                                        <div style={{ marginBottom: 8 }}><b>Số ngày gia hạn:</b> {extendPolicy.extension_days}</div>
                                                        <div style={{ marginBottom: 8 }}><b>Loại phí:</b> {extendPolicy.fee_type}</div>
                                                        <div style={{ marginBottom: 8 }}><b>Tỷ lệ phần trăm:</b> {extendPolicy.extension_percentage}</div>
                                                        <div style={{ marginBottom: 8 }}><b>Phí cố định mỗi ngày:</b> {Number(extendPolicy.extension_fixed_amount || 0).toLocaleString('vi-VN')}₫</div>
                                                        <div style={{ marginBottom: 8 }}><b>Tổng giá trị booking sau gia hạn:</b> {Number(extendPolicy.booking_info?.total_price || 0).toLocaleString('vi-VN')}₫</div>
                                                        <div style={{ marginBottom: 8 }}><b>Mã booking:</b> {extendPolicy.booking_info?.booking_code}</div>
                                                        <div style={{ marginBottom: 8 }}><b>Phòng:</b> {extendPolicy.booking_info?.room_name || extendPolicy.room_info?.room_name}</div>
                                                        <div style={{ marginBottom: 8 }}><b>Check-in:</b> {extendPolicy.booking_info?.check_in_date}</div>
                                                        <div style={{ marginBottom: 8 }}><b>Check-out mới:</b> {extendPolicy.booking_info?.check_out_date}</div>
                                                        <div style={{ marginBottom: 8 }}><b>Loại phòng:</b> {extendPolicy.room_info?.room_type}</div>
                                                        <div style={{ marginBottom: 8 }}><b>Mô tả phòng:</b> {extendPolicy.room_info?.room_description}</div>
                                                        <div style={{ marginBottom: 8 }}><b>Khách sạn:</b> {extendPolicy.hotel_info?.hotel_name}</div>
                                                        <div style={{ marginBottom: 8 }}><b>Địa chỉ KS:</b> {extendPolicy.hotel_info?.hotel_address}</div>
                                                        <div style={{ marginBottom: 8 }}><b>Điện thoại KS:</b> {extendPolicy.hotel_info?.hotel_phone}</div>
                                                        <div style={{ marginBottom: 8 }}>
                                                            <b>Chọn ngày trả phòng mới:</b>
                                                            <DatePicker
                                                                value={extendDate}
                                                                onChange={handleExtendDateChange}
                                                                style={{ marginLeft: 8 }}
                                                                disabledDate={current => {
                                                                    if (!current) return false;
                                                                    const minDate = dayjs(extendPolicy.booking_info?.check_out_date);
                                                                    return current.isSame(minDate, 'day') || current.isBefore(minDate, 'day');
                                                                }}
                                                                format="YYYY-MM-DD"
                                                            />
                                                        </div>
                                                        <div style={{ marginTop: 24, display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                                                            <Button onClick={() => { setExtendPolicy(null); setExtendBookingId(null); setExtendDate(null); }}>
                                                                Quay lại
                                                            </Button>
                                                            <Button type="primary" loading={extendConfirming} onClick={handleConfirmExtend}>
                                                                Xác nhận gia hạn
                                                            </Button>
                                                        </div>
                                                    </div>
                                                )}
                                            </Modal>
                                        </Row>
                                    </Card>
                                </Col>
                            ))}
                        </Row>
                    ) : (
                        <Empty description="Không tìm thấy đặt phòng nào" image={Empty.PRESENTED_IMAGE_SIMPLE} style={{ padding: '60px 20px' }} />
                    )}
                </div>
            </Card>

            {/* Booking Detail Modal */}
            <Modal
                title={
                    <div style={{
                        display: 'flex',
                        alignItems: 'center',
                        fontSize: '18px',
                        fontWeight: 500
                    }}>
                        <CalendarOutlined style={{ marginRight: '12px', color: '#1890ff' }} />
                        Chi tiết đặt phòng
                    </div>
                }
                open={isDetailModalVisible}
                onCancel={() => setIsDetailModalVisible(false)}
                footer={null}
                width={800}
                style={{ top: 20 }}
            >
                {selectedBooking && (
                    <div style={{ marginTop: '20px' }}>
                        <Row gutter={[24, 24]}>
                            <Col xs={24} md={12}>
                                <Carousel dots={true} style={{ width: '100%' }}>
                                    {(
                                        selectedBooking.room_type_images && selectedBooking.room_type_images.length > 0
                                            ? selectedBooking.room_type_images
                                            : (selectedBooking.room_image
                                                ? [{ image_path: selectedBooking.room_image, alt_text: selectedBooking.room_name }]
                                                : [])
                                    ).length > 0 ? (
                                        (selectedBooking.room_type_images && selectedBooking.room_type_images.length > 0
                                            ? selectedBooking.room_type_images
                                            : [{ image_path: selectedBooking.room_image, alt_text: selectedBooking.room_name }]
                                        ).map((img, idx) => (
                                            <Image key={idx} src={img.image_path} alt={img.alt_text} style={{ width: '100%', height: 220, objectFit: 'cover', borderRadius: '12px' }} preview={false} />
                                        ))
                                    ) : (
                                        <div style={{ width: '100%', height: 220, borderRadius: '12px', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#bbb' }}>
                                            Không có ảnh
                                        </div>
                                    )}
                                </Carousel>
                            </Col>
                            <Col xs={24} md={12}>
                                <Space direction="vertical" size={16} style={{ width: '100%' }}>
                                    <div>
                                        <Title level={3} style={{ margin: '0 0 8px 0' }}>
                                            {selectedBooking.room_name}
                                        </Title>
                                        <Text style={{ color: '#1890ff', fontSize: '16px', fontWeight: 500 }}>
                                            {selectedBooking.room_type}
                                        </Text>
                                    </div>
                                    <div>
                                        <Tag
                                            color={getStatusColor(selectedBooking.status)}
                                            icon={getStatusIcon(selectedBooking.status)}
                                            style={{ fontSize: '14px', padding: '6px 12px', borderRadius: '6px', border: 'none' }}
                                        >
                                            {getStatusText(selectedBooking.status)}
                                        </Tag>
                                    </div>
                                    <Divider style={{ margin: '16px 0' }} />
                                    <Timeline
                                        items={[
                                            {
                                                dot: <ClockCircleOutlined style={{ color: '#1890ff' }} />, children: (
                                                    <div>
                                                        <Text strong style={{ color: '#262626' }}>Ngày đặt phòng</Text>
                                                        <br />
                                                        <Text style={{ color: '#8c8c8c' }}>{dayjs(selectedBooking.created_at).format('DD/MM/YYYY HH:mm')}</Text>
                                                    </div>
                                                )
                                            },
                                            {
                                                dot: <CalendarOutlined style={{ color: '#52c41a' }} />, children: (
                                                    <div>
                                                        <Text strong style={{ color: '#262626' }}>Check-in / Check-out</Text>
                                                        <br />
                                                        <Text style={{ color: '#8c8c8c' }}>{dayjs(selectedBooking.check_in_date).format('DD/MM/YYYY')} - {dayjs(selectedBooking.check_out_date).format('DD/MM/YYYY')}</Text>
                                                        <br />
                                                        <Text style={{ color: '#1890ff', fontSize: '13px' }}>
                                                            ({selectedBooking.guest_name}, {selectedBooking.guest_phone})
                                                        </Text>
                                                    </div>
                                                )
                                            },
                                            {
                                                dot: <BankOutlined style={{ color: '#faad14' }} />, children: (
                                                    <div>
                                                        <Text strong style={{ color: '#262626' }}>Thanh toán</Text>
                                                        <br />
                                                        <Text style={{ color: '#8c8c8c' }}>{selectedBooking.payment_status}</Text>
                                                        <br />
                                                        <Text strong style={{ color: '#52c41a', fontSize: '16px' }}>{Number(selectedBooking.total_price_vnd).toLocaleString('vi-VN')}₫</Text>
                                                    </div>
                                                )
                                            }
                                        ]}
                                    />
                                </Space>
                            </Col>
                        </Row>
                        <Divider style={{ margin: '24px 0' }} />
                        <Row gutter={[24, 24]}>
                            <Col xs={24} md={24}>
                                <div>
                                    <Title level={5} style={{ color: '#262626', marginBottom: '16px', display: 'flex', alignItems: 'center' }}>
                                        <GiftOutlined style={{ color: '#1890ff', marginRight: '8px' }} />
                                        Tiện ích & Dịch vụ
                                    </Title>
                                    <div style={{ padding: '16px', borderRadius: '8px', border: '1px solid #f0f0f0' }}>
                                        <Space wrap>
                                            {selectedBooking.room_type_amenities && selectedBooking.room_type_amenities.length > 0 ? (
                                                selectedBooking.room_type_amenities
                                                    .sort((a: any, b: any) => (b.is_highlighted - a.is_highlighted))
                                                    .map((amenity: any, idx: number) => (
                                                        <div key={idx} style={{
                                                            display: 'flex',
                                                            alignItems: 'center',
                                                            background: amenity.is_highlighted ? getCategoryColor(amenity.category) + '22' : '#f6f6f6',
                                                            borderRadius: 6,
                                                            padding: '4px 10px',
                                                            minWidth: 90,
                                                            boxShadow: amenity.is_highlighted ? '0 1px 6px 0 #e0e7ff' : undefined,
                                                            border: amenity.is_highlighted ? '1.5px solid ' + getCategoryColor(amenity.category) : '1px solid #eee',
                                                            fontWeight: amenity.is_highlighted ? 600 : 400,
                                                            color: amenity.is_highlighted ? getCategoryColor(amenity.category) : '#444',
                                                            fontSize: 13,
                                                            marginBottom: 2
                                                        }}>
                                                            {getAmenityIcon(amenity.icon, amenity.category, amenity.is_highlighted)}
                                                            <span style={{ marginLeft: 7 }}>{amenity.name}</span>
                                                        </div>
                                                    ))
                                            ) : (
                                                <Text type="secondary">Không có tiện ích</Text>
                                            )}
                                        </Space>
                                    </div>
                                </div>
                            </Col>
                        </Row>
                        <Divider style={{ margin: '24px 0' }} />
                        <Row gutter={[24, 16]}>
                            <Col xs={24} md={12}>
                                <div style={{ border: '1px solid #b7eb8f', borderRadius: '8px', padding: '16px' }}>
                                    <Title level={5} style={{ color: '#389e0d', marginBottom: '8px', display: 'flex', alignItems: 'center' }}>
                                        <SafetyOutlined style={{ marginRight: '8px' }} />
                                        Chính sách hủy phòng
                                    </Title>
                                    <Text style={{ color: '#595959' }}>
                                        {/* Add cancellation policy if available */}
                                    </Text>
                                </div>
                            </Col>
                            <Col xs={24} md={12}>
                                <div style={{ border: '1px solid #91d5ff', borderRadius: '8px', padding: '16px' }}>
                                    <Title level={5} style={{ color: '#1890ff', marginBottom: '8px', display: 'flex', alignItems: 'center' }}>
                                        <CreditCardOutlined style={{ marginRight: '8px' }} />
                                        Chính sách thanh toán
                                    </Title>
                                    <Text style={{ color: '#595959' }}>
                                        {/* Add payment policy if available */}
                                    </Text>
                                </div>
                            </Col>
                        </Row>
                        {/* Confirmation Code */}
                        <div style={{ marginTop: '24px', textAlign: 'center', padding: '16px', borderRadius: '8px' }}>
                            <Text style={{ color: '#8c8c8c', fontSize: '14px' }}>
                                Mã xác nhận đặt phòng
                            </Text>
                            <br />
                            <Text style={{ fontSize: '18px', fontWeight: 600, letterSpacing: '2px' }}>
                                {selectedBooking.booking_code}
                            </Text>
                        </div>
                    </div>
                )}
            </Modal>
        </div>
    );
}

export default React.memo(BookingManagement);