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
    message,
    DatePicker,
    Input
} from 'antd';
import CheckoutInfoModal from './CheckoutInfoModal';
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
    RestOutlined,
    SmileOutlined
} from '@ant-design/icons';
import { useGetBookings, useGetBookingStatistics, useCancelBooking } from '../../../hooks/useReception';
import {
    Booking,
    BookingFilters
} from '../../../types/booking';
import BookingDetailModal from './BookingDetailModal';
import ErrorBoundary from '../../../components/common/ErrorBoundary';
import CheckinModal from './CheckinModal';
import dayjs from 'dayjs';
import 'dayjs/locale/vi';
import RoomSelectionModal from './RoomSelectionModal';
import ReceptionServicesModal from './ReceptionServicesModal';
import { receptionAPI } from '../../../utils/api';
dayjs.locale('vi');

const { Content } = Layout;
const { Title, Text, Link } = Typography;
const { RangePicker } = DatePicker;

const bookingStatusConfig = {
    pending: { color: 'gold', text: 'Pending', icon: <ClockCircleOutlined /> },
    confirmed: { color: 'blue', text: 'Confirmed', icon: <CheckCircleOutlined /> },
    operational: { color: 'cyan', text: 'Operational', icon: <SyncOutlined spin /> },
    completed: { color: 'green', text: 'Completed', icon: <CheckCircleOutlined /> },
    cancelled: { color: 'red', text: 'Cancelled', icon: <CloseCircleOutlined /> },
    cancelled_with_penalty: { color: 'volcano', text: 'Cancelled With Penalty', icon: <CloseCircleOutlined /> },
    unsuccessful: { color: 'magenta', text: 'Unsuccessful', icon: <QuestionCircleOutlined /> },
    cleaning: { color: 'orange', text: 'Cleaning', icon: <SyncOutlined /> },
    default: { color: 'default', text: 'Unknown', icon: <QuestionCircleOutlined /> },
};

// Helper to normalize backend status strings to keys defined above
const statusKey = (status?: string) => (status ? status.toLowerCase().replace(/\s+/g, '_') : 'default');

