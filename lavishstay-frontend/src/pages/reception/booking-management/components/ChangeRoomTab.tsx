import React, { useState, useEffect, useMemo } from 'react';
import { Button, message, Spin, Card, Typography, Row, Col, Divider, Empty, Alert, Select, List, Avatar, Tag } from 'antd';
import { Building, BedDouble, ArrowRight, KeyRound, CheckCircle2 } from 'lucide-react';
import { receptionAPI } from '../../../../utils/api';

const { Title, Text, Paragraph } = Typography;
const { Option } = Select;

interface AvailableRoom {
  id: number;
  name: string;
  floor: number;
  room_type_id: number;
}

interface BookingRoom {
  booking_room_id: number;
  room_id: number | null;
  room_name: string | null;
  room_type: {
    id: number;
    name: string;
  };
  check_in_date: string;
  check_out_date: string;
}

interface ChangeRoomTabProps {
  bookingId: number | null;
  bookingRooms: BookingRoom[];
  onUpdate: () => void;
}

const ChangeRoomTab: React.FC<ChangeRoomTabProps> = ({ bookingId, bookingRooms, onUpdate }) => {
  const [loading, setLoading] = useState(false);
  const [availableRooms, setAvailableRooms] = useState<AvailableRoom[]>([]);
  const [selectedBookingRoomId, setSelectedBookingRoomId] = useState<number | null>(null);
  const [selectedNewRoomId, setSelectedNewRoomId] = useState<number | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const currentRoom = useMemo(() => {
    if (!Array.isArray(bookingRooms)) return undefined;
    return bookingRooms.find(r => r.booking_room_id === selectedBookingRoomId);
  }, [selectedBookingRoomId, bookingRooms]);

  useEffect(() => {
    const fetchAvailableRooms = async () => {
      if (!currentRoom) {
        setAvailableRooms([]);
        return;
      }
      setLoading(true);
      setSelectedNewRoomId(null); // Reset new room selection
      try {
        const params = {
          check_in_date: currentRoom.check_in_date,
          check_out_date: currentRoom.check_out_date,
          room_type_id: currentRoom.room_type.id,
          exclude_room_id: currentRoom.room_id || undefined
        };
        const response = await receptionAPI.getRooms(params);
        setAvailableRooms(response || []);
      } catch (error) {
        message.error('Lỗi khi tải danh sách phòng trống.');
        console.error("Failed to fetch available rooms:", error);
        setAvailableRooms([]);
      } finally {
        setLoading(false);
      }
    };

    fetchAvailableRooms();
  }, [currentRoom]);

  const handleChangeRoom = async () => {
    if (!selectedBookingRoomId || !selectedNewRoomId) {
      message.warning('Vui lòng chọn phòng cần đổi và phòng mới.');
      return;
    }
    setIsSubmitting(true);
    try {
      await receptionAPI.transferBooking({
        booking_id: bookingId!,
        old_room_id: currentRoom?.room_id!,
        new_room_id: selectedNewRoomId!,
      });
      message.success('Đổi phòng thành công!');
      onUpdate(); // Refresh booking details
      // Reset state
      setSelectedBookingRoomId(null);
      setSelectedNewRoomId(null);
      setAvailableRooms([]);
    } catch (error: any) {
      message.error(error.response?.data?.message || 'Không thể đổi phòng.');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <Card title="Thực hiện đổi phòng">
      <Row gutter={[32, 16]}>
        {/* Step 1: Select room to change */}
        <Col xs={24} md={8}>
          <Title level={5}>1. Chọn phòng cần đổi</Title>
          <Paragraph type="secondary">Chọn phòng hiện tại của khách muốn đổi sang phòng khác.</Paragraph>
          <Select
            style={{ width: '100%' }}
            placeholder="Chọn phòng từ danh sách"
            onChange={(value) => setSelectedBookingRoomId(value)}
            value={selectedBookingRoomId}
          >
            {(Array.isArray(bookingRooms) ? bookingRooms : []).filter(r => r.room_id).map(room => (
              <Option key={room.booking_room_id} value={room.booking_room_id}>
                {`Phòng ${room.room_name} (${room.room_type.name})`}
              </Option>
            ))}
          </Select>
        </Col>

        {/* Step 2: Select new room */}
        <Col xs={24} md={16}>
          <Title level={5}>2. Chọn phòng mới</Title>
          <Paragraph type="secondary">
            {currentRoom ? `Các phòng trống loại "${currentRoom.room_type.name}" khả dụng.` : 'Vui lòng chọn phòng cần đổi trước.'}
          </Paragraph>
          <Spin spinning={loading}>
            <div style={{ maxHeight: '400px', overflowY: 'auto', border: '1px solid #f0f0f0', borderRadius: '8px', padding: '8px' }}>
              {loading ? (
                <div style={{ padding: 50, textAlign: 'center' }}><Spin /></div>
              ) : availableRooms.length > 0 ? (
                <List
                  dataSource={availableRooms}
                  renderItem={room => (
                    <List.Item
                      onClick={() => setSelectedNewRoomId(room.id)}
                      style={{
                        cursor: 'pointer',
                        borderRadius: '6px',
                        margin: '4px 0',
                        backgroundColor: selectedNewRoomId === room.id ? '#e6f4ff' : '#fff',
                        border: selectedNewRoomId === room.id ? '1px solid #1677ff' : '1px solid #d9d9d9'
                      }}
                    >
                      <List.Item.Meta
                        avatar={<Avatar icon={<BedDouble />} style={{ backgroundColor: '#1677ff' }} />}
                        title={<Text strong>Phòng {room.name}</Text>}
                        description={`Tầng ${room.floor}`}
                      />
                      {selectedNewRoomId === room.id && <CheckCircle2 color="#52c41a" />}
                    </List.Item>
                  )}
                />
              ) : (
                <Empty description={currentRoom ? "Không có phòng trống phù hợp" : "Chưa chọn phòng cần đổi"} />
              )}
            </div>
          </Spin>
        </Col>
      </Row>

      <Divider />

      {/* Step 3: Confirmation */}
      <Row justify="space-between" align="middle">
        <Col>
          {currentRoom && selectedNewRoomId && (
            <Text strong>
              Đổi từ <Tag color="red">{currentRoom.room_name}</Tag> sang <Tag color="green">{availableRooms.find(r => r.id === selectedNewRoomId)?.name}</Tag>?
            </Text>
          )}
        </Col>
        <Col>
          <Button
            type="primary"
            onClick={handleChangeRoom}
            disabled={!selectedBookingRoomId || !selectedNewRoomId || isSubmitting}
            loading={isSubmitting}
            icon={<ArrowRight />}
          >
            Xác nhận đổi phòng
          </Button>
        </Col>
      </Row>
      <Divider />
      <Card type="inner" title="Chính sách đổi phòng">
        <Paragraph>
          - Việc đổi phòng tùy thuộc vào tình trạng phòng trống.
        </Paragraph>
        <Paragraph>
          - Nếu đổi sang loại phòng có giá cao hơn, khách hàng sẽ phải thanh toán phần chênh lệch.
        </Paragraph>
        <Paragraph>
          - Nếu đổi phòng do lỗi từ phía khách sạn, khách hàng sẽ không phải trả thêm phí.
        </Paragraph>
      </Card>
    </Card>
  );
};

export default ChangeRoomTab;