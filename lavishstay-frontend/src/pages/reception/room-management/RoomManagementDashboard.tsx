import React, { useState, useMemo } from 'react';
import { Layout, message, Modal, Descriptions, Tag, Button, Space } from 'antd';
import { UnorderedListOutlined } from '@ant-design/icons';
import { useNavigate } from 'react-router-dom';
import { useRoomManagementStore } from '../../../stores/roomManagementStore';
import { RoomFilters } from '../../../types/room';
import FilterBar from '../../../components/room-management/FilterBar';
import RoomGridView from '../../../components/room-management/RoomGridView';
import RoomTimelineView from '../../../components/room-management/RoomTimelineView';
import { useGetReceptionRooms, useGetReceptionRoomTypes, useGetAvailableRooms } from '../../../hooks/useReception';
import { statusOptions } from '../../../constants/roomStatus';
import dayjs from 'dayjs';
import 'dayjs/locale/vi';

dayjs.locale('vi');

const { Content } = Layout;

const RoomManagementDashboard: React.FC = () => {
    const navigate = useNavigate();
    const { viewMode } = useRoomManagementStore();
    const [selectedRoom, setSelectedRoom] = useState<any>(null);
    const [roomDetailVisible, setRoomDetailVisible] = useState(false);
    const [currentFilters, setCurrentFilters] = useState<RoomFilters>({});
    const [multiSelectMode, setMultiSelectMode] = useState(false);
    const [selectedRoomIds, setSelectedRoomIds] = useState<Set<string>>(new Set());
    const [guestCount, setGuestCount] = useState(2);

    // --- API HOOKS ---
    const { data: roomTypesData } = useGetReceptionRoomTypes();
    const roomTypes = roomTypesData?.data || [];

    const { data: allRoomsData, isLoading: isLoadingRooms } = useGetReceptionRooms({ include: 'room_type' });
    const masterRoomList = useMemo(() => allRoomsData?.data || [], [allRoomsData]);

    const hasDateRange = currentFilters.dateRange && currentFilters.dateRange.length === 2;
    const { data: availableRoomsData, isLoading: isLoadingAvailable } = useGetAvailableRooms(
        hasDateRange ? {
            check_in_date: currentFilters.dateRange![0],
            check_out_date: currentFilters.dateRange![1],
        } : undefined
    );

    const availableRoomIdSet = useMemo(() => {
        if (!hasDateRange) return null;
        const ids = new Set<string>();
        (availableRoomsData?.data || []).forEach((roomType: any) => {
            roomType.available_rooms?.forEach((room: any) => ids.add(room.room_id));
        });
        return ids;
    }, [hasDateRange, availableRoomsData]);

    const filteredRoomsToDisplay = useMemo(() => {
        return masterRoomList.filter((room: any) => {
            if (availableRoomIdSet && !availableRoomIdSet.has(room.id)) {
                return false;
            }
            if (currentFilters.roomType && String(room.room_type?.id) !== String(currentFilters.roomType)) {
                return false;
            }
            return true;
        });
    }, [masterRoomList, availableRoomIdSet, currentFilters.roomType]);

    const isLoading = isLoadingRooms || (hasDateRange && isLoadingAvailable);

    const handleSearch = (searchFilters: RoomFilters) => {
        setCurrentFilters(searchFilters);
    };

    const handleRoomClick = (room: any) => {
        setSelectedRoom(room);
        setRoomDetailVisible(true);
    };

    const handleMultiSelectModeChange = (enabled: boolean) => {
        setMultiSelectMode(enabled);
        if (!enabled) {
            setSelectedRoomIds(new Set());
        }
    };

    const handleRoomSelect = (roomId: string, selected: boolean) => {
        setSelectedRoomIds(prev => {
            const newSet = new Set(prev);
            if (selected) newSet.add(roomId);
            else newSet.delete(roomId);
            return newSet;
        });
    };

    const handleBulkRoomSelect = (roomIds: string[], select: boolean) => {
        setSelectedRoomIds(prev => {
            const newSet = new Set(prev);
            roomIds.forEach(id => {
                if (select) newSet.add(id);
                else newSet.delete(id);
            });
            return newSet;
        });
    };

    const handleProceedToBooking = () => {
        if (selectedRoomIds.size === 0) {
            message.warning('Vui lòng chọn ít nhất một phòng');
            return;
        }
        const selectedRoomsList = masterRoomList.filter((room: any) => selectedRoomIds.has(room.id));

        navigate('/reception/confirm-representative-payment', {
            state: {
                selectedRooms: selectedRoomsList,
                guestCount,
                checkInDate: hasDateRange ? currentFilters.dateRange![0] : undefined,
                checkOutDate: hasDateRange ? currentFilters.dateRange![1] : undefined,
                bookingData: hasDateRange ? {
                    checkInDate: currentFilters.dateRange![0],
                    checkOutDate: currentFilters.dateRange![1],
                    adults: 1,
                    children: []
                } : undefined
            }
        });
    };

    const handleCloseModal = () => {
        setRoomDetailVisible(false);
        setSelectedRoom(null);
    };

    const renderRoomDetail = () => {
        if (!selectedRoom) return null;
        const roomType = selectedRoom.room_type || {};
        return (
            <Descriptions column={2} bordered size="small">
                <Descriptions.Item label="Số phòng">{selectedRoom.name || 'N/A'}</Descriptions.Item>
                <Descriptions.Item label="Tầng">Tầng {selectedRoom.floor || 'N/A'}</Descriptions.Item>
                <Descriptions.Item label="Loại phòng">{roomType.name || 'N/A'}</Descriptions.Item>
                <Descriptions.Item label="Giá phòng">{roomType.base_price ? `${new Intl.NumberFormat('vi-VN').format(roomType.base_price)} VNĐ/đêm` : 'N/A'}</Descriptions.Item>
                <Descriptions.Item label="Trạng thái"><Tag color={statusOptions.find(s => s.value === selectedRoom.status)?.color || 'default'}>{statusOptions.find(s => s.value === selectedRoom.status)?.label || selectedRoom.status}</Tag></Descriptions.Item>
                <Descriptions.Item label="ID phòng">{selectedRoom.id}</Descriptions.Item>
            </Descriptions>
        );
    };

    return (
        <Layout className="50">
            <Content className="p-6">
                <div className="">
                    <div className="mb-6 flex justify-between items-start">
                        <div>
                            <h1 className="text-2xl font-bold mb-2">Quản lý phòng khách sạn</h1>
                            <p className="">Quản lý trạng thái phòng, đặt phòng và lịch sử khách hàng</p>
                        </div>
                        <Space>
                            <Button type="primary" icon={<UnorderedListOutlined />} onClick={() => navigate('/reception/booking-management')}>Quản lý đặt phòng</Button>
                        </Space>
                    </div>

                    <FilterBar
                        roomTypes={roomTypes}
                        onSearch={handleSearch}
                        loading={isLoading}
                        selectedRoomsCount={selectedRoomIds.size}
                        onMultiSelectModeChange={handleMultiSelectModeChange}
                        multiSelectMode={multiSelectMode}
                    />

                    <div className="rounded-lg shadow-sm p-6">
                        {viewMode === 'grid' ? (
                            <RoomGridView
                                rooms={filteredRoomsToDisplay}
                                allRooms={masterRoomList} // Pass the master list here
                                loading={isLoading}
                                onRoomClick={handleRoomClick}
                                multiSelectMode={multiSelectMode}
                                selectedRooms={selectedRoomIds}
                                onRoomSelect={handleRoomSelect}
                                onBulkRoomSelect={handleBulkRoomSelect}
                                navigate={navigate}
                                hasDateFilter={hasDateRange}
                                checkInDate={hasDateRange ? currentFilters.dateRange![0] : undefined}
                                checkOutDate={hasDateRange ? currentFilters.dateRange![1] : undefined}
                            />
                        ) : (
                            <RoomTimelineView
                                rooms={filteredRoomsToDisplay}
                                loading={isLoading}
                                onEventClick={() => {}}
                                onDateSelect={() => {}}
                            />
                        )}
                    </div>

                    <Modal title="Chi tiết phòng" open={roomDetailVisible} onCancel={handleCloseModal} footer={null} width={800}>
                        {renderRoomDetail()}
                    </Modal>
                </div>
            </Content>
        </Layout>
    );
};

export default RoomManagementDashboard;
