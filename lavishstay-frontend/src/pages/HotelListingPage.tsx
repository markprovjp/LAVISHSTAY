// src/pages/HotelListingPage.tsx
import React from "react";
import { useHotels } from "../hooks/useHotels";
import { Card, Row, Col, Spin, Alert, Rate, Button, Typography } from "antd";
import { useNavigate } from "react-router-dom";

const { Meta } = Card;
const { Title, Paragraph } = Typography;

const HotelListing: React.FC = () => {
  const navigate = useNavigate();
  const { data: hotels, isLoading, error } = useHotels();

  if (isLoading) {
    return (
      <div className="flex justify-center items-center h-96">
        <Spin size="large" />
      </div>
    );
  }

  if (error) {
    return (
      <Alert
        message="Error"
        description="Failed to load hotels. Please try again later."
        type="error"
        showIcon
      />
    );
  }

  return (
    <div className="container mx-auto px-4 py-8">
      <Title level={2} className="mb-6 text-center">
        Discover Our Luxury Hotels
      </Title>

      <Paragraph className="text-center mb-8 max-w-3xl mx-auto text-gray-600">
        Experience unparalleled luxury and comfort at our carefully curated
        selection of hotels. Each property offers exceptional service and
        amenities to make your stay unforgettable.
      </Paragraph>

      <Row gutter={[24, 24]}>
        {hotels?.map((hotel) => (
          <Col xs={24} sm={12} md={8} lg={6} key={hotel.id}>
            <Card
              hoverable
              cover={
                <div className="h-48 overflow-hidden">
                  <img
                    alt={hotel.name}
                    src={
                      hotel.imageUrl ||
                      "https://placehold.co/600x400?text=Hotel+Image"
                    }
                    className="w-full h-full object-cover"
                  />
                </div>
              }
              className="h-full flex flex-col"
            >
              <Meta
                title={hotel.name}
                description={
                  <div className="mb-2">
                    <div className="flex items-center mb-2">
                      <Rate
                        disabled
                        defaultValue={hotel.rating}
                        className="text-sm"
                      />
                      <span className="ml-2 text-gray-500">
                        ({hotel.rating})
                      </span>
                    </div>
                    <div className="text-sm mb-2 text-gray-500">
                      {hotel.location}
                    </div>
                    <div className="font-bold text-lg text-indigo-700">
                      ${hotel.price}/night
                    </div>
                  </div>
                }
              />
              <div className="mt-auto pt-4">
                <Button
                  type="primary"
                  block
                  onClick={() => navigate(`/hotels/${hotel.id}`)}
                >
                  View Details
                </Button>
              </div>
            </Card>
          </Col>
        ))}
      </Row>
    </div>
  );
};

export default HotelListing;
