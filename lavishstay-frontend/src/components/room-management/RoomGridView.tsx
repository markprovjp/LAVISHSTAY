import React, { memo, useState } from 'react';
import { Card, Row, Col, Typography, Empty, Spin, Tag, Checkbox, Button, Space, Dropdown, Menu, Modal, Form, Input, InputNumber, Select, Divider } from 'antd';
import { UserOutlined, CalendarOutlined, EditOutlined, CheckCircleOutlined, CloseCircleOutlined, ToolOutlined, PlusOutlined, SettingOutlined, MinusCircleOutlined } from '@ant-design/icons';
import { statusOptions } from '../../constants/roomStatus';
import { User, Clock, CheckCircle, AlertCircle, Wrench, Sparkles } from 'lucide-react';
import { useUpdateRoomStatus } from '../../hooks/useReception';
import dayjs from 'dayjs';

const { Text } = Typography;
const { Option } = Select;

interface Child {
    age: number;
}

interface BookingData {
    adults: number;
    children: Child[];
    notes?: string;
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
    hasDateFilter?: boolean; // Thêm prop để biết có filter ngày không
    checkInDate?: string; // Thêm props ngày
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
            // Validate form trước khi submit
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
                        guestCount: bookingData.adults + bookingData.children.length,
                        // Truyền thông tin ngày từ filter nếu có
                        checkInDate,
                        checkOutDate
                        // Không có quickBookNights vì đây là đặt phòng thường
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
            // Validate form trước khi submit
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
                        guestCount: bookingData.adults + bookingData.children.length,
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
        // Đặt phòng thường khi có filter ngày - cũng cần modal nhập thông tin khách
        setSelectedRoomForBooking(room);
        setBookingModal(true);

