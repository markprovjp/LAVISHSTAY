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
    Avatar,
    Tooltip,
    Dropdown,
    Menu,
    Flex,
    message
} from 'antd';
import { ProTable, type ProColumns } from '@ant-design/pro-components';
import {
    EyeOutlined,
    DeleteOutlined,
    ClockCircleOutlined,
    UserOutlined,
    MoreOutlined,
    HomeOutlined,
    TeamOutlined,
    CheckCircleOutlined,
    CloseCircleOutlined,
    SyncOutlined,
    PlusOutlined,
    FilePdfOutlined,
    MailOutlined,
    PhoneOutlined,
    ArrowRightOutlined,
    QuestionCircleOutlined,
    SmileOutlined
} from '@ant-design/icons';
import { useGetBookings, useGetBookingStatistics, useCancelBooking } from '../../../hooks/useReception';
import { receptionAPI } from '../../../utils/api';
import {
    Booking,
    BookingFilters
} from '../../../types/booking';
import BookingDetailModal from './BookingDetailModal';
import BookingFilterBar from '../../../components/booking-management/BookingFilterBar';
import ErrorBoundary from '../../../components/common/ErrorBoundary';
import CheckinModal from './CheckinModal';
import dayjs from 'dayjs';
import 'dayjs/locale/vi';
import RoomSelectionModal from './RoomSelectionModal';
dayjs.locale('vi');

const { Content } = Layout;
const { Title, Text, Link } = Typography;

const bookingStatusConfig = {
    pending: { color: 'gold', text: 'Chờ thanh toán', icon: <ClockCircleOutlined /> },
    confirmed: { color: 'blue', text: 'Đã thanh toán', icon: <CheckCircleOutlined /> },
    cancelled: { color: 'red', text: 'Đã hủy', icon: <CloseCircleOutlined /> },
    completed: { color: 'green', text: 'Hoàn thành', icon: <CheckCircleOutlined /> },
    processing: { color: 'purple', text: 'Đang xử lý', icon: <SyncOutlined spin /> },
};

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
    children_age?: number[];
    total_price_vnd: number;
    status: string;
    check_in_date: string;
    check_out_date: string;
    created_at: string;
    updated_at: string;
    room_names: string;
    room_type_names: string;
    total_rooms: number;
    payment_status: string;
    payment_type: string;
    payment_amount: number;
    transaction_id: string;
    representative_name: string;
    representative_phone: string;
    representative_email: string;
    option_names: string;
    room_id?: number | null;
    room_name?: string;
    room_id_display?: number | null;
}

