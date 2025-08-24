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
    CheckOutlined,
    RestOutlined,
    StarOutlined,
    CoffeeOutlined
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
        // optional status coming from backend booking (preferred source of truth for booked/occupied/cleaning)
        status?: string;
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
    // When provided, this set contains room ids that are available for the selected date range.
    availableRoomIds?: Set<string> | null;
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
                color: '#73d13d',
                bgColor: '#f6ffed',
                borderColor: '#b7eb8f',
                icon: <CheckCircleOutlined />,
                text: 'Trống',
                ribbonColor: 'green'
            },
            booked: {
                color: '#ffc53d',
                bgColor: '#fffbe6',
                borderColor: '#ffe58f',
                icon: <CalendarOutlined />,
                text: 'Đã đặt',
                ribbonColor: 'gold'
            },
            occupied: {
                color: '#40a9ff',
                bgColor: '#e6f7ff',
                borderColor: '#91d5ff',
                icon: <TeamOutlined />, // Changed icon to differentiate from cleaning
                text: 'Đang ở',
                ribbonColor: 'blue'
            },
            cleaning: {
                color: '#36cfc9',
                bgColor: '#e6fffb',
                borderColor: '#87e8de',
                icon: <RestOutlined />, // Use RestOutlined for cleaning status
                text: 'Đang dọn',
                ribbonColor: 'cyan'
            },
            out_of_service: {
                color: '#ff7875',
                bgColor: '#fff2f0',
                borderColor: '#ffccc7',
                icon: <StopOutlined />,
                text: 'Ngưng hoạt động',
                ribbonColor: 'red'
            },
            maintenance: {
                color: '#b37feb',
                bgColor: '#f9f0ff',
                borderColor: '#d3adf7',
                icon: <ToolOutlined />,
                text: 'Bảo trì',
                ribbonColor: 'purple'
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

    // Treat `room.status` as a simple usable flag (usable vs not-usable).
    // Use `booking_info.status` (if provided) as the authoritative booking state.
    const effectiveStatus = (() => {
        const notUsableStates = ['maintenance', 'out_of_service'];
        const isUsable = !notUsableStates.includes(room.status);

        if (room.booking_info) {
            const bStatus = (room.booking_info as any).status?.toString?.().toLowerCase?.();
            if (bStatus) {
                // Normalize common enum values from backend
                // Backend enums: 'Pending', 'Confirmed', 'Operational', 'Completed', 'Cancelled', 'Cancelled With Penalty', 'Unsuccessful', 'Cleaning'
                const bs = bStatus.toLowerCase();
                if (bs === 'operational') return 'occupied';
                if (bs === 'cleaning') return 'cleaning';
                if (bs === 'confirmed' || bs === 'pending') return 'booked';
                if (bs === 'completed' || bs.startsWith('cancel') || bs === 'unsuccessful') return 'available';
                // fallback for unknown booking statuses: treat as occupied to avoid double-booking UI
                return 'occupied';
            }
            // booking_info exists but no explicit status -> assume occupied (current booking)
            return 'occupied';
        }

        // No booking: if room is usable, it's available; otherwise keep room's non-usable state
        return isUsable ? 'available' : (room.status || 'out_of_service');
    })();

    const statusConfig = getStatusConfig(effectiveStatus);
    const icons = getRoomTypeIcons(room.room_type?.name || '');

    const handleCardClick = useCallback(() => {
        if (mode === 'view') {
            onViewDetails();
        } else {
            onSelect(!isSelected);
        }
    }, [mode, isSelected, onSelect, onViewDetails]);

    // Render booking info (if any) or maintenance reason when room is not usable
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
                    {/* Optional: show booking status if backend sent it */}
                    {(room.booking_info as any).status && (
                        <div className="flex items-center space-x-1">
                            <InfoCircleOutlined style={{ color: '#fff', fontSize: 12 }} />
                            <Text style={{ color: '#fff', fontSize: 11 }}>
                                {(room.booking_info as any).status}
                            </Text>
                        </div>
                    )}
                </div>
            );
        }

        const notUsableStates = ['maintenance', 'out_of_service'];
        if (notUsableStates.includes(room.status)) {
            return (
                <div className="space-y-1">
                    <div className="flex items-center space-x-1">
                        <ToolOutlined style={{ color: '#fff', fontSize: 12 }} />
                        <Text style={{ color: '#fff', fontSize: 11 }}>
                            {room.maintenance_reason || 'Không sử dụng'}
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
                    disabled={effectiveStatus === 'out_of_service'}
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
    onViewDetails,
    availableRoomIds = null
}: {
    floor: string;
    rooms: RoomInfo[];
    selectedRooms: Set<string>;
    mode: 'view' | 'select';
    onRoomSelect: (roomId: string, selected: boolean) => void;
    onFloorSelect: (floorId: string, roomIds: string[], selected: boolean) => void;
    onViewDetails: (roomId: string) => void;
    availableRoomIds?: Set<string> | null;
}) => {
    // Tính toán thống kê tầng
    const floorStats = useMemo(() => {
        const total = rooms.length;

        // compute effective status for each room: booking_info.status (preferred) -> occupied/booked/cleaning
        const computeEffective = (r: RoomInfo) => {
            const notUsable = ['maintenance', 'out_of_service'];
            const isUsable = !notUsable.includes(r.status);
            if (r.booking_info) {
                const bStatus = (r.booking_info as any).status?.toString?.().toLowerCase?.();
                if (bStatus) {
                    const bs = bStatus;
                    if (bs === 'operational') return 'occupied';
                    if (bs === 'cleaning') return 'cleaning';
                    if (bs === 'confirmed' || bs === 'pending') return 'booked';
                    if (bs === 'completed' || bs.startsWith('cancel') || bs === 'unsuccessful') return 'available';
                    return 'occupied';
                }
                return 'occupied';
            }
            return isUsable ? 'available' : (r.status || 'out_of_service');
        };

        // If availableRoomIds provided by parent, use it to compute available count.
        const available = availableRoomIds ? rooms.filter(r => availableRoomIds.has((r.id ?? '').toString())).length : rooms.filter(r => computeEffective(r) === 'available').length;
        const occupied = rooms.filter(r => computeEffective(r) === 'occupied').length;
        const booked = rooms.filter(r => computeEffective(r) === 'booked').length;
        const cleaning = rooms.filter(r => computeEffective(r) === 'cleaning').length;
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
            cleaning,
            maintenance,
            roomIds,
            selectedCount,
            allSelected,
            indeterminate
        };
    }, [rooms, selectedRooms, availableRoomIds]);

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
                        <Tag color="#73d13d" style={{ backgroundColor: '#f6ffed', borderColor: '#b7eb8f', color: '#389e0d' }} icon={<CheckCircleOutlined />}>
                            Trống: {floorStats.available}
                        </Tag>
                        <Tag color="#40a9ff" style={{ backgroundColor: '#e6f7ff', borderColor: '#91d5ff', color: '#096dd9' }} icon={<TeamOutlined />}>
                            Đang ở: {floorStats.occupied}
                        </Tag>
                        <Tag color="#ffc53d" style={{ backgroundColor: '#fffbe6', borderColor: '#ffe58f', color: '#d48806' }} icon={<CalendarOutlined />}>
                            Đã đặt: {floorStats.booked}
                        </Tag>
                        {floorStats.cleaning > 0 && (
                            <Tag color="#36cfc9" style={{ backgroundColor: '#e6fffb', borderColor: '#87e8de', color: '#08979c' }} icon={<RestOutlined />}>
                                Đang dọn: {floorStats.cleaning}
                            </Tag>
                        )}
                        {floorStats.maintenance > 0 && (
                            <Tag color="#ff7875" style={{ backgroundColor: '#fff2f0', borderColor: '#ffccc7', color: '#cf1322' }} icon={<ToolOutlined />}>
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
    // detail modal state is unused in this component; higher-level page handles details

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
        // Details handled by parent via onViewDetails; keep this lightweight
        onViewDetails?.(roomId);
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

// note: visual status config is defined inline in RoomCard; no global helper needed

export default RoomCardGrid;
export type { RoomInfo, RoomCardGridProps };