        // Initialize form with default values
        bookingForm.setFieldsValue({
            adults: 1,
            children: []
        });
    };

    const handleBookRoom = (room: any) => {
        setSelectedRoomForBooking(room);
        setBookingModal(true);
        // Set default values
        bookingForm.setFieldsValue({
            adults: 1,
            children: []
        });
    };

    const handleCancelBooking = () => {
        setBookingModal(false);
        bookingForm.resetFields();
        setSelectedRoomForBooking(null);
    };

    const getRoomContextMenu = (room: any) => {
        const menuItems = [];

        // Hiển thị đặt phòng với logic khác nhau tùy theo có filter ngày hay không
        if (room.status === 'available') {
            if (!hasDateFilter) {
                // Không có filter ngày - hiển thị đặt phòng nhanh
                menuItems.push({
                    key: 'book',
                    icon: <PlusOutlined />,
                    label: 'Đặt phòng nhanh'
                });
            } else {
                // Có filter ngày - hiển thị đặt phòng thường
                menuItems.push({
                    key: 'book-normal',
                    icon: <PlusOutlined />,
                    label: 'Đặt phòng'
                });
            }
            menuItems.push({ type: 'divider' });
        }

        // Menu cập nhật trạng thái
        menuItems.push({
            key: 'status',
            label: 'Cập nhật trạng thái',
            icon: <SettingOutlined />,
            children: [
                {
                    key: 'status-available',
                    icon: <CheckCircleOutlined style={{ color: '#52c41a' }} />,
                    label: 'Có sẵn'
                },
                {
                    key: 'status-occupied',
                    icon: <UserOutlined style={{ color: '#f5222d' }} />,
                    label: 'Đã có khách'
                },
                {
                    key: 'status-maintenance',
                    icon: <ToolOutlined style={{ color: '#8c8c8c' }} />,
                    label: 'Bảo trì'
                },
                {
                    key: 'status-cleaning',
                    icon: <CloseCircleOutlined style={{ color: '#fa8c16' }} />,
                    label: 'Đang dọn dẹp'
                },
                {
                    key: 'status-deposited',
                    icon: <CalendarOutlined style={{ color: '#faad14' }} />,
                    label: 'Đã cọc tiền'
                },
                {
                    key: 'status-no_show',
                    icon: <CloseCircleOutlined style={{ color: '#ff4d4f' }} />,
                    label: 'Không đến'
                },
                {
                    key: 'status-check_in',
                    icon: <CheckCircleOutlined style={{ color: '#1890ff' }} />,
                    label: 'Đón khách'
                },
                {
                    key: 'status-check_out',
                    icon: <CheckCircleOutlined style={{ color: '#722ed1' }} />,
                    label: 'Trả phòng'
                }
            ]
        });

        menuItems.push({ type: 'divider' });
        menuItems.push({
            key: 'edit',
            icon: <EditOutlined />,
            label: 'Chỉnh sửa thông tin'
        });

        return (
            <Menu
                onClick={({ key }) => {
                    if (key === 'book') {
                        handleBookRoom(room);
                    } else if (key === 'book-normal') {
                        handleNormalBook(room);
                    } else if (key.startsWith('status-')) {
                        const newStatus = key.replace('status-', '');
                        handleRoomStatusUpdate(room.id, newStatus);
                    }
                }}
                items={menuItems as any}
            />
        );
    };
    // Get room status icon
    const getRoomStatusIcon = (status: string) => {
        switch (status) {
            case 'available':
                return <CheckCircle size={20} className="text-green-500" />;
            case 'occupied':
                return <User size={20} className="text-red-500" />;
            case 'cleaning':
                return <Sparkles size={20} className="text-orange-500" />;
            case 'maintenance':
                return <Wrench size={20} className="text-gray-500" />;
            case 'deposited':
                return <Clock size={20} className="text-yellow-500" />;
            case 'no_show':
                return <AlertCircle size={20} className="text-red-400" />;
            case 'check_in':
                return <CheckCircle size={20} className="text-blue-500" />;
            case 'check_out':
                return <CheckCircle size={20} className="text-purple-500" />;
            default:
                return <CheckCircle size={20} className="text-gray-400" />;
        }
    };

    if (loading) {
        return (
            <div className="flex justify-center items-center h-64">
                <Spin size="large" />
            </div>
        );
    }

    if (rooms.length === 0) {
        return (
            <Empty
                description="Không có phòng nào"
                image={Empty.PRESENTED_IMAGE_SIMPLE}
            />
        );
    }

    // Group rooms by floor
    const roomsByFloor = rooms.reduce((acc: any, room: any) => {
        const floor = room.floor || Math.floor(parseInt(room.name) / 100) || 1;
        if (!acc[floor]) {
            acc[floor] = [];
        }
        acc[floor].push(room);
        return acc;
    }, {});

    const formatDate = (date: string) => {
        return dayjs(date).format('DD/MM');
    };

    const formatDateRange = (checkIn: string, checkOut: string) => {
        return `${formatDate(checkIn)} - ${formatDate(checkOut)}`;
    };

    const RoomCard: React.FC<{ room: any }> = ({ room }) => {
        const isOccupied = room.status === 'occupied';
        const isAvailable = room.status === 'available';
        const isSelected = selectedRooms.has(room.id);

        const handleRoomClick = () => {
            if (multiSelectMode) {
                onRoomSelect?.(room.id, !isSelected);
            } else {
                onRoomClick?.(room);
            }
        };

        return (
            <Dropdown
                overlay={getRoomContextMenu(room)}
                trigger={['contextMenu']}
                placement="bottomLeft"
            >
                <Card
                    className={`
                        h-52 cursor-pointer transition-all duration-200 hover:shadow-md
                        ${isSelected ? 'ring-2 ring-blue-500 bg-blue-50' : ''}
                        ${!isAvailable && !multiSelectMode ? 'opacity-75' : ''}
                    `}
                    onClick={handleRoomClick}
                    size="small"
                    styles={{ body: { padding: '12px', height: '100%' } }}
                >
                    <div className="h-full flex flex-col">
                        {/* Header với checkbox và status */}
                        <div className="flex justify-between items-start mb-2">
                            <div className="flex items-center gap-2">
                                {multiSelectMode && isAvailable && (
                                    <Checkbox
                                        checked={isSelected}
                                        onChange={(e) => {
                                            e.stopPropagation();
                                            onRoomSelect?.(room.id, e.target.checked);
                                        }}
                                    />
                                )}
                                <Text strong className="text-lg">{room.name}</Text>
                            </div>
                            <div className="flex items-center gap-1">
                                {getRoomStatusIcon(room.status)}
                            </div>
                        </div>

                        {/* Room type */}
                        <div className="mb-2">
                            <Tag color="blue" className="text-xs">
                                {room.room_type?.name || 'N/A'}
                            </Tag>
                        </div>

                        {/* Status và thông tin khách */}
                        <div className="flex-1">
                            <Tag color={statusOptions.find(s => s.value === room.status)?.color || 'default'} className="mb-2">
                                {statusOptions.find(s => s.value === room.status)?.label || room.status}
                            </Tag>

                            {isOccupied && room.guestName && (
                                <div className="space-y-1 text-xs">
                                    <div className="flex items-center gap-1">
                                        <UserOutlined className="text-gray-500" />
                                        <Text className="text-xs">{room.guestName}</Text>
                                    </div>
                                    <div className="flex items-center gap-1">
                                        <CalendarOutlined className="text-gray-500" />
                                        <Text className="text-xs">
                                            {formatDateRange(room.checkInDate, room.checkOutDate)}
                                        </Text>
                                    </div>
                                    <div className="flex items-center gap-1">
                                        <UserOutlined className="text-gray-500" />
                                        <Text className="text-xs">{room.guestCount} khách</Text>
                                    </div>
                                </div>
                            )}

                            {(room.status === 'deposited' || room.status === 'no_show') && room.checkInDate && (
                                <div className="space-y-1 text-xs">
                                    <div className="flex items-center gap-1">
                                        <CalendarOutlined className="text-gray-500" />
                                        <Text className="text-xs">
                                            {formatDateRange(room.checkInDate, room.checkOutDate)}
                                        </Text>
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Hint text for right-click */}
                        <div className="mt-2 pt-2 border-t">
                            <Text className="text-xs text-gray-400 text-center block">
                                {hasDateFilter
                                    ? "Nhấn chuột phải → Cập nhật trạng thái"
                                    : "Nhấn chuột phải → Đặt nhanh/Cập nhật"}
                            </Text>
                        </div>
                    </div>
                </Card>
            </Dropdown>
        );
    };

    return (
        <div className="space-y-6">
            {/* Multi-select actions */}
            {multiSelectMode && (
                <div className="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div className="flex justify-between items-center">
                        <div className="flex items-center gap-4">
                            <Text strong>Đã chọn: {selectedRooms.size} phòng</Text>
                            <Space>
                                <Text>Số khách:</Text>
                                <Button.Group>
                                    <Button
                                        size="small"
                                        onClick={() => onGuestCountChange?.(Math.max(1, guestCount - 1))}
                                    >
                                        -
                                    </Button>
                                    <Button size="small" disabled>
                                        {guestCount}
                                    </Button>
                                    <Button
                                        size="small"
                                        onClick={() => onGuestCountChange?.(guestCount + 1)}
                                    >
                                        +
                                    </Button>
                                </Button.Group>
                            </Space>
                        </div>
                        {selectedRooms.size > 0 && (
                            <Button
                                type="primary"
                                size="large"
                                onClick={onProceedToBooking}
                            >
                                Tiến hành đặt {selectedRooms.size} phòng
                            </Button>
                        )}
                    </div>
                </div>
            )}

            {/* Rooms grid by floor */}
            {Object.keys(roomsByFloor)
                .sort((a, b) => parseInt(a) - parseInt(b))
                .map(floor => (
                    <div key={floor} className="space-y-4">
                        <div className="flex items-center gap-2">
                            <Text strong className="text-lg">Tầng {floor}</Text>
                            {multiSelectMode && (
                                <Button
                                    size="small"
                                    onClick={() => {
                                        const floorRooms = roomsByFloor[floor].filter((r: any) => r.status === 'available');
                                        const allSelected = floorRooms.every((r: any) => selectedRooms.has(r.id));
                                        floorRooms.forEach((r: any) => {
                                            onRoomSelect?.(r.id, !allSelected);
                                        });
                                    }}
                                >
                                    Chọn cả tầng
                                </Button>
                            )}
                        </div>
                        <Row gutter={[16, 16]}>
                            {roomsByFloor[floor].map((room: any) => (
                                <Col key={room.id} xs={24} sm={12} md={8} lg={6} xl={4}>
                                    <RoomCard room={room} />
                                </Col>
                            ))}
                        </Row>
                    </div>
                ))}

            {/* Booking Modal - Chọn số người, logic khác nhau tùy theo có filter ngày */}
            <Modal
                title={hasDateFilter ? "Thông tin đặt phòng" : "Đặt phòng nhanh"}
                open={bookingModal}
                onCancel={handleCancelBooking}
                footer={null}
                width={600}
                centered
            >
                {selectedRoomForBooking && (
                    <div className="mb-4 p-4 bg-blue-50 rounded-lg">
                        <div className="flex justify-between items-center">
                            <div>
                                <Text strong className="text-lg">Phòng {selectedRoomForBooking.name}</Text>
                                <br />
                                <Text className="text-sm text-gray-600">
                                    {selectedRoomForBooking.room_type?.name} - {selectedRoomForBooking.room_type?.max_guests} khách tối đa
                                </Text>
                            </div>
                            <Text strong className="text-blue-600">
                                {new Intl.NumberFormat('vi-VN').format(selectedRoomForBooking.room_type?.adjusted_price || selectedRoomForBooking.room_type?.base_price || 0)} VND/đêm
                            </Text>
                        </div>
                    </div>
                )}

                <Form
                    form={bookingForm}
                    layout="vertical"
                    className="mt-6"
                >
                    <Row gutter={16}>
                        <Col span={12}>
                            <Form.Item
                                label="Số người lớn"
                                name="adults"
                                rules={[{ required: true, message: 'Vui lòng nhập số người lớn' }]}
                            >
                                <InputNumber
                                    className="w-full"
                                    min={1}
                                    max={selectedRoomForBooking?.room_type?.max_guests || 10}
                                    placeholder="Số người lớn"
                                />
                            </Form.Item>
                        </Col>
                        <Col span={12}>
                            <Form.Item label="Trẻ em (0-12 tuổi)">
                                <Form.List name="children">
                                    {(fields, { add, remove }) => (
                                        <>
                                            {fields.map(({ key, name, ...restField }) => (
                                                <Space key={key} style={{ display: 'flex', marginBottom: 8 }} align="baseline">
                                                    <Form.Item
                                                        {...restField}
                                                        name={[name, 'age']}
                                                        rules={[
                                                            { required: true, message: 'Nhập tuổi trẻ em' },
                                                            { type: 'number', min: 0, max: 12, message: 'Tuổi từ 0-12' }
                                                        ]}
                                                    >
                                                        <Select placeholder="Tuổi trẻ em" style={{ width: 120 }}>
                                                            {Array.from({ length: 13 }, (_, i) => (
                                                                <Option key={i} value={i}>
                                                                    {i} tuổi
                                                                </Option>
                                                            ))}
                                                        </Select>
                                                    </Form.Item>
                                                    <MinusCircleOutlined onClick={() => remove(name)} />
                                                </Space>
                                            ))}
                                            <Form.Item>
                                                <Button
                                                    type="dashed"
                                                    onClick={() => add()}
                                                    block
                                                    icon={<PlusOutlined />}
                                                    disabled={fields.length >= 3}
                                                >
                                                    Thêm trẻ em (tối đa 3)
                                                </Button>
                                            </Form.Item>
                                        </>
                                    )}
                                </Form.List>
                            </Form.Item>
                        </Col>
                    </Row>

                    <Form.Item label="Ghi chú" name="notes">
                        <Input.TextArea
                            placeholder="Ghi chú thêm về đặt phòng (tùy chọn)"
                            rows={3}
                        />
                    </Form.Item>

                    <Divider />

                    <div className="text-center">
                        {!hasDateFilter ? (
                            // Đặt phòng nhanh - hiển thị lựa chọn số đêm
                            <>
                                <Text strong className="block mb-2">Chọn số đêm đặt phòng:</Text>
                                <Text className="block mb-4 text-gray-600">
                                    Giá: {new Intl.NumberFormat('vi-VN').format(selectedRoomForBooking?.room_type?.adjusted_price || selectedRoomForBooking?.room_type?.base_price || 0)} VND/đêm
                                </Text>
                                <Space size="large">
                                    <div className="text-center">
                                        <Button
                                            type="primary"
                                            size="large"
                                            onClick={() => handleQuickBook(1)}
                                            className="block mb-2"
                                        >
                                            1 đêm
                                        </Button>
                                        <Text className="text-sm text-gray-500">
                                            {new Intl.NumberFormat('vi-VN').format((selectedRoomForBooking?.room_type?.adjusted_price || selectedRoomForBooking?.room_type?.base_price || 0) * 1)} VND
                                        </Text>
                                    </div>
                                    <div className="text-center">
                                        <Button
                                            type="primary"
                                            size="large"
                                            onClick={() => handleQuickBook(2)}
                                            className="block mb-2"
                                        >
                                            2 đêm
                                        </Button>
                                        <Text className="text-sm text-gray-500">
                                            {new Intl.NumberFormat('vi-VN').format((selectedRoomForBooking?.room_type?.adjusted_price || selectedRoomForBooking?.room_type?.base_price || 0) * 2)} VND
                                        </Text>
                                    </div>
                                    <Button
                                        size="large"
                                        onClick={handleCancelBooking}
                                    >
                                        Hủy
                                    </Button>
                                </Space>
                            </>
                        ) : (
                            // Đặt phòng thường - chỉ có nút xác nhận
                            <Space size="large">
                                <Button
                                    size="large"
                                    onClick={handleCancelBooking}
                                >
                                    Hủy
                                </Button>
                                <Button
                                    type="primary"
                                    size="large"
                                    onClick={handleNormalBookConfirm}
                                >
                                    Xác nhận đặt phòng
                                </Button>
                            </Space>
                        )}
                    </div>
                </Form>
            </Modal>
        </div>
    );
};

export default memo(RoomGridView);
