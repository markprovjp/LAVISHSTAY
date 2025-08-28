import React, { useState } from "react";
import {
  Form,
  Input,
  Button,
  Typography,
  message,
  ConfigProvider,
  theme,
  Modal,
} from "antd";
import {
  MailOutlined,
  SendOutlined,
  GiftOutlined,
  SafetyOutlined,
} from "@ant-design/icons";
import { useTranslation } from "react-i18next";
import illustration from "../assets/images/illustration.svg";

const { Title, Paragraph, Text } = Typography;

const API_BASE = import.meta.env.VITE_API_BASE || "http://localhost:8888";

interface NewsletterProps {
  title?: string;
  subtitle?: string;
  buttonText?: string;
  className?: string;
  style?: React.CSSProperties;
  showOnlyOnHome?: boolean;
}

const Newsletter: React.FC<NewsletterProps> = ({
  title,
  subtitle,
  buttonText,
  className = "",
  style = {},
  showOnlyOnHome = true,
}) => {
  const [loading, setLoading] = useState(false);
  const [form] = Form.useForm();
  const { token } = theme.useToken();
  const location = window.location.pathname;
  const { t } = useTranslation();
  const [visibleModal, setVisibleModal] = useState(false);
  const [discountCode, setDiscountCode] = useState<string | null>(null);

  // Đặt các giá trị mặc định bằng cách sử dụng các bản dịch nếu không được cung cấp
  title = title || t("newsletter.title");
  subtitle = subtitle || t("newsletter.subtitle");
  buttonText = buttonText || t("newsletter.button");

  // Nếu showOnlyOnHome là true và không phải đang ở trang chủ, không hiển thị component
  if (showOnlyOnHome && location !== "/") {
    return null;
  }

  const handleSubmit = async (values: any) => {
    setLoading(true);

    try {
      const res = await fetch(`${API_BASE}/api/newsletter`, {
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
          // ignore JSON parse errors
          console.warn("Failed to parse JSON response", e);
        }
      }

      if (res.ok && data && data.success) {
        const code = data.discount_code || null;
        setDiscountCode(code);
        setVisibleModal(true);
        message.success(data.message || "Đăng ký thành công!");
        form.resetFields();
      } else if (data && data.message) {
        message.error(data.message);
      } else {
        message.error("Có lỗi xảy ra khi đăng ký.");
      }
    } catch (err) {
      message.error("Không thể đăng ký. Vui lòng thử lại sau.");
      // eslint-disable-next-line no-console
      console.error("Newsletter submit error", err);
    } finally {
      setLoading(false);
    }
  };

  return (
    <ConfigProvider theme={{ token }}>
      <div className="container mx-auto px-4">
        <div
          className={`${className}`}
          style={{
            background: token.colorBgContainer,
            borderRadius: token.borderRadius * 2,
            boxShadow: token.boxShadowTertiary,
            border: `1px solid ${token.colorBorderSecondary}`,
            overflow: "hidden",
            margin: "60px 0",
            ...style,
          }}
        >
          <div className="flex flex-col md:flex-row items-center">
            {/* Left side - Illustration */}
            <div className="w-full md:w-5/12 p-6 md:p-10 flex justify-center md:justify-end">
              <img
                src={illustration}
                alt="Newsletter illustration"
                className="max-w-full md:max-w-xs h-auto"
                style={{ maxHeight: "280px" }}
              />
            </div>

            {/* Right side - Content */}
            <div className="w-full md:w-7/12 p-6 md:p-10 md:pl-4">
              <div className="mb-6">
                <Title
                  level={3}
                  style={{
                    color: token.colorPrimary,
                    fontFamily: token.fontFamily,
                    marginBottom: "16px",
                  }}
                >
                  {title}
                </Title>
                <Paragraph
                  style={{
                    color: token.colorTextSecondary,
                    marginBottom: "24px",
                  }}
                >
                  {subtitle}
                </Paragraph>
                <Form
                  form={form}
                  onFinish={handleSubmit}
                  className="newsletter-form"
                >
                  <div className="flex flex-col sm:flex-row gap-3">
                    <Form.Item
                      name="email"
                      rules={[
                        { required: true, message: "Vui lòng nhập email!" },
                        { type: "email", message: "Email không hợp lệ!" },
                      ]}
                      className="flex-grow mb-0 w-full"
                      style={{ marginBottom: "0" }}
                    >
                      <Input
                        size="large"
                        placeholder="Email của bạn"
                        prefix={
                          <MailOutlined
                            style={{ color: token.colorTextSecondary }}
                          />
                        }
                        style={{
                          borderRadius: token.borderRadius,
                          height: "44px",
                        }}
                      />
                    </Form.Item>

                    <Form.Item className="mb-0" style={{ marginBottom: "0" }}>
                      <Button
                        type="primary"
                        htmlType="submit"
                        size="large"
                        icon={<SendOutlined />}
                        loading={loading}
                        style={{
                          height: "44px",
                          borderRadius: token.borderRadius,
                          background: token.colorPrimary,
                          borderColor: token.colorPrimary,
                          fontWeight: 500,
                        }}
                      >
                        {buttonText}
                      </Button>
                    </Form.Item>
                  </div>
                </Form>{" "}
                <Text type="secondary" className="text-xs mt-4 block">
                  {t("newsletter.privacy")}
                </Text>
              </div>

              {/* Features */}
              <div className="flex flex-wrap gap-y-3">
                <div className="w-full sm:w-1/2 flex items-center">
                  <GiftOutlined
                    style={{ color: token.colorPrimary, marginRight: "8px" }}
                  />{" "}
                  <Text style={{ color: token.colorTextSecondary }}>
                    {t("newsletter.features.exclusive")}
                  </Text>
                </div>
                <div className="w-full sm:w-1/2 flex items-center">
                  <SafetyOutlined
                    style={{ color: token.colorPrimary, marginRight: "8px" }}
                  />
                  <Text style={{ color: token.colorTextSecondary }}>
                    {t("newsletter.features.priority")}
                  </Text>
                </div>
              </div>
            </div>
          </div>
        </div>

        <Modal
          title="Mã giảm giá của bạn"
          visible={visibleModal}
          onOk={() => setVisibleModal(false)}
          onCancel={() => setVisibleModal(false)}
        >
          <div className="text-center">
            <p className="text-lg font-semibold">Cảm ơn bạn đã đăng ký!</p>
            {discountCode ? (
              <div className="mt-4">
                <p className="text-sm">Mã giảm giá 20% của bạn:</p>
                <div className="text-2xl font-bold text-blue-600 mt-2">
                  {discountCode}
                </div>
                <p className="text-xs mt-2 text-gray-500">Hạn sử dụng 90 ngày</p>
              </div>
            ) : (
              <p>Vui lòng kiểm tra email để nhận mã.</p>
            )}
          </div>
        </Modal>
      </div>
    </ConfigProvider>
  );
};

export default Newsletter;
