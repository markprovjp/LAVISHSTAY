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
    children_age?: any; // JSON field, optional
    total_price_vnd: number;
    status: string;
    check_in_date: string;
    check_out_date: string;
    created_at: string;
    updated_at: string;
    room_id?: number | null;
    // Flattened room data to avoid any nested objects
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

            // Extract room data safely first
            const roomData = booking.room || {};
            const roomName = roomData && typeof roomData === 'object' ? (roomData.name || '') : '';
            const roomId = roomData && typeof roomData === 'object' ? (roomData.id || null) : null;

            // ULTRA SAFE processing of children and adults values
            let safeChildren = 0;
            let safeAdults = 1;

            try {
                // Process children with extreme caution
                if (booking.children !== null && booking.children !== undefined) {
                    const childrenVal = Number(booking.children);
                    safeChildren = isNaN(childrenVal) ? 0 : Math.max(0, Math.floor(childrenVal));
                }

                // Process adults with extreme caution  
                if (booking.adults !== null && booking.adults !== undefined) {
                    const adultsVal = Number(booking.adults);
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

                // Use the ultra-safe processed values - rename "children" to avoid Ant Design Table conflict
                adults: safeAdults,

                num_children: safeChildren, // Renamed from "children" to avoid Ant Design reserved property

                status: String(booking.status || 'pending'),
                quantity: Number(booking.quantity) || 1,
                created_at: String(booking.created_at || ''),
                updated_at: String(booking.updated_at || ''),

                // Guest information (optional in schema) - all strings
                guest_name: String(booking.guest_name || ''),
                guest_email: String(booking.guest_email || ''),
                guest_phone: String(booking.guest_phone || ''),
                room_id: booking.room_id ? Number(booking.room_id) : null,

                // FLATTEN room data immediately - NO nested objects, only primitives
                room_name: String(roomName),
                room_id_display: roomId ? Number(roomId) : null,

                // Map status for compatibility - all strings
                booking_status: String(booking.status || 'pending'),
                payment_status: String(booking.payment_status || 'pending'),
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
            width: 160,
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
            width: 220,
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
            title: 'Người lớn',
            dataIndex: 'adults',
            key: 'adults',
            width: 80,
            align: 'center',
            render: (adults: number) => (
                <div style={{ textAlign: 'center' }}>
                    <div style={{
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        gap: 4
                    }}>
                        <TeamOutlined style={{ color: '#1890ff', fontSize: '14px' }} />
                        <Text strong style={{ fontSize: '13px' }}>{adults || 0}</Text>
                    </div>
                </div>
            ),
        },
        {
            title: 'Trẻ em',
            key: 'num_children',
            width: 100,
            align: 'center',
            render: (_, record) => {
                // Ultra safe children value extraction
                let children = 0;
                try {
                    children = Number(record.num_children) || 0;
                    if (isNaN(children)) children = 0;
                } catch (error) {
                    console.error('Error processing children in render:', error);
                    children = 0;
                }

                const childrenAges = record.children_age;
                let tooltipTitle = 'Không có thông tin độ tuổi';

                if (typeof childrenAges === 'string' && childrenAges.length > 0) {
                    tooltipTitle = `Độ tuổi: ${childrenAges.split(',').join(', ')}`;
                }

                const content = (
                    <div style={{
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        gap: 4
                    }}>
                        <SmileOutlined style={{ color: '#fa8c16', fontSize: '14px' }} />
                        <Text strong style={{ fontSize: '13px' }}>{children}</Text>
                    </div>
                );

                if (children > 0) {
                    return (
                        <Tooltip title={tooltipTitle}>
                            <div style={{ textAlign: 'center' }}>
                                {content}
                            </div>
                        </Tooltip>
                    );
                }

                return (
                    <div style={{ textAlign: 'center' }}>
                        {content}
                    </div>
                );
            },
        },
        {
            title: 'Phòng',
            key: 'room',
            width: 140,
            render: (_, record) => {
                if (record.room_name) {
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
                                    {record.room_name}
                                </div>
                                <div style={{ fontSize: '11px', color: '#8c8c8c' }}>
                                    ID: {record.room_id_display}
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
                    {/* Table with robust error handling */}
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
                        {(() => {
                            try {
                                if (!Array.isArray(bookings)) {
                                    return (
                                        <Alert
                                            message="Dữ liệu không hợp lệ"
                                            description="Dữ liệu đặt phòng không đúng định dạng."
                                            type="warning"
                                            showIcon
                                            style={{ margin: '16px 0' }}
                                        />
                                    );
                                }

                                if (bookings.length === 0 && !isLoading) {
                                    return (
                                        <Alert
                                            message="Không có dữ liệu"
                                            description="Không tìm thấy đặt phòng nào."
                                            type="info"
                                            showIcon
                                            style={{ margin: '16px 0' }}
                                        />
                                    );
                                }

                                // Create ULTRA safe data - completely flatten and validate all fields
                                const safeBookings = bookings.map((booking, index) => {
                                    // Deep clean all fields to ensure no nested objects
                                    const cleanedBooking = {
                                        key: `booking-${booking.booking_id}-${index}`,
                                        booking_id: Number(booking.booking_id) || 0,
                                        booking_code: String(booking.booking_code || ''),
                                        guest_name: String(booking.guest_name || ''),
                                        guest_email: String(booking.guest_email || ''),
                                        guest_phone: String(booking.guest_phone || ''),
                                        guest_count: Number(booking.guest_count) || 0,
                                        adults: Number(booking.adults) || 0,
                                        num_children: Number(booking.num_children) || 0,
                                        children_age: booking.children_age , // Convert JSON to string
                                        total_price_vnd: Number(booking.total_price_vnd) || 0,
                                        status: String(booking.status || 'pending'),
                                        check_in_date: String(booking.check_in_date || ''),
                                        check_out_date: String(booking.check_out_date || ''),
                                        created_at: String(booking.created_at || ''),
                                        updated_at: String(booking.updated_at || ''),
                                        room_id: booking.room_id ? Number(booking.room_id) : null,
                                        room_name: String(booking.room_name || ''),
                                        room_id_display: booking.room_id_display ? Number(booking.room_id_display) : null
                                    };

                                    // Additional validation: ensure NO nested objects remain
                                    Object.keys(cleanedBooking).forEach(key => {
                                        const value = cleanedBooking[key as keyof typeof cleanedBooking];
                                        if (value !== null && typeof value === 'object') {
                                            console.warn(`Found nested object in ${key}:`, value);
                                            // Convert to string as fallback
                                            (cleanedBooking as any)[key] = String(value);
                                        }
                                    });

                                    return cleanedBooking;
                                });


                                // Final safety check - ensure dataSource is valid array
                                const validatedDataSource = Array.isArray(safeBookings) ? safeBookings : [];


                                try {
                                    // SAFE: Create test data WITH num_children field using string conversion
                                    const safeTestData = validatedDataSource.map((booking) => ({
                                        key: `safe-${booking.booking_id}`,
                                        booking_id: booking.booking_id,
                                        booking_code: booking.booking_code,
                                        // Convert num_children to string first, then back to number to ensure it's safe
                                        num_children: Number(String(booking.num_children || 0)),
                                        adults: Number(String(booking.adults || 1)),
                                        guest_name: booking.guest_name,
                                        status: booking.status,
                                        total_price_vnd: booking.total_price_vnd,
                                        check_in_date: booking.check_in_date,
                                        check_out_date: booking.check_out_date,
                                        room_name: booking.room_name,
                                        room_id_display: booking.room_id_display,
                                        guest_email: booking.guest_email,
                                        guest_phone: booking.guest_phone,
                                        children_age: booking.children_age // mảng
                                    }));

                                    // Beautiful columns similar to original design
                                    const beautifulColumns = [
                                        {
                                            title: 'Mã đặt phòng',
                                            dataIndex: 'booking_code',
                                            key: 'booking_code',
                                            width: 160,
                                            fixed: 'left' as const,
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
                                            width: 220,
                                            render: (_: any, record: any) => (
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
                                            render: (_: any, record: any) => {
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
                                            title: 'Người lớn',
                                            dataIndex: 'adults',
                                            key: 'adults',
                                            width: 80,
                                            align: 'center' as const,
                                            render: (adults: number) => (
                                                <div style={{ textAlign: 'center' }}>
                                                    <div style={{
                                                        display: 'flex',
                                                        alignItems: 'center',
                                                        justifyContent: 'center',
                                                        gap: 4
                                                    }}>
                                                        <TeamOutlined style={{ color: '#1890ff', fontSize: '14px' }} />
                                                        <Text strong style={{ fontSize: '13px' }}>{adults || 0}</Text>
                                                    </div>
                                                </div>
                                            ),
                                        },
                                        {
                                            title: 'Trẻ em',
                                            dataIndex: 'num_children',
                                            key: 'num_children',
                                            width: 100,
                                            align: 'center' as const,
                                            render: (children: number, record: any) => {
                                                // Ultra safe processing
                                                const safeChildren = Number(children) || 0;

                                                const childrenAges = record.children_age;
                                                let tooltipTitle = 'Không có thông tin độ tuổi';
                                                
                                                if (Array.isArray(childrenAges) && childrenAges.length > 0) {
                                                    tooltipTitle = `Độ tuổi: ${childrenAges.join(', ')}`;
                                                }

                                                const content = (
                                                    <div style={{
                                                        display: 'flex',
                                                        alignItems: 'center',
                                                        justifyContent: 'center',
                                                        gap: 4
                                                    }}>
                                                        <SmileOutlined style={{ color: '#fa8c16', fontSize: '14px' }} />
                                                        <Text strong style={{ fontSize: '13px' }}>{safeChildren}</Text>
                                                    </div>
                                                );

                                                if (safeChildren > 0) {
                                                    return (
                                                        <Tooltip title={tooltipTitle}>
                                                            <div style={{ textAlign: 'center' }}>
                                                                {content}
                                                            </div>
                                                        </Tooltip>
                                                    );
                                                }

                                                return (
                                                    <div style={{ textAlign: 'center' }}>
                                                        {content}
                                                    </div>
                                                );
                                            },
                                        },
                                        {
                                            title: 'Phòng',
                                            key: 'room',
                                            width: 140,
                                            render: (_: any, record: any) => {
                                                if (record.room_name) {
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
                                                                    {record.room_name}
                                                                </div>
                                                                <div style={{ fontSize: '11px', color: '#8c8c8c' }}>
                                                                    ID: {record.room_id_display}
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
                                            align: 'right' as const,
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
                                            align: 'center' as const,
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
                                            fixed: 'right' as const,
                                            align: 'center' as const,
                                            render: (_: any, record: any) => (
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
                                                                setSelectedBooking(record);
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
                                    ] as const;

                                    const tableComponent = (<Table
                                        columns={beautifulColumns as any}
                                        dataSource={safeTestData}
                                        loading={isLoading}
                                        rowKey={(record) => `safe-${record.booking_id}`}
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
                                    />);

                                    return tableComponent;
                                } catch (tableError) {
                                    console.error('CRITICAL: Error creating Table component:', tableError);
                                    if (tableError instanceof Error) {
                                        console.error('Stack trace:', tableError.stack);
                                    }
                                    console.error('DataSource that caused error:', validatedDataSource);

                                    const errorMessage = tableError instanceof Error ? tableError.message : 'Unknown error';

                                    return (
                                        <Alert
                                            message="Lỗi khởi tạo bảng"
                                            description={`Có lỗi xảy ra khi khởi tạo bảng: ${errorMessage}`}
                                            type="error"
                                            showIcon
                                            style={{ margin: '16px 0' }}
                                        />
                                    );
                                }
                            } catch (error) {
                                console.error('Error rendering table:', error);
                                return (
                                    <Alert
                                        message="Lỗi hiển thị bảng"
                                        description="Có lỗi xảy ra khi hiển thị bảng. Vui lòng thử lại."
                                        type="error"
                                        showIcon
                                        style={{ margin: '16px 0' }}
                                    />
                                );
                            }
                        })()}
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
