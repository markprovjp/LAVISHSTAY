import React from 'react';
import { Row, Col, Spin } from 'antd';
import RoomCard from './RoomCard';

interface Room {
    id: string;
    room_number: string;
    room_type: string;
    status: 'available' | 'occupied' | 'maintenance' | 'reserved';
    guest_name?: string;
    check_in?: string;
    check_out?: string;
    guest_count?: number;
    booking_code?: string;
}

interface RoomGridProps {
    rooms: Room[];
    loading: boolean;
    onViewDetails: (roomId: string) => void;
    onStatusChange: (roomId: string, newStatus: string) => void;
}

const RoomGrid: React.FC<RoomGridProps> = ({
    rooms,
    loading,
    onViewDetails,
    onStatusChange
}) => {
    if (loading) {
        return (
            <div style={{ textAlign: 'center', padding: '50px' }}>
                <Spin size="large" />
            </div>
        );
    }

    return (
        <Row gutter={[16, 16]}>
            {rooms.map(room => (
                <Col xs={24} sm={12} md={8} lg={6} xl={4} key={room.id}>
                    <RoomCard
                        room={room}
                        onViewDetails={onViewDetails}
                        onStatusChange={onStatusChange}
                    />
                </Col>
            ))}
        </Row>
    );
};

export default RoomGrid;
