import React, { useState, useEffect } from 'react';
import { Modal, Table, Checkbox, Row, Col, Button, message } from 'antd';
import { HomeOutlined } from '@ant-design/icons';
import type { ColumnsType } from 'antd/es/table';

interface Room {
    room_id: number;
    room_number: string;
    floor_number: number;
    room_type_name: string;
    base_price: number;
    status: string;
}

interface RoomSelectionModalProps {
    visible: boolean;
    onClose: () => void;
    bookingId: number;
    requiredRooms: number;
    onRoomsSelected: (selectedRooms: number[]) => void;
}

const RoomSelectionModal: React.FC<RoomSelectionModalProps> = ({
    visible,
    onClose,
    bookingId,
    requiredRooms,
    onRoomsSelected
}) => {
    const [loading, setLoading] = useState(false);
    const [rooms, setRooms] = useState<Room[]>([]);
    const [selectedRoomIds, setSelectedRoomIds] = useState<number[]>([]);

    const fetchAvailableRooms = async () => {
        try {
            setLoading(true);
            const response = await receptionAPI.getAvailableRoomsForBooking(bookingId);
            setRooms(response.data.rooms);
        } catch (error: any) {
            message.error('Lỗi khi tải danh sách phòng: ' + error.message);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        if (visible) {
            fetchAvailableRooms();
        }
    }, [visible]);

    const handleRoomSelect = (roomId: number, checked: boolean) => {
        if (checked && selectedRoomIds.length >= requiredRooms) {
            message.warning(`Chỉ được chọn tối đa ${requiredRooms} phòng`);
            return;
        }

        setSelectedRoomIds(prev =>
            checked
                ? [...prev, roomId]
                : prev.filter(id => id !== roomId)
        );
    };

    const handleConfirm = () => {
        if (selectedRoomIds.length !== requiredRooms) {
            message.warning(`Vui lòng chọn đủ ${requiredRooms} phòng`);
            return;
        }
        onRoomsSelected(selectedRoomIds);
        onClose();
    };

    // Group rooms by floor
    const roomsByFloor = rooms.reduce((acc, room) => {
        const floor = room.floor_number;
        if (!acc[floor]) {
            acc[floor] = [];
        }
        acc[floor].push(room);
        return acc;
    }, {} as Record<number, Room[]>);

    return (
        <Modal
            title="Chọn phòng cho đơn đặt"
            open={visible}
            onCancel={onClose}
            width={1000}
            footer={[
                <Button key="back" onClick={onClose}>
                    Hủy
                </Button>,
                <Button
                    key="submit"
                    type="primary"
                    onClick={handleConfirm}
                    disabled={selectedRoomIds.length !== requiredRooms}
                >
                    Xác nhận ({selectedRoomIds.length}/{requiredRooms})
                </Button>,
            ]}
        >
            {Object.entries(roomsByFloor).map(([floor, floorRooms]) => (
                <div key={floor} style={{ marginBottom: 24 }}>
                    <h3 style={{ marginBottom: 16 }}>Tầng {floor}</h3>
                    <Row gutter={[16, 16]}>
                        {floorRooms.map(room => (
                            <Col key={room.room_id} span={6}>
                                <div
                                    style={{
                                        border: '1px solid #d9d9d9',
                                        borderRadius: 8,
                                        padding: 16,
                                        backgroundColor: selectedRoomIds.includes(room.room_id) ? '#f0f5ff' : 'white',
                                    }}
                                >
                                    <Checkbox
                                        checked={selectedRoomIds.includes(room.room_id)}
                                        onChange={(e) => handleRoomSelect(room.room_id, e.target.checked)}
                                    >
                                        <div style={{ marginLeft: 8 }}>
                                            <div style={{ fontSize: 16, fontWeight: 600 }}>
                                                {room.room_number}
                                            </div>
                                            <div style={{ fontSize: 12, color: '#8c8c8c' }}>
                                                {room.room_type_name}
                                            </div>
                                            <div style={{ fontSize: 14, color: '#f50', marginTop: 4 }}>
                                                {new Intl.NumberFormat('vi-VN').format(room.base_price)}₫
                                            </div>
                                        </div>
                                    </Checkbox>
                                </div>
                            </Col>
                        ))}
                    </Row>
                </div>
            ))}
        </Modal>
    );
};

export default RoomSelectionModal;
