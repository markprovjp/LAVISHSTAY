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
    Tag,
    Button,
    Tooltip,
    Card,
    Empty,
    ConfigProvider,
    Progress
} from 'antd';
import {
    HomeOutlined,
    UserOutlined,
    CalendarOutlined,
    TeamOutlined,
    // BellOutlined (unused) removed
    CheckCircleOutlined,
    StopOutlined,
    ToolOutlined,
    InfoCircleOutlined,
    CheckOutlined,
    RestOutlined,
    CloseCircleOutlined,

} from '@ant-design/icons';
import { Bed as LucideBed, Users as LucideUsers, CalendarDays as LucideCalendar, RotateCcw as LucideRotateCcw, X as LucideX, Wrench as LucideWrench } from 'lucide-react';
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
    // Some backend endpoints return `booking_status` at the room root instead of inside booking_info
    booking_status?: string;
    // Cleaning workflow fields (optional, provided by backend)
    is_cleaning?: boolean;
    cleaning_ends_at?: string | null;
    cleaning_note?: string | null;
    // Add bed_type_name field
    bed_type_name?: string;
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
                // Emerald - strong but harmonious on white background
                color: '#16a34a',
                bgColor: '#ecfdf5',
                borderColor: '#14532d',
                icon: <CheckCircleOutlined />,
                text: 'Trống',
                ribbonColor: 'green'
            },
            booked: {
                // Orange/amber - warm, trending
                color: '#f97316',
                bgColor: '#fff7ed',
                borderColor: '#c2410c',
                icon: <CalendarOutlined />,
                text: 'Đã đặt',
                ribbonColor: 'gold'
            },
            occupied: {
                // Blue indigo - clear contrast
                color: '#2563eb',
                bgColor: '#eff6ff',
                borderColor: '#1e40af',
                icon: <TeamOutlined />, // Changed icon to differentiate from cleaning
                text: 'Đang ở',
                ribbonColor: 'blue'
            },
            cleaning: {
                // Cyan/teal - fresh
                color: '#06b6d4',
                bgColor: '#ecfeff',
                borderColor: '#0369a1',
                icon: <RestOutlined />,
                text: 'Đang dọn',
                ribbonColor: 'cyan'
            },
            cancelled: {
                // Slate/neutral - subdued but legible
                color: '#475569',
                bgColor: '#f8fafc',
                borderColor: '#334155',
                icon: <CloseCircleOutlined />,
                text: 'Đã huỷ',
                ribbonColor: 'default'
            },
            out_of_service: {
                // Red - clear problem state
                color: '#ef4444',
                bgColor: '#fff1f2',
                borderColor: '#991b1b',
                icon: <StopOutlined />,
                text: 'Ngưng hoạt động',
                ribbonColor: 'red'
            },
            maintenance: {
                // Violet - stylish, trending accent
                color: '#7c3aed',
                bgColor: '#f5f3ff',
                borderColor: '#5b21b6',
                icon: <ToolOutlined />,
                text: 'Bảo trì',
                ribbonColor: 'purple'
            }
        };
        return configs[status] || configs.available;
    };

    // Chọn icon dựa vào trạng thái phòng / booking (thay vì chỉ dựa vào loại phòng)
    const getStatusIcon = (roomStatus: string, bookingStatus?: string) => {
        const b = bookingStatus?.toString?.().toLowerCase?.();
        const rs = roomStatus?.toString?.().toLowerCase?.();
        // Prioritize booking-derived states
        if (b === 'cleaning') return <LucideRotateCcw size={18} color="#fff" />;
        if (b === 'operational' || rs === 'occupied') return <LucideUsers size={18} color="#fff" />;
        if (b === 'confirmed' || b === 'pending') return <LucideCalendar size={18} color="#fff" />;
        if (rs === 'maintenance') return <LucideWrench size={18} color="#fff" />;
        if (rs === 'out_of_service') return <LucideX size={18} color="#fff" />;
        // default: show bed/home
        return <LucideBed size={18} color="#fff" />;
    };

    // Treat `room.status` as a simple usable flag (usable vs not-usable).
    // Use `booking_info.status` (if provided) as the authoritative booking state.
    const effectiveStatus = (() => {
        const notUsableStates = ['maintenance', 'out_of_service'];
        const isUsable = !notUsableStates.includes(room.status);

        // Prefer explicit status in booking_info, but some endpoints return `booking_status` at room root.
        const explicitStatus = (room.booking_info as any)?.status ?? (room as any).booking_status;
        const bStatus = explicitStatus?.toString?.().toLowerCase?.();
        if (bStatus) {
            const bs = bStatus;
            if (bs === 'operational') return 'occupied';
            if (bs === 'cleaning') return 'cleaning';
            if (bs === 'confirmed' || bs === 'pending') return 'booked';
            if (bs === 'completed') return 'available';
            if (bs.startsWith('cancel')) return 'cancelled';
            if (bs === 'unsuccessful') return 'available';
            return 'occupied';
        }
        if (room.booking_info) {
            // booking_info exists but no explicit status -> assume occupied
            return 'occupied';
        }

        // No booking: if room is usable, it's available; otherwise keep room's non-usable state
        return isUsable ? 'available' : (room.status || 'out_of_service');
    })();

    const statusConfig = getStatusConfig(effectiveStatus);
    const icons = getStatusIcon(room.status, (room.booking_info as any)?.status ?? room.booking_status);

    // Cleaning remaining minutes (if any)
    const cleaningEndsAt = (room as any).cleaning_ends_at || (room as any).cleaningEndsAt || null;
    const cleaningStartedAt = (room as any).cleaning_started_at || (room as any).cleaningStartsAt || null;
    // derive cleaning flag from multiple possible sources: explicit flag, booking_info.status, or future cleaning_ends_at
    const bookingInfoStatus = (room.booking_info as any)?.status?.toString?.().toLowerCase?.();
    const isCleaningFlag = !!((room as any).is_cleaning) || bookingInfoStatus === 'cleaning' || (cleaningEndsAt ? dayjs(cleaningEndsAt).isAfter(dayjs()) : false);
    const cleaningRemainingMinutes = cleaningEndsAt ? Math.max(0, Math.ceil(dayjs(cleaningEndsAt).diff(dayjs(), 'minute', true))) : 0;
    const cleaningTotalMinutes = (cleaningStartedAt && cleaningEndsAt) ? Math.max(1, Math.ceil(dayjs(cleaningEndsAt).diff(dayjs(cleaningStartedAt), 'minute', true))) : null;
    const cleaningProgress = cleaningTotalMinutes ? Math.min(100, Math.max(0, Math.round((1 - cleaningRemainingMinutes / cleaningTotalMinutes) * 100))) : null;

    // Debug: log cleaning fields so developer can inspect network vs render
    if (process.env.NODE_ENV !== 'production') {
        // eslint-disable-next-line no-console
        console.debug(`[RoomCardGrid] room=${room.id} is_cleaning=${isCleaningFlag} cleaning_ends_at=${cleaningEndsAt}`);
    }

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
                    {/* Put dates and guest name on the same row to avoid overlap */}
                    <div className="flex items-center justify-between">
                        <Text style={{ color: '#fff', fontSize: 12, fontWeight: 500 }}>
                            {dayjs(room.booking_info.check_in).format('DD/MM')} - {dayjs(room.booking_info.check_out).format('DD/MM')}
                        </Text>
                        <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                            {room.booking_info.guest_name && (
                                <div className="flex items-center" style={{ gap: 6 }}>
                                    <UserOutlined style={{ color: '#fff', fontSize: 12 }} />
                                    <Text style={{ color: '#fff', fontSize: 11 }}>
                                        {room.booking_info.guest_count ? `${room.booking_info.guest_count} ` : ''}{room.booking_info.guest_name}
                                    </Text>
                                </div>
                            )}
                        </div>
                    </div>
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

        // Explicitly show maintenance vs out_of_service with clear labels and reasons
        if (room.status === 'maintenance') {
            return (
                <div className="space-y-1">
                    <div className="flex items-center space-x-1">
                        <ToolOutlined style={{ color: '#fff', fontSize: 12 }} />
                        <Text style={{ color: '#fff', fontSize: 11 }}>
                            Bảo trì{room.maintenance_reason ? ` — ${room.maintenance_reason}` : ''}
                        </Text>
                    </div>
                </div>
            );
        }

        if (room.status === 'out_of_service') {
            return (
                <div className="space-y-1">
                    <div className="flex items-center space-x-1">
                        <StopOutlined style={{ color: '#fff', fontSize: 12 }} />
                        <Text style={{ color: '#fff', fontSize: 11 }}>
                            Ngưng hoạt động{room.maintenance_reason ? ` — ${room.maintenance_reason}` : ''}
                        </Text>
                    </div>
                </div>
            );
        }

        // Show bed type and room type for available rooms; bed icon moved to bottom-right
        return (
            <div className="space-y-1">
                {room.room_type?.name && (
                    <div className="flex items-center space-x-1">
                        <HomeOutlined style={{ color: '#fff', fontSize: 12 }} />
                        <Text style={{ color: '#fff', fontSize: 11 }}>
                            {room.room_type.name}
                        </Text>
                    </div>
                )}
                {/* bed_type_name displayed as text; icon rendered separately in bottom-right */}
                {room.bed_type_name && (
                    <div className="flex items-center space-x-1">
                        <Text style={{ color: '#fff', fontSize: 11 }}>
                            {room.bed_type_name}
                        </Text>
                    </div>
                )}
            </div>
        );
    };

    const cardElement = (
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
                {/* If cleaning progress can be computed, show a compact progress bar */}
                {isCleaningFlag && cleaningProgress !== null && (
                    <div style={{ marginTop: 8 }}>
                        <Progress percent={cleaningProgress} size="small" showInfo={false} strokeColor="#13c2c2" />
                    </div>
                )}

                {/* Compact cleaning Tag placed under content (won't overlap room name) */}
                {isCleaningFlag && cleaningEndsAt && (
                    <div style={{ marginTop: 8 }}>
                        <Tag
                            icon={<RestOutlined />}
                            style={{
                                backgroundColor: '#e6fffb',
                                color: '#044d47',
                                borderColor: '#b7ebe6',
                                fontSize: 12,
                                padding: '4px 8px',
                                borderRadius: 8
                            }}
                        >
                            Đang dọn • còn {cleaningRemainingMinutes} phút
                        </Tag>
                    </div>
                )}

                {/* Bed type icon bottom-right: single vs double */}
                <div className="absolute" style={{ bottom: 10, right: 12, display: 'flex', alignItems: 'center', gap: 6 }}>
                    {room.bed_type_name ? (
                        <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
                            {/* simple heuristic: detect words indicating double/twin/king */}
                            {/(double|twin|king|queen|2|2-)/i.test(String(room.bed_type_name)) ? (
                                <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
                                    <LucideUsers size={16} color="#fff" />
                                    <Text style={{ color: '#fff', fontSize: 11 }}>{room.bed_type_name}</Text>
                                </div>
                            ) : (
                                <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
                                    <LucideBed size={16} color="#fff" />
                                    <Text style={{ color: '#fff', fontSize: 11 }}>{room.bed_type_name}</Text>
                                </div>
                            )}
                        </div>
                    ) : null}
                </div>
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
    );

    return (
        <ConfigProvider
            theme={{
                token: {
                    colorPrimary: statusConfig.color,
                },
            }}
        >
            <div className="relative">
                {cardElement}
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
                    if (bs === 'completed') return 'available';
                    if (bs.startsWith('cancel')) return 'cancelled';
                    if (bs === 'unsuccessful') return 'available';
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
        const cancelled = rooms.filter(r => computeEffective(r) === 'cancelled').length;
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
            cancelled,
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
                        {floorStats.cancelled > 0 && (
                            <Tag color="#8c8c8c" style={{ backgroundColor: '#fafafa', borderColor: '#d9d9d9', color: '#595959' }} icon={<CloseCircleOutlined />}>
                                Đã hủy: {floorStats.cancelled}
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
    onModeChange,
    availableRoomIds = null
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
        // Special case: clear all selections when floorId is 'all'
        if (floorId === 'all') {
            onFloorSelect?.('all', [], false);
            return;
        }
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
                                onClick={() => handleFloorSelect('all', [], false)}
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
                    availableRoomIds={availableRoomIds}
                />
            ))}

        </div>
    );
};

// note: visual status config is defined inline in RoomCard; no global helper needed

export default RoomCardGrid;
export type { RoomInfo, RoomCardGridProps };
