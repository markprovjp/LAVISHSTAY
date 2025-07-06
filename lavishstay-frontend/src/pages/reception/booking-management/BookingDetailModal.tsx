import React, { useState, useEffect } from 'react';
import {
    Modal,
    Form,
    Input,
    Select,
    DatePicker,
    Button,
    Row,
    Col,
    Card,
    Tabs,
    Table,
    Tag,
    Space,
    Divider,
    Typography,
    Checkbox,
    InputNumber,
    message,
    Spin,
    Avatar,
    Badge,
    Tooltip,
    Dropdown,
    MenuProps,
    Progress,
    Statistic,
} from 'antd';
import {
    UserOutlined,
    PhoneOutlined,
    MailOutlined,
    CalendarOutlined,
    HomeOutlined,
    DollarOutlined,
    TeamOutlined,
    EditOutlined,
    SaveOutlined,
    CloseOutlined,
    SwapOutlined,
    CheckCircleOutlined,
    CloseCircleOutlined,
    ExclamationCircleOutlined,
    DeleteOutlined,
    MoreOutlined,
    ClockCircleOutlined,
    CreditCardOutlined,
} from '@ant-design/icons';
import dayjs from 'dayjs';
import type { ColumnsType } from 'antd/es/table';

const { Text } = Typography;
const { Option } = Select;
const { TabPane } = Tabs;


// Status badge component
const StatusBadge: React.FC<{ status: string; type: 'booking' | 'payment' | 'room' }> = ({ status, type }) => {
    const getConfig = () => {
        if (type === 'booking') {
            const configs = {
                pending: { color: '#fa8c16', bg: '#fff7e6', text: 'Chờ xác nhận' },
                confirmed: { color: '#1890ff', bg: '#e6f7ff', text: 'Đã xác nhận' },
                completed: { color: '#52c41a', bg: '#f6ffed', text: 'Hoàn thành' },
                cancelled: { color: '#ff4d4f', bg: '#fff2f0', text: 'Đã hủy' },
            };
            return configs[status as keyof typeof configs] || configs.pending;
        }
        if (type === 'payment') {
            const configs = {
                pending: { color: '#fa8c16', bg: '#fff7e6', text: 'Chờ thanh toán' },
                completed: { color: '#52c41a', bg: '#f6ffed', text: 'Đã thanh toán' },
                failed: { color: '#ff4d4f', bg: '#fff2f0', text: 'Thất bại' },
                refunded: { color: '#722ed1', bg: '#f9f0ff', text: 'Đã hoàn tiền' },
            };
            return configs[status as keyof typeof configs] || configs.pending;
        }
        // room status
        const configs = {
            available: { color: '#52c41a', bg: '#f6ffed', text: 'Trống' },
            occupied: { color: '#ff4d4f', bg: '#fff2f0', text: 'Có khách' },
            cleaning: { color: '#1890ff', bg: '#e6f7ff', text: 'Dọn dẹp' },
            maintenance: { color: '#fa8c16', bg: '#fff7e6', text: 'Bảo trì' },
            deposited: { color: '#722ed1', bg: '#f9f0ff', text: 'Đã cọc' },
            no_show: { color: '#ff7875', bg: '#fff2f0', text: 'Không đến' },
            check_in: { color: '#13c2c2', bg: '#e6fffb', text: 'Đang nhận' },
            check_out: { color: '#a0d911', bg: '#fcffe6', text: 'Đang trả' },
        };
        return configs[status as keyof typeof configs] || configs.available;
    };

    const config = getConfig();
    return (
        <span
            style={{
                padding: '4px 8px',
                borderRadius: '6px',
                fontSize: '12px',
                fontWeight: 500,
                color: config.color,
                backgroundColor: config.bg,
                border: `1px solid ${config.color}20`,
            }}
        >
            {config.text}
        </span>
    );
};

interface BookingRoom {
    booking_room_id: number;
    room_id: number;
    room_name: string;
    room_floor: number;
    room_status: string;
    room_type_name: string;
    room_type_price: number;
    max_guests: number;
    price_per_night: number;
    nights: number;
    total_price: number;
    check_in_date: string;
    check_out_date: string;
    representative_name?: string;
}

