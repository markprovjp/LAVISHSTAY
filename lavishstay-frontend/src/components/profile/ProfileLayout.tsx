import React, { useEffect, useState } from 'react';
import { Layout, Menu, Avatar, Typography, Spin, Divider } from 'antd';
import {
    UserOutlined,
    BookOutlined,
    HeartOutlined,
    SettingOutlined,
    BellOutlined,
    SafetyOutlined,
    LogoutOutlined,
    LockOutlined,
    KeyOutlined,
} from '@ant-design/icons';
import { useNavigate, useLocation, Outlet } from 'react-router-dom';
import { profileService, type UserProfile } from '../../services/profileService';

const { Sider, Content } = Layout;
const { Title, Text } = Typography;

const ProfileLayout: React.FC = () => {
    const navigate = useNavigate();
    const location = useLocation();
    const [userProfile, setUserProfile] = useState<UserProfile | null>(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const loadUserProfile = async () => {
            try {
                const profile = await profileService.getUserProfile();
                setUserProfile(profile);
            } catch (error) {
                console.error('Error loading user profile:', error);
            } finally {
                setLoading(false);
            }
        }; loadUserProfile();
    }, []);

    const handleMenuClick = (path: string) => {
        navigate(path);
    };

    const handleLogout = () => {
        localStorage.removeItem('user');
        localStorage.removeItem('token');
        navigate('/');
    };

    if (loading) {
        return (
            <div style={{
                display: 'flex',
                justifyContent: 'center',
                alignItems: 'center',
                height: '200px'
            }}>
                <Spin size="large" />
            </div>
        );
    } return (
        <div className="container mx-auto px-4 py-8">
            <Layout style={{
                background: 'white',
                borderRadius: '12px',
                overflow: 'hidden',
                boxShadow: '0 4px 20px rgba(0,0,0,0.08)',
                minHeight: '600px'
            }}>
                <Sider
                    width={280}
                    style={{
                        background: '#fafafa',
                        borderRight: '1px solid #e8e8e8'
                    }}
                >
                    {/* User Profile Header */}
                    <div style={{
                        padding: '24px',
                        borderBottom: '1px solid #e8e8e8',
                        textAlign: 'center'
                    }}>
                        <Avatar
                            size={64}
                            src={userProfile?.avatar}
                            style={{
                                backgroundColor: '#1890ff',
                                marginBottom: '12px'
                            }}
                        >
                            {userProfile?.name?.[0]?.toUpperCase()}
                        </Avatar>

                        <div>
                            <Title level={5} style={{
                                margin: '0 0 4px 0',
                                color: '#262626',
                                fontWeight: 600
                            }}>
                                {userProfile?.name}
                            </Title>
                            <Text type="secondary" style={{
                                fontSize: '13px',
                                color: '#8c8c8c'
                            }}>
                                {userProfile?.email}
                            </Text>
                        </div>
                    </div>

                    {/* Navigation Menu */}
                    <div style={{ padding: '8px 0' }}>
                        <Menu
                            mode="inline"
                            selectedKeys={[location.pathname]}
                            style={{
                                border: 'none',
                                background: 'transparent'
                            }}
                        >
                            {/* Main Section */}
                            <Menu.Item
                                key="/profile"
                                icon={<UserOutlined />}
                                onClick={() => handleMenuClick('/profile')}
                                style={{
                                    margin: '4px 12px',
                                    borderRadius: '6px',
                                    height: '40px',
                                    lineHeight: '40px'
                                }}
                            >
                                Thông tin cá nhân
                            </Menu.Item>
                            <Menu.Item
                                key="/profile/bookings"
                                icon={<BookOutlined />}
                                onClick={() => handleMenuClick('/profile/bookings')}
                                style={{
                                    margin: '4px 12px',
                                    borderRadius: '6px',
                                    height: '40px',
                                    lineHeight: '40px'
                                }}
                            >
                                Lịch sử đặt phòng
                            </Menu.Item>
                            <Menu.Item
                                key="/profile/wishlist"
                                icon={<HeartOutlined />}
                                onClick={() => handleMenuClick('/profile/wishlist')}
                                style={{
                                    margin: '4px 12px',
                                    borderRadius: '6px',
                                    height: '40px',
                                    lineHeight: '40px'
                                }}
                            >
                                Danh sách yêu thích
                            </Menu.Item>

                            <Divider style={{ margin: '8px 0', borderColor: '#e8e8e8' }} />

                            {/* Security Section */}
                            <Menu.Item
                                key="/profile/change-password"
                                icon={<KeyOutlined />}
                                onClick={() => handleMenuClick('/profile/change-password')}
                                style={{
                                    margin: '4px 12px',
                                    borderRadius: '6px',
                                    height: '40px',
                                    lineHeight: '40px'
                                }}
                            >
                                Đổi mật khẩu
                            </Menu.Item>
                            <Menu.Item
                                key="/profile/forgot-password"
                                icon={<LockOutlined />}
                                onClick={() => handleMenuClick('/profile/forgot-password')}
                                style={{
                                    margin: '4px 12px',
                                    borderRadius: '6px',
                                    height: '40px',
                                    lineHeight: '40px'
                                }}
                            >
                                Quên mật khẩu
                            </Menu.Item>

                            <Divider style={{ margin: '8px 0', borderColor: '#e8e8e8' }} />

                            {/* Settings Section */}
                            <Menu.Item
                                key="/profile/notifications"
                                icon={<BellOutlined />}
                                onClick={() => handleMenuClick('/profile/notifications')}
                                style={{
                                    margin: '4px 12px',
                                    borderRadius: '6px',
                                    height: '40px',
                                    lineHeight: '40px'
                                }}
                            >
                                Thông báo
                            </Menu.Item>
                            <Menu.Item
                                key="/profile/security"
                                icon={<SafetyOutlined />}
                                onClick={() => handleMenuClick('/profile/security')}
                                style={{
                                    margin: '4px 12px',
                                    borderRadius: '6px',
                                    height: '40px',
                                    lineHeight: '40px'
                                }}
                            >
                                Bảo mật
                            </Menu.Item>
                            <Menu.Item
                                key="/profile/settings"
                                icon={<SettingOutlined />}
                                onClick={() => handleMenuClick('/profile/settings')}
                                style={{
                                    margin: '4px 12px',
                                    borderRadius: '6px',
                                    height: '40px',
                                    lineHeight: '40px'
                                }}
                            >
                                Cài đặt
                            </Menu.Item>
                        </Menu>
                    </div>

                    {/* Logout */}
                    <div style={{
                        position: 'absolute',
                        bottom: '16px',
                        left: '12px',
                        right: '12px'
                    }}>
                        <Divider style={{ margin: '0 0 8px 0', borderColor: '#e8e8e8' }} />
                        <Menu
                            mode="inline"
                            style={{
                                border: 'none',
                                background: 'transparent'
                            }}
                        >
                            <Menu.Item
                                key="logout"
                                icon={<LogoutOutlined />}
                                onClick={handleLogout}
                                danger
                                style={{
                                    borderRadius: '6px',
                                    height: '40px',
                                    lineHeight: '40px'
                                }}
                            >
                                Đăng xuất
                            </Menu.Item>
                        </Menu>
                    </div>
                </Sider>

                <Content style={{
                    padding: '32px',
                    background: 'white',
                    minHeight: '600px',
                    overflow: 'auto'
                }}>
                    <Outlet />
                </Content>
            </Layout>
        </div>
    );
};

export default ProfileLayout;
