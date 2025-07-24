import React, { memo, useState, useCallback } from 'react';
import { Card, Row, Col, Typography, Empty, Spin, Tag, Checkbox, Button, Space, Dropdown, Menu, Modal, Form, Input, InputNumber, Select, Divider, Flex, App, Badge } from 'antd';
import { UserOutlined, SettingOutlined, PlusOutlined, MinusCircleOutlined, TeamOutlined, ClockCircleOutlined, DollarCircleOutlined, HomeOutlined, CheckCircleFilled, CloseCircleOutlined } from '@ant-design/icons';
import { statusOptions } from '../../constants/roomStatus';
import { useUpdateRoomStatus, useCalculateBookingQuote, BookingQuotePayload } from '../../hooks/useReception';

const { Text, Title, Paragraph } = Typography;
const { Option } = Select;
const iconMap = {
    CheckCircleFilled: <CheckCircleFilled />,
    UserOutlined: <UserOutlined />,
    SettingOutlined: <SettingOutlined />,
    ClockCircleOutlined: <ClockCircleOutlined />,
    DollarCircleOutlined: <DollarCircleOutlined />,
    HomeOutlined: <HomeOutlined />,
    CloseCircleOutlined: <CloseCircleOutlined />,
};
// --- Type Definitions ---
interface Child { age: number; }
interface GuestInfo { adults: number; children: Child[]; }
interface RoomGuestData { [roomId: string]: GuestInfo; }
interface BookingPayload { guests: RoomGuestData; notes?: string; quickBookNights?: number; }
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

