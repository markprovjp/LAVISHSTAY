import React from "react";
import {
  Layout,
  Row,
  Col,
  Typography,
  Space,
  Divider,
  Input,
  Button,
  List,
} from "antd";
import { Link } from "react-router-dom";
import {
  FacebookOutlined,
  TwitterOutlined,
  InstagramOutlined,
  LinkedinOutlined,
  MailOutlined,
  PhoneOutlined,
  HomeOutlined,
  GlobalOutlined,
  SafetyOutlined,
  CreditCardOutlined,
  CustomerServiceOutlined,
} from "@ant-design/icons";

const { Footer: AntFooter } = Layout;
const { Title, Text, Paragraph } = Typography;

const Footer: React.FC = () => {
  // Quick links for the footer
  const quickLinks = [
    { title: "Về chúng tôi", link: "/about" },
    { title: "Dịch vụ", link: "/services" },
    { title: "Blogs", link: "/blogs" },
    { title: "Điều khoản sử dụng", link: "/terms" },
    { title: "Chính sách riêng tư", link: "/privacy" },
    { title: "Liên hệ", link: "/contact" },
  ];

  // Destinations list
  const destinations = [
    { title: "Hà Nội", link: "/destinations/ha-noi" },
    { title: "TP. Hồ Chí Minh", link: "/destinations/ho-chi-minh" },
    { title: "Đà Nẵng", link: "/destinations/da-nang" },
    { title: "Nha Trang", link: "/destinations/nha-trang" },
    { title: "Phú Quốc", link: "/destinations/phu-quoc" },
    { title: "Đà Lạt", link: "/destinations/da-lat" },
  ];

  // Hotel categories
  const categories = [
    { title: "Khách sạn 5 sao", link: "/categories/5-star" },
    { title: "Resort & Spa", link: "/categories/resort-spa" },
    { title: "Biệt thự", link: "/categories/villas" },
    { title: "Căn hộ cao cấp", link: "/categories/apartments" },
    { title: "Khách sạn boutique", link: "/categories/boutique" },
    { title: "Homestay", link: "/categories/homestay" },
  ];

  // Features list
  const features = [
    { icon: <SafetyOutlined />, text: "Đảm bảo giá tốt nhất" },
    { icon: <CreditCardOutlined />, text: "Thanh toán an toàn" },
    { icon: <CustomerServiceOutlined />, text: "Hỗ trợ 24/7" },
    { icon: <GlobalOutlined />, text: "Đối tác trên toàn quốc" },
  ];

  return (
    <AntFooter className="bg-gray-900 text-white pt-16 pb-8 px-4 md:px-6 lg:px-8">
      <div className="container mx-auto">
        {/* Main Footer Content */}
        <Row gutter={[32, 32]} className="mb-12">
          {/* Company Info */}
          <Col xs={24} md={8} lg={6}>
            <Title level={4} className="text-white mb-4 font-bevietnam">
              LavishStay
            </Title>
            <Paragraph className="text-gray-300 mb-4">
              Khám phá những kỳ nghỉ sang trọng và độc đáo tại những địa điểm
              tuyệt vời nhất Việt Nam. Chúng tôi cam kết mang đến trải nghiệm
              lưu trú tuyệt vời với giá cả hợp lý.
            </Paragraph>
            <Space size="middle">
              <Button
                type="text"
                icon={<FacebookOutlined />}
                className="text-blue-400 hover:text-blue-300"
              />
              <Button
                type="text"
                icon={<TwitterOutlined />}
                className="text-blue-400 hover:text-blue-300"
              />
              <Button
                type="text"
                icon={<InstagramOutlined />}
                className="text-pink-400 hover:text-pink-300"
              />
              <Button
                type="text"
                icon={<LinkedinOutlined />}
                className="text-blue-400 hover:text-blue-300"
              />
            </Space>
          </Col>

          {/* Quick Links */}
          <Col xs={24} sm={12} md={8} lg={6}>
            <Title level={5} className="text-white mb-4 font-bevietnam">
              Liên kết nhanh
            </Title>
            <List
              dataSource={quickLinks}
              renderItem={(item) => (
                <List.Item className="border-0 p-0 mb-2">
                  <Link
                    to={item.link}
                    className="text-gray-300 hover:text-primary"
                  >
                    {item.title}
                  </Link>
                </List.Item>
              )}
              split={false}
            />
          </Col>

          {/* Top Destinations */}
          <Col xs={24} sm={12} md={8} lg={6}>
            <Title level={5} className="text-white mb-4 font-bevietnam">
              Điểm đến hàng đầu
            </Title>
            <List
              dataSource={destinations}
              renderItem={(item) => (
                <List.Item className="border-0 p-0 mb-2">
                  <Link
                    to={item.link}
                    className="text-gray-300 hover:text-primary"
                  >
                    {item.title}
                  </Link>
                </List.Item>
              )}
              split={false}
            />
          </Col>

          {/* Newsletter */}
          <Col xs={24} md={12} lg={6}>
            <Title level={5} className="text-white mb-4 font-bevietnam">
              Nhận thông tin khuyến mãi
            </Title>
            <Paragraph className="text-gray-300 mb-4">
              Đăng ký nhận thông tin về các ưu đãi đặc biệt và gói kỳ nghỉ mới.
            </Paragraph>
            <Space.Compact style={{ width: "100%" }}>
              <Input
                placeholder="Email của bạn"
                prefix={<MailOutlined className="text-gray-400" />}
              />
              <Button type="primary">Đăng ký</Button>
            </Space.Compact>
          </Col>
        </Row>

        {/* Features Banner */}
        <div className="bg-gray-800 rounded-lg p-6 mb-12">
          <Row gutter={[20, 20]} justify="space-around" align="middle">
            {features.map((feature, index) => (
              <Col key={index} xs={24} sm={12} md={6}>
                <div className="flex items-center justify-center sm:justify-start">
                  <div className="text-primary text-xl mr-3">
                    {feature.icon}
                  </div>
                  <Text className="text-white">{feature.text}</Text>
                </div>
              </Col>
            ))}
          </Row>
        </div>

        {/* Contact Information */}
        <Row className="mb-8">
          <Col span={24}>
            <Divider className="bg-gray-700" />
            <Row gutter={[30, 20]} className="text-gray-300">
              <Col xs={24} sm={8}>
                <Space>
                  <PhoneOutlined />
                  <Text className="text-gray-300">+84 (0) 123 456 789</Text>
                </Space>
              </Col>
              <Col xs={24} sm={8}>
                <Space>
                  <MailOutlined />
                  <Text className="text-gray-300">info@lavishstay.com</Text>
                </Space>
              </Col>
              <Col xs={24} sm={8}>
                <Space>
                  <HomeOutlined />
                  <Text className="text-gray-300">
                    123 Đường ABC, Quận 1, TP. Hồ Chí Minh
                  </Text>
                </Space>
              </Col>
            </Row>
          </Col>
        </Row>

        {/* Copyright */}
        <Divider className="bg-gray-700" />
        <Row
          justify="space-between"
          align="middle"
          className="text-gray-400 text-sm"
        >
          <Col>
            &copy; {new Date().getFullYear()} LavishStay. Bản quyền thuộc về
            chúng tôi.
          </Col>
          <Col>
            <Space split={<Divider type="vertical" className="bg-gray-600" />}>
              <Link to="/terms" className="text-gray-400 hover:text-primary">
                Điều khoản
              </Link>
              <Link to="/privacy" className="text-gray-400 hover:text-primary">
                Quyền riêng tư
              </Link>
              <Link to="/cookies" className="text-gray-400 hover:text-primary">
                Cookies
              </Link>
            </Space>
          </Col>
        </Row>
      </div>
    </AntFooter>
  );
};

export default Footer;
