import React from "react";
import { Layout, Card, Typography } from "antd";
import { Outlet, Link } from "react-router-dom";

const { Content } = Layout;
const { Title } = Typography;

const AuthLayout: React.FC = () => {
  return (
    <Layout className="min-h-screen bg-gray-50">
      <Content className="flex items-center justify-center p-4">
        <div className="w-full max-w-md">
          <div className="text-center mb-6">
            <Link to="/" className="inline-block">
              <Title level={2} className="text-primary m-0">
                LavishStay
              </Title>
            </Link>
          </div>

          <Card
            className="w-full shadow-lg rounded-lg overflow-hidden"
            bordered={false}
          >
            <div className="py-4">
              <Outlet />
            </div>
          </Card>

          <div className="text-center mt-6 text-gray-500">
            <p>
              © {new Date().getFullYear()} LavishStay. Tất cả quyền được bảo
              lưu.
            </p>
          </div>
        </div>
      </Content>
    </Layout>
  );
};

export default AuthLayout;
