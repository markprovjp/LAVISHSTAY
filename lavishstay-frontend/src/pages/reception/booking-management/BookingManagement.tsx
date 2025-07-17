import React, { useState } from 'react';
import {
    Layout,
    Card,
    Button,
    Tag,
    Space,
    Modal,
    Row,
    Col,
    Statistic,
    Typography,
    Alert,
    Table,
    Avatar,
    Tooltip,
} from 'antd';
import type { TableColumnsType } from 'antd';
import {
    EyeOutlined,
    DeleteOutlined,
    CalendarOutlined,
    ClockCircleOutlined,
    DollarOutlined,
    UserOutlined,
    PhoneOutlined,
    MailOutlined,
    TeamOutlined,
    SmileOutlined,
    HomeOutlined,
} from '@ant-design/icons';
import {
    useGetBookings,
    useGetBookingStatistics,
    useCancelBooking,
} from '../../../hooks/useReception';
import {
    Booking,
    BookingFilters
} from '../../../types/booking';
import BookingDetailModal from './BookingDetailModal';
import BookingFilterBar from '../../../components/booking-management/BookingFilterBar';
import ErrorBoundary from '../../../components/common/ErrorBoundary';
import dayjs from 'dayjs';
import 'dayjs/locale/vi';

dayjs.locale('vi');

const { Content } = Layout;
const { Title, Text } = Typography;

// Status configurations
const bookingStatusConfig = {
    pending: { color: 'orange', text: 'Chờ xác nhận' },
    confirmed: { color: 'blue', text: 'Đã xác nhận' },
    cancelled: { color: 'red', text: 'Đã hủy' },
    completed: { color: 'green', text: 'Hoàn thành' },
};

// Table columns interface - completely flattened
interface BookingTableData {
    key: React.Key;
    booking_id: number;
    booking_code: string;
    guest_name: string;
    guest_email: string;
    guest_phone: string;
    guest_count: number;
    adults: number;
    num_children: number;
    children_age?: number[] | string; // Support both array from new API and string for backward compatibility
    total_price_vnd: number;
    status: string;
    check_in_date: string;
    check_out_date: string;
    created_at: string;
    updated_at: string;
    // Multi-room booking fields
    room_names: string; // Aggregated room names
    room_type_names: string; // Aggregated room type names
    total_rooms: number; // Total number of rooms
    // Payment and representative info
    payment_status: string;
    payment_type: string;
    payment_amount: number;
    transaction_id: string;
    representative_name: string;
    representative_phone: string;
    representative_email: string;
    option_names: string; // New field for option names
    // Compatibility fields
    room_id?: number | null;
    room_name?: string;
    room_id_display?: number | null;
}

