import React from "react";
import { Layout } from "antd";
import { Outlet } from "react-router-dom";
import Navbar from "../Navbar";
import Footer from "../layouts/Footer";

const { Content } = Layout;

const MainLayout: React.FC = () => {
  return (
    <Layout className="min-h-screen">
      <Navbar />
      <Content className="pt-6 pb-12">
        <Outlet />
      </Content>
      <Footer />
    </Layout>
  );
};

export default MainLayout;
