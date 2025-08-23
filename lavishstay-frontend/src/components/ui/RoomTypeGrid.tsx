import React from 'react';
import { Row, Col, Spin, Empty, Alert } from 'antd';
import { RoomType } from '../../types/roomTypes';
import RoomTypeOverviewCard from './RoomTypeOverviewCard';

interface RoomTypeGridProps {
    roomTypes: RoomType[];
    loading?: boolean;
    error?: string | null;
    onCardClick?: (roomType: RoomType) => void;
    className?: string;
    gutter?: [number, number];
}

const RoomTypeGrid: React.FC<RoomTypeGridProps> = ({
    roomTypes,
    loading = false,
    error = null,
    onCardClick,
    className = '',
    gutter = [16, 16]
}) => {
    // Loading state
    if (loading) {
        return (
            <div className="text-center py-16">
                <Spin size="large" tip="Đang tải loại phòng..." />
            </div>
        );
    }

    // Error state
    if (error) {
        return (
            <Alert
                message="Có lỗi xảy ra"
                description={error}
                type="error"
                showIcon
                className="mb-6"
            />
        );
    }

    // Empty state
    if (!roomTypes || roomTypes.length === 0) {
        return (
            <Empty
                image={Empty.PRESENTED_IMAGE_SIMPLE}
                description="Không có loại phòng nào"
                className="py-16"
            />
        );
    }

    return (
        <div className={`room-type-grid ${className}`}>
            <Row gutter={gutter}>
                {roomTypes.map((roomType) => (
                    <Col
                        key={roomType.room_type_id}
                        xs={24}      // Mobile: 1 column
                        sm={12}      // Tablet: 2 columns  
                        md={8}       // Desktop: 3 columns
                        xl={6}       // Wide: 4 columns
                        className="mb-4"
                    >
                        <RoomTypeOverviewCard
                            roomType={roomType}
                            onClick={onCardClick}
                            className="h-full"
                        />
                    </Col>
                ))}
            </Row>
        </div>
    );
};

export default React.memo(RoomTypeGrid);
