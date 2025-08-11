import React, { useState, useMemo, useCallback, memo } from 'react';
import { CheckCard } from '@ant-design/pro-components';
import {
    Row,
    Col,
    Typography,
    Space,
    Switch,
    Checkbox,
    Skeleton,
    Modal,
    Descriptions,
    Tag,
    Divider,
    Button,
    Tooltip,
    Card,
    Empty,
    ConfigProvider
} from 'antd';
import {
    HomeOutlined,
    UserOutlined,
    CalendarOutlined,
    TeamOutlined,
    BellOutlined,
    CheckCircleOutlined,
    ClockCircleOutlined,
    StopOutlined,
    ToolOutlined,
    InfoCircleOutlined,
    CheckOutlined
} from '@ant-design/icons';
import dayjs from 'dayjs';
import 'dayjs/locale/vi';

dayjs.locale('vi');

const { Title, Text } = Typography;

// Types
interface RoomInfo {
    id: string;
    name: string;
    floor: number | string;
    status: 'available' | 'booked' | 'occupied' | 'out_of_service' | 'maintenance' | 'cleaning';
    room_type?: {
        id: string;
        name: string;
    };
    booking_info?: {
        check_in: string;
        check_out: string;
        guest_name?: string;
        guest_count?: number;
        representative_name?: string;
    };
    maintenance_reason?: string;
    last_cleaned?: string;
}

interface RoomCardGridProps {
    rooms: RoomInfo[];
    loading?: boolean;
    mode?: 'view' | 'select';
    selectedRooms?: Set<string>;
    onRoomSelect?: (roomId: string, selected: boolean) => void;
    onFloorSelect?: (floorId: string, roomIds: string[], selected: boolean) => void;
    onViewDetails?: (roomId: string) => void;
    onModeChange?: (mode: 'view' | 'select') => void;
}

