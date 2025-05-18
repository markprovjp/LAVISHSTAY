import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import {
  Form,
  Input,
  Button,
  Typography,
  Divider,
  Tabs,
  Space,
  Row,
  Col,
  Alert,
  Card,
} from "antd";
import {
  UserOutlined,
  LockOutlined,
  MailOutlined,
  FacebookOutlined,
  GoogleOutlined,
  ArrowLeftOutlined,
} from "@ant-design/icons";
import useStore from "../store/useStore";
import { User } from "../types";
import { SEO } from "../utils";

const { Title, Paragraph, Text } = Typography;
const { TabPane } = Tabs;

interface LoginFormValues {
  email: string;
  password: string;
}

interface RegisterFormValues {
  name: string;
  email: string;
  password: string;
  passwordConfirm: string;
}

const Login: React.FC = () => {
  const [error, setError] = useState<string>("");
  const [loading, setLoading] = useState<boolean>(false);
  const [activeTab, setActiveTab] = useState<string>("login");
  const [loginForm] = Form.useForm();
  const [registerForm] = Form.useForm();

  const navigate = useNavigate();
  const { setUser } = useStore();

  const onFinishLogin = (values: LoginFormValues): void => {
    setLoading(true);
    setError("");

    // Giả lập gọi API đăng nhập
    setTimeout(() => {
      if (
        values.email === "user@example.com" &&
        values.password === "password"
      ) {
        // Giả lập đăng nhập thành công
        const user: User = {
          id: 1,
          name: "Test User",
          email: "user@example.com",
        };

        setUser(user);
        navigate("/");
      } else {
        setError(
          "Thông tin đăng nhập không chính xác. Thử với user@example.com / password"
        );
      }
      setLoading(false);
    }, 1000); // Đợi 1 giây để giả lập API
  };

  const onFinishRegister = (values: RegisterFormValues): void => {
    setLoading(true);
    setError("");

    // Giả lập gọi API đăng ký
    setTimeout(() => {
      // Giả lập đăng ký thành công
      const user: User = {
        id: 1,
        name: values.name,
        email: values.email,
      };

      setUser(user);
      navigate("/");
      setLoading(false);
    }, 1000); // Đợi 1 giây để giả lập API
  };

  return (
    <>
      {" "}
      <SEO
        title="Đăng nhập | LavishStay"
        description="Đăng nhập vào LavishStay để trải nghiệm dịch vụ đặt phòng sang trọng bậc nhất."
      />
      <div className="login-bg-gradient min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <Button
          type="text"
          icon={<ArrowLeftOutlined />}
          className="absolute top-8 left-8 font-bevietnam text-base hover:bg-white/20 transition-all"
          onClick={() => navigate("/")}
        >
          Quay lại trang chủ
        </Button>

        <Card className="w-full max-w-7xl shadow-xl rounded-2xl overflow-hidden backdrop-blur-sm bg-white/90 dark:bg-gray-800/70 auth-card">
          <Row className="min-h-[600px]">
            {/* Left Column - Image */}
            <Col xs={0} md={12} className="relative auth-left-column">
              <div className="absolute inset-0 flex flex-col items-center justify-center p-8 bg-gradient-to-br from-primary-500 to-primary-700 text-white">
                <div className="max-w-md mx-auto">
                  <img
                    src="/lavishstay-login-illustration.svg"
                    alt="LavishStay Login"
                    className="mb-8 max-h-72 w-full object-contain login-illustration"
                    onError={(e) => {
                      // Fallback if image doesn't exist
                      e.currentTarget.style.display = "none";
                    }}
                  />
                  <Title
                    level={2}
                    className="text-white font-bevietnam font-bold mb-4"
                  >
                    Trải nghiệm hành trình sang trọng cùng LavishStay
                  </Title>
                  <Paragraph className="text-white/90 text-lg font-bevietnam">
                    Khám phá và đặt phòng tại những khách sạn và khu nghỉ dưỡng
                    đẳng cấp nhất tại Việt Nam. Tận hưởng dịch vụ đặc biệt và
                    những đặc quyền dành riêng cho thành viên.
                  </Paragraph>

                  <div className="mt-8">
                    <Text className="text-white/90 italic font-bevietnam block mb-2">
                      "Kỳ nghỉ đáng nhớ nhất mà tôi từng có. Dịch vụ và tiện
                      nghi tuyệt vời!"
                    </Text>
                    <div className="flex items-center">
                      <div className="w-12 h-12 rounded-full bg-white/30 mr-3 testimonial-avatar overflow-hidden flex items-center justify-center">
                        <UserOutlined
                          style={{ fontSize: "24px", color: "white" }}
                        />
                      </div>
                      <div>
                        <Text
                          strong
                          className="text-white font-bevietnam block"
                        >
                          Nguyễn Minh Anh
                        </Text>
                        <Text className="text-white/80 font-bevietnam">
                          Khách hàng VIP
                        </Text>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </Col>{" "}
            {/* Right Column - Form */}
            <Col xs={24} md={12} className="p-8">
              <div className="max-w-md mx-auto">
                <div className="mb-6 text-center">
                  <img
                    src="/logo-dark.png"
                    alt="LavishStay Logo"
                    className="h-12 mb-4 mx-auto"
                    onError={(e) => {
                      // Fallback to text if logo doesn't exist
                      e.currentTarget.style.display = "none";
                      const logoText = document.createElement("h2");
                      logoText.innerText = "LavishStay";
                      logoText.className =
                        "text-2xl font-bevietnam font-bold text-primary-600 dark:text-primary-400";
                      e.currentTarget.parentNode?.appendChild(logoText);
                    }}
                  />
                  <Title
                    level={2}
                    className="font-bevietnam font-bold text-gray-800 dark:text-white mb-2"
                  >
                    Chào mừng quay trở lại
                  </Title>
                  <Paragraph className="font-bevietnam text-gray-600 dark:text-gray-300">
                    Đăng nhập hoặc tạo tài khoản để trải nghiệm dịch vụ đẳng cấp
                  </Paragraph>
                </div>
                {error && (
                  <Alert
                    message="Lỗi xác thực"
                    description={error}
                    type="error"
                    showIcon
                    className="mb-6 rounded-lg"
                    closable
                    onClose={() => setError("")}
                  />
                )}{" "}
                <Tabs
                  activeKey={activeTab}
                  onChange={setActiveTab}
                  centered
                  className="auth-tabs"
                >
                  <TabPane
                    tab={<span className="px-2">Đăng nhập</span>}
                    key="login"
                  >
                    <Form
                      form={loginForm}
                      name="login"
                      initialValues={{ remember: true }}
                      onFinish={onFinishLogin}
                      layout="vertical"
                      size="large"
                      className="font-bevietnam pt-4"
                    >
                      <Form.Item
                        name="email"
                        label={
                          <Text className="font-bevietnam font-medium auth-form-label">
                            Email
                          </Text>
                        }
                        className="auth-form-item"
                        rules={[
                          { required: true, message: "Vui lòng nhập email!" },
                          { type: "email", message: "Email không hợp lệ!" },
                        ]}
                      >
                        <Input
                          prefix={<MailOutlined className="text-gray-400" />}
                          placeholder="Nhập địa chỉ email của bạn"
                          autoComplete="email"
                          className="rounded-lg auth-form-input"
                        />
                      </Form.Item>{" "}
                      <Form.Item
                        name="password"
                        label={
                          <Text className="font-bevietnam font-medium auth-form-label">
                            Mật khẩu
                          </Text>
                        }
                        className="auth-form-item"
                        rules={[
                          {
                            required: true,
                            message: "Vui lòng nhập mật khẩu!",
                          },
                        ]}
                      >
                        <Input.Password
                          prefix={<LockOutlined className="text-gray-400" />}
                          placeholder="Nhập mật khẩu của bạn"
                          autoComplete="current-password"
                          className="rounded-lg auth-form-input"
                        />
                      </Form.Item>
                      <Form.Item className="mb-2">
                        <Button
                          type="link"
                          className="font-bevietnam p-0 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                          onClick={() => navigate("/forgot-password")}
                        >
                          Quên mật khẩu?
                        </Button>
                      </Form.Item>
                      <Form.Item>
                        <Button
                          type="primary"
                          htmlType="submit"
                          className="w-full rounded-lg font-bevietnam font-medium h-12 text-base"
                          loading={loading}
                        >
                          Đăng nhập
                        </Button>
                      </Form.Item>
                      <Divider className="font-bevietnam text-gray-500 dark:text-gray-400">
                        Hoặc đăng nhập với
                      </Divider>{" "}
                      <div className="flex gap-4 mb-6">
                        {" "}
                        <Button
                          block
                          icon={
                            <FacebookOutlined style={{ fontSize: "18px" }} />
                          }
                          className="font-bevietnam rounded-lg h-12 flex items-center justify-center facebook-login-button shadow hover:shadow-lg"
                        >
                          Facebook
                        </Button>
                        <Button
                          block
                          icon={
                            <div className="flex items-center">
                              <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 48 48"
                                width="18px"
                                height="18px"
                                style={{ marginRight: "4px" }}
                              >
                                <path
                                  fill="#FFC107"
                                  d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"
                                />
                                <path
                                  fill="#FF3D00"
                                  d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"
                                />
                                <path
                                  fill="#4CAF50"
                                  d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"
                                />
                                <path
                                  fill="#1976D2"
                                  d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"
                                />
                              </svg>
                            </div>
                          }
                          className="font-bevietnam rounded-lg h-12 flex items-center justify-center google-login-button shadow hover:shadow-lg"
                        >
                          Google
                        </Button>
                      </div>
                    </Form>
                  </TabPane>{" "}
                  <TabPane
                    tab={<span className="px-2">Đăng ký</span>}
                    key="register"
                  >
                    <Form
                      form={registerForm}
                      name="register"
                      onFinish={onFinishRegister}
                      layout="vertical"
                      size="large"
                      className="font-bevietnam pt-4"
                    >
                      <Form.Item
                        name="name"
                        label={
                          <Text className="font-bevietnam font-medium auth-form-label">
                            Họ và tên
                          </Text>
                        }
                        className="auth-form-item"
                        rules={[
                          { required: true, message: "Vui lòng nhập họ tên!" },
                        ]}
                      >
                        <Input
                          prefix={<UserOutlined className="text-gray-400" />}
                          placeholder="Nhập họ và tên của bạn"
                          className="rounded-lg auth-form-input"
                        />
                      </Form.Item>

                      <Form.Item
                        name="email"
                        label={
                          <Text className="font-bevietnam font-medium auth-form-label">
                            Email
                          </Text>
                        }
                        className="auth-form-item"
                        rules={[
                          { required: true, message: "Vui lòng nhập email!" },
                          { type: "email", message: "Email không hợp lệ!" },
                        ]}
                      >
                        <Input
                          prefix={<MailOutlined className="text-gray-400" />}
                          placeholder="Nhập địa chỉ email của bạn"
                          className="rounded-lg auth-form-input"
                        />
                      </Form.Item>

                      <Form.Item
                        name="password"
                        label={
                          <Text className="font-bevietnam font-medium auth-form-label">
                            Mật khẩu
                          </Text>
                        }
                        className="auth-form-item"
                        rules={[
                          {
                            required: true,
                            message: "Vui lòng nhập mật khẩu!",
                          },
                          {
                            min: 6,
                            message: "Mật khẩu phải có ít nhất 6 ký tự!",
                          },
                        ]}
                        hasFeedback
                      >
                        <Input.Password
                          prefix={<LockOutlined className="text-gray-400" />}
                          placeholder="Tạo mật khẩu (ít nhất 6 ký tự)"
                          className="rounded-lg auth-form-input"
                        />
                      </Form.Item>

                      <Form.Item
                        name="passwordConfirm"
                        label={
                          <Text className="font-bevietnam font-medium auth-form-label">
                            Xác nhận mật khẩu
                          </Text>
                        }
                        className="auth-form-item"
                        dependencies={["password"]}
                        rules={[
                          {
                            required: true,
                            message: "Vui lòng xác nhận mật khẩu!",
                          },
                          ({ getFieldValue }) => ({
                            validator(_, value) {
                              if (
                                !value ||
                                getFieldValue("password") === value
                              ) {
                                return Promise.resolve();
                              }
                              return Promise.reject(
                                new Error("Hai mật khẩu không khớp!")
                              );
                            },
                          }),
                        ]}
                        hasFeedback
                      >
                        <Input.Password
                          prefix={<LockOutlined className="text-gray-400" />}
                          placeholder="Nhập lại mật khẩu"
                          className="rounded-lg auth-form-input"
                        />
                      </Form.Item>

                      <Form.Item>
                        <Button
                          type="primary"
                          htmlType="submit"
                          className="w-full rounded-lg font-bevietnam font-medium h-12 text-base"
                          loading={loading}
                        >
                          Đăng ký
                        </Button>
                      </Form.Item>

                      <p className="text-center text-gray-600 dark:text-gray-300 font-bevietnam text-sm mt-4">
                        Bằng cách đăng ký, bạn đồng ý với{" "}
                        <a
                          href="/terms"
                          className="text-primary-600 hover:text-primary-700 dark:text-primary-400"
                        >
                          Điều khoản dịch vụ
                        </a>{" "}
                        và{" "}
                        <a
                          href="/privacy"
                          className="text-primary-600 hover:text-primary-700 dark:text-primary-400"
                        >
                          Chính sách bảo mật
                        </a>{" "}
                        của chúng tôi.
                      </p>
                    </Form>
                  </TabPane>
                </Tabs>
                <div className="text-center mt-6">
                  {activeTab === "login" ? (
                    <Space>
                      <Text className="text-gray-600 dark:text-gray-300 font-bevietnam">
                        Chưa có tài khoản?
                      </Text>
                      <Button
                        type="link"
                        onClick={() => setActiveTab("register")}
                        className="p-0 font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 font-bevietnam"
                      >
                        Đăng ký ngay
                      </Button>
                    </Space>
                  ) : (
                    <Space>
                      <Text className="text-gray-600 dark:text-gray-300 font-bevietnam">
                        Đã có tài khoản?
                      </Text>
                      <Button
                        type="link"
                        onClick={() => setActiveTab("login")}
                        className="p-0 font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 font-bevietnam"
                      >
                        Đăng nhập
                      </Button>
                    </Space>
                  )}
                </div>
              </div>
            </Col>
          </Row>
        </Card>
      </div>
    </>
  );
};

export default Login;
