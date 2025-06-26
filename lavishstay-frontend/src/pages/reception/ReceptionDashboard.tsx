import React from 'react';
import { Card, Row, Col, Typography, Button, Statistic, Space } from 'antd';
import { 
  HomeOutlined, 
  BookOutlined, 
  TeamOutlined,
  CalendarOutlined,
  DollarOutlined,
  ClockCircleOutlined
} from '@ant-design/icons';
import { Link } from 'react-router-dom';

const { Title, Text } = Typography;

const ReceptionDashboard: React.FC = () => {
  // Mock data for dashboard
  const stats = {
    totalRooms: 120,
    occupiedRooms: 85,
    checkInsToday: 15,
    checkOutsToday: 12,
    revenue: 2500000,
    pendingBookings: 8  
  };

  return (
    <div className="p-6">
      <Title level={2} className="mb-6">
         Bảng điều khiển Lễ tân
      </Title>

      {/* Statistics Cards */}
      <Row gutter={[24, 24]} className="mb-8">
        <Col xs={24} sm={12} lg={8}>
          <Card>
            <Statistic
              title="Tổng số phòng"
              value={stats.totalRooms}
              prefix={<HomeOutlined />}
              valueStyle={{ color: '#3f8600' }}
            />
          </Card>
        </Col>
        <Col xs={24} sm={12} lg={8}>
          <Card>
            <Statistic
              title="Phòng đã đặt"
              value={stats.occupiedRooms}
              suffix={`/ ${stats.totalRooms}`}
              prefix={<BookOutlined />}
              valueStyle={{ color: '#cf1322' }}
            />
          </Card>
        </Col>
        <Col xs={24} sm={12} lg={8}>
          <Card>
            <Statistic
              title="Check-in hôm nay"
              value={stats.checkInsToday}
              prefix={<TeamOutlined />}
              valueStyle={{ color: '#1890ff' }}
            />
          </Card>
        </Col>
        <Col xs={24} sm={12} lg={8}>
          <Card>
            <Statistic
              title="Check-out hôm nay"
              value={stats.checkOutsToday}
              prefix={<CalendarOutlined />}
              valueStyle={{ color: '#722ed1' }}
            />
          </Card>
        </Col>
        <Col xs={24} sm={12} lg={8}>
          <Card>
            <Statistic
              title="Doanh thu hôm nay"
              value={stats.revenue}
              prefix={<DollarOutlined />}
              suffix="VNĐ"
              valueStyle={{ color: '#52c41a' }}
            />
          </Card>
        </Col>
        <Col xs={24} sm={12} lg={8}>
          <Card>
            <Statistic
              title="Đặt phòng chờ xử lý"
              value={stats.pendingBookings}
              prefix={<ClockCircleOutlined />}
              valueStyle={{ color: '#fa8c16' }}
            />
          </Card>
        </Col>
      </Row>


      {/* Today's Schedule */}
      <Row gutter={[24, 24]}>
        <Col xs={24} lg={12}>
          <Card title="Lịch trình hôm nay" extra={<ClockCircleOutlined />}>
            <Space direction="vertical" className="w-full">
              <div className="flex justify-between items-center p-2 bg-blue-50 rounded">
                <Text>Check-in: Nguyễn Văn A</Text>
                <Text type="secondary">09:00</Text>
              </div>
              <div className="flex justify-between items-center p-2 bg-green-50 rounded">
                <Text>Check-out: Trần Thị B</Text>
                <Text type="secondary">11:00</Text>
              </div>
              <div className="flex justify-between items-center p-2 bg-orange-50 rounded">
                <Text>Đặt phòng mới: Lê Văn C</Text>
                <Text type="secondary">14:00</Text>
              </div>
            </Space>
          </Card>
        </Col>
        <Col xs={24} lg={12}>
          <Card title="Thông báo quan trọng">
            <Space direction="vertical" className="w-full">
              <div className="p-2 bg-red-50 rounded border-l-4 border-red-400">
                <Text strong>Khẩn cấp:</Text>
                <Text> Phòng 205 cần bảo trì</Text>
              </div>
              <div className="p-2 bg-yellow-50 rounded border-l-4 border-yellow-400">
                <Text strong>Chú ý:</Text>
                <Text> Khách VIP check-in 15:00</Text>
              </div>
              <div className="p-2 bg-blue-50 rounded border-l-4 border-blue-400">
                <Text strong>Thông tin:</Text>
                <Text> Họp nhân viên 17:00</Text>
              </div>
            </Space>
          </Card>
        </Col>
      </Row>
    </div>
  );
};

export default ReceptionDashboard;
