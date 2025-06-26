
import React from "react";
import { Alert, Typography, Space } from "antd";
import { InfoCircleOutlined } from "@ant-design/icons";

const { Text } = Typography;

const LoginInfo: React.FC = () => {
    return (
        <Alert
            message="Thông tin đăng nhập thử nghiệm"
            description={
                <Space direction="vertical" size="small">
                    <Text strong>Tài khoản người dùng:</Text>
                    <Text code>Email: user@example.com | Mật khẩu: 123456</Text>
                    <Text strong>Tài khoản lễ tân:</Text>
                    <Text code>Email: reception@hotel.com | Mật khẩu: reception123</Text>
                    <Text strong>Tài khoản admin:</Text>
                    <Text code>Email: admin@example.com | Mật khẩu: admin123</Text>
                </Space>
            }
            type="info"
            icon={<InfoCircleOutlined />}
            showIcon
            style={{ marginBottom: 16, fontSize: "12px" }}
            closable
        />
    );
};

export default LoginInfo;
