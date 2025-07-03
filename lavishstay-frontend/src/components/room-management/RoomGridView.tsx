import React from 'react';
import { Row, Col, Typography, Empty, Spin, Tooltip } from 'antd';
import { UserOutlined, CalendarOutlined } from '@ant-design/icons';
import dayjs from 'dayjs';
import {
    Bed,
    DoorOpen,
    UserRound,
    Hammer,
    Clock,
    Ban,
    BadgeCheck
} from "lucide-react";

const { Title } = Typography;

// Định nghĩa màu sắc theo yêu cầu
const statusColorMap = {
    occupied: 'bg-red-500',      // 🔴 Phòng đang có khách
    available: 'bg-green-500',   // 🟢 Phòng trống
    cleaning: 'bg-orange-500',   // 🟠 Nghỉ giờ
    deposited: 'bg-blue-500',    // 🔵 Khách đi cọc tiền
    no_show: 'bg-purple-500',    // 🟣 No show
    maintenance: 'bg-gray-400',  // ⚪ Phòng đang sửa
    check_in: 'bg-blue-500',     // 🔵 Phòng đón khách
    check_out: 'bg-orange-500',  // 🟠 Phòng trả
};

const statusIconMap = {
    occupied: Bed,
    available: DoorOpen,
    cleaning: Clock,
    deposited: BadgeCheck,
    no_show: Ban,
    maintenance: Hammer,
    check_in: Bed,
    check_out: DoorOpen,
};
useGetReceptionRooms({ include: 'room_type' })


interface RoomGridViewProps {
    rooms: any[];
    loading?: boolean;
    onRoomClick?: (room: any) => void;
}

const RoomGridView: React.FC<RoomGridViewProps> = ({
    rooms,
    loading = false,
    onRoomClick
}) => {
    if (loading) {
        return (
            <div className="flex justify-center items-center h-64">
                <Spin size="large" />
            </div>
        );
    }

    if (rooms.length === 0) {
        return (
            <Empty
                description="Không có phòng nào"
                image={Empty.PRESENTED_IMAGE_SIMPLE}
            />
        );
    }

    // Group rooms by floor - extract floor from room name or use a default
    const roomsByFloor = rooms.reduce((acc: any, room: any) => {
        const floor = room.floor || Math.floor(parseInt(room.name) / 100) || 1;
        if (!acc[floor]) {
            acc[floor] = [];
        }
        acc[floor].push(room);
        return acc;
    }, {});

    const formatDate = (date: string) => {
        return dayjs(date).format('DD/MM');
    };

    const formatDateRange = (checkIn: string, checkOut: string) => {
        return `${formatDate(checkIn)} - ${formatDate(checkOut)}`;
    };
    const RoomCard: React.FC<{ room: any }> = ({ room }) => {
        const statusColor = statusColorMap[room.status as keyof typeof statusColorMap] || 'bg-gray-400';
        const statusIcon = statusIconMap[room.status as keyof typeof statusIconMap] || '⚪';

        // Tạo mã phòng ngắn từ loại phòng
        const roomCode = room.room_type?.name ? room.room_type.name.substring(0, 2).toUpperCase() : 'RM';
        const IconComponent = statusIconMap[room.status as keyof typeof statusIconMap];

        return (
            <Tooltip
                title={
                    <div className="space-y-2">
                        <div><strong>Phòng:</strong> {room.name}</div>
                        <div><strong>Loại:</strong> {room.room_type?.name || 'N/A'}</div>
                        <div><strong>Trạng thái:</strong> {room.status || 'N/A'}</div>
                        <div><strong>Diện tích:</strong> {room.room_type?.room_area || 'N/A'} m²</div>
                        <div><strong>Sức chứa:</strong> {room.room_type?.max_guests || 'N/A'} khách</div>
                        {room.guestName && (
                            <>
                                <div><strong>Khách:</strong> {room.guestName}</div>
                                <div><strong>Số người:</strong> {room.guestCount}</div>
                                {room.checkInDate && room.checkOutDate && (
                                    <div><strong>Thời gian:</strong> {formatDateRange(room.checkInDate, room.checkOutDate)}</div>
                                )}
                            </>
                        )}
                    </div>
                }
                placement="top"
            >
                <div
                    className={`
                        ${statusColor} 
                        rounded-xl 
                        shadow-md 
                        hover:shadow-lg 
                        hover:scale-105 
                        transition-all 
                        duration-200 
                        cursor-pointer 
                        p-4 
                        text-white 
                        min-h-[120px]
                        flex 
                        flex-col 
                        justify-between
                    `}
                    onClick={() => onRoomClick?.(room)}
                >
                    {/* Header: Icon và Số phòng */}
                    <div className="flex justify-between items-start mb-2">
                        <div className="text-lg text-white/90">
                            {IconComponent && <IconComponent size={20} />}
                        </div>
                        <div className="text-right">
                            <div className="text-white font-semibold text-lg">
                                {room.name}
                            </div>
                            <div className="text-white/80 text-xs">
                                {roomCode}
                            </div>
                        </div>
                    </div>

                    {/* Giữa: Ngày check-in đến check-out */}
                    <div className="text-center mb-2">
                        {room.checkInDate && room.checkOutDate ? (
                            <div className="text-white/90 text-sm">
                                <CalendarOutlined className="mr-1" />
                                {formatDateRange(room.checkInDate, room.checkOutDate)}
                            </div>
                        ) : (
                            <div className="text-white/70 text-sm">
                                {room.status === 'available' ? 'Sẵn sàng' :
                                    room.status === 'maintenance' ? 'Bảo trì' :
                                        room.status === 'cleaning' ? 'Dọn dẹp' : 'Trống'}
                            </div>
                        )}
                    </div>

                    {/* Footer: Thông tin khách */}
                    <div className="flex items-center justify-between">
                        <div className="flex items-center space-x-1">
                            <UserOutlined className="text-white/80 text-xs" />
                            <span className="text-white/90 text-xs">
                                {room.guestCount || 0}
                            </span>
                        </div>
                        {room.guestName && (
                            <div className="text-white/90 text-xs truncate max-w-[80px]">
                                {room.guestName}
                            </div>
                        )}
                    </div>
                </div>
            </Tooltip>
        );
    };

    return (
        <div className="space-y-8">
            {Object.entries(roomsByFloor)
                .sort(([a], [b]) => Number(a) - Number(b))
                .map(([floor, floorRooms]) => (
                    <div key={floor} className="space-y-4">
                        <div className="flex items-center justify-between">
                            <Title level={3} className="mb-0 text-gray-700">
                                🏢 Tầng {floor}
                            </Title>
                            <div className="text-sm text-gray-500">
                                {(floorRooms as any[]).length} phòng
                            </div>
                        </div>
                        <Row gutter={[16, 16]}>
                            {(floorRooms as any[]).map((room: any) => (
                                <Col key={room.id} xs={12} sm={8} md={6} lg={4} xl={3}>
                                    <RoomCard room={room} />
                                </Col>
                            ))}
                        </Row>
                    </div>
                ))}
        </div>
    );
};

export default RoomGridView;
