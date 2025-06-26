
import React from "react";
import { Card, Space, Typography, Button, Alert } from "antd";
import { useSelector, useDispatch } from "react-redux";
import { RootState } from "../store";
import { logout } from "../store/slices/authSlice";

const { Title, Text } = Typography;

const AuthTest: React.FC = () => {
    const dispatch = useDispatch();
    const { isAuthenticated, user, token } = useSelector((state: RootState) => state.auth);

    const handleLogout = () => {
        dispatch(logout());
    };

    return (
        <div style={{ padding: "40px 20px", maxWidth: "800px", margin: "0 auto" }}>
            <Title level={2}>Trang Test Authentication</Title>

            <Space direction="vertical" size="large" style={{ width: "100%" }}>
                <Card title="Trạng thái đăng nhập" style={{ width: "100%" }}>
                    <Space direction="vertical" size="middle">
                        <div>
                            <Text strong>Đã đăng nhập: </Text>
                            <Text type={isAuthenticated ? "success" : "danger"}>
                                {isAuthenticated ? "Có ✅" : "Không ❌"}
                            </Text>
                        </div>

                        {isAuthenticated && user && (
                            <>
                                <div>
                                    <Text strong>Tên người dùng: </Text>
                                    <Text>{user.name}</Text>
                                </div>
                                <div>
                                    <Text strong>Email: </Text>
                                    <Text>{user.email}</Text>
                                </div>
                                <div>
                                    <Text strong>Role: </Text>
                                    <Text>{user.role}</Text>
                                </div>
                                <div>
                                    <Text strong>Avatar: </Text>
                                    {user.avatar ? (
                                        <img
                                            src={user.avatar}
                                            alt="Avatar"
                                            style={{ width: 40, height: 40, borderRadius: "50%", marginLeft: 8 }}
                                        />
                                    ) : (
                                        <Text type="secondary">Không có</Text>
                                    )}
                                </div>
                                <div>
                                    <Text strong>Token: </Text>
                                    <Text code style={{ fontSize: "10px" }}>
                                        {token ? `${token.substring(0, 30)}...` : "Không có"}
                                    </Text>
                                </div>

                                <Button type="primary" danger onClick={handleLogout}>
                                    Đăng xuất
                                </Button>
                            </>
                        )}
                    </Space>
                </Card>

                {!isAuthenticated && (
                    <Alert
                        message="Chưa đăng nhập"
                        description="Bạn cần đăng nhập để xem thông tin chi tiết. Hãy nhấn nút 'Đăng nhập' ở header."
                        type="info"
                        showIcon
                    />
                )}

                <Card title="Hướng dẫn test" style={{ width: "100%" }}>
                    <Space direction="vertical" size="small">
                        <Text strong>Các tài khoản test:</Text>
                        <div style={{ marginLeft: 16 }}>
                            <div>📧 user@example.com | 🔑 123456</div>
                            <div>📧 admin@example.com | 🔑 admin123</div>
                            <div>📧 test@example.com | 🔑 test123</div>
                        </div>
                        <Text strong>Cách test:</Text>
                        <div style={{ marginLeft: 16 }}>
                            <div>1. Nhấn nút "Đăng nhập" ở header</div>
                            <div>2. Nhập một trong các tài khoản trên</div>
                            <div>3. Nhấn "Đăng nhập"</div>
                            <div>4. Kiểm tra thông tin ở trang này</div>
                            <div>5. Test chức năng đăng xuất</div>
                        </div>
                    </Space>
                </Card>
            </Space>
        </div>
    );
};

export default AuthTest;
