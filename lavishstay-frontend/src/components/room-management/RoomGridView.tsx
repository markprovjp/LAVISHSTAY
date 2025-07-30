import React, { memo, useState, useCallback, useRef, createRef } from 'react';
import { Card, Row, Col, Typography, Empty, Spin, Tag, Checkbox, Button, Space, Input, Tooltip, Flex, App, Divider } from 'antd';
import { UserOutlined, ClockCircleOutlined, DollarCircleOutlined, HomeOutlined, CheckCircleFilled, CloseCircleOutlined, EyeOutlined, SearchOutlined } from '@ant-design/icons';
import { statusOptions } from '../../constants/roomStatus';

const { Text, Title } = Typography;

const iconMap: { [key: string]: React.ReactNode } = {
    CheckCircleFilled: <CheckCircleFilled />,
    UserOutlined: <UserOutlined />,
    ClockCircleOutlined: <ClockCircleOutlined />,
    DollarCircleOutlined: <DollarCircleOutlined />,
    HomeOutlined: <HomeOutlined />,
    CloseCircleOutlined: <CloseCircleOutlined />,
};

// --- Type Definitions ---
interface RoomGridViewProps {
    rooms: any[];
    loading?: boolean;
    multiSelectMode?: boolean;
    selectedRooms?: Set<string>;
    onRoomSelect: (roomId: string, selected: boolean) => void;
    onBulkRoomSelect: (roomIds: string[], select: boolean) => void;
    onViewDetails: (roomId: string) => void;
}

// --- Optimized RoomCard Component ---
const RoomCard: React.FC<{
    room: any;
    isSelected: boolean;
    multiSelectMode: boolean;
    onRoomSelect: (roomId: string, selected: boolean) => void;
    onViewDetails: (roomId: string) => void;
}> = memo(({ room, isSelected, multiSelectMode, onRoomSelect, onViewDetails }) => {
    const statusInfoRaw = statusOptions.find(s => s.value === room.status);
    const statusInfo = statusInfoRaw
        ? { ...statusInfoRaw, icon: typeof statusInfoRaw.icon === 'string' ? iconMap[statusInfoRaw.icon] : statusInfoRaw.icon }
        : { label: room.status, color: 'default', icon: <HomeOutlined /> };
    const { booking_info } = room;

    const cardClasses = [
        "h-full", "transition-all", "duration-300", "ease-in-out", "shadow-sm", "hover:shadow-xl", "border",
        isSelected ? "ring-2 ring-blue-500 border-blue-400" : "border-gray-200",
        booking_info ? "bg-orange-50" : "",
        room.status !== 'available' && !booking_info ? "bg-gray-50" : "",
    ].join(' ');

    const handleCardClick = () => {
        if (multiSelectMode && room.status === 'available') {
            onRoomSelect(room.id, !isSelected);
        }
    };

    return (
        <div className="relative h-full">
            <Card
                className={cardClasses}
                size="small"
                styles={{ body: { padding: '12px', height: '100%', cursor: 'pointer' } }}
                onClick={handleCardClick}
            >
                <Flex vertical className="h-full">
                    <Flex justify="space-between" align="start">
                        <Title level={5} className="!mb-0 pr-2 truncate">{room.name}</Title>
                        <Tag icon={statusInfo.icon} color={statusInfo.color}>{statusInfo.label}</Tag>
                    </Flex>
                    <Text type="secondary" className="text-xs block mb-2">{room.room_type?.name || 'N/A'}</Text>

                    <div className="flex-grow">
                        {booking_info ? (
                            <Card size="small" className="mt-2 bg-orange-100 border-orange-200">
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
                            <Flex vertical align="center" justify="center" className="h-full">
                                <DollarCircleOutlined className="text-2xl text-gray-300" />
                                <Text className="font-semibold text-lg text-gray-700">
                                    {new Intl.NumberFormat('vi-VN').format(room.room_type?.adjusted_price || 0)}
                                </Text>
                                <Text type="secondary" className="text-xs">VNĐ / đêm</Text>
                            </Flex>
                        )}
                    </div>

                    <Tooltip title="Xem chi tiết phòng">
                        <Button
                            type="text"
                            shape="circle"
                            icon={<EyeOutlined />}
                            className="absolute top-10 right-1 view-details-btn"
                            onClick={(e) => {
                                e.stopPropagation(); // Prevent card click from firing
                                onViewDetails(room.id);
                            }}
                        />
                    </Tooltip>
                </Flex>
            </Card>
            {multiSelectMode && room.status === 'available' && (
                <Checkbox
                    className="absolute top-2 right-2 z-10"
                    checked={isSelected}
                    onChange={(e) => onRoomSelect(room.id, e.target.checked)}
                    onClick={(e) => e.stopPropagation()}
                />
            )}
        </div>
    );
});

