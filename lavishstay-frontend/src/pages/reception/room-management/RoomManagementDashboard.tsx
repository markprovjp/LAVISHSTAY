import React, { useState } from 'react';
import { Layout, message, Modal, Descriptions, Tag } from 'antd';
import { useRoomManagementStore } from '../../../stores/roomManagementStore';
import { RoomFilters, FullCalendarEvent } from '../../../types/room';
import FilterBar from '../../../components/room-management/FilterBar';
import RoomGridView from '../../../components/room-management/RoomGridView';
import RoomTimelineView from '../../../components/room-management/RoomTimelineView';
import { useGetReceptionRooms, useGetReceptionRoomTypes } from '../../../hooks/useReception';
import dayjs from 'dayjs';
import 'dayjs/locale/vi';

dayjs.locale('vi');

const { Content } = Layout;

const RoomManagementDashboard: React.FC = () => {
    const { viewMode } = useRoomManagementStore();
    const [selectedRoom, setSelectedRoom] = useState<any>(null);
    const [roomDetailVisible, setRoomDetailVisible] = useState(false);
    const [currentFilters, setCurrentFilters] = useState<RoomFilters>({});

    // Fetch room types using real API
    const { data: roomTypesData } = useGetReceptionRoomTypes();
    const roomTypes = roomTypesData?.data || [];

    // Fetch rooms using real API with filters
    const { data: roomsData, isLoading } = useGetReceptionRooms({
        ...currentFilters,
        include: 'room_type'
    });
    const rooms = roomsData?.data || [];

    const handleSearch = (searchFilters: RoomFilters) => {
        setCurrentFilters(searchFilters);
    };

    const handleRoomClick = (room: any) => {
        setSelectedRoom(room);
        setRoomDetailVisible(true);
    };

    const handleEventClick = (event: FullCalendarEvent) => {
        const room = rooms.find((r: any) => r.id === event.resourceId);
        if (room) {
            handleRoomClick(room);
        }
    };

    const handleDateSelect = (start: string, end: string) => {
        message.info(`Đã chọn từ ${dayjs(start).format('DD/MM/YYYY')} đến ${dayjs(end).format('DD/MM/YYYY')}`);
    };

    const handleCloseModal = () => {
        setRoomDetailVisible(false);
        setSelectedRoom(null);
    };

    const renderRoomDetail = () => {
        if (!selectedRoom) return null;

        const roomType = selectedRoom.room_type || {};
        const statusColor =
            selectedRoom.status === 'occupied' ? 'red' :
                selectedRoom.status === 'available' ? 'green' :
                    selectedRoom.status === 'cleaning' ? 'orange' :
                        selectedRoom.status === 'maintenance' ? 'gray' : 'blue';

        return (
            <Descriptions column={2} bordered size="small">
                <Descriptions.Item label="Số phòng">
                    {selectedRoom.name || selectedRoom.number || 'N/A'}
                </Descriptions.Item>
                <Descriptions.Item label="Tầng">
                    Tầng {selectedRoom.floor || Math.floor(parseInt(selectedRoom.name || '0') / 100) || 'N/A'}
                </Descriptions.Item>
                <Descriptions.Item label="Loại phòng">
                    {roomType.name || selectedRoom.roomType || 'N/A'}
                </Descriptions.Item>
                <Descriptions.Item label="Giá phòng">
                    {roomType.base_price ?
                        `${new Intl.NumberFormat('vi-VN').format(roomType.base_price)} VNĐ/đêm` :
                        'N/A'
                    }
                </Descriptions.Item>
                <Descriptions.Item label="Trạng thái">
                    <Tag color={statusColor}>
                        {selectedRoom.status === 'available' ? 'Có thể thuê' :
                            selectedRoom.status === 'occupied' ? 'Đang có khách' :
                                selectedRoom.status === 'cleaning' ? 'Đang dọn dẹp' :
                                    selectedRoom.status === 'maintenance' ? 'Bảo trì' :
                                        selectedRoom.status || 'N/A'}
                    </Tag>
                </Descriptions.Item>
                <Descriptions.Item label="ID phòng">
                    {selectedRoom.id}
                </Descriptions.Item>
                <Descriptions.Item label="Sức chứa" span={2}>
                    {roomType.max_guests ? `${roomType.max_guests} khách` : 'N/A'}
                </Descriptions.Item>
                <Descriptions.Item label="Diện tích">
                    {roomType.room_area ? `${roomType.room_area}m²` : 'N/A'}
                </Descriptions.Item>
                <Descriptions.Item label="Mô tả">
                    {roomType.description || 'N/A'}
                </Descriptions.Item>

                {selectedRoom.guestName && (
                    <>
                        <Descriptions.Item label="Tên khách" span={2}>
                            {selectedRoom.guestName}
                        </Descriptions.Item>
                        <Descriptions.Item label="Số người">
                            {selectedRoom.guestCount}
                        </Descriptions.Item>
                        <Descriptions.Item label="Ngày nhận phòng">
                            {selectedRoom.checkInDate ? dayjs(selectedRoom.checkInDate).format('DD/MM/YYYY') : '-'}
                        </Descriptions.Item>
                        <Descriptions.Item label="Ngày trả phòng" span={2}>
                            {selectedRoom.checkOutDate ? dayjs(selectedRoom.checkOutDate).format('DD/MM/YYYY') : '-'}
                        </Descriptions.Item>
                    </>
                )}
            </Descriptions>
        );
    };

    return (
        <Layout className=" ">
            <Content className="p-8">
                <div className="">
                    <div className="mb-8">
                        <h1 className="text-3xl font-bold text-gray-900 mb-2">
                             Quản lý phòng khách sạn
                        </h1>
                        <p className="text-gray-600 text-lg">
                            Quản lý trạng thái phòng, đặt phòng và lịch sử khách hàng
                        </p>
                    </div>

                    <FilterBar
                        roomTypes={roomTypes}
                        onSearch={handleSearch}
                        loading={isLoading}
                    />

                    <div className="bg-white rounded-2xl shadow-sm p-8 mt-6">
                        {viewMode === 'grid' ? (
                            <RoomGridView
                                rooms={rooms}
                                loading={isLoading}
                                onRoomClick={handleRoomClick}
                            />
                        ) : (
                            <RoomTimelineView
                                rooms={rooms}
                                loading={isLoading}
                                onEventClick={handleEventClick}
                                onDateSelect={handleDateSelect}
                            />
                        )}
                    </div>

                    {/* Room Detail Modal */}
                    <Modal
                        title={
                            <div className="flex items-center space-x-2">
                                <span>Chi tiết phòng</span>
                                {selectedRoom && (
                                    <Tag color="blue">{selectedRoom.name || selectedRoom.number || selectedRoom.id}</Tag>
                                )}
                            </div>
                        }
                        open={roomDetailVisible}
                        onCancel={handleCloseModal}
                        footer={null}
                        width={700}
                    >
                        {renderRoomDetail()}
                    </Modal>
                </div>
            </Content>
        </Layout>
    );
};

export default RoomManagementDashboard;
