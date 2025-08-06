import React, { useState } from "react";
import { Layout, Menu, Button, theme, Avatar, Dropdown } from "antd";
import {
  MenuFoldOutlined,
  MenuUnfoldOutlined,
  UserOutlined,
  HomeOutlined,
  BookOutlined,
  LogoutOutlined,
  BellOutlined,
  SettingOutlined,
} from "@ant-design/icons";
import { Link, Outlet, useLocation, useNavigate } from "react-router-dom";
import { useDispatch, useSelector } from "react-redux";
import { RootState } from "../../store";
import { logout } from "../../store/slices/authSlice";

const { Header, Sider, Content } = Layout;

const DashboardLayout: React.FC = () => {
  const [collapsed, setCollapsed] = useState(false);
  const location = useLocation();
  const navigate = useNavigate();
  const dispatch = useDispatch();
  const user = useSelector((state: RootState) => state.auth.user);

  const { token } = theme.useToken();

  const handleLogout = () => {
    dispatch(logout());
    navigate("/");
  };

  const userMenuItems = [
    {
      key: "profile",
      label: <Link to="/profile">Thông tin cá nhân</Link>,
      icon: <UserOutlined />,
    },
    {
      key: "settings",
      label: <Link to="/settings">Cài đặt</Link>,
      icon: <SettingOutlined />,
    },
    {
      type: "divider",
    },
    {
      key: "logout",
      label: "Đăng xuất",
      icon: <LogoutOutlined />,
      danger: true,
      onClick: handleLogout,
    },
  ];

  return (
    <Layout>
      <Sider
        trigger={null}
        collapsible
        collapsed={collapsed}
        className="min-h-screen"
        style={{
          background: token.colorBgContainer,
          boxShadow: "0 2px 8px rgba(0, 0, 0, 0.15)",
        }}
        width={260}
      >
        <div className="p-4 flex items-center justify-center">
          <Link to="/" className="text-xl font-bold text-primary">
            {collapsed ? "LS" : "LavishStay"}
          </Link>
        </div>
        <Menu
          mode="inline"
          selectedKeys={[location.pathname]}
          style={{ border: "none" }}
          items={[
            {
              key: "/dashboard",
              icon: <HomeOutlined />,
              label: <Link to="/dashboard">Trang chính</Link>,
            },
            {
              key: "/bookings",
              icon: <BookOutlined />,
              label: <Link to="/bookings">Đặt phòng</Link>,
            },
            {
              key: "/profile",
              icon: <UserOutlined />,
              label: <Link to="/profile">Hồ sơ</Link>,
            },
          ]}
        />
        <div className="absolute bottom-0 p-4 w-full">
          <Button
            type="primary"
            icon={<LogoutOutlined />}
            onClick={handleLogout}
            block
          >
            {collapsed ? "" : "Đăng xuất"}
          </Button>
        </div>
      </Sider>
      <Layout>
        <Header
          style={{
            padding: "0 16px",
            background: token.colorBgContainer,
            display: "flex",
            alignItems: "center",
            justifyContent: "space-between",
            boxShadow: "0 1px 4px rgba(0, 0, 0, 0.1)",
          }}
        >
          <Button
            type="text"
            icon={collapsed ? <MenuUnfoldOutlined /> : <MenuFoldOutlined />}
            onClick={() => setCollapsed(!collapsed)}
          />

          <div className="flex items-center gap-4">
            <Button shape="circle" icon={<BellOutlined />} />
            <Dropdown menu={{ items: userMenuItems }} placement="bottomRight">
              <div className="flex items-center gap-2 cursor-pointer">
                <Avatar icon={<UserOutlined />} src={user?.avatar} />
                {!collapsed && <span>{user?.name}</span>}
              </div>
            </Dropdown>
          </div>
        </Header>
        <Content
          style={{
            margin: "24px 16px",
            padding: 24,
            background: token.colorBgContainer,
            borderRadius: token.borderRadius,
          }}
        >
          <Outlet />
        </Content>
      </Layout>
    </Layout>
  );
};

export default DashboardLayout;
