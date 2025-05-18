import React from "react";
import { Layout } from "antd";
import { Outlet } from "react-router-dom";
import Navbar from "../Navbar";
import CustomFooter from "../CustomFooter";
import { useSelector } from "react-redux";
import { RootState } from "../../store";

const { Content } = Layout;

/**
 * Layout mặc định cho trang web công khai
 * Bao gồm Navbar, Content và Footer
 */
const DefaultLayout: React.FC = () => {
  const { isDarkMode } = useSelector((state: RootState) => state.theme);

  return (
    <Layout
      className={`min-h-screen ${isDarkMode ? "bg-gray-900" : "bg-white"}`}
    >
      <Navbar />
      <Content className="pt-6 pb-12">
        <Outlet />
      </Content>
      <CustomFooter />
    </Layout>
  );
};

export default DefaultLayout;
