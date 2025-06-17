import React, { useState } from "react";
import { Card, Typography, Row, Col, Button, Tag } from "antd";
import {
    DownOutlined,
    UpOutlined,
    HeartOutlined,
} from "@ant-design/icons";
import { AmenityUtils } from "../../constants/amenities";

const { Title } = Typography;

interface AmenitiesProps {
    amenities: string[];
}

const RoomAmenities: React.FC<AmenitiesProps> = ({ amenities }) => {
    const [showAllAmenities, setShowAllAmenities] = useState(false);

    // Limit amenities shown initially
    const displayAmenities = showAllAmenities ? amenities : amenities.slice(0, 12);
    const formattedAmenities = AmenityUtils.formatAmenitiesForDisplay(displayAmenities);

    return (
        <Card className="shadow-sm rounded-lg border-0">
            <div className="flex items-center justify-between mb-6">
                <Title level={3} className="mb-0 text-gray-800">
                    Tiện nghi phòng
                </Title>
                <Tag color="blue" className="rounded-full px-3 py-1 flex items-center">
                    <HeartOutlined className="mr-1" /> {amenities.length} tiện nghi
                </Tag>
            </div>

            <Row gutter={[16, 16]}>
                {formattedAmenities.map((amenity) => (
                    <Col xs={24} sm={12} md={8} lg={12} xl={8} key={amenity.key}>
                        <div className="flex items-center p-3 rounded-lg bg-gray-50 shadow-sm hover:shadow-md transition-all duration-200 cursor-default">
                            <div className="text-2xl mr-3 flex-shrink-0">
                                {amenity.icon}
                            </div>
                            <span className="text-sm font-medium leading-snug text-gray-700">
                                {amenity.name}
                            </span>
                        </div>
                    </Col>
                ))}
            </Row>

            {/* Show more/less button */}
            {amenities.length > 12 && (
                <div className="mt-6 flex justify-center">
                    <Button
                        type="link"
                        onClick={() => setShowAllAmenities(!showAllAmenities)}
                        className="flex items-center text-blue-600 hover:text-blue-800"
                    >
                        {showAllAmenities ? (
                            <>Thu gọn <UpOutlined className="ml-1" /></>
                        ) : (
                            <>Xem thêm tiện nghi <DownOutlined className="ml-1" /></>
                        )}
                    </Button>
                </div>
            )}
        </Card>
    );
};

export default RoomAmenities;
