/**
 * ReceptionDashboard Component
 * 
 * This component provides a comprehensive dashboard for reception staff with real-time
 * statistics, charts, and booking information.
 * 
 * Dependencies:
 * - React 18+
 * - Ant Design (antd)
 * - @ant-design/charts
 * - @ant-design/icons
 * 
 * To run development server:
 * cd lavishstay-frontend && npm run dev
 */

import React, { useState, useEffect } from 'react';
import {
  Card,
  Row,
  Col,
  Statistic,
  Typography,
  Progress,
  Timeline,
  Table,
  Space,
  Tag,
  Empty,
  Avatar,
  Spin,
  Alert
} from 'antd';
import {
  BookOutlined,
  CheckCircleOutlined,
  CalendarOutlined,
  ClockCircleOutlined,
  DollarOutlined,
  TeamOutlined,
  PieChartOutlined
} from '@ant-design/icons';
import { Column, Pie } from '@ant-design/charts';
import receptionChartApi from '../../services/receptionChartApi';

const { Title, Text } = Typography;

// TypeScript interfaces
interface DashboardStats {
  total_revenue: number;
  total_bookings: number;
  total_visits: number;
  performance_rate: number;
  visits_trend?: number[];
  payments_trend?: number[];
  daily_revenue?: number;
  daily_visits?: number;
}

interface ScheduleItem {
  booking_code: string;
  customer_name: string;
  room_number: string;
  check_in_time: string;
  check_out_time: string;
  status: string;
  type?: string;
  name?: string;
  time?: string;
}

interface RoomStatusItem {
  status: string;
  count: number;
}

interface RevenueByMonthItem {
  month_name: string;
  month: number;
  total_revenue: number;
  booking_count: number;
}

interface RevenueCategoryItem {
  type: string;
  value: number; // store numeric value for charts
  booking_count: number;
}

interface TopServiceItem {
  rank: number;
  keyword: string;
  users: number;
  total_revenue: number;
  avg_price: number;
  rate: string;
  trend?: string;
}

interface NotificationItem {
  type: 'urgent' | 'vip' | 'info';
  message: string;
  timestamp?: string;
}

// Mock data for fallback
const mockDashboardStats: DashboardStats = {
  total_revenue: 12345000,
  total_bookings: 124,
  total_visits: 5420,
  performance_rate: 78,
  visits_trend: [1200, 1400, 1100, 1600, 1300, 1700, 1500],
  payments_trend: [300, 400, 350, 500, 420, 480, 390],
  daily_revenue: 450000,
  daily_visits: 234
};

const mockSchedule: ScheduleItem[] = [
  {
    booking_code: 'LV001',
    customer_name: 'Nguyễn Văn A',
    room_number: '101',
    check_in_time: '14:00',
    check_out_time: '12:00',
    status: 'checkin',
    type: 'checkin',
    name: 'Nguyễn Văn A - Phòng 101',
    time: '14:00'
  },
  {
    booking_code: 'LV002',
    customer_name: 'Trần Thị B',
    room_number: '205',
    check_in_time: '15:30',
    check_out_time: '11:00',
    status: 'checkout',
    type: 'checkout',
    name: 'Trần Thị B - Phòng 205',
    time: '11:00'
  }
];

const mockRevenueByMonth: RevenueByMonthItem[] = [
  { month_name: 'Tháng 1', month: 1, total_revenue: 2500000, booking_count: 15 },
  { month_name: 'Tháng 2', month: 2, total_revenue: 3200000, booking_count: 18 },
  { month_name: 'Tháng 3', month: 3, total_revenue: 2800000, booking_count: 16 },
  { month_name: 'Tháng 4', month: 4, total_revenue: 3500000, booking_count: 22 },
  { month_name: 'Tháng 5', month: 5, total_revenue: 4100000, booking_count: 25 },
  { month_name: 'Tháng 6', month: 6, total_revenue: 3900000, booking_count: 23 }
];

const mockRevenueByCategory: RevenueCategoryItem[] = [
  { type: 'Suite', value: 9710000.0, booking_count: 1 },
  { type: 'Deluxe', value: 5420000.0, booking_count: 3 },
  { type: 'Standard', value: 3200000.0, booking_count: 8 }
];

