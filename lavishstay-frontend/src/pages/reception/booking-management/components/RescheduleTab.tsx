
import React, { useState } from 'react';
import { DatePicker, Button, Form, message, Card, Typography, Divider } from 'antd';
import dayjs from 'dayjs';

const { Text, Paragraph } = Typography;

const RescheduleTab = ({ bookingId, currentCheckInDate, currentCheckOutDate, onUpdate }) => {
  const [form] = Form.useForm();
  const [loading, setLoading] = useState(false);

  const handleReschedule = async (values) => {
    setLoading(true);
    try {
      const response = await fetch(`http://localhost:8888/api/reception/bookings/${bookingId}/reschedule`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 
          new_check_in_date: values.new_check_in_date.format('YYYY-MM-DD'),
          new_check_out_date: values.new_check_out_date.format('YYYY-MM-DD'),
        }),
      });
      const data = await response.json();
      if (data.success) {
        message.success('Dời lịch thành công!');
        onUpdate();
      } else {
        message.error(data.message || 'Không thể dời lịch.');
      }
    } catch (error) {
      message.error('Có lỗi xảy ra khi dời lịch.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <Card title="Dời lịch đặt phòng">
      <Form form={form} layout="vertical" onFinish={handleReschedule}>
        <Form.Item
          label="Ngày nhận phòng mới"
          name="new_check_in_date"
          rules={[{ required: true, message: 'Vui lòng chọn ngày nhận phòng mới!' }]}
        >
          <DatePicker style={{ width: '100%' }} format="DD/MM/YYYY" />
        </Form.Item>
        <Form.Item
          label="Ngày trả phòng mới"
          name="new_check_out_date"
          rules={[{ required: true, message: 'Vui lòng chọn ngày trả phòng mới!' }]}
        >
          <DatePicker style={{ width: '100%' }} format="DD/MM/YYYY" />
        </Form.Item>
        <Form.Item>
          <Button type="primary" htmlType="submit" loading={loading}>
            Xác nhận dời lịch
          </Button>
        </Form.Item>
      </Form>
      <Divider />
      <Card type="inner" title="Chính sách dời lịch">
        <Paragraph>
          Việc dời lịch có thể phát sinh phụ phí tùy theo chính sách của khách sạn và tình trạng phòng trống.
        </Paragraph>
      </Card>
    </Card>
  );
};

export default RescheduleTab;
