import React from 'react';
import {
    Card,
    Row,
    Col,
    Avatar,
    Divider,
    Typography,
} from 'antd';
import {
    HomeOutlined,
    UserOutlined,
} from '@ant-design/icons';

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
    representative?: {
        name?: string;
        phone?: string;
        email?: string;
        identity_number?: string;
    };
}

const BookedDetailsTab: React.FC<{ booking_rooms?: BookingRoom[] }> = ({ booking_rooms }) => {
    return (
        <Card
            style={{
                borderRadius: '12px',
                boxShadow: '0 2px 8px 0 rgba(0, 0, 0, 0.04)',
                border: '1px solid #f0f0f0'
            }}
            bodyStyle={{ padding: '20px' }}
        >
            <div style={{ marginBottom: 20 }}>
                <Text strong style={{ fontSize: '16px', color: '#262626' }}>
                    Thông tin các phòng đã đặt
                </Text>
            </div>
            {Array.isArray(booking_rooms) && booking_rooms.length > 0 ? (
                <Row gutter={[16, 16]}>
                    {booking_rooms.map((room) => (
                        <Col span={12} key={room.booking_room_id}>
                            <Card
                                size="small"
                                style={{
                                    border: '1px solid #f0f0f0',
                                    borderRadius: '8px',
                                    backgroundColor: '#fafafa'
                                }}
                                title={
                                    <div style={{ display: 'flex', alignItems: 'center' }}>
                                        <HomeOutlined style={{ marginRight: 8, color: '#1890ff' }} />
                                        <span style={{ fontWeight: 600, fontSize: '14px' }}>
                                            {room.option_name || 'Chưa chỉ định'}
                                        </span>
                                    </div>
                                }
                            >
                                <div style={{ marginBottom: 12 }}>
                                    <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: 8 }}>
                                        <span style={{ color: '#8c8c8c', fontSize: '12px' }}>Giá mỗi đêm:</span>
                                        <span style={{ fontWeight: 500, color: '#1890ff' }}>{new Intl.NumberFormat('vi-VN').format(room.option_price || 0)} ₫</span>
                                    </div>
                                    <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: 8 }}>
                                        <span style={{ color: '#8c8c8c', fontSize: '12px' }}>Người lớn:</span>
                                        <span style={{ fontWeight: 500, color: '#1890ff' }}>{room.adults} người</span>
                                    </div>
                                    <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: 8 }}>
                                        <span style={{ color: '#8c8c8c', fontSize: '12px' }}>Trẻ em:</span>
                                        <span style={{ fontWeight: 500, color: '#fa8c16' }}>{room.children} trẻ</span>
                                    </div>
                                    {room.children > 0 && Array.isArray(room.children_age) && room.children_age.length > 0 && (
                                        <div style={{ marginTop: 8 }}>
                                            <div style={{ color: '#8c8c8c', fontSize: '12px', marginBottom: 4 }}>
                                                Độ tuổi trẻ em:
                                            </div>
                                            <div style={{
                                                padding: '6px 8px',
                                                backgroundColor: '#fff7e6',
                                                borderRadius: '4px',
                                                fontSize: '12px',
                                                color: '#fa8c16',
                                                fontWeight: 500
                                            }}>
                                                {room.children_age.map((age, index) => `Trẻ ${index + 1}: ${age} tuổi`).join(' • ')}
                                            </div>
                                        </div>
                                    )}
                                </div>
                                <Divider style={{ margin: '12px 0' }} />
                                <div>
                                    <div style={{ color: '#8c8c8c', fontSize: '12px', marginBottom: 8 }}>
                                        Người đại diện:
                                    </div>
                                    {room.representative && room.representative.name ? (
                                        <div style={{ display: 'flex', alignItems: 'center' }}>
                                            <Avatar
                                                size={32}
                                                icon={<UserOutlined />}
                                                style={{
                                                    marginRight: 8,
                                                    backgroundColor: '#52c41a'
                                                }}
                                            />
                                            <div>
                                                <div style={{ fontWeight: 600, fontSize: '13px', color: '#262626' }}>
                                                    {room.representative.name}
                                                </div>
                                                <div style={{ fontSize: '11px', color: '#8c8c8c' }}>
                                                    📞 {room.representative.phone || 'Chưa có SĐT'}
                                                </div>
                                            </div>
                                        </div>
                                    ) : (
                                        <div style={{
                                            textAlign: 'center',
                                            padding: '12px',
                                            backgroundColor: '#f5f5f5',
                                            borderRadius: '6px',
                                            color: '#8c8c8c',
                                            fontSize: '12px'
                                        }}>
                                            Chưa chỉ định người đại diện
                                        </div>
                                    )}
                                </div>
                            </Card>
                        </Col>
                    ))}
                </Row>
            ) : (
                <div style={{ textAlign: 'center', padding: '40px 0' }}>
                    <Text type="secondary">Chưa có thông tin phòng</Text>
                </div>
            )}
        </Card>
    );
};

export default BookedDetailsTab;
