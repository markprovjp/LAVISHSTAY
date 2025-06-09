import React, { useState } from 'react';
import {
    Card,
    Row,
    Col,
    Typography,
    Tag,
    Button,
    Space,
    Tabs,
    Avatar,
    Rate,
    Divider,
    Badge,
    Tooltip,
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
    EnvironmentOutlined,
    UserOutlined,
    PhoneOutlined,
    CreditCardOutlined,
    EyeOutlined,
    EditOutlined,
    DeleteOutlined,
    StarOutlined,
    CheckCircleOutlined,
    ExclamationCircleOutlined,
    CloseCircleOutlined,
    SearchOutlined,
    DownloadOutlined
} from '@ant-design/icons';
import { useSelector } from 'react-redux';
import { RootState } from '../../store';
import dayjs from 'dayjs';
import { AmenityUtils } from '../../constants/amenities';

const { Title, Text, Paragraph } = Typography;
const { TabPane } = Tabs;
const { Search } = Input;
const { RangePicker } = DatePicker;

interface Booking {
    id: string;
    hotelName: string;
    roomType: string;
    roomImage: string;
    checkIn: string;
    checkOut: string;
    guests: number;
    totalPrice: number;
    status: 'confirmed' | 'completed' | 'cancelled' | 'pending';
    bookingDate: string;
    paymentMethod: string;
    confirmationCode: string;
    amenities: string[];
    rating?: number;
    review?: string;
    address: string;
    contact: string;
    policies: string[];
}

