import React, { memo, useState } from 'react';
import { Card, Row, Col, Typography, Empty, Spin, Tag, Checkbox, Button, Space, Dropdown, Menu, Modal, Form, Input, InputNumber, Select, Divider, Flex, App } from 'antd';
import { UserOutlined, EditOutlined, SettingOutlined, PlusOutlined, MinusCircleOutlined, TeamOutlined, ClockCircleOutlined } from '@ant-design/icons';
import { statusOptions } from '../../constants/roomStatus';
import { useUpdateRoomStatus, useCalculateBookingQuote, BookingQuotePayload } from '../../hooks/useReception';

const { Text, Title } = Typography;
const { Option } = Select;

// --- Type Definitions ---
interface Child { age: number; }
interface GuestInfo { adults: number; children: Child[]; }
interface RoomGuestData { [roomId: string]: GuestInfo; }
interface BookingPayload {
    guests: RoomGuestData;
    notes?: string;
    quickBookNights?: number;
}

interface RoomGridViewProps {
    rooms: any[];
    allRooms: any[];
    loading?: boolean;
    onRoomClick?: (room: any) => void;
    multiSelectMode?: boolean;
    selectedRooms?: Set<string>;
    onRoomSelect?: (roomId: string, selected: boolean) => void;
    onBulkRoomSelect?: (roomIds: string[], select: boolean) => void;
    navigate?: (path: string, options?: any) => void;
    hasDateFilter?: boolean;
    checkInDate?: string;
    checkOutDate?: string;
}

// --- Reusable Guest Form Component ---
const GuestInfoForm: React.FC<{ namePrefix: (string | number)[], maxGuests: number }> = ({ namePrefix, maxGuests }) => (
    <Row gutter={16}>
        <Col xs={24} sm={12}>
            <Form.Item label="Số người lớn" name={[...namePrefix, 'adults']} rules={[{ required: true, message: 'Bắt buộc' }]}>
                <InputNumber className="w-full" min={1} max={maxGuests} addonBefore={<TeamOutlined />} />
            </Form.Item>
        </Col>
        <Col xs={24} sm={12}>
            <Form.Item label="Trẻ em (dưới 12 tuổi)">
                <Form.List name={[...namePrefix, 'children']}>
                    {(fields, { add, remove }) => (
                        <div className="space-y-2">
                            {fields.map(({ key, name, ...restField }) => (
                                <Flex key={key} align="baseline" gap="small">
                                    <Form.Item {...restField} name={[name, 'age']} rules={[{ required: true, message: 'Tuổi?' }]} className="!mb-0 flex-1">
                                        <Select placeholder="Tuổi">{Array.from({ length: 12 }, (_, i) => <Option key={i} value={i}>{i} tuổi</Option>)}</Select>
                                    </Form.Item>
                                    <MinusCircleOutlined onClick={() => remove(name)} className="text-red-500" />
                                </Flex>
                            ))}
                            <Button type="dashed" onClick={() => add()} block icon={<PlusOutlined />} disabled={fields.length >= 4}>Thêm trẻ em</Button>
                        </div>
                    )}
                </Form.List>
            </Form.Item>
        </Col>
    </Row>
);


