import React, { useState } from "react";
import {
  Card,
  Form,
  DatePicker,
  InputNumber,
  Button,
  Divider,
  Typography,
  Space,
  message,
} from "antd";
import {
  CalendarOutlined,
  UserOutlined,
  DollarOutlined,
} from "@ant-design/icons";
import { useSelector } from 'react-redux';
import { RootState } from '../../store';
import dayjs, { Dayjs } from "dayjs";
import { calculateNightsFromRange, formatVND } from '../../utils/helpers';

const { Title, Text } = Typography;
const { RangePicker } = DatePicker;

interface RoomBookingFormProps {
  room: {
    id: number;
    name: string;
    priceUSD: number;
    priceVND: number;
    maxGuests: number;
  };
}

const RoomBookingForm: React.FC<RoomBookingFormProps> = ({ room }) => {
  const [form] = Form.useForm();
  const [loading, setLoading] = useState(false);
  const [dates, setDates] = useState<[Dayjs | null, Dayjs | null] | null>(null);
  const [guests, setGuests] = useState<number>(1);

  // Get search data from Redux store for initial values
  const searchData = useSelector((state: RootState) => state.search);

  const calculateNights = () => {
    if (!dates || !dates[0] || !dates[1]) {
      // Fallback to search data if local dates not set
      return calculateNightsFromRange(searchData.dateRange) || 0;
    }
    return dates[1].diff(dates[0], "day");
  };

  const calculateTotal = () => {
    const nights = calculateNights();
    return {
      usd: room.priceUSD * nights,
      vnd: room.priceVND * nights,
    };
  };

  const handleSubmit = async (values: any) => {
    setLoading(true);
    try {
      // Simulate API call
      await new Promise((resolve) => setTimeout(resolve, 2000));
      message.success(
        "Đặt phòng thành công! Chúng tôi sẽ liên hệ với bạn sớm."
      );
      form.resetFields();
      setDates(null);
      setGuests(1);
    } catch (error) {
      message.error("Có lỗi xảy ra. Vui lòng thử lại!");
    } finally {
      setLoading(false);
    }
  };

  const disabledDate = (current: Dayjs) => {
    // Disable past dates
    return current && current < dayjs().startOf("day");
  };

  const nights = calculateNights();
  const total = calculateTotal();

  return (
    <Card className="room-booking-form shadow-lg">
      <Title level={4} className="mb-6 text-center">
        Đặt phòng ngay
      </Title>

      <Form
        form={form}
        layout="vertical"
        onFinish={handleSubmit}
        className="space-y-4"
      >
        {/* Date picker */}
        <Form.Item
          name="dates"
          label="Ngày nhận - trả phòng"
          rules={[{ required: true, message: "Vui lòng chọn ngày!" }]}
        >
          <RangePicker
            className="w-full"
            size="large"
            placeholder={["Ngày nhận phòng", "Ngày trả phòng"]}
            suffixIcon={<CalendarOutlined />}
            disabledDate={disabledDate}
            onChange={(dates) =>
              setDates(dates as [Dayjs | null, Dayjs | null])
            }
          />
        </Form.Item>

        {/* Guest count */}
        <Form.Item
          name="guests"
          label="Số khách"
          rules={[{ required: true, message: "Vui lòng chọn số khách!" }]}
          initialValue={1}
        >
          <InputNumber
            className="w-full"
            size="large"
            min={1}
            max={room.maxGuests}
            prefix={<UserOutlined />}
            onChange={(value) => setGuests(value || 1)}
          />
        </Form.Item>

        {/* Pricing summary */}
        {nights > 0 && (
          <div className="bg-gray-50 p-4 rounded-lg">
            <Space direction="vertical" className="w-full">
              <div className="flex justify-between">
                <Text>Giá phòng / đêm:</Text>
                <Space>
                  <Text strong>${room.priceUSD}</Text>
                  <Text type="secondary">({formatVND(room.priceVND)})</Text>
                </Space>
              </div>

              <div className="flex justify-between">
                <Text>Số đêm:</Text>
                <Text>{nights} đêm</Text>
              </div>

              <Divider className="my-2" />

              <div className="flex justify-between">
                <Text strong>Tổng cộng:</Text>
                <Space direction="vertical" className="text-right">
                  <Text strong className="text-blue-600 text-lg">
                    ${total.usd}
                  </Text>
                  <Text type="secondary">{formatVND(total.vnd)}</Text>
                </Space>
              </div>
            </Space>
          </div>
        )}

        {/* Contact info */}
        <Form.Item
          name="name"
          label="Họ và tên"
          rules={[{ required: true, message: "Vui lòng nhập họ tên!" }]}
        >
          <input
            type="text"
            className="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Nhập họ và tên"
          />
        </Form.Item>

        <Form.Item
          name="phone"
          label="Số điện thoại"
          rules={[
            { required: true, message: "Vui lòng nhập số điện thoại!" },
            {
              pattern: /^[0-9+\-\s()]+$/,
              message: "Số điện thoại không hợp lệ!",
            },
          ]}
        >
          <input
            type="tel"
            className="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Nhập số điện thoại"
          />
        </Form.Item>

        <Form.Item
          name="email"
          label="Email"
          rules={[
            { required: true, message: "Vui lòng nhập email!" },
            { type: "email", message: "Email không hợp lệ!" },
          ]}
        >
          <input
            type="email"
            className="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Nhập địa chỉ email"
          />
        </Form.Item>

        {/* Submit button */}
        <Form.Item className="mb-0">
          <Button
            type="primary"
            htmlType="submit"
            size="large"
            loading={loading}
            className="w-full bg-blue-600 hover:bg-blue-700"
            icon={<DollarOutlined />}
          >
            {loading ? "Đang xử lý..." : "Đặt phòng ngay"}
          </Button>
        </Form.Item>
      </Form>

      {/* Additional info */}
      <div className="mt-4 p-3 bg-blue-50 rounded-lg">
        <Text className="text-sm text-gray-600">
          <strong>Lưu ý:</strong> Đây là đặt phòng tạm thời. Chúng tôi sẽ liên
          hệ xác nhận trong vòng 24h.
        </Text>
      </div>
    </Card>
  );
};

export default RoomBookingForm;