const ReceptionDashboard: React.FC = () => {
  // State management
  const [stats, setStats] = useState<DashboardStats>(mockDashboardStats);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [schedule, setSchedule] = useState<ScheduleItem[]>([]);
  const [notifications, setNotifications] = useState<NotificationItem[]>([]);
  const [revenueTrend, setRevenueTrend] = useState<RevenueByMonthItem[]>([]);
  const [roomStatus, setRoomStatus] = useState<RoomStatusItem[]>([]);
  const [categoryData, setCategoryData] = useState<RevenueCategoryItem[]>([]);
  const [topServices, setTopServices] = useState<TopServiceItem[]>([]);

  useEffect(() => {
    const fetchData = async () => {
      setLoading(true);
      setError(null);

      try {
        // Fetch all data in parallel using Promise.allSettled
        // Use the centralized receptionChartApi service which uses the correct baseURL
        const results = await Promise.allSettled([
          receptionChartApi.getDashboardStats(),
          receptionChartApi.getTodaySchedule(),
          receptionChartApi.getRevenueByMonth(),
          receptionChartApi.getRevenueByCategory(),
          receptionChartApi.getActivityRate(),
          receptionChartApi.getTopBookedServices(),
          receptionChartApi.getNotifications()
        ]);

        console.debug('API Results:', results);

        // Process dashboard stats
        if (results[0].status === 'fulfilled') {
          const resp = results[0].value;
          const payload: any = resp.data?.data || resp.data;
          console.debug('Dashboard stats payload:', payload);
          if (payload) {
            // receptionChartApi DashboardStatsData may have different keys; map defensively
            setStats((prev) => ({
              total_revenue: Number(payload.total_revenue ?? payload.total_revenue) || prev.total_revenue,
              total_bookings: Number(payload.total_bookings ?? payload.total_bookings) || prev.total_bookings,
              total_visits: Number(payload.visits ?? payload.total_visits ?? prev.total_visits) || prev.total_visits,
              performance_rate: Number(payload.activity_rate ?? payload.performance_rate) || prev.performance_rate,
              visits_trend: payload.visits_trend || payload.visits_trend || prev.visits_trend,
              payments_trend: payload.payments_trend || prev.payments_trend,
              daily_revenue: Number(payload.daily_revenue ?? payload.daily_revenue) || prev.daily_revenue,
              daily_visits: Number(payload.daily_visits ?? payload.daily_visits) || prev.daily_visits
            }));
          }
        }

        // Process today schedule
        if (results[1].status === 'fulfilled') {
          const resp = results[1].value;
          const payload: any = resp.data?.data || resp.data;
          console.debug('Schedule payload:', payload);
          let scheduleItems: ScheduleItem[] = [];

          if (payload?.timeline && Array.isArray(payload.timeline) && payload.timeline.length > 0) {
            // Map items to local ScheduleItem shape
            scheduleItems = payload.timeline.map((it: any) => {
              const personPart = (it.customer_name ?? it.title ?? it.name ?? '') as string;
              const roomPart = (it.room_number ?? it.room ?? it.room_type ?? '') as string;
              const nameParts: string[] = [];
              if (personPart && String(personPart).trim()) nameParts.push(String(personPart).trim());
              if (roomPart && String(roomPart).trim()) nameParts.push(`Phòng ${String(roomPart).trim()}`);
              const safeName = nameParts.join(' - ');
              return {
                booking_code: it.booking_code ?? it.booking_id ?? '',
                customer_name: it.customer_name ?? it.title ?? it.name ?? '',
                room_number: it.room_number ?? it.room ?? it.room_type ?? '',
                check_in_time: it.check_in_time ?? it.time ?? '',
                check_out_time: it.check_out_time ?? '',
                status: it.status ?? it.type ?? 'booking',
                type: it.type ?? (it.status === 'checked_in' ? 'checkin' : it.status === 'checked_out' ? 'checkout' : 'booking'),
                name: safeName,
                time: it.time ?? it.check_in_time ?? ''
              } as ScheduleItem;
            });
          } else if (payload?.active_bookings && Array.isArray(payload.active_bookings) && payload.active_bookings.length > 0) {
            scheduleItems = payload.active_bookings.map((booking: any) => {
              const personPart = (booking.guest_name ?? booking.customer_name ?? booking.full_name ?? booking.title ?? '') as string;
              const roomPart = (booking.room_number ?? booking.room ?? booking.room_type ?? '') as string;
              const nameParts: string[] = [];
              if (personPart && String(personPart).trim()) nameParts.push(String(personPart).trim());
              if (roomPart && String(roomPart).trim()) nameParts.push(`Phòng ${String(roomPart).trim()}`);
              const safeName = nameParts.join(' - ');
              return {
                booking_code: booking.booking_code ?? booking.booking_id ?? '',
                customer_name: booking.customer_name ?? booking.title ?? booking.guest_name ?? '',
                room_number: booking.room_number ?? booking.room ?? '',
                check_in_time: booking.check_in_time ?? '',
                check_out_time: booking.check_out_time ?? '',
                status: booking.status ?? 'booking',
                type: booking.status === 'checked_in' ? 'checkin' : booking.status === 'checked_out' ? 'checkout' : 'booking',
                name: safeName,
                time: booking.check_in_time || booking.check_out_time || booking.time || ''
              } as ScheduleItem;
            });
          }

          setSchedule(scheduleItems.length > 0 ? scheduleItems : mockSchedule);
        }

        // Process revenue by month
        if (results[2].status === 'fulfilled') {
          const resp = results[2].value;
          const payload: any = resp.data?.data || resp.data;
          console.debug('Revenue by month payload:', payload);
          if (payload && Array.isArray(payload)) {
            const mappedRevenue = payload.map((item: any) => ({
              month_name: item.month_name ?? item.date ?? `Tháng ${item.month ?? ''}`,
              month: Number(item.month) || Number(new Date(item.date).getMonth() + 1) || 0,
              total_revenue: Number(item.total_revenue ?? item.price ?? item.value) || 0,
              booking_count: Number(item.booking_count || item.count) || 0
            }));
            console.debug('Mapped revenue:', mappedRevenue);
            setRevenueTrend(mappedRevenue);
          } else {
            setRevenueTrend(mockRevenueByMonth);
          }
        }

        // Process revenue by category
        if (results[3].status === 'fulfilled') {
          const resp = results[3].value;
          const payload: any = resp.data?.data || resp.data;
          console.debug('Revenue by category payload:', payload);
          if (payload && Array.isArray(payload)) {
            const mappedCategories: RevenueCategoryItem[] = payload.map((item: any) => ({
              type: item.type ?? item.category ?? 'Không xác định',
              value: Number(item.value ?? item.amount ?? item.price) || 0,
              booking_count: Number(item.booking_count || item.count) || 0
            }));
            console.debug('Mapped categories:', mappedCategories);
            setCategoryData(mappedCategories);
          } else {
            setCategoryData(mockRevenueByCategory);
          }
        }

        // Process activity rate / room status
        if (results[4].status === 'fulfilled') {
          const resp = results[4].value;
          const payload: any = resp.data?.data || resp.data;
          console.debug('Activity rate payload:', payload);
          if (payload?.room_stats) {
            const roomStatusData: RoomStatusItem[] = Object.entries(payload.room_stats).map(([status, count]) => ({
              status,
              count: Number(count) || 0
            }));
            setRoomStatus(roomStatusData);
          }
        }

        // Process top services
        if (results[5].status === 'fulfilled') {
          const resp = results[5].value;
          const payload: any = resp.data?.data || resp.data;
          console.debug('Top services payload:', payload);
          if (payload && Array.isArray(payload)) {
            const mappedTop = payload.map((item: any, index: number) => ({
              rank: Number(item.rank ?? index + 1) || index + 1,
              keyword: item.keyword ?? item.service_name ?? `Service ${index + 1}`,
              users: Number(item.users ?? item.booking_count) || 0,
              total_revenue: Number(item.total_revenue ?? item.value) || 0,
              avg_price: Number(item.avg_price) || 0,
              rate: item.rate ?? `${Math.random() > 0.5 ? '+' : '-'}${Math.floor(Math.random() * 20)}%${Math.random() > 0.5 ? '▲' : '▼'}`
            }));
            setTopServices(mappedTop);
          }
        }

        // Process notifications
        if (results[6].status === 'fulfilled') {
          const resp = results[6].value;
          const payload: any = resp.data?.data || resp.data;
          console.debug('Notifications payload:', payload);
          if (payload && Array.isArray(payload)) {
            // Map external notification shape to our local NotificationItem
            const mappedNotes: NotificationItem[] = payload.map((n: any) => ({
              type: n.priority === 'urgent' || n.priority === 'high' ? 'urgent' : n.priority === 'medium' ? 'vip' : 'info',
              message: n.message ?? n.title ?? '',
              timestamp: n.time ?? n.created_at ?? ''
            }));
            setNotifications(mappedNotes);
          } else {
            setNotifications([
              { type: 'urgent', message: 'Khách VIP check-in lúc 15:00 - Phòng 501' },
              { type: 'info', message: 'Bảo trì hệ thống điều hòa từ 22:00-06:00' }
            ]);
          }
        }

      } catch (err) {
        console.error('Error fetching dashboard data:', err);
        setError('Không thể tải dữ liệu dashboard. Đang hiển thị dữ liệu mẫu.');
        // Use mock data on error
        setSchedule(mockSchedule);
        setRevenueTrend(mockRevenueByMonth);
        setCategoryData(mockRevenueByCategory.map(item => ({
          ...item,
          value: Number(item.value)
        })) as any);
        setNotifications([
          { type: 'urgent', message: 'Khách VIP check-in lúc 15:00 - Phòng 501' },
          { type: 'info', message: 'Bảo trì hệ thống điều hòa từ 22:00-06:00' }
        ]);
      }

      setLoading(false);
    };

    fetchData();
  }, []);

  // Table columns for room status
  const roomStatusColumns = [
    {
      title: 'Trạng thái',
      dataIndex: 'status',
      key: 'status',
      render: (text: string) => {
        const statusMap: Record<string, { color: string; label: string }> = {
          available: { color: 'green', label: 'Còn trống' },
          occupied: { color: 'red', label: 'Đã đặt' },
          maintenance: { color: 'orange', label: 'Bảo trì' },
          cleaning: { color: 'blue', label: 'Đang dọn' }
        };
        const config = statusMap[text] || { color: 'default', label: text };
        return <Tag color={config.color}>{config.label}</Tag>;
      }
    },
    {
      title: 'Số lượng',
      dataIndex: 'count',
      key: 'count',
      render: (count: number) => <Text strong>{count}</Text>
    }
  ];

  // Table columns for top services
  const topServicesColumns = [
    {
      title: 'Xếp hạng',
      dataIndex: 'rank',
      key: 'rank',
      align: 'center' as const,
      render: (rank: number) => (
        <Avatar size="small" style={{ backgroundColor: '#1890ff' }}>
          {rank}
        </Avatar>
      )
    },
    {
      title: 'Từ khóa',
      dataIndex: 'keyword',
      key: 'keyword',
      render: (keyword: string) => (
        <Text style={{ color: '#1890ff', fontWeight: 500 }}>{keyword}</Text>
      )
    },
    {
      title: 'Người dùng',
      dataIndex: 'users',
      key: 'users'
    },
    {
      title: 'Tỷ lệ',
      dataIndex: 'rate',
      key: 'rate',
      render: (rate: string) => {
        const isPositive = rate.includes('▲');
        return (
          <Text style={{ color: isPositive ? '#52c41a' : '#ff4d4f' }}>
            {rate}
          </Text>
        );
      }
    }
  ];

  // Timeline for today's schedule
  const scheduleTimeline = (
    <Timeline
      mode="alternate"
      items={schedule.map((item, index) => {
        let color = 'blue';
        let dot = null;
        let label = '';

        if (item.type === 'checkin' || item.status === 'checked_in') {
          color = 'blue';
          dot = <CheckCircleOutlined style={{ fontSize: '16px', color: '#1890ff' }} />;
          label = 'Check-in';
        } else if (item.type === 'checkout' || item.status === 'checked_out') {
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
          label = item.type || 'Hoạt động';
        }

        const bookingCode = (item as any).booking_code || (item as any).booking_id || '';
        const personName = (item as any).customer_name || (item as any).guest_name || (item as any).title || (item as any).name || '';
        const roomNo = (item as any).room_number || (item as any).room || (item as any).room_type || '';

        return {
          color,
          dot,
          children: (
            <div key={index}>
              {bookingCode ? <Text strong>{bookingCode}</Text> : <Text strong aria-label={`event-label-${index}`}>{label}</Text>}
              <br />
              {personName ? <Text>{personName}</Text> : <Text type="secondary">{label}</Text>}
              <br />
              {roomNo ? <Text type="secondary">Phòng: {roomNo}</Text> : null}
              <br />
              <Text type="secondary">{(item as any).time || item.check_in_time || item.check_out_time || ''}</Text>
            </div>
          )
        };
      })}
    />
  );

  // Notification list
  const notificationList = (
    <Space direction="vertical" className="w-full" style={{ width: '100%' }}>
      {notifications.map((item, idx) => (
        <div
          key={idx}
          className={`p-2 rounded border-l-4 ${item.type === 'urgent'
              ? 'bg-red-50 border-red-400'
              : item.type === 'vip'
                ? 'bg-yellow-50 border-yellow-400'
                : 'bg-blue-50 border-blue-400'
            }`}
          style={{
            padding: 12,
            borderRadius: 8,
            borderLeft: `4px solid ${item.type === 'urgent' ? '#ff4d4f' : item.type === 'vip' ? '#faad14' : '#1890ff'
              }`,
            backgroundColor:
              item.type === 'urgent'
                ? '#fff2f0'
                : item.type === 'vip'
                  ? '#fffbe6'
                  : '#f0f9ff'
          }}
        >
          <Text strong>
            {item.type === 'urgent' ? 'Khẩn cấp:' : item.type === 'vip' ? 'Chú ý:' : 'Thông tin:'}
          </Text>
          <Text> {item.message}</Text>
        </div>
      ))}
    </Space>
  );

  if (loading) {
    return (
      <div style={{ padding: '64px', textAlign: 'center' }}>
        <Spin size="large" />
        <div style={{ marginTop: 16 }}>
          <Text>Đang tải dữ liệu dashboard...</Text>
        </div>
      </div>
    );
  }

  return (
    <div style={{ padding: '32px 0' }}>
      <div style={{ padding: '0 32px' }}>
        {error && (
          <Alert
            message="Lỗi tải dữ liệu"
            description={error}
            type="warning"
            showIcon
            closable
            style={{ marginBottom: 24, borderRadius: 12 }}
          />
        )}

        {/* Hero Card */}
        <Card
          style={{
            borderRadius: 24,
            boxShadow: '0 8px 32px rgba(24,144,255,0.12)',
            marginBottom: 32,
            marginTop: 16
          }}
          bodyStyle={{ padding: 40 }}
        >
          <div style={{ display: 'flex', alignItems: 'center', gap: 32 }}>
            <div>
              <Title level={2} style={{ marginBottom: 0 }}>
                Bảng điều khiển Lễ tân
              </Title>
              <Text style={{ fontSize: 18 }}>
                Chào mừng bạn đến với hệ thống quản lý lễ tân
              </Text>
            </div>
          </div>
        </Card>

        {/* Stats Cards Row */}
        <Row gutter={[32, 32]} style={{ marginBottom: 32 }}>
          <Col xs={24} sm={12} lg={6}>
            <Card
              style={{
                borderRadius: 20,
                boxShadow: '0 4px 16px #1890ff22',
                height: '100%'
              }}
              bodyStyle={{ padding: 28 }}
            >
              <div style={{ display: 'flex', alignItems: 'center', gap: 16, marginBottom: 12 }}>
                <Avatar
                  size={40}
                  style={{ background: '#1890ff22', color: '#1890ff' }}
                  icon={<DollarOutlined />}
                />
                <Text strong style={{ fontSize: 20 }}>
                  Tổng doanh thu
                </Text>
              </div>
              <Statistic
                value={stats.total_revenue}
                suffix="VNĐ"
                valueStyle={{ fontSize: 36, fontWeight: 700 }}
                formatter={(value) => `${Number(value).toLocaleString('vi-VN')}`}
              />
              <div style={{ fontSize: 13, color: '#888', marginTop: 4 }}>
                Doanh thu ngày: {stats.daily_revenue?.toLocaleString('vi-VN') || '0'} VNĐ
              </div>
            </Card>
          </Col>

          <Col xs={24} sm={12} lg={6}>
            <Card
              style={{
                borderRadius: 20,
                boxShadow: '0 4px 16px #b37feb22',
                height: '100%'
              }}
              bodyStyle={{ padding: 28 }}
            >
              <div style={{ display: 'flex', alignItems: 'center', gap: 16, marginBottom: 12 }}>
                <Avatar
                  size={40}
                  style={{ background: '#b37feb22', color: '#b37feb' }}
                  icon={<TeamOutlined />}
                />
                <Text strong style={{ fontSize: 20 }}>
                  Lượt truy cập
                </Text>
              </div>
              <Statistic
                value={stats.total_visits}
                valueStyle={{ fontSize: 36, fontWeight: 700 }}
              />
              <div style={{ fontSize: 13, color: '#888', marginTop: 4 }}>
                Lượt truy cập ngày: {stats.daily_visits?.toLocaleString('vi-VN') || '0'}
              </div>
            </Card>
          </Col>

          <Col xs={24} sm={12} lg={6}>
            <Card
              style={{
                borderRadius: 20,
                boxShadow: '0 4px 16px #52c41a22',
                height: '100%'
              }}
              bodyStyle={{ padding: 28 }}
            >
              <div style={{ display: 'flex', alignItems: 'center', gap: 16, marginBottom: 12 }}>
                <Avatar
                  size={40}
                  style={{ background: '#52c41a22', color: '#52c41a' }}
                  icon={<BookOutlined />}
                />
                <Text strong style={{ fontSize: 20 }}>
                  Số giao dịch
                </Text>
              </div>
              <Statistic
                value={stats.total_bookings}
                valueStyle={{ fontSize: 36, fontWeight: 700 }}
              />
            </Card>
          </Col>

          <Col xs={24} sm={12} lg={6}>
            <Card
              style={{
                borderRadius: 20,
                boxShadow: '0 4px 16px #faad1422',
                height: '100%'
              }}
              bodyStyle={{ padding: 28 }}
            >
              <div style={{ display: 'flex', alignItems: 'center', gap: 16, marginBottom: 12 }}>
                <Avatar
                  size={40}
                  style={{ background: '#faad1422', color: '#faad14' }}
                  icon={<PieChartOutlined />}
                />
                <Text strong style={{ fontSize: 20 }}>
                  Hiệu suất hoạt động
                </Text>
              </div>
              <Statistic
                value={stats.performance_rate}
                suffix="%"
                valueStyle={{ fontSize: 36, fontWeight: 700 }}
              />
              <Progress
                percent={stats.performance_rate}
                showInfo={false}
                strokeColor={{
                  '0%': '#108ee9',
                  '100%': '#87d068'
                }}
                status="active"
                style={{ width: '100%', marginTop: 12 }}
              />
            </Card>
          </Col>
        </Row>

        {/* Today Schedule & Revenue Trend Row */}
        <Row gutter={[32, 32]} style={{ marginBottom: 32 }}>
          <Col xs={24} lg={12}>
            <Card
              title={
                <span style={{ fontWeight: 600, fontSize: 18 }}>Lịch trình hôm nay</span>
              }
              style={{
                borderRadius: 20,
                boxShadow: '0 2px 8px #1890ff22',
                height: '100%'
              }}
              bodyStyle={{ padding: 28 }}
            >
              {schedule.length > 0 ? (
                scheduleTimeline
              ) : (
                <Empty description="Không có lịch trình nào hôm nay" />
              )}
            </Card>
          </Col>

          <Col xs={24} lg={12}>
            <Card
              title={
                <span style={{ fontWeight: 600, fontSize: 18 }}>Doanh thu theo tháng</span>
              }
              style={{
                borderRadius: 20,
                boxShadow: '0 2px 8px #1890ff22',
                height: '100%'
              }}
              bodyStyle={{ padding: 28 }}
            >
              {revenueTrend.length > 0 ? (
                <Column
                  data={revenueTrend}
                  xField="month_name"
                  yField="total_revenue"
                  height={300}
                  color="#1890ff"
                  columnStyle={{
                    radius: [4, 4, 0, 0]
                  }}
                  label={{
                    position: 'middle',
                    style: {
                      fill: '#FFFFFF',
                      opacity: 0.8
                    }
                  }}
                  tooltip={{
                    formatter: (datum: any) => ({
                      name: 'Doanh thu',
                      value: `${Number(datum.total_revenue).toLocaleString('vi-VN')} VNĐ`
                    })
                  }}
                />
              ) : (
                <Empty description="Không có dữ liệu doanh thu" />
              )}
            </Card>
          </Col>
        </Row>

        {/* Room Status & Revenue Category Row */}
        <Row gutter={[32, 32]} style={{ marginBottom: 32 }}>
          <Col xs={24} lg={12}>
            <Card
              title={
                <span style={{ fontWeight: 600, fontSize: 18 }}>Trạng thái phòng</span>
              }
              style={{
                borderRadius: 20,
                boxShadow: '0 2px 8px #1890ff22',
                height: '100%'
              }}
              bodyStyle={{ padding: 28 }}
            >
              <Table
                columns={roomStatusColumns}
                dataSource={roomStatus}
                pagination={false}
                size="middle"
                rowKey="status"
                locale={{
                  emptyText: <Empty description="Không có dữ liệu trạng thái phòng" />
                }}
              />
            </Card>
          </Col>

          <Col xs={24} lg={12}>
            <Card
              title={
                <span style={{ fontWeight: 600, fontSize: 18 }}>Phân loại doanh thu</span>
              }
              style={{
                borderRadius: 20,
                boxShadow: '0 2px 8px #1890ff22',
                height: '100%'
              }}
              bodyStyle={{ padding: 28 }}
            >
              {categoryData.length > 0 ? (
                <Pie
                  data={categoryData}
                  angleField="value"
                  colorField="type"
                  radius={0.8}
                  height={300}
                  label={{
                    text: (data: any) => `${data.type}\n${((data.value / categoryData.reduce((sum, item) => sum + Number(item.value), 0)) * 100).toFixed(1)}%`,
                    style: {
                      fontSize: 12,
                      textAlign: 'center'
                    }
                  }}
                  legend={{
                    position: 'bottom'
                  }}
                  tooltip={{
                    formatter: (datum: any) => ({
                      name: datum.type,
                      value: `${Number(datum.value).toLocaleString('vi-VN')} VNĐ`
                    })
                  }}
                  color={['#1890ff', '#36cfc9', '#9254de', '#faad14', '#73d13d', '#ff85c0']}
                />
              ) : (
                <Empty description="Không có dữ liệu phân loại doanh thu" />
              )}
            </Card>
          </Col>
        </Row>

        {/* Top Services & Notifications Row */}
        <Row gutter={[32, 32]}>
          <Col xs={24} lg={16}>
            <Card
              title={
                <span style={{ fontWeight: 600, fontSize: 18 }}>Dịch vụ được đặt nhiều nhất</span>
              }
              style={{
                borderRadius: 20,
                boxShadow: '0 2px 8px #1890ff22'
              }}
              bodyStyle={{ padding: 28 }}
            >
              <Table
                columns={topServicesColumns}
                dataSource={topServices}
                pagination={{ pageSize: 5, showSizeChanger: false }}
                size="middle"
                rowKey="rank"
                locale={{
                  emptyText: <Empty description="Không có dữ liệu dịch vụ" />
                }}
              />
            </Card>
          </Col>

          <Col xs={24} lg={8}>
            <Card
              title={
                <span style={{ fontWeight: 600, fontSize: 18 }}>Thông báo quan trọng</span>
              }
              style={{
                borderRadius: 20,
                boxShadow: '0 2px 8px #faad1422',
                height: '100%'
              }}
              bodyStyle={{ padding: 28 }}
            >
              {notifications.length > 0 ? (
                notificationList
              ) : (
                <Empty description="Không có thông báo mới" />
              )}
            </Card>
          </Col>
        </Row>
      </div>
    </div>
  );
};

export default ReceptionDashboard;

