import React from "react";
import { Link, useNavigate } from "react-router-dom";
import { Menu, Layout, Button, Avatar, Dropdown } from "antd";
import {
  UserOutlined,
  LogoutOutlined,
  HomeOutlined,
  InfoCircleOutlined,
  LoginOutlined,
} from "@ant-design/icons";
import useStore from "@/store/useStore";
import type { MenuProps } from "antd";

const { Header } = Layout;

const Navbar: React.FC = () => {
  const { isAuthenticated, user, logout } = useStore();
  const navigate = useNavigate();

  const handleLogout = () => {
    logout();
    navigate("/");
  };

  const items: MenuProps["items"] = [
    {
      key: "1",
      label: <Link to="/">Trang chủ</Link>,
      icon: <HomeOutlined />,
    },
    {
      key: "2",
      label: <Link to="/about">Giới thiệu</Link>,
      icon: <InfoCircleOutlined />,
    },
  ];

  const userDropdownItems: MenuProps["items"] = [
    {
      key: "1",
      label: <Link to="/profile">Tài khoản của tôi</Link>,
      icon: <UserOutlined />,
    },
    {
      key: "2",
      label: <span onClick={handleLogout}>Đăng xuất</span>,
      icon: <LogoutOutlined />,
      danger: true,
    },
  ];

  return (
    <Header className="p-0 bg-blue-600">
      <div className="flex justify-between items-center h-full max-w-[1200px] mx-auto px-4">
        <div className="flex items-center">
          <Link to="/" className="flex items-center text-white mr-6">
            <span className="font-bold text-xl">LavishStay</span>
          </Link>
          <Menu
            mode="horizontal"
            items={items}
            className="bg-blue-600 text-white border-0"
            theme="dark"
          />
        </div>

        <div className="flex items-center">
          {isAuthenticated ? (
            <Dropdown
              menu={{ items: userDropdownItems }}
              placement="bottomRight"
            >
              <div className="flex items-center cursor-pointer text-white">
                <Avatar icon={<UserOutlined />} className="mr-2" />
                <span>{user?.name || "Người dùng"}</span>
              </div>
            </Dropdown>
          ) : (
            <Button
              type="primary"
              icon={<LoginOutlined />}
              onClick={() => navigate("/login")}
              className="bg-green-500 hover:bg-green-600 border-0"
            >
              Đăng nhập
            </Button>
          )}
        </div>
      </div>
    </Header>
  );
};

export default Navbar;
