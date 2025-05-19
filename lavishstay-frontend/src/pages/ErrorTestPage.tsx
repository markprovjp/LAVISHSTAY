import React from "react";
import { Button, Space, Card, Typography } from "antd";
import useErrorRedirect from "../hooks/useErrorRedirect";

const { Title, Paragraph } = Typography;

const ErrorTestPage: React.FC = () => {
  const { redirectTo404, redirectTo403, redirectTo500 } = useErrorRedirect();

  return (
    <div className="container mx-auto py-8 px-4">
      <Card className="mb-6">
        <Title level={3}>Trang Thử Nghiệm Lỗi</Title>
        <Paragraph>
          Sử dụng các nút bên dưới để xem các trang lỗi khác nhau trong ứng
          dụng. Trong thực tế, bạn sẽ chuyển hướng người dùng đến các trang này
          khi gặp lỗi tương ứng.
        </Paragraph>

        <Space>
          <Button danger onClick={redirectTo404}>
            Hiển thị lỗi 404
          </Button>
          <Button danger onClick={redirectTo403}>
            Hiển thị lỗi 403
          </Button>
          <Button danger onClick={redirectTo500}>
            Hiển thị lỗi 500
          </Button>
        </Space>
      </Card>
    </div>
  );
};

export default ErrorTestPage;
