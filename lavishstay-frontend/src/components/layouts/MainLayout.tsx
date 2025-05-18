import React from "react";
import { Layout } from "antd";
import { Outlet } from "react-router-dom";
import Navbar from "../Navbar";
import CustomFooter from "../CustomFooter";

const { Content } = Layout;

const MainLayout: React.FC = () => {
  return (
    <Layout className="min-h-screen">
      <Navbar />
      <Content className="pt-6 pb-12">
        <Outlet />
      </Content>
      <CustomFooter />
    </Layout>
  );
};

export default MainLayout;
