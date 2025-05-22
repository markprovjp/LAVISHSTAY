// src/pages/HotelDetailsPage.tsx
import React from "react";
import { useParams, useNavigate } from "react-router-dom";
import { useHotel } from "../hooks/useHotels";
import useCartStore from "../store/useCartStore";
import {
  Spin,
  Alert,
  Typography,
  Divider,
  Row,
  Col,
  Card,
  Rate,
  Button,
  Image,
  Tabs,
  Tag,
  Space,
  Descriptions,
  message,
} from "antd";
import {
  EnvironmentOutlined,
  WifiOutlined,
  CarOutlined,
  CoffeeOutlined,
  CheckOutlined,
  ClockCircleOutlined,
} from "@ant-design/icons";

const { Title, Paragraph, Text } = Typography;
const { TabPane } = Tabs;

const HotelDetailsPage: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const addItem = useCartStore((state) => state.addItem);

  const { data: hotel, isLoading, error } = useHotel(id!);

  if (isLoading) {
    return (
      <div className="flex justify-center items-center h-96">
        <Spin size="large" />
      </div>
    );
  }

  if (error || !hotel) {
    return (
      <div className="container mx-auto px-4 py-8">
        <Alert
          message="Hotel Not Found"
          description="We couldn't find the hotel you're looking for. Please try another one."
          type="error"
          showIcon
          action={
            <Button type="primary" onClick={() => navigate("/hotels")}>
              Back to All Hotels
            </Button>
          }
        />
      </div>
    );
  }

  return (
    <div className="container mx-auto px-4 py-8">
      {/* Hotel Header */}
      <div className="mb-8">
        <Row gutter={[24, 24]} align="middle">
          <Col lg={16} md={24}>
            <Title level={2}>{hotel.name}</Title>
            <Space className="mb-2">
              <Rate disabled defaultValue={hotel.rating} />
              <Text className="text-gray-500">({hotel.rating} out of 5)</Text>
            </Space>
            <Paragraph className="flex items-center">
              <EnvironmentOutlined className="mr-2" />
              {hotel.location}
            </Paragraph>
          </Col>{" "}
          <Col lg={8} md={24} className="text-right">
            <div className="p-6 bg-white shadow-md rounded-lg">
              <Title level={4} className="text-indigo-700">
                ${hotel.price}
              </Title>
              <Text className="block text-gray-500 mb-4">per night</Text>
              <Button
                type="primary"
                size="large"
                block
                onClick={() => {
                  addItem({
                    id: hotel.id,
                    name: hotel.name,
                    price: hotel.price,
                    imageUrl: hotel.imageUrl,
                  });
                  message.success("Added to cart!");
                }}
              >
                Book Now
              </Button>
            </div>
          </Col>
        </Row>
      </div>

      {/* Hotel Images */}
      <Card className="mb-8 overflow-hidden">
        <Image
          alt={hotel.name}
          src={
            hotel.imageUrl || "https://placehold.co/1200x600?text=Hotel+View"
          }
          className="w-full object-cover rounded"
          style={{ height: "500px" }}
        />
      </Card>

      {/* Hotel Details Tabs */}
      <Card className="mb-8">
        <Tabs defaultActiveKey="overview">
          <TabPane tab="Overview" key="overview">
            <Title level={4}>About This Hotel</Title>
            <Paragraph className="text-gray-700">
              {hotel.description ||
                `Nestled in the heart of ${hotel.location}, this luxurious hotel offers an exceptional 
                blend of comfort, convenience, and impeccable service. Guests can enjoy state-of-the-art 
                amenities, gourmet dining options, and beautifully appointed accommodations designed to 
                provide the ultimate relaxation experience.`}
            </Paragraph>

            <Divider />

            <Title level={4}>Amenities</Title>
            <Row gutter={[16, 16]}>
              <Col xs={12} sm={8} md={6}>
                <div className="flex items-center">
                  <WifiOutlined className="mr-2 text-indigo-600" />
                  <Text>Free WiFi</Text>
                </div>
              </Col>
              <Col xs={12} sm={8} md={6}>
                <div className="flex items-center">
                  <CoffeeOutlined className="mr-2 text-indigo-600" />
                  <Text>Breakfast</Text>
                </div>
              </Col>
              <Col xs={12} sm={8} md={6}>
                <div className="flex items-center">
                  <CarOutlined className="mr-2 text-indigo-600" />
                  <Text>Free Parking</Text>
                </div>
              </Col>
              <Col xs={12} sm={8} md={6}>
                <div className="flex items-center">
                  <CheckOutlined className="mr-2 text-indigo-600" />
                  <Text>Air Conditioning</Text>
                </div>
              </Col>
            </Row>
          </TabPane>

          <TabPane tab="Rooms" key="rooms">
            <Title level={4}>Available Room Types</Title>
            <Row gutter={[16, 16]}>
              {[
                "Standard Room",
                "Deluxe Room",
                "Executive Suite",
                "Presidential Suite",
              ].map((room, index) => (
                <Col xs={24} sm={12} md={8} lg={6} key={index}>
                  <Card
                    hoverable
                    cover={
                      <img
                        alt={room}
                        src={`https://placehold.co/300x200?text=${room.replace(
                          " ",
                          "+"
                        )}`}
                      />
                    }
                  >
                    <Card.Meta
                      title={room}
                      description={`From $${(
                        hotel.price *
                        (1 + index * 0.3)
                      ).toFixed(0)}/night`}
                    />
                    <Button type="link" className="mt-3 p-0">
                      View Details
                    </Button>
                  </Card>
                </Col>
              ))}
            </Row>
          </TabPane>

          <TabPane tab="Location" key="location">
            <Title level={4}>Hotel Location</Title>
            <Paragraph>
              {hotel.name} is located at {hotel.location}. The area is known for
              its convenience to major attractions and local transportation.
            </Paragraph>

            <div className="bg-gray-200 rounded-lg h-80 flex items-center justify-center my-4">
              <Text className="text-gray-500">
                Map view will be available soon
              </Text>
            </div>

            <Descriptions
              title="Nearby Attractions"
              bordered
              column={{ xxl: 4, xl: 3, lg: 3, md: 2, sm: 1, xs: 1 }}
            >
              <Descriptions.Item label="Airport">
                15 km (20 min drive)
              </Descriptions.Item>
              <Descriptions.Item label="City Center">
                3 km (10 min drive)
              </Descriptions.Item>
              <Descriptions.Item label="Beach">
                1 km (5 min walk)
              </Descriptions.Item>
              <Descriptions.Item label="Shopping Mall">
                2.5 km (8 min drive)
              </Descriptions.Item>
            </Descriptions>
          </TabPane>

          <TabPane tab="Reviews" key="reviews">
            <Title level={4}>Guest Reviews</Title>
            <Paragraph className="mb-4">
              <Rate disabled defaultValue={hotel.rating} />{" "}
              <Text className="ml-2">{hotel.rating} out of 5</Text>
            </Paragraph>

            <Alert
              message="Review Feature Coming Soon"
              description="We're currently working on implementing a guest review system. Please check back soon!"
              type="info"
              showIcon
              className="mb-4"
            />
          </TabPane>
        </Tabs>
      </Card>
      <div className="flex justify-between mt-8">
        <Button onClick={() => navigate("/hotels")}>Back to All Hotels</Button>
        <Button
          type="primary"
          onClick={() => {
            addItem({
              id: hotel.id,
              name: hotel.name,
              price: hotel.price,
              imageUrl: hotel.imageUrl,
            });
            message.success("Added to cart!");
          }}
        >
          Book This Hotel
        </Button>
      </div>
    </div>
  );
};

export default HotelDetailsPage;
