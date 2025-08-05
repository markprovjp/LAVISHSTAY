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
    Descriptions,
    Collapse
} from 'antd';
import { HomeOutlined, EditOutlined, SaveOutlined, CloseOutlined, MoreOutlined, UserOutlined, PhoneOutlined, MailOutlined, DollarOutlined, TeamOutlined, CreditCardOutlined, SwapOutlined, CheckCircleOutlined, CloseCircleOutlined, DeleteOutlined, ExclamationCircleOutlined, CalendarOutlined } from '@ant-design/icons';
import { BedDouble, CalendarDays, UserRound, Pencil, DoorOpen, LogOut, XCircle, Ban, ArrowRightLeft } from 'lucide-react';
import dayjs from 'dayjs';
import type { ColumnsType } from 'antd/es/table';
import RoomSelectionModal from './RoomSelectionModal';
import {
    ChangeRoomTab,
    ExtendStayTab,
    RescheduleTab,
    BookedDetailsTab,
    LateCheckOutTab,
    EarlyCheckOutTab
} from './components';

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
    room_id: number | null;
    room_name: string | null;
    room_floor: number | null;
    room_status: string | null;
    room_type: {
        name: string | null;
        description: string | null;
        base_price: number;
        max_guests: number;
        room_area: number | null;
    };
    option_name?: string;
    option_price?: number;
    price_per_night: number;
    nights: number;
    total_price: number;
    check_in_date: string;
    check_out_date: string;
    adults: number;
    children: number;
    children_age?: number[] | string;
    representative: {
        id: number;
        name: string;
        phone: string;
        email: string;
        date_of_birth?: string;
        identity_number: string;
        nationality?: string;
    };
}

interface Representative {
    id: number;
    full_name: string;
    phone_number: string;
    email: string;
    date_of_birth?: string;
    identity_number: string;
    nationality?: string;
    room_number?: string;
}

