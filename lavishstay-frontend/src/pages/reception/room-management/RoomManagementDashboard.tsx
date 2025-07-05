import React, { useState } from 'react';
import { Layout, message, Modal, Descriptions, Tag, Button, Space } from 'antd';
import { UnorderedListOutlined } from '@ant-design/icons';
import { useNavigate } from 'react-router-dom';
import { useRoomManagementStore } from '../../../stores/roomManagementStore';
import { RoomFilters, FullCalendarEvent } from '../../../types/room';
import FilterBar from '../../../components/room-management/FilterBar';
import RoomGridView from '../../../components/room-management/RoomGridView';
import RoomTimelineView from '../../../components/room-management/RoomTimelineView';
import { useGetReceptionRooms, useGetReceptionRoomTypes } from '../../../hooks/useReception';
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
    const [selectedRooms, setSelectedRooms] = useState<Set<string>>(new Set());
    const [guestCount, setGuestCount] = useState(2);

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

    const handleMultiSelectModeChange = (enabled: boolean) => {
        setMultiSelectMode(enabled);
        if (!enabled) {
            setSelectedRooms(new Set());
        }
    };

    const handleRoomSelect = (roomId: string, selected: boolean) => {
        const newSelected = new Set(selectedRooms);
        if (selected) {
            newSelected.add(roomId);
        } else {
            newSelected.delete(roomId);
        }
        setSelectedRooms(newSelected);
    };

    const handleProceedToBooking = () => {
        if (selectedRooms.size === 0) {
            message.warning('Vui lòng chọn ít nhất một phòng');
            return;
        }

        const selectedRoomsList = rooms.filter((room: any) => selectedRooms.has(room.id));

        // Chuyển sang trang confirm với dữ liệu phòng đã chọn
        navigate('/reception/confirm-representative-payment', {
            state: {
                selectedRooms: selectedRoomsList,
                guestCount
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
                    <Tag color={statusOptions.find(s => s.value === selectedRoom.status)?.color || 'default'}>
                        {statusOptions.find(s => s.value === selectedRoom.status)?.label || selectedRoom.status}
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
        <Layout className="50">
            <Content className="p-6">
                <div className="">
                    <div className="mb-6 flex justify-between items-start">
                        <div>
                            <h1 className="text-2xl font-bold  mb-2">
                                Quản lý phòng khách sạn
                            </h1>
                            <p className="">
                                Quản lý trạng thái phòng, đặt phòng và lịch sử khách hàng
                            </p>
                        </div>
                        <Space>
                            <Button
                                type="primary"
                                icon={<UnorderedListOutlined />}
                                onClick={() => navigate('/reception/booking-management')}
                            >
                                Quản lý đặt phòng
                            </Button>
                        </Space>
                    </div>

                    <FilterBar
                        roomTypes={roomTypes}
                        onSearch={handleSearch}
                        loading={isLoading}
                        selectedRoomsCount={selectedRooms.size}
                        onMultiSelectModeChange={handleMultiSelectModeChange}
                        multiSelectMode={multiSelectMode}
                    />

                    <div className=" rounded-lg shadow-sm p-6">
                        {viewMode === 'grid' ? (
                            <RoomGridView
                                rooms={rooms}
                                loading={isLoading}
                                onRoomClick={handleRoomClick}
                                multiSelectMode={multiSelectMode}
                                selectedRooms={selectedRooms}
                                onRoomSelect={handleRoomSelect}
                                onProceedToBooking={handleProceedToBooking}
                                guestCount={guestCount}
                                onGuestCountChange={setGuestCount}
                                navigate={navigate}
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

                    <Modal
                        title="Chi tiết phòng"
                        open={roomDetailVisible}
                        onCancel={handleCloseModal}
                        footer={null}
                        width={800}
                    >
                        {renderRoomDetail()}
                    </Modal>
                </div>
            </Content>
        </Layout>
    );
};

export default RoomManagementDashboard;
