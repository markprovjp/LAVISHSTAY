import React from 'react';
import { Tooltip } from 'antd';
import { WifiOutlined, CarOutlined, CoffeeOutlined, SafetyOutlined, HomeOutlined } from '@ant-design/icons';
import { RoomTypeAmenity } from '../../types/roomTypes';

interface AmenitiesListProps {
    amenities: RoomTypeAmenity[];
    maxDisplay?: number;
    showTooltip?: boolean;
    className?: string;
}

// Icon mapping for common amenities
const amenityIcons: Record<string, React.ReactNode> = {
    wifi: <WifiOutlined />,
    parking: <CarOutlined />,
    breakfast: <CoffeeOutlined />,
    pool: <HomeOutlined />,
    safe: <SafetyOutlined />,
    // Add more icons as needed
};

const AmenitiesList: React.FC<AmenitiesListProps> = ({
    amenities,
    maxDisplay = 5,
    showTooltip = true,
    className = ''
}) => {
    const displayAmenities = amenities.slice(0, maxDisplay);
    const hasMore = amenities.length > maxDisplay;

    return (
        <div className={`flex flex-wrap gap-2 ${className}`}>
            {displayAmenities.map((amenity) => {
                const icon = amenityIcons[amenity.icon] || <span className="text-xs">•</span>;

                const amenityElement = (
                    <div
                        key={amenity.id}
                        className="flex items-center gap-1 text-sm text-gray-600 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-md"
                    >
                        {icon}
                        <span className="truncate max-w-20">{amenity.name}</span>
                    </div>
                );

                return showTooltip ? (
                    <Tooltip key={amenity.id} title={amenity.name}>
                        {amenityElement}
                    </Tooltip>
                ) : amenityElement;
            })}

            {hasMore && (
                <div className="flex items-center text-sm text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-md">
                    +{amenities.length - maxDisplay} khác
                </div>
            )}
        </div>
    );
};

export default React.memo(AmenitiesList);