// Memoized Room Card Component để tối ưu hiệu năng
const RoomCard = memo(({
    room,
    isSelected,
    mode,
    onSelect,
    onViewDetails
}: {
    room: RoomInfo;
    isSelected: boolean;
    mode: 'view' | 'select';
    onSelect: (selected: boolean) => void;
    onViewDetails: () => void;
}) => {
    // Xác định màu và icon theo trạng thái
    const getStatusConfig = (status: string) => {
        const configs: Record<string, {
            color: string;
            bgColor: string;
            borderColor: string;
            icon: React.ReactElement;
            text: string;
            ribbonColor: string;
        }> = {
            available: {
                color: '#52c41a',
                bgColor: '#f6ffed',
                borderColor: '#b7eb8f',
                icon: <CheckCircleOutlined />,
                text: 'Trống',
                ribbonColor: 'green'
            },
            booked: {
                color: '#faad14',
                bgColor: '#fffbe6',
                borderColor: '#ffe58f',
                icon: <CalendarOutlined />,
                text: 'Đã đặt',
                ribbonColor: 'gold'
            },
            occupied: {
                color: '#1890ff',
                bgColor: '#e6f7ff',
                borderColor: '#91d5ff',
                icon: <UserOutlined />,
                text: 'Đang ở',
                ribbonColor: 'blue'
            },
            out_of_service: {
                color: '#ff4d4f',
                bgColor: '#fff2f0',
                borderColor: '#ffccc7',
                icon: <StopOutlined />,
                text: 'Ngưng hoạt động',
                ribbonColor: 'red'
            },
            maintenance: {
                color: '#722ed1',
                bgColor: '#f9f0ff',
                borderColor: '#d3adf7',
                icon: <ToolOutlined />,
                text: 'Bảo trì',
                ribbonColor: 'purple'
            },
            cleaning: {
                color: '#13c2c2',
                bgColor: '#e6fffb',
                borderColor: '#87e8de',
                icon: <ClockCircleOutlined />,
                text: 'Đang dọn',
                ribbonColor: 'cyan'
            }
        };
        return configs[status] || configs.available;
    };

    // Xác định icons theo loại phòng
    const getRoomTypeIcons = (typeName: string) => {
        const name = typeName?.toLowerCase() || '';
        if (name.includes('suite')) {
            return [
                <HomeOutlined key="suite" style={{ fontSize: 20, color: '#fff' }} />,
                <BellOutlined key="service" style={{ fontSize: 20, color: '#fff' }} />
            ];
        } else if (name.includes('deluxe')) {
            return [
                <TeamOutlined key="deluxe" style={{ fontSize: 20, color: '#fff' }} />,
                <BellOutlined key="service" style={{ fontSize: 20, color: '#fff' }} />
            ];
        } else if (name.includes('standard')) {
            return [<HomeOutlined key="standard" style={{ fontSize: 20, color: '#fff' }} />];
        } else {
            return [<HomeOutlined key="default" style={{ fontSize: 20, color: '#fff' }} />];
        }
    };

    const statusConfig = getStatusConfig(room.status);
    const icons = getRoomTypeIcons(room.room_type?.name || '');

    const handleCardClick = useCallback(() => {
        if (mode === 'view') {
            onViewDetails();
        } else {
            onSelect(!isSelected);
        }
    }, [mode, isSelected, onSelect, onViewDetails]);

    // Render thông tin booking hoặc lý do bảo trì
    const renderCardContent = () => {
        if (room.booking_info) {
            return (
                <div className="space-y-1">
                    <Text style={{ color: '#fff', fontSize: 12, fontWeight: 500 }}>
                        {dayjs(room.booking_info.check_in).format('DD/MM')} - {dayjs(room.booking_info.check_out).format('DD/MM')}
                    </Text>
                    {room.booking_info.guest_name && (
                        <div className="flex items-center space-x-1">
                            <UserOutlined style={{ color: '#fff', fontSize: 12 }} />
                            <Text style={{ color: '#fff', fontSize: 11 }}>
                                {room.booking_info.guest_count && `${room.booking_info.guest_count} `}
                                {room.booking_info.guest_name}
                            </Text>
                        </div>
                    )}
                </div>
            );
        } else if (room.status === 'maintenance' || room.status === 'out_of_service') {
            return (
                <div className="space-y-1">
                    <div className="flex items-center space-x-1">
                        <ToolOutlined style={{ color: '#fff', fontSize: 12 }} />
                        <Text style={{ color: '#fff', fontSize: 11 }}>
                            {room.maintenance_reason || 'Bảo trì'}
                        </Text>
                    </div>
                </div>
            );
        }
        return null;
    };

    return (
        <ConfigProvider
            theme={{
                token: {
                    colorPrimary: statusConfig.color,
                },
            }}
        >
            <div className="relative">
                <CheckCard
                    checked={isSelected && mode === 'select'}
                    onChange={mode === 'select' ? onSelect : undefined}
                    onClick={handleCardClick}
                    disabled={room.status === 'out_of_service'}
                    className={`
                        transition-all duration-300 cursor-pointer
                        hover:shadow-lg hover:-translate-y-1
                        ${isSelected && mode === 'select' ? 'ring-2 ring-blue-400' : ''}
                    `}
                    style={{
                        width: '100%',
                        height: 160,
                        borderRadius: 12,
                        backgroundColor: statusConfig.color,
                        border: `2px solid ${statusConfig.borderColor}`,
                        overflow: 'hidden'
                    }}
                    bodyStyle={{ padding: 0, height: '100%' }}
                >
                    {/* Header với icons */}
                    <div className="flex justify-between items-start p-3 h-full">
                        <div className="flex space-x-2">
                            {icons}
                        </div>

                        {/* Tên phòng - ribbon style */}
                        <div
                            className="absolute top-0 right-0 px-3 py-1 text-white font-bold text-lg"
                            style={{
                                backgroundColor: 'rgba(0,0,0,0.2)',
                                borderBottomLeftRadius: 12
                            }}
                        >
                            {room.name}
                        </div>
                    </div>

                    {/* Content */}
                    <div className="absolute bottom-0 left-0 right-0 p-3">
                        {renderCardContent()}
                    </div>

                    {/* Status badge */}
                    <div className="absolute top-3 left-3">
                        <Tooltip title={statusConfig.text}>
                            <div
                                className="w-6 h-6 rounded-full flex items-center justify-center"
                                style={{ backgroundColor: 'rgba(255,255,255,0.2)' }}
                            >
                                <span style={{ color: '#fff', fontSize: 12 }}>
                                    {statusConfig.icon}
                                </span>
                            </div>
                        </Tooltip>
                    </div>
                </CheckCard>
            </div>
        </ConfigProvider>
    );
});