const { Text: AntText } = Typography;

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
    auto_assigned?: boolean;
    // Coupon fields
    coupon_applied?: boolean;
    coupon?: {
        redemption_id: number;
        coupon_id: number;
        code: string;
        amount_saved_vnd: number;
        applied_amount_vnd: number;
        applied_at: string;
        meta: any;
    } | null;
    guest_avatar?: string;
    // Cleaning fields (optional)
    is_cleaning?: boolean;
    cleaning_ends_at?: string | null;
    cleaning_note?: string | null;
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
    const [isServicesModalVisible, setIsServicesModalVisible] = useState(false);
    const [servicesBookingId, setServicesBookingId] = useState<number | null>(null);
    const [isCheckoutDrawerVisible, setIsCheckoutDrawerVisible] = useState(false);
    const [checkoutBookingId, setCheckoutBookingId] = useState<number | null>(null);

    // Table-level filters (used by column filterDropdowns)
    const [tableFilters, setTableFilters] = useState<{ bookingCode?: string; guest?: string; dateRange?: [string, string] }>({});

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
                guest_avatar: String(booking.avatar || booking.guest_avatar || booking.avatar_url || ''),
                booking_status: String(booking.status || 'pending'),
                // Pass through cleaning flags if API provided them at booking or room level
                // If API omitted cleaning_ends_at but booking.status === 'Cleaning', fallback to updated_at + 120 minutes
                ...(() => {
                    const statusLower = String(booking.status || '').toLowerCase();
                    const providedCleaningEndsAt = booking.cleaning_ends_at || booking.room?.cleaning_ends_at || null;
                    const fallbackCleaningEndsAt = (!providedCleaningEndsAt && statusLower === 'cleaning' && booking.updated_at)
                        ? dayjs(booking.updated_at).add(120, 'minute').toISOString()
                        : providedCleaningEndsAt;
                    return {
                        is_cleaning: statusLower === 'cleaning' || !!(booking.is_cleaning || booking.room?.is_cleaning),
                        cleaning_ends_at: fallbackCleaningEndsAt,
                    };
                })(),
                // Coupon fields
                coupon_applied: booking.coupon_applied || false,
                coupon: booking.coupon || null,
                // Auto assignment flag from backend (booking-level)
                auto_assigned: booking.has_auto_assigned || booking.auto_assigned || false,
            };
        });
    }, [bookingsData]);

    if (process.env.NODE_ENV !== 'production') {
        // eslint-disable-next-line no-console
        console.debug('[BookingManagement] mapped bookings sample:', bookings.slice(0, 5).map((b: any) => ({ id: b.booking_id, is_cleaning: b.is_cleaning, cleaning_ends_at: b.cleaning_ends_at })));
    }

    // Bulk action handler for selected bookings in the table toolbar
    const handleBulkAction = async (actionKey: string) => {
        if (!selectedRowKeys || selectedRowKeys.length === 0) return;
        const bookingIds = selectedRowKeys.map(k => Number(String(k).split('-')[1]));

        if (actionKey === 'cancel') {
            Modal.confirm({
                title: `Xác nhận hủy ${bookingIds.length} mục đã chọn`,
                content: 'Bạn có chắc chắn muốn hủy các đặt phòng chọn này không? Hành động này không thể hoàn tác.',
                okText: 'Xác nhận hủy',
                cancelText: 'Đóng',
                okType: 'danger',
                icon: <DeleteOutlined />,
                onOk: async () => {
                    try {
                        for (const id of bookingIds) {
                            // eslint-disable-next-line no-await-in-loop
                            await cancelBookingMutation.mutateAsync(id);
                        }
                        refetch();
                        setSelectedRowKeys([]);
                        message.success('Hủy các đặt phòng đã chọn thành công.');
                    } catch (error) {
                        console.error('Bulk cancel error:', error);
                        message.error('Có lỗi xảy ra khi hủy các mục đã chọn.');
                    }
                },
            });
        } else if (actionKey === 'assign') {
            const first = bookingIds[0];
            setRoomSelectionBookingId(first);
            setIsRoomSelectionModalVisible(true);
        } else if (actionKey === 'checkin') {
            const first = bookingIds[0];
            setCheckinBookingId(first);
            setIsCheckinModalVisible(true);
        } else if (actionKey === 'checkout') {
            const first = bookingIds[0];
            try {
                await receptionAPI.getCheckoutInfo(first);
                setCheckoutBookingId(first);
                setIsCheckoutDrawerVisible(true);
            } catch (err) {
                setServicesBookingId(first);
                setIsServicesModalVisible(true);
            }
        }
    };

    // Apply tableFilters (booking code, guest info, date range) to the bookings list shown in the table
    const filteredBookings = React.useMemo(() => {
        if (!bookings || bookings.length === 0) return [];
        return bookings.filter((b: any) => {
            // booking code filter
            if (tableFilters.bookingCode) {
                const code = String(tableFilters.bookingCode).toLowerCase();
                if (!String(b.booking_code || '').toLowerCase().includes(code)) return false;
            }
            // guest filter (name / phone / email)
            if (tableFilters.guest) {
                const g = String(tableFilters.guest).toLowerCase();
                const name = String(b.guest_name || '').toLowerCase();
                const phone = String(b.guest_phone || '').toLowerCase();
                const email = String(b.guest_email || '').toLowerCase();
                if (!(name.includes(g) || phone.includes(g) || email.includes(g))) return false;
            }
            // date range overlap filter
            if (tableFilters.dateRange && tableFilters.dateRange[0] && tableFilters.dateRange[1]) {
                const start = dayjs(tableFilters.dateRange[0]);
                const end = dayjs(tableFilters.dateRange[1]);
                const checkIn = b.check_in_date ? dayjs(b.check_in_date) : null;
                const checkOut = b.check_out_date ? dayjs(b.check_out_date) : null;
                if (!checkIn || !checkOut) return false;
                // overlap if checkIn < end && checkOut > start
                if (!(checkIn.isBefore(end.add(1, 'day')) && checkOut.isAfter(start.subtract(1, 'day')))) return false;
            }
            return true;
        });
    }, [bookings, tableFilters]);

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
            // Use column filter to restrict table rows to the entered booking code
            filterDropdown: ({ setSelectedKeys, selectedKeys, confirm, clearFilters }: any) => (
                <div style={{ padding: 8 }}>
                    <Input
                        placeholder="Nhập mã đặt phòng"
                        value={selectedKeys && selectedKeys[0] ? selectedKeys[0] : tableFilters.bookingCode || ''}
                        onChange={e => setSelectedKeys(e.target.value ? [e.target.value] : [])}
                        onKeyDown={(e) => { if (e.key === 'Enter') { const code = (selectedKeys && selectedKeys[0]) || (e.target as HTMLInputElement).value; setTableFilters(prev => ({ ...prev, bookingCode: code || undefined })); confirm(); } }}
                        style={{ width: 200, marginRight: 8 }}
                    />
                    <Button type="primary" onClick={() => { const code = (selectedKeys && selectedKeys[0]) || ''; setTableFilters(prev => ({ ...prev, bookingCode: code || undefined })); confirm(); }}>Tìm</Button>
                    <Button style={{ marginLeft: 8 }} onClick={() => { clearFilters(); setSelectedKeys([]); setTableFilters(prev => ({ ...prev, bookingCode: undefined })); }}>Xóa</Button>
                </div>
            ),
            render: (_, record) => <Link copyable style={{ fontWeight: 'bold', color: '#1890ff' }}>{record.booking_code}</Link>,
        },
        {
            title: 'Thông Tin Khách',
            key: 'guest',
            width: 280,
            // Add a filter dropdown to search by guest name / phone / email
            filterDropdown: ({ setSelectedKeys, selectedKeys, confirm, clearFilters }: any) => (
                <div style={{ padding: 8 }}>
                    <Input
                        placeholder="Tìm theo tên, số điện thoại, email"
                        value={selectedKeys && selectedKeys[0] ? selectedKeys[0] : tableFilters.guest || ''}
                        onChange={e => setSelectedKeys(e.target.value ? [e.target.value] : [])}
                        onKeyDown={(e) => { if (e.key === 'Enter') { const val = (selectedKeys && selectedKeys[0]) || (e.target as HTMLInputElement).value; setTableFilters(prev => ({ ...prev, guest: val || undefined })); confirm(); } }}
                        style={{ width: 260, marginRight: 8 }}
                    />
                    <Button type="primary" onClick={() => { const val = (selectedKeys && selectedKeys[0]) || ''; setTableFilters(prev => ({ ...prev, guest: val || undefined })); confirm(); }}>Tìm</Button>
                    <Button style={{ marginLeft: 8 }} onClick={() => { clearFilters(); setSelectedKeys([]); setTableFilters(prev => ({ ...prev, guest: undefined })); }}>Xóa</Button>
                </div>
            ),
            render: (_, record) => (
                <Flex align="center" gap="middle">
                    {record.guest_avatar ? (
                        <Avatar size={48} src={record.guest_avatar} />
                    ) : (
                        <Avatar size={48} style={{ backgroundColor: '#e6f7ff', color: '#1890ff' }} icon={<UserOutlined />} />
                    )}
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
            filterDropdown: ({ setSelectedKeys, selectedKeys, confirm, clearFilters }: any) => (
                <div style={{ padding: 8 }}>
                    <RangePicker
                        onChange={(vals) => {
                            if (vals && vals[0] && vals[1]) {
                                const s = vals[0].format('YYYY-MM-DD');
                                const e = vals[1].format('YYYY-MM-DD');
                                setSelectedKeys([`${s}|${e}`]);
                            } else {
                                setSelectedKeys([]);
                            }
                        }}
                        value={tableFilters.dateRange ? [dayjs(tableFilters.dateRange[0]), dayjs(tableFilters.dateRange[1])] : undefined}
                    />
                    <div style={{ marginTop: 8 }}>
                        <Button type="primary" onClick={() => {
                            const sel = selectedKeys && selectedKeys[0] ? String(selectedKeys[0]) : '';
                            if (sel && sel.includes('|')) {
                                const [s, e] = sel.split('|');
                                setTableFilters(prev => ({ ...prev, dateRange: [s, e] }));
                            }
                            confirm();
                        }}>Áp dụng</Button>
                        <Button style={{ marginLeft: 8 }} onClick={() => { clearFilters(); setSelectedKeys([]); setTableFilters(prev => ({ ...prev, dateRange: undefined })); }}>Xóa</Button>
                    </div>
                </div>
            ),
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
            title: 'Phòng & Gán',
            key: 'room_assignment',
            width: 280,
            render: (_, record) => {
                const hasRooms = record.room_names && !record.room_names.includes('null');
                const isAutoAssigned = record.auto_assigned;

                return (
                    <Flex vertical gap={8}>
                        <Flex align="center" gap={8}>
                            <HomeOutlined style={{ color: hasRooms ? '#52c41a' : '#d9d9d9' }} />
                            <Flex vertical flex={1}>
                                <Text strong style={{ color: hasRooms ? 'inherit' : '#999' }}>
                                    {hasRooms ? record.room_names : 'Chưa gán phòng'}
                                </Text>
                                <Text type="secondary" style={{ fontSize: 12 }}>
                                    {record.option_names || 'Không có gói'}
                                </Text>
                            </Flex>
                        </Flex>

                        {/* Assignment Status Tags */}
                        <Flex gap={4} wrap="wrap">
                            {isAutoAssigned ? (
                                <Tag
                                    color="cyan"
                                    icon={<SyncOutlined />}
                                    style={{ fontSize: 11, margin: 0 }}
                                >
                                    Gán tự động
                                </Tag>
                            ) : hasRooms ? (
                                <Tag
                                    color="blue"
                                    icon={<UserOutlined />}
                                    style={{ fontSize: 11, margin: 0 }}
                                >
                                    Gán thủ công
                                </Tag>
                            ) : (
                                <Tag
                                    color="orange"
                                    icon={<ClockCircleOutlined />}
                                    style={{ fontSize: 11, margin: 0 }}
                                >
                                    Chờ gán phòng
                                </Tag>
                            )}

                            {/* Show reassignment option for auto-assigned rooms */}
                            {isAutoAssigned && hasRooms && (
                                <Tooltip title="Có thể gán lại phòng thủ công">
                                    <Tag
                                        color="green"
                                        style={{ fontSize: 11, margin: 0, cursor: 'help' }}
                                    >
                                        Có thể gán lại
                                    </Tag>
                                </Tooltip>
                            )}
                        </Flex>
                    </Flex>
                );
            },
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
            title: 'Mã giảm giá',
            key: 'coupon_info',
            width: 140,
            align: 'center',
            render: (_, record) => {
                if (record.coupon_applied && record.coupon) {
                    return (
                        <Flex vertical align="center">
                            <Text strong style={{ color: '#52c41a', fontSize: '12px' }}>
                                {record.coupon.code}
                            </Text>
                            <Text style={{ color: '#f5222d', fontSize: '11px' }}>
                                -{new Intl.NumberFormat('vi-VN').format(record.coupon.amount_saved_vnd)} ₫
                            </Text>
                        </Flex>
                    );
                }
                return <Text type="secondary" style={{ fontSize: '11px' }}>-</Text>;
            },
        },
        {
            title: 'Trạng Thái',
            dataIndex: 'status',
            key: 'status',
            width: 150,
            align: 'center',
            filters: [
                { text: 'Pending', value: 'Pending' },
                { text: 'Confirmed', value: 'Confirmed' },
                { text: 'Operational', value: 'Operational' },
                { text: 'Completed', value: 'Completed' },
                { text: 'Cancelled', value: 'Cancelled' },
                { text: 'Cancelled With Penalty', value: 'Cancelled With Penalty' },
                { text: 'Unsuccessful', value: 'Unsuccessful' },
                { text: 'Cleaning', value: 'Cleaning' },
            ],
            onFilter: (value, record) => record.status.toLowerCase() === String(value).toLowerCase(),
            render: (_, record) => {
                const key = statusKey(record.status);
                const config = (bookingStatusConfig as any)[key] || bookingStatusConfig.default;
                const cleaningRemainingMinutes = record.cleaning_ends_at ? Math.max(0, Math.ceil(dayjs(record.cleaning_ends_at).diff(dayjs(), 'minute', true))) : 0;
                return (
                    <div style={{ display: 'flex', flexDirection: 'column', gap: 6, alignItems: 'center' }}>
                        <Tag color={config.color} icon={config.icon}>{config.text}</Tag>
                        {record.is_cleaning && record.cleaning_ends_at && (
                            <Tag color="#36cfc9" icon={<SyncOutlined />}>
                                Đang dọn • còn {cleaningRemainingMinutes} phút
                            </Tag>
                        )}
                    </div>
                );
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
                        // If fetching checkout-info fails (e.g., none selected yet), open services modal
                        try {
                            await receptionAPI.getCheckoutInfo(record.booking_id);
                            setCheckoutBookingId(record.booking_id);
                            setIsCheckoutDrawerVisible(true);
                        } catch (err) {
                            // If fetching checkout-info fails (e.g., none selected yet), open services modal
                            setServicesBookingId(record.booking_id);
                            setIsServicesModalVisible(true);
                        }
                    }
                };
                const statusLower = (record.status || '').toLowerCase();
                const menu = (
                    <Menu onClick={handleMenuClick}>
                        <Menu.Item key="view" icon={<EyeOutlined />}>Xem Chi Tiết</Menu.Item>
                        {((!record.room_names || record.room_names.includes('null')) || record.auto_assigned) && (
                            <Menu.Item key="assign" icon={<HomeOutlined />}>
                                {record.auto_assigned ? 'Gán Lại Phòng' : 'Gán Phòng'}
                            </Menu.Item>
                        )}
                        {(statusLower === 'pending' || statusLower === 'confirmed') && (
                            <Menu.Item key="cancel" icon={<DeleteOutlined />} danger>Hủy Đặt Phòng</Menu.Item>
                        )}
                        {/* Show check-in only when booking is Confirmed. Do not show check-in when already Operational. */}
                        {(statusLower === 'confirmed') && (
                            <Menu.Item key="checkin" icon={<CheckCircleOutlined style={{ color: '#52c41a' }} />}>Check-in</Menu.Item>
                        )}
                        {/* Show check-out only when booking is Operational (checked-in). */}
                        {(statusLower === 'operational') && (
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
                <Card style={{ marginTop: 24, marginBottom: 24, borderRadius: 8, boxShadow: '0 2px 8px rgba(0,0,0,0.09)' }}>
                    <Flex justify="space-between" align="center">
                        <div>
                            <Title level={2} style={{ marginBottom: 0 }}>Quản lý Đặt Phòng</Title>
                            <Text type="secondary">Theo dõi và quản lý tất cả các đặt phòng.</Text>
                        </div>
                        <Space>
                            <Button icon={<FilePdfOutlined />} onClick={async () => {
                                try {
                                    // Build params from current tableFilters and top-level filters
                                    const params: any = {};
                                    if (tableFilters.bookingCode) params.booking_code = tableFilters.bookingCode;
                                    if (tableFilters.guest) params.guest_name = tableFilters.guest;
                                    if (tableFilters.dateRange && tableFilters.dateRange[0] && tableFilters.dateRange[1]) {
                                        params.check_in_date = tableFilters.dateRange[0];
                                        params.check_out_date = tableFilters.dateRange[1];
                                    }
                                    if ((filters as any).status) params.status = (filters as any).status;

                                    const res = await receptionAPI.exportBookings(params);
                                    const blob = new Blob([res.data], { type: 'text/csv;charset=utf-8;' });
                                    const url = window.URL.createObjectURL(blob);
                                    const link = document.createElement('a');
                                    link.href = url;
                                    // backend returns filename; generate fallback
                                    const filename = `bookings_export_${new Date().toISOString().slice(0, 19).replace(/[:T]/g, '_')}.csv`;
                                    link.setAttribute('download', filename);
                                    document.body.appendChild(link);
                                    link.click();
                                    document.body.removeChild(link);
                                    window.URL.revokeObjectURL(url);
                                    message.success('Tải báo cáo thành công.');
                                } catch (err) {
                                    console.error('Export error', err);
                                    message.error('Tải báo cáo thất bại.');
                                }
                            }}>Xuất Báo Cáo</Button>
                        </Space>
                    </Flex>
                </Card>




                <CheckoutInfoModal
                    visible={isCheckoutDrawerVisible}
                    bookingId={checkoutBookingId}
                    onClose={() => { setIsCheckoutDrawerVisible(false); setCheckoutBookingId(null); }}
                    onAddServicesRequest={() => {
                        setIsCheckoutDrawerVisible(false);
                        setIsServicesModalVisible(true);
                        setServicesBookingId(checkoutBookingId);
                    }}
                />

                <Card style={{ borderRadius: 8, overflow: 'hidden', boxShadow: '0 2px 8px rgba(0,0,0,0.09)' }}>
                    <ErrorBoundary fallback={<Alert message="Lỗi hiển thị bảng" type="error" showIcon />}>
                        <ProTable<BookingTableData>
                            columns={columns}
                            dataSource={filteredBookings}
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
                                <Dropdown
                                    overlay={
                                        <Menu onClick={({ key }) => handleBulkAction(key)}>
                                            <Menu.Item key="cancel" icon={<DeleteOutlined />} disabled={selectedRowKeys.length === 0}>Hủy {selectedRowKeys.length} mục</Menu.Item>
                                            <Menu.Item key="assign" icon={<HomeOutlined />} disabled={selectedRowKeys.length === 0}>Gán phòng (mục đầu)</Menu.Item>
                                            <Menu.Item key="checkin" icon={<CheckCircleOutlined />} disabled={selectedRowKeys.length === 0}>Mở Check-in (mục đầu)</Menu.Item>
                                            <Menu.Item key="checkout" icon={<CheckCircleOutlined />} disabled={selectedRowKeys.length === 0}>Mở Check-out (mục đầu)</Menu.Item>
                                        </Menu>
                                    }
                                >
                                    <Button disabled={selectedRowKeys.length === 0}>Hành động hàng loạt ({selectedRowKeys.length})</Button>
                                </Dropdown>
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
                <ReceptionServicesModal
                    visible={isServicesModalVisible}
                    bookingId={servicesBookingId}
                    onClose={() => {
                        setIsServicesModalVisible(false);
                        setServicesBookingId(null);
                    }}
                    onAdded={(checkoutInfo?: any) => {
                        // after services added, refresh data so receptionist can proceed to checkout
                        refetch();
                        message.success('Dịch vụ đã được thêm.');
                        if (checkoutInfo) {
                            setCheckoutBookingId(servicesBookingId);
                            setIsCheckoutDrawerVisible(true);
                        } else {
                            message.info('Vui lòng mở Check-out để xác nhận tổng tiền.');
                        }
                    }}
                />
            </Content>

        </Layout>
    );
};

export default BookingManagement;
