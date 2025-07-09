import React, { useState, useMemo, useCallback } from 'react';
import {
    Card,
    Row,
    Col,
    Typography,
    Tag,
    Button,
    Space,
    Tabs,
    Rate,
    Divider,
    Modal,
    Timeline,
    Image,
    Empty,
    Input,
    DatePicker
} from 'antd';
import {
    CalendarOutlined,
    ClockCircleOutlined,
    UserOutlined,
    PhoneOutlined,
    CreditCardOutlined,
    EyeOutlined,
    DeleteOutlined,
    StarOutlined,
    CheckCircleOutlined,
    ExclamationCircleOutlined,
    CloseCircleOutlined,
    DownloadOutlined,
    TeamOutlined,
    BankOutlined,
    GiftOutlined,
    SafetyOutlined
} from '@ant-design/icons';
import dayjs from 'dayjs';
import { RoomOption } from '../../mirage/roomoption';

const { Title, Text, Paragraph } = Typography;
const { Search } = Input;
const { RangePicker } = DatePicker;

interface Booking {
    id: string;
    hotelName: string;
    roomOption: RoomOption;
    roomImage: string;
    checkIn: string;
    checkOut: string;
    nights: number;
    guests: number;
    totalPrice: number;
    status: 'confirmed' | 'completed' | 'cancelled' | 'pending';
    bookingDate: string;
    paymentMethod: string;
    confirmationCode: string;
    rating?: number;
    review?: string;
    contact: string;
}