const BookingManagement: React.FC = () => {
    const [filters, setFilters] = useState<BookingFilters>({});
    const [selectedBooking, setSelectedBooking] = useState<Booking | null>(null);
    const [isDetailModalVisible, setIsDetailModalVisible] = useState(false);
    const [isRoomSelectionModalVisible, setIsRoomSelectionModalVisible] = useState(false);
    const [roomSelectionBookingId, setRoomSelectionBookingId] = useState<number | null>(null);
    const [selectedRowKeys, setSelectedRowKeys] = useState<React.Key[]>([]);
    const [isCheckinModalVisible, setIsCheckinModalVisible] = useState(false);
    const [checkinBookingId, setCheckinBookingId] = useState<number | null>(null);

    const { data: bookingsData, isLoading, refetch } = useGetBookings(filters);
    const { data: statisticsData } = useGetBookingStatistics();
    const cancelBookingMutation = useCancelBooking();

    const bookings = React.useMemo(() => {
        if (!bookingsData?.data) return [];
        let rawBookings = Array.isArray(bookingsData.data) ? bookingsData.data : [];
        if (typeof bookingsData.data === 'object' && !Array.isArray(bookingsData.data) && bookingsData.data.data) {
            rawBookings = Array.isArray(bookingsData.data.data) ? bookingsData.data.data : [];
        }
        const validBookings = rawBookings.filter((booking: any) =>
            booking && typeof booking === 'object' && (booking.booking_id || booking.id) && booking.booking_code
        );
        const uniqueBookings = validBookings.reduce((acc: any[], current: any) => {
            const bookingId = current.booking_id || current.id;
            if (!acc.some(b => (b.booking_id || b.id) === bookingId)) {
                acc.push(current);
            }
            return acc;
        }, []);

        return uniqueBookings.map((booking: any, index: number) => {
            const bookingId = booking.booking_id || booking.id;
            const totalAmount = booking.total_price_vnd || booking.total_amount || 0;
            const roomNames = booking.room_names || '';
            const roomTypeNames = booking.room_type_names || '';
            const totalRooms = booking.total_rooms || 1;
            let safeAdults = booking.adults ?? booking.guest_count ?? 1;
            let safeChildren = booking.children ?? 0;

            return {
                booking_id: bookingId,
                id: bookingId,
                key: `booking-${bookingId}-${index}`,
                booking_code: String(booking.booking_code || ''),
                user_id: booking.user_id ? Number(booking.user_id) : null,
                option_id: booking.option_id ? Number(booking.option_id) : null,
                children_age: Array.isArray(booking.children_age) ? booking.children_age : [],
                check_in_date: String(booking.check_in_date || ''),
                check_out_date: String(booking.check_out_date || ''),
                total_price_vnd: Number(totalAmount) || 0,
                total_amount: Number(totalAmount) || 0,
                guest_count: Number(booking.guest_count) || 1,
                adults: safeAdults,
                num_children: safeChildren,
                status: String(booking.status || 'pending'),
                quantity: Number(booking.quantity) || totalRooms,
                created_at: String(booking.created_at || ''),
                updated_at: String(booking.updated_at || ''),
                guest_name: String(booking.guest_name || ''),
                guest_email: String(booking.guest_email || ''),
                guest_phone: String(booking.guest_phone || ''),
                room_names: String(roomNames),
                room_type_names: String(roomTypeNames),
                total_rooms: Number(totalRooms),
                payment_status: String(booking.payment_status || 'pending'),
                payment_type: String(booking.payment_type || ''),
                payment_amount: Number(booking.payment_amount || 0),
                transaction_id: String(booking.transaction_id || ''),
                representative_name: String(booking.representative_name || ''),
                representative_phone: String(booking.representative_phone || ''),
                representative_email: String(booking.representative_email || ''),
                option_names: String(booking.option_names || ''),
                room_id: booking.room_id ? Number(booking.room_id) : null,
                room_name: String(roomNames.split(',')[0] || ''),
                room_id_display: booking.room_id ? Number(booking.room_id) : null,
                booking_status: String(booking.status || 'pending'),
            };
        });
    }, [bookingsData]);

    const statistics = statisticsData?.data || {};

    const handleSearch = (searchFilters: BookingFilters) => setFilters(searchFilters);

    const handleCancelBooking = (bookingId: number) => {
        Modal.confirm({
            title: 'Xác nhận hủy đặt phòng',
            content: 'Bạn có chắc chắn muốn hủy đặt phòng này không? Hành động này không thể hoàn tác.',
            okText: 'Xác nhận hủy',
            cancelText: 'Đóng',
            okType: 'danger',
            icon: <DeleteOutlined />,
            onOk: async () => {
                try {
                    await cancelBookingMutation.mutateAsync(bookingId);
                    refetch();
                    setSelectedRowKeys([]);
                } catch (error) {
                    console.error('Error canceling booking:', error);
                }
            },
        });
    };

    const columns: ProColumns<BookingTableData>[] = [
        {
            title: 'Mã Đặt Phòng',
            dataIndex: 'booking_code',
            key: 'booking_code',
            width: 220,
            fixed: 'left',
            sorter: (a, b) => a.booking_code.localeCompare(b.booking_code),
            render: (_, record) => <Link copyable style={{ fontWeight: 'bold', color: '#1890ff' }}>{record.booking_code}</Link>,
        },
        {
            title: 'Thông Tin Khách',
            key: 'guest',
            width: 280,
            render: (_, record) => (
                <Flex align="center" gap="middle">
                    <Avatar size={48} style={{ backgroundColor: '#e6f7ff', color: '#1890ff' }} icon={<UserOutlined />} />
                    <Flex vertical>
                        <Text strong>{record.guest_name}</Text>
                        <Space size={4}><PhoneOutlined /><Text type="secondary">{record.guest_phone}</Text></Space>
                        <Tooltip title={record.guest_email}>
                            <Space size={4}><MailOutlined /><Text type="secondary" style={{ maxWidth: 180 }} ellipsis>{record.guest_email}</Text></Space>
                        </Tooltip>
                    </Flex>
                </Flex>
            ),
        },
        {
            title: 'Thời Gian Lưu Trú',
            key: 'dates',
            width: 320,
            render: (_, record) => {
                const checkIn = dayjs(record.check_in_date);
                const checkOut = dayjs(record.check_out_date);
                const nights = checkOut.diff(checkIn, 'day');
                return (
                    <Flex align="center" justify="space-between">
                        <Flex vertical>
                            <Text strong>{checkIn.format('DD/MM/YYYY')}</Text>
                            <Text type="secondary">Check-in</Text>
                        </Flex>
                        <Flex vertical align="center">
                            <ArrowRightOutlined style={{ color: '#1890ff' }} />
                            <Tag color="blue">{`${nights} đêm`}</Tag>
                        </Flex>
                        <Flex vertical align="end">
                            <Text strong>{checkOut.format('DD/MM/YYYY')}</Text>
                            <Text type="secondary">Check-out</Text>
                        </Flex>
                    </Flex>
                );
            },
        },
        {
            title: 'Khách',
            key: 'total_guests',
            width: 150,
            align: 'center',
            render: (_, record) => {
                const total = record.adults + record.num_children;
                const hasChildren = record.num_children > 0;
                return (
                    <Flex vertical align="center" justify="center">
                        <Tooltip
                            title={
                                hasChildren
                                    ? `Tổng khách: ${total} (Người lớn: ${record.adults}, Trẻ em: ${record.num_children})`
                                    : `Tổng khách: ${total} (Người lớn: ${record.adults})`
                            }
                        >
                            <Flex align="center" gap={8}>
                                <TeamOutlined style={{ color: '#1890ff', fontSize: 18 }} />
                                <Text strong style={{ fontSize: 16 }}>{total}</Text>
                                {hasChildren && <SmileOutlined style={{ color: '#faad14', fontSize: 12, marginLeft: 2 }} />}
                            </Flex>
                        </Tooltip>
                        {hasChildren && record.children_age && record.children_age.length > 0 && (
                            <Text type="secondary" style={{ fontSize: 12, marginTop: 4 }}>{`Tuổi trẻ em: ${record.children_age.join(', ')}`}</Text>
                        )}
                    </Flex>
                );
            },
        },
        {
            title: 'Phòng & Gói',
            key: 'room_option',
            width: 220,
            render: (_, record) => (
                <Flex vertical>
                    <Flex align="center" gap={6}>
                        <HomeOutlined style={{ color: '#52c41a' }} />
                        <Text strong>{record.room_names || 'Chưa gán phòng'}</Text>
                    </Flex>
                    <Text type="secondary">{record.option_names || 'Không có gói'}</Text>
                </Flex>
            ),
        },
        {
            title: 'Tổng Tiền',
            dataIndex: 'total_price_vnd',
            key: 'total_price_vnd',
            width: 150,
            align: 'right',
            sorter: (a, b) => a.total_price_vnd - b.total_price_vnd,
            render: (price) => <Text strong style={{ color: '#f5222d' }}>{new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price as number)}</Text>,
        },
        {
            title: 'Trạng Thái',
            dataIndex: 'status',
            key: 'status',
            width: 150,
            align: 'center',
            filters: Object.entries(bookingStatusConfig).map(([key, { text }]) => ({ text, value: key })),
            onFilter: (value, record) => record.status.toLowerCase() === String(value).toLowerCase(),
            render: (_, record) => {
                const config = bookingStatusConfig[record.status.toLowerCase() as keyof typeof bookingStatusConfig] || { color: 'default', text: record.status, icon: <QuestionCircleOutlined /> };
                return <Tag color={config.color} icon={config.icon}>{config.text}</Tag>;
            },
        },
        {
            title: 'Thao Tác',
            key: 'actions',
            width: 120,
            fixed: 'right',
            align: 'center',
            render: (_, record) => {
                const handleMenuClick = async ({ key }: { key: string }) => {
                    if (key === 'view') {
                        setSelectedBooking(record as any);
                        setIsDetailModalVisible(true);
                    } else if (key === 'assign') {
                        setRoomSelectionBookingId(record.booking_id);
                        setIsRoomSelectionModalVisible(true);
                    } else if (key === 'cancel') {
                        handleCancelBooking(record.booking_id);
                    } else if (key === 'checkin') {
                        // Mở modal check-in với thông tin chi tiết
                        setCheckinBookingId(record.booking_id);
                        setIsCheckinModalVisible(true);
                    } else if (key === 'checkout') {
                        // Gọi API check-out qua axios
                        try {
                            await receptionAPI.checkOut({ booking_id: record.booking_id, room_id: record.room_id || 0 });
                            message.success('Check-out thành công!');
                            refetch();
                        } catch (e) {
                            message.error('Check-out thất bại!');
                        }
                    }
                };
                const menu = (
                    <Menu onClick={handleMenuClick}>
                        <Menu.Item key="view" icon={<EyeOutlined />}>Xem Chi Tiết</Menu.Item>
                        {(!record.room_names || record.room_names.includes('null')) && (
                            <Menu.Item key="assign" icon={<HomeOutlined />}>Gán Phòng</Menu.Item>
                        )}
                        {(record.status.toLowerCase() === 'pending' || record.status.toLowerCase() === 'confirmed') && (
                            <Menu.Item key="cancel" icon={<DeleteOutlined />} danger>Hủy Đặt Phòng</Menu.Item>
                        )}
                        {(record.status.toLowerCase() === 'confirmed' || record.status.toLowerCase() === 'operational') && (
                            <Menu.Item key="checkin" icon={<CheckCircleOutlined style={{ color: '#52c41a' }} />}>Check-in</Menu.Item>
                        )}
                        {(record.status.toLowerCase() === 'operational') && (
                            <Menu.Item key="checkout" icon={<CheckCircleOutlined style={{ color: '#1890ff' }} />}>Check-out</Menu.Item>
                        )}
                    </Menu>
                );
                return <Dropdown overlay={menu} trigger={['click']}><Button type="text" icon={<MoreOutlined />} /></Dropdown>;
            },
        },
    ];

    return (
        <Layout style={{ minHeight: '100vh', background: '#f0f2f5' }}>
            <Content style={{ padding: 24 }}>
                <Card style={{ marginBottom: 24, borderRadius: 8, boxShadow: '0 2px 8px rgba(0,0,0,0.09)' }}>
                    <Flex justify="space-between" align="center">
                        <div>
                            <Title level={2} style={{ marginBottom: 0 }}>Quản lý Đặt Phòng</Title>
                            <Text type="secondary">Theo dõi và quản lý tất cả các đặt phòng.</Text>
                        </div>
                        <Space>
                            <Button icon={<FilePdfOutlined />}>Xuất Báo Cáo</Button>
                            <Button type="primary" icon={<PlusOutlined />}>Tạo Đặt Phòng Mới</Button>
                        </Space>
                    </Flex>
                </Card>

                <Row gutter={[24, 24]} style={{ marginBottom: 24 }}>
                    {Object.entries(statistics).map(([key, value]) => (
                        <Col xs={24} sm={12} md={6} key={key}>
                            <Card style={{ borderRadius: 8, boxShadow: '0 2px 8px rgba(0,0,0,0.06)' }}>
                                <Statistic title={key.replace(/_/g, ' ').toUpperCase()} value={value as number} />
                            </Card>
                        </Col>
                    ))}
                </Row>

                <BookingFilterBar onSearch={handleSearch} loading={isLoading} />

                <Card style={{ borderRadius: 8, overflow: 'hidden', boxShadow: '0 2px 8px rgba(0,0,0,0.09)' }}>
                    <ErrorBoundary fallback={<Alert message="Lỗi hiển thị bảng" type="error" showIcon />}>
                        <ProTable<BookingTableData>
                            columns={columns}
                            dataSource={bookings}
                            loading={isLoading}
                            rowKey="key"
                            rowSelection={{
                                selectedRowKeys,
                                onChange: (keys) => setSelectedRowKeys(keys),
                            }}
                            pagination={{ pageSize: 10, showQuickJumper: true }}
                            search={false}
                            options={{ density: true, reload: true, setting: true, fullScreen: true }}
                            headerTitle="Danh sách Đặt phòng"
                            toolBarRender={() => [
                                <Button
                                    danger
                                    disabled={selectedRowKeys.length === 0}
                                    onClick={() => selectedRowKeys.forEach(key => handleCancelBooking(Number(String(key).split('-')[1])))}
                                >
                                    Hủy {selectedRowKeys.length} mục đã chọn
                                </Button>
                            ]}
                        />
                    </ErrorBoundary>
                </Card>

                <BookingDetailModal
                    visible={isDetailModalVisible}
                    onClose={() => setIsDetailModalVisible(false)}
                    bookingId={selectedBooking?.booking_id || null}
                    onUpdate={refetch}
                />
                {isRoomSelectionModalVisible && (
                    <RoomSelectionModal
                        visible={isRoomSelectionModalVisible}
                        bookingId={roomSelectionBookingId}
                        onClose={() => setIsRoomSelectionModalVisible(false)}
                        onUpdate={refetch}
                    />
                )}
                <CheckinModal
                    visible={isCheckinModalVisible}
                    bookingId={checkinBookingId}
                    onClose={() => {
                        setIsCheckinModalVisible(false);
                        setCheckinBookingId(null);
                    }}
                    onSuccess={() => {
                        refetch();
                        message.success('Check-in thành công!');
                    }}
                />
            </Content>
        </Layout>
    );
};

export default BookingManagement;