const RoomGridView: React.FC<RoomGridViewProps> = ({
    rooms,
    loading = false,
    multiSelectMode = false,
    selectedRooms = new Set(),
    onRoomSelect = () => {},
    onBulkRoomSelect = () => {},
    onViewDetails
}) => {
    const { message } = App.useApp();
    const [jumpToValue, setJumpToValue] = useState('');
    const floorRefs = useRef<Record<string, React.RefObject<HTMLDivElement>>>({});
    const roomRefs = useRef<Record<string, React.RefObject<HTMLDivElement>>>({});

    // Create refs for floors and rooms
    rooms.forEach(room => {
        const floor = room.floor || 'Chưa xác định';
        if (!floorRefs.current[floor]) {
            floorRefs.current[floor] = createRef<HTMLDivElement>();
        }
        if (!roomRefs.current[room.id]) {
            roomRefs.current[room.id] = createRef<HTMLDivElement>();
        }
    });

    const handleJumpTo = () => {
        const query = jumpToValue.trim().toLowerCase().replace('tầng ', '');
        if (!query) return;

        // Check for floor match first
        const floorRefKey = Object.keys(floorRefs.current).find(floor => floor.toLowerCase() === query);
        if (floorRefKey && floorRefs.current[floorRefKey]?.current) {
            floorRefs.current[floorRefKey].current?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            return;
        }

        // Check for room name match
        const room = rooms.find(r => r.name.toLowerCase() === query);
        if (room && roomRefs.current[room.id]?.current) {
            const roomElement = roomRefs.current[room.id].current;
            roomElement?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            // Highlight the card briefly
            roomElement?.classList.add('ring-2', 'ring-green-500', 'transition-all', 'duration-300');
            setTimeout(() => roomElement?.classList.remove('ring-2', 'ring-green-500'), 2500);
            return;
        }

        message.warning(`Không tìm thấy tầng hoặc phòng "${jumpToValue}"`);
    };

    if (loading) return <div className="flex justify-center items-center h-96"><Spin size="large" tip="Đang tải danh sách phòng..." /></div>;
    if (rooms.length === 0) return <Empty className="py-16" description={<Title level={5}>Không có phòng nào phù hợp</Title>} />;

    const roomsByFloor = rooms.reduce((acc: { [key: string]: any[] }, room: any) => {
        const floor = room.floor || 'Chưa xác định';
        if (!acc[floor]) acc[floor] = [];
        acc[floor].push(room);
        return acc;
    }, {});

    return (
        <div className="space-y-8">
            <div className="sticky top-0  z-20 py-3 px-4 border-b">
                <Input.Search
                    placeholder="Nhập số phòng hoặc tầng (ví dụ: 101, Tầng 1)"
                    enterButton={<Button type="primary" icon={<SearchOutlined />}>Đi đến</Button>}
                    size="large"
                    value={jumpToValue}
                    onChange={(e) => setJumpToValue(e.target.value)}
                    onSearch={handleJumpTo}
                    allowClear
                />
            </div>

            {Object.keys(roomsByFloor).sort((a, b) => parseInt(a) - parseInt(b)).map(floor => (
                <div key={floor} ref={floorRefs.current[floor]}>
                    <Flex justify="space-between" align="center" className="mb-4 px-2">
                        <Title level={3} className="!mb-0">Tầng {floor}</Title>
                        {multiSelectMode && (
                            <Button type="link" onClick={() => {
                                const floorRoomIds = roomsByFloor[floor].filter(r => r.status === 'available').map(r => r.id);
                                const allSelectedOnFloor = floorRoomIds.every(id => selectedRooms.has(id));
                                onBulkRoomSelect(floorRoomIds, !allSelectedOnFloor);
                            }}>
                                {roomsByFloor[floor].filter(r => r.status === 'available').every(r => selectedRooms.has(r.id)) ? 'Bỏ chọn tất cả' : 'Chọn tất cả phòng trống'}
                            </Button>
                        )}
                    </Flex>
                    <Row gutter={[20, 20]}>
                        {roomsByFloor[floor].map((room: any) => (
                            <Col key={room.id} xs={12} sm={8} md={6} lg={6} xl={4}>
                                <div ref={roomRefs.current[room.id]}>
                                    <RoomCard
                                        room={room}
                                        isSelected={selectedRooms.has(room.id)}
                                        multiSelectMode={multiSelectMode}
                                        onRoomSelect={onRoomSelect}
                                        onViewDetails={onViewDetails}
                                    />
                                </div>
                            </Col>
                        ))}
                    </Row>
                </div>
            ))}
        </div>
    );
};

export default memo(RoomGridView);
