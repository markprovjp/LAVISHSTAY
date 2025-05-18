import React from "react";
import {
  Layout,
  Typography,
  Row,
  Col,
  Space,
  Divider,
  Input,
  Button,
} from "antd";
import { Link } from "react-router-dom";
import {
  EnvironmentOutlined,
  PhoneOutlined,
  MailOutlined,
  FacebookOutlined,
  InstagramOutlined,
  TwitterOutlined,
  YoutubeOutlined,
  SendOutlined,
} from "@ant-design/icons";

const { Footer } = Layout;
const { Title, Text, Paragraph } = Typography;

interface CustomFooterProps {
  className?: string;
}

const CustomFooter: React.FC<CustomFooterProps> = ({ className = "" }) => {
  const currentYear = new Date().getFullYear();

  return (
    <Footer className={`p-0 bg-blue-600 text-white ${className}`}>
      <div className="max-w-7xl mx-auto px-4 py-12">
        <Row gutter={[48, 32]}>
          {/* Company Info */}
          <Col xs={24} sm={24} md={8}>
            <div className="mb-6">
              <Title
                level={3}
                className="text-white font-bevietnam font-bold m-0"
              >
                LavishStay
              </Title>
              <div className="w-12 h-1 bg-blue-300 mt-2 mb-4"></div>
            </div>

            <Paragraph className="text-blue-100 font-bevietnam mb-4">
              Chúng tôi cung cấp những trải nghiệm lưu trú sang trọng và độc đáo
              tại những địa điểm tuyệt vời nhất trên khắp Việt Nam.
            </Paragraph>

            <Space
              direction="vertical"
              className="text-blue-100 font-bevietnam"
            >
              <Space>
                <EnvironmentOutlined />
                <Text className="text-blue-100">
                  123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh
                </Text>
              </Space>
              <Space>
                <PhoneOutlined />
                <Text className="text-blue-100">+84 (0) 28 1234 5678</Text>
              </Space>
              <Space>
                <MailOutlined />
                <Text className="text-blue-100">info@lavishstay.com</Text>
              </Space>
            </Space>
          </Col>

          {/* Quick Links */}
          <Col xs={24} sm={12} md={5}>
            <Title
              level={4}
              className="text-white font-bevietnam font-semibold"
            >
              Liên kết nhanh
            </Title>
            <ul className="list-none pl-0 font-bevietnam">
              <li className="mb-2">
                <Link
                  to="/"
                  className="text-blue-100 hover:text-white transition-colors"
                >
                  Trang chủ
                </Link>
              </li>
              <li className="mb-2">
                <Link
                  to="/about"
                  className="text-blue-100 hover:text-white transition-colors"
                >
                  Giới thiệu
                </Link>
              </li>
              <li className="mb-2">
                <Link
                  to="/hotels"
                  className="text-blue-100 hover:text-white transition-colors"
                >
                  Khách sạn
                </Link>
              </li>
              <li className="mb-2">
                <Link
                  to="/promotions"
                  className="text-blue-100 hover:text-white transition-colors"
                >
                  Khuyến mãi
                </Link>
              </li>
              <li className="mb-2">
                <Link
                  to="/contact"
                  className="text-blue-100 hover:text-white transition-colors"
                >
                  Liên hệ
                </Link>
              </li>
            </ul>
          </Col>

          {/* Support */}
          <Col xs={24} sm={12} md={5}>
            <Title
              level={4}
              className="text-white font-bevietnam font-semibold"
            >
              Hỗ trợ
            </Title>
            <ul className="list-none pl-0 font-bevietnam">
              <li className="mb-2">
                <Link
                  to="/faqs"
                  className="text-blue-100 hover:text-white transition-colors"
                >
                  FAQs
                </Link>
              </li>
              <li className="mb-2">
                <Link
                  to="/terms"
                  className="text-blue-100 hover:text-white transition-colors"
                >
                  Điều khoản sử dụng
                </Link>
              </li>
              <li className="mb-2">
                <Link
                  to="/privacy"
                  className="text-blue-100 hover:text-white transition-colors"
                >
                  Chính sách bảo mật
                </Link>
              </li>
              <li className="mb-2">
                <Link
                  to="/refund"
                  className="text-blue-100 hover:text-white transition-colors"
                >
                  Chính sách hoàn tiền
                </Link>
              </li>
              <li className="mb-2">
                <Link
                  to="/help"
                  className="text-blue-100 hover:text-white transition-colors"
                >
                  Trung tâm trợ giúp
                </Link>
              </li>
            </ul>
          </Col>

          {/* Newsletter */}
          <Col xs={24} md={6}>
            <Title
              level={4}
              className="text-white font-bevietnam font-semibold"
            >
              Đăng ký nhận tin
            </Title>
            <Paragraph className="text-blue-100 font-bevietnam mb-4">
              Cập nhật tin tức mới nhất và ưu đãi đặc biệt
            </Paragraph>

            <div className="flex">
              <Input placeholder="Email của bạn" className="mr-2 rounded-l" />
              <Button
                type="primary"
                icon={<SendOutlined />}
                className="bg-green-500 hover:bg-green-600 border-0 rounded-r"
              />
            </div>

            <div className="mt-6">
              <Title
                level={5}
                className="text-white font-bevietnam font-semibold mb-3"
              >
                Kết nối với chúng tôi
              </Title>
              <Space size="large">
                <a
                  href="#"
                  className="text-blue-100 hover:text-white text-xl transition-colors"
                >
                  <FacebookOutlined />
                </a>
                <a
                  href="#"
                  className="text-blue-100 hover:text-white text-xl transition-colors"
                >
                  <InstagramOutlined />
                </a>
                <a
                  href="#"
                  className="text-blue-100 hover:text-white text-xl transition-colors"
                >
                  <TwitterOutlined />
                </a>
                <a
                  href="#"
                  className="text-blue-100 hover:text-white text-xl transition-colors"
                >
                  <YoutubeOutlined />
                </a>
              </Space>
            </div>
          </Col>
        </Row>

        <Divider className="bg-blue-400 opacity-30 mt-8 mb-6" />

        <div className="text-center font-bevietnam text-blue-200">
          <p>© {currentYear} LavishStay. Tất cả các quyền được bảo lưu.</p>
        </div>
      </div>
    </Footer>
  );
};

export default CustomFooter;
