import React from 'react';
import { Space, Typography } from 'antd';
import RoomCard from './RoomCard';
import { Room } from '../../mirage/models';

const { Title } = Typography;

interface RoomTypeSectionProps {
    roomType: string;
    rooms: Room[];
    selectedRooms: { [roomId: string]: { [optionId: string]: number } };
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
    selectedRooms,
    onQuantityChange,
    onShowImageGallery,
    shouldShowSuggestion,
    searchData,
    formatVND,
    getNights,
    getRoomTypeDisplayName
}) => {
    return (
        <div id={`room-type-${roomType}`} className="scroll-mt-24">
            <div className="mb-6">
                <Title level={3} className="text-gray-800 mb-0">
                    {getRoomTypeDisplayName(roomType)}
                </Title>
            </div>
            <Space direction="vertical" size="large" className="w-full">
                {rooms.map((room) => (
                    <div key={room.id} className="w-full">
                        <RoomCard
                            room={room}
                            selectedRooms={selectedRooms}
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
