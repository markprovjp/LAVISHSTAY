import React from 'react';
import { Card, Space, Tag, Button, Typography, Badge, Tooltip } from 'antd';
import {
    EyeOutlined,
    UserOutlined,
    CalendarOutlined,
    CheckCircleOutlined,
    CloseCircleOutlined,
    ClockCircleOutlined
} from '@ant-design/icons';
import dayjs from 'dayjs';

const { Text } = Typography;

interface RoomCardProps {
    room: {
        id: string;
        room_number: string;
        room_type: string;
        status: 'available' | 'occupied' | 'maintenance' | 'reserved';
        guest_name?: string;
        check_in?: string;
        check_out?: string;
        guest_count?: number;
        booking_code?: string;
    };
    onViewDetails: (roomId: string) => void;
    onStatusChange: (roomId: string, newStatus: string) => void;
}

const RoomCard: React.FC<RoomCardProps> = ({ room, onViewDetails, onStatusChange }) => {
    const getStatusColor = (status: string) => {
        const colors = {
            available: 'green',
            occupied: 'blue',
            maintenance: 'orange',
            reserved: 'purple'
        };
        return colors[status as keyof typeof colors] || 'default';
    };

    const getStatusText = (status: string) => {
        const texts = {
            available: 'Trống',
            occupied: 'Có khách',
            maintenance: 'Bảo trì',
            reserved: 'Đã đặt'
        };
        return texts[status as keyof typeof texts] || status;
    };

    const getStatusIcon = (status: string) => {
        switch (status) {
            case 'available': return <CheckCircleOutlined />;
            case 'occupied': return <UserOutlined />;
            case 'maintenance': return <CloseCircleOutlined />;
            case 'reserved': return <ClockCircleOutlined />;
            default: return null;
        }
    };

    return (
        <Card
            hoverable
            size="small"
            title={
                <Space>
                    <Text strong>{room.room_number}</Text>
                    <Tag color={getStatusColor(room.status)} icon={getStatusIcon(room.status)}>
                        {getStatusText(room.status)}
                    </Tag>
                </Space>
            }
            extra={
                <Button
                    type="text"
                    size="small"
                    icon={<EyeOutlined />}
                    onClick={() => onViewDetails(room.id)}
                />
            }
            style={{
                marginBottom: 16,
                border: room.status === 'available' ? '2px solid #52c41a' : undefined
            }}
        >
            <Space direction="vertical" size={4} style={{ width: '100%' }}>
                <Text type="secondary">{room.room_type}</Text>

                {room.guest_name && (
                    <Space>
                        <UserOutlined />
                        <Text>{room.guest_name}</Text>
                    </Space>
                )}

                {room.check_in && (
                    <Space>
                        <CalendarOutlined />
                        <Text type="secondary">
                            {dayjs(room.check_in).format('MM/DD')} - {dayjs(room.check_out).format('MM/DD')}
                        </Text>
                    </Space>
                )}

                {room.guest_count && (
                    <Badge count={`${room.guest_count} khách`} color="blue" />
                )}

                {room.booking_code && (
                    <Tooltip title="Mã booking">
                        <Tag>{room.booking_code}</Tag>
                    </Tooltip>
                )}
            </Space>
        </Card>
    );
};

export default RoomCard;
