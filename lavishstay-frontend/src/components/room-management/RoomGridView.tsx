import React, { memo, useState } from 'react';
import { Card, Row, Col, Typography, Empty, Spin, Tag, Checkbox, Button, Space, Dropdown, Menu, Modal, Form, Input, InputNumber, Select, Divider, Flex } from 'antd';
import { UserOutlined, CalendarOutlined, EditOutlined, CheckCircleOutlined, CloseCircleOutlined, ToolOutlined, PlusOutlined, SettingOutlined, MinusCircleOutlined, TeamOutlined, SmileOutlined } from '@ant-design/icons';
import { statusOptions } from '../../constants/roomStatus';
import { User, Clock, CheckCircle, AlertCircle, Wrench, Sparkles } from 'lucide-react';
import { useUpdateRoomStatus } from '../../hooks/useReception';
import dayjs from 'dayjs';

const { Text, Title } = Typography;
const { Option } = Select;

interface Child {
    age: number;
}

interface BookingData {
    adults: number;
    children: Child[];
    notes?: string;
    checkInDate?: string;
    checkOutDate?: string;
}

interface RoomGridViewProps {
    rooms: any[];
    loading?: boolean;
    onRoomClick?: (room: any) => void;
    multiSelectMode?: boolean;
    selectedRooms?: Set<string>;
    onRoomSelect?: (roomId: string, selected: boolean) => void;
    onProceedToBooking?: () => void;
    guestCount?: number;
    onGuestCountChange?: (count: number) => void;
    navigate?: (path: string, options?: any) => void;
    hasDateFilter?: boolean;
    checkInDate?: string;
    checkOutDate?: string;
}

