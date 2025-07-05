import React, { useState, memo } from 'react';
import { Card, Row, Col, Typography, Empty, Spin, Tag, Checkbox, Button, Space, Modal, DatePicker, Form, Input, InputNumber, Select, Divider } from 'antd';
import { UserOutlined, CalendarOutlined, ShoppingCartOutlined, MinusCircleOutlined, PlusOutlined } from '@ant-design/icons';
import { statusOptions } from '../../constants/roomStatus';
import { User, Clock, CheckCircle, AlertCircle, Wrench, Sparkles } from 'lucide-react';
import dayjs from 'dayjs';

const { Text } = Typography;
const { RangePicker } = DatePicker;
const { Option } = Select;

interface Child {
    age: number;
}

interface BookingData {
    checkInDate: string;
    checkOutDate: string;
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
    navigate
}) => {
    const [bookingModal, setBookingModal] = useState(false);
    const [selectedRoomForBooking, setSelectedRoomForBooking] = useState<any>(null);
    const [bookingForm] = Form.useForm();

    const handleOpenBookingModal = (room: any) => {
        setSelectedRoomForBooking(room);
        setBookingModal(true);
        // Set default values
        bookingForm.setFieldsValue({
            checkInDate: dayjs().add(1, 'day'),
            checkOutDate: dayjs().add(2, 'day'),
            adults: 1,
            children: []
        });
    };

    const handleConfirmBooking = async () => {
        try {
            const values = await bookingForm.validateFields();
            const bookingData: BookingData = {
                checkInDate: values.checkInDate.format('YYYY-MM-DD'),
                checkOutDate: values.checkOutDate.format('YYYY-MM-DD'),
                adults: values.adults,
                children: values.children || [],
                notes: values.notes
            };

            const totalGuests = bookingData.adults + bookingData.children.length;

            if (navigate && selectedRoomForBooking) {
                navigate('/reception/confirm-representative-payment', {
                    state: {
                        selectedRooms: [selectedRoomForBooking],
                        bookingData,
                        guestCount: totalGuests,
                        checkInDate: bookingData.checkInDate,
                        checkOutDate: bookingData.checkOutDate
                    }
                });
            }
            setBookingModal(false);
            bookingForm.resetFields();
        } catch (error) {
            console.error('Validation failed:', error);
        }
    };

    const handleCancelBooking = () => {
        setBookingModal(false);
        bookingForm.resetFields();
        setSelectedRoomForBooking(null);
    };
    // Get room status icon
    const getRoomStatusIcon = (status: string) => {
        switch (status) {
            case 'available':
                return <CheckCircle size={20} className="text-green-500" />;
            case 'occupied':
                return <User size={20} className="text-red-500" />;
            case 'cleaning':
                return <Sparkles size={20} className="text-blue-500" />;
            case 'maintenance':
                return <Wrench size={20} className="text-orange-500" />;
            case 'deposited':
                return <Clock size={20} className="text-purple-500" />;
            case 'no_show':
                return <AlertCircle size={20} className="text-gray-500" />;
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

        const handleBookSingleRoom = (e: React.MouseEvent) => {
            e.stopPropagation();
            handleOpenBookingModal(room);
        };

        return (
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

                    {/* Action buttons */}
                    {!multiSelectMode && isAvailable && (
                        <div className="mt-2 pt-2 border-t">
                            <Button
                                type="primary"
                                size="small"
                                block
                                icon={<ShoppingCartOutlined />}
                                onClick={handleBookSingleRoom}
                            >
                                Đặt phòng
                            </Button>
                        </div>
                    )}
                </div>
            </Card>
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

            {/* Booking Modal */}
            <Modal
                title="Đặt phòng"
                open={bookingModal}
                onOk={handleConfirmBooking}
                onCancel={handleCancelBooking}
                okText="Xác nhận đặt phòng"
                cancelText="Hủy"
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
                                {new Intl.NumberFormat('vi-VN').format(selectedRoomForBooking.room_type?.adjusted_price || 0)} VND/đêm
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
                                label="Ngày nhận phòng"
                                name="checkInDate"
                                rules={[{ required: true, message: 'Vui lòng chọn ngày nhận phòng' }]}
                            >
                                <DatePicker
                                    className="w-full"
                                    format="DD/MM/YYYY"
                                    placeholder="Chọn ngày nhận phòng"
                                    disabledDate={(current) => current && current < dayjs().startOf('day')}
                                />
                            </Form.Item>
                        </Col>
                        <Col span={12}>
                            <Form.Item
                                label="Ngày trả phòng"
                                name="checkOutDate"
                                rules={[{ required: true, message: 'Vui lòng chọn ngày trả phòng' }]}
                            >
                                <DatePicker
                                    className="w-full"
                                    format="DD/MM/YYYY"
                                    placeholder="Chọn ngày trả phòng"
                                    disabledDate={(current) => {
                                        const checkInDate = bookingForm.getFieldValue('checkInDate');
                                        return current && (current < dayjs().startOf('day') || (checkInDate && current <= checkInDate));
                                    }}
                                />
                            </Form.Item>
                        </Col>
                    </Row>

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
                                                    disabled={fields.length >= 5}
                                                >
                                                    Thêm trẻ em (tối đa 2)
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

                    <Form.Item shouldUpdate={(prevValues, currentValues) =>
                        prevValues.checkInDate !== currentValues.checkInDate ||
                        prevValues.checkOutDate !== currentValues.checkOutDate
                    }>
                        {({ getFieldValue }) => {
                            const checkIn = getFieldValue('checkInDate');
                            const checkOut = getFieldValue('checkOutDate');
                            const adults = getFieldValue('adults') || 0;
                            const children = getFieldValue('children') || [];

                            if (checkIn && checkOut) {
                                const nights = checkOut.diff(checkIn, 'day');
                                const pricePerNight = selectedRoomForBooking?.room_type?.adjusted_price || 0;
                                const total = nights * pricePerNight;

                                return (
                                    <div className="bg-gray-50 p-4 rounded-lg">
                                        <div className="flex justify-between items-center mb-2">
                                            <Text>Số đêm:</Text>
                                            <Text strong>{nights} đêm</Text>
                                        </div>
                                        <div className="flex justify-between items-center mb-2">
                                            <Text>Tổng khách:</Text>
                                            <Text strong>{adults + children.length} người</Text>
                                        </div>
                                        <div className="flex justify-between items-center mb-2">
                                            <Text>Giá/đêm:</Text>
                                            <Text strong>{new Intl.NumberFormat('vi-VN').format(pricePerNight)} VND</Text>
                                        </div>
                                        <Divider className="my-2" />
                                        <div className="flex justify-between items-center">
                                            <Text strong>Tổng tiền:</Text>
                                            <Text strong className="text-red-600 text-lg">
                                                {new Intl.NumberFormat('vi-VN').format(total)} VND
                                            </Text>
                                        </div>
                                    </div>
                                );
                            }
                            return null;
                        }}
                    </Form.Item>
                </Form>
            </Modal>
        </div>
    );
};

export default memo(RoomGridView);