interface BookingDetail {
    id: number;
    booking_code: string;
    guest_name: string;
    guest_email: string;
    guest_phone: string;
    guest_count: number;
    check_in_date: string;
    check_out_date: string;
    total_price_vnd: number;
    status: string;
    quantity: number;
    created_at: string;
    updated_at: string;
    payment_status: string;
    payment_type: string;
    booking_rooms: BookingRoom[];
}

interface BookingDetailModalProps {
    visible: boolean;
    onClose: () => void;
    bookingId: number | null;
    onUpdate?: () => void;
}

const BookingDetailModal: React.FC<BookingDetailModalProps> = ({
    visible,
    onClose,
    bookingId,
    onUpdate
}) => {
    const [form] = Form.useForm();
    const [loading, setLoading] = useState(false);
    const [bookingDetail, setBookingDetail] = useState<BookingDetail | null>(null);
    const [isEditing, setIsEditing] = useState(false);
    const [selectedRooms, setSelectedRooms] = useState<number[]>([]);

    // Status configurations
    const bookingStatusConfig = {
        pending: { color: 'orange', text: 'Chờ xác nhận' },
        confirmed: { color: 'blue', text: 'Đã xác nhận' },
        completed: { color: 'green', text: 'Đã hoàn thành' },
        cancelled: { color: 'red', text: 'Đã hủy' },
    };

    const paymentStatusConfig = {
        pending: { color: 'orange', text: 'Chờ thanh toán' },
        completed: { color: 'green', text: 'Đã thanh toán' },
        failed: { color: 'red', text: 'Thanh toán thất bại' },
        refunded: { color: 'purple', text: 'Đã hoàn tiền' },
    };

    const roomStatusConfig = {
        available: { color: 'green', text: 'Trống' },
        occupied: { color: 'red', text: 'Có khách' },
        cleaning: { color: 'blue', text: 'Dọn dẹp' },
        maintenance: { color: 'orange', text: 'Bảo trì' },
        deposited: { color: 'purple', text: 'Đã cọc' },
        no_show: { color: 'volcano', text: 'Không đến' },
        check_in: { color: 'cyan', text: 'Đang nhận' },
        check_out: { color: 'lime', text: 'Đang trả' },
    };

    // Fetch booking details
    const fetchBookingDetails = async () => {
        if (!bookingId) return;

        setLoading(true);
        try {
            const response = await fetch(`http://localhost:8888/api/reception/bookings/${bookingId}`);
            const data = await response.json();

            if (data.success) {
                setBookingDetail(data.data);
                form.setFieldsValue({
                    guest_name: data.data.guest_name,
                    guest_email: data.data.guest_email,
                    guest_phone: data.data.guest_phone,
                    guest_count: data.data.guest_count,
                    check_in_date: dayjs(data.data.check_in_date),
                    check_out_date: dayjs(data.data.check_out_date),
                    status: data.data.status,
                    payment_status: data.data.payment_status,
                    payment_type: data.data.payment_type,
                });
            } else {
                message.error('Không thể tải thông tin đặt phòng');
            }
        } catch (error) {
            message.error('Có lỗi xảy ra khi tải thông tin');
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        if (visible && bookingId) {
            fetchBookingDetails();
        }
    }, [visible, bookingId]);

    // Handle form submit
    const handleSubmit = async (values: any) => {
        if (!bookingDetail) return;

        setLoading(true);
        try {
            const response = await fetch(`http://localhost:8888/api/reception/bookings/${bookingDetail.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    ...values,
                    check_in_date: values.check_in_date.format('YYYY-MM-DD'),
                    check_out_date: values.check_out_date.format('YYYY-MM-DD'),
                }),
            });

            const data = await response.json();
            if (data.success) {
                message.success('Cập nhật thông tin thành công');
                setIsEditing(false);
                fetchBookingDetails();
                onUpdate?.();
            } else {
                message.error(data.message || 'Không thể cập nhật thông tin');
            }
        } catch (error) {
            message.error('Có lỗi xảy ra khi cập nhật');
        } finally {
            setLoading(false);
        }
    };

    // Room action handlers
    const handleRoomAction = async (action: string, roomIds: number[]) => {
        if (roomIds.length === 0) {
            message.warning('Vui lòng chọn ít nhất một phòng');
            return;
        }

        try {
            const response = await fetch('http://localhost:8888/api/reception/room-actions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action,
                    booking_id: bookingDetail?.id,
                    room_ids: roomIds,
                }),
            });

            const data = await response.json();
            if (data.success) {
                message.success(`${action} thành công`);
                fetchBookingDetails();
                setSelectedRooms([]);
            } else {
                message.error(data.message || `Không thể ${action}`);
            }
        } catch (error) {
            message.error('Có lỗi xảy ra');
        }
    };

    // Room columns with beautiful design
    const roomColumns: ColumnsType<BookingRoom> = [
        {
            title: '',
            dataIndex: 'booking_room_id',
            width: 50,
            fixed: 'left',
            render: (id: number) => (
                <Checkbox
                    checked={selectedRooms.includes(id)}
                    onChange={(e) => {
                        if (e.target.checked) {
                            setSelectedRooms([...selectedRooms, id]);
                        } else {
                            setSelectedRooms(selectedRooms.filter(roomId => roomId !== id));
                        }
                    }}
                />
            ),
        },
        {
            title: 'Thông tin phòng',
            dataIndex: 'room_name',
            key: 'room_name',
            width: 200,
            fixed: 'left',
            render: (name: string, record: BookingRoom) => (
                <div style={{ display: 'flex', alignItems: 'center' }}>
                    <div style={{
                        width: 40,
                        height: 40,
                        borderRadius: '8px',
                        backgroundColor: '#f0f2ff',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        marginRight: 12,
                    }}>
                        <HomeOutlined style={{ color: '#1890ff', fontSize: '16px' }} />
                    </div>
                    <div>
                        <div style={{ fontWeight: 600, fontSize: '14px', color: '#262626', marginBottom: 2 }}>
                            {name}
                        </div>
                        <div style={{ fontSize: '12px', color: '#8c8c8c' }}>
                            Tầng {record.room_floor} • {record.room_type_name}
                        </div>
                        <div style={{ fontSize: '12px', color: '#8c8c8c' }}>
                            Tối đa {record.max_guests} khách
                        </div>
                    </div>
                </div>
            ),
        },
        {
            title: 'Trạng thái',
            dataIndex: 'room_status',
            key: 'room_status',
            width: 120,
            align: 'center',
            render: (status: string) => <StatusBadge status={status} type="room" />,
        },
        {
            title: 'Thời gian lưu trú',
            key: 'duration',
            width: 180,
            render: (_, record: BookingRoom) => (
                <div style={{ textAlign: 'center' }}>
                    <div style={{
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        marginBottom: 4,
                        padding: '4px 8px',
                        backgroundColor: '#f6ffed',
                        borderRadius: '4px',
                        fontSize: '12px',
                        fontWeight: 500,
                        color: '#52c41a'
                    }}>
                        <CalendarOutlined style={{ marginRight: 4 }} />
                        {dayjs(record.check_in_date).format('DD/MM')}
                    </div>
                    <div style={{
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        marginBottom: 4,
                        padding: '4px 8px',
                        backgroundColor: '#fff2f0',
                        borderRadius: '4px',
                        fontSize: '12px',
                        fontWeight: 500,
                        color: '#ff4d4f'
                    }}>
                        <CalendarOutlined style={{ marginRight: 4 }} />
                        {dayjs(record.check_out_date).format('DD/MM')}
                    </div>
                    <div style={{
                        fontSize: '12px',
                        color: '#1890ff',
                        fontWeight: 500,
                        textAlign: 'center'
                    }}>
                        {record.nights} đêm
                    </div>
                </div>
            ),
        },
        {
            title: 'Giá phòng',
            key: 'price',
            width: 150,
            align: 'right',
            render: (_, record: BookingRoom) => (
                <div style={{ textAlign: 'right' }}>
                    <div style={{
                        fontSize: '13px',
                        color: '#262626',
                        fontWeight: 500,
                        marginBottom: 2
                    }}>
                        {new Intl.NumberFormat('vi-VN').format(record.price_per_night)} ₫
                        <span style={{ fontSize: '11px', color: '#8c8c8c', fontWeight: 400 }}>/đêm</span>
                    </div>
                    <div style={{
                        fontSize: '15px',
                        fontWeight: 600,
                        color: '#f5222d',
                        marginTop: 4
                    }}>
                        {new Intl.NumberFormat('vi-VN').format(record.total_price)} ₫
                    </div>
                    <div style={{ fontSize: '11px', color: '#8c8c8c' }}>
                        tổng cộng
                    </div>
                </div>
            ),
        },
        {
            title: 'Người đại diện',
            dataIndex: 'representative_name',
            key: 'representative_name',
            width: 160,
            render: (name: string) => (
                name ? (
                    <div style={{ display: 'flex', alignItems: 'center' }}>
                        <Avatar
                            size={32}
                            icon={<UserOutlined />}
                            style={{
                                marginRight: 8,
                                backgroundColor: '#1890ff',
                                fontSize: '14px'
                            }}
                        />
                        <div>
                            <div style={{
                                fontSize: '13px',
                                fontWeight: 500,
                                color: '#262626',
                                marginBottom: 2
                            }}>
                                {name}
                            </div>
                            <div style={{ fontSize: '11px', color: '#8c8c8c' }}>
                                Đại diện
                            </div>
                        </div>
                    </div>
                ) : (
                    <div style={{
                        textAlign: 'center',
                        padding: '8px',
                        backgroundColor: '#fafafa',
                        borderRadius: '6px',
                        fontSize: '12px',
                        color: '#8c8c8c'
                    }}>
                        Chưa chỉ định
                    </div>
                )
            ),
        },
        {
            title: 'Thao tác',
            key: 'action',
            width: 100,
            fixed: 'right',
            align: 'center',
            render: (_, record: BookingRoom) => (
                <Dropdown
                    menu={{
                        items: [
                            {
                                key: 'transfer',
                                label: 'Đổi phòng',
                                icon: <SwapOutlined />,
                                onClick: () => handleRoomAction('transfer', [record.booking_room_id]),
                            },
                            {
                                key: 'check_in',
                                label: 'Check-in',
                                icon: <CheckCircleOutlined />,
                                onClick: () => handleRoomAction('check_in', [record.booking_room_id]),
                            },
                            {
                                key: 'check_out',
                                label: 'Check-out',
                                icon: <CloseCircleOutlined />,
                                onClick: () => handleRoomAction('check_out', [record.booking_room_id]),
                            },
                        ],
                    }}
                    trigger={['click']}
                >
                    <Button
                        type="text"
                        icon={<MoreOutlined />}
                        size="small"
                        style={{
                            borderRadius: '6px',
                        }}
                    />
                </Dropdown>
            ),
        },
    ];

    const roomActionMenuItems: MenuProps['items'] = [
        {
            key: 'transfer',
            label: 'Đổi phòng',
            icon: <SwapOutlined />,
            onClick: () => handleRoomAction('transfer', selectedRooms),
        },
        {
            key: 'check_in',
            label: 'Check-in',
            icon: <CheckCircleOutlined />,
            onClick: () => handleRoomAction('check_in', selectedRooms),
        },
        {
            key: 'check_out',
            label: 'Check-out',
            icon: <CloseCircleOutlined />,
            onClick: () => handleRoomAction('check_out', selectedRooms),
        },
        {
            type: 'divider',
        },
        {
            key: 'cancel_check_in',
            label: 'Hủy check-in',
            icon: <CloseOutlined />,
            onClick: () => handleRoomAction('cancel_check_in', selectedRooms),
        },
        {
            key: 'cancel_check_out',
            label: 'Hủy check-out',
            icon: <CloseOutlined />,
            onClick: () => handleRoomAction('cancel_check_out', selectedRooms),
        },
        {
            key: 'no_show',
            label: 'No show',
            icon: <ExclamationCircleOutlined />,
            danger: true,
            onClick: () => handleRoomAction('no_show', selectedRooms),
        },
        {
            key: 'cancel',
            label: 'Hủy',
            icon: <DeleteOutlined />,
            danger: true,
            onClick: () => handleRoomAction('cancel', selectedRooms),
        },
    ];

    return (
        <Modal
            title={
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <span>Chi tiết đặt phòng - {bookingDetail?.booking_code}</span>
                    <Space>
                        {isEditing ? (
                            <>
                                <Button
                                    type="primary"
                                    icon={<SaveOutlined />}
                                    onClick={() => form.submit()}
                                    loading={loading}
                                >
                                    Lưu
                                </Button>
                                <Button
                                    icon={<CloseOutlined />}
                                    onClick={() => setIsEditing(false)}
                                >
                                    Hủy
                                </Button>
                            </>
                        ) : (
                            <Button
                                type="primary"
                                icon={<EditOutlined />}
                                onClick={() => setIsEditing(true)}
                            >
                                Chỉnh sửa
                            </Button>
                        )}
                    </Space>
                </div>
            }
            open={visible}
            onCancel={onClose}
            footer={null}
            width={1200}
            style={{ top: 20 }}
        >
            <Spin spinning={loading}>
                {bookingDetail && (
                    <>


                        {/* Phần thông tin khách hàng */}
                        <Card
                            title="Thông tin đặt phòng"
                            style={{ marginBottom: 24 }}
                        >
                            <Form
                                form={form}
                                layout="vertical"
                                onFinish={handleSubmit}
                                disabled={!isEditing}
                            >
                                <Row gutter={[16, 16]}>
                                    <Col span={8}>
                                        <Form.Item
                                            label="Tên khách hàng"
                                            name="guest_name"
                                            rules={[{ required: true, message: 'Vui lòng nhập tên khách hàng' }]}
                                        >
                                            <Input prefix={<UserOutlined />} />
                                        </Form.Item>
                                    </Col>
                                    <Col span={8}>
                                        <Form.Item
                                            label="Số điện thoại"
                                            name="guest_phone"
                                            rules={[{ required: true, message: 'Vui lòng nhập số điện thoại' }]}
                                        >
                                            <Input prefix={<PhoneOutlined />} />
                                        </Form.Item>
                                    </Col>
                                    <Col span={8}>
                                        <Form.Item
                                            label="Email"
                                            name="guest_email"
                                            rules={[{ type: 'email', message: 'Email không hợp lệ' }]}
                                        >
                                            <Input prefix={<MailOutlined />} />
                                        </Form.Item>
                                    </Col>
                                    <Col span={6}>
                                        <Form.Item
                                            label="Ngày nhận phòng"
                                            name="check_in_date"
                                            rules={[{ required: true, message: 'Vui lòng chọn ngày nhận phòng' }]}
                                        >
                                            <DatePicker style={{ width: '100%' }} format="DD/MM/YYYY" />
                                        </Form.Item>
                                    </Col>
                                    <Col span={6}>
                                        <Form.Item
                                            label="Ngày trả phòng"
                                            name="check_out_date"
                                            rules={[{ required: true, message: 'Vui lòng chọn ngày trả phòng' }]}
                                        >
                                            <DatePicker style={{ width: '100%' }} format="DD/MM/YYYY" />
                                        </Form.Item>
                                    </Col>
                                    <Col span={6}>
                                        <Form.Item
                                            label="Số khách"
                                            name="guest_count"
                                            rules={[{ required: true, message: 'Vui lòng nhập số khách' }]}
                                        >
                                            <InputNumber
                                                style={{ width: '100%' }}
                                                min={1}
                                                prefix={<TeamOutlined />}
                                            />
                                        </Form.Item>
                                    </Col>
                                    <Col span={6}>
                                        <Form.Item label="Tổng tiền">
                                            <div style={{ display: 'flex', alignItems: 'center', height: 32 }}>
                                                <DollarOutlined style={{ marginRight: 8, color: '#f50' }} />
                                                <Text strong style={{ color: '#f50', fontSize: 16 }}>
                                                    {new Intl.NumberFormat('vi-VN').format(bookingDetail.total_price_vnd)} ₫
                                                </Text>
                                            </div>
                                        </Form.Item>
                                    </Col>
                                    <Col span={8}>
                                        <Form.Item
                                            label="Trạng thái đặt phòng"
                                            name="status"
                                        >
                                            <Select>
                                                <Option value="pending">Chờ xác nhận</Option>
                                                <Option value="confirmed">Đã xác nhận</Option>
                                                <Option value="completed">Đã hoàn thành</Option>
                                                <Option value="cancelled">Đã hủy</Option>
                                            </Select>
                                        </Form.Item>
                                    </Col>
                                    <Col span={8}>
                                        <Form.Item
                                            label="Trạng thái thanh toán"
                                            name="payment_status"
                                        >
                                            <Select>
                                                <Option value="pending">Chờ thanh toán</Option>
                                                <Option value="completed">Đã thanh toán</Option>
                                                <Option value="failed">Thanh toán thất bại</Option>
                                                <Option value="refunded">Đã hoàn tiền</Option>
                                            </Select>
                                        </Form.Item>
                                    </Col>
                                    <Col span={8}>
                                        <Form.Item
                                            label="Hình thức thanh toán"
                                            name="payment_type"
                                        >
                                            <Select>
                                                <Option value="deposit">Cọc trước</Option>
                                                <Option value="full">Thanh toán đầy đủ</Option>
                                                <Option value="qr_code">QR Code</Option>
                                                <Option value="at_hotel">Tại khách sạn</Option>
                                                <Option value="pay_now_with_vietQR">VietQR</Option>
                                            </Select>
                                        </Form.Item>
                                    </Col>
                                </Row>
                            </Form>
                        </Card>

                        {/* Phần Tabs */}
                        <Tabs defaultActiveKey="rooms">
                            <TabPane tab={
                                <span>
                                    <HomeOutlined style={{ marginRight: 8 }} />
                                    Chi tiết phòng ({bookingDetail.booking_rooms?.length || 0})
                                </span>
                            } key="rooms">
                                <Card
                                    style={{
                                        borderRadius: '12px',
                                        boxShadow: '0 2px 8px 0 rgba(0, 0, 0, 0.04)',
                                        border: '1px solid #f0f0f0'
                                    }}
                                    bodyStyle={{ padding: '20px' }}
                                >
                                    <div style={{
                                        marginBottom: 20,
                                        display: 'flex',
                                        justifyContent: 'space-between',
                                        alignItems: 'center',
                                        padding: '16px 20px',
                                        backgroundColor: '#fafafa',
                                        borderRadius: '8px',
                                        border: '1px solid #f0f0f0'
                                    }}>
                                        <Space size={16}>
                                            <Checkbox
                                                indeterminate={selectedRooms.length > 0 && selectedRooms.length < bookingDetail.booking_rooms.length}
                                                checked={selectedRooms.length === bookingDetail.booking_rooms.length && bookingDetail.booking_rooms.length > 0}
                                                onChange={(e) => {
                                                    if (e.target.checked) {
                                                        setSelectedRooms(bookingDetail.booking_rooms.map(room => room.booking_room_id));
                                                    } else {
                                                        setSelectedRooms([]);
                                                    }
                                                }}
                                            >
                                                <span style={{ fontWeight: 500, color: '#262626' }}>
                                                    Chọn tất cả
                                                </span>
                                            </Checkbox>
                                            <Badge
                                                count={selectedRooms.length}
                                                style={{ backgroundColor: '#1890ff' }}
                                                showZero={false}
                                            >
                                                <span style={{ color: '#8c8c8c', fontSize: '13px' }}>
                                                    ({selectedRooms.length}/{bookingDetail.booking_rooms.length} phòng)
                                                </span>
                                            </Badge>
                                        </Space>
                                        <Space>
                                            <Dropdown
                                                menu={{
                                                    items: roomActionMenuItems,
                                                }}
                                                disabled={selectedRooms.length === 0}
                                            >
                                                <Button
                                                    type="primary"
                                                    icon={<MoreOutlined />}
                                                    disabled={selectedRooms.length === 0}
                                                    style={{ borderRadius: '8px' }}
                                                >
                                                    Thao tác hàng loạt ({selectedRooms.length})
                                                </Button>
                                            </Dropdown>
                                        </Space>
                                    </div>
                                    <Table
                                        columns={roomColumns}
                                        dataSource={bookingDetail.booking_rooms}
                                        rowKey="booking_room_id"
                                        pagination={false}
                                        size="middle"
                                        scroll={{ x: 'max-content' }}
                                        rowClassName={(record) =>
                                            selectedRooms.includes(record.booking_room_id)
                                                ? 'selected-row'
                                                : ''
                                        }
                                    />
                                </Card>
                            </TabPane>
                            <TabPane tab="Hóa đơn" key="invoice">
                                <Card>
                                    <div style={{ textAlign: 'center', padding: '40px 0' }}>
                                        <Text type="secondary">Tính năng hóa đơn sẽ được phát triển trong phiên bản tiếp theo</Text>
                                    </div>
                                </Card>
                            </TabPane>
                        </Tabs>
                    </>
                )}
            </Spin>
        </Modal>
    );
};

export default BookingDetailModal;
