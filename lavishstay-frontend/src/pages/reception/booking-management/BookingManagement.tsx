import React, { useState } from 'react';
import {
    Layout,
    Card,
    Table,
    Button,
    Tag,
    Space,
    Input,
    Select,
    DatePicker,
    Modal,
    Form,
    Row,
    Col,
    Statistic,
    Typography,
    Tooltip,
    Dropdown,
    Badge,
    Avatar,
} from 'antd';
import {
    SearchOutlined,
    PlusOutlined,
    EyeOutlined,
    DeleteOutlined,
    CalendarOutlined,
    UserOutlined,
    PhoneOutlined,
    MailOutlined,
    HomeOutlined,
    DollarOutlined,
    ClockCircleOutlined,
    MoreOutlined,
    ReloadOutlined
} from '@ant-design/icons';
import type { ColumnsType } from 'antd/es/table';
import {
    useGetBookings,
    useGetBookingStatistics,
    useCreateBooking,
    useUpdateBookingStatus,
    useCancelBooking,
    useGetAvailableRooms
} from '../../../hooks/useReception';
import {
    Booking,
    BookingFilters,
    CreateBookingRequest,
    BookingPaymentStatus,
    BookingStatus
} from '../../../types/booking';
import BookingDetailModal from './BookingDetailModal';
import dayjs from 'dayjs';
import 'dayjs/locale/vi';

dayjs.locale('vi');

const { Content } = Layout;
const { Title, Text } = Typography;
const { RangePicker } = DatePicker;
const { Option } = Select;

// Status configurations
const paymentStatusConfig = {
    pending: { color: 'orange', text: 'Chờ thanh toán' },
    paid: { color: 'green', text: 'Đã thanh toán' },
    partial: { color: 'blue', text: 'Thanh toán một phần' },
    refunded: { color: 'purple', text: 'Đã hoàn tiền' },
    failed: { color: 'red', text: 'Thanh toán thất bại' },
};

const bookingStatusConfig = {
    pending: { color: 'orange', text: 'Chờ xác nhận' },
    confirmed: { color: 'blue', text: 'Đã xác nhận' },
    checked_in: { color: 'green', text: 'Đã nhận phòng' },
    checked_out: { color: 'gray', text: 'Đã trả phòng' },
    cancelled: { color: 'red', text: 'Đã hủy' },
    no_show: { color: 'volcano', text: 'Không đến' },
};