// --- Optimized RoomCard Component ---
const RoomCard: React.FC<{
    room: any;
    isSelected: boolean;
    multiSelectMode: boolean;
    onRoomSelect: (roomId: string, selected: boolean) => void;
    onRoomClick: (room: any) => void;
    onMenuClick: (key: string, room: any) => void;
    hasDateFilter: boolean;
}> = memo(({ room, isSelected, multiSelectMode, onRoomSelect, onRoomClick, onMenuClick, hasDateFilter }) => {
    const statusInfoRaw = statusOptions.find(s => s.value === room.status);
    const statusInfo = statusInfoRaw
        ? {
            ...statusInfoRaw,
            icon: typeof statusInfoRaw.icon === 'string' ? iconMap[statusInfoRaw.icon as keyof typeof iconMap] : statusInfoRaw.icon
        }
        : { label: room.status, color: 'default', icon: <HomeOutlined /> };
    const { booking_info } = room;

    const contextMenu = (
        <Menu onClick={({ key }) => onMenuClick(key, room)}>
            {hasDateFilter ? (
                <Menu.Item key="book-normal" icon={<PlusOutlined />}>Đặt phòng theo ngày</Menu.Item>
            ) : (
                <>
                    <Menu.Item key="book-quick-1" icon={<ClockCircleOutlined />}>Đặt nhanh 1 đêm</Menu.Item>
                    <Menu.Item key="book-quick-2" icon={<ClockCircleOutlined />}>Đặt nhanh 2 đêm</Menu.Item>
                </>
            )}
            <Menu.Divider />
            <Menu.SubMenu key="status" title="Cập nhật trạng thái" icon={<SettingOutlined />}>
                {statusOptions.map(s => (
                    <Menu.Item >
                        <span style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                            {typeof s.icon === 'string' ? iconMap[s.icon as keyof typeof iconMap] : s.icon}
                            {s.label}
                        </span>
                    </Menu.Item>
                ))}
            </Menu.SubMenu>
        </Menu>
    );

    const cardClasses = [
        "h-full", "cursor-pointer", "transition-all", "duration-300", "ease-in-out", "shadow-sm", "hover:shadow-xl", "border",
        isSelected ? "ring-2 ring-blue-500 border-blue-400" : "border-gray-200",
        booking_info ? "bg-orange-50" : "",
        room.status !== 'available' && !booking_info ? "bg-gray-50" : "",
    ].join(' ');

    const handleCardClick = () => {
        if (multiSelectMode && room.status === 'available') {
            onRoomSelect(room.id, !isSelected);
        } else {
            onRoomClick(room);
        }
    };

    return (
        <Dropdown overlay={contextMenu} trigger={['contextMenu']}>
            <div className="relative h-full">
                <Card
                    className={cardClasses}
                    onClick={handleCardClick}
                    size="small"
                    styles={{ body: { padding: '12px', height: '100%' } }}
                >
                    <Flex vertical className="h-full">
                        <Flex justify="space-between" align="start">
                            <Title level={5} className="!mb-0 pr-2 truncate">{room.name}</Title>
                            <Tag icon={statusInfo.icon} color={statusInfo.color}>{statusInfo.label}</Tag>
                        </Flex>
                        <Text type="secondary" className="text-xs block mb-2">{room.room_type?.name || 'N/A'}</Text>

                        {booking_info ? (
                            <Card size="small" className="mt-2 bg-orange-100 border-orange-200 flex-grow">
                                <Flex align="center" gap={8}>
                                    <UserOutlined className="text-orange-700" />
                                    <Text strong className="text-sm text-orange-900 truncate" title={booking_info.guest_name}>
                                        {booking_info.guest_name}
                                    </Text>
                                </Flex>
                                <Divider className="my-1" />
                                <Flex align="center" gap={8}>
                                    <ClockCircleOutlined className="text-orange-700" />
                                    <Text className="text-xs text-gray-700">
                                        {booking_info.check_in} → {booking_info.check_out}
                                    </Text>
                                </Flex>
                            </Card>
                        ) : (
                            <div className="flex-grow flex items-center justify-center">
                                <Flex vertical align="center" gap={4}>
                                    <DollarCircleOutlined className="text-2xl text-gray-300" />
                                    <Text className="font-semibold text-lg text-gray-700">
                                        {new Intl.NumberFormat('vi-VN').format(room.room_type?.adjusted_price || 0)}
                                    </Text>
                                    <Text type="secondary" className="text-xs">VNĐ / đêm</Text>
                                </Flex>
                            </div>
                        )}

                        <div className="mt-auto pt-2 text-center">
                            <Text className="text-xs text-gray-400">Nhấn chuột phải để xem tùy chọn</Text>
                        </div>
                    </Flex>
                </Card>
                {multiSelectMode && room.status === 'available' && (
                    <Checkbox
                        className="absolute top-2 right-2 z-10"
                        checked={isSelected}
                        onClick={(e) => e.stopPropagation()} // Prevent card click
                        onChange={(e) => onRoomSelect(room.id, e.target.checked)}
                    />
                )}
                {isSelected && (
                    <div className="absolute top-0 left-0 w-full h-full bg-blue-500 bg-opacity-10 pointer-events-none flex items-center justify-center">
                        <CheckCircleFilled className="text-4xl text-white text-opacity-80" style={{ textShadow: '0 2px 4px rgba(0,0,0,0.3)' }} />
                    </div>
                )}
            </div>
        </Dropdown>
    );
});


