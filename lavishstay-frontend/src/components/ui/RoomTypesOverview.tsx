import React, { useCallback } from 'react';
import { Button, Divider } from 'antd';
import { ReloadOutlined, EyeOutlined } from '@ant-design/icons';
import { useNavigate } from 'react-router-dom';
import { useRoomTypes } from '../../hooks/useRoomTypesOverview';
import { RoomType } from '../../types/roomTypes';
import RoomTypeGrid from './RoomTypeGrid';
import '../../styles/room-types-overview.css';
import { getImageUrl } from '../../utils/formatUtils';
import { useEffect } from 'react';

interface RoomTypesOverviewProps {
    limit?: number;
    showHeader?: boolean;
    showViewAll?: boolean;
    className?: string;
}

const RoomTypesOverview: React.FC<RoomTypesOverviewProps> = ({
    limit = 100, // Show all rooms by default (no pagination)
    showHeader = false,
    showViewAll = true,
    className = ''
}) => {
    const navigate = useNavigate();

    // Fetch room types without pagination (show all)
    const { data: roomTypes, loading, error, refetch } = useRoomTypes({
        limit,
        locale: 'vi',
        currency: 'VND'
    });

    // Handle card click
    const handleCardClick = useCallback((roomType: RoomType) => {
        // Navigate to room type detail page
        navigate(roomType.slug_url);
    }, [navigate]);

    // Handle view all click
    const handleViewAll = useCallback(() => {
        navigate('/rooms');
    }, [navigate]);

    // Quick runtime normalization: if backend URL was produced as http://localhost/storage/... (missing port), rewrite to API base
    useEffect(() => {
        if (!roomTypes || roomTypes.length === 0) return;

        const normalized = roomTypes.map(rt => ({
            ...rt,
            thumbnail: getImageUrl(rt.thumbnail),
            gallery: (rt.gallery || []).map((g: string) => getImageUrl(g))
        }));

        // Mutate in place for grid (RoomTypeGrid reads from the same array reference)
        try {
            roomTypes.splice(0, roomTypes.length, ...normalized as any);
        } catch (e) {
            // ignore
        }
    }, [roomTypes]);

    return (
        <div className={`room-types-overview ${className}`}>
            {/* Header */}
            {showHeader && (
                <div className="flex items-center justify-between mb-6">
                    <div>
                        <h2 className="text-2xl font-bold mb-2">Các loại phòng của chúng tôi</h2>
                        <p className="text-gray-600 dark:text-gray-300">
                            Khám phá đa dạng các loại phòng sang trọng tại LavishStay
                        </p>
                    </div>

                    <div className="flex items-center gap-2">
                        <Button
                            icon={<ReloadOutlined />}
                            onClick={refetch}
                            disabled={loading}
                        >
                            Làm mới
                        </Button>

                        {showViewAll && (
                            <Button
                                type="primary"
                                icon={<EyeOutlined />}
                                onClick={handleViewAll}
                            >
                                Xem tất cả
                            </Button>
                        )}
                    </div>
                </div>
            )}

            {/* Grid */}
            <RoomTypeGrid
                roomTypes={roomTypes}
                loading={loading}
                error={error}
                onCardClick={handleCardClick}
                gutter={[24, 24]}
            />

            {/* Statistics */}
            {!loading && !error && roomTypes.length > 0 && (
                <>
                    <Divider />
                    <div className="text-center text-gray-600 dark:text-gray-300">
                        <p>Hiển thị {roomTypes.length} loại phòng</p>
                        {showViewAll && roomTypes.length >= limit && (
                            <Button
                                type="link"
                                onClick={handleViewAll}
                                className="mt-2"
                            >
                                Xem thêm loại phòng khác →
                            </Button>
                        )}
                    </div>
                </>
            )}
        </div>
    );
};

export default React.memo(RoomTypesOverview);