const BookingManagement: React.FC = () => {
    const [activeTab, setActiveTab] = useState('all');
    const [selectedBooking, setSelectedBooking] = useState<Booking | null>(null);
    const [isDetailModalVisible, setIsDetailModalVisible] = useState(false);
    const [searchText, setSearchText] = useState('');
    const [dateRange, setDateRange] = useState<[dayjs.Dayjs | null, dayjs.Dayjs | null] | null>(null);

    // Memoize heavy computations
    const mockRoomOptions = useMemo(() => [
        {
            id: 'deluxe-001',
            name: 'Deluxe Room with City View',
            pricePerNight: { vnd: 2150000 },
            maxGuests: 2,
            minGuests: 1, roomType: 'deluxe' as const,
            cancellationPolicy: {
                type: 'free' as const,
                freeUntil: '2024-12-14',
                description: 'Hủy miễn phí trước 24 giờ'
            },
            paymentPolicy: {
                type: 'pay_now_with_vietQR' as const,
                description: 'Thanh toán ngay bằng VietQR',
                prepaymentRequired: true
            },
            availability: {
                total: 10,
                remaining: 3,
                urgencyMessage: 'Chỉ còn 3 phòng!'
            },
            additionalServices: [
                { icon: '🛜', name: 'WiFi miễn phí', included: true },
                { icon: '❄️', name: 'Điều hòa không khí', included: true },
                { icon: '📺', name: 'TV màn hình phẳng', included: true },
                { icon: '🍸', name: 'Minibar', price: '200.000 VNĐ', included: false }
            ], promotion: {
                type: 'hot' as const,
                message: 'Giảm 15% cho đặt phòng sớm',
                discount: 15
            },
            recommended: true
        }, {
            id: 'suite-002',
            name: 'Ocean View Suite Premium',
            pricePerNight: { vnd: 3200000 },
            maxGuests: 4,
            minGuests: 2,
            roomType: 'suite' as const,
            cancellationPolicy: {
                type: 'free' as const,
                freeUntil: '2024-11-09',
                description: 'Hủy miễn phí trước 48 giờ'
            },
            paymentPolicy: {
                type: 'pay_at_hotel' as const,
                description: 'Thanh toán tại khách sạn'
            },
            availability: {
                total: 5,
                remaining: 1,
                urgencyMessage: 'Phòng cuối cùng!'
            },
            additionalServices: [
                { icon: '🌊', name: 'Tầm nhìn cảnh biển', included: true },
                { icon: '🏊', name: 'Bể bơi riêng', included: true },
                { icon: '💆', name: 'Spa & Massage', price: '800.000 VNĐ', included: false },
                { icon: '🛜', name: 'WiFi miễn phí', included: true }
            ],
            mostPopular: true
        }, {
            id: 'premium-003',
            name: 'Executive Premium Room',
            pricePerNight: { vnd: 1850000 },
            maxGuests: 2,
            minGuests: 1,
            roomType: 'premium' as const,
            cancellationPolicy: {
                type: 'conditional' as const,
                penalty: 50,
                description: 'Hủy có phí 50% sau 24 giờ'
            },
            paymentPolicy: {
                type: 'pay_now_with_vietQR' as const,
                description: 'Thanh toán ngay bằng VietQR'
            },
            availability: {
                total: 8,
                remaining: 5
            },
            additionalServices: [
                { icon: '🏢', name: 'Phòng chờ thương gia', included: true },
                { icon: '🛜', name: 'WiFi miễn phí', included: true },
                { icon: '🏋️', name: 'Gym 24/7', included: true },
                { icon: '🍳', name: 'Bữa sáng miễn phí', included: true }
            ]
        }, {
            id: 'presidential-004',
            name: 'Presidential Suite The Level',
            pricePerNight: { vnd: 5500000 },
            maxGuests: 6,
            minGuests: 2,
            roomType: 'presidential' as const,
            cancellationPolicy: {
                type: 'free' as const,
                freeUntil: '2024-12-20',
                description: 'Hủy miễn phí trước 72 giờ'
            },
            paymentPolicy: {
                type: 'pay_now_with_vietQR' as const,
                description: 'Thanh toán ngay bằng VietQR',
                prepaymentRequired: true
            },
            availability: {
                total: 2,
                remaining: 1,
                urgencyMessage: 'Chỉ còn 1 phòng cuối cùng!'
            },
            additionalServices: [
                { icon: '👑', name: 'Dịch vụ butler riêng', included: true },
                { icon: '🍾', name: 'Champagne chào mừng', included: true },
                { icon: '🚗', name: 'Đưa đón sân bay', included: true },
                { icon: '💎', name: 'Phòng VIP Lounge', included: true }
            ], promotion: {
                type: 'limited' as const,
                message: 'Ưu đãi đặc biệt - Giảm 25%',
                discount: 25
            }
        }, {
            id: 'thelevel-005',
            name: 'The Level Premium Corner',
            pricePerNight: { vnd: 2750000 },
            maxGuests: 3,
            minGuests: 1,
            roomType: 'theLevelPremiumCorner' as const,
            cancellationPolicy: {
                type: 'free' as const,
                freeUntil: '2024-12-10',
                description: 'Hủy miễn phí trước 48 giờ'
            },
            paymentPolicy: {
                type: 'pay_at_hotel' as const,
                description: 'Thanh toán tại khách sạn'
            },
            availability: {
                total: 6,
                remaining: 2,
                urgencyMessage: 'Chỉ còn 2 phòng!'
            },
            additionalServices: [
                { icon: '🏙️', name: 'Góc view thành phố', included: true },
                { icon: '☕', name: 'Coffee & Tea miễn phí', included: true },
                { icon: '📰', name: 'Báo chí hàng ngày', included: true },
                { icon: '🎭', name: 'Concierge service', included: true }
            ]
        }
    ], []);

    const bookings = useMemo(() => [
        {
            id: 'BK001',
            hotelName: 'LavishStay Deluxe ',
            roomOption: mockRoomOptions[0],
            roomImage: 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=400&h=300&fit=crop',
            checkIn: '2024-12-15',
            checkOut: '2024-12-18',
            nights: 3,
            guests: 2,
            totalPrice: mockRoomOptions[0].pricePerNight.vnd * 3,
            status: 'confirmed',
            bookingDate: '2024-12-01',
            paymentMethod: 'VietQR',
            confirmationCode: 'LS2024001',
            contact: '+84 24 3825 1234'
        },
        {
            id: 'BK002',
            hotelName: 'LavishStay Beach Resort',
            roomOption: mockRoomOptions[1],
            roomImage: 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=400&h=300&fit=crop',
            checkIn: '2024-11-10',
            checkOut: '2024-11-13',
            nights: 3,
            guests: 4,
            totalPrice: mockRoomOptions[1].pricePerNight.vnd * 3,
            status: 'completed',
            bookingDate: '2024-10-25',
            paymentMethod: 'Thanh toán tại khách sạn',
            confirmationCode: 'LS2024002',
            rating: 5,
            review: 'Khách sạn tuyệt vời! View biển đẹp, dịch vụ chuyên nghiệp.',
            contact: '+84 236 3851 234'
        },
        {
            id: 'BK003',
            hotelName: 'LavishStay City Center TP.HCM',
            roomOption: mockRoomOptions[2],
            roomImage: 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=400&h=300&fit=crop',
            checkIn: '2024-10-05',
            checkOut: '2024-10-07',
            nights: 2,
            guests: 1,
            totalPrice: mockRoomOptions[2].pricePerNight.vnd * 2,
            status: 'cancelled',
            bookingDate: '2024-09-20',
            paymentMethod: 'VietQR',
            confirmationCode: 'LS2024003',
            contact: '+84 28 3824 1234'
        },
        {
            id: 'BK004',
            hotelName: 'LavishStay Presidential Suite Phú Quốc',
            roomOption: mockRoomOptions[3],
            roomImage: 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=400&h=300&fit=crop',
            checkIn: '2024-12-20',
            checkOut: '2024-12-23',
            nights: 3,
            guests: 4,
            totalPrice: mockRoomOptions[3].pricePerNight.vnd * 3,
            status: 'confirmed',
            bookingDate: '2024-12-05',
            paymentMethod: 'VietQR',
            confirmationCode: 'LS2024004',
            contact: '+84 297 3999 888'
        },
        {
            id: 'BK005',
            hotelName: 'LavishStay The Level Nha Trang',
            roomOption: mockRoomOptions[4],
            roomImage: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&h=300&fit=crop',
            checkIn: '2024-11-25',
            checkOut: '2024-11-28',
            nights: 3,
            guests: 2,
            totalPrice: mockRoomOptions[4].pricePerNight.vnd * 3,
            status: 'completed',
            bookingDate: '2024-11-15',
            paymentMethod: 'Thanh toán tại khách sạn',
            confirmationCode: 'LS2024005',
            rating: 4,
            review: 'Phòng đẹp, view đẹp. Nhân viên thân thiện.',
            contact: '+84 258 3888 999'
        }
    ], [mockRoomOptions]);

    // Memoize helper functions
    const getStatusColor = useCallback((status: string) => {
        switch (status) {
            case 'confirmed': return 'blue';
            case 'completed': return 'green';
            case 'cancelled': return 'red';
            case 'pending': return 'orange';
            default: return 'default';
        }
    }, []);

    const getStatusText = useCallback((status: string) => {
        switch (status) {
            case 'confirmed': return 'Đã xác nhận';
            case 'completed': return 'Hoàn thành';
            case 'cancelled': return 'Đã hủy';
            case 'pending': return 'Chờ xử lý';
            default: return status;
        }
    }, []);

    const getStatusIcon = useCallback((status: string) => {
        switch (status) {
            case 'confirmed': return <CheckCircleOutlined />;
            case 'completed': return <CheckCircleOutlined />;
            case 'cancelled': return <CloseCircleOutlined />;
            case 'pending': return <ExclamationCircleOutlined />;
            default: return <ClockCircleOutlined />;
        }
    }, []);

    // Memoize filtered bookings
    const filteredBookings = useMemo(() => {
        let filtered = bookings;

        // Filter by tab
        if (activeTab !== 'all') {
            filtered = filtered.filter(booking => booking.status === activeTab);
        }

        // Filter by search text
        if (searchText) {
            filtered = filtered.filter(booking =>
                booking.hotelName.toLowerCase().includes(searchText.toLowerCase()) ||
                booking.confirmationCode.toLowerCase().includes(searchText.toLowerCase()) ||
                booking.roomOption.name.toLowerCase().includes(searchText.toLowerCase())
            );
        }

        // Filter by date range
        if (dateRange && dateRange[0] && dateRange[1]) {
            filtered = filtered.filter(booking => {
                const bookingDate = dayjs(booking.bookingDate);
                return bookingDate.isAfter(dayjs(dateRange[0])) && bookingDate.isBefore(dayjs(dateRange[1]));
            });
        }

        return filtered;
    }, [bookings, activeTab, searchText, dateRange]);

    const showBookingDetail = useCallback((booking: Booking) => {
        setSelectedBooking(booking);
        setIsDetailModalVisible(true);
    }, []);

    const handleCancelBooking = useCallback((bookingId: string) => {
        Modal.confirm({
            title: 'Xác nhận hủy đặt phòng',
            content: 'Bạn có chắc chắn muốn hủy đặt phòng này? Hành động này không thể hoàn tác.',
            okText: 'Hủy đặt phòng',
            cancelText: 'Không',
            okType: 'danger',
            onOk() {
                // Handle booking cancellation
                console.log('Cancel booking:', bookingId);
            }
        });
    }, []);

    const tabItems = [
        { key: 'all', label: `Tất cả (${bookings.length})` },
        { key: 'confirmed', label: `Đã xác nhận (${bookings.filter(b => b.status === 'confirmed').length})` },
        { key: 'completed', label: `Hoàn thành (${bookings.filter(b => b.status === 'completed').length})` },
        { key: 'cancelled', label: `Đã hủy (${bookings.filter(b => b.status === 'cancelled').length})` },
        { key: 'pending', label: `Chờ xử lý (${bookings.filter(b => b.status === 'pending').length})` }
    ]; return (
        <div style={{ padding: '0' }}>
            {/* Header */}
            <div style={{ marginBottom: '32px' }}>
                <Row align="middle" justify="space-between">
                    <Col>
                        <Title level={2} style={{
                            margin: 0,
                            color: '#262626',
                            fontWeight: 500
                        }}>
                            <CalendarOutlined style={{
                                marginRight: '12px',
                                color: '#1890ff',
                                fontSize: '28px'
                            }} />
                            Quản lý đặt phòng
                        </Title>
                        <Text style={{
                            fontSize: '16px',
                            color: '#8c8c8c',
                            marginTop: '8px',
                            display: 'block'
                        }}>
                            Theo dõi và quản lý các đặt phòng của bạn tại LavishStay Hotel
                        </Text>
                    </Col>
                    <Col>
                        <Button
                            type="primary"
                            icon={<DownloadOutlined />}
                            size="large"
                            style={{
                                borderRadius: '8px',
                                height: '44px',
                                borderColor: 'transparent',
                                fontWeight: 500,
                            }}
                        >
                            Xuất báo cáo
                        </Button>
                    </Col>
                </Row>
            </div>

            {/* Filters */}
            <Card
                bordered={false}
                style={{
                    borderRadius: '12px',
                    marginBottom: '24px',
                    boxShadow: '0 2px 8px rgba(0,0,0,0.06)'
                }}
            >
                <Row gutter={[16, 16]} align="middle">
                    <Col xs={24} sm={12} md={8}>
                        <Search
                            placeholder="Tìm theo tên khách sạn, mã đặt phòng, loại phòng..."
                            value={searchText}
                            onChange={(e) => setSearchText(e.target.value)}
                            size="large"
                            style={{
                                borderRadius: '8px'
                            }}
                        />
                    </Col>
                    <Col xs={24} sm={12} md={8}>
                        <RangePicker
                            placeholder={['Từ ngày', 'Đến ngày']}
                            value={dateRange}
                            onChange={setDateRange}
                            size="large"
                            style={{
                                width: '100%',
                                borderRadius: '8px'
                            }}
                        />
                    </Col>
                    <Col xs={24} md={8}>
                        <div style={{
                            padding: '12px 16px',
                            background: 'linear-gradient(135deg, #f0f2f5 0%, #fafafa 100%)',
                            borderRadius: '8px',
                            textAlign: 'center'
                        }}>
                            <Text strong >
                                <TeamOutlined style={{ marginRight: '8px' }} />
                                Tổng: {filteredBookings.length} đặt phòng
                            </Text>
                        </div>
                    </Col>
                </Row>
            </Card>

            {/* Booking List */}
            <Card
                bordered={false}
                style={{
                    borderRadius: '12px',
                    boxShadow: '0 2px 8px rgba(0,0,0,0.06)'
                }}
            >
                <Tabs
                    activeKey={activeTab}
                    onChange={setActiveTab}
                    style={{ marginBottom: '24px' }}
                    items={[
                        {
                            key: 'all',
                            label: (
                                <span>
                                    <CalendarOutlined style={{ marginRight: '6px' }} />
                                    Tất cả ({bookings.length})
                                </span>
                            )
                        },
                        {
                            key: 'confirmed',
                            label: (
                                <span>
                                    <CheckCircleOutlined style={{ marginRight: '6px', color: '#1890ff' }} />
                                    Đã xác nhận ({bookings.filter(b => b.status === 'confirmed').length})
                                </span>
                            )
                        },
                        {
                            key: 'completed',
                            label: (
                                <span>
                                    <CheckCircleOutlined style={{ marginRight: '6px', color: '#52c41a' }} />
                                    Hoàn thành ({bookings.filter(b => b.status === 'completed').length})
                                </span>
                            )
                        },
                        {
                            key: 'cancelled',
                            label: (
                                <span>
                                    <CloseCircleOutlined style={{ marginRight: '6px', color: '#ff4d4f' }} />
                                    Đã hủy ({bookings.filter(b => b.status === 'cancelled').length})
                                </span>
                            )
                        }
                    ]}
                />

                <div>
                    {filteredBookings.length > 0 ? (
                        <Row gutter={[0, 16]}>
                            {filteredBookings.map((booking) => (
                                <Col xs={24} key={booking.id}>
                                    <Card
                                        bordered={false}
                                        style={{
                                            borderRadius: '12px',
                                            border: '1px solid #f0f0f0',
                                            overflow: 'hidden',
                                            transition: 'all 0.3s ease',
                                            cursor: 'pointer'
                                        }}
                                        hoverable
                                        onClick={() => showBookingDetail(booking)}
                                    >
                                        <Row gutter={[16, 16]} align="middle">
                                            {/* Hotel Image */}
                                            <Col xs={24} sm={6} md={4}>
                                                <div style={{ position: 'relative' }}>
                                                    <Image
                                                        src={booking.roomImage}
                                                        alt={booking.hotelName}
                                                        style={{
                                                            width: '120%',
                                                            objectFit: 'cover',
                                                            borderRadius: '8px'
                                                        }}
                                                        preview={false}
                                                    />
                                                    {booking.roomOption.promotion && (
                                                        <div style={{
                                                            position: 'absolute',
                                                            top: '8px',
                                                            left: '8px',
                                                            background: 'linear-gradient(135deg, #ff4d4f 0%, #cf1322 100%)',
                                                            color: 'white',
                                                            padding: '4px 8px',
                                                            borderRadius: '4px',
                                                            fontSize: '10px',
                                                            fontWeight: 600
                                                        }}>
                                                            <GiftOutlined style={{ marginRight: '4px' }} />
                                                            -{booking.roomOption.promotion.discount}%
                                                        </div>
                                                    )}
                                                </div>
                                            </Col>

                                            {/* Booking Info */}
                                            <Col xs={24} sm={12} md={14}>
                                                <div>
                                                    <div style={{ marginBottom: '12px' }}>
                                                        <Title level={4} style={{
                                                            margin: '0 0 4px 0',
                                                            color: '#262626',
                                                            fontSize: '18px'
                                                        }}>
                                                            {booking.hotelName}
                                                        </Title>
                                                        <Text style={{
                                                            color: '#1890ff',
                                                            fontWeight: 500,
                                                            fontSize: '14px'
                                                        }}>
                                                            {booking.roomOption.name}
                                                        </Text>
                                                    </div>

                                                    <Space direction="vertical" size={6} style={{ width: '100%' }}>
                                                        <div style={{ display: 'flex', alignItems: 'center' }}>
                                                            <CalendarOutlined style={{
                                                                color: '#1890ff',
                                                                marginRight: '8px',
                                                                fontSize: '14px'
                                                            }} />
                                                            <Text style={{ fontSize: '14px' }}>
                                                                {dayjs(booking.checkIn).format('DD/MM/YYYY')} - {dayjs(booking.checkOut).format('DD/MM/YYYY')}
                                                            </Text>
                                                            <Text style={{
                                                                marginLeft: '8px',
                                                                color: '#8c8c8c',
                                                                fontSize: '13px'
                                                            }}>
                                                                ({booking.nights} đêm)
                                                            </Text>
                                                        </div>

                                                        <div style={{ display: 'flex', alignItems: 'center' }}>
                                                            <UserOutlined style={{
                                                                color: '#1890ff',
                                                                marginRight: '8px',
                                                                fontSize: '14px'
                                                            }} />
                                                            <Text style={{ fontSize: '14px' }}>
                                                                {booking.guests} khách
                                                            </Text>
                                                        </div>
                                                    </Space>

                                                    {/* Amenities */}
                                                    <div style={{ marginTop: '12px' }}>
                                                        <Space wrap>
                                                            {booking.roomOption.additionalServices?.slice(0, 3).map((service, index) => (
                                                                <Tag
                                                                    key={index}
                                                                    style={{
                                                                        border: 'none',
                                                                        background: '#f0f2f5',
                                                                        borderRadius: '6px',
                                                                        padding: '2px 8px',
                                                                        fontSize: '12px'
                                                                    }}
                                                                >
                                                                    {service.icon} {service.name}
                                                                </Tag>
                                                            ))}
                                                            {booking.roomOption.additionalServices && booking.roomOption.additionalServices.length > 3 && (
                                                                <Tag style={{
                                                                    border: 'none',
                                                                    background: '#e6f7ff',
                                                                    color: '#1890ff',
                                                                    borderRadius: '6px',
                                                                    padding: '2px 8px',
                                                                    fontSize: '12px'
                                                                }}>
                                                                    +{booking.roomOption.additionalServices.length - 3} tiện ích
                                                                </Tag>
                                                            )}
                                                        </Space>
                                                    </div>
                                                </div>
                                            </Col>

                                            {/* Price & Actions */}
                                            <Col xs={24} sm={6} md={6}>
                                                <div style={{
                                                    textAlign: 'right',
                                                    display: 'flex',
                                                    flexDirection: 'column',
                                                    height: '100%',
                                                    justifyContent: 'space-between'
                                                }}>
                                                    <div>
                                                        <Text style={{
                                                            fontSize: '14px',
                                                            color: '#8c8c8c'
                                                        }}>
                                                            Tổng tiền
                                                        </Text>
                                                        <Title level={3} style={{
                                                            margin: '4px 0 8px 0',
                                                            color: '#262626',
                                                            fontSize: '22px',
                                                            fontWeight: 600
                                                        }}>
                                                            {booking.totalPrice.toLocaleString('vi-VN')}₫
                                                        </Title>
                                                    </div>

                                                    <div style={{ marginBottom: '12px' }}>
                                                        <Tag
                                                            color={getStatusColor(booking.status)}
                                                            icon={getStatusIcon(booking.status)}
                                                            style={{
                                                                fontSize: '13px',
                                                                padding: '4px 12px',
                                                                borderRadius: '6px',
                                                                border: 'none',
                                                                fontWeight: 500
                                                            }}
                                                        >
                                                            {getStatusText(booking.status)}
                                                        </Tag>
                                                    </div>

                                                    <div style={{ marginBottom: '12px' }}>
                                                        <Text style={{
                                                            fontSize: '13px',
                                                            color: '#8c8c8c',
                                                            background: '#fafafa',
                                                            padding: '4px 8px',
                                                            borderRadius: '4px',
                                                            display: 'inline-block'
                                                        }}>
                                                            Mã: {booking.confirmationCode}
                                                        </Text>
                                                    </div>

                                                    {booking.rating && (
                                                        <div style={{ marginBottom: '12px' }}>
                                                            <Rate
                                                                disabled
                                                                defaultValue={booking.rating}
                                                                style={{ fontSize: '14px' }}
                                                            />
                                                        </div>
                                                    )}

                                                    <div>
                                                        <Space direction="vertical" style={{ width: '100%' }}>
                                                            <Button
                                                                type="primary"
                                                                icon={<EyeOutlined />}
                                                                onClick={(e) => {
                                                                    e.stopPropagation();
                                                                    showBookingDetail(booking);
                                                                }}
                                                                style={{
                                                                    width: '100%',
                                                                    borderRadius: '6px',
                                                                    fontWeight: 500
                                                                }}
                                                            >
                                                                Chi tiết
                                                            </Button>

                                                            {booking.status === 'confirmed' && (
                                                                <Button
                                                                    danger
                                                                    icon={<DeleteOutlined />}
                                                                    onClick={(e) => {
                                                                        e.stopPropagation();
                                                                        handleCancelBooking(booking.id);
                                                                    }}
                                                                    style={{
                                                                        width: '100%',
                                                                        borderRadius: '6px',
                                                                        fontWeight: 500
                                                                    }}
                                                                >
                                                                    Hủy đặt phòng
                                                                </Button>
                                                            )}

                                                            {booking.status === 'completed' && !booking.rating && (
                                                                <Button
                                                                    icon={<StarOutlined />}
                                                                    style={{
                                                                        width: '100%',
                                                                        borderRadius: '6px',
                                                                        fontWeight: 500,
                                                                        borderColor: '#faad14',
                                                                        color: '#faad14'
                                                                    }}
                                                                >
                                                                    Đánh giá
                                                                </Button>
                                                            )}
                                                        </Space>
                                                    </div>
                                                </div>
                                            </Col>
                                        </Row>
                                    </Card>
                                </Col>
                            ))}
                        </Row>
                    ) : (
                        <Empty
                            description="Không tìm thấy đặt phòng nào"
                            image={Empty.PRESENTED_IMAGE_SIMPLE}
                            style={{ padding: '60px 20px' }}
                        />
                    )}
                </div>
            </Card>            {/* Booking Detail Modal */}
            <Modal
                title={
                    <div style={{
                        display: 'flex',
                        alignItems: 'center',
                        fontSize: '18px',
                        fontWeight: 500
                    }}>
                        <CalendarOutlined style={{
                            marginRight: '12px',
                            color: '#1890ff'
                        }} />
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
                                <Image
                                    src={selectedBooking.roomImage}
                                    alt={selectedBooking.hotelName}
                                    style={{
                                        width: '100%',
                                        borderRadius: '12px',
                                        maxHeight: '300px',
                                        objectFit: 'cover'
                                    }}
                                />
                            </Col>

                            <Col xs={24} md={12}>
                                <Space direction="vertical" size={16} style={{ width: '100%' }}>
                                    <div>
                                        <Title level={3} style={{ margin: '0 0 8px 0' }}>
                                            {selectedBooking.hotelName}
                                        </Title>
                                        <Text style={{
                                            color: '#1890ff',
                                            fontSize: '16px',
                                            fontWeight: 500
                                        }}>
                                            {selectedBooking.roomOption.name}
                                        </Text>
                                    </div>

                                    <div>
                                        <Tag
                                            color={getStatusColor(selectedBooking.status)}
                                            icon={getStatusIcon(selectedBooking.status)}
                                            style={{
                                                fontSize: '14px',
                                                padding: '6px 12px',
                                                borderRadius: '6px',
                                                border: 'none'
                                            }}
                                        >
                                            {getStatusText(selectedBooking.status)}
                                        </Tag>
                                    </div>

                                    <Divider style={{ margin: '16px 0' }} />

                                    <Timeline
                                        items={[
                                            {
                                                dot: <ClockCircleOutlined style={{ color: '#1890ff' }} />,
                                                children: (
                                                    <div>
                                                        <Text strong style={{ color: '#262626' }}>Ngày đặt phòng</Text>
                                                        <br />
                                                        <Text style={{ color: '#8c8c8c' }}>
                                                            {dayjs(selectedBooking.bookingDate).format('DD/MM/YYYY HH:mm')}
                                                        </Text>
                                                    </div>
                                                )
                                            },
                                            {
                                                dot: <CalendarOutlined style={{ color: '#52c41a' }} />,
                                                children: (
                                                    <div>
                                                        <Text strong style={{ color: '#262626' }}>Check-in / Check-out</Text>
                                                        <br />
                                                        <Text style={{ color: '#8c8c8c' }}>
                                                            {dayjs(selectedBooking.checkIn).format('DD/MM/YYYY')} - {dayjs(selectedBooking.checkOut).format('DD/MM/YYYY')}
                                                        </Text>
                                                        <br />
                                                        <Text style={{ color: '#1890ff', fontSize: '13px' }}>
                                                            ({selectedBooking.nights} đêm, {selectedBooking.guests} khách)
                                                        </Text>
                                                    </div>
                                                )
                                            },
                                            {
                                                dot: <BankOutlined style={{ color: '#faad14' }} />,
                                                children: (
                                                    <div>
                                                        <Text strong style={{ color: '#262626' }}>Thanh toán</Text>
                                                        <br />
                                                        <Text style={{ color: '#8c8c8c' }}>
                                                            {selectedBooking.paymentMethod}
                                                        </Text>
                                                        <br />
                                                        <Text strong style={{
                                                            color: '#52c41a',
                                                            fontSize: '16px'
                                                        }}>
                                                            {selectedBooking.totalPrice.toLocaleString('vi-VN')}₫
                                                        </Text>
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
                            <Col xs={24} md={12}>
                                <div>
                                    <Title level={5} style={{
                                        color: '#262626',
                                        marginBottom: '16px',
                                        display: 'flex',
                                        alignItems: 'center'
                                    }}>
                                        Thông tin liên hệ
                                    </Title>
                                    <Space direction="vertical" size={8}>

                                        <div style={{
                                            padding: '12px',
                                            background: '#fafafa',
                                            borderRadius: '8px',
                                            border: '1px solid #f0f0f0'
                                        }}>
                                            <PhoneOutlined style={{
                                                color: '#1890ff',
                                                marginRight: '8px'
                                            }} />
                                            <Text>{selectedBooking.contact}</Text>
                                        </div>
                                    </Space>
                                </div>
                            </Col>

                            <Col xs={24} md={12}>
                                <div>
                                    <Title level={5} style={{
                                        color: '#262626',
                                        marginBottom: '16px',
                                        display: 'flex',
                                        alignItems: 'center'
                                    }}>
                                        <GiftOutlined style={{
                                            color: '#1890ff',
                                            marginRight: '8px'
                                        }} />
                                        Tiện ích & Dịch vụ
                                    </Title>
                                    <div style={{
                                        background: '#fafafa',
                                        padding: '16px',
                                        borderRadius: '8px',
                                        border: '1px solid #f0f0f0'
                                    }}>
                                        <Space wrap>
                                            {selectedBooking.roomOption.additionalServices?.map((service, index) => (
                                                <Tag
                                                    key={index}
                                                    style={{
                                                        background: service.included ? '#e6f7ff' : '#fff7e6',
                                                        color: service.included ? '#1890ff' : '#fa8c16',
                                                        border: 'none',
                                                        borderRadius: '6px',
                                                        padding: '4px 8px',
                                                        fontSize: '13px',
                                                        display: 'flex',
                                                        alignItems: 'center'
                                                    }}
                                                >
                                                    <span style={{ marginRight: '6px' }}>{service.icon}</span>
                                                    {service.name}
                                                    {service.price && !service.included && (
                                                        <Text style={{
                                                            marginLeft: '4px',
                                                            fontSize: '12px',
                                                            opacity: 0.8
                                                        }}>
                                                            ({service.price})
                                                        </Text>
                                                    )}
                                                </Tag>
                                            ))}
                                        </Space>
                                    </div>
                                </div>
                            </Col>
                        </Row>

                        {/* Policies */}
                        <Divider style={{ margin: '24px 0' }} />
                        <Row gutter={[24, 16]}>
                            <Col xs={24} md={12}>
                                <div style={{
                                    background: '#f6ffed',
                                    border: '1px solid #b7eb8f',
                                    borderRadius: '8px',
                                    padding: '16px'
                                }}>
                                    <Title level={5} style={{
                                        color: '#389e0d',
                                        marginBottom: '8px',
                                        display: 'flex',
                                        alignItems: 'center'
                                    }}>
                                        <SafetyOutlined style={{ marginRight: '8px' }} />
                                        Chính sách hủy phòng
                                    </Title>
                                    <Text style={{ color: '#595959' }}>
                                        {selectedBooking.roomOption.cancellationPolicy.description}
                                    </Text>
                                </div>
                            </Col>
                            <Col xs={24} md={12}>
                                <div style={{
                                    background: '#e6f7ff',
                                    border: '1px solid #91d5ff',
                                    borderRadius: '8px',
                                    padding: '16px'
                                }}>
                                    <Title level={5} style={{
                                        color: '#1890ff',
                                        marginBottom: '8px',
                                        display: 'flex',
                                        alignItems: 'center'
                                    }}>
                                        <CreditCardOutlined style={{ marginRight: '8px' }} />
                                        Chính sách thanh toán
                                    </Title>
                                    <Text style={{ color: '#595959' }}>
                                        {selectedBooking.roomOption.paymentPolicy.description}
                                    </Text>
                                </div>
                            </Col>
                        </Row>

                        {selectedBooking.review && (
                            <>
                                <Divider style={{ margin: '24px 0' }} />
                                <div style={{
                                    background: '#fffbe6',
                                    border: '1px solid #ffe58f',
                                    borderRadius: '8px',
                                    padding: '16px'
                                }}>
                                    <Title level={5} style={{
                                        color: '#d48806',
                                        marginBottom: '12px',
                                        display: 'flex',
                                        alignItems: 'center'
                                    }}>
                                        <StarOutlined style={{ marginRight: '8px' }} />
                                        Đánh giá của bạn
                                    </Title>
                                    <Rate disabled defaultValue={selectedBooking.rating} style={{ marginBottom: '8px' }} />
                                    <Paragraph style={{
                                        margin: 0,
                                        color: '#595959',
                                        fontStyle: 'italic'
                                    }}>
                                        "{selectedBooking.review}"
                                    </Paragraph>
                                </div>
                            </>
                        )}

                        {/* Confirmation Code */}
                        <div style={{
                            marginTop: '24px',
                            textAlign: 'center',
                            padding: '16px',
                            background: 'linear-gradient(135deg, #f0f2f5 0%, #fafafa 100%)',
                            borderRadius: '8px'
                        }}>
                            <Text style={{ color: '#8c8c8c', fontSize: '14px' }}>
                                Mã xác nhận đặt phòng
                            </Text>
                            <br />
                            <Text style={{
                                fontSize: '18px',
                                fontWeight: 600,
                                letterSpacing: '2px'
                            }}>
                                {selectedBooking.confirmationCode}
                            </Text>
                        </div>
                    </div>
                )}
            </Modal>
        </div>
    );
};

export default React.memo(BookingManagement);
