import React from "react";
import { Typography, Row, Col, Card, Statistic } from "antd";
import {
  UserOutlined,
  HeartOutlined,
  ClockCircleOutlined,
} from "@ant-design/icons";
import { useSelector } from "react-redux";
import { RootState } from "../../store";

const { Title } = Typography;

const Dashboard: React.FC = () => {
  const user = useSelector((state: RootState) => state.auth.user);

  return (
    <div>
      <Title level={2}>Bảng điều khiển</Title>
      <Title level={5} className="text-gray-500 font-normal mb-6">
        Xin chào, {user?.name || "Khách hàng"}!
      </Title>

      <Row gutter={[16, 16]}>
        <Col xs={24} sm={8}>
          <Card bordered={false} className="shadow-sm">
            <Statistic
              title="Tổng lượt đặt phòng"
              value={0}
              prefix={<ClockCircleOutlined />}
            />
          </Card>
        </Col>
        <Col xs={24} sm={8}>
          <Card bordered={false} className="shadow-sm">
            <Statistic
              title="Đã hoàn thành"
              value={0}
              prefix={<UserOutlined />}
            />
          </Card>
        </Col>
        <Col xs={24} sm={8}>
          <Card bordered={false} className="shadow-sm">
            <Statistic title="Yêu thích" value={0} prefix={<HeartOutlined />} />
          </Card>
        </Col>
      </Row>

      <Title level={4} className="mt-8 mb-4">
        Đặt phòng đang chờ
      </Title>
      <Card bordered={false} className="shadow-sm">
        <div className="text-center py-8 text-gray-500">
          Bạn chưa có đặt phòng nào. Hãy khám phá các lựa chọn phòng mới ngay
          bây giờ!
        </div>
      </Card>

      <Title level={4} className="mt-8 mb-4">
        Gợi ý cho bạn
      </Title>
      <Card bordered={false} className="shadow-sm">
        <div className="text-center py-8 text-gray-500">
          Quay lại sau để xem các gợi ý được cá nhân hóa dành cho bạn.
        </div>
      </Card>
    </div>
  );
};

export default Dashboard;
