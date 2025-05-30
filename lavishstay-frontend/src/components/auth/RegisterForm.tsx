import React from "react";
import { Form, Input, Button, Divider } from "antd";
import { FcGoogle } from "react-icons/fc";
import { useSelector } from "react-redux";
import type { RootState } from "../../store";

interface RegisterFormProps {
  onSwitchToLogin?: () => void;
  formItemStyle?: React.CSSProperties;
  inputSize?: "large" | "middle" | "small";
}

const RegisterForm: React.FC<RegisterFormProps> = ({
  onSwitchToLogin,
  formItemStyle,
  inputSize = "middle",
}) => {
  const isDarkMode = useSelector((state: RootState) => state.theme?.isDarkMode);

  const onFinish = (values: any) => {
    // Xử lý đăng ký ở đây
    console.log("Register values:", values);
  };

  const handleGoogleRegister = () => {
    // Xử lý đăng ký bằng Google ở đây
    console.log("Register with Google");
  };

  return (
    <Form
      name="register"
      layout="vertical"
      onFinish={onFinish}
      autoComplete="off"
      requiredMark={false}
    >
      <Form.Item
        label={
          <span style={{ color: isDarkMode ? "#fff" : undefined }}>
            Họ và tên
          </span>
        }
        name="name"
        style={formItemStyle}
        rules={[{ required: true, message: "Vui lòng nhập họ tên!" }]}
      >
        <Input
          placeholder="Nhập họ và tên đầy đủ"
          size={inputSize}
          allowClear
          autoFocus
        />
      </Form.Item>

      <Form.Item
        label={
          <span style={{ color: isDarkMode ? "#fff" : undefined }}>Email</span>
        }
        name="email"
        style={formItemStyle}
        rules={[
          { required: true, message: "Vui lòng nhập email!" },
          { type: "email", message: "Email không hợp lệ!" },
        ]}
      >
        <Input placeholder="Nhập email của bạn" size={inputSize} allowClear />
      </Form.Item>

      <Form.Item
        label={
          <span style={{ color: isDarkMode ? "#fff" : undefined }}>
            Mật khẩu
          </span>
        }
        name="password"
        style={formItemStyle}
        rules={[{ required: true, message: "Vui lòng nhập mật khẩu!" }]}
        hasFeedback
      >
        <Input.Password
          placeholder="Tạo mật khẩu mới"
          size={inputSize}
          allowClear
        />
      </Form.Item>

      <Form.Item
        label={
          <span style={{ color: isDarkMode ? "#fff" : undefined }}>
            Nhập lại mật khẩu
          </span>
        }
        name="confirm"
        style={formItemStyle}
        dependencies={["password"]}
        hasFeedback
        rules={[
          { required: true, message: "Vui lòng nhập lại mật khẩu!" },
          ({ getFieldValue }) => ({
            validator(_, value) {
              if (!value || getFieldValue("password") === value) {
                return Promise.resolve();
              }
              return Promise.reject(new Error("Mật khẩu không khớp!"));
            },
          }),
        ]}
      >
        <Input.Password
          placeholder="Nhập lại mật khẩu"
          size={inputSize}
          allowClear
        />
      </Form.Item>

      <Form.Item style={formItemStyle} className="pt-4">
        <Button type="primary" htmlType="submit" block size={inputSize}>
          Đăng ký
        </Button>
      </Form.Item>

      <Divider plain style={{ margin: "12px 0" }}>
        hoặc
      </Divider>

      <Form.Item style={formItemStyle}>
        <Button
          icon={<FcGoogle size={22} />}
          block
          size={inputSize}
          style={{
            background: isDarkMode ? "#23272f" : "#fff",
            color: isDarkMode ? "#fff" : "#222",
            border: isDarkMode ? "1px solid #444" : "1px solid #d9d9d9",
            fontWeight: 500,
            display: "flex",
            alignItems: "center",
            justifyContent: "center",
            gap: 8,
          }}
          onClick={handleGoogleRegister}
        >
          Đăng ký với Google
        </Button>
      </Form.Item>

      <div style={{ textAlign: "center", marginTop: 8 }}>
        Đã có tài khoản?{" "}
        <Button type="link" onClick={onSwitchToLogin} style={{ padding: 0 }}>
          Đăng nhập
        </Button>
      </div>
    </Form>
  );
};

export default RegisterForm;