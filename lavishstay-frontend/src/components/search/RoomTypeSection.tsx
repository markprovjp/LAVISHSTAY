import React from 'react';
import { Space, Typography } from 'antd';
import { useSelector } from 'react-redux';
import RoomCard from './RoomCard';
import { Room } from '../../mirage/models';
import { selectBookingState } from '../../store/slices/bookingSlice';

const { Title } = Typography;

interface RoomTypeSectionProps {
    roomType: string;
    rooms: Room[];
    onQuantityChange: (roomId: string, optionId: string, quantity: number) => void;
    onShowImageGallery: (room: Room) => void;
    shouldShowSuggestion: () => boolean;
    searchData: any;
    formatVND: (price: number) => string;
    getNights: () => number;
    getRoomTypeDisplayName: (roomType: string) => string;
}

const RoomTypeSection: React.FC<RoomTypeSectionProps> = ({
    roomType,
    rooms,
    onQuantityChange,
    onShowImageGallery,
    shouldShowSuggestion,
    searchData,
    formatVND,
    getNights,
    getRoomTypeDisplayName
}) => {
    const bookingState = useSelector(selectBookingState);
    return (
        <div id={`room-type-${roomType}`} className="scroll-mt-24">
            <div className="mb-6">
                <Title level={3} className="text-gray-800 mb-0">
                    {getRoomTypeDisplayName(roomType)}
                </Title>
            </div>
            <Space direction="vertical" size="large" className="w-full">
                {rooms.map((room) => (
                    <div key={room.id} className="w-full">                        <RoomCard
                        room={room}
                        selectedRooms={bookingState.selectedRooms}
                        onQuantityChange={onQuantityChange}
                        onShowImageGallery={onShowImageGallery}
                        shouldShowSuggestion={shouldShowSuggestion}
                        searchData={searchData}
                        formatVND={formatVND}
                        getNights={getNights}
                    />
                    </div>
                ))}
            </Space>
        </div>
    );
};

export default RoomTypeSection;
