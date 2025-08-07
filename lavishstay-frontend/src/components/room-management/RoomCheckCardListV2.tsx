import React, { useMemo, useCallback } from 'react';
import RoomCardGrid, { type RoomInfo } from './RoomCardGrid';

// Interface để map từ data hiện tại sang RoomInfo
interface OldRoomData {
    id: string;
    name: string;
    floor: number | string;
    status: string;
    room_type?: {
        id: string;
        name: string;
    };
    booking_info?: {
        check_in: string;
        check_out: string;
        guest_name?: string;
        guest_count?: number;
        representative_name?: string;
    };
    maintenance_reason?: string;
    last_cleaned?: string;
}

interface RoomCheckCardListProps {
    rooms: OldRoomData[];
    loading?: boolean;
    selectedRooms?: Set<string>;
    onRoomSelect?: (roomId: string, selected: boolean) => void;
    onViewDetails?: (roomId: string) => void;
    mode?: 'view' | 'select';
    onFloorSelect?: (floorId: string, roomIds: string[], selected: boolean) => void;
}

// Component wrapper để maintain backward compatibility
const RoomCheckCardListV2: React.FC<RoomCheckCardListProps> = ({
    rooms,
    loading = false,
    selectedRooms = new Set(),
    onRoomSelect,
    onViewDetails,
    mode = 'view',
    onFloorSelect
}) => {
    // Convert old room data format to new RoomInfo format
    const convertedRooms = useMemo<RoomInfo[]>(() => {
        return rooms.map(room => ({
            id: room.id,
            name: room.name,
            floor: room.floor,
            status: room.status as RoomInfo['status'],
            room_type: room.room_type,
            booking_info: room.booking_info,
            maintenance_reason: room.maintenance_reason,
            last_cleaned: room.last_cleaned
        }));
    }, [rooms]);

    const handleRoomSelect = useCallback((roomId: string, selected: boolean) => {
        onRoomSelect?.(roomId, selected);
    }, [onRoomSelect]);

    const handleFloorSelect = useCallback((floorId: string, roomIds: string[], selected: boolean) => {
        onFloorSelect?.(floorId, roomIds, selected);
    }, [onFloorSelect]);

    const handleViewDetails = useCallback((roomId: string) => {
        onViewDetails?.(roomId);
    }, [onViewDetails]);

    return (
        <RoomCardGrid
            rooms={convertedRooms}
            loading={loading}
            mode={mode}
            selectedRooms={selectedRooms}
            onRoomSelect={handleRoomSelect}
            onFloorSelect={handleFloorSelect}
            onViewDetails={handleViewDetails}
        />
    );
};

export default RoomCheckCardListV2;
export type { RoomCheckCardListProps, OldRoomData };
