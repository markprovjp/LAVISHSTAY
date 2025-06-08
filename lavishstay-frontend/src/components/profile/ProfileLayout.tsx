import React from 'react';
import { Layout, Menu, Avatar, Typography, Card, Space } from 'antd';
import {
    UserOutlined,
    BookOutlined,
    HeartOutlined,
    SettingOutlined,
    LockOutlined,
    MailOutlined,
    BellOutlined,
    SafetyOutlined
} from '@ant-design/icons';
import { useNavigate, useLocation, Outlet } from 'react-router-dom';
import { useSelector } from 'react-redux';
import { RootState } from '../../store';
import './ProfileLayout.css';

const { Sider, Content } = Layout;
const { Title, Text } = Typography;

const ProfileLayout: React.FC = () => {
    const navigate = useNavigate();
    const location = useLocation();
    const { isDarkMode } = useSelector((state: RootState) => state.theme);

    // Mock user data - thay thế bằng data thực từ store
    const currentUser = {
        id: 1,
        name: 'Nguyễn Văn A',
        email: 'nguyenvana@email.com',
        avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face',
        memberSince: '2023',
        totalBookings: 12,
        loyaltyLevel: 'Gold'
    };

    const menuItems = [
        {
            key: '/profile',
            icon: <UserOutlined />,
            label: 'Thông tin cá nhân',
            path: '/profile'
        },
        {
            key: '/profile/bookings',
            icon: <BookOutlined />,
            label: 'Đặt phòng của tôi',
            path: '/profile/bookings'
        },
        {
            key: '/profile/wishlist',
            icon: <HeartOutlined />,
            label: 'Danh sách yêu thích',
            path: '/profile/wishlist'
        },
        {
            key: '/profile/security',
            icon: <LockOutlined />,
            label: 'Bảo mật',
            path: '/profile/security'
        },
        {
            key: '/profile/change-password',
            icon: <SafetyOutlined />,
            label: 'Đổi mật khẩu',
            path: '/profile/change-password'
        },
        {
            key: '/profile/forgot-password',
            icon: <MailOutlined />,
            label: 'Quên mật khẩu',
            path: '/profile/forgot-password'
        },
        {
            key: '/profile/notifications',
            icon: <BellOutlined />,
            label: 'Thông báo',
            path: '/profile/notifications'
        },
        {
            key: '/profile/settings',
            icon: <SettingOutlined />,
            label: 'Cài đặt',
            path: '/profile/settings'
        }
    ];

    const handleMenuClick = (item: any) => {
        const menuItem = menuItems.find(menu => menu.key === item.key);
        if (menuItem) {
            navigate(menuItem.path);
        }
    };

    const getCurrentKey = () => {
        const currentPath = location.pathname;
        const menuItem = menuItems.find(item => item.path === currentPath);
        return menuItem ? menuItem.key : '/profile';
    };

    return (
        <div className="profile-layout-container">
            <Layout className="profile-layout">
                {/* Sidebar */}
                <Sider
                    width={320}
                    className="profile-sidebar"
                    theme={isDarkMode ? 'dark' : 'light'}
                    breakpoint="lg"
                    collapsedWidth="0"
                >
                    {/* User Profile Card */}
                    <Card className="profile-user-card" bordered={false}>
                        <div className="profile-user-info">
                            <div className="profile-avatar-container">
                                <Avatar
                                    size={80}
                                    src={currentUser.avatar}
                                    className="profile-avatar"
                                >
                                    {currentUser.name.charAt(0)}
                                </Avatar>
                                <div className="profile-status-badge">
                                    <span className="status-dot"></span>
                                    Online
                                </div>
                            </div>

                            <div className="profile-user-details">
                                <Title level={4} className="profile-user-name">
                                    {currentUser.name}
                                </Title>
                                <Text className="profile-user-email">
                                    {currentUser.email}
                                </Text>
                                <div className="profile-loyalty-badge">
                                    <span className="loyalty-level">{currentUser.loyaltyLevel} Member</span>
                                    <span className="member-since">Thành viên từ {currentUser.memberSince}</span>
                                </div>
                            </div>
                        </div>

                        {/* Stats */}
                        <div className="profile-stats">
                            <div className="stat-item">
                                <div className="stat-number">{currentUser.totalBookings}</div>
                                <div className="stat-label">Lượt đặt phòng</div>
                            </div>
                            <div className="stat-divider"></div>
                            <div className="stat-item">
                                <div className="stat-number">4.9</div>
                                <div className="stat-label">Đánh giá TB</div>
                            </div>
                        </div>
                    </Card>

                    {/* Navigation Menu */}
                    <Menu
                        mode="inline"
                        selectedKeys={[getCurrentKey()]}
                        className="profile-menu"
                        items={menuItems}
                        onClick={handleMenuClick}
                    />
                </Sider>

                {/* Main Content */}
                <Layout className="profile-content-layout">
                    <Content className="profile-content">
                        <div className="profile-content-wrapper">
                            <Outlet />
                        </div>
                    </Content>
                </Layout>
            </Layout>
        </div>
    );
};

export default ProfileLayout;