const RoomGridView: React.FC<RoomGridViewProps> = ({
    rooms,
    allRooms,
    loading = false,
    onRoomClick,
    multiSelectMode = false,
    selectedRooms = new Set(),
    onRoomSelect,
    onBulkRoomSelect,
    navigate,
    hasDateFilter = false,
    checkInDate,
    checkOutDate
}) => {
    const { message } = App.useApp();
    const updateRoomStatusMutation = useUpdateRoomStatus();
    const [bookingModalVisible, setBookingModalVisible] = useState(false);
    const [bookingTarget, setBookingTarget] = useState<any[]>([]);
    const [quickBookNights, setQuickBookNights] = useState<number | undefined>(undefined);
    const [bookingForm] = Form.useForm();

    const { mutateAsync: calculateQuote, isPending: isQuoteLoading } = useCalculateBookingQuote();

    const openBookingModal = (targetRooms: any[], nights?: number) => {
        if (targetRooms.length === 0) return;
        setBookingTarget(targetRooms);
        setQuickBookNights(nights);
        const initialValues: { guests: RoomGuestData } = { guests: {} };
        targetRooms.forEach(room => {
            initialValues.guests[room.id] = { adults: 1, children: [] };
        });
        bookingForm.setFieldsValue(initialValues);
        setBookingModalVisible(true);
    };

    const handleConfirmBooking = async () => {
        if (!navigate || !checkInDate || !checkOutDate) {
            message.error("Thiếu thông tin ngày để tiến hành đặt phòng.");
            return;
        };

        try {
            const values = await bookingForm.validateFields();

            // --- INTELLIGENT CODE FIX ---
            // Clean the sparse array from the form into a clean object.
            const cleanedGuestsData: RoomGuestData = {};
            bookingTarget.forEach(room => {
                if (values.guests && values.guests[room.id]) {
                    cleanedGuestsData[room.id] = values.guests[room.id];
                }
            });
            // --- END FIX ---

            const bookingPayload: BookingPayload = {
                guests: cleanedGuestsData, // Use the cleaned data
                notes: values.notes,
                quickBookNights: quickBookNights,
            };

            const quotePayload: BookingQuotePayload = {
                checkInDate: checkInDate,
                checkOutDate: checkOutDate,
                rooms: bookingTarget.map(room => ({
                    room_id: room.id,
                    adults: bookingPayload.guests[room.id]?.adults || 1,
                    children: bookingPayload.guests[room.id]?.children || [],
                }))
            };

            console.log("API Fe gửi cho tính toán tổng tiền phòng là :", quotePayload);

            const quoteResult = await calculateQuote(quotePayload);

            navigate('/reception/confirm-representative-payment', {
                state: {
                    selectedRooms: bookingTarget,
                    bookingData: bookingPayload,
                    checkInDate,
                    checkOutDate,
                    quoteData: quoteResult.data,
                }
            });
            setBookingModalVisible(false);
        } catch (error) {
            message.error("Vui lòng điền đủ thông tin hoặc đã có lỗi xảy ra khi tính giá.");
            console.error('Booking confirmation failed:', error);
        }
    };

    const getRoomContextMenu = (room: any) => {
        const menuItems = [];
        if (hasDateFilter) {
            menuItems.push({ key: 'book-normal', icon: <PlusOutlined />, label: 'Đặt phòng' });
        } else {
            menuItems.push({ key: 'book-quick-1', icon: <ClockCircleOutlined />, label: 'Đặt nhanh 1 đêm' });
            menuItems.push({ key: 'book-quick-2', icon: <ClockCircleOutlined />, label: 'Đặt nhanh 2 đêm' });
        }
        menuItems.push({ type: 'divider' });
        menuItems.push({
            key: 'status', label: 'Cập nhật trạng thái', icon: <SettingOutlined />,
            children: statusOptions.map(s => ({ key: `status-${s.value}`, label: s.label }))
        });
        return (
            <Menu onClick={({ key }) => {
                if (key === 'book-normal') openBookingModal([room]);
                else if (key === 'book-quick-1') openBookingModal([room], 1);
                else if (key === 'book-quick-2') openBookingModal([room], 2);
                else if (key.startsWith('status-')) updateRoomStatusMutation.mutate({ roomId: parseInt(room.id), status: key.replace('status-', '') });
            }}>
                {menuItems.map(item => {
                    if (item.type === 'divider') return <Menu.Divider key={Math.random()} />;
                    if (item.children) {
                        return <Menu.SubMenu key={item.key} title={item.label} icon={item.icon}>{item.children.map(child => <Menu.Item key={child.key}>{child.label}</Menu.Item>)}</Menu.SubMenu>;
                    }
                    return <Menu.Item key={item.key} icon={item.icon}>{item.label}</Menu.Item>;
                })}
            </Menu>
        );
    };

    if (loading) return <div className="flex justify-center items-center h-64"><Spin size="large" /></div>;
    if (rooms.length === 0) return <Empty description="Không có phòng nào phù hợp" />;

    const roomsByFloor = rooms.reduce((acc: any, room: any) => {
        const floor = room.floor || 'Chưa xác định';
        if (!acc[floor]) acc[floor] = [];
        acc[floor].push(room);
        return acc;
    }, {});

    const RoomCard: React.FC<{ room: any }> = memo(({ room }) => {
        const isSelected = selectedRooms.has(room.id);
        return (
            <Dropdown overlay={getRoomContextMenu(room)} trigger={['contextMenu']}>
                <Card
                    className={`h-full cursor-pointer transition-all duration-300 ease-in-out border ${isSelected ? 'ring-2 ring-blue-500 border-blue-500' : 'border-gray-200'} ${room.status !== 'available' && !multiSelectMode ? 'bg-gray-50' : 'bg-white'} hover:shadow-lg hover:border-blue-400`}
                    onClick={() => multiSelectMode ? onRoomSelect?.(room.id, !isSelected) : onRoomClick?.(room)}
                    size="small" styles={{ body: { padding: 0, height: '100%' } }}
                >
                    <Flex vertical className="h-full">
                        <div className="p-3">
                            <Flex justify="space-between" align="start">
                                <Flex align="center" gap={8}>
                                    {multiSelectMode && room.status === 'available' && <Checkbox checked={isSelected} onChange={(e) => { e.stopPropagation(); onRoomSelect?.(room.id, e.target.checked); }} />}
                                    <Title level={5} className="!mb-0">{room.name}</Title>
                                </Flex>
                                <Tag color={statusOptions.find(s => s.value === room.status)?.color}>{statusOptions.find(s => s.value === room.status)?.label}</Tag>
                            </Flex>
                            <Text type="secondary" className="text-xs">{room.room_type?.name || 'N/A'}</Text>
                        </div>
                        <div className="mt-auto p-2 border-t border-gray-100 bg-gray-50 text-center">
                            <Text className="text-xs text-gray-400">Nhấn chuột phải để xem tùy chọn</Text>
                        </div>
                    </Flex>
                </Card>
            </Dropdown>
        );
    });

    return (
        <div className="space-y-6">
            {multiSelectMode && (
                <Card>
                    <Flex justify="space-between" align="center">
                        <Text strong>Đã chọn: {selectedRooms.size} phòng</Text>
                        {selectedRooms.size > 0 && (
                            <Button type="primary" size="large" onClick={() => openBookingModal(allRooms.filter(r => selectedRooms.has(r.id)))}>
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
                            <Button size="small" onClick={() => {
                                const floorRoomIds = roomsByFloor[floor].filter((r: any) => r.status === 'available').map((r: any) => r.id);
                                const allSelected = floorRoomIds.every((id: string) => selectedRooms.has(id));
                                onBulkRoomSelect?.(floorRoomIds, !allSelected);
                            }}>
                                {roomsByFloor[floor].filter((r: any) => r.status === 'available').every((r: any) => selectedRooms.has(r.id)) ? 'Bỏ chọn cả tầng' : 'Chọn cả tầng'}
                            </Button>
                        )}
                    </Flex>
                    <Row gutter={[16, 16]}>{roomsByFloor[floor].map((room: any) => <Col key={room.id} xs={12} sm={8} md={6} lg={6} xl={4}><RoomCard room={room} /></Col>)}</Row>
                </div>
            ))}

            <Modal
                title={<Title level={4}>Thông tin khách đặt phòng</Title>}
                open={bookingModalVisible}
                onCancel={() => setBookingModalVisible(false)}
                footer={[
                    <Button key="back" onClick={() => setBookingModalVisible(false)}>
                        Hủy
                    </Button>,
                    <Button key="submit" type="primary" loading={isQuoteLoading} onClick={handleConfirmBooking}>
                        Xác nhận và tiếp tục
                    </Button>,
                ]}
                width={bookingTarget.length > 1 ? 800 : 600}
                centered
            >
                <Form form={bookingForm} layout="vertical" className="mt-6 max-h-[60vh] overflow-y-auto pr-4">
                    {bookingTarget.map((room, index) => (
                        <div key={room.id}>
                            <Card size="small" className="mb-4 bg-gray-50">
                                <Title level={5}>Phòng {room.name} <Text type="secondary">({room.room_type.name})</Text></Title>
                                <GuestInfoForm namePrefix={['guests', room.id]} maxGuests={room.room_type.max_guests} />
                            </Card>
                            {index < bookingTarget.length - 1 && <Divider />}
                        </div>
                    ))}
                    <Form.Item label="Ghi chú chung" name="notes">
                        <Input.TextArea placeholder="Ghi chú thêm cho toàn bộ đơn đặt phòng (tùy chọn)" rows={3} />
                    </Form.Item>
                </Form>
            </Modal>
        </div>
    );
};

export default memo(RoomGridView);
