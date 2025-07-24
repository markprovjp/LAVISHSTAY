import React, { useState } from "react";
import { Card, Typography, Row, Col, Button, Tag } from "antd";
import { StarOutlined, UpOutlined, DownOutlined } from "@ant-design/icons"; // Thêm import StarOutlined
import { getIcon } from "../../constants/Icons"; // Điều chỉnh đường dẫn tùy theo cấu trúc project

const { Title } = Typography;

interface Amenity {
  id: number;
  name: string;
  icon: string;
  icon_lib?: string;
  category: string;
  description?: string;
}

interface AmenitiesProps {
  amenities: Amenity[];
}

const RoomAmenities: React.FC<AmenitiesProps> = ({ amenities }) => {
  const [showAllAmenities, setShowAllAmenities] = useState(false);

  const displayAmenities = showAllAmenities ? amenities : amenities.slice(0, 12);

  return (
    <Card className="shadow-sm rounded-lg border-0">
      <div className="flex items-center justify-between mb-6">
        <Title level={3} className="mb-0 text-gray-800">
          Tiện nghi phòng
        </Title>
        <Tag color="blue" className="rounded-full px-3 py-1 flex items-center">
          <StarOutlined className="mr-1" /> {amenities.length} tiện nghi
        </Tag>
      </div>

      <Row gutter={[16, 16]}>
        {displayAmenities.map((amenity) => (
          <Col xs={24} sm={12} md={8} lg={12} xl={8} key={amenity.id}>
            <div className="flex items-center p-3 rounded-lg bg-gray-50 shadow-sm hover:shadow-md transition-all duration-200 cursor-default">
              <div className="text-2xl mr-3 flex-shrink-0">
                {getIcon(amenity.icon, amenity.icon_lib)}
              </div>
              <span className="text-sm font-medium leading-snug text-gray-700">
                {amenity.name}
              </span>
            </div>
          </Col>
        ))}
      </Row>

      {amenities.length > 12 && (
        <div className="mt-6 flex justify-center">
          <Button
            type="link"
            onClick={() => setShowAllAmenities(!showAllAmenities)}
            className="flex items-center text-blue-600 hover:text-blue-800"
          >
            {showAllAmenities ? (
              <>
                Thu gọn <UpOutlined className="ml-1" />
              </>
            ) : (
              <>
                Xem thêm tiện nghi <DownOutlined className="ml-1" />
              </>
            )}
          </Button>
        </div>
      )}
    </Card>
  );
};

export default RoomAmenities;