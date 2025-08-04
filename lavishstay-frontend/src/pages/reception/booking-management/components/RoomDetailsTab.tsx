
import React from 'react';
import {
    Card,
    Collapse,
    Button,
    Descriptions,
    Divider,
    Space,
    Typography,
    message,
} from 'antd';
import {
    BedDouble,
    CalendarDays,
    UserRound,
    LogOut,
    XCircle,
    Ban,
    ArrowRightLeft,
    DoorOpen,
    ExclamationCircleOutlined,
    DollarOutlined,
} from 'lucide-react';
import dayjs from 'dayjs';

const { Text } = Typography;

interface BookingRoom {
    booking_room_id: number;
    room_id: number | null;
    room_name: string | null;
    room_floor: number | null;
    room_status: string | null;
    room_type: {
        name: string | null;
        description: string | null;
        base_price: number;
        max_guests: number;
        room_area: number | null;
    };
    option_name?: string;
    option_price?: number;
    price_per_night: number;
    nights: number;
    total_price: number;
    check_in_date: string;
    check_out_date: string;
    adults: number;
    children: number;
    children_age?: number[] | string;
    representative: {
        id: number;
        name: string;
        phone: string;
        email: string;
        date_of_birth?: string;
        identity_number: string;
        nationality?: string;
    };
}

interface RoomDetailsTabProps {
    booking_rooms: BookingRoom[];
    handleOpenRoomSelectModal: (bookingRoomId: number) => void;
    handleRoomAction: (action: string, roomIds: number[]) => void;
}

const RoomDetailsTab: React.FC<RoomDetailsTabProps> = ({
    booking_rooms,
    handleOpenRoomSelectModal,
    handleRoomAction,
}) => {
    return (
        <Collapse
            accordion
            style={{ background: '#fff', borderRadius: 12, boxShadow: '0 2px 8px 0 rgba(0,0,0,0.04)', border: '1px solid #f0f0f0' }}
        >
            {Array.isArray(booking_rooms) && booking_rooms.length > 0 ? (
                booking_rooms.map((room, idx) => (
                    <Collapse.Panel
                        header={
                            <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                                <BedDouble size={20} style={{ color: '#1890ff' }} />
                                <span style={{ fontWeight: 600, fontSize: '15px' }}>{room.option_name || 'Chưa chỉ định'} - Phòng #{idx + 1}</span>
                                {room.room_name ? (
                                    <span style={{ color: '#52c41a', fontWeight: 500, marginLeft: 8 }}><DoorOpen size={18} /> {room.room_name}</span>
                                ) : (
                                    <Button type="dashed" icon={<DoorOpen size={16} />} size="small" onClick={() => handleOpenRoomSelectModal(room.booking_room_id)} style={{ marginLeft: 8 }}>
                                        Chọn phòng
                                    </Button>
                                )}
                            </div>
                        }
                        key={room.booking_room_id}
                    >
                        <Card
                            style={{ borderRadius: 8, background: '#fafafa', border: '1px solid #f0f0f0', marginBottom: 12 }}
                            bodyStyle={{ padding: '16px' }}
                        >
                            <Descriptions
                                title={<span><CalendarDays size={16} style={{ marginRight: 6 }} />Thông tin phòng</span>}
                                bordered
                                size="middle"
                                column={2}
                                style={{ marginBottom: 12 }}
                            >
                                <Descriptions.Item label={<span><UserRound size={14} style={{ marginRight: 4 }} />Người lớn</span>}>{room.adults} người</Descriptions.Item>
                                <Descriptions.Item label={<span><UserRound size={14} style={{ marginRight: 4 }} />Trẻ em</span>}>{room.children} trẻ</Descriptions.Item>
                                <Descriptions.Item label={<CalendarDays size={14} style={{ marginRight: 4 }} />}>Ngày nhận phòng: {room.check_in_date}</Descriptions.Item>
                                <Descriptions.Item label={<CalendarDays size={14} style={{ marginRight: 4 }} />}>Ngày trả phòng: {room.check_out_date}</Descriptions.Item>
                                <Descriptions.Item label={<span><LogOut size={14} style={{ marginRight: 4 }} />Số đêm</span>}>{room.nights}</Descriptions.Item>
                                <Descriptions.Item label={<DollarOutlined style={{ marginRight: 4, color: '#f50' }} />}>Tổng tiền: <span style={{ color: '#f50', fontWeight: 600 }}>{new Intl.NumberFormat('vi-VN').format(room.total_price || 0)} ₫</span></Descriptions.Item>
                            </Descriptions>
                            {room.children > 0 && Array.isArray(room.children_age) && room.children_age.length > 0 && (
                                <Descriptions
                                    title={<span><UserRound size={14} style={{ marginRight: 4 }} />Độ tuổi trẻ em</span>}
                                    bordered
                                    size="small"
                                    column={2}
                                    style={{ marginBottom: 12, background: '#fffbe6', borderRadius: 8 }}
                                >
                                    {room.children_age.map((age, index) => (
                                        <Descriptions.Item
                                            key={index}
                                            label={`Trẻ em ${index + 1}`}
                                            labelStyle={{ fontWeight: 500, color: '#fa8c16' }}
                                            contentStyle={{ color: '#fa8c16', fontWeight: 500 }}
                                        >
                                            {age} tuổi
                                        </Descriptions.Item>
                                    ))}
                                </Descriptions>
                            )}
                            <Divider style={{ margin: '12px 0' }} />
                            <Descriptions
                                title={<span><UserRound size={14} style={{ marginRight: 4 }} />Người đại diện</span>}
                                bordered
                                size="small"
                                column={2}
                            >
                                <Descriptions.Item label="Tên">{room.representative?.name || '-'}</Descriptions.Item>
                                <Descriptions.Item label="SĐT">{room.representative?.phone || '-'}</Descriptions.Item>
                                <Descriptions.Item label="Email">{room.representative?.email || '-'}</Descriptions.Item>
                                <Descriptions.Item label="CMND/CCCD">{room.representative?.identity_number || '-'}</Descriptions.Item>
                            </Descriptions>
                            <Divider style={{ margin: '12px 0' }} />
                            <Space wrap>
                                <Button icon={<ArrowRightLeft size={16} />} onClick={() => handleRoomAction('transfer', [room.booking_room_id])}>Đổi phòng</Button>
                                <Button icon={<LogOut size={16} />} onClick={() => handleRoomAction('check_in', [room.booking_room_id])}>Check-in</Button>
                                <Button icon={<XCircle size={16} />} onClick={() => handleRoomAction('check_out', [room.booking_room_id])}>Check-out</Button>
                                <Button icon={<Ban size={16} />} danger onClick={() => handleRoomAction('cancel', [room.booking_room_id])}>Hủy</Button>
                                <Button icon={<ExclamationCircleOutlined />} danger onClick={() => handleRoomAction('no_show', [room.booking_room_id])}>No show</Button>
                            </Space>
                        </Card>
                    </Collapse.Panel>
                ))
            ) : (
                <div style={{ textAlign: 'center', padding: '40px 0' }}>
                    <Text type="secondary">Chưa có thông tin phòng</Text>
                </div>
            )}
        </Collapse>
    );
};

export default RoomDetailsTab;
