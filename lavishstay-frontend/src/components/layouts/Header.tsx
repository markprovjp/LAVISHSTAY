import React, { useState, useEffect } from "react";
import { Link, useLocation } from "react-router-dom";
import {
  Layout,
  Menu,
  Button,
  Drawer,
  Dropdown,
  Avatar,
  Badge,
  Space,
  Divider,
  Typography,
  ConfigProvider,
  theme,
  message,
} from "antd";
import {
  UserOutlined,
  MenuOutlined,
  BellOutlined,
  HeartOutlined,
  LogoutOutlined,
  BookOutlined,
  SettingOutlined,
  HomeOutlined,
  ShoppingOutlined,
} from "@ant-design/icons";
import ThemeToggle from "../ui/ThemeToggle";
import LanguageSwitcher from "../ui/LanguageSwitcher";
import RainbowButton from "../ui/RainbowButton";
import { useSelector, useDispatch } from "react-redux";
import { RootState } from "../../store";
import { logout } from "../../store/slices/authSlice";
import authService from "../../services/authService";
import { useTranslation } from "react-i18next";
import logoLight from "../../assets/images/logo-light.png";
import logoDark from "../../assets/images/logo-dark.png";
import AuthModal from "../auth/AuthModal";
const { Header: AntHeader } = Layout;
const { Text } = Typography;

interface HeaderProps {
  transparent?: boolean;
}

interface MenuItem {
  key: string;
  label: string;
  icon?: React.ReactNode;
  children?: MenuItem[];
}

