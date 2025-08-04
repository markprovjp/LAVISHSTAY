
import React, { useState } from 'react';
import { DatePicker, Button, Form, InputNumber, message, Card, Typography, Divider } from 'antd';
import dayjs from 'dayjs';

const { Text, Paragraph } = Typography;

const ExtendStayTab = ({ bookingId, currentCheckOutDate, onUpdate }) => {
  const [form] = Form.useForm();
  const [loading, setLoading] = useState(false);

  const handleExtendStay = async (values) => {
    setLoading(true);
    try {
      const response = await fetch(`http://localhost:8888/api/reception/bookings/${bookingId}/extend`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 
          new_check_out_date: values.new_check_out_date.format('YYYY-MM-DD'),
        }),
      });
      const data = await response.json();
      if (data.success) {
        message.success('Gia hạn thành công!');
        onUpdate();
      } else {
        message.error(data.message || 'Không thể gia hạn.');
      }
    } catch (error) {
      message.error('Có lỗi xảy ra khi gia hạn.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <Card title="Gia hạn thời gian lưu trú">
      <Form form={form} layout="vertical" onFinish={handleExtendStay}>
        <Form.Item label="Ngày trả phòng hiện tại">
          <Text strong>{dayjs(currentCheckOutDate).format('DD/MM/YYYY')}</Text>
        </Form.Item>
        <Form.Item
          label="Ngày trả phòng mới"
          name="new_check_out_date"
          rules={[{ required: true, message: 'Vui lòng chọn ngày trả phòng mới!' }]}
        >
          <DatePicker 
            style={{ width: '100%' }} 
            format="DD/MM/YYYY"
            disabledDate={(current) => current && current <= dayjs(currentCheckOutDate).endOf('day')}
          />
        </Form.Item>
        <Form.Item>
          <Button type="primary" htmlType="submit" loading={loading}>
            Xác nhận gia hạn
          </Button>
        </Form.Item>
      </Form>
      <Divider />
      <Card type="inner" title="Chính sách gia hạn">
        <Paragraph>
          Việc gia hạn phòng tùy thuộc vào tình trạng phòng trống và sẽ áp dụng mức giá hiện tại.
        </Paragraph>
      </Card>
    </Card>
  );
};

export default ExtendStayTab;
