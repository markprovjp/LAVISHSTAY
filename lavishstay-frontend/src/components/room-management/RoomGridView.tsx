import React from 'react';
import { Card, Row, Col, Typography, Empty, Spin, Tooltip, Tag , Divider } from 'antd';
import { UserOutlined, CalendarOutlined, HomeOutlined  } from '@ant-design/icons';
import { statusColorMap, statusOptions } from '../../constants/roomStatus';
import { Plane, Cake, Bed } from 'lucide-react';
import dayjs from 'dayjs';

const { Text, Title } = Typography;

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
        const isOccupied = room.status === 'occupied';
        const statusColor = statusColorMap[room.status as keyof typeof statusColorMap] || 'bg-gray-400';

        return (
            <Tooltip
                title={
                    <div className="space-y-2">
                        <div><strong>Phòng:</strong> {room.name}</div>
                        <div><strong>Loại:</strong> {room.room_type?.name || 'N/A'}</div>
                        <div><strong>Trạng thái:</strong> {statusOptions.find(s => s.value === room.status)?.label || room.status}</div>
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
                <Card
                    size="small"
                    className={`cursor-pointer transition-all duration-200 hover:shadow-md hover:scale-105 ${statusColor} text-white `}
                    onClick={() => onRoomClick?.(room)}
                    bodyStyle={{ padding: '12px'  }}
                >
                    <div className="space-y-2">
                        {/* Room number and code */}
                        <div className="flex justify-between items-start">
                            <div className="flex items-center space-x-2">
                                <HomeOutlined className="text-white" />
                                <Text className="text-white font-semibold text-sm">
                                    {room.name}
                                </Text>
                                {room.room_type?.name && (
                                    <Tag color="rgba(255,255,255,0.2)" className="text-white border-white text-xs">
                                        {room.room_type.name.substring(0, 2).toUpperCase()}
                                    </Tag>
                                )}
                            </div>
                            <div className="opacity-75 text-white text-xs">
                                 {statusOptions.find(s => s.value === room.status)?.label || room.status}
                            </div>
                        </div>

                        {/* Guest information */}
                        {isOccupied && room.guestName && (
                            <div className="space-y-1">
                                <div className="flex items-center space-x-1">
                                    <UserOutlined className="text-white text-xs" />
                                    <Text className="text-white text-xs truncate max-w-[120px]">
                                        {room.guestName}
                                    </Text>
                                </div>

                                {room.guestCount && (
                                    <div className="text-white text-xs">
                                        {room.guestCount} người
                                    </div>
                                )}

                                {room.checkInDate && room.checkOutDate && (
                                    <div className="flex items-center space-x-1">
                                        <CalendarOutlined className="text-white text-xs" />
                                        <Text className="text-white text-xs">
                                            {formatDateRange(room.checkInDate, room.checkOutDate)}
                                        </Text>
                                    </div>
                                )}
                            </div>
                        )}

                        {/* Room type */}
                        <div className="text-white text-xs opacity-75 truncate">
                            {room.room_type?.name || 'N/A'}
                        </div>
                    </div>
                </Card>
            </Tooltip>
        );
    };

    return (
        <div className="space-y-6">
            {Object.entries(roomsByFloor)
                .sort(([a], [b]) => Number(a) - Number(b))
                .map(([floor, floorRooms]) => (
                    <div key={floor} className="space-y-5">
                        <Divider orientation="left" plain>
                        <Title level={4} className="mb-3 text-gray-700">
                            Tầng {floor}
                        </Title>
                        </Divider>
                        <Row gutter={[12, 12]}>
                            {(floorRooms as any[]).map((room: any) => (
                                <Col key={room.id} xs={12} sm={8} md={6} lg={6} xl={4}>
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