const Header: React.FC<HeaderProps> = ({ transparent = false }) => {
  const location = useLocation();
  const dispatch = useDispatch();
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const [authModalOpen, setAuthModalOpen] = useState(false);
  const { isDarkMode } = useSelector((state: RootState) => state.theme);
  const { isAuthenticated, user } = useSelector(
    (state: RootState) => state.auth
  );
  const { token } = theme.useToken(); // Lấy token từ theme
  const { t } = useTranslation();



  // Xử lý đăng xuất
  const handleLogout = async () => {
    try {
      await authService.logout();
      dispatch(logout());
      message.success("Đăng xuất thành công!");
    } catch (error) {
      console.error("Logout error:", error);
      // Vẫn logout trên client side ngay cả khi API có lỗi
      dispatch(logout());
      message.success("Đăng xuất thành công!");
    }
  };

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
  };  // Navigation items
  const menuItems: MenuItem[] = [
    { key: "/", label: t("header.home"), icon: <HomeOutlined /> },
    { key: "/about", label: "Về chúng tôi", icon: null },
    // Reception menu with dropdown
    {
      key: "/reception",
      label: "🏨 Lễ tân",
      icon: null,
    },
    // Development only - Auth test page
    ...(process.env.NODE_ENV === 'development' ? [
      { key: "/auth-test", label: "🔐 Auth Test", icon: null }
    ] : []),
  ];

  // User menu dropdown
  const userMenu = (
    <Menu
      style={{
        borderRadius: token.borderRadius,
        background: token.colorBgContainer,
        boxShadow: token.boxShadowTertiary,
      }}
      onClick={({ key }) => {
        if (key === 'logout') {
          handleLogout();
        }
      }}
    >
      <Menu.Item key="profile" icon={<UserOutlined />}>
        <Link to="/profile">{t("user.profile")}</Link>
      </Menu.Item>
      <Menu.Item key="bookings" icon={<BookOutlined />}>
        <Link to="/bookings">{t("user.bookings")}</Link>
      </Menu.Item>
      <Menu.Item key="wishlist" icon={<HeartOutlined />}>
        <Link to="/wishlist">{t("user.wishlist")}</Link>
      </Menu.Item>
      <Menu.Item key="settings" icon={<SettingOutlined />}>
        <Link to="/settings">{t("user.settings")}</Link>
      </Menu.Item>
      <Menu.Divider />
      <Menu.Item key="logout" icon={<LogoutOutlined />}>
        {t("user.logout")}
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
        <Text strong>{t("user.notifications")}</Text>
        <Link to="/notifications">
          <Text type="secondary" className="text-sm">
            {t("user.viewAll")}
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
        className="flex items-center justify-between px-4 md:px-6 lg:px-10"
      >
        {/* Logo */}
        <div className="logo flex items-center h-16">
          <Link to="/" className="flex items-center h-full">
            {" "}
            <img
              src={isDarkMode ? logoDark : logoLight}
              alt="LavishStay"
              className="h-10 mr-3"
            />
          </Link>
        </div>        {/* Desktop Menu */}
        <div className="hidden lg:flex flex-1 justify-center mx-8">
          <div className="flex items-center space-x-8">
            {menuItems.map((item) => (
              item.children ? (
                <Dropdown
                  key={item.key}
                  overlay={
                    <Menu>
                      {item.children.map((child) => (
                        <Menu.Item key={child.key}>
                          <Link to={child.key} style={{ color: token.colorTextBase }}>
                            {child.label}
                          </Link>
                        </Menu.Item>
                      ))}
                    </Menu>
                  }
                  trigger={["hover"]}
                  placement="bottomCenter"
                >
                  <span
                    className={`flex items-center space-x-1 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-opacity-10 cursor-pointer ${location.pathname.startsWith(item.key)
                      ? `bg-opacity-20 font-semibold`
                      : 'font-medium'
                      }`}
                    style={{
                      color: transparent && !scrolled ? "#ffffff" : token.colorTextBase,
                    }}
                  >
                    {item.icon && (
                      <span style={{ fontSize: "16px" }}>{item.icon}</span>
                    )}
                    <span style={{ fontFamily: token.fontFamily }}>
                      {item.label}
                    </span>
                  </span>
                </Dropdown>
              ) : (
                <Link
                  key={item.key}
                  to={item.key}
                  className={`flex items-center space-x-1 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-opacity-10 ${location.pathname === item.key
                    ? `bg-opacity-20 font-semibold`
                    : 'font-medium'
                    }`}
                  style={{
                    color: transparent && !scrolled ? "#ffffff" : token.colorTextBase,
                  }}
                >
                  {item.icon && (
                    <span style={{ fontSize: "16px" }}>{item.icon}</span>
                  )}
                  <span style={{ fontFamily: token.fontFamily }}>
                    {item.label}
                  </span>
                </Link>
              )
            ))}
          </div>
        </div>{" "}        {/* Desktop Right Menu */}
        <div className="hidden lg:flex items-center space-x-3">
          {/* Theme Toggle */}
          <ThemeToggle />
          {/* Language Switcher */}
          <LanguageSwitcher mode="icon-only" />
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
                icon={<BellOutlined style={{ fontSize: 18 }} />}
                style={{
                  color:
                    transparent && !scrolled ? "#ffffff" : token.colorTextBase,
                }}
                className="hover:bg-opacity-10 rounded-lg w-10 h-10 flex items-center justify-center"
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
              <div
                className="cursor-pointer flex items-center space-x-2 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-black hover:bg-opacity-5"
                style={{
                  background: "transparent",
                }}
              >
                <Avatar
                  src={user?.avatar}
                  icon={!user?.avatar ? <UserOutlined /> : undefined}
                  size={32}
                  style={{
                    backgroundColor: token.colorPrimary,
                  }}
                />
                <Text
                  style={{
                    fontFamily: token.fontFamily,
                    color:
                      transparent && !scrolled
                        ? "#ffffff"
                        : token.colorTextBase,
                    fontWeight: 500,
                  }}
                  className="hidden xl:inline"
                >
                  {user?.name || "Người dùng"}
                </Text>
              </div>
            </Dropdown>) : (
            <RainbowButton
              onClick={() => setAuthModalOpen(true)}
              size="medium"
            >
              Đăng nhập
            </RainbowButton>
          )}
        </div>{" "}
        {/* Mobile Menu Button */}
        <div className="lg:hidden flex items-center space-x-3">
          <ThemeToggle />
          <LanguageSwitcher mode="icon-only" />
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
        >          <Menu
            mode="vertical"
            selectedKeys={[location.pathname]}
            style={{ border: "none", background: token.colorBgBase }}
            items={menuItems.map((item) => {
              if (item.children) {
                return {
                  key: item.key,
                  label: item.label,
                  icon: item.icon,
                  children: item.children.map((child) => ({
                    key: child.key,
                    label: (
                      <Link
                        to={child.key}
                        onClick={() => setMobileMenuOpen(false)}
                        style={{ color: token.colorTextBase }}
                      >
                        {child.label}
                      </Link>
                    ),
                  })),
                };
              }
              return {
                key: item.key,
                label: (
                  <Link
                    to={item.key}
                    onClick={() => setMobileMenuOpen(false)}
                    style={{ color: token.colorTextBase }}
                  >
                    {item.label}
                  </Link>
                ),
                icon: item.icon,
              };
            })}
          />

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
                    style={{
                      color: token.colorTextBase,
                      fontFamily: token.fontFamily,
                    }}
                  >
                    {user?.name || "Người dùng"}
                  </Text>
                  <Text
                    style={{ color: token.colorTextSecondary }}
                    className="text-xs"
                  >
                    {user?.email}
                  </Text>
                </div>
              </div>              <Menu
                mode="vertical"
                style={{ border: "none", background: token.colorBgBase }}
                onClick={({ key }) => {
                  if (key === 'logout') {
                    handleLogout();
                    setMobileMenuOpen(false);
                  }
                }}
              >
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
            </>) : (
            <div className="space-y-3 px-4">
              <RainbowButton
                onClick={() => {
                  setAuthModalOpen(true);
                  setMobileMenuOpen(false);
                }}
                className="w-full"
                size="medium"
              >
                Đăng nhập
              </RainbowButton>
            </div>
          )}
        </Drawer>
      </AntHeader>
      <AuthModal open={authModalOpen} onClose={() => setAuthModalOpen(false)} />
    </ConfigProvider>
  );
};

export default Header;
