import React, { useState, useEffect } from 'react';
import { Button, message, Spin, Card, Typography, Row, Col, Divider, Alert, Select, Tag, Input, } from 'antd';
import { ArrowRight } from 'lucide-react';
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
  room_floor: number | null;
  room_status: string | null;
  room_type: {
    id: number;
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

interface ChangeRoomTabProps {
  bookingId: number | null;
  bookingRooms: BookingRoom[];
  onUpdate: () => void;
}


const ChangeRoomTab: React.FC<ChangeRoomTabProps> = ({ bookingId, bookingRooms, onUpdate }) => {
  // const [loading, setLoading] = useState(false); // Không cần loading riêng, đã có previewLoading
  const [roomTypes, setRoomTypes] = useState<any[]>([]); // Danh sách loại phòng
  // Multi-room state: mỗi phòng 1 object {booking_room_id, selectedRoomTypeId, selectedOptionId, selectedNewRoomId, roomOptions, availableRooms}
  const [roomChangeList, setRoomChangeList] = useState<any[]>([]);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [reason, setReason] = useState<string>('');
  const [preview, setPreview] = useState<any>(null);
  const [previewLoading, setPreviewLoading] = useState(false);
  // Khi đã chọn đủ phòng mới, gói phòng mới thì tự động gọi API preview phí/chính sách cho tất cả các phòng
  useEffect(() => {
    const fetchPreview = async () => {
      if (!bookingId || roomChangeList.some(item => !item.selectedRoomTypeId || !item.selectedOptionId || !item.selectedNewRoomId)) {
        setPreview(null);
        return;
      }
      setPreviewLoading(true);
      try {
        // Nếu API chỉ nhận 1 option_id, truyền option đầu tiên
        const data = await receptionAPI.previewTransferBooking(
          Number(bookingId),
          roomChangeList.map(item => Number(item.selectedNewRoomId)),
          Number(roomChangeList[0].selectedOptionId),
          reason
        );
        setPreview(data);
      } catch (e) {
        setPreview(null);
      } finally {
        setPreviewLoading(false);
      }
    };
    fetchPreview();
  }, [bookingId, roomChangeList, reason]);


  // Lấy danh sách loại phòng từ tất cả room_types trong hệ thống (nếu muốn cho phép chuyển sang bất kỳ loại phòng nào)
  useEffect(() => {
    const fetchRoomTypes = async () => {
      try {
        const res = await receptionAPI.getRoomTypes();
        // Chuẩn hóa về {id, name}
        let types: any[] = [];
        if (Array.isArray(res)) types = res;
        else if (res && Array.isArray(res.data)) types = res.data;
        setRoomTypes(types.map((t: any) => ({ id: t.room_type_id || t.id, name: t.name })));
      } catch {
        setRoomTypes([]);
      }
    };
    fetchRoomTypes();
  }, []);

  // Khởi tạo roomChangeList khi bookingRooms thay đổi
  useEffect(() => {
    setRoomChangeList(
      bookingRooms.map(room => ({
        booking_room_id: room.booking_room_id,
        selectedRoomTypeId: null,
        selectedOptionId: null,
        selectedNewRoomId: null,
        roomOptions: [],
        availableRooms: []
      }))
    );
  }, [bookingRooms]);


  // Khi chọn loại phòng cho từng phòng, load gói phòng và phòng trống tương ứng
  const handleRoomTypeChange = async (idx: number, roomTypeId: number) => {
    const newList = [...roomChangeList];
    newList[idx].selectedRoomTypeId = roomTypeId;
    newList[idx].selectedOptionId = null;
    newList[idx].selectedNewRoomId = null;
    // Load packages
    try {
      const packages = await receptionAPI.getPackagesByRoomType(roomTypeId);
      newList[idx].roomOptions = packages;
    } catch {
      newList[idx].roomOptions = [];
    }
    // Load available rooms
    try {
      const roomsRes = await receptionAPI.getRooms({ room_type_id: roomTypeId });
      newList[idx].availableRooms = Array.isArray(roomsRes) ? roomsRes : (roomsRes?.data || []);
    } catch {
      newList[idx].availableRooms = [];
    }
    setRoomChangeList(newList);
  };


  const handleChangeRoom = async () => {
    // Validate: tất cả phòng đều phải chọn đủ loại phòng, gói phòng, phòng mới
    if (roomChangeList.some(item => !item.selectedRoomTypeId || !item.selectedOptionId || !item.selectedNewRoomId)) {
      message.warning('Vui lòng chọn đủ loại phòng, gói phòng và phòng mới cho tất cả các phòng.');
      return;
    }
    setIsSubmitting(true);
    try {
      await receptionAPI.transferBooking(
        Number(bookingId),
        {
          new_room_ids: roomChangeList.map(item => Number(item.selectedNewRoomId)),
          // Nếu backend chỉ nhận 1 option_id, lấy option đầu tiên hoặc sửa backend cho nhận mảng
          new_option_id: Number(roomChangeList[0].selectedOptionId),
          reason: reason || undefined,
        }
      );
      message.success('Đổi phòng thành công!');
      onUpdate();
      setRoomChangeList(roomChangeList.map(item => ({ ...item, selectedRoomTypeId: null, selectedOptionId: null, selectedNewRoomId: null, roomOptions: [], availableRooms: [] })));
      setReason('');
      setPreview(null);
    } catch (error: any) {
      message.error(error.response?.data?.message || 'Không thể đổi phòng.');
    } finally {
      setIsSubmitting(false);
    }
  };



  return (
    <Card title="Thực hiện đổi phòng">
      <Row gutter={[32, 16]}>
        <Col xs={24}>
          <Title level={5}>Danh sách phòng đã đặt ({bookingRooms.length})</Title>
        </Col>
        {roomChangeList.map((item, idx) => (
          <Col xs={24} key={item.booking_room_id} style={{ marginBottom: 24 }}>
            <Card type="inner" title={`Phòng hiện tại: ${bookingRooms.find(r => r.booking_room_id === item.booking_room_id)?.room_name || ''}`}
              bordered>
              <Row gutter={16} align="middle">
                <Col xs={24} md={6}>
                  <Text strong>Loại phòng mới</Text>
                  <Select
                    style={{ width: '100%' }}
                    placeholder="Chọn loại phòng mới"
                    value={item.selectedRoomTypeId ?? undefined}
                    onChange={v => handleRoomTypeChange(idx, v)}
                    allowClear
                  >
                    {roomTypes.map((rt: any) => (
                      <Option key={rt.id} value={rt.id}>{rt.name}</Option>
                    ))}
                  </Select>
                </Col>
                <Col xs={24} md={6}>
                  <Text strong>Gói phòng</Text>
                  <Select
                    style={{ width: '100%' }}
                    placeholder="Chọn gói phòng mới"
                    value={item.selectedOptionId ?? undefined}
                    onChange={v => {
                      const newList = [...roomChangeList];
                      newList[idx].selectedOptionId = v;
                      setRoomChangeList(newList);
                    }}
                    allowClear
                    disabled={!item.selectedRoomTypeId}
                  >
                    {item.roomOptions.map((opt: any, i: number) => {
                      const key = opt.package_id ?? opt.id ?? `pkg-idx-${i}`;
                      const value = opt.package_id ?? opt.id ?? `pkg-idx-${i}`;
                      return (
                        <Option key={key} value={value}>
                          {opt.package_name || opt.name || `Gói phòng ${i + 1}`}
                        </Option>
                      );
                    })}
                  </Select>
                </Col>
                <Col xs={24} md={6}>
                  <Text strong>Phòng mới</Text>
                  <Select
                    style={{ width: '100%' }}
                    placeholder="Chọn phòng mới"
                    value={item.selectedNewRoomId ?? undefined}
                    onChange={v => {
                      const newList = [...roomChangeList];
                      newList[idx].selectedNewRoomId = v;
                      setRoomChangeList(newList);
                    }}
                    disabled={!item.selectedRoomTypeId}
                  >
                    {item.availableRooms.map((room: any) => (
                      <Option key={room.id} value={room.id}>{`Phòng ${room.name} (Tầng ${room.floor})`}</Option>
                    ))}
                  </Select>
                </Col>
              </Row>
            </Card>
          </Col>
        ))}
      </Row>

      {/* Nhập lý do đổi phòng */}
      <div style={{ marginTop: 24 }}>
        <Text strong>Lý do đổi phòng: </Text>
        <Input.TextArea
          value={reason}
          onChange={e => setReason(e.target.value)}
          placeholder="Nhập lý do (bắt buộc nếu có phí/chính sách đặc biệt)"
          rows={2}
          style={{ marginTop: 4, maxWidth: 400 }}
        />
      </div>

      {/* Preview phí/chính sách đổi phòng */}
      <div style={{ marginTop: 24 }}>
        <Text strong>Kết quả preview phí/chính sách:</Text>
        <Spin spinning={previewLoading}>
          {preview ? (
            <Alert
              type="info"
              showIcon
              message={preview.message || 'Có thể đổi phòng.'}
              description={
                <div>
                  {preview.policy && <div><b>Chính sách:</b> {preview.policy}</div>}
                  {preview.formula && <div><b>Công thức:</b> {preview.formula}</div>}
                  {preview.transfer_fee && <div><b>Phí chuyển phòng:</b> {Number(preview.transfer_fee).toLocaleString('vi-VN')} VND</div>}
                  {preview.price_difference !== undefined && <div><b>Chênh lệch giá:</b> {Number(preview.price_difference).toLocaleString('vi-VN')} VND</div>}
                  {preview.total_price_difference !== undefined && <div><b>Tổng chênh lệch:</b> {Number(preview.total_price_difference).toLocaleString('vi-VN')} VND</div>}
                  {preview.reason && <div><b>Lý do:</b> {preview.reason}</div>}
                  {preview.booking_info && (
                    <div style={{ marginTop: 8 }}>
                      <b>Thông tin booking:</b><br />
                      Mã: {preview.booking_info.booking_code} | Nhận: {preview.booking_info.check_in_date} | Trả: {preview.booking_info.check_out_date} | Tổng tiền: {Number(preview.booking_info.total_price).toLocaleString('vi-VN')} VND
                    </div>
                  )}
                  {preview.room_info && preview.room_info.length > 0 && (
                    <div style={{ marginTop: 8 }}>
                      <b>Phòng mới:</b>                       {preview.room_info.map((r: any) => `${r.room_name} (${r.room_type}) - Gói: ${r.package_name}`).join(', ')}
                    </div>
                  )}
                </div>
              }
              style={{ marginTop: 8 }}
            />
          ) : (
            <Alert type="warning" showIcon message="Chưa có dữ liệu preview hoặc không thể lấy preview." style={{ marginTop: 8 }} />
          )}
        </Spin>
      </div>

      <Divider />

      {/* Step 5: Confirmation */}
      <Row justify="end" align="middle">
        <Col>
          <Button
            type="primary"
            onClick={handleChangeRoom}
            disabled={roomChangeList.some(item => !item.selectedRoomTypeId || !item.selectedOptionId || !item.selectedNewRoomId) || isSubmitting}
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