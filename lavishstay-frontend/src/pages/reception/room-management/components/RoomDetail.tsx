import React from 'react';
import { Drawer, Descriptions, Tag, Space, Button, Typography, Badge, Divider } from 'antd';
import {
    InfoCircleOutlined,
    UserOutlined,
    CalendarOutlined,
    PhoneOutlined,
    MailOutlined,
    HomeOutlined,
    CheckCircleOutlined,
    EditOutlined,
    ToolOutlined
} from '@ant-design/icons';
import dayjs from 'dayjs';

const { Title, Text } = Typography;
const { Item } = Descriptions;

interface RoomDetailProps {
    visible: boolean;
    onClose: () => void;
    room: {
        id: string;
        room_number: string;
        room_type: string;
        floor: string;
        status: string;
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
    } | null;
    onStatusChange: (roomId: string, newStatus: string) => void;
    onEditRoom: (roomId: string) => void;
}

const RoomDetail: React.FC<RoomDetailProps> = ({
    visible,
    onClose,
    room,
    onStatusChange,
    onEditRoom
}) => {
    if (!room) return null;

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

    return (
        <Drawer
            title={
                <Space>
                    <InfoCircleOutlined />
                    Chi tiết phòng {room.room_number}
                </Space>
            }
            width={720}
            open={visible}
            onClose={onClose}
            extra={
                <Space>
                    <Button
                        icon={<EditOutlined />}
                        onClick={() => onEditRoom(room.id)}
                    >
                        Chỉnh sửa
                    </Button>
                    <Button
                        icon={<ToolOutlined />}
                        onClick={() => onStatusChange(room.id, 'maintenance')}
                        disabled={room.status === 'maintenance'}
                    >
                        Bảo trì
                    </Button>
                </Space>
            }
        >
            {/* Basic Info */}
            <Descriptions
                title="Thông tin phòng"
                bordered
                column={1}
                size="middle"
            >
                <Item label="Số phòng">{room.room_number}</Item>
                <Item label="Loại phòng">{room.room_type}</Item>
                <Item label="Tầng">Tầng {room.floor}</Item>
                <Item label="Trạng thái">
                    <Tag color={getStatusColor(room.status)}>
                        {getStatusText(room.status)}
                    </Tag>
                </Item>
                <Item label="Diện tích">{room.room_area ? `${room.room_area} m²` : 'Chưa có thông tin'}</Item>
                <Item label="Loại giường">{room.bed_type || 'Chưa có thông tin'}</Item>
                <Item label="Lần cuối dọn phòng">
                    {room.last_cleaned ? dayjs(room.last_cleaned).format('YYYY-MM-DD HH:mm') : 'Chưa có thông tin'}
                </Item>
            </Descriptions>

            {/* Guest Info - Only show if occupied or reserved */}
            {(room.status === 'occupied' || room.status === 'reserved') && room.guest_name && (
                <>
                    <Divider />
                    <Title level={4}>
                        <UserOutlined /> Thông tin khách
                    </Title>
                    <Descriptions bordered column={1} size="middle">
                        <Item label="Tên khách">{room.guest_name}</Item>
                        <Item label="Số điện thoại">
                            <Space>
                                <PhoneOutlined />
                                {room.guest_phone || 'Chưa có thông tin'}
                            </Space>
                        </Item>
                        <Item label="Email">
                            <Space>
                                <MailOutlined />
                                {room.guest_email || 'Chưa có thông tin'}
                            </Space>
                        </Item>
                        <Item label="Mã booking">
                            <Tag color="blue">{room.booking_code}</Tag>
                        </Item>
                        <Item label="Ngày nhận phòng">
                            <Space>
                                <CalendarOutlined />
                                {room.check_in ? dayjs(room.check_in).format('YYYY-MM-DD HH:mm') : 'Chưa có thông tin'}
                            </Space>
                        </Item>
                        <Item label="Ngày trả phòng">
                            <Space>
                                <CalendarOutlined />
                                {room.check_out ? dayjs(room.check_out).format('YYYY-MM-DD HH:mm') : 'Chưa có thông tin'}
                            </Space>
                        </Item>
                        <Item label="Số khách">
                            <Badge count={room.guest_count || 0} color="blue" />
                        </Item>
                    </Descriptions>
                </>
            )}

            {/* Amenities */}
            {room.amenities && room.amenities.length > 0 && (
                <>
                    <Divider />
                    <Title level={4}>
                        <HomeOutlined /> Tiện nghi
                    </Title>
                    <Space wrap>
                        {room.amenities.map((amenity, index) => (
                            <Tag key={index} icon={<CheckCircleOutlined />}>
                                {amenity}
                            </Tag>
                        ))}
                    </Space>
                </>
            )}

            {/* Notes */}
            {room.notes && (
                <>
                    <Divider />
                    <Title level={4}>Ghi chú</Title>
                    <Text>{room.notes}</Text>
                </>
            )}
        </Drawer>
    );
};

export default RoomDetail;
