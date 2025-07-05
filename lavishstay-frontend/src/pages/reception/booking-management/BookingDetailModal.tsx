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
} from '@ant-design/icons';
import dayjs from 'dayjs';
import type { ColumnsType } from 'antd/es/table';

const { Title, Text } = Typography;
const { TextArea } = Input;
const { Option } = Select;
const { TabPane } = Tabs;

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

    // Room columns
    const roomColumns: ColumnsType<BookingRoom> = [
        {
            title: '',
            dataIndex: 'booking_room_id',
            width: 50,
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
            title: 'Phòng',
            dataIndex: 'room_name',
            key: 'room_name',
            render: (name: string, record: BookingRoom) => (
                <div>
                    <div style={{ display: 'flex', alignItems: 'center', marginBottom: 4 }}>
                        <HomeOutlined style={{ marginRight: 8, color: '#1890ff' }} />
                        <Text strong>{name}</Text>
                    </div>
                    <Text type="secondary" style={{ fontSize: '12px' }}>
                        Tầng {record.room_floor} • {record.room_type_name}
                    </Text>
                </div>
            ),
        },
        {
            title: 'Trạng thái phòng',
            dataIndex: 'room_status',
            key: 'room_status',
            render: (status: string) => (
                <Tag color={roomStatusConfig[status as keyof typeof roomStatusConfig]?.color}>
                    {roomStatusConfig[status as keyof typeof roomStatusConfig]?.text}
                </Tag>
            ),
        },
        {
            title: 'Thời gian',
            key: 'duration',
            render: (_, record: BookingRoom) => (
                <div>
                    <div>
                        <CalendarOutlined style={{ marginRight: 4, color: '#52c41a' }} />
                        <Text style={{ fontSize: '12px' }}>
                            {dayjs(record.check_in_date).format('DD/MM/YYYY')}
                        </Text>
                    </div>
                    <div>
                        <CalendarOutlined style={{ marginRight: 4, color: '#ff4d4f' }} />
                        <Text style={{ fontSize: '12px' }}>
                            {dayjs(record.check_out_date).format('DD/MM/YYYY')}
                        </Text>
                    </div>
                    <Text type="secondary" style={{ fontSize: '12px' }}>
                        {record.nights} đêm
                    </Text>
                </div>
            ),
        },
        {
            title: 'Giá phòng',
            key: 'price',
            align: 'right',
            render: (_, record: BookingRoom) => (
                <div>
                    <div>
                        <Text strong>
                            {new Intl.NumberFormat('vi-VN').format(record.price_per_night)} ₫
                        </Text>
                        <Text type="secondary" style={{ fontSize: '12px', display: 'block' }}>
                            /đêm
                        </Text>
                    </div>
                    <div style={{ marginTop: 4 }}>
                        <Text strong style={{ color: '#f50' }}>
                            {new Intl.NumberFormat('vi-VN').format(record.total_price)} ₫
                        </Text>
                        <Text type="secondary" style={{ fontSize: '12px', display: 'block' }}>
                            tổng cộng
                        </Text>
                    </div>
                </div>
            ),
        },
        {
            title: 'Đại diện',
            dataIndex: 'representative_name',
            key: 'representative_name',
            render: (name: string) => (
                name ? (
                    <div style={{ display: 'flex', alignItems: 'center' }}>
                        <Avatar size="small" icon={<UserOutlined />} style={{ marginRight: 8 }} />
                        <Text>{name}</Text>
                    </div>
                ) : (
                    <Text type="secondary">Chưa chỉ định</Text>
                )
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
                            <TabPane tab="Chi tiết phòng" key="rooms">
                                <Card>
                                    <div style={{ marginBottom: 16, display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                                        <Space>
                                            <Checkbox
                                                indeterminate={selectedRooms.length > 0 && selectedRooms.length < bookingDetail.booking_rooms.length}
                                                checked={selectedRooms.length === bookingDetail.booking_rooms.length}
                                                onChange={(e) => {
                                                    if (e.target.checked) {
                                                        setSelectedRooms(bookingDetail.booking_rooms.map(room => room.booking_room_id));
                                                    } else {
                                                        setSelectedRooms([]);
                                                    }
                                                }}
                                            >
                                                Chọn tất cả ({selectedRooms.length}/{bookingDetail.booking_rooms.length})
                                            </Checkbox>
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
                                                >
                                                    Thao tác ({selectedRooms.length})
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
