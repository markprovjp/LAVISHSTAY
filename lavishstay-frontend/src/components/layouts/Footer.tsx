import React from "react";
import {
  Layout,
  Row,
  Col,
  Typography,
  Space,
  Divider,
  Button,
  List,
  ConfigProvider,
  theme,
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
  SafetyOutlined,
  CreditCardOutlined,
  CustomerServiceOutlined,
  GlobalOutlined,
} from "@ant-design/icons";
import { RootState } from "../../store";
import { useSelector } from "react-redux";
import logoLight from "../../assets/images/logo-light.png";
import logoDark from "../../assets/images/logo-dark.png";

const { Footer: AntFooter } = Layout;
const { Title, Text, Paragraph } = Typography;

const Footer: React.FC = () => {
  const { token } = theme.useToken();
  const { isDarkMode } = useSelector((state: RootState) => state.theme);
  // Quick links for the footer
  const quickLinks = [
    { title: "Về chúng tôi", link: "/about" },
    { title: "Dịch vụ", link: "/services" },
    { title: "Nhà hàng", link: "/dining" },
    { title: "Spa & Wellness", link: "/spa" },
    { title: "Khuyến mãi", link: "/offers" },
    { title: "Liên hệ", link: "/contact" },
  ];

  // Room categories
  const categories = [
    { title: "Phòng Deluxe", link: "/rooms/deluxe" },
    { title: "Phòng Premium", link: "/rooms/premium" },
    { title: "Phòng The Level", link: "/rooms/thelevel" },
    { title: "Phòng Grand", link: "/rooms/grand" },
    { title: "Phòng Junior Suite", link: "/rooms/junior-suite" },
    { title: "Phòng Presidential", link: "/rooms/presidential" },
  ];
  // Features list
  const features = [
    { icon: <SafetyOutlined />, text: "Đảm bảo giá tốt nhất" },
    { icon: <CreditCardOutlined />, text: "The Level Benefits" },
    { icon: <CustomerServiceOutlined />, text: "Dịch vụ 5 sao 24/7" },
    { icon: <GlobalOutlined />, text: "Đặt phòng toàn cầu" },
  ];

  return (
    <ConfigProvider theme={{ token }}>
      <AntFooter
        style={{
          background: token.colorBgBase,
          borderTop: `1px solid ${token.colorBorderSecondary}`,
          color: token.colorTextBase,
        }}
        className="pt-12 pb-4 px-4 md:px-6 lg:px-8"
      >
        <div className="container mx-auto">
          {/* Main Footer Content */}
          <Row gutter={[32, 32]} className="mb-12">
            {/* Company Info */}
            <Col xs={24} md={12} lg={10}>
              {" "}
              <div className="flex items-center mb-4">
                <Link to="/" className="flex items-center">
                  {" "}
                  <img
                    src={isDarkMode ? logoDark : logoLight}
                    alt="LavishStay"
                    className="h-10 mr-3"
                  />
                </Link>
              </div>
              <Paragraph
                style={{ color: token.colorTextSecondary }}
                className="mb-6"
              >
                Trải nghiệm kỳ nghỉ sang trọng tại LavishStay Thanh Hoá - kết
                hợp hoàn hảo giữa phong cách Tây Ban Nha đặc trưng của
                LavishStay Hotels International và dịch vụ hiếu khách Việt Nam
                từ LavishStay, mang đến không gian nghỉ dưỡng tinh tế và đẳng
                cấp.
              </Paragraph>
              <Space size="middle">
                <Button
                  type="text"
                  icon={<FacebookOutlined style={{ fontSize: "18px" }} />}
                  style={{
                    color: token.colorPrimary,
                    backgroundColor: token.colorPrimary + "10",
                    borderRadius: "50%",
                    width: "40px",
                    height: "40px",
                  }}
                  className="flex items-center justify-center"
                />
                <Button
                  type="text"
                  icon={<TwitterOutlined style={{ fontSize: "18px" }} />}
                  style={{
                    color: token.colorPrimary,
                    backgroundColor: token.colorPrimary + "10",
                    borderRadius: "50%",
                    width: "40px",
                    height: "40px",
                  }}
                  className="flex items-center justify-center"
                />
                <Button
                  type="text"
                  icon={<InstagramOutlined style={{ fontSize: "18px" }} />}
                  style={{
                    color: token.colorPrimary,
                    backgroundColor: token.colorPrimary + "10",
                    borderRadius: "50%",
                    width: "40px",
                    height: "40px",
                  }}
                  className="flex items-center justify-center"
                />
                <Button
                  type="text"
                  icon={<LinkedinOutlined style={{ fontSize: "18px" }} />}
                  style={{
                    color: token.colorPrimary,
                    backgroundColor: token.colorPrimary + "10",
                    borderRadius: "50%",
                    width: "40px",
                    height: "40px",
                  }}
                  className="flex items-center justify-center"
                />
              </Space>
            </Col>

            {/* Quick Links */}
            <Col xs={24} sm={12} md={6} lg={7}>
              <Title
                level={5}
                style={{
                  color: token.colorTextBase,
                  fontFamily: token.fontFamily,
                }}
                className="mb-4 font-medium"
              >
                Liên kết nhanh
              </Title>
              <List
                dataSource={quickLinks}
                renderItem={(item) => (
                  <List.Item
                    style={{ border: "none", padding: "4px 0" }}
                    className="mb-1"
                  >
                    <Link
                      to={item.link}
                      style={{ color: token.colorTextSecondary }}
                      className="hover:text-primary transition-colors"
                    >
                      {item.title}
                    </Link>
                  </List.Item>
                )}
                split={false}
              />
            </Col>

            {/* Categories */}
            <Col xs={24} sm={12} md={6} lg={7}>
              {" "}
              <Title
                level={5}
                style={{
                  color: token.colorTextBase,
                  fontFamily: token.fontFamily,
                }}
                className="mb-4 font-medium"
              >
                Loại phòng
              </Title>
              <List
                dataSource={categories}
                renderItem={(item) => (
                  <List.Item
                    style={{ border: "none", padding: "4px 0" }}
                    className="mb-1"
                  >
                    <Link
                      to={item.link}
                      style={{ color: token.colorTextSecondary }}
                      className="hover:text-primary transition-colors"
                    >
                      {item.title}
                    </Link>
                  </List.Item>
                )}
                split={false}
              />
            </Col>
          </Row>

          {/* Features Banner */}
          <div
            style={{
              background: token.colorBgContainer,
              borderRadius: token.borderRadius,
              boxShadow: token.boxShadowTertiary,
              border: `1px solid ${token.colorBorderSecondary}`,
            }}
            className="p-6 mb-10"
          >
            <Row gutter={[20, 20]} justify="space-around" align="middle">
              {features.map((feature, index) => (
                <Col key={index} xs={24} sm={12} md={6}>
                  <div className="flex items-center justify-center sm:justify-start">
                    <div
                      style={{ color: token.colorPrimary }}
                      className="text-xl mr-3"
                    >
                      {feature.icon}
                    </div>
                    <Text style={{ color: token.colorTextBase }}>
                      {feature.text}
                    </Text>
                  </div>
                </Col>
              ))}
            </Row>
          </div>

          {/* Contact Information */}
          <Row className="mb-6">
            <Col span={24}>
              <Divider style={{ background: token.colorBorderSecondary }} />
              <Row gutter={[30, 20]}>
                {" "}
                <Col xs={24} sm={8}>
                  <Space>
                    <PhoneOutlined style={{ color: token.colorPrimary }} />
                    <Text style={{ color: token.colorTextSecondary }}>
                      +84 (0) 237 3699 699
                    </Text>
                  </Space>
                </Col>
                <Col xs={24} sm={8}>
                  <Space>
                    <MailOutlined style={{ color: token.colorPrimary }} />
                    <Text style={{ color: token.colorTextSecondary }}>
                      quyenjpn@gmail.com
                    </Text>
                  </Space>
                </Col>
                <Col xs={24} sm={8}>
                  <Space>
                    <HomeOutlined style={{ color: token.colorPrimary }} />
                    <Text style={{ color: token.colorTextSecondary }}>
                      Số 27 Trần Phú, Phường Điện Biên, Thành Phố Thanh Hóa ,
                      Việt Nam
                    </Text>
                  </Space>
                </Col>
              </Row>
            </Col>
          </Row>

          {/* Copyright */}
          <Divider style={{ background: token.colorBorderSecondary }} />
          <Row
            justify="space-between"
            align="middle"
            style={{ color: token.colorTextSecondary }}
            className="text-sm"
          >
            <Col>
              {" "}
              &copy; {new Date().getFullYear()} LavishStay Thanh Hoá. Bản quyền
              thuộc về LavishStay Hotels International.
            </Col>
            <Col>
              <Space
                split={
                  <Divider
                    type="vertical"
                    style={{ background: token.colorBorderSecondary }}
                  />
                }
              >
                <Link
                  to="/terms"
                  style={{ color: token.colorTextSecondary }}
                  className="hover:text-primary"
                >
                  Điều khoản
                </Link>
                <Link
                  to="/privacy"
                  style={{ color: token.colorTextSecondary }}
                  className="hover:text-primary"
                >
                  Quyền riêng tư
                </Link>
                <Link
                  to="/cookies"
                  style={{ color: token.colorTextSecondary }}
                  className="hover:text-primary"
                >
                  Cookies
                </Link>
              </Space>
            </Col>
          </Row>
        </div>
      </AntFooter>
    </ConfigProvider>
  );
};

export default Footer;