const RoomGridView: React.FC<RoomGridViewProps> = ({
    rooms,
    allRooms,
    loading = false,
    onRoomClick = () => { },
    multiSelectMode = false,
    selectedRooms = new Set(),
    onRoomSelect = () => { },
    onBulkRoomSelect = () => { },
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

    const openBookingModal = useCallback((targetRooms: any[], nights?: number) => {
        if (targetRooms.length === 0) return;
        setBookingTarget(targetRooms);
        setQuickBookNights(nights);
        const initialValues: { guests: RoomGuestData } = { guests: {} };
        targetRooms.forEach(room => {
            initialValues.guests[room.id] = { adults: 1, children: [] };
        });
        bookingForm.setFieldsValue(initialValues);
        setBookingModalVisible(true);
    }, [bookingForm]);

    const handleConfirmBooking = useCallback(async () => {
        if (!navigate || !checkInDate || !checkOutDate) {
            message.error("Thiếu thông tin ngày để tiến hành đặt phòng.");
            return;
        };
        try {
            const values = await bookingForm.validateFields();
            const cleanedGuestsData: RoomGuestData = {};
            bookingTarget.forEach(room => {
                if (values.guests && values.guests[room.id]) {
                    cleanedGuestsData[room.id] = values.guests[room.id];
                }
            });
            const bookingPayload: BookingPayload = {
                guests: cleanedGuestsData,
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
            const quoteResult = await calculateQuote(quotePayload);

            navigate('/reception/confirm-representative-payment', {
                state: { selectedRooms: bookingTarget, bookingData: bookingPayload, checkInDate, checkOutDate, quoteData: quoteResult.data }
            });
            setBookingModalVisible(false);
        } catch (error) {
            message.error("Vui lòng điền đủ thông tin hoặc đã có lỗi xảy ra khi tính giá.");
        }
    }, [navigate, checkInDate, checkOutDate, bookingForm, bookingTarget, quickBookNights, calculateQuote, message]);

    const handleMenuClick = useCallback((key: string, room: any) => {
        if (key === 'book-normal') openBookingModal([room]);
        else if (key === 'book-quick-1') openBookingModal([room], 1);
        else if (key === 'book-quick-2') openBookingModal([room], 2);
        else if (key.startsWith('status-')) updateRoomStatusMutation.mutate({ roomId: parseInt(room.id), status: key.replace('status-', '') });
    }, [openBookingModal, updateRoomStatusMutation]);

    if (loading) return <div className="flex justify-center items-center h-96"><Spin size="large" tip="Đang tải danh sách phòng..." /></div>;
    if (rooms.length === 0) return <Empty className="py-16" description={<Title level={5}>Không có phòng nào phù hợp</Title>} />;

    const roomsByFloor = rooms.reduce((acc: any, room: any) => {
        const floor = room.floor || 'Chưa xác định';
        if (!acc[floor]) acc[floor] = [];
        acc[floor].push(room);
        return acc;
    }, {});

    return (
        <div className="space-y-8">
            {multiSelectMode && (
                <Card bordered={false} className=" border border-blue-200">
                    <Flex justify="space-between" align="center">
                        <Badge count={selectedRooms.size} overflowCount={99}>
                            <Title level={5} className="!m-0 mr-4">Phòng đã chọn</Title>
                        </Badge>
                        <Paragraph type="secondary" className="!m-0 flex-grow">Chọn các phòng còn trống để thêm vào danh sách đặt phòng.</Paragraph>
                        {selectedRooms.size > 0 && (
                            <Button type="primary" size="large" icon={<PlusOutlined />} onClick={() => openBookingModal(allRooms.filter(r => selectedRooms.has(r.id)))}>
                                Tiến hành đặt {selectedRooms.size} phòng
                            </Button>
                        )}
                    </Flex>
                </Card>
            )}

            {Object.keys(roomsByFloor).sort((a, b) => parseInt(a) - parseInt(b)).map(floor => (
                <div key={floor}>
                    <Flex justify="space-between" align="center" className="mb-4">
                        <Title level={3} className="!mb-0">Tầng {floor}</Title>
                        {multiSelectMode && (
                            <Button type="link" onClick={() => {
                                const floorRoomIds = roomsByFloor[floor].filter((r: any) => r.status === 'available').map((r: any) => r.id);
                                const allSelected = floorRoomIds.every((id: string) => selectedRooms.has(id));
                                onBulkRoomSelect(floorRoomIds, !allSelected);
                            }}>
                                {roomsByFloor[floor].filter((r: any) => r.status === 'available').every((r: any) => selectedRooms.has(r.id)) ? 'Bỏ chọn tất cả' : 'Chọn tất cả phòng trống'}
                            </Button>
                        )}
                    </Flex>
                    <Row gutter={[20, 20]}>
                        {roomsByFloor[floor].map((room: any) => (
                            <Col key={room.id} xs={12} sm={8} md={6} lg={6} xl={4}>
                                <RoomCard
                                    room={room}
                                    isSelected={selectedRooms.has(room.id)}
                                    multiSelectMode={multiSelectMode}
                                    onRoomSelect={onRoomSelect}
                                    onRoomClick={onRoomClick}
                                    onMenuClick={handleMenuClick}
                                    hasDateFilter={hasDateFilter}
                                />
                            </Col>
                        ))}
                    </Row>
                </div>
            ))}

            <Modal
                title={<Title level={4}>Thông tin khách đặt phòng</Title>}
                open={bookingModalVisible}
                onCancel={() => setBookingModalVisible(false)}
                footer={[
                    <Button key="back" onClick={() => setBookingModalVisible(false)}>Hủy</Button>,
                    <Button key="submit" type="primary" loading={isQuoteLoading} onClick={handleConfirmBooking}>Xác nhận và tiếp tục</Button>,
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