// Skeleton component cho loading
const RoomCardSkeleton = memo(() => (
    <div className="w-full h-40 bg-gray-200 rounded-lg animate-pulse relative overflow-hidden">
        <div className="absolute top-3 left-3 w-6 h-6 bg-gray-300 rounded-full"></div>
        <div className="absolute top-3 right-3 w-16 h-6 bg-gray-300 rounded"></div>
        <div className="absolute bottom-3 left-3 space-y-2">
            <div className="w-20 h-3 bg-gray-300 rounded"></div>
            <div className="w-24 h-3 bg-gray-300 rounded"></div>
        </div>
    </div>
));

// Main Floor Component được memoize
const FloorSection = memo(({
    floor,
    rooms,
    selectedRooms,
    mode,
    onRoomSelect,
    onFloorSelect,
    onViewDetails
}: {
    floor: string;
    rooms: RoomInfo[];
    selectedRooms: Set<string>;
    mode: 'view' | 'select';
    onRoomSelect: (roomId: string, selected: boolean) => void;
    onFloorSelect: (floorId: string, roomIds: string[], selected: boolean) => void;
    onViewDetails: (roomId: string) => void;
}) => {
    // Tính toán thống kê tầng
    const floorStats = useMemo(() => {
        const total = rooms.length;
        const available = rooms.filter(r => r.status === 'available').length;
        const occupied = rooms.filter(r => r.status === 'occupied').length;
        const booked = rooms.filter(r => r.status === 'booked').length;
        const maintenance = rooms.filter(r => ['maintenance', 'out_of_service'].includes(r.status)).length;
        const roomIds = rooms.map(r => r.id);
        const selectedCount = roomIds.filter(id => selectedRooms.has(id)).length;
        const allSelected = selectedCount === total && total > 0;
        const indeterminate = selectedCount > 0 && selectedCount < total;

        return {
            total,
            available,
            occupied,
            booked,
            maintenance,
            roomIds,
            selectedCount,
            allSelected,
            indeterminate
        };
    }, [rooms, selectedRooms]);

    const handleFloorCheckboxChange = useCallback((checked: boolean) => {
        onFloorSelect(floor, floorStats.roomIds, checked);
    }, [floor, floorStats.roomIds, onFloorSelect]);

    return (
        <Card
            className="mb-6 shadow-sm hover:shadow-md transition-shadow"
            bodyStyle={{ padding: '20px' }}
        >
            {/* Floor Header */}
            <div className="flex justify-between items-center mb-4">
                <div className="flex items-center space-x-4">
                    {mode === 'select' && (
                        <Checkbox
                            checked={floorStats.allSelected}
                            indeterminate={floorStats.indeterminate}
                            onChange={(e) => handleFloorCheckboxChange(e.target.checked)}
                        >
                            <Title level={4} className="!mb-0">Tầng {floor}</Title>
                        </Checkbox>
                    )}
                    {mode === 'view' && (
                        <Title level={4} className="!mb-0">Tầng {floor}</Title>
                    )}

                    {/* Floor Statistics */}
                    <Space size="small">
                        <Tag color="green" icon={<CheckCircleOutlined />}>
                            Trống: {floorStats.available}
                        </Tag>
                        <Tag color="blue" icon={<UserOutlined />}>
                            Đang ở: {floorStats.occupied}
                        </Tag>
                        <Tag color="gold" icon={<CalendarOutlined />}>
                            Đã đặt: {floorStats.booked}
                        </Tag>
                        {floorStats.maintenance > 0 && (
                            <Tag color="red" icon={<ToolOutlined />}>
                                Bảo trì: {floorStats.maintenance}
                            </Tag>
                        )}
                    </Space>
                </div>

                {mode === 'select' && (
                    <div className="text-sm text-gray-500">
                        Đã chọn: {floorStats.selectedCount}/{floorStats.total}
                    </div>
                )}
            </div>

            {/* Room Grid */}
            <Row gutter={[16, 16]}>
                {rooms.map(room => (
                    <Col
                        key={room.id}
                        xs={12} sm={8} md={6} lg={4} xl={4} xxl={4}
                    >
                        <RoomCard
                            room={room}
                            isSelected={selectedRooms.has(room.id)}
                            mode={mode}
                            onSelect={(selected) => onRoomSelect(room.id, selected)}
                            onViewDetails={() => onViewDetails(room.id)}
                        />
                    </Col>
                ))}
            </Row>
        </Card>
    );
});