const BookingManagement: React.FC = () => {
    const { isDarkMode } = useSelector((state: RootState) => state.theme);
    const [activeTab, setActiveTab] = useState('all');
    const [selectedBooking, setSelectedBooking] = useState<Booking | null>(null);
    const [isDetailModalVisible, setIsDetailModalVisible] = useState(false);
    const [searchText, setSearchText] = useState('');
    const [dateRange, setDateRange] = useState<[dayjs.Dayjs | null, dayjs.Dayjs | null] | null>(null);

    // Mock booking data
    const bookings: Booking[] = [
        {
            id: 'BK001',
            hotelName: 'LavishStay Deluxe Hà Nội',
            roomType: 'Superior Double Room',
            roomImage: 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=400&h=300&fit=crop',
            checkIn: '2024-12-15',
            checkOut: '2024-12-18',
            guests: 2,
            totalPrice: 3500000,
            status: 'confirmed',
            bookingDate: '2024-12-01',
            paymentMethod: 'Thẻ tín dụng',
            confirmationCode: 'LS2024001',
            amenities: ['WiFi miễn phí', 'Điều hòa không khí', 'TV màn hình phẳng', 'Minibar'],
            address: '123 Hoàn Kiếm, Hà Nội',
            contact: '+84 24 3825 1234',
            policies: ['Hủy miễn phí trước 24h', 'Không hút thuốc', 'Không thú cưng']
        },
        {
            id: 'BK002',
            hotelName: 'LavishStay Beach Resort Đà Nẵng',
            roomType: 'Ocean View Suite',
            roomImage: 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=400&h=300&fit=crop',
            checkIn: '2024-11-10',
            checkOut: '2024-11-13',
            guests: 3,
            totalPrice: 5200000,
            status: 'completed',
            bookingDate: '2024-10-25',
            paymentMethod: 'Chuyển khoản',
            confirmationCode: 'LS2024002',
            amenities: ['Tầm nhìn cảnh biển', 'Bể bơi riêng', 'Spa & Massage', 'WiFi miễn phí'],
            rating: 5,
            review: 'Khách sạn tuyệt vời! View biển đẹp, dịch vụ chuyên nghiệp.',
            address: '456 Nguyễn Văn Thoại, Đà Nẵng',
            contact: '+84 236 3851 234',
            policies: ['Hủy miễn phí trước 48h', 'Cho phép thú cưng', 'Bữa sáng bao gồm']
        },
        {
            id: 'BK003',
            hotelName: 'LavishStay City Center TP.HCM',
            roomType: 'Executive Room',
            roomImage: 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=400&h=300&fit=crop',
            checkIn: '2024-10-05',
            checkOut: '2024-10-07',
            guests: 1,
            totalPrice: 2800000,
            status: 'cancelled',
            bookingDate: '2024-09-20',
            paymentMethod: 'Ví điện tử',
            confirmationCode: 'LS2024003',
            amenities: ['Phòng chờ thương gia', 'WiFi miễn phí', 'Gym 24/7', 'Bữa sáng miễn phí'],
            address: '789 Nguyễn Huệ, Q1, TP.HCM',
            contact: '+84 28 3824 1234',
            policies: ['Hủy có phí sau 24h', 'Không hút thuốc', 'Check-in linh hoạt']
        }
    ];

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'confirmed': return 'blue';
            case 'completed': return 'green';
            case 'cancelled': return 'red';
            case 'pending': return 'orange';
            default: return 'default';
        }
    };

    const getStatusText = (status: string) => {
        switch (status) {
            case 'confirmed': return 'Đã xác nhận';
            case 'completed': return 'Hoàn thành';
            case 'cancelled': return 'Đã hủy';
            case 'pending': return 'Chờ xử lý';
            default: return status;
        }
    };

    const getStatusIcon = (status: string) => {
        switch (status) {
            case 'confirmed': return <CheckCircleOutlined />;
            case 'completed': return <CheckCircleOutlined />;
            case 'cancelled': return <CloseCircleOutlined />;
            case 'pending': return <ExclamationCircleOutlined />;
            default: return <ClockCircleOutlined />;
        }
    };

    const filterBookings = (bookings: Booking[], tab: string) => {
        let filtered = bookings;

        // Filter by tab
        if (tab !== 'all') {
            filtered = filtered.filter(booking => booking.status === tab);
        }

        // Filter by search text
        if (searchText) {
            filtered = filtered.filter(booking =>
                booking.hotelName.toLowerCase().includes(searchText.toLowerCase()) ||
                booking.confirmationCode.toLowerCase().includes(searchText.toLowerCase()) ||
                booking.roomType.toLowerCase().includes(searchText.toLowerCase())
            );
        }

        // Filter by date range
        if (dateRange && dateRange[0] && dateRange[1]) {
            filtered = filtered.filter(booking => {
                const bookingDate = dayjs(booking.bookingDate);
                return bookingDate.isAfter(dateRange[0]) && bookingDate.isBefore(dateRange[1]);
            });
        }

        return filtered;
    };

    const showBookingDetail = (booking: Booking) => {
        setSelectedBooking(booking);
        setIsDetailModalVisible(true);
    };

    const handleCancelBooking = (bookingId: string) => {
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
    };

    const filteredBookings = filterBookings(bookings, activeTab);

    const tabItems = [
        { key: 'all', label: `Tất cả (${bookings.length})` },
        { key: 'confirmed', label: `Đã xác nhận (${bookings.filter(b => b.status === 'confirmed').length})` },
        { key: 'completed', label: `Hoàn thành (${bookings.filter(b => b.status === 'completed').length})` },
        { key: 'cancelled', label: `Đã hủy (${bookings.filter(b => b.status === 'cancelled').length})` },
        { key: 'pending', label: `Chờ xử lý (${bookings.filter(b => b.status === 'pending').length})` }
    ];

    return (
        <div className="booking-management-container">
            {/* Header */}
            <div className="booking-header">
                <Row align="middle" justify="space-between">
                    <Col>
                        <Title level={2} className="page-title">
                            <CalendarOutlined className="title-icon" />
                            Quản lý đặt phòng
                        </Title>
                        <Text className="page-subtitle">
                            Theo dõi và quản lý các đặt phòng của bạn
                        </Text>
                    </Col>
                    <Col>
                        <Button type="primary" icon={<DownloadOutlined />} size="large">
                            Xuất báo cáo
                        </Button>
                    </Col>
                </Row>
            </div>

            {/* Filters */}
            <Card className="filter-card" bordered={false}>
                <Row gutter={[16, 16]} align="middle">
                    <Col xs={24} sm={12} md={8}>
                        <Search
                            placeholder="Tìm theo tên khách sạn, mã đặt phòng..."
                            value={searchText}
                            onChange={(e) => setSearchText(e.target.value)}
                            prefix={<SearchOutlined />}
                            size="large"
                        />
                    </Col>
                    <Col xs={24} sm={12} md={8}>
                        <RangePicker
                            placeholder={['Từ ngày', 'Đến ngày']}
                            value={dateRange}
                            onChange={setDateRange}
                            size="large"
                            style={{ width: '100%' }}
                        />
                    </Col>
                    <Col xs={24} md={8}>
                        <Space>
                            <Text strong>Tổng: {filteredBookings.length} đặt phòng</Text>
                        </Space>
                    </Col>
                </Row>
            </Card>

            {/* Booking List */}
            <Card className="booking-list-card" bordered={false}>
                <Tabs
                    activeKey={activeTab}
                    onChange={setActiveTab}
                    className="booking-tabs"
                    items={tabItems}
                />

                <div className="booking-items">
                    {filteredBookings.length > 0 ? (
                        <Row gutter={[24, 24]}>
                            {filteredBookings.map((booking) => (
                                <Col xs={24} key={booking.id}>
                                    <Card className="booking-item-card" bordered={false}>
                                        <Row gutter={[16, 16]} align="middle">
                                            {/* Hotel Image */}
                                            <Col xs={24} sm={6} md={4}>
                                                <div className="booking-image-container">
                                                    <Image
                                                        src={booking.roomImage}
                                                        alt={booking.hotelName}
                                                        className="booking-image"
                                                        preview={false}
                                                    />
                                                    <Badge
                                                        status={booking.status === 'completed' ? 'success' :
                                                            booking.status === 'confirmed' ? 'processing' :
                                                                booking.status === 'cancelled' ? 'error' : 'warning'}
                                                        className="booking-status-badge"
                                                    />
                                                </div>
                                            </Col>

                                            {/* Booking Info */}
                                            <Col xs={24} sm={12} md={14}>
                                                <div className="booking-info">
                                                    <div className="booking-main-info">
                                                        <Title level={4} className="hotel-name">
                                                            {booking.hotelName}
                                                        </Title>
                                                        <Text className="room-type">{booking.roomType}</Text>

                                                        <div className="booking-details">
                                                            <Space direction="vertical" size={4}>
                                                                <div className="booking-detail-item">
                                                                    <CalendarOutlined className="detail-icon" />
                                                                    <Text>
                                                                        {dayjs(booking.checkIn).format('DD/MM/YYYY')} - {dayjs(booking.checkOut).format('DD/MM/YYYY')}
                                                                    </Text>
                                                                    <Text className="detail-duration">
                                                                        ({dayjs(booking.checkOut).diff(booking.checkIn, 'day')} đêm)
                                                                    </Text>
                                                                </div>

                                                                <div className="booking-detail-item">
                                                                    <UserOutlined className="detail-icon" />
                                                                    <Text>{booking.guests} khách</Text>
                                                                </div>

                                                                <div className="booking-detail-item">
                                                                    <EnvironmentOutlined className="detail-icon" />
                                                                    <Text>{booking.address}</Text>
                                                                </div>
                                                            </Space>
                                                        </div>

                                                        {/* Amenities */}
                                                        <div className="booking-amenities">
                                                            {AmenityUtils.formatAmenitiesForDisplay(booking.amenities.slice(0, 3)).map((amenity) => (
                                                                <Tag key={amenity.key} className="amenity-tag">
                                                                    {amenity.icon}
                                                                    {amenity.name}
                                                                </Tag>
                                                            ))}
                                                            {booking.amenities.length > 3 && (
                                                                <Tag className="amenity-more">+{booking.amenities.length - 3}</Tag>
                                                            )}
                                                        </div>
                                                    </div>
                                                </div>
                                            </Col>

                                            {/* Price & Actions */}
                                            <Col xs={24} sm={6} md={6}>
                                                <div className="booking-actions">
                                                    <div className="booking-price">
                                                        <Text className="price-label">Tổng tiền</Text>
                                                        <Title level={3} className="price-amount">
                                                            {booking.totalPrice.toLocaleString('vi-VN')}₫
                                                        </Title>
                                                    </div>

                                                    <div className="booking-status">
                                                        <Tag
                                                            color={getStatusColor(booking.status)}
                                                            icon={getStatusIcon(booking.status)}
                                                            className="status-tag"
                                                        >
                                                            {getStatusText(booking.status)}
                                                        </Tag>
                                                    </div>

                                                    <div className="booking-code">
                                                        <Text className="confirmation-code">
                                                            Mã: {booking.confirmationCode}
                                                        </Text>
                                                    </div>

                                                    {booking.rating && (
                                                        <div className="booking-rating">
                                                            <Rate disabled defaultValue={booking.rating} size="small" />
                                                        </div>
                                                    )}

                                                    <div className="action-buttons">
                                                        <Space direction="vertical" style={{ width: '100%' }}>
                                                            <Button
                                                                type="primary"
                                                                icon={<EyeOutlined />}
                                                                onClick={() => showBookingDetail(booking)}
                                                                block
                                                            >
                                                                Chi tiết
                                                            </Button>

                                                            {booking.status === 'confirmed' && (
                                                                <Button
                                                                    danger
                                                                    icon={<DeleteOutlined />}
                                                                    onClick={() => handleCancelBooking(booking.id)}
                                                                    block
                                                                >
                                                                    Hủy đặt phòng
                                                                </Button>
                                                            )}

                                                            {booking.status === 'completed' && !booking.rating && (
                                                                <Button
                                                                    icon={<StarOutlined />}
                                                                    block
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
                        />
                    )}
                </div>
            </Card>

            {/* Booking Detail Modal */}
            <Modal
                title={
                    <div className="modal-title">
                        <CalendarOutlined />
                        Chi tiết đặt phòng
                    </div>
                }
                open={isDetailModalVisible}
                onCancel={() => setIsDetailModalVisible(false)}
                footer={null}
                width={800}
                className="booking-detail-modal"
            >
                {selectedBooking && (
                    <div className="booking-detail-content">
                        <Row gutter={[24, 24]}>
                            <Col xs={24} md={12}>
                                <Image
                                    src={selectedBooking.roomImage}
                                    alt={selectedBooking.hotelName}
                                    style={{ width: '100%', borderRadius: '8px' }}
                                />
                            </Col>

                            <Col xs={24} md={12}>
                                <Space direction="vertical" size={16} style={{ width: '100%' }}>
                                    <div>
                                        <Title level={3}>{selectedBooking.hotelName}</Title>
                                        <Text type="secondary">{selectedBooking.roomType}</Text>
                                    </div>

                                    <div>
                                        <Tag
                                            color={getStatusColor(selectedBooking.status)}
                                            icon={getStatusIcon(selectedBooking.status)}
                                            style={{ fontSize: '14px', padding: '4px 12px' }}
                                        >
                                            {getStatusText(selectedBooking.status)}
                                        </Tag>
                                    </div>

                                    <Divider />

                                    <Timeline
                                        items={[
                                            {
                                                dot: <CalendarOutlined className="timeline-icon" />,
                                                children: (
                                                    <div>
                                                        <Text strong>Ngày đặt phòng</Text>
                                                        <br />
                                                        <Text>{dayjs(selectedBooking.bookingDate).format('DD/MM/YYYY HH:mm')}</Text>
                                                    </div>
                                                )
                                            },
                                            {
                                                dot: <ClockCircleOutlined className="timeline-icon" />,
                                                children: (
                                                    <div>
                                                        <Text strong>Check-in / Check-out</Text>
                                                        <br />
                                                        <Text>
                                                            {dayjs(selectedBooking.checkIn).format('DD/MM/YYYY')} - {dayjs(selectedBooking.checkOut).format('DD/MM/YYYY')}
                                                        </Text>
                                                    </div>
                                                )
                                            },
                                            {
                                                dot: <CreditCardOutlined className="timeline-icon" />,
                                                children: (
                                                    <div>
                                                        <Text strong>Thanh toán</Text>
                                                        <br />
                                                        <Text>{selectedBooking.paymentMethod}</Text>
                                                        <br />
                                                        <Text strong>{selectedBooking.totalPrice.toLocaleString('vi-VN')}₫</Text>
                                                    </div>
                                                )
                                            }
                                        ]}
                                    />
                                </Space>
                            </Col>
                        </Row>

                        <Divider />

                        <Row gutter={[24, 24]}>
                            <Col xs={24} md={12}>
                                <Title level={5}>Thông tin liên hệ</Title>
                                <Space direction="vertical">
                                    <div>
                                        <EnvironmentOutlined /> {selectedBooking.address}
                                    </div>
                                    <div>
                                        <PhoneOutlined /> {selectedBooking.contact}
                                    </div>
                                </Space>
                            </Col>

                            <Col xs={24} md={12}>
                                <Title level={5}>Tiện nghi phòng</Title>
                                <div className="amenities-grid">
                                    {AmenityUtils.formatAmenitiesForDisplay(selectedBooking.amenities).map((amenity) => (
                                        <Tag key={amenity.key} className="amenity-tag">
                                            {amenity.icon}
                                            {amenity.name}
                                        </Tag>
                                    ))}
                                </div>
                            </Col>
                        </Row>

                        {selectedBooking.review && (
                            <>
                                <Divider />
                                <div>
                                    <Title level={5}>Đánh giá của bạn</Title>
                                    <Rate disabled defaultValue={selectedBooking.rating} />
                                    <Paragraph style={{ marginTop: 8 }}>
                                        {selectedBooking.review}
                                    </Paragraph>
                                </div>
                            </>
                        )}
                    </div>
                )}
            </Modal>
        </div>
    );
};

export default BookingManagement;
