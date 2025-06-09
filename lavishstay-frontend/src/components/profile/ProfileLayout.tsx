import React, { useEffect, useState, useCallback, useMemo } from 'react';
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

const ProfileLayout: React.FC = React.memo(() => {
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

    const handleMenuClick = useCallback((path: string) => {
        navigate(path);
    }, [navigate]);

    const handleLogout = useCallback(() => {
        localStorage.removeItem('user');
        localStorage.removeItem('token');
        navigate('/');
    }, [navigate]);    // Memoize menu items to prevent re-renders
    const menuItems = useMemo(() => [
        {
            key: '/profile',
            icon: <UserOutlined />,
            label: 'Thông tin cá nhân',
            onClick: () => handleMenuClick('/profile'),
        },
        {
            key: '/profile/bookings',
            icon: <BookOutlined />,
            label: 'Lịch sử đặt phòng',
            onClick: () => handleMenuClick('/profile/bookings'),
        },
        {
            key: '/profile/wishlist',
            icon: <HeartOutlined />,
            label: 'Danh sách yêu thích',
            onClick: () => handleMenuClick('/profile/wishlist'),
        },
        {
            type: 'divider' as const,
        },
        {
            key: '/profile/change-password',
            icon: <KeyOutlined />,
            label: 'Đổi mật khẩu',
            onClick: () => handleMenuClick('/profile/change-password'),
        },
        {
            key: '/profile/forgot-password',
            icon: <LockOutlined />,
            label: 'Quên mật khẩu',
            onClick: () => handleMenuClick('/profile/forgot-password'),
        },
        {
            type: 'divider' as const,
        },
        {
            key: '/profile/notifications',
            icon: <BellOutlined />,
            label: 'Thông báo',
            onClick: () => handleMenuClick('/profile/notifications'),
        },
        {
            key: '/profile/settings',
            icon: <SettingOutlined />,
            label: 'Cài đặt',
            onClick: () => handleMenuClick('/profile/settings'),
        },
    ], [handleMenuClick]);

    const logoutMenuItem = useMemo(() => [{
        key: 'logout',
        icon: <LogoutOutlined />,
        label: 'Đăng xuất',
        onClick: handleLogout,
        danger: true,
    }], [handleLogout]);

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
            }}>                <Sider
                width={280}
                style={{
                    background: '#fafafa',
                    borderRight: '1px solid #e8e8e8',
                    display: 'flex',
                    flexDirection: 'column'
                }}
            >
                    {/* User Profile Header */}
                    <div style={{
                        padding: '24px',
                        borderBottom: '1px solid #e8e8e8',
                        textAlign: 'center',
                        flexShrink: 0
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
                    <div style={{
                        padding: '8px 0',
                        flex: 1,
                        minHeight: 0
                    }}>
                        <Menu
                            mode="inline"
                            selectedKeys={[location.pathname]}
                            items={menuItems}
                            style={{
                                border: 'none',
                                background: 'transparent'
                            }}
                        />
                    </div>

                    {/* Logout */}
                    <div style={{
                        padding: '12px',
                        flexShrink: 0,
                        marginTop: 'auto'
                    }}>
                        <Divider style={{ margin: '0 0 8px 0', borderColor: '#e8e8e8' }} />
                        <Menu
                            mode="inline"
                            items={logoutMenuItem}
                            style={{
                                border: 'none',
                                background: 'transparent'
                            }}
                        />
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
});

ProfileLayout.displayName = 'ProfileLayout';

export default ProfileLayout;