const RoomGridView: React.FC<RoomGridViewProps> = ({
    rooms,
    loading = false,
    onRoomClick,
    multiSelectMode = false,
    selectedRooms = new Set(),
    onRoomSelect,
    onProceedToBooking,
    guestCount = 2,
    onGuestCountChange,
    navigate,
    hasDateFilter = false,
    checkInDate,
    checkOutDate
}) => {
    const updateRoomStatusMutation = useUpdateRoomStatus();
    const [bookingModal, setBookingModal] = useState(false);
    const [selectedRoomForBooking, setSelectedRoomForBooking] = useState<any>(null);
    const [bookingForm] = Form.useForm();

    const handleRoomStatusUpdate = (roomId: string, newStatus: string) => {
        updateRoomStatusMutation.mutate({
            roomId: parseInt(roomId),
            status: newStatus
        });
    };

    const handleNormalBookConfirm = async () => {
        if (!selectedRoomForBooking) return;

        try {
            const values = await bookingForm.validateFields();
            const bookingData: BookingData = {
                adults: values.adults || 1,
                children: values.children || [],
                notes: values.notes,
                checkInDate: checkInDate,
                checkOutDate: checkOutDate
            };

            if (navigate) {
                navigate('/reception/confirm-representative-payment', {
                    state: {
                        selectedRooms: [selectedRoomForBooking],
                        bookingData,
                        guestCount: bookingData.adults + (bookingData.children?.length || 0),
                        checkInDate,
                        checkOutDate
                    }
                });
            }
            setBookingModal(false);
            bookingForm.resetFields();
        } catch (error) {
            console.error('Validation failed:', error);
        }
    };

    const handleQuickBook = async (nights: number) => {
        if (!selectedRoomForBooking) return;

        try {
            const values = await bookingForm.validateFields();
            const bookingData: BookingData = {
                adults: values.adults || 1,
                children: values.children || [],
                notes: values.notes
            };

            if (navigate) {
                navigate('/reception/confirm-representative-payment', {
                    state: {
                        selectedRooms: [selectedRoomForBooking],
                        bookingData,
                        guestCount: bookingData.adults + (bookingData.children?.length || 0),
                        quickBookNights: nights
                    }
                });
            }
            setBookingModal(false);
            bookingForm.resetFields();
        } catch (error) {
            console.error('Validation failed:', error);
        }
    };

    const handleNormalBook = (room: any) => {
        setSelectedRoomForBooking(room);
        setBookingModal(true);
        bookingForm.setFieldsValue({ adults: 1, children: [] });
    };

    const handleBookRoom = (room: any) => {
        setSelectedRoomForBooking(room);
        setBookingModal(true);
        bookingForm.setFieldsValue({ adults: 1, children: [] });
    };

    const handleCancelBooking = () => {
        setBookingModal(false);
        bookingForm.resetFields();
        setSelectedRoomForBooking(null);
    };

    const getRoomContextMenu = (room: any) => {
        const menuItems = [];
        if (room.status === 'available') {
            const label = hasDateFilter ? 'Đặt phòng' : 'Đặt phòng nhanh';
            const key = hasDateFilter ? 'book-normal' : 'book';
            menuItems.push({ key, icon: <PlusOutlined />, label });
            menuItems.push({ type: 'divider' });
        }

        menuItems.push({
            key: 'status',
            label: 'Cập nhật trạng thái',
            icon: <SettingOutlined />,
            children: statusOptions.map(s => ({
                key: `status-${s.value}`,
                icon: getRoomStatusIcon(s.value, 14),
                label: s.label
            }))
        });

        menuItems.push({ type: 'divider' });
        menuItems.push({ key: 'edit', icon: <EditOutlined />, label: 'Chỉnh sửa thông tin' });

        return (
            <Menu
                onClick={({ key }) => {
                    if (key === 'book') handleBookRoom(room);
                    else if (key === 'book-normal') handleNormalBook(room);
                    else if (key.startsWith('status-')) {
                        const newStatus = key.replace('status-', '');
                        handleRoomStatusUpdate(room.id, newStatus);
                    }
                }}
                items={menuItems as any}
            />
        );
    };

    const getRoomStatusIcon = (status: string, size = 20) => {
        const statusConfig: { [key: string]: { icon: React.ReactNode, color: string } } = {
            available: { icon: <CheckCircle size={size} />, color: 'text-green-500' },
            occupied: { icon: <User size={size} />, color: 'text-red-500' },
            cleaning: { icon: <Sparkles size={size} />, color: 'text-orange-500' },
            maintenance: { icon: <Wrench size={size} />, color: 'text-gray-500' },
            deposited: { icon: <Clock size={size} />, color: 'text-yellow-500' },
            no_show: { icon: <AlertCircle size={size} />, color: 'text-red-400' },
            check_in: { icon: <CheckCircle size={size} />, color: 'text-blue-500' },
            check_out: { icon: <CheckCircle size={size} />, color: 'text-purple-500' },
        };
        const config = statusConfig[status] || { icon: <CheckCircle size={size} />, color: 'text-gray-400' };
        return <span className={config.color}>{config.icon}</span>;
    };

    if (loading) {
        return <div className="flex justify-center items-center h-64"><Spin size="large" /></div>;
    }

    if (rooms.length === 0) {
        return <Empty description="Không có phòng nào" image={Empty.PRESENTED_IMAGE_SIMPLE} />;
    }

    const roomsByFloor = rooms.reduce((acc: any, room: any) => {
        const floor = room.floor || Math.floor(parseInt(room.name) / 100) || 1;
        if (!acc[floor]) acc[floor] = [];
        acc[floor].push(room);
        return acc;
    }, {});

    const formatDateRange = (checkIn: string, checkOut: string) => {
        return `${dayjs(checkIn).format('DD/MM')} - ${dayjs(checkOut).format('DD/MM')}`;
    };

    const RoomCard: React.FC<{ room: any }> = ({ room }) => {
        const isAvailable = room.status === 'available';
        const isSelected = selectedRooms.has(room.id);
        const statusInfo = statusOptions.find(s => s.value === room.status) || { label: room.status, color: 'default' };

        const handleRoomClick = () => {
            if (multiSelectMode) {
                onRoomSelect?.(room.id, !isSelected);
            } else {
                onRoomClick?.(room);
            }
        };

        return (
            <Dropdown overlay={getRoomContextMenu(room)} trigger={['contextMenu']} placement="bottomLeft">
                <Card
                    className={`
                        h-full cursor-pointer transition-all duration-300 ease-in-out
                        border
                        ${isSelected ? 'ring-2 ring-blue-500 border-blue-500' : 'border-gray-200'}
                        ${!isAvailable && !multiSelectMode ? 'bg-gray-50' : 'bg-white'}
                        hover:shadow-lg hover:border-blue-400
                    `}
                    onClick={handleRoomClick}
                    size="small"
                    styles={{ body: { padding: 0, height: '100%' } }}
                >
                    <Flex vertical className="h-full">
                        <div className="p-3">
                            <Flex justify="space-between" align="start">
                                <Flex align="center" gap={8}>
                                    {multiSelectMode && isAvailable && (
                                        <Checkbox
                                            checked={isSelected}
                                            onChange={(e) => {
                                                e.stopPropagation();
                                                onRoomSelect?.(room.id, e.target.checked);
                                            }}
                                        />
                                    )}
                                    <Title level={5} className="!mb-0">{room.name}</Title>
                                </Flex>
                                {getRoomStatusIcon(room.status)}
                            </Flex>
                            <Text type="secondary" className="text-xs">{room.room_type?.name || 'N/A'}</Text>
                        </div>

                        <div className="p-3 flex-1">
                            <Tag color={statusInfo.color}>{statusInfo.label}</Tag>
                            {(room.status === 'occupied' || room.status === 'deposited' || room.status === 'no_show') && (room.guestName || room.checkInDate) && (
                                <div className="mt-2 space-y-1 text-xs">
                                    {room.guestName && (
                                        <Flex align="center" gap={4}>
                                            <UserOutlined className="text-gray-500" />
                                            <Text className="text-xs">{room.guestName} ({room.guestCount} khách)</Text>
                                        </Flex>
                                    )}
                                    {room.checkInDate && room.checkOutDate && (
                                        <Flex align="center" gap={4}>
                                            <CalendarOutlined className="text-gray-500" />
                                            <Text className="text-xs">{formatDateRange(room.checkInDate, room.checkOutDate)}</Text>
                                        </Flex>
                                    )}
                                </div>
                            )}
                        </div>

                        <div className="mt-auto p-2 border-t border-gray-100 bg-gray-50 text-center">
                            <Text className="text-xs text-gray-400">
                                Nhấn chuột phải để xem tùy chọn
                            </Text>
                        </div>
                    </Flex>
                </Card>
            </Dropdown>
        );
    };

    return (
        <div className="space-y-6">
            {multiSelectMode && (
                <Card>
                    <Flex justify="space-between" align="center">
                        <Text strong>Đã chọn: {selectedRooms.size} phòng</Text>
                        {selectedRooms.size > 0 && (
                            <Button type="primary" size="large" onClick={onProceedToBooking}>
                                Tiến hành đặt {selectedRooms.size} phòng
                            </Button>
                        )}
                    </Flex>
                </Card>
            )}

            {Object.keys(roomsByFloor).sort((a, b) => parseInt(a) - parseInt(b)).map(floor => (
                <div key={floor}>
                    <Flex justify="space-between" align="center" className="mb-4">
                        <Title level={4}>Tầng {floor}</Title>
                        {multiSelectMode && (
                            <Button
                                size="small"
                                onClick={() => {
                                    const floorRooms = roomsByFloor[floor].filter((r: any) => r.status === 'available');
                                    const allSelected = floorRooms.every((r: any) => selectedRooms.has(r.id));
                                    floorRooms.forEach((r: any) => onRoomSelect?.(r.id, !allSelected));
                                }}
                            >
                                Chọn cả tầng
                            </Button>
                        )}
                    </Flex>
                    <Row gutter={[16, 16]}>
                        {roomsByFloor[floor].map((room: any) => (
                            <Col key={room.id} xs={12} sm={8} md={6} lg={6} xl={4}>
                                <RoomCard room={room} />
                            </Col>
                        ))}
                    </Row>
                </div>
            ))}

            <Modal
                title={<Title level={4}>{hasDateFilter ? "Thông tin đặt phòng" : "Đặt phòng nhanh"}</Title>}
                open={bookingModal}
                onCancel={handleCancelBooking}
                footer={null}
                width={600}
                centered
            >
                {selectedRoomForBooking && (
                    <div className="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <Flex justify="space-between" align="center">
                            <div>
                                <Title level={5} className="!mb-1">Phòng {selectedRoomForBooking.name}</Title>
                                <Text type="secondary">{selectedRoomForBooking.room_type?.name} - Tối đa {selectedRoomForBooking.room_type?.max_guests} khách</Text>
                            </div>
                            <Text strong className="text-lg text-blue-600">
                                {new Intl.NumberFormat('vi-VN').format(selectedRoomForBooking.room_type?.adjusted_price || selectedRoomForBooking.room_type?.base_price || 0)}đ/đêm
                            </Text>
                        </Flex>
                    </div>
                )}

                <Form form={bookingForm} layout="vertical" className="mt-6">
                    <Title level={5}>Thông tin khách</Title>
                    <Row gutter={24}>
                        <Col xs={24} sm={12}>
                            <Form.Item
                                label="Số người lớn"
                                name="adults"
                                rules={[{ required: true, message: 'Vui lòng nhập số người lớn' }]}
                                initialValue={1}
                            >
                                <InputNumber
                                    className="w-full"
                                    min={1}
                                    max={selectedRoomForBooking?.room_type?.max_guests || 10}
                                    addonBefore={<TeamOutlined />}
                                />
                            </Form.Item>
                        </Col>
                        <Col xs={24} sm={12}>
                            <Form.Item label="Số trẻ em (dưới 12 tuổi)">
                                <Form.List name="children">
                                    {(fields, { add, remove }) => (
                                        <div className="space-y-2">
                                            {fields.map(({ key, name, ...restField }) => (
                                                <Flex key={key} align="baseline" gap="small">
                                                    <Form.Item
                                                        {...restField}
                                                        name={[name, 'age']}
                                                        rules={[{ required: true, message: 'Chọn tuổi' }]}
                                                        className="!mb-0 flex-1"
                                                    >
                                                        <Select placeholder="Tuổi">
                                                            {Array.from({ length: 13 }, (_, i) => (
                                                                <Option key={i} value={i}>{i} tuổi</Option>
                                                            ))}
                                                        </Select>
                                                    </Form.Item>
                                                    <MinusCircleOutlined onClick={() => remove(name)} className="text-red-500" />
                                                </Flex>
                                            ))}
                                            <Button type="dashed" onClick={() => add()} block icon={<PlusOutlined />} disabled={fields.length >= 4}>
                                                Thêm trẻ em
                                            </Button>
                                        </div>
                                    )}
                                </Form.List>
                            </Form.Item>
                        </Col>
                    </Row>

                    <Form.Item label="Ghi chú" name="notes">
                        <Input.TextArea placeholder="Ghi chú thêm về đặt phòng (tùy chọn)" rows={3} />
                    </Form.Item>

                    <Divider />

                    <div className="text-center">
                        {!hasDateFilter ? (
                            <>
                                <Title level={5} className="!mb-2">Chọn số đêm đặt phòng</Title>
                                <Space size="large" direction="vertical" className="w-full">
                                    <Button type="primary" size="large" onClick={() => handleQuickBook(1)} block>
                                        1 đêm - {new Intl.NumberFormat('vi-VN').format((selectedRoomForBooking?.room_type?.adjusted_price || selectedRoomForBooking?.room_type?.base_price || 0) * 1)}đ
                                    </Button>
                                    <Button type="primary" size="large" onClick={() => handleQuickBook(2)} block>
                                        2 đêm - {new Intl.NumberFormat('vi-VN').format((selectedRoomForBooking?.room_type?.adjusted_price || selectedRoomForBooking?.room_type?.base_price || 0) * 2)}đ
                                    </Button>
                                    <Button size="large" onClick={handleCancelBooking} block>Hủy</Button>
                                </Space>
                            </>
                        ) : (
                            <Flex justify="end" gap="middle">
                                <Button size="large" onClick={handleCancelBooking}>Hủy</Button>
                                <Button type="primary" size="large" onClick={handleNormalBookConfirm}>Xác nhận đặt phòng</Button>
                            </Flex>
                        )}
                    </div>
                </Form>
            </Modal>
        </div>
    );
};

export default memo(RoomGridView);