const BookingManagement: React.FC = () => {
    const [filters, setFilters] = useState<BookingFilters>({});
    const [selectedBooking, setSelectedBooking] = useState<Booking | null>(null);
    const [isDetailModalVisible, setIsDetailModalVisible] = useState(false);

    // API hooks
    const { data: bookingsData, isLoading, refetch } = useGetBookings(filters);
    const { data: statisticsData } = useGetBookingStatistics();

    const cancelBookingMutation = useCancelBooking();

    // Safely extract and validate bookings data
    const bookings = React.useMemo(() => {
        if (!bookingsData?.data) return [];

        // Handle both array and object responses
        let rawBookings = Array.isArray(bookingsData.data) ? bookingsData.data : [];

        // If data is an object with data property, extract it
        if (typeof bookingsData.data === 'object' && !Array.isArray(bookingsData.data) && bookingsData.data.data) {
            rawBookings = Array.isArray(bookingsData.data.data) ? bookingsData.data.data : [];
        }

        // Filter out invalid bookings and remove duplicates
        const validBookings = rawBookings.filter((booking: any) =>
            booking &&
            typeof booking === 'object' &&
            (booking.booking_id || booking.id) &&
            booking.booking_code
        );

        // Remove duplicates based on ID and booking_code
        const uniqueBookings = validBookings.reduce((acc: any[], current: any) => {
            const bookingId = current.booking_id || current.id;
            const existingIndex = acc.findIndex(booking =>
                (booking.booking_id || booking.id) === bookingId && booking.booking_code === current.booking_code
            );
            if (existingIndex === -1) {
                acc.push(current);
            }
            return acc;
        }, []);

        // Map with safe defaults matching actual schema
        return uniqueBookings.map((booking: any, index: number) => {
            // Map database fields to expected interface
            const bookingId = booking.booking_id || booking.id;
            const totalAmount = booking.total_price_vnd || booking.total_amount || 0;

            // Extract aggregated room data from new backend API
            const roomNames = booking.room_names || '';
            const roomTypeNames = booking.room_type_names || '';
            const totalRooms = booking.total_rooms || 1;

            // ULTRA SAFE processing of children and adults values
            let safeChildren = 0;
            let safeAdults = 1;

            try {
                // Use aggregated values from backend if available
                const backendAdults = booking.total_adults_from_rooms || booking.adults;
                const backendChildren = booking.total_children_from_rooms || booking.children;

                if (backendChildren !== null && backendChildren !== undefined) {
                    const childrenVal = Number(backendChildren);
                    safeChildren = isNaN(childrenVal) ? 0 : Math.max(0, Math.floor(childrenVal));
                }

                if (backendAdults !== null && backendAdults !== undefined) {
                    const adultsVal = Number(backendAdults);
                    safeAdults = isNaN(adultsVal) ? 1 : Math.max(1, Math.floor(adultsVal));
                }
            } catch (error) {
                console.error('Error processing children/adults values:', error);
                safeChildren = 0;
                safeAdults = 1;
            }

            const processedBooking = {
                // Use booking_id as primary key
                booking_id: bookingId,
                id: bookingId, // Compatibility
                key: `booking-${bookingId}-${index}`,

                // Core booking fields from schema - all primitives
                booking_code: String(booking.booking_code || ''),
                user_id: booking.user_id ? Number(booking.user_id) : null,
                option_id: booking.option_id ? Number(booking.option_id) : null,
                check_in_date: String(booking.check_in_date || ''),
                check_out_date: String(booking.check_out_date || ''),
                total_price_vnd: Number(totalAmount) || 0,
                total_amount: Number(totalAmount) || 0, // Compatibility
                guest_count: Number(booking.guest_count) || 1,

                // Use the ultra-safe processed values
                adults: safeAdults,
                num_children: safeChildren,

                status: String(booking.status || 'pending'),
                quantity: Number(booking.quantity) || totalRooms,
                created_at: String(booking.created_at || ''),
                updated_at: String(booking.updated_at || ''),

                // Guest information (optional in schema) - all strings
                guest_name: String(booking.guest_name || ''),
                guest_email: String(booking.guest_email || ''),
                guest_phone: String(booking.guest_phone || ''),

                // Room information from aggregated data
                room_names: String(roomNames),
                room_type_names: String(roomTypeNames),
                total_rooms: Number(totalRooms),

                // Payment and representative info
                payment_status: String(booking.payment_status || 'pending'),
                payment_type: String(booking.payment_type || ''),
                payment_amount: Number(booking.payment_amount || 0),
                transaction_id: String(booking.transaction_id || ''),
                representative_name: String(booking.representative_name || ''),
                representative_phone: String(booking.representative_phone || ''),
                representative_email: String(booking.representative_email || ''),
                option_names: String(booking.option_names || ''), // New field for option names
                // Compatibility fields
                room_id: booking.room_id ? Number(booking.room_id) : null,
                room_name: String(roomNames.split(',')[0] || ''), // First room for compatibility
                room_id_display: booking.room_id ? Number(booking.room_id) : null,
                booking_status: String(booking.status || 'pending'),
            };

            console.log('Final processed booking:', processedBooking);

            return processedBooking;
        });
    }, [bookingsData]);

    const statistics = statisticsData?.data || {};

    // Handle search
    const handleSearch = (searchFilters: BookingFilters) => {
        setFilters(searchFilters);
    };

    // Handle cancel booking
    const handleCancelBooking = (bookingId: number) => {
        Modal.confirm({
            title: 'Xác nhận hủy đặt phòng',
            content: 'Bạn có chắc chắn muốn hủy đặt phòng này không?',
            okText: 'Hủy đặt phòng',
            cancelText: 'Đóng',
            okType: 'danger',
            onOk: async () => {
                try {
                    await cancelBookingMutation.mutateAsync(bookingId);
                    refetch();
                } catch (error) {
                    console.error('Error canceling booking:', error);
                }
            },
        });
    };

    // Table columns definition
    const columns: TableColumnsType<BookingTableData> = [
        {
            title: 'Mã đặt phòng',
            dataIndex: 'booking_code',
            key: 'booking_code',
            width: 140,
            fixed: 'left',
            render: (code: string) => (
                <div style={{ display: 'flex', alignItems: 'center' }}>
                    <div style={{
                        width: 32,
                        height: 32,
                        borderRadius: '6px',
                        backgroundColor: '#e6f4ff',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        marginRight: 10,
                    }}>
                        <CalendarOutlined style={{ color: '#1890ff', fontSize: '14px' }} />
                    </div>
                    <div>
                        <Text strong style={{ color: '#1890ff', fontSize: '13px' }}>
                            {code}
                        </Text>
                        <div style={{ fontSize: '11px', color: '#8c8c8c' }}>
                            Đặt phòng
                        </div>
                    </div>
                </div>
            ),
        },
        {
            title: 'Thông tin khách',
            key: 'guest',
            width: 200,
            render: (_, record) => (
                <div style={{ display: 'flex', alignItems: 'center' }}>
                    <Avatar
                        size={40}
                        icon={<UserOutlined />}
                        style={{
                            backgroundColor: '#f56a00',
                            marginRight: 12,
                            flexShrink: 0
                        }}
                    />
                    <div style={{ flex: 1, minWidth: 0 }}>
                        <div style={{ fontWeight: 600, fontSize: '14px', color: '#262626', marginBottom: 2 }}>
                            {record.guest_name}
                        </div>
                        <div style={{ fontSize: '12px', color: '#8c8c8c', marginBottom: 1 }}>
                            <PhoneOutlined style={{ marginRight: 4, fontSize: '11px' }} />
                            {record.guest_phone}
                        </div>
                        <div style={{ fontSize: '12px', color: '#8c8c8c' }}>
                            <MailOutlined style={{ marginRight: 4, fontSize: '11px' }} />
                            <span style={{ overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap', display: 'block' }}>
                                {record.guest_email}
                            </span>
                        </div>
                    </div>
                </div>
            ),
        },
        {
            title: 'Thời gian lưu trú',
            key: 'dates',
            width: 160,
            render: (_, record) => {
                const checkIn = dayjs(record.check_in_date);
                const checkOut = dayjs(record.check_out_date);
                const nights = checkOut.diff(checkIn, 'day');

                return (
                    <div>
                        <div style={{
                            display: 'flex',
                            alignItems: 'center',
                            marginBottom: 4,
                            padding: '4px 8px',
                            backgroundColor: '#f6ffed',
                            borderRadius: '4px',
                            fontSize: '12px',
                            fontWeight: 500,
                            color: '#52c41a'
                        }}>
                            <CalendarOutlined style={{ marginRight: 4 }} />
                            {checkIn.format('DD/MM')} - {checkOut.format('DD/MM')}
                        </div>
                        <div style={{
                            fontSize: '12px',
                            color: '#8c8c8c',
                            textAlign: 'center',
                            fontWeight: 500
                        }}>
                            {nights} đêm
                        </div>
                    </div>
                );
            },
        },
        {
            title: 'Tổng khách',
            key: 'total_guests',
            width: 120,
            align: 'center',
            render: (_, record) => {
                const adults = record.adults || 0;
                const children = record.num_children || 0;

                return (
                    <div style={{ textAlign: 'center' }}>
                        <div style={{
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            gap: 4
                        }}>
                            <TeamOutlined style={{ color: '#1890ff', fontSize: '14px' }} />
                            <Text strong style={{ fontSize: '13px' }}>
                                {adults} NL {children > 0 ? `+ ${children} TE` : ''}
                            </Text>
                        </div>
                    </div>
                );
            },
        },
        {
            title: 'Gói phòng',
            key: 'option_names',
            width: 200,
            render: (_, record) => {
                const options = record.option_names ? record.option_names.split(',').map(name => name.trim()) : [];
                return (
                    <div style={{ display: 'flex', alignItems: 'center' }}>
                        <div style={{
                            width: 32,
                            height: 32,
                            borderRadius: '6px',
                            backgroundColor: '#f6ffed',
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            marginRight: 8,
                        }}>
                            <HomeOutlined style={{ color: '#52c41a', fontSize: '14px' }} />
                        </div>
                        <div>
                            <div style={{ fontWeight: 600, fontSize: '13px', color: '#262626', marginBottom: 2 }}>
                                {options.length > 0 ? options.join(', ') : 'Chưa chọn tùy chọn'}
                            </div>
                        </div>
                    </div>
                );
            },
        },
        {
            title: 'Số phòng',
            key: 'total_rooms',
            width: 100,
            align: 'center',
            render: (_, record) => (
                <div style={{ textAlign: 'center' }}>
                    <div style={{
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        gap: 4
                    }}>
                        <HomeOutlined style={{ color: '#52c41a', fontSize: '14px' }} />
                        <Text strong style={{ fontSize: '13px' }}>{record.total_rooms || 1}</Text>
                    </div>
                </div>
            ),
        },
        {
            title: 'Phòng',
            key: 'room',
            width: 200,
            render: (_, record) => {
                if (record.total_rooms > 1) {
                    // Multiple rooms
                    const roomNames = record.room_names ? record.room_names.split(',').map(name => name.trim()) : [];
                    const roomTypes = record.room_type_names ? record.room_type_names.split(',').map(type => type.trim()) : [];

                    return (
                        <div style={{ display: 'flex', alignItems: 'center' }}>
                            <div style={{
                                width: 32,
                                height: 32,
                                borderRadius: '6px',
                                backgroundColor: '#f6ffed',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                                marginRight: 8,
                            }}>
                                <HomeOutlined style={{ color: '#52c41a', fontSize: '14px' }} />
                            </div>
                            <div>
                                <div style={{ fontWeight: 600, fontSize: '13px', color: '#262626', marginBottom: 2 }}>
                                    {record.total_rooms} phòng
                                </div>
                                <div style={{ fontSize: '11px', color: '#8c8c8c', maxWidth: '150px' }}>
                                    {roomNames.length > 0 ? roomNames.slice(0, 2).join(', ') : 'Chưa chọn phòng'}
                                    {roomNames.length > 2 && `... +${roomNames.length - 2}`}
                                </div>
                                <div style={{ fontSize: '10px', color: '#8c8c8c', maxWidth: '150px' }}>
                                    {roomTypes.length > 0 ? roomTypes.slice(0, 2).join(', ') : ''}
                                    {roomTypes.length > 2 && `... +${roomTypes.length - 2}`}
                                </div>
                            </div>
                        </div>
                    );
                } else if (record.room_name || record.room_names) {
                    // Single room
                    const roomName = record.room_name || (record.room_names ? record.room_names.split(',')[0].trim() : '');
                    const roomType = record.room_type_names ? record.room_type_names.split(',')[0].trim() : '';

                    return (
                        <div style={{ display: 'flex', alignItems: 'center' }}>
                            <div style={{
                                width: 32,
                                height: 32,
                                borderRadius: '6px',
                                backgroundColor: '#f6ffed',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                                marginRight: 8,
                            }}>
                                <HomeOutlined style={{ color: '#52c41a', fontSize: '14px' }} />
                            </div>
                            <div>
                                <div style={{ fontWeight: 600, fontSize: '13px', color: '#262626' }}>
                                    {roomName}
                                </div>
                                <div style={{ fontSize: '11px', color: '#8c8c8c' }}>
                                    {roomType || `ID: ${record.room_id_display || 'N/A'}`}
                                </div>
                            </div>
                        </div>
                    );
                }
                return (
                    <div style={{ textAlign: 'center', color: '#8c8c8c', fontSize: '12px' }}>
                        <HomeOutlined style={{ marginRight: 4 }} />
                        Chưa chọn phòng
                    </div>
                );
            },
        },
        {
            title: 'Tổng tiền',
            dataIndex: 'total_price_vnd',
            key: 'total_price_vnd',
            width: 130,
            align: 'right',
            render: (amount: number) => (
                <div style={{ textAlign: 'right' }}>
                    <div style={{ fontWeight: 600, fontSize: '14px', color: '#f50', marginBottom: 2 }}>
                        {new Intl.NumberFormat('vi-VN').format(amount || 0)}₫
                    </div>
                    <div style={{ fontSize: '11px', color: '#8c8c8c' }}>
                        Tổng cộng
                    </div>
                </div>
            ),
        },
        {
            title: 'Trạng thái',
            dataIndex: 'status',
            key: 'status',
            width: 130,
            align: 'center',
            render: (status: string) => (
                <Tag
                    color={bookingStatusConfig[status as keyof typeof bookingStatusConfig]?.color || 'default'}
                    style={{
                        borderRadius: '6px',
                        fontWeight: 500,
                        fontSize: '12px',
                        padding: '4px 8px'
                    }}
                >
                    {bookingStatusConfig[status as keyof typeof bookingStatusConfig]?.text || status}
                </Tag>
            ),
        },
        {
            title: 'Thao tác',
            key: 'actions',
            width: 120,
            fixed: 'right',
            align: 'center',
            render: (_, record) => (
                <Space size="small">
                    <Tooltip title="Xem chi tiết">
                        <Button
                            type="text"
                            icon={<EyeOutlined />}
                            size="small"
                            style={{
                                borderRadius: '6px',
                                backgroundColor: '#f0f2ff',
                                color: '#1890ff'
                            }}
                            onClick={() => {
                                setSelectedBooking(record as any);
                                setIsDetailModalVisible(true);
                            }}
                        />
                    </Tooltip>
                    {record.status === 'pending' && (
                        <Tooltip title="Hủy đặt phòng">
                            <Button
                                type="text"
                                danger
                                icon={<DeleteOutlined />}
                                size="small"
                                style={{
                                    borderRadius: '6px',
                                    backgroundColor: '#fff2f0'
                                }}
                                onClick={() => handleCancelBooking(record.booking_id)}
                            />
                        </Tooltip>
                    )}
                </Space>
            ),
        },
    ];

    return (
        <Layout className="min-h-screen" style={{ backgroundColor: '#f5f5f5' }}>
            <Content className="p-6">
                {/* Header Section */}
                <div style={{
                    marginBottom: 24,
                    padding: '24px 0',
                    borderBottom: '1px solid #f0f0f0'
                }}>
                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                        <div>
                            <Title level={2} style={{
                                marginBottom: 8,
                                fontSize: '28px',
                                fontWeight: 700,
                                color: '#262626'
                            }}>
                                Quản lý đặt phòng
                            </Title>
                            <Text type="secondary" style={{ fontSize: '14px' }}>
                                Quản lý và theo dõi các đặt phòng của khách sạn
                            </Text>
                        </div>
                        <Space>
                            <Button
                                type="default"
                                icon={<CalendarOutlined />}
                                style={{ borderRadius: '8px' }}
                            >
                                Xuất báo cáo
                            </Button>
                        </Space>
                    </div>
                </div>

                {/* Statistics Cards */}
                <Row gutter={[24, 24]} className="mb-6">
                    <Col xs={24} sm={12} md={6}>
                        <Card style={{
                            borderRadius: '12px',
                            border: '1px solid #f0f0f0',
                            boxShadow: '0 2px 8px rgba(0,0,0,0.04)'
                        }}>
                            <Statistic
                                title={<span style={{ fontSize: '14px', fontWeight: 500 }}>Tổng đặt phòng</span>}
                                value={statistics.total_bookings || 0}
                                prefix={<div style={{
                                    width: 40,
                                    height: 40,
                                    borderRadius: '8px',
                                    backgroundColor: '#e6f4ff',
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    marginBottom: 8
                                }}>
                                    <CalendarOutlined style={{ color: '#1890ff', fontSize: '18px' }} />
                                </div>}
                                valueStyle={{ fontSize: '24px', fontWeight: 600, color: '#262626' }}
                            />
                        </Card>
                    </Col>
                    <Col xs={24} sm={12} md={6}>
                        <Card style={{
                            borderRadius: '12px',
                            border: '1px solid #f0f0f0',
                            boxShadow: '0 2px 8px rgba(0,0,0,0.04)'
                        }}>
                            <Statistic
                                title={<span style={{ fontSize: '14px', fontWeight: 500 }}>Chờ xác nhận</span>}
                                value={statistics.pending_bookings || 0}
                                prefix={<div style={{
                                    width: 40,
                                    height: 40,
                                    borderRadius: '8px',
                                    backgroundColor: '#fff7e6',
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    marginBottom: 8
                                }}>
                                    <ClockCircleOutlined style={{ color: '#faad14', fontSize: '18px' }} />
                                </div>}
                                valueStyle={{ fontSize: '24px', fontWeight: 600, color: '#faad14' }}
                            />
                        </Card>
                    </Col>
                    <Col xs={24} sm={12} md={6}>
                        <Card style={{
                            borderRadius: '12px',
                            border: '1px solid #f0f0f0',
                            boxShadow: '0 2px 8px rgba(0,0,0,0.04)'
                        }}>
                            <Statistic
                                title={<span style={{ fontSize: '14px', fontWeight: 500 }}>Đã xác nhận</span>}
                                value={statistics.confirmed_bookings || 0}
                                prefix={<div style={{
                                    width: 40,
                                    height: 40,
                                    borderRadius: '8px',
                                    backgroundColor: '#f6ffed',
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    marginBottom: 8
                                }}>
                                    <CalendarOutlined style={{ color: '#52c41a', fontSize: '18px' }} />
                                </div>}
                                valueStyle={{ fontSize: '24px', fontWeight: 600, color: '#52c41a' }}
                            />
                        </Card>
                    </Col>
                    <Col xs={24} sm={12} md={6}>
                        <Card style={{
                            borderRadius: '12px',
                            border: '1px solid #f0f0f0',
                            boxShadow: '0 2px 8px rgba(0,0,0,0.04)'
                        }}>
                            <Statistic
                                title={<span style={{ fontSize: '14px', fontWeight: 500 }}>Doanh thu</span>}
                                value={statistics.total_revenue || 0}
                                prefix={<div style={{
                                    width: 40,
                                    height: 40,
                                    borderRadius: '8px',
                                    backgroundColor: '#fff2f0',
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    marginBottom: 8
                                }}>
                                    <DollarOutlined style={{ color: '#f50', fontSize: '18px' }} />
                                </div>}
                                formatter={(value) => `${new Intl.NumberFormat('vi-VN').format(Number(value))}₫`}
                                valueStyle={{ fontSize: '20px', fontWeight: 600, color: '#f50' }}
                            />
                        </Card>
                    </Col>
                </Row>

                {/* Filter Bar Component */}
                <BookingFilterBar
                    onSearch={handleSearch}
                    loading={isLoading}
                />

                {/* Bookings Table */}
                <Card
                    style={{
                        borderRadius: '12px',
                        overflow: 'hidden',
                        boxShadow: '0 2px 8px rgba(0,0,0,0.06)'
                    }}
                >
                    <ErrorBoundary
                        fallback={
                            <Alert
                                message="Lỗi hiển thị bảng"
                                description="Không thể hiển thị danh sách đặt phòng. Vui lòng kiểm tra dữ liệu và thử lại."
                                type="error"
                                showIcon
                                style={{ margin: '16px 0' }}
                            />
                        }
                    >
                        <Table
                            columns={columns}
                            dataSource={bookings}
                            loading={isLoading}
                            rowKey={(record) => record.key}
                            pagination={{
                                pageSize: 20,
                                showSizeChanger: true,
                                showQuickJumper: true,
                                showTotal: (total, range) =>
                                    `${range[0]}-${range[1]} của ${total} đặt phòng`,
                                pageSizeOptions: ['10', '20', '50', '100'],
                                style: {
                                    padding: '16px 0',
                                }
                            }}
                            scroll={{ x: 1400, y: 600 }}
                            size="middle"
                            style={{
                                background: '#fff',
                                borderRadius: '8px'
                            }}
                            bordered={false}
                            showHeader
                            locale={{
                                emptyText: (
                                    <div style={{ padding: '60px 0', textAlign: 'center' }}>
                                        <CalendarOutlined style={{ fontSize: '48px', color: '#d9d9d9', marginBottom: 16 }} />
                                        <div style={{ fontSize: '16px', color: '#595959', marginBottom: 8 }}>
                                            Không có đặt phòng nào
                                        </div>
                                        <div style={{ fontSize: '14px', color: '#8c8c8c' }}>
                                            Chưa có dữ liệu đặt phòng nào được tìm thấy
                                        </div>
                                    </div>
                                )
                            }}
                        />
                    </ErrorBoundary>
                </Card>

                {/* Booking Detail Modal */}
                <BookingDetailModal
                    visible={isDetailModalVisible}
                    onClose={() => {
                        setIsDetailModalVisible(false);
                        setSelectedBooking(null);
                    }}
                    bookingId={selectedBooking?.booking_id || selectedBooking?.id || null}
                    onUpdate={() => {
                        refetch();
                    }}
                />
            </Content>
        </Layout>
    );
};

export default BookingManagement;