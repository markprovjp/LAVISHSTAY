import React, { useState, useEffect, useCallback } from 'react';
import { Layout, Card, Space, Button, Typography, message, Modal } from 'antd';
import {
    ReloadOutlined,
    PlusOutlined,
    SettingOutlined,
    DashboardOutlined
} from '@ant-design/icons';

import RoomStats from './components/RoomStats';
import RoomFilters from './components/RoomFilters';
import RoomGrid from './components/RoomGrid';
import RoomDetail from './components/RoomDetail';

const { Content } = Layout;
const { Title } = Typography;

// Mock data structure - replace with actual API calls
interface Room {
    id: string;
    room_number: string;
    room_type: string;
    floor: string;
    status: 'available' | 'occupied' | 'maintenance' | 'reserved';
    guest_name?: string;
    guest_phone?: string;
    guest_email?: string;
    check_in?: string;
    check_out?: string;
    guest_count?: number;
    booking_code?: string;
    notes?: string;
    amenities?: string[];
    last_cleaned?: string;
    room_area?: number;
    bed_type?: string;
}

const EnhancedRoomManagement: React.FC = () => {
    // State management
    const [rooms, setRooms] = useState<Room[]>([]);
    const [filteredRooms, setFilteredRooms] = useState<Room[]>([]);
    const [loading, setLoading] = useState(false);
    const [selectedRoom, setSelectedRoom] = useState<Room | null>(null);
    const [detailVisible, setDetailVisible] = useState(false);

    // Filter state
    const [filters, setFilters] = useState<{
        status?: string;
        roomType?: string;
        floor?: string;
        search?: string;
        dateRange?: [string, string];
    }>({
        status: undefined,
        roomType: undefined,
        floor: undefined,
        search: undefined,
        dateRange: undefined
    });

    // Stats state
    const [stats, setStats] = useState({
        total: 0,
        available: 0,
        occupied: 0,
        maintenance: 0,
        reserved: 0,
        occupancyRate: 0,
        revenue: 0
    });

    // Room types and floors for filters
    const [roomTypes, setRoomTypes] = useState<string[]>([]);
    const [floors, setFloors] = useState<string[]>([]);

    // Mock data generation
    const generateMockRooms = useCallback((): Room[] => {
        const roomTypes = ['Standard', 'Deluxe', 'Suite', 'Premium'];
        const statuses: Array<'available' | 'occupied' | 'maintenance' | 'reserved'> =
            ['available', 'occupied', 'maintenance', 'reserved'];

        const mockRooms: Room[] = [];

        for (let floor = 1; floor <= 5; floor++) {
            for (let room = 1; room <= 10; room++) {
                const roomNumber = `${floor}${room.toString().padStart(2, '0')}`;
                const status = statuses[Math.floor(Math.random() * statuses.length)];
                const roomType = roomTypes[Math.floor(Math.random() * roomTypes.length)];

                const mockRoom: Room = {
                    id: roomNumber,
                    room_number: roomNumber,
                    room_type: roomType,
                    floor: floor.toString(),
                    status,
                    room_area: 25 + Math.floor(Math.random() * 20),
                    bed_type: ['King', 'Queen', 'Twin'][Math.floor(Math.random() * 3)],
                    amenities: ['WiFi', 'TV', 'AC', 'Minibar', 'Safe'].slice(0, Math.floor(Math.random() * 5) + 1),
                    last_cleaned: new Date(Date.now() - Math.floor(Math.random() * 24 * 60 * 60 * 1000)).toISOString()
                };

                // Add guest info if occupied or reserved
                if (status === 'occupied' || status === 'reserved') {
                    mockRoom.guest_name = `Khách ${Math.floor(Math.random() * 1000)}`;
                    mockRoom.guest_phone = `09${Math.floor(Math.random() * 100000000).toString().padStart(8, '0')}`;
                    mockRoom.guest_email = `guest${Math.floor(Math.random() * 1000)}@email.com`;
                    mockRoom.check_in = new Date(Date.now() - Math.floor(Math.random() * 2 * 24 * 60 * 60 * 1000)).toISOString();
                    mockRoom.check_out = new Date(Date.now() + Math.floor(Math.random() * 5 * 24 * 60 * 60 * 1000)).toISOString();
                    mockRoom.guest_count = Math.floor(Math.random() * 4) + 1;
                    mockRoom.booking_code = `BK${Math.floor(Math.random() * 10000).toString().padStart(4, '0')}`;
                }

                mockRooms.push(mockRoom);
            }
        }

        return mockRooms;
    }, []);

    // Calculate stats from rooms
    const calculateStats = useCallback((roomList: Room[]) => {
        const total = roomList.length;
        const available = roomList.filter(r => r.status === 'available').length;
        const occupied = roomList.filter(r => r.status === 'occupied').length;
        const maintenance = roomList.filter(r => r.status === 'maintenance').length;
        const reserved = roomList.filter(r => r.status === 'reserved').length;
        const occupancyRate = total > 0 ? ((occupied + reserved) / total) * 100 : 0;
        const revenue = occupied * 1500000 + reserved * 800000; // Mock revenue calculation

        return {
            total,
            available,
            occupied,
            maintenance,
            reserved,
            occupancyRate,
            revenue
        };
    }, []);

    // Load rooms data
    const loadRooms = useCallback(async () => {
        setLoading(true);
        try {
            // Simulate API call delay
            await new Promise(resolve => setTimeout(resolve, 1000));

            const mockRooms = generateMockRooms();
            setRooms(mockRooms);
            setFilteredRooms(mockRooms);

            // Extract unique room types and floors
            const uniqueRoomTypes = [...new Set(mockRooms.map(r => r.room_type))];
            const uniqueFloors = [...new Set(mockRooms.map(r => r.floor))];
            setRoomTypes(uniqueRoomTypes);
            setFloors(uniqueFloors.sort());

            // Calculate stats
            const newStats = calculateStats(mockRooms);
            setStats(newStats);

            message.success('Đã tải dữ liệu phòng thành công');
        } catch (error) {
            console.error('Failed to load rooms:', error);
            message.error('Không thể tải dữ liệu phòng');
        } finally {
            setLoading(false);
        }
    }, [generateMockRooms, calculateStats]);

    // Filter rooms based on current filters
    const applyFilters = useCallback(() => {
        let filtered = [...rooms];

        if (filters.status) {
            filtered = filtered.filter(room => room.status === filters.status);
        }

        if (filters.roomType) {
            filtered = filtered.filter(room => room.room_type === filters.roomType);
        }

        if (filters.floor) {
            filtered = filtered.filter(room => room.floor === filters.floor);
        }

        if (filters.search) {
            const searchTerm = filters.search.toLowerCase();
            filtered = filtered.filter(room =>
                room.room_number.toLowerCase().includes(searchTerm) ||
                room.guest_name?.toLowerCase().includes(searchTerm) ||
                room.booking_code?.toLowerCase().includes(searchTerm)
            );
        }

        setFilteredRooms(filtered);

        // Recalculate stats based on filtered data
        const newStats = calculateStats(filtered);
        setStats(newStats);
    }, [rooms, filters, calculateStats]);

    // Handle filter changes
    const handleFiltersChange = useCallback((newFilters: any) => {
        setFilters(newFilters);
    }, []);

    const handleClearFilters = useCallback(() => {
        setFilters({
            status: undefined,
            roomType: undefined,
            floor: undefined,
            search: undefined,
            dateRange: undefined
        });
    }, []);

    // Handle room actions
    const handleViewDetails = useCallback((roomId: string) => {
        const room = rooms.find(r => r.id === roomId);
        if (room) {
            setSelectedRoom(room);
            setDetailVisible(true);
        }
    }, [rooms]);

    const handleStatusChange = useCallback(async (roomId: string, newStatus: string) => {
        try {
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 500));

            setRooms(prevRooms =>
                prevRooms.map(room =>
                    room.id === roomId ? { ...room, status: newStatus as any } : room
                )
            );

            message.success(`Đã cập nhật trạng thái phòng ${roomId}`);
        } catch (error) {
            console.error('Failed to update room status:', error);
            message.error('Không thể cập nhật trạng thái phòng');
        }
    }, []);

    const handleEditRoom = useCallback((roomId: string) => {
        Modal.info({
            title: 'Chỉnh sửa phòng',
            content: `Chức năng chỉnh sửa phòng ${roomId} sẽ được triển khai trong phiên bản tiếp theo.`,
        });
    }, []);

    // Effects
    useEffect(() => {
        loadRooms();
    }, [loadRooms]);

    useEffect(() => {
        applyFilters();
    }, [applyFilters]);

    return (
        <Layout style={{ minHeight: '100vh', background: '#f0f2f5' }}>
            <Content style={{ padding: '24px' }}>
                {/* Header */}
                <Card style={{ marginBottom: 24 }}>
                    <Space size="large" style={{ width: '100%', justifyContent: 'space-between' }}>
                        <Space>
                            <DashboardOutlined style={{ fontSize: '24px', color: '#1890ff' }} />
                            <Title level={2} style={{ margin: 0 }}>
                                Quản lý phòng
                            </Title>
                        </Space>
                        <Space>
                            <Button
                                icon={<ReloadOutlined />}
                                onClick={loadRooms}
                                loading={loading}
                            >
                                Làm mới
                            </Button>
                            <Button
                                type="primary"
                                icon={<PlusOutlined />}
                                onClick={() => message.info('Chức năng thêm phòng sẽ được triển khai')}
                            >
                                Thêm phòng
                            </Button>
                            <Button
                                icon={<SettingOutlined />}
                                onClick={() => message.info('Chức năng cài đặt sẽ được triển khai')}
                            >
                                Cài đặt
                            </Button>
                        </Space>
                    </Space>
                </Card>

                {/* Stats */}
                <RoomStats stats={stats} loading={loading} />

                {/* Filters */}
                <RoomFilters
                    filters={filters}
                    onFiltersChange={handleFiltersChange}
                    onClearFilters={handleClearFilters}
                    roomTypes={roomTypes}
                    floors={floors}
                />

                {/* Room Grid */}
                <Card
                    style={{ marginTop: 24 }}
                    title={`Danh sách phòng (${filteredRooms.length}/${rooms.length})`}
                    bodyStyle={{ padding: 16 }}
                >
                    <RoomGrid
                        rooms={filteredRooms}
                        loading={loading}
                        onViewDetails={handleViewDetails}
                        onStatusChange={handleStatusChange}
                    />
                </Card>

                {/* Room Detail Drawer */}
                <RoomDetail
                    visible={detailVisible}
                    onClose={() => setDetailVisible(false)}
                    room={selectedRoom}
                    onStatusChange={handleStatusChange}
                    onEditRoom={handleEditRoom}
                />
            </Content>
        </Layout>
    );
};

export default EnhancedRoomManagement;