// Main Component
const RoomCardGrid: React.FC<RoomCardGridProps> = ({
    rooms,
    loading = false,
    mode: propMode = 'view',
    selectedRooms = new Set(),
    onRoomSelect,
    onFloorSelect,
    onViewDetails,
    onModeChange
}) => {
    const [currentMode, setCurrentMode] = useState<'view' | 'select'>(propMode);
    const [roomDetailModalVisible, setRoomDetailModalVisible] = useState(false);
    const [selectedRoomForDetail, setSelectedRoomForDetail] = useState<RoomInfo | null>(null);

    // Group rooms by floor
    const roomsByFloor = useMemo(() => {
        const grouped = rooms.reduce((acc, room) => {
            const floor = room.floor?.toString() || '0';
            if (!acc[floor]) acc[floor] = [];
            acc[floor].push(room);
            return acc;
        }, {} as Record<string, RoomInfo[]>);

        // Sort floors numerically
        return Object.keys(grouped)
            .sort((a, b) => parseInt(a) - parseInt(b))
            .reduce((acc, floor) => {
                acc[floor] = grouped[floor].sort((a, b) =>
                    a.name.localeCompare(b.name, 'vi', { numeric: true })
                );
                return acc;
            }, {} as Record<string, RoomInfo[]>);
    }, [rooms]);

    // Handle mode change
    const handleModeChange = useCallback((newMode: 'view' | 'select') => {
        setCurrentMode(newMode);
        onModeChange?.(newMode);
    }, [onModeChange]);

    // Handle view details
    const handleViewDetails = useCallback((roomId: string) => {
        const room = rooms.find(r => r.id === roomId);
        if (room) {
            setSelectedRoomForDetail(room);
            setRoomDetailModalVisible(true);
            onViewDetails?.(roomId);
        }
    }, [rooms, onViewDetails]);

    // Handle room selection
    const handleRoomSelect = useCallback((roomId: string, selected: boolean) => {
        onRoomSelect?.(roomId, selected);
    }, [onRoomSelect]);

    // Handle floor selection
    const handleFloorSelect = useCallback((floorId: string, roomIds: string[], selected: boolean) => {
        onFloorSelect?.(floorId, roomIds, selected);
    }, [onFloorSelect]);

    // Render loading skeleton
    if (loading) {
        return (
            <div className="space-y-6">
                {[1, 2, 3].map(floor => (
                    <Card key={floor} className="shadow-sm">
                        <div className="flex justify-between items-center mb-4">
                            <Skeleton.Input active style={{ width: 200 }} />
                            <Skeleton.Input active style={{ width: 300 }} />
                        </div>
                        <Row gutter={[16, 16]}>
                            {Array.from({ length: 8 }).map((_, idx) => (
                                <Col key={idx} xs={12} sm={8} md={6} lg={4} xl={4} xxl={4}>
                                    <RoomCardSkeleton />
                                </Col>
                            ))}
                        </Row>
                    </Card>
                ))}
            </div>
        );
    }

    // Render empty state
    if (rooms.length === 0) {
        return (
            <Empty
                description="Không có phòng nào"
                image={Empty.PRESENTED_IMAGE_SIMPLE}
            />
        );
    }

    return (
        <div className="space-y-6">
            {/* Mode Toggle */}
            <Card className="shadow-sm">
                <div className="flex justify-between items-center">
                    <Title level={3} className="!mb-0">
                        Quản lý phòng
                    </Title>
                    <Space align="center">
                        <Text>Xem trạng thái</Text>
                        <Switch
                            checked={currentMode === 'select'}
                            onChange={(checked) => handleModeChange(checked ? 'select' : 'view')}
                            checkedChildren="Chọn phòng"
                            unCheckedChildren="Xem phòng"
                        />
                        <Text>Chọn phòng</Text>
                    </Space>
                </div>
            </Card>

            {/* Selection Summary */}
            {currentMode === 'select' && selectedRooms.size > 0 && (
                <Card className="bg-blue-50 border-blue-200 shadow-sm">
                    <div className="flex justify-between items-center">
                        <Text>
                            <CheckOutlined className="text-blue-500 mr-2" />
                            Đã chọn {selectedRooms.size} phòng
                        </Text>
                        <Space>
                            <Button
                                size="small"
                                onClick={() => onFloorSelect?.('all', [], false)}
                            >
                                Bỏ chọn tất cả
                            </Button>
                        </Space>
                    </div>
                </Card>
            )}

            {/* Floor Sections */}
            {Object.entries(roomsByFloor).map(([floor, floorRooms]) => (
                <FloorSection
                    key={floor}
                    floor={floor}
                    rooms={floorRooms}
                    selectedRooms={selectedRooms}
                    mode={currentMode}
                    onRoomSelect={handleRoomSelect}
                    onFloorSelect={handleFloorSelect}
                    onViewDetails={handleViewDetails}
                />
            ))}

        </div>
    );
};