const BookingManagement: React.FC = () => {
    const [filters, setFilters] = useState<BookingFilters>({});
    const [isCreateModalVisible, setIsCreateModalVisible] = useState(false);
    const [selectedBooking, setSelectedBooking] = useState<Booking | null>(null);
    const [isDetailModalVisible, setIsDetailModalVisible] = useState(false);
    const [createForm] = Form.useForm();
    const [selectedDates, setSelectedDates] = useState<{ checkIn: string, checkOut: string } | null>(null);

    // API hooks
    const { data: bookingsData, isLoading, refetch } = useGetBookings(filters);
    const { data: statisticsData } = useGetBookingStatistics();
    const { data: availableRoomsData } = useGetAvailableRooms(
        selectedDates?.checkIn || '',
        selectedDates?.checkOut || ''
    );

    const createBookingMutation = useCreateBooking();
    const updateStatusMutation = useUpdateBookingStatus();
    const cancelBookingMutation = useCancelBooking();

    const bookings = bookingsData?.data || [];
    const statistics = statisticsData?.data || {};
    const availableRooms = availableRoomsData?.data || [];

    // Handle search
    const handleSearch = (searchFilters: BookingFilters) => {
        setFilters(searchFilters);
    };

    // Handle create booking
    const handleCreateBooking = async (values: any) => {
        try {
            const bookingData: CreateBookingRequest = {
                guest_name: values.guest_name,
                guest_email: values.guest_email,
                guest_phone: values.guest_phone,
                guest_count: values.guest_count,
                room_id: values.room_id,
                check_in_date: values.date_range[0].format('YYYY-MM-DD'),
                check_out_date: values.date_range[1].format('YYYY-MM-DD'),
                payment_method: values.payment_method,
                special_requests: values.special_requests,
            };

            await createBookingMutation.mutateAsync(bookingData);
            setIsCreateModalVisible(false);
            createForm.resetFields();
            setSelectedDates(null);
        } catch (error) {
            console.error('Error creating booking:', error);
        }
    };

    // Handle status update
    const handleStatusUpdate = async (bookingId: number, status: string) => {
        try {
            await updateStatusMutation.mutateAsync({ bookingId, status });
        } catch (error) {
            console.error('Error updating status:', error);
        }
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
                } catch (error) {
                    console.error('Error canceling booking:', error);
                }
            },
        });
    };

    // Handle date range change for available rooms
    const handleDateRangeChange = (dates: any) => {
        if (dates && dates[0] && dates[1]) {
            setSelectedDates({
                checkIn: dates[0].format('YYYY-MM-DD'),
                checkOut: dates[1].format('YYYY-MM-DD')
            });
        } else {
            setSelectedDates(null);
        }
    };

    // Table columns
    const columns: ColumnsType<Booking> = [
        {
            title: 'Mã đặt phòng',
            dataIndex: 'booking_code',
            key: 'booking_code',
            width: 160,
            render: (code: string) => (
                <Text strong style={{ color: '#1890ff' }}>
                    {code}
                </Text>
            ),
        },
        {
            title: 'Khách hàng',
            key: 'guest',
            width: 200,
            render: (_, record) => (
                <div>
                    <div style={{ display: 'flex', alignItems: 'center', marginBottom: 4 }}>
                        <Avatar size="small" icon={<UserOutlined />} style={{ marginRight: 8 }} />
                        <Text strong>{record.guest_name}</Text>
                    </div>
                    <div style={{ fontSize: '12px', color: '#666' }}>
                        <PhoneOutlined style={{ marginRight: 4 }} />
                        {record.guest_phone}
                    </div>
                    <div style={{ fontSize: '12px', color: '#666' }}>
                        <MailOutlined style={{ marginRight: 4 }} />
                        {record.guest_email}
                    </div>
                </div>
            ),
        },
        {
            title: 'Phòng',
            key: 'room',
            width: 120,
            render: (_, record) => (
                <div>
                    <Tag icon={<HomeOutlined />} color="blue">
                        {record.room?.name || `Phòng ${record.room_id}`}
                    </Tag>
                    <div style={{ fontSize: '12px', color: '#666', marginTop: 4 }}>
                        {record.room?.room_type?.name}
                    </div>
                </div>
            ),
        },
        {
            title: 'Thời gian',
            key: 'dates',
            width: 120,
            render: (_, record) => (
                <div>
                    <div style={{ marginBottom: 4 }}>
                        <CalendarOutlined style={{ marginRight: 4, color: '#52c41a' }} />
                        <Text style={{ fontSize: '12px' }}>
                            Nhận: {dayjs(record.check_in_date).format('DD/MM/YYYY')}
                        </Text>
                    </div>
                    <div>
                        <CalendarOutlined style={{ marginRight: 4, color: '#ff4d4f' }} />
                        <Text style={{ fontSize: '12px' }}>
                            Trả: {dayjs(record.check_out_date).format('DD/MM/YYYY')}
                        </Text>
                    </div>
                    <div style={{ fontSize: '12px', color: '#666', marginTop: 4 }}>
                        {dayjs(record.check_out_date).diff(dayjs(record.check_in_date), 'day')} đêm
                    </div>
                </div>
            ),
        },
        {
            title: 'Số khách',
            dataIndex: 'guest_count',
            key: 'guest_count',
            width: 80,
            align: 'center',
            render: (count: number) => (
                <Badge count={count} style={{ backgroundColor: '#1890ff' }} />
            ),
        },
        {
            title: 'Tổng tiền',
            dataIndex: 'total_amount',
            key: 'total_amount',
            width: 120,
            align: 'right',
            render: (amount: number) => (
                <div>
                    <Text strong style={{ color: '#f50' }}>
                        {new Intl.NumberFormat('vi-VN').format(amount)} ₫
                    </Text>
                </div>
            ),
        },
        {
            title: 'Thanh toán',
            dataIndex: 'payment_status',
            key: 'payment_status',
            width: 120,
            render: (status: BookingPaymentStatus) => (
                <Tag color={paymentStatusConfig[status]?.color}>
                    {paymentStatusConfig[status]?.text}
                </Tag>
            ),
        },
        {
            title: 'Trạng thái',
            dataIndex: 'booking_status',
            key: 'booking_status',
            width: 120,
            render: (status: BookingStatus) => (
                <Tag color={bookingStatusConfig[status]?.color}>
                    {bookingStatusConfig[status]?.text}
                </Tag>
            ),
        },
        {
            title: 'Ngày tạo',
            dataIndex: 'created_at',
            key: 'created_at',
            width: 120,
            render: (date: string) => (
                <div>
                    <div>{dayjs(date).format('DD/MM/YYYY')}</div>
                    <div style={{ fontSize: '12px', color: '#666' }}>
                        {dayjs(date).format('HH:mm')}
                    </div>
                </div>
            ),
        },
        {
            title: 'Thao tác',
            key: 'actions',
            width: 120,
            fixed: 'right',
            render: (_, record) => {
                const statusMenuItems = [
                    {
                        key: 'confirmed',
                        label: 'Xác nhận',
                        disabled: record.booking_status !== 'pending',
                        onClick: () => handleStatusUpdate(record.id, 'confirmed'),
                    },
                    {
                        key: 'checked_in',
                        label: 'Nhận phòng',
                        disabled: record.booking_status !== 'confirmed',
                        onClick: () => handleStatusUpdate(record.id, 'checked_in'),
                    },
                    {
                        key: 'checked_out',
                        label: 'Trả phòng',
                        disabled: record.booking_status !== 'checked_in',
                        onClick: () => handleStatusUpdate(record.id, 'checked_out'),
                    },
                ];

                return (
                    <Space size="small">
                        <Tooltip title="Xem chi tiết">
                            <Button
                                type="text"
                                icon={<EyeOutlined />}
                                onClick={() => {
                                    setSelectedBooking(record);
                                    setIsDetailModalVisible(true);
                                }}
                            />
                        </Tooltip>
                        <Dropdown
                            menu={{
                                items: statusMenuItems,
                            }}
                            trigger={['click']}
                        >
                            <Button type="text" icon={<MoreOutlined />} />
                        </Dropdown>
                        {record.booking_status === 'pending' && (
                            <Tooltip title="Hủy đặt phòng">
                                <Button
                                    type="text"
                                    danger
                                    icon={<DeleteOutlined />}
                                    onClick={() => handleCancelBooking(record.id)}
                                />
                            </Tooltip>
                        )}
                    </Space>
                );
            },
        },
    ];

    return (
        <Layout className="min-h-screen">
            <Content className="p-6">
                <div className="mb-6">
                    <Title level={2} style={{ marginBottom: 8 }}>
                        Quản lý đặt phòng
                    </Title>
                    <Text type="secondary">
                        Quản lý và theo dõi các đặt phòng của khách sạn
                    </Text>
                </div>

                {/* Statistics Cards */}
                <Row gutter={[16, 16]} className="mb-6">
                    <Col xs={24} sm={12} md={6}>
                        <Card>
                            <Statistic
                                title="Tổng đặt phòng"
                                value={statistics.total_bookings || 0}
                                prefix={<CalendarOutlined />}
                            />
                        </Card>
                    </Col>
                    <Col xs={24} sm={12} md={6}>
                        <Card>
                            <Statistic
                                title="Chờ xác nhận"
                                value={statistics.pending_bookings || 0}
                                prefix={<ClockCircleOutlined />}
                                valueStyle={{ color: '#faad14' }}
                            />
                        </Card>
                    </Col>
                    <Col xs={24} sm={12} md={6}>
                        <Card>
                            <Statistic
                                title="Đã xác nhận"
                                value={statistics.confirmed_bookings || 0}
                                prefix={<CalendarOutlined />}
                                valueStyle={{ color: '#52c41a' }}
                            />
                        </Card>
                    </Col>
                    <Col xs={24} sm={12} md={6}>
                        <Card>
                            <Statistic
                                title="Doanh thu"
                                value={statistics.total_revenue || 0}
                                prefix={<DollarOutlined />}
                                formatter={(value) => `${new Intl.NumberFormat('vi-VN').format(Number(value))} ₫`}
                                valueStyle={{ color: '#f50' }}
                            />
                        </Card>
                    </Col>
                </Row>

                {/* Filter Bar */}
                <Card className="mb-6">
                    <Row gutter={[16, 16]}>
                        <Col xs={24} sm={12} md={6}>
                            <Input
                                placeholder="Tìm theo tên khách hàng"
                                prefix={<SearchOutlined />}
                                onChange={(e) => handleSearch({ ...filters, guest_name: e.target.value })}
                            />
                        </Col>
                        <Col xs={24} sm={12} md={6}>
                            <Input
                                placeholder="Mã đặt phòng"
                                onChange={(e) => handleSearch({ ...filters, booking_code: e.target.value })}
                            />
                        </Col>
                        <Col xs={24} sm={12} md={6}>
                            <Select
                                placeholder="Trạng thái thanh toán"
                                style={{ width: '100%' }}
                                allowClear
                                onChange={(value) => handleSearch({ ...filters, payment_status: value })}
                            >
                                {Object.entries(paymentStatusConfig).map(([key, config]) => (
                                    <Option key={key} value={key}>{config.text}</Option>
                                ))}
                            </Select>
                        </Col>
                        <Col xs={24} sm={12} md={6}>
                            <Select
                                placeholder="Trạng thái đặt phòng"
                                style={{ width: '100%' }}
                                allowClear
                                onChange={(value) => handleSearch({ ...filters, booking_status: value })}
                            >
                                {Object.entries(bookingStatusConfig).map(([key, config]) => (
                                    <Option key={key} value={key}>{config.text}</Option>
                                ))}
                            </Select>
                        </Col>
                        <Col xs={24} sm={12} md={8}>
                            <RangePicker
                                placeholder={['Ngày nhận phòng', 'Ngày trả phòng']}
                                style={{ width: '100%' }}
                                onChange={(dates) => {
                                    if (dates && dates[0] && dates[1]) {
                                        handleSearch({
                                            ...filters,
                                            date_range: [
                                                dates[0].format('YYYY-MM-DD'),
                                                dates[1].format('YYYY-MM-DD')
                                            ]
                                        });
                                    } else {
                                        const { date_range, ...newFilters } = filters;
                                        handleSearch(newFilters);
                                    }
                                }}
                            />
                        </Col>
                        <Col xs={24} sm={12} md={4}>
                            <Space>
                                <Button
                                    type="primary"
                                    icon={<PlusOutlined />}
                                    onClick={() => setIsCreateModalVisible(true)}
                                >
                                    Đặt phòng
                                </Button>
                                <Button icon={<ReloadOutlined />} onClick={() => refetch()} />
                            </Space>
                        </Col>
                    </Row>
                </Card>

                {/* Bookings Table */}
                <Card>
                    <Table
                        columns={columns}
                        dataSource={bookings}
                        loading={isLoading}
                        rowKey="id"
                        scroll={{ x: 1400 }}
                        pagination={{
                            total: bookingsData?.total || 0,
                            pageSize: 20,
                            showSizeChanger: true,
                            showQuickJumper: true,
                            showTotal: (total, range) =>
                                `${range[0]}-${range[1]} của ${total} đặt phòng`,
                        }}
                    />
                </Card>

                {/* Create Booking Modal */}
                <Modal
                    title="Tạo đặt phòng mới"
                    open={isCreateModalVisible}
                    onCancel={() => {
                        setIsCreateModalVisible(false);
                        createForm.resetFields();
                        setSelectedDates(null);
                    }}
                    footer={null}
                    width={800}
                >
                    <Form
                        form={createForm}
                        layout="vertical"
                        onFinish={handleCreateBooking}
                    >
                        <Row gutter={[16, 16]}>
                            <Col xs={24} sm={12}>
                                <Form.Item
                                    label="Tên khách hàng"
                                    name="guest_name"
                                    rules={[{ required: true, message: 'Vui lòng nhập tên khách hàng' }]}
                                >
                                    <Input placeholder="Nhập tên khách hàng" />
                                </Form.Item>
                            </Col>
                            <Col xs={24} sm={12}>
                                <Form.Item
                                    label="Email"
                                    name="guest_email"
                                    rules={[
                                        { required: true, message: 'Vui lòng nhập email' },
                                        { type: 'email', message: 'Email không hợp lệ' }
                                    ]}
                                >
                                    <Input placeholder="Nhập email" />
                                </Form.Item>
                            </Col>
                            <Col xs={24} sm={12}>
                                <Form.Item
                                    label="Số điện thoại"
                                    name="guest_phone"
                                    rules={[{ required: true, message: 'Vui lòng nhập số điện thoại' }]}
                                >
                                    <Input placeholder="Nhập số điện thoại" />
                                </Form.Item>
                            </Col>
                            <Col xs={24} sm={12}>
                                <Form.Item
                                    label="Số khách"
                                    name="guest_count"
                                    rules={[{ required: true, message: 'Vui lòng nhập số khách' }]}
                                >
                                    <Input type="number" min={1} placeholder="Nhập số khách" />
                                </Form.Item>
                            </Col>
                            <Col xs={24}>
                                <Form.Item
                                    label="Ngày nhận/trả phòng"
                                    name="date_range"
                                    rules={[{ required: true, message: 'Vui lòng chọn ngày nhận và trả phòng' }]}
                                >
                                    <RangePicker
                                        style={{ width: '100%' }}
                                        placeholder={['Ngày nhận phòng', 'Ngày trả phòng']}
                                        disabledDate={(current) => current && current < dayjs().startOf('day')}
                                        onChange={handleDateRangeChange}
                                    />
                                </Form.Item>
                            </Col>
                            <Col xs={24}>
                                <Form.Item
                                    label="Chọn phòng"
                                    name="room_id"
                                    rules={[{ required: true, message: 'Vui lòng chọn phòng' }]}
                                >
                                    <Select placeholder="Chọn phòng trống">
                                        {availableRooms.map((room: any) => (
                                            <Option key={room.id} value={room.id}>
                                                {room.name} - {room.room_type?.name}
                                                ({new Intl.NumberFormat('vi-VN').format(room.room_type?.base_price || 0)} ₫/đêm)
                                            </Option>
                                        ))}
                                    </Select>
                                </Form.Item>
                            </Col>
                            <Col xs={24} sm={12}>
                                <Form.Item
                                    label="Phương thức thanh toán"
                                    name="payment_method"
                                    rules={[{ required: true, message: 'Vui lòng chọn phương thức thanh toán' }]}
                                >
                                    <Select placeholder="Chọn phương thức thanh toán">
                                        <Option value="cash">Tiền mặt</Option>
                                        <Option value="bank_transfer">Chuyển khoản</Option>
                                        <Option value="credit_card">Thẻ tín dụng</Option>
                                        <Option value="vnpay">VNPay</Option>
                                    </Select>
                                </Form.Item>
                            </Col>
                            <Col xs={24}>
                                <Form.Item
                                    label="Yêu cầu đặc biệt"
                                    name="special_requests"
                                >
                                    <Input.TextArea
                                        placeholder="Nhập yêu cầu đặc biệt (nếu có)"
                                        rows={3}
                                    />
                                </Form.Item>
                            </Col>
                        </Row>
                        <Row justify="end" gutter={[8, 8]}>
                            <Col>
                                <Button onClick={() => {
                                    setIsCreateModalVisible(false);
                                    createForm.resetFields();
                                    setSelectedDates(null);
                                }}>
                                    Hủy
                                </Button>
                            </Col>
                            <Col>
                                <Button
                                    type="primary"
                                    htmlType="submit"
                                    loading={createBookingMutation.isPending}
                                >
                                    Tạo đặt phòng
                                </Button>
                            </Col>
                        </Row>
                    </Form>
                </Modal>

                {/* Booking Detail Modal */}
                <BookingDetailModal
                    visible={isDetailModalVisible}
                    onClose={() => {
                        setIsDetailModalVisible(false);
                        setSelectedBooking(null);
                    }}
                    bookingId={selectedBooking?.id || null}
                    onUpdate={() => {
                        refetch();
                    }}
                />
            </Content>
        </Layout>
    );
};

export default BookingManagement;
