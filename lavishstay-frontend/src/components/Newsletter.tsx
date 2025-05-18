import React, { useState } from "react";
import { Form, Input, Button, Typography, message } from "antd";
import { MailOutlined, SendOutlined } from "@ant-design/icons";

const { Title, Paragraph } = Typography;

interface NewsletterProps {
  title?: string;
  subtitle?: string;
  buttonText?: string;
  className?: string;
  style?: React.CSSProperties;
}

const Newsletter: React.FC<NewsletterProps> = ({
  title = "Đăng ký nhận thông tin khuyến mãi",
  subtitle = "Nhận thông tin mới nhất về các ưu đãi và khuyến mãi hấp dẫn từ LavishStay.",
  buttonText = "Đăng ký",
  className = "",
  style = {},
}) => {
  const [loading, setLoading] = useState(false);
  const [form] = Form.useForm();

  const handleSubmit = (values: any) => {
    setLoading(true);

    // Giả lập API call
    setTimeout(() => {
      setLoading(false);
      message.success("Đăng ký thành công!");
      form.resetFields();
    }, 1000);
  };

  return (
    <div
      className={`bg-blue-50 py-12 px-6 md:px-12 rounded-xl ${className}`}
      style={style}
    >
      <div className="max-w-xl mx-auto text-center">
        <Title
          level={3}
          className="font-bevietnam font-bold text-blue-700 mb-3"
        >
          {title}
        </Title>

        <Paragraph className="font-bevietnam text-gray-600 mb-6">
          {subtitle}
        </Paragraph>

        <Form
          form={form}
          layout="inline"
          onFinish={handleSubmit}
          className="flex flex-col sm:flex-row w-full gap-3"
        >
          <Form.Item
            name="email"
            rules={[
              { required: true, message: "Vui lòng nhập email!" },
              { type: "email", message: "Email không hợp lệ!" },
            ]}
            className="flex-grow w-full sm:w-auto mb-0"
          >
            <Input
              size="large"
              placeholder="Email của bạn"
              prefix={<MailOutlined className="text-gray-400" />}
              className="w-full"
            />
          </Form.Item>

          <Form.Item className="w-full sm:w-auto mb-0">
            <Button
              type="primary"
              htmlType="submit"
              size="large"
              icon={<SendOutlined />}
              loading={loading}
              className="w-full sm:w-auto"
            >
              {buttonText}
            </Button>
          </Form.Item>
        </Form>

        <Paragraph className="font-bevietnam text-gray-500 text-xs mt-4">
          Chúng tôi tôn trọng quyền riêng tư của bạn. Bạn có thể hủy đăng ký bất
          cứ lúc nào.
        </Paragraph>
      </div>
    </div>
  );
};

export default Newsletter;