// Helper function moved outside component để tránh re-render
const getStatusConfig = (status: string) => {
    const configs: Record<string, {
        color: string;
        bgColor: string;
        borderColor: string;
        text: string;
        ribbonColor: string;
    }> = {
        available: {
            color: '#52c41a',
            bgColor: '#f6ffed',
            borderColor: '#b7eb8f',
            text: 'Trống',
            ribbonColor: 'green'
        },
        booked: {
            color: '#faad14',
            bgColor: '#fffbe6',
            borderColor: '#ffe58f',
            text: 'Đã đặt',
            ribbonColor: 'gold'
        },
        occupied: {
            color: '#1890ff',
            bgColor: '#e6f7ff',
            borderColor: '#91d5ff',
            text: 'Đang ở',
            ribbonColor: 'blue'
        },
        out_of_service: {
            color: '#ff4d4f',
            bgColor: '#fff2f0',
            borderColor: '#ffccc7',
            text: 'Ngưng hoạt động',
            ribbonColor: 'red'
        },
        maintenance: {
            color: '#722ed1',
            bgColor: '#f9f0ff',
            borderColor: '#d3adf7',
            text: 'Bảo trì',
            ribbonColor: 'purple'
        },
        cleaning: {
            color: '#13c2c2',
            bgColor: '#e6fffb',
            borderColor: '#87e8de',
            text: 'Đang dọn',
            ribbonColor: 'cyan'
        }
    };
    return configs[status] || configs.available;
};

export default RoomCardGrid;
export type { RoomInfo, RoomCardGridProps };
