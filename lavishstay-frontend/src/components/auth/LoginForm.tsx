import React from "react";
import { Form, Input, Button, Divider, message } from "antd";
import { useSelector, useDispatch } from "react-redux";
import type { RootState } from "../../store";
import { loginStart, loginSuccess, loginFailure } from "../../store/slices/authSlice";
import authService, { LoginCredentials } from "../../services/authService";
import GoogleLoginButton from "./GoogleLoginButton";

interface LoginFormProps {
    onSwitchToRegister?: () => void;
    onForgotPassword?: () => void;
    onLoginSuccess?: () => void; // Callback khi đăng nhập thành công
    formItemStyle?: React.CSSProperties;
    inputSize?: "large" | "middle" | "small";
}

const LoginForm: React.FC<LoginFormProps> = ({
    onSwitchToRegister,
    onForgotPassword,
    onLoginSuccess,
    formItemStyle,
    inputSize = "middle",
}) => {
    const dispatch = useDispatch();
    const { isLoading } = useSelector((state: RootState) => state.auth);
    const isDarkMode = useSelector((state: RootState) => state.theme?.isDarkMode);

    const onFinish = async (values: LoginCredentials) => {
        try {
            dispatch(loginStart());

            const response = await authService.login(values);

            dispatch(loginSuccess({
                user: response.user,
                token: response.token
            }));

            message.success(`Chào mừng ${response.user.name}! Đăng nhập thành công.`);

            // Gọi callback nếu có
            if (onLoginSuccess) {
                onLoginSuccess();
            }

        } catch (error: any) {
            console.error("Login error:", error);

            const errorMessage = error?.response?.data?.error ||
                error?.message ||
                "Đăng nhập thất bại. Vui lòng thử lại!";

            dispatch(loginFailure(errorMessage));
            message.error(errorMessage);
        }
    };

    return (
        <Form
            name="login"
            layout="vertical"
            onFinish={onFinish}
            autoComplete="off"
            requiredMark={false}
        >
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
                <Input
                    placeholder="Nhập email của bạn"
                    size={inputSize}
                    allowClear
                    autoFocus
                />
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
            >
                <Input.Password
                    placeholder="Nhập mật khẩu của bạn"
                    size={inputSize}
                    allowClear
                />
            </Form.Item>

            <div
                style={{ display: "flex", justifyContent: "flex-end", marginBottom: 8 }}
            >
                <Button type="link" onClick={onForgotPassword} style={{ padding: 0 }}>
                    Quên mật khẩu?
                </Button>
            </div>      <Form.Item style={formItemStyle}>
                <Button
                    type="primary"
                    htmlType="submit"
                    block
                    size={inputSize}
                    loading={isLoading}
                    disabled={isLoading}
                >
                    {isLoading ? "Đang đăng nhập..." : "Đăng nhập"}
                </Button>
            </Form.Item>

            <Divider plain style={{ margin: "12px 0" }}>
                hoặc
            </Divider>

            <Form.Item style={formItemStyle}>
                <GoogleLoginButton
                    onSuccess={onLoginSuccess}
                    size={inputSize}
                    block
                    style={{
                        background: isDarkMode ? "#23272f" : "#fff",
                        color: isDarkMode ? "#fff" : "#222",
                        border: isDarkMode ? "1px solid #444" : "1px solid #d9d9d9",
                    }}
                />
            </Form.Item>

            <div style={{ textAlign: "center", marginTop: 8 }}>
                Chưa có tài khoản?{" "}
                <Button type="link" onClick={onSwitchToRegister} style={{ padding: 0 }}>
                    Đăng ký tài khoản
                </Button>
            </div>
        </Form>
    );
};

export default LoginForm;