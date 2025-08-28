import React, { useState } from "react";
import { Form, Input, Button, Card, message, Typography, Row, Col } from "antd";
import {
  MailOutlined,
  PhoneOutlined,
  UserOutlined,
  SendOutlined,
} from "@ant-design/icons";

const { TextArea } = Input;
const { Title, Paragraph } = Typography;

const API_BASE = import.meta.env.VITE_API_BASE || "http://localhost:8888";

interface ContactFormProps {
  title?: string;
  subtitle?: string;
  className?: string;
  onSubmit?: (values: any) => void;
}

const ContactForm: React.FC<ContactFormProps> = ({
  title = "Liên hệ với chúng tôi",
  subtitle = "Hãy để lại thông tin, chúng tôi sẽ liên hệ lại với bạn sớm nhất có thể.",
  className = "",
  onSubmit,
}) => {
  const [form] = Form.useForm();
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (values: any) => {
    setLoading(true);

    try {
      const res = await fetch(`${API_BASE}/api/contact`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify(values),
      });

      let data: any = null;
      const contentType = res.headers.get("content-type") || "";
      if (contentType.includes("application/json")) {
        try {
          data = await res.json();
        } catch (e) {
          console.warn("Contact: failed to parse JSON response", e);
        }
      }

      if (res.ok && data && data.success) {
        message.success(
          data.message || "Thông tin của bạn đã được gửi thành công!"
        );
        form.resetFields();
        if (onSubmit) onSubmit(values);
      } else if (data && data.message) {
        message.error(data.message);
      } else {
        message.error("Có lỗi xảy ra khi gửi thông tin.");
      }
    } catch (err) {
      message.error("Không thể gửi yêu cầu. Vui lòng thử lại sau.");
      // eslint-disable-next-line no-console
      console.error("Contact form submit error", err);
    } finally {
      setLoading(false);
    }
  };

  return (
    <Card className={`shadow-md rounded-lg ${className}`}>
      <div className="text-center mb-6">
        <Title level={3} className="font-bevietnam font-bold text-gray-800">
          {title}
        </Title>
        <Paragraph className="font-bevietnam text-gray-600">
          {subtitle}
        </Paragraph>
      </div>

      <Form
        form={form}
        layout="vertical"
        onFinish={handleSubmit}
        className="font-bevietnam"
      >
        <Row gutter={16}>
          <Col xs={24} md={12}>
            <Form.Item
              name="name"
              label="Họ tên"
              rules={[{ required: true, message: "Vui lòng nhập họ tên!" }]}
            >
              <Input
                prefix={<UserOutlined className="text-gray-400" />}
                placeholder="Nhập họ tên của bạn"
                size="large"
              />
            </Form.Item>
          </Col>

          <Col xs={24} md={12}>
            <Form.Item
              name="email"
              label="Email"
              rules={[
                { required: true, message: "Vui lòng nhập email!" },
                { type: "email", message: "Email không hợp lệ!" },
              ]}
            >
              <Input
                prefix={<MailOutlined className="text-gray-400" />}
                placeholder="Nhập email của bạn"
                size="large"
              />
            </Form.Item>
          </Col>
        </Row>

        <Form.Item
          name="phone"
          label="Số điện thoại"
          rules={[{ required: true, message: "Vui lòng nhập số điện thoại!" }]}
        >
          <Input
            prefix={<PhoneOutlined className="text-gray-400" />}
            placeholder="Nhập số điện thoại của bạn"
            size="large"
          />
        </Form.Item>

        <Form.Item
          name="subject"
          label="Tiêu đề"
          rules={[{ required: true, message: "Vui lòng nhập tiêu đề!" }]}
        >
          <Input placeholder="Tiêu đề tin nhắn của bạn" size="large" />
        </Form.Item>

        <Form.Item
          name="message"
          label="Nội dung"
          rules={[
            { required: true, message: "Vui lòng nhập nội dung tin nhắn!" },
          ]}
        >
          <TextArea
            placeholder="Nhập nội dung tin nhắn của bạn"
            rows={5}
            className="text-base"
          />
        </Form.Item>

        <Form.Item>
          <Button
            type="primary"
            htmlType="submit"
            size="large"
            block
            icon={<SendOutlined />}
            loading={loading}
            className="h-12 text-base font-medium"
          >
            Gửi tin nhắn
          </Button>
        </Form.Item>
      </Form>
    </Card>
  );
};

export default ContactForm;
