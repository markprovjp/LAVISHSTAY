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
    } return (
        <Row gutter={[6, 6]} className="mt-2">
            {formattedAmenities.map((amenity) => (
                <Col xs={12} sm={12} md={12} lg={12} xl={12} key={amenity.key}>
                    <div className="flex items-center p-2 rounded-md bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                        <div className="text-sm mr-2 flex-shrink-0 text-blue-600">
                            {amenity.icon}
                        </div>
                        <span className="text-xs font-medium leading-tight text-gray-700 truncate">
                            {amenity.name}
                        </span>
                    </div>
                </Col>
            ))}
        </Row>
    );
};

export default RoomMainAmenities;
