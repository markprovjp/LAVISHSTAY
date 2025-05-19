import React, { useState, useEffect } from "react";
import { Link, useLocation } from "react-router-dom";
import {
  Layout,
  Menu,
  Button,
  Drawer,
  Dropdown,
  Avatar,
  Input,
  Badge,
  Space,
  Divider,
  Typography,
  ConfigProvider,
  theme,
} from "antd";
import {
  UserOutlined,
  SearchOutlined,
  MenuOutlined,
  BellOutlined,
  HeartOutlined,
  LogoutOutlined,
  BookOutlined,
  SettingOutlined,
  HomeOutlined,
  GlobalOutlined,
  ShoppingOutlined,
  PhoneOutlined,
} from "@ant-design/icons";
import ThemeToggle from "../ui/ThemeToggle";
import { useSelector } from "react-redux";
import { RootState } from "../../store";
import logoLight from "../../assets/images/logo-light.png";
import logoDark from "../../assets/images/logo-dark.png";

const { Header: AntHeader } = Layout;
const { Search } = Input;
const { Text } = Typography;

interface HeaderProps {
  transparent?: boolean;
}

const Header: React.FC<HeaderProps> = ({ transparent = false }) => {
  const location = useLocation();
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const { isDarkMode } = useSelector((state: RootState) => state.theme);
  const { isAuthenticated, user } = useSelector(
    (state: RootState) => state.auth
  );
  const { token } = theme.useToken(); // Lấy token từ theme

  // Sample notification data
  const notifications = [
    {
      id: 1,
      message: "Đặt phòng được xác nhận cho Dynasty Hotel",
      read: false,
      time: "10 vài phút trước",
    },
    {
      id: 2,
      message: "Ưu đãi đặc biệt: Giảm 25% vào cuối tuần",
      read: false,
      time: "1 giờ trước",
    },
    {
      id: 3,
      message: "Đánh giá của bạn đã được đăng",
      read: true,
      time: "1 ngày trước",
    },
  ];

  // Check if scrolled for transparent header effect
  useEffect(() => {
    const handleScroll = () => {
      if (window.scrollY > 10) {
        setScrolled(true);
      } else {
        setScrolled(false);
      }
    };

    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  // Phong cách tiêu đề động dựa trên cuộn và prop trong suốt
  const headerStyle = {
    position: "fixed" as "fixed",
    width: "100%",
    zIndex: 1000,
    background: transparent && !scrolled ? "transparent" : token.colorBgBase,
    boxShadow: scrolled ? "0 2px 8px rgba(0,0,0,0.1)" : "none",
    transition: "all 0.3s ease",
    backdropFilter: scrolled ? "blur(12px)" : "none",
    borderBottom: scrolled ? `1px solid ${token.colorBorderSecondary}` : "none",
  };

  // Navigation items
  const menuItems = [
    { key: "/", label: "Trang chủ", icon: <HomeOutlined /> },
    { key: "/destinations", label: "Điểm đến", icon: <GlobalOutlined /> },
    { key: "/hotels", label: "Khách sạn", icon: <ShoppingOutlined /> },
    { key: "/about", label: "Về chúng tôi", icon: null },
    // { key: "/contact", label: "Liên hệ", icon: <PhoneOutlined /> }, 
  ];

  // User menu dropdown
  const userMenu = (
    <Menu
      style={{
        borderRadius: token.borderRadius,
        background: token.colorBgContainer,
        boxShadow: token.boxShadowTertiary,
      }}
    >
      <Menu.Item key="profile" icon={<UserOutlined />}>
        <Link to="/profile">Hồ sơ cá nhân</Link>
      </Menu.Item>
      <Menu.Item key="bookings" icon={<BookOutlined />}>
        <Link to="/bookings">Đặt phòng của tôi</Link>
      </Menu.Item>
      <Menu.Item key="wishlist" icon={<HeartOutlined />}>
        <Link to="/wishlist">Danh sách yêu thích</Link>
      </Menu.Item>
      <Menu.Item key="settings" icon={<SettingOutlined />}>
        <Link to="/settings">Cài đặt</Link>
      </Menu.Item>
      <Menu.Divider />
      <Menu.Item key="logout" icon={<LogoutOutlined />}>
        Đăng xuất
      </Menu.Item>
    </Menu>
  );

  // Notification dropdown
  const notificationMenu = (
    <Menu
      style={{
        width: 320,
        borderRadius: token.borderRadius,
        background: token.colorBgContainer,
        boxShadow: token.boxShadowTertiary,
      }}
    >
      <div className="px-4 py-2 flex justify-between items-center">
        <Text strong>Thông báo</Text>
        <Link to="/notifications">
          <Text type="secondary" className="text-sm">
            Xem tất cả
          </Text>
        </Link>
      </div>
      <Divider style={{ margin: "0" }} />
      {notifications.map((notification) => (
        <Menu.Item
          key={notification.id}
          style={{
            background: !notification.read
              ? isDarkMode
                ? token.colorPrimary + "20"
                : token.colorPrimary + "10"
              : undefined,
          }}
        >
          <div>
            <div className="flex justify-between">
              <Text style={{ color: token.colorTextBase }}>
                {notification.message}
              </Text>
              {!notification.read && <Badge color={token.colorPrimary} />}
            </div>
            <Text type="secondary" className="text-xs">
              {notification.time}
            </Text>
          </div>
        </Menu.Item>
      ))}
    </Menu>
  );

  return (
    <ConfigProvider theme={{ token }}>
      <AntHeader
        style={headerStyle}
        className="flex items-center justify-between px-4 md:px-6 lg:px-8"
      >
        {/* Logo */}
        <div className="logo flex items-center h-16">
          <Link to="/" className="flex items-center h-full">
            <img
              src={isDarkMode ? logoDark : logoLight}
              alt="LavishStay"
              className="h-12 mr-3"
            />
          </Link>
        </div>

        {/* Desktop Menu */}
        <div className="hidden lg:flex flex-1 justify-center mx-8">
          <Menu
            mode="horizontal"
            selectedKeys={[location.pathname]}
            items={menuItems.map((item) => ({
              key: item.key,
              icon: item.icon,
              label: (
                <Link to={item.key}>
                  <span
                    style={{
                      fontFamily: token.fontFamily,
                      fontWeight: 500,
                      color:
                        transparent && !scrolled
                          ? "#ffffff"
                          : token.colorTextBase,
                    }}
                  >
                    {item.label}
                  </span>
                </Link>
              ),
            }))}
            style={{
              background: "transparent",
              borderBottom: "none",
              color:
                transparent && !scrolled ? "#ffffff" : token.colorTextBase,
              lineHeight: "64px",
            }}
            className="min-w-[400px] flex justify-center"
          />
        </div>

        {/* Desktop Right Menu */}
        <div className="hidden lg:flex items-center space-x-3">
          {/* Theme Toggle */}
          <ThemeToggle />

          {/* Notifications */}
          <Dropdown
            overlay={notificationMenu}
            trigger={["hover"]}
            placement="bottomRight"
          >
            <Badge
              count={notifications.filter((n) => !n.read).length}
              size="small"
              style={{ backgroundColor: token.colorPrimary }}
            >
              <Button
                type="text"
                icon={<BellOutlined style={{ fontSize: 15 }} />}
                style={{
                  color:
                    transparent && !scrolled
                      ? "#ffffff"
                      : token.colorTextBase,
                }}
                className="hover:bg-opacity-10 rounded-full w-5 h-5 flex items-center justify-center"
              />
            </Badge>
          </Dropdown>

          {/* User Menu */}
          {isAuthenticated ? (
            <Dropdown
              overlay={userMenu}
              trigger={["hover"]}
              placement="bottomRight"
            >
              <Space
                className="cursor-pointer hover:bg-opacity-10 rounded-full px-2 py-1"
                style={{
                  background:
                    transparent && !scrolled
                      ? "rgba(255,255,255,0.1)"
                      : token.colorPrimary + "10",
                }}
              >
                <Avatar
                  src={user?.avatar}
                  icon={!user?.avatar ? <UserOutlined /> : undefined}
                  style={{
                    backgroundColor: token.colorPrimary,
                    border: `1px solid ${token.colorBorderSecondary}`,
                  }}
                />
                <Text
                  style={{
                    fontFamily: token.fontFamily,
                    color:
                      transparent && !scrolled
                        ? "#ffffff"
                        : token.colorTextBase,
                  }}
                  className="hidden xl:inline"
                >
                  {user?.name || "Người dùng"}
                </Text>
              </Space>
            </Dropdown>
          ) : (
            <Link to="/login">
              <Button
                type="primary"
                style={{
                  borderRadius: token.borderRadius,
                  background: token.colorPrimary,
                  borderColor: token.colorPrimary,
                }}
                className="font-medium"
              >
                Đăng nhập
              </Button>
            </Link>
          )}
        </div>

        {/* Mobile Menu Button */}
        <div className="lg:hidden flex items-center space-x-3">
          <ThemeToggle />
          <Button
            type="text"
            icon={<MenuOutlined style={{ fontSize: "20px" }} />}
            onClick={() => setMobileMenuOpen(true)}
            style={{
              color: transparent && !scrolled ? "#ffffff" : token.colorTextBase,
            }}
            className="hover:bg-opacity-10 rounded-full w-10 h-10 flex items-center justify-center"
          />
        </div>

        {/* Mobile Menu Drawer */}
        <Drawer
          title={
            <Text strong style={{ color: token.colorTextBase }}>
              Menu
            </Text>
          }
          placement="right"
          onClose={() => setMobileMenuOpen(false)}
          open={mobileMenuOpen}
          width={300}
          styles={{
            header: { background: token.colorBgBase },
            body: { background: token.colorBgBase, padding: "16px" },
          }}
        >


          <Menu
            mode="vertical"
            selectedKeys={[location.pathname]}
            style={{ border: "none", background: token.colorBgBase }}
          >
            {menuItems.map((item) => (
              <Menu.Item
                key={item.key}
                icon={item.icon}
                style={{ color: token.colorTextBase }}
              >
                <Link
                  to={item.key}
                  onClick={() => setMobileMenuOpen(false)}
                  style={{ color: token.colorTextBase }}
                >
                  {item.label}
                </Link>
              </Menu.Item>
            ))}
          </Menu>

          <Divider />

          {isAuthenticated ? (
            <>
              <div className="flex items-center space-x-3 mb-6 px-4">
                <Avatar
                  src={user?.avatar}
                  icon={!user?.avatar ? <UserOutlined /> : undefined}
                  style={{
                    backgroundColor: token.colorPrimary,
                    border: `1px solid ${token.colorBorderSecondary}`,
                  }}
                />
                <div>
                  <Text
                    strong
                    style={{ color: token.colorTextBase, fontFamily: token.fontFamily }}
                  >
                    {user?.name || "Người dùng"}
                  </Text>
                  <Text style={{ color: token.colorTextSecondary }} className="text-xs">
                    {user?.email}
                  </Text>
                </div>
              </div>

              <Menu mode="vertical" style={{ border: "none", background: token.colorBgBase }}>
                <Menu.Item key="profile" icon={<UserOutlined />}>
                  <Link
                    to="/profile"
                    onClick={() => setMobileMenuOpen(false)}
                    style={{ color: token.colorTextBase }}
                  >
                    Hồ sơ cá nhân
                  </Link>
                </Menu.Item>
                <Menu.Item key="bookings" icon={<BookOutlined />}>
                  <Link
                    to="/bookings"
                    onClick={() => setMobileMenuOpen(false)}
                    style={{ color: token.colorTextBase }}
                  >
                    Đặt phòng của tôi
                  </Link>
                </Menu.Item>
                <Menu.Item key="wishlist" icon={<HeartOutlined />}>
                  <Link
                    to="/wishlist"
                    onClick={() => setMobileMenuOpen(false)}
                    style={{ color: token.colorTextBase }}
                  >
                    Danh sách yêu thích
                  </Link>
                </Menu.Item>
                <Menu.Item key="logout" icon={<LogoutOutlined />}>
                  Đăng xuất
                </Menu.Item>
              </Menu>
            </>
          ) : (
            <div className="space-y-3 px-4">
              <Link to="/login" onClick={() => setMobileMenuOpen(false)}>
                <Button
                  type="primary"
                  block
                  style={{
                    borderRadius: token.borderRadius,
                    background: token.colorPrimary,
                    borderColor: token.colorPrimary,
                  }}
                >
                  Đăng nhập
                </Button>
              </Link>
            </div>
          )}
        </Drawer>
      </AntHeader>
    </ConfigProvider>
  );
};

export default Header;