interface BookingDetail {
    booking_id: number;
    booking_code: string;
    guest_name: string;
    guest_email: string;
    guest_phone: string;
    guest_count: number;
    adults: number;
    children: number;
    children_age?: number[] | string;
    check_in_date: string;
    check_out_date: string;
    total_price_vnd: number;
    status: string;
    notes?: string;
    quantity: number;
    created_at: string;
    updated_at: string;
    payment: {
        amount_vnd: number;
        payment_type: string;
        status: string;
        transaction_id: string;
        created_at: string;
    } | null;
    booking_rooms: BookingRoom[];
    total_rooms: number;
    representatives: Representative[];
    id: number;
    payment_status: string;
    payment_type: string;
    total_amount: number;
    booking_status: string;
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
    // Room selection modal state
    const [roomSelectionModal, setRoomSelectionModal] = useState({
        visible: false,
        bookingRoomId: null,
        roomType: '',
        checkInDate: '',
        checkOutDate: '',
        requiredRooms: 1,
    });
    const handleOpenRoomSelectModal = (bookingRoomId: number) => {
        setRoomSelectionModal({
            visible: true,
            bookingRoomId,
        });
    };
    const fetchBookingDetails = async () => {
        if (!bookingId) return;
        setLoading(true);
        try {
            const response = await fetch(`http://localhost:8888/api/reception/bookings/${bookingId}`);
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            const rawData = await response.json();
            if (rawData.success && rawData.data && Array.isArray(rawData.data) && rawData.data.length > 0) {
                const data = rawData.data[0];
                data.status = data.status?.toLowerCase() || 'pending';
                data.payment_status = data.payment_status?.toLowerCase() || 'pending';
                data.children_age = Array.isArray(data.children_age) ? data.children_age : [];
                let rooms = Array.isArray(data.booking_rooms) ? data.booking_rooms : [];
                rooms = rooms.map((room: BookingRoom) => ({
                    ...room,
                    children_age: Array.isArray(room.children_age) ? room.children_age : [],
                }));
                data.booking_rooms = rooms;
                let representatives = Array.isArray(data.representatives) ? data.representatives : [];
                data.representatives = representatives;
                data.payment = data.payment || null;
                setBookingDetail(data);
                form.setFieldsValue({
                    guest_name: data.guest_name || '',
                    guest_email: data.guest_email || '',
                    guest_phone: data.guest_phone || '',
                    check_in_date: data.check_in_date ? dayjs(data.check_in_date) : null,
                    check_out_date: data.check_out_date ? dayjs(data.check_out_date) : null,
                    status: data.status,
                    notes: data.notes || '',
                    payment_status: data.payment_status,
                    payment_type: data.payment_type || '',
                });
            } else {
                console.error('No valid data found:', rawData);
                message.error('Không thể tải thông tin đặt phòng');
                setBookingDetail(null);
            }
        } catch (error) {
            console.error('Error fetching booking details:', error);
            message.error('Có lỗi xảy ra khi tải thông tin');
            setBookingDetail(null);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        if (visible && bookingId) {
            fetchBookingDetails();
        }
    }, [visible, bookingId]);

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
                            {name || record.option_name || 'Chưa chỉ định phòng'}
                        </div>
                        <div style={{ fontSize: '12px', color: '#8c8c8c' }}>
                            Tầng {record.room_floor || 'N/A'} • {record.room_type?.name || 'N/A'}
                        </div>
                        {isUnassigned && (
                            <Button
                                type="primary"
                                size="small"
                                style={{ borderRadius: '6px', marginTop: 4 }}
                                onClick={() => {
                                    setRoomSelectionModal({
                                        visible: true,
                                        bookingRoomId: record.booking_room_id,
                                        roomType: record.room_type?.name || '',
                                        checkInDate: record.check_in_date,
                                        checkOutDate: record.check_out_date,
                                        requiredRooms: 1,
                                    });
                                }}
                            >
                                Chọn phòng
                            </Button>
                        )}
                        <div style={{ fontSize: '12px', color: '#8c8c8c' }}>
                            Tối đa {record.room_type?.max_guests || 0} khách • {record.room_type?.room_area || 0}m²
                        </div>
                    </div>
                </div>
            ),
        },
        {
            title: 'Thông tin khách',
            key: 'guest_info',
            width: 160,
            render: (_, record: BookingRoom) => (
                <div>
                    <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: 4 }}>
                        <span style={{ fontSize: '12px', color: '#8c8c8c' }}>Người lớn:</span>
                        <span style={{ fontSize: '12px', fontWeight: 500, color: '#1890ff' }}>
                            {Number(record.adults || 0)}
                        </span>

                    </div>
                    <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: 4 }}>
                        <span style={{ fontSize: '12px', color: '#8c8c8c' }}>Trẻ em:</span>
                        <span style={{ fontSize: '12px', fontWeight: 500, color: '#fa8c16' }}>
                            {Number(record.children || 0)}
                        </span>
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
            render: (status: string) => <StatusBadge status={status || 'available'} type="room" />,
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
            key: 'representative',
            width: 160,
            render: (_, record: BookingRoom) => {
                const rep = record.representative;
                if (rep && rep.name) {
                    return (
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
                                    {rep.name}
                                </div>
                                <div style={{ fontSize: '11px', color: '#8c8c8c' }}>
                                    {rep.phone || 'Chưa có SĐT'}
                                </div>
                                <div style={{ fontSize: '10px', color: '#8c8c8c' }}>
                                    ID: {rep.identity_number || 'Chưa có CCCD'}
                                </div>
                            </div>
                        </div>
                    );
                }
                return (
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
                );
            },
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
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', padding: '0 14px' }}>
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
            width={1300}
            style={{ top: 20 }}
        >
            <Spin spinning={loading}>
                {bookingDetail && (
                    <>
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

                        <Tabs defaultActiveKey="rooms">
                            <TabPane tab={
                                <span>
                                    <HomeOutlined style={{ marginRight: 8 }} />
                                    Chi tiết phòng ({Array.isArray(bookingDetail.booking_rooms) ? bookingDetail.booking_rooms.length : 0})
                                </span>
                            } key="rooms">
                                <Collapse
                                    accordion
                                    style={{ background: '#fff', borderRadius: 12, boxShadow: '0 2px 8px 0 rgba(0,0,0,0.04)', border: '1px solid #f0f0f0' }}
                                >
                                    {Array.isArray(bookingDetail.booking_rooms) && bookingDetail.booking_rooms.length > 0 ? (
                                        bookingDetail.booking_rooms.map((room, idx) => (
                                            <Collapse.Panel
                                                header={
                                                    <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                                                        <BedDouble size={20} style={{ color: '#1890ff' }} />
                                                        <span style={{ fontWeight: 600, fontSize: '15px' }}>{room.option_name || 'Chưa chỉ định'} - Phòng #{idx + 1}</span>
                                                        {room.room_name ? (
                                                            <span style={{ color: '#52c41a', fontWeight: 500, marginLeft: 8 }}><DoorOpen size={18} /> {room.room_name}</span>
                                                        ) : (
                                                            <Button type="dashed" icon={<DoorOpen size={16} />} size="small" onClick={() => handleOpenRoomSelectModal(room.booking_room_id)} style={{ marginLeft: 8 }}>
                                                                Chọn phòng
                                                            </Button>
                                                        )}
                                                    </div>
                                                }
                                                key={room.booking_room_id}
                                            >
                                                <Card
                                                    style={{ borderRadius: 8, background: '#fafafa', border: '1px solid #f0f0f0', marginBottom: 12 }}
                                                    bodyStyle={{ padding: '16px' }}
                                                >
                                                    <Descriptions
                                                        title={<span><CalendarDays size={16} style={{ marginRight: 6 }} />Thông tin phòng</span>}
                                                        bordered
                                                        size="middle"
                                                        column={2}
                                                        style={{ marginBottom: 12 }}
                                                    >
                                                        <Descriptions.Item label={<span><UserRound size={14} style={{ marginRight: 4 }} />Người lớn</span>}>{room.adults} người</Descriptions.Item>
                                                        <Descriptions.Item label={<span><UserRound size={14} style={{ marginRight: 4 }} />Trẻ em</span>}>{room.children} trẻ</Descriptions.Item>
                                                        <Descriptions.Item label={<CalendarDays size={14} style={{ marginRight: 4 }} />}>Ngày nhận phòng: {room.check_in_date}</Descriptions.Item>
                                                        <Descriptions.Item label={<CalendarDays size={14} style={{ marginRight: 4 }} />}>Ngày trả phòng: {room.check_out_date}</Descriptions.Item>
                                                        <Descriptions.Item label={<span><LogOut size={14} style={{ marginRight: 4 }} />Số đêm</span>}>{room.nights}</Descriptions.Item>
                                                        <Descriptions.Item label={<DollarOutlined style={{ marginRight: 4, color: '#f50' }} />}>Tổng tiền: <span style={{ color: '#f50', fontWeight: 600 }}>{new Intl.NumberFormat('vi-VN').format(room.total_price || 0)} ₫</span></Descriptions.Item>
                                                    </Descriptions>
                                                    {room.children > 0 && Array.isArray(room.children_age) && room.children_age.length > 0 && (
                                                        <Descriptions
                                                            title={<span><UserRound size={14} style={{ marginRight: 4 }} />Độ tuổi trẻ em</span>}
                                                            bordered
                                                            size="small"
                                                            column={2}
                                                            style={{ marginBottom: 12, background: '#fffbe6', borderRadius: 8 }}
                                                        >
                                                            {room.children_age.map((age, index) => (
                                                                <Descriptions.Item
                                                                    key={index}
                                                                    label={`Trẻ em ${index + 1}`}
                                                                    labelStyle={{ fontWeight: 500, color: '#fa8c16' }}
                                                                    contentStyle={{ color: '#fa8c16', fontWeight: 500 }}
                                                                >
                                                                    {age} tuổi
                                                                </Descriptions.Item>
                                                            ))}
                                                        </Descriptions>
                                                    )}
                                                    <Divider style={{ margin: '12px 0' }} />
                                                    <Descriptions
                                                        title={<span><UserRound size={14} style={{ marginRight: 4 }} />Người đại diện</span>}
                                                        bordered
                                                        size="small"
                                                        column={2}
                                                    >
                                                        <Descriptions.Item label="Tên">{room.representative?.name || '-'}</Descriptions.Item>
                                                        <Descriptions.Item label="SĐT">{room.representative?.phone || '-'}</Descriptions.Item>
                                                        <Descriptions.Item label="Email">{room.representative?.email || '-'}</Descriptions.Item>
                                                        <Descriptions.Item label="CMND/CCCD">{room.representative?.identity_number || '-'}</Descriptions.Item>
                                                    </Descriptions>
                                                    <Divider style={{ margin: '12px 0' }} />
                                                    <Space wrap>
                                                        <Button icon={<ArrowRightLeft size={16} />} onClick={() => handleRoomAction('transfer', [room.booking_room_id])}>Đổi phòng</Button>
                                                        <Button icon={<LogOut size={16} />} onClick={() => handleRoomAction('check_in', [room.booking_room_id])}>Check-in</Button>
                                                        <Button icon={<XCircle size={16} />} onClick={() => handleRoomAction('check_out', [room.booking_room_id])}>Check-out</Button>
                                                        <Button icon={<Ban size={16} />} danger onClick={() => handleRoomAction('cancel', [room.booking_room_id])}>Hủy</Button>
                                                        <Button icon={<ExclamationCircleOutlined />} danger onClick={() => handleRoomAction('no_show', [room.booking_room_id])}>No show</Button>
                                                    </Space>
                                                </Card>
                                            </Collapse.Panel>
                                        ))
                                    ) : (
                                        <div style={{ textAlign: 'center', padding: '40px 0' }}>
                                            <Text type="secondary">Chưa có thông tin phòng</Text>
                                        </div>
                                    )}
                                </Collapse>
                            </TabPane>
                            <TabPane tab={
                                <span>
                                    <UserOutlined style={{ marginRight: 8 }} />
                                    Chi tiết đã đặt ({Array.isArray(bookingDetail.booking_rooms) ? bookingDetail.booking_rooms.length : 0})
                                </span>
                            } key="bookedDetails">
                                <Card
                                    style={{
                                        borderRadius: '12px',
                                        boxShadow: '0 2px 8px 0 rgba(0, 0, 0, 0.04)',
                                        border: '1px solid #f0f0f0'
                                    }}
                                    bodyStyle={{ padding: '20px' }}
                                >
                                    <div style={{ marginBottom: 20 }}>
                                        <Text strong style={{ fontSize: '16px', color: '#262626' }}>
                                            Thông tin các phòng đã đặt
                                        </Text>
                                    </div>
                                    {Array.isArray(bookingDetail?.booking_rooms) && bookingDetail.booking_rooms.length > 0 ? (
                                        <Row gutter={[16, 16]}>
                                            {bookingDetail.booking_rooms.map((room) => (
                                                <Col span={12} key={room.booking_room_id}>
                                                    <Card
                                                        size="small"
                                                        style={{
                                                            border: '1px solid #f0f0f0',
                                                            borderRadius: '8px',
                                                            backgroundColor: '#fafafa'
                                                        }}
                                                        title={
                                                            <div style={{ display: 'flex', alignItems: 'center' }}>
                                                                <HomeOutlined style={{ marginRight: 8, color: '#1890ff' }} />
                                                                <span style={{ fontWeight: 600, fontSize: '14px' }}>
                                                                    {room.option_name || 'Chưa chỉ định'}
                                                                </span>
                                                            </div>
                                                        }
                                                    >
                                                        <div style={{ marginBottom: 12 }}>
                                                            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: 8 }}>
                                                                <span style={{ color: '#8c8c8c', fontSize: '12px' }}>Giá mỗi đêm:</span>
                                                                <span style={{ fontWeight: 500, color: '#1890ff' }}>{new Intl.NumberFormat('vi-VN').format(room.option_price || 0)} ₫</span>
                                                            </div>
                                                            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: 8 }}>
                                                                <span style={{ color: '#8c8c8c', fontSize: '12px' }}>Người lớn:</span>
                                                                <span style={{ fontWeight: 500, color: '#1890ff' }}>{room.adults} người</span>
                                                            </div>
                                                            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: 8 }}>
                                                                <span style={{ color: '#8c8c8c', fontSize: '12px' }}>Trẻ em:</span>
                                                                <span style={{ fontWeight: 500, color: '#fa8c16' }}>{room.children} trẻ</span>
                                                            </div>
                                                            {room.children > 0 && Array.isArray(room.children_age) && room.children_age.length > 0 && (
                                                                <div style={{ marginTop: 8 }}>
                                                                    <div style={{ color: '#8c8c8c', fontSize: '12px', marginBottom: 4 }}>
                                                                        Độ tuổi trẻ em:
                                                                    </div>
                                                                    <div style={{
                                                                        padding: '6px 8px',
                                                                        backgroundColor: '#fff7e6',
                                                                        borderRadius: '4px',
                                                                        fontSize: '12px',
                                                                        color: '#fa8c16',
                                                                        fontWeight: 500
                                                                    }}>
                                                                        {room.children_age.map((age, index) => `Trẻ ${index + 1}: ${age} tuổi`).join(' • ')}
                                                                    </div>
                                                                </div>
                                                            )}
                                                        </div>
                                                        <Divider style={{ margin: '12px 0' }} />
                                                        <div>
                                                            <div style={{ color: '#8c8c8c', fontSize: '12px', marginBottom: 8 }}>
                                                                Người đại diện:
                                                            </div>
                                                            {room.representative && room.representative.name ? (
                                                                <div style={{ display: 'flex', alignItems: 'center' }}>
                                                                    <Avatar
                                                                        size={32}
                                                                        icon={<UserOutlined />}
                                                                        style={{
                                                                            marginRight: 8,
                                                                            backgroundColor: '#52c41a'
                                                                        }}
                                                                    />
                                                                    <div>
                                                                        <div style={{ fontWeight: 600, fontSize: '13px', color: '#262626' }}>
                                                                            {room.representative.name}
                                                                        </div>
                                                                        <div style={{ fontSize: '11px', color: '#8c8c8c' }}>
                                                                            📞 {room.representative.phone || 'Chưa có SĐT'}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            ) : (
                                                                <div style={{
                                                                    textAlign: 'center',
                                                                    padding: '12px',
                                                                    backgroundColor: '#f5f5f5',
                                                                    borderRadius: '6px',
                                                                    color: '#8c8c8c',
                                                                    fontSize: '12px'
                                                                }}>
                                                                    Chưa chỉ định người đại diện
                                                                </div>
                                                            )}
                                                        </div>
                                                    </Card>
                                                </Col>
                                            ))}
                                        </Row>
                                    ) : (
                                        <div style={{ textAlign: 'center', padding: '40px 0' }}>
                                            <Text type="secondary">Chưa có thông tin phòng</Text>
                                        </div>
                                    )}
                                </Card>
                            </TabPane>
                            <TabPane tab={
                                <span>
                                    <CreditCardOutlined style={{ marginRight: 8 }} />
                                    Thanh toán
                                </span>
                            } key="payment">
                                <Card
                                    style={{
                                        borderRadius: '12px',
                                        boxShadow: '0 2px 8px 0 rgba(0, 0, 0, 0.04)',
                                        border: '1px solid #f0f0f0'
                                    }}
                                    bodyStyle={{ padding: '20px' }}
                                >
                                    {bookingDetail.payment ? (
                                        <Row gutter={[24, 24]}>
                                            <Col span={12}>
                                                <Statistic
                                                    title="Số tiền thanh toán"
                                                    value={bookingDetail.payment.amount_vnd}
                                                    formatter={(value) => `${new Intl.NumberFormat('vi-VN').format(Number(value))} ₫`}
                                                    valueStyle={{ color: '#f50', fontSize: '24px', fontWeight: 600 }}
                                                />
                                            </Col>
                                            <Col span={12}>
                                                <div style={{ marginBottom: 16 }}>
                                                    <Text strong style={{ fontSize: '14px', color: '#262626' }}>
                                                        Trạng thái:
                                                    </Text>
                                                    <span style={{ marginLeft: 8 }}>
                                                        <StatusBadge status={bookingDetail.payment.status} type="payment" />
                                                    </span>
                                                </div>
                                                <div style={{ marginBottom: 16 }}>
                                                    <Text strong style={{ fontSize: '14px', color: '#262626' }}>
                                                        Hình thức:
                                                    </Text>
                                                    <span style={{ marginLeft: 8, fontSize: '14px' }}>
                                                        {bookingDetail.payment.payment_type || 'Chưa xác định'}
                                                    </span>
                                                </div>
                                                {bookingDetail.payment.transaction_id && (
                                                    <div style={{ marginBottom: 16 }}>
                                                        <Text strong style={{ fontSize: '14px', color: '#262626' }}>
                                                            Mã giao dịch:
                                                        </Text>
                                                        <span style={{ marginLeft: 8, fontSize: '14px', fontFamily: 'monospace' }}>
                                                            {bookingDetail.payment.transaction_id}
                                                        </span>
                                                    </div>
                                                )}
                                                <div>
                                                    <Text strong style={{ fontSize: '14px', color: '#262626' }}>
                                                        Thời gian thanh toán:
                                                    </Text>
                                                    <span style={{ marginLeft: 8, fontSize: '14px' }}>
                                                        {dayjs(bookingDetail.payment.created_at).format('DD/MM/YYYY HH:mm')}
                                                    </span>
                                                </div>
                                            </Col>
                                        </Row>
                                    ) : (
                                        <div style={{ textAlign: 'center', padding: '40px 0' }}>
                                            <Text type="secondary">Chưa có thông tin thanh toán</Text>
                                        </div>
                                    )}
                                </Card>
                            </TabPane>
                            <TabPane tab="Hóa đơn" key="invoice">
                                <Card>
                                    <div style={{ textAlign: 'center', padding: '40px 0' }}>
                                        <Text type="secondary">Tính năng hóa đơn sẽ được phát triển trong phiên bản tiếp theo</Text>
                                    </div>
                                </Card>
                            </TabPane>
                            <TabPane tab="Gia hạn" key="extend">
                                <ExtendStayTab />
                            </TabPane>
                            <TabPane tab="Dời lịch" key="reschedule">
                                <RescheduleTab />
                            </TabPane>
                            <TabPane tab="Chuyển phòng" key="changeroom">
                                <ChangeRoomTab
                                    bookingId={bookingDetail?.id || bookingDetail?.booking_id || null}
                                    bookingRooms={bookingDetail?.booking_rooms || []}
                                    onUpdate={fetchBookingDetails}
                                />
                            </TabPane>
                        </Tabs>
                        <RoomSelectionModal
                            visible={roomSelectionModal.visible}
                            onClose={() => setRoomSelectionModal({ visible: false, bookingRoomId: null })}
                            bookingId={bookingDetail?.id || null}
                            bookingRoomId={roomSelectionModal.bookingRoomId}
                            onAssignmentSuccess={() => {
                                setRoomSelectionModal({ visible: false, bookingRoomId: null });
                                fetchBookingDetails();
                            }}
                        />
                    </>
                )}
            </Spin>
        </Modal>

    );
};

export default BookingDetailModal;