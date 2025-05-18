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
    // padding: "0 24px",
    background:
      transparent && !scrolled
        ? "transparent"
        : isDarkMode
        ? "#141414"
        : "#ffffff",
    boxShadow: scrolled ? "0 2px 8px rgba(0,0,0,0.1)" : "none",
    transition: "all 0.3s ease",
    backdropFilter: scrolled ? "blur(10px)" : "none",
  };

  // Navigation items
  const menuItems = [
    { key: "/", label: "Trang chủ", icon: <HomeOutlined /> },
    { key: "/destinations", label: "Điểm đến", icon: <GlobalOutlined /> },
    { key: "/hotels", label: "Khách sạn", icon: <ShoppingOutlined /> },
    { key: "/about", label: "Về chúng tôi", icon: null },
    { key: "/contact", label: "Liên hệ", icon: <PhoneOutlined /> },
  ];

  // User menu dropdown
  const userMenu = (
    <Menu>
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
    <Menu className="notification-dropdown" style={{ width: 320 }}>
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
          className={!notification.read ? "bg-blue-50 dark:bg-blue-900/20" : ""}
        >
          <div>
            <div className="flex justify-between">
              <Text>{notification.message}</Text>
              {!notification.read && <Badge color="blue" />}
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
    <AntHeader
      style={headerStyle}
      className="flex items-center justify-between px-4 md:px-8"
    >
      {/* Logo */}
      <div className="logo h-16 flex items-center">
        <Link to="/" className="flex items-center h-full">
          <img
            src={isDarkMode ? logoDark : logoLight}
            alt="LavishStay"
            className="h-10 mr-2"
          />
          {/* <span className={`text-xl font-bold font-bevietnam ${transparent && !scrolled ? 'text-white' : ''}`}>
            LavishStay
          </span> */}
        </Link>
      </div>      {/* Desktop Menu */}
      <div className="hidden lg:flex flex-1 justify-center">
        <Menu
          mode="horizontal"
          selectedKeys={[location.pathname]}
          items={menuItems.map((item) => ({
            key: item.key,
            icon: item.icon,
            label: (
              <Link to={item.key}>
                <span className="font-bevietnam font-medium">{item.label}</span>
              </Link>
            ),
          }))}
          style={{
            background: "transparent",
            borderBottom: "none",
            color: transparent && !scrolled ? "#fff" : undefined,
          }}
          className="min-w-[500px] flex justify-center"
        />
      </div>

      {/* Desktop Right Menu */}
      <div className="hidden lg:flex items-center space-x-4">
        {/* Search */}
        {/* <Search 
          placeholder="Tìm kiếm khách sạn..." 
          style={{ width: 200 }}
          className="header-search"
        /> */}

        {/* Theme Toggle */}
        <ThemeToggle />

        {/* Notifications */}        <Dropdown
          overlay={notificationMenu}
          trigger={["hover"]}
          placement="bottomRight"
          overlayClassName="custom-dropdown"
        >
          <Badge
            count={notifications.filter((n) => !n.read).length}
            size="small"
          >
            <Button
              type="text"
              icon={<BellOutlined />}
              className={transparent && !scrolled ? "text-white" : ""}
            />
          </Badge>
        </Dropdown>        {/* User Menu */}
        {isAuthenticated ? (
          <Dropdown
            overlay={userMenu}
            trigger={["hover"]}
            placement="bottomRight"
            overlayClassName="custom-dropdown"
          >
            <Button type="text" className="flex items-center hover:bg-opacity-20">
              <Space>
                <Avatar
                  src={user?.avatar}
                  icon={!user?.avatar ? <UserOutlined /> : undefined}
                />
                <span
                  className={`hidden xl:inline font-bevietnam ${
                    transparent && !scrolled ? "text-white" : ""
                  }`}
                >
                  {user?.name || "Người dùng"}
                </span>
              </Space>
            </Button>
          </Dropdown>
        ) : (
          <Space>
            <Link to="/login">
              <Button
                type={transparent && !scrolled ? "default" : "primary"}
                ghost={transparent && !scrolled}
                className="font-bevietnam"
              >
                Đăng nhập
              </Button>
            </Link>
            <Link to="/register">
              <Button type="primary" className="font-bevietnam">
                Đăng ký
              </Button>
            </Link>
          </Space>
        )}
      </div>      {/* Mobile Menu Button */}
      <div className="lg:hidden flex items-center space-x-3">
        <ThemeToggle />
        <Button
          type="text"
          icon={<MenuOutlined style={{ fontSize: '20px' }} />}
          onClick={() => setMobileMenuOpen(true)}
          className={`${transparent && !scrolled ? "text-white" : ""} hover:bg-opacity-20 rounded-full flex items-center justify-center w-10 h-10`}
        />
      </div>

      {/* Mobile Menu Drawer */}
      <Drawer
        title="Menu"
        placement="right"
        onClose={() => setMobileMenuOpen(false)}
        open={mobileMenuOpen}
        width={300}
      >
        <div className="mb-6">
          <Search placeholder="Tìm kiếm..." enterButton />
        </div>

        <Menu
          mode="vertical"
          selectedKeys={[location.pathname]}
          style={{ border: "none" }}
        >
          {menuItems.map((item) => (
            <Menu.Item key={item.key} icon={item.icon}>
              <Link to={item.key} onClick={() => setMobileMenuOpen(false)}>
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
              />
              <div>
                <div className="font-medium">{user?.name || "Người dùng"}</div>
                <div className="text-xs text-gray-500">{user?.email}</div>
              </div>
            </div>

            <Menu mode="vertical" style={{ border: "none" }}>
              <Menu.Item key="profile" icon={<UserOutlined />}>
                <Link to="/profile" onClick={() => setMobileMenuOpen(false)}>
                  Hồ sơ cá nhân
                </Link>
              </Menu.Item>
              <Menu.Item key="bookings" icon={<BookOutlined />}>
                <Link to="/bookings" onClick={() => setMobileMenuOpen(false)}>
                  Đặt phòng của tôi
                </Link>
              </Menu.Item>
              <Menu.Item key="wishlist" icon={<HeartOutlined />}>
                <Link to="/wishlist" onClick={() => setMobileMenuOpen(false)}>
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
              <Button type="primary" block>
                Đăng nhập
              </Button>
            </Link>
            <Link to="/register" onClick={() => setMobileMenuOpen(false)}>
              <Button block>Đăng ký</Button>
            </Link>
          </div>
        )}
      </Drawer>
    </AntHeader>
  );
};

export default Header;
