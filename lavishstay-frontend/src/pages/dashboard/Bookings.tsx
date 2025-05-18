import React, { useState } from "react";
import { Typography, Tabs, Table, Tag, Button, Space } from "antd";
import { EyeOutlined, DeleteOutlined } from "@ant-design/icons";
import type { TableProps } from "antd";

const { Title } = Typography;
const { TabPane } = Tabs;

interface Booking {
  id: number;
  hotelName: string;
  checkIn: string;
  checkOut: string;
  status: "pending" | "confirmed" | "completed" | "cancelled";
  totalPrice: number;
  guests: number;
}

const Bookings: React.FC = () => {
  const [activeTab, setActiveTab] = useState("all");

  // Mock data for bookings
  const bookings: Booking[] = [];

  const getStatusColor = (status: string) => {
    switch (status) {
      case "pending":
        return "orange";
      case "confirmed":
        return "blue";
      case "completed":
        return "green";
      case "cancelled":
        return "red";
      default:
        return "default";
    }
  };

  const getStatusText = (status: string) => {
    switch (status) {
      case "pending":
        return "Đang chờ";
      case "confirmed":
        return "Đã xác nhận";
      case "completed":
        return "Đã hoàn thành";
      case "cancelled":
        return "Đã hủy";
      default:
        return status;
    }
  };

  const columns: TableProps<Booking>["columns"] = [
    {
      title: "ID",
      dataIndex: "id",
      key: "id",
      width: 70,
    },
    {
      title: "Chỗ nghỉ",
      dataIndex: "hotelName",
      key: "hotelName",
      render: (text) => <a>{text}</a>,
    },
    {
      title: "Nhận phòng",
      dataIndex: "checkIn",
      key: "checkIn",
    },
    {
      title: "Trả phòng",
      dataIndex: "checkOut",
      key: "checkOut",
    },
    {
      title: "Số khách",
      dataIndex: "guests",
      key: "guests",
      width: 100,
    },
    {
      title: "Tổng tiền",
      dataIndex: "totalPrice",
      key: "totalPrice",
      render: (price) => `$${price.toFixed(2)}`,
    },
    {
      title: "Trạng thái",
      dataIndex: "status",
      key: "status",
      render: (status) => (
        <Tag color={getStatusColor(status)}>{getStatusText(status)}</Tag>
      ),
    },
    {
      title: "Thao tác",
      key: "action",
      render: (_, record) => (
        <Space size="middle">
          <Button type="text" icon={<EyeOutlined />} size="small">
            Chi tiết
          </Button>
          {record.status === "pending" && (
            <Button type="text" danger icon={<DeleteOutlined />} size="small">
              Hủy
            </Button>
          )}
        </Space>
      ),
    },
  ];

  const filteredBookings =
    activeTab === "all"
      ? bookings
      : bookings.filter((booking) => booking.status === activeTab);

  return (
    <div>
      <Title level={2}>Đặt phòng của tôi</Title>

      <Tabs activeKey={activeTab} onChange={setActiveTab} className="mb-4">
        <TabPane tab="Tất cả" key="all" />
        <TabPane tab="Đang chờ" key="pending" />
        <TabPane tab="Đã xác nhận" key="confirmed" />
        <TabPane tab="Đã hoàn thành" key="completed" />
        <TabPane tab="Đã hủy" key="cancelled" />
      </Tabs>

      <Table
        columns={columns}
        dataSource={filteredBookings}
        rowKey="id"
        pagination={{ pageSize: 10 }}
        locale={{
          emptyText: "Không có đặt phòng nào",
        }}
      />
    </div>
  );
};

export default Bookings;
