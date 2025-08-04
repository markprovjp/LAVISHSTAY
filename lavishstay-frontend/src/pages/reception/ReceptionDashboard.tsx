
import React, { useEffect, useState } from 'react';
import { Card, Row, Col, Typography, Statistic, Space, Progress, Timeline, Table, Tag, Avatar, Divider, ProgressProps } from 'antd';
import { Area, Column, Pie, Line } from '@ant-design/plots';
import {
  HomeOutlined,
  BookOutlined,
  TeamOutlined,
  CalendarOutlined,
  DollarOutlined,
  ClockCircleOutlined,
  PieChartOutlined,
  CheckCircleOutlined,
  WarningOutlined,
  InfoCircleOutlined
} from '@ant-design/icons';
import axiosInstance from '../../config/axios';
const { Title, Text } = Typography;

const ReceptionDashboard: React.FC = () => {
  // State cho số liệu tổng quan
  const [stats, setStats] = useState<any>({});
  const [loading, setLoading] = useState(true);
  const [schedule, setSchedule] = useState<any[]>([]);
  const [notifications, setNotifications] = useState<any[]>([]);
  const [revenueTrend, setRevenueTrend] = useState<any[]>([]);
  const [roomStatus, setRoomStatus] = useState<any[]>([]);

  useEffect(() => {
    // Gọi API lấy số liệu tổng quan, lịch trình, thông báo, doanh thu, trạng thái phòng
    const fetchData = async () => {
      setLoading(true);
      try {
        // Ví dụ endpoint, cần chỉnh lại cho đúng backend
        const [statsRes, scheduleRes, notificationsRes, revenueRes, roomStatusRes] = await Promise.all([
          axiosInstance.get('/reception/overview'),
          axiosInstance.get('/reception/schedule'),
          axiosInstance.get('/reception/notifications'),
          axiosInstance.get('/reception/revenue-trend'),
          axiosInstance.get('/reception/room-status'),
        ]);
        setStats(statsRes.data);
        setSchedule(scheduleRes.data);
        setNotifications(notificationsRes.data);
        setRevenueTrend(revenueRes.data);
        setRoomStatus(roomStatusRes.data);
      } catch (err) {
        // fallback nếu lỗi
        setStats({
          totalRooms: 120,
          occupiedRooms: 85,
          checkInsToday: 15,
          checkOutsToday: 12,
          revenue: 2500000,
          pendingBookings: 8,
          activityRate: 78,
        });
        setSchedule([
          { type: 'checkin', name: 'Nguyễn Văn A', time: '09:00' },
          { type: 'checkout', name: 'Trần Thị B', time: '11:00' },
          { type: 'booking', name: 'Lê Văn C', time: '14:00' },
        ]);
        setNotifications([
          { type: 'urgent', message: 'Phòng 205 cần bảo trì' },
          { type: 'vip', message: 'Khách VIP check-in 15:00' },
          { type: 'info', message: 'Họp nhân viên 17:00' },
        ]);
        setRevenueTrend([
          { month: '1', value: 200 },
          { month: '2', value: 800 },
          { month: '3', value: 600 },
          { month: '4', value: 900 },
          { month: '5', value: 1200 },
          { month: '6', value: 800 },
          { month: '7', value: 400 },
          { month: '8', value: 350 },
          { month: '9', value: 900 },
          { month: '10', value: 850 },
          { month: '11', value: 600 },
          { month: '12', value: 700 },
        ]);
        setRoomStatus([
          { status: 'available', count: 35 },
          { status: 'occupied', count: 85 },
        ]);
      }
      setLoading(false);
    };
    fetchData();
  }, []);

  // Columns cho bảng trạng thái phòng
  const roomStatusColumns = [
    {
      title: 'Trạng thái', dataIndex: 'status', key: 'status', render: (text: string) => {
        if (text === 'available') return <Tag color="green">Còn trống</Tag>;
        if (text === 'occupied') return <Tag color="red">Đã đặt</Tag>;
        return <Tag>{text}</Tag>;
      }
    },
    { title: 'Số lượng', dataIndex: 'count', key: 'count' },
  ];
const twoColors: ProgressProps['strokeColor'] = {
  '0%': '#108ee9',
  '100%': '#87d068',
};
  // Timeline cho lịch trình hôm nay
  const scheduleTimeline = (
    <Timeline
      mode="alternate"
      items={schedule.map((item, idx) => {
        let color = 'blue';
        let dot = null;
        let label = '';
        if (item.type === 'checkin') {
          color = 'blue';
          dot = <CheckCircleOutlined style={{ fontSize: '16px', color: '#1890ff' }} />;
          label = 'Check-in';
        } else if (item.type === 'checkout') {
          color = 'green';
          dot = <CalendarOutlined style={{ fontSize: '16px', color: '#52c41a' }} />;
          label = 'Check-out';
        } else if (item.type === 'booking') {
          color = 'orange';
          dot = <BookOutlined style={{ fontSize: '16px', color: '#faad14' }} />;
          label = 'Đặt phòng mới';
        } else {
          color = 'gray';
          dot = <ClockCircleOutlined style={{ fontSize: '16px', color: '#888' }} />;
          label = item.type;
        }
        return {
          color,
          dot,
          children: (
            <span>
              <Text strong>{label}:</Text> {item.name} <Text type="secondary">{item.time}</Text>
            </span>
          ),
        };
      })}
    />
  );

  // Thông báo quan trọng
  const notificationList = (
    <Space direction="vertical" className="w-full">
      {notifications.map((item, idx) => (
        <div
          key={idx}
          className={`p-2 rounded border-l-4 ${item.type === 'urgent' ? 'bg-red-50 border-red-400' : item.type === 'vip' ? 'bg-yellow-50 border-yellow-400' : 'bg-blue-50 border-blue-400'}`}
        >
          <Text strong>{item.type === 'urgent' ? 'Khẩn cấp:' : item.type === 'vip' ? 'Chú ý:' : 'Thông tin:'}</Text>
          <Text> {item.message}</Text>
        </div>
      ))}
    </Space>
  );

  return (
    <div style={{
      padding: '32px 0',
    }}>
      <div style={{ padding: '0 32px' }}>
        <Card
          style={{
            borderRadius: 24,
            boxShadow: '0 8px 32px rgba(24,144,255,0.12)',
            marginBottom: 32,
          }}
          bodyStyle={{ padding: 40 }}
        >
          <div style={{ display: 'flex', alignItems: 'center', gap: 32 }}>
            <div>
              <Title level={2} style={{ marginBottom: 0 }}>Bảng điều khiển Lễ tân</Title>
              <Text style={{ fontSize: 18 }}>Chào mừng bạn đến với hệ thống quản lý lễ tân chuyên nghiệp</Text>
            </div>
          </div>
        </Card>

        <Row gutter={[32, 32]} style={{ marginBottom: 32 }}>
          {/* Card tổng quan */}
          <Col xs={24} sm={12} lg={6}>
            <Card style={{ borderRadius: 20, boxShadow: '0 4px 16px #1890ff22', height: '100%' }} bodyStyle={{ padding: 28 }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 16, marginBottom: 12 }}>
                <Avatar size={40} style={{ background: '#1890ff22', color: '#1890ff' }} icon={<DollarOutlined />} />
                <Text strong style={{ fontSize: 20 }}>Tổng doanh thu</Text>
              </div>
              <Text style={{ fontSize: 36, fontWeight: 700, }}>{stats.revenue ? stats.revenue.toLocaleString('vi-VN') : '0'} VNĐ</Text>
              <div style={{ width: '100%', margin: '12px 0' }}>
                <Column
                  data={revenueTrend.map((item: any) => ({ type: item.month, value: item.value }))}
                  xField="type"
                  yField="value"
                  height={40}
                  color="l(90) 0:#1890ff 1:#b37feb"
                  autoFit={true}
                  legend={false}
                  axis={false}
                  tooltip={{ showMarkers: false }}
                />
              </div>
              <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: 13, marginTop: 8 }}>
                <span>Tuần trước <span style={{ color: '#ff4d4f' }}>12% ▲</span></span>
                <span>Hôm qua <span style={{ color: '#52c41a' }}>11% ▼</span></span>
              </div>
              <div style={{ fontSize: 13, color: '#888', marginTop: 4 }}>Doanh thu ngày: {stats.dailyRevenue ? stats.dailyRevenue.toLocaleString('vi-VN') : '12,423'} VNĐ</div>
            </Card>
          </Col>
          <Col xs={24} sm={12} lg={6}>
            <Card style={{ borderRadius: 20, boxShadow: '0 4px 16px #b37feb22', height: '100%' }} bodyStyle={{ padding: 28 }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 16, marginBottom: 12 }}>
                <Avatar size={40} style={{ background: '#b37feb22',color: '#b37feb' }} icon={<TeamOutlined />} />
                <Text strong style={{ fontSize: 20 }}>Lượt truy cập</Text>
              </div>
              <Text style={{ fontSize: 36, fontWeight: 700,  }}>{stats.visits || '8,846'}</Text>
              <div style={{ width: '100%', margin: '12px 0' }}>
                <Area
                  data={(stats.visitsTrend || [1200, 1400, 1100, 1600, 1300, 1700, 1500]).map((v: number, idx: number) => ({ x: idx + 1, y: v }))}
                  xField="x"
                  yField="y"
                  height={40}
                  color="l(90) 0:#b37feb 1:#1890ff"
                  autoFit={true}
                  legend={false}
                  axis={false}
                  smooth
                  tooltip={{ showMarkers: false }}
                />
              </div>
              <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: 13, marginTop: 8 }}>
                <span>Tuần trước <span style={{ color: '#ff4d4f' }}>12% ▲</span></span>
                <span>Hôm qua <span style={{ color: '#52c41a' }}>11% ▼</span></span>
              </div>
              <div style={{ fontSize: 13, color: '#888', marginTop: 4 }}>Lượt truy cập ngày: {stats.dailyVisits || '1,234'}</div>
            </Card>
          </Col>
          <Col xs={24} sm={12} lg={6}>
            <Card style={{ borderRadius: 20, boxShadow: '0 4px 16px #52c41a22', height: '100%' }} bodyStyle={{ padding: 28 }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 16, marginBottom: 12 }}>
                <Avatar size={40} style={{ background: '#52c41a22', color: '#52c41a' }} icon={<BookOutlined />} />
                <Text strong style={{ fontSize: 20 }}>Số giao dịch</Text>
              </div>
              <Text style={{ fontSize: 36, fontWeight: 700, }}>{stats.payments || '6,560'}</Text>
              <div style={{ width: '100%', margin: '12px 0' }}>
                <Column
                  data={(stats.paymentsTrend || [300, 400, 350, 500, 420, 480, 390]).map((v: number, idx: number) => ({ type: idx + 1, value: v }))}
                  xField="type"
                  yField="value"
                  height={40}
                  color="l(90) 0:#52c41a 1:#1890ff"
                  autoFit={true}
                  legend={false}
                  axis={false}
                  tooltip={{ showMarkers: false }}
                />
              </div>
              <div style={{ fontSize: 13, color: '#1890ff', marginTop: 8 }}>Tỷ lệ chuyển đổi <span style={{ color: '#1890ff', fontWeight: 600 }}>60%</span></div>
            </Card>
          </Col>
          <Col xs={24} sm={12} lg={6}>
            <Card style={{ borderRadius: 20, boxShadow: '0 4px 16px #faad1422', height: '100%' }} bodyStyle={{ padding: 28 }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 16, marginBottom: 12 }}>
                <Avatar size={40} style={{ background: '#faad1422', color: '#faad14' }} icon={<PieChartOutlined />} />
                <Text strong style={{ fontSize: 20 }}>Hiệu suất hoạt động</Text>
              </div>
              <Text style={{ fontSize: 36, fontWeight: 700 }}>{stats.activityRate || '78'}%</Text>
              <Progress percent={stats.activityRate || 78} showInfo={false} strokeColor={twoColors} status='active' style={{ width: '100%', marginBottom: 0 }} />
              <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: 13, marginTop: 8 }}>
                <span>Tuần trước <span style={{ color: '#ff4d4f' }}>12% ▲</span></span>
                <span>Hôm qua <span style={{ color: '#52c41a' }}>11% ▼</span></span>
              </div>
            </Card>
          </Col>
        </Row>

        <Row gutter={[32, 32]} style={{ marginBottom: 32 }}>
          <Col xs={24} lg={12}>
            <Card title={<span style={{ fontWeight: 600, fontSize: 18 }}>Lịch trình hôm nay</span>} style={{ borderRadius: 20, boxShadow: '0 2px 8px #1890ff22', height: '100%' }} bodyStyle={{ padding: 28 }}>
              {scheduleTimeline}
            </Card>
          </Col>
          <Col xs={24} lg={12}>
            <Card title={<span style={{ fontWeight: 600, fontSize: 18 }}>Thông báo quan trọng</span>} style={{ borderRadius: 20, boxShadow: '0 2px 8px #faad1422', height: '100%' }} bodyStyle={{ padding: 28 }}>
              {notificationList}
            </Card>
          </Col>
        </Row>

        <Row gutter={[32, 32]} style={{ marginBottom: 32 }}>
          <Col xs={24} lg={12}>
            <Card title={<span style={{ fontWeight: 600, fontSize: 18 }}>Doanh thu theo tháng</span>} style={{ borderRadius: 20, boxShadow: '0 2px 8px #1890ff22', height: '100%' }} bodyStyle={{ padding: 28 }}>
              {/* Line + Column chart demo style from Ant Design Pro */}
              {(() => {
                // Mock data for dashboard context
                const data = [
                  { date: '1', price: 200 },
                  { date: '2', price: 800 },
                  { date: '3', price: 600 },
                  { date: '4', price: 900 },
                  { date: '5', price: 1200 },
                  { date: '6', price: 800 },
                  { date: '7', price: 400 },
                  { date: '8', price: 350 },
                  { date: '9', price: 900 },
                  { date: '10', price: 850 },
                  { date: '11', price: 600 },
                  { date: '12', price: 700 },
                ];
                const lineConfig = {
                  data,
                  xField: 'date',
                  yField: 'price',
                  height: 120,
                  color: '#1890ff',
                  point: { size: 4, shape: 'circle' },
                  tooltip: { showMarkers: true },
                  legend: false,
                  autoFit: true,
                };
                const columnConfig = {
                  data,
                  xField: 'date',
                  yField: 'price',
                  height: 120,
                  color: '#9254de',
                  legend: false,
                  autoFit: true,
                  tooltip: { showMarkers: true },
                };
                return (
                  <div>
                    <Line {...lineConfig} />
                    <Column {...columnConfig} />
                  </div>
                );
              })()}
            </Card>
          </Col>
          <Col xs={24} lg={12}>
            <Card style={{ borderRadius: 20, boxShadow: '0 2px 8px #1890ff22', padding: 0, height: '100%' }} bodyStyle={{ padding: 0 }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', padding: '28px 28px 0 28px' }}>
                <div style={{ fontWeight: 700, fontSize: 20 }}>Phân loại doanh thu</div>
                <div>
                  <span style={{ marginRight: 8 }}><button style={{ border: 'none', background: '#f0f0f0', borderRadius: 6, padding: '4px 16px', fontWeight: 500, cursor: 'pointer' }}>Tất cả kênh</button></span>
                  <span style={{ marginRight: 8 }}><button style={{ border: 'none', background: '#f0f0f0', borderRadius: 6, padding: '4px 16px', fontWeight: 500, cursor: 'pointer' }}>Online</button></span>
                  <span><button style={{ border: 'none', background: '#f0f0f0', borderRadius: 6, padding: '4px 16px', fontWeight: 500, cursor: 'pointer' }}>Cửa hàng</button></span>
                </div>
              </div>
              <div style={{ padding: '0 28px 28px 28px' }}>
                <div style={{ fontSize: 16, fontWeight: 600, marginBottom: 8 }}>Doanh thu</div>
                {/* Pie chart demo style from Ant Design Pro */}
                {(() => {
                  const pieData = [
                    { type: 'Gia dụng', value: 4544 },
                    { type: 'Thực phẩm', value: 3321 },
                    { type: 'Dịch vụ', value: 2341 },
                    { type: 'Cá nhân', value: 3113 },
                    { type: 'Khác', value: 1231 },
                    { type: 'Mẹ & Bé', value: 1231 },
                  ];
                  const pieConfig = {
                    data: pieData,
                    angleField: 'value',
                    colorField: 'type',
                    radius: 0.8,
                    legend: { position: 'right' },
                    label: {
                      text: 'value',
                      position: 'outside',
                      style: {
                        fontSize: 14,
                        fill: '#333',
                      },
                    },
                    tooltip: { showMarkers: true },
                    color: ["#1890ff", "#36cfc9", "#9254de", "#faad14", "#73d13d", "#ff85c0"],
                    height: 260,
                    autoFit: true,
                  };
                  return <Pie {...pieConfig} />;
                })()}
              </div>
            </Card>
          </Col>
        </Row>

        {/* Table chart đẹp, xếp hạng từ khoá, số lượng, tỷ lệ, summary, mini chart */}
        <Row gutter={[32, 32]} style={{ marginBottom: 32 }}>
          <Col xs={24}>
            <Card style={{ borderRadius: 20, boxShadow: '0 2px 8px #1890ff22', padding: 0 }} bodyStyle={{ padding: 0 }}>
              <div style={{ padding: '28px 28px 0 28px' }}>
                <div style={{ fontWeight: 700, fontSize: 20, marginBottom: 16 }}> Xếp hạng tìm kiếm online</div>
                <div style={{ display: 'flex', gap: 32, marginBottom: 12 }}>
                  <div>
                    <div style={{ fontSize: 15, color: '#888', display: 'flex', alignItems: 'center', gap: 4 }}>
                      Tìm kiếm người dùng <InfoCircleOutlined style={{ fontSize: 14 }} />
                    </div>
                    <div style={{ fontSize: 22, fontWeight: 700, color: '#222', marginBottom: 2 }}>17.1 <span style={{ fontSize: 16, color: '#52c41a' }}>▲</span></div>
                    <Area
                      data={[{ x: 1, y: 12 }, { x: 2, y: 15 }, { x: 3, y: 17 }, { x: 4, y: 14 }, { x: 5, y: 18 }, { x: 6, y: 16 }]}
                      xField="x"
                      yField="y"
                      height={32}
                      color="#1890ff"
                      autoFit={true}
                      legend={false}
                      axis={false}
                      smooth
                      tooltip={false}
                    />
                  </div>
                  <div>
                    <div style={{ fontSize: 15, color: '#888', display: 'flex', alignItems: 'center', gap: 4 }}>
                      Tìm kiếm trung bình <InfoCircleOutlined style={{ fontSize: 14 }} />
                    </div>
                    <div style={{ fontSize: 22, fontWeight: 700, color: '#222', marginBottom: 2 }}>26.2 <span style={{ fontSize: 16, color: '#ff4d4f' }}>▼</span></div>
                    <Area
                      data={[{ x: 1, y: 22 }, { x: 2, y: 25 }, { x: 3, y: 26 }, { x: 4, y: 28 }, { x: 5, y: 24 }, { x: 6, y: 27 }]}
                      xField="x"
                      yField="y"
                      height={32}
                      color="#b37feb"
                      autoFit={true}
                      legend={false}
                      axis={false}
                      smooth
                      tooltip={false}
                    />
                  </div>
                </div>
              </div>
              <Table
                columns={[
                  { title: <span style={{ fontWeight: 600 }}>Xếp hạng</span>, dataIndex: 'rank', key: 'rank', align: 'left', render: (v: number) => <span style={{ fontWeight: 600 }}>{v}</span> },
                  { title: <span style={{ fontWeight: 600 }}>Từ khoá</span>, dataIndex: 'keyword', key: 'keyword', align: 'left', render: (v: string) => <a style={{ color: '#1890ff', fontWeight: 500 }}>{v}</a> },
                  { title: <span style={{ fontWeight: 600 }}>Người dùng</span>, dataIndex: 'users', key: 'users', align: 'left' },
                  {
                    title: <span style={{ fontWeight: 600 }}>Tỷ lệ</span>, dataIndex: 'rate', key: 'rate', align: 'left', render: (v: string) => (
                      <span style={{ fontWeight: 500 }}>
                        {v.includes('▲') ? <span style={{ color: '#52c41a' }}>{v.replace('▲', '')} <span style={{ fontSize: 16 }}>▲</span></span> : <span style={{ color: '#ff4d4f' }}>{v.replace('▼', '')} <span style={{ fontSize: 16 }}>▼</span></span>}
                      </span>
                    )
                  },
                ]}
                dataSource={[
                  { rank: 1, keyword: 'Tìm kiếm-0', users: 479, rate: '27% ▼' },
                  { rank: 2, keyword: 'Tìm kiếm-1', users: 110, rate: '47% ▲' },
                  { rank: 3, keyword: 'Tìm kiếm-2', users: 520, rate: '53% ▲' },
                  { rank: 4, keyword: 'Tìm kiếm-3', users: 974, rate: '86% ▲' },
                  { rank: 5, keyword: 'Tìm kiếm-4', users: 109, rate: '99% ▼' },
                ]}
                pagination={{ pageSize: 5, showSizeChanger: false, showQuickJumper: true }}
                size="middle"
                rowKey="rank"
                style={{ borderRadius: 0, padding: '0 28px 28px 28px' }}
              />
            </Card>
          </Col>
        </Row>

        <Row gutter={[32, 32]} style={{ marginBottom: 32 }}>
          <Col xs={24} lg={12}>
            <Card title={<span style={{ fontWeight: 600, fontSize: 18 }}>Trạng thái phòng</span>} style={{ borderRadius: 20, boxShadow: '0 2px 8px #1890ff22', height: '100%' }} bodyStyle={{ padding: 28 }}>
              <Table
                columns={roomStatusColumns}
                dataSource={roomStatus}
                pagination={false}
                size="middle"
                loading={loading}
                rowKey="status"
                style={{ borderRadius: 12 }}
              />
            </Card>
          </Col>

        </Row>
        <Divider style={{ margin: '32px 0' }} />
        <div style={{ textAlign: 'center', color: '#888', fontSize: 15 }}>
          &copy; {new Date().getFullYear()} LavishStay Reception Dashboard. Powered by Ant Design Pro.
        </div>
      </div>
    </div>
  );
};

export default ReceptionDashboard;
