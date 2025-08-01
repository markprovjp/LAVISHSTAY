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
    Rate,
    Divider,
    Modal,
    Timeline,
    Image,
    Empty,
    Input,
    DatePicker,
    Skeleton
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
    SafetyOutlined,
    MailOutlined,
    IdcardOutlined
} from '@ant-design/icons';
import dayjs from 'dayjs';
import { RoomOption } from '../../mirage/roomoption';
import bookingService, { Booking as ApiBooking } from '../../services/bookingService';
import { getAmenityIcon, getCategoryColor } from '../../constants/Icons';
import { Carousel } from 'antd';
import ProDescriptions from '@ant-design/pro-descriptions';
const { Title, Text, Paragraph } = Typography;
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
}


const BookingManagement: React.FC = () => {
    const [activeTab, setActiveTab] = useState('all');
    const [selectedBooking, setSelectedBooking] = useState<Booking | null>(null);
    const [isDetailModalVisible, setIsDetailModalVisible] = useState(false);
    const [searchText, setSearchText] = useState('');
    const [dateRange, setDateRange] = useState<[dayjs.Dayjs | null, dayjs.Dayjs | null] | null>(null);
    const [bookings, setBookings] = useState<Booking[]>([]);
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        setLoading(true);
        bookingService.getUserBookings()
            .then((data) => {
                // Lọc trùng booking_code, chỉ lấy booking đầu tiên cho mỗi booking_code
                const uniqueBookings: Booking[] = [];
                const seenCodes = new Set();
                for (const b of data) {
                    if (!seenCodes.has(b.booking_code)) {
                        uniqueBookings.push(b);
                        seenCodes.add(b.booking_code);
                    }
                }
                setBookings(uniqueBookings);
            })
            .catch(() => setBookings([]))
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

    const handleCancelBooking = useCallback((bookingId: number) => {
        Modal.confirm({
            title: 'Xác nhận hủy đặt phòng',
            content: 'Bạn có chắc chắn muốn hủy đặt phòng này? Hành động này không thể hoàn tác.',
            okText: 'Hủy đặt phòng',
            cancelText: 'Không',
            okType: 'danger',
            onOk() {
                // TODO: Gọi API hủy booking
                console.log('Cancel booking:', bookingId);
            }
        });
    }, []);

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
                                        onClick={() => showBookingDetail(booking)}
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
                                                    <Text style={{ fontSize: 13, color: '#888', padding: '4px 8px', borderRadius: 4, display: 'inline-block', marginTop: 8 }}>Mã: {booking.booking_code}</Text>
                                                </div>
                                            </Col>
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
