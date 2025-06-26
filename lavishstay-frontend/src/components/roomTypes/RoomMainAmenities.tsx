import React from "react";
import { Row, Col } from "antd";
import { AmenityUtils } from "../../constants/amenities";

interface MainAmenitiesProps {
    amenities: string[];
    limit?: number; // Giới hạn số lượng tiện ích hiển thị
}

const RoomMainAmenities: React.FC<MainAmenitiesProps> = ({
    amenities,
    limit = 4
}) => {
    // Lấy các tiện ích chính, giới hạn số lượng
    const displayAmenities = amenities.slice(0, limit);
    const formattedAmenities = AmenityUtils.formatAmenitiesForDisplay(displayAmenities);

    if (formattedAmenities.length === 0) {
        return null;
    }

    return (
        <div className="grid grid-cols-2 gap-2">
            {formattedAmenities.map((amenity) => (
                <div key={amenity.key} className="flex items-center gap-2 py-1">
                    <div className="flex items-center justify-center w-4 h-4 text-xs opacity-80">
                        {amenity.icon}
                    </div>
                    <span className="text-xs font-medium text-gray-700 truncate leading-tight">
                        {amenity.name}
                    </span>
                </div>
            ))}
        </div>
    );
};

export default RoomMainAmenities;
