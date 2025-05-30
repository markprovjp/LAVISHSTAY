import React, { useState } from "react";
import { Modal } from "antd";
import LoginForm from "./LoginForm";
import RegisterForm from "./RegisterForm";
import LoginInfo from "./LoginInfo";

interface AuthModalProps {
    open: boolean;
    onClose: () => void;
}

const AuthModal: React.FC<AuthModalProps> = ({ open, onClose }) => {
    const [activeKey, setActiveKey] = useState<"login" | "register">("login");

    return (
        <Modal
            open={open}
            onCancel={onClose}
            footer={null}
            centered
            width={560}
            destroyOnClose
            bodyStyle={{
                padding: "18px 18px 10px 18px",
                borderRadius: 14,
                minHeight: 0,
            }}
        >
            <h2
                style={{
                    textAlign: "center",
                    fontSize: 22,
                    fontWeight: 800,
                    marginBottom: 12,
                    marginTop: 0,
                    letterSpacing: 0.5,
                }}
            >
                {activeKey === "login" ? "Đăng nhập" : "Đăng ký"}
            </h2>
            <div style={{ maxWidth: 480, margin: "0 auto" }}>
                {activeKey === "login" && <LoginInfo />}
                {activeKey === "login" ? (
                    <LoginForm
                        onSwitchToRegister={() => setActiveKey("register")}
                        onForgotPassword={() => alert("Chức năng quên mật khẩu!")}
                        onLoginSuccess={onClose} // Đóng modal khi đăng nhập thành công
                        formItemStyle={{ marginBottom: 10 }}
                        inputSize="middle"
                    />
                ) : (
                    <RegisterForm
                        onSwitchToLogin={() => setActiveKey("login")}
                        formItemStyle={{ marginBottom: 10 }}
                        inputSize="middle"
                    />
                )}
            </div>
        </Modal>
    );
};

export default AuthModal;