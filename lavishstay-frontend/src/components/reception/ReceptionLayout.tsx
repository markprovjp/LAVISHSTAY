import React, { useState } from 'react';
import { Layout, Menu, Typography, Button } from 'antd';
import { Link, Outlet, useLocation } from 'react-router-dom';
import {
    DashboardOutlined,
    BookOutlined,
    HomeOutlined,
    CalendarOutlined,
    TeamOutlined,
    MenuFoldOutlined,
    MenuUnfoldOutlined
} from '@ant-design/icons';

const { Sider, Content } = Layout;
const { Title } = Typography;

const ReceptionLayout: React.FC = () => {
    const location = useLocation();
    const [collapsed, setCollapsed] = useState(false);

    const menuItems = [
        {
            key: '/reception/dashboard',
            icon: <DashboardOutlined />,
            label: <Link to="/reception/dashboard">Tổng quan</Link>,
        },
        {
            key: '/reception/room-management',
            icon: <HomeOutlined />,
            label: 'Quản lý phòng',
            children: [
                {
                    key: '/reception/room-management-list',
                    label: <Link to="/reception/room-management-list">Danh sách phòng</Link>,
                },
                {
                    key: '/reception/confirm-representative-payment',
                    label: <Link to="/reception/confirm-representative-payment">Xác nhận thanh toán</Link>,
                },
                {
                    key: '/reception/room-management/today',
                    label: <Link to="/reception/room-management/today">Phòng hôm nay</Link>,
                },
                {
                    key: '/reception/room-management/check-in',
                    label: <Link to="/reception/room-management/check-in">Check-in</Link>,
                },
                {
                    key: '/reception/room-management/check-out',
                    label: <Link to="/reception/room-management/check-out">Check-out</Link>,
                },
                {
                    key: '/reception/room-management/maintenance',
                    label: <Link to="/reception/room-management/maintenance">Bảo trì</Link>,
                }
            ]
        },
        {
            key: '/reception/guests',
            icon: <TeamOutlined />,
            label: 'Quản lý khách',
            children: [
                {
                    key: '/reception/guests/list',
                    label: <Link to="/reception/guests/list">Danh sách khách</Link>,
                },
                {
                    key: '/reception/guests/history',
                    label: <Link to="/reception/guests/history">Lịch sử lưu trú</Link>,
                }
            ]
        },
        {
            key: '/reception/schedule',
            icon: <CalendarOutlined />,
            label: <Link to="/reception/schedule">Lịch đặt phòng</Link>,
        }
    ];

    return (
        <Layout style={{ minHeight: '100vh' }}>
            <Sider
                width={250}
                theme="light"
                collapsible
                collapsed={collapsed}
                onCollapse={setCollapsed}
                trigger={null}
                style={{
                    overflow: 'auto',
                    height: '100vh',
                    position: 'fixed',
                    left: 0,
                    top: 110,
                    bottom: 0,
                }}
            >
                <div className="p-4 border-b">
                    <div className="flex items-center justify-between">
                        <div className="flex items-center">
                            <div className="w-8 h-8  rounded-lg flex items-center justify-center mr-3">
                                <img
                                    src="../../../public/images/logo.png"
                                    alt="Logo"
                                    style={{ width: '100%', height: '100%', objectFit: 'cover' }}
                                    className='rounded-lg'
                                />
                            </div>
                            {!collapsed && (
                                <Title level={4} className="mb-0">
                                    Lễ tân
                                </Title>
                            )}
                        </div>
                        <Button
                            type="text"
                            icon={collapsed ? <MenuUnfoldOutlined /> : <MenuFoldOutlined />}
                            onClick={() => setCollapsed(!collapsed)}
                            style={{
                                fontSize: '16px',
                                width: 32,
                                height: 32,
                            }}
                        />
                    </div>
                </div>
                <Menu
                    mode="inline"
                    selectedKeys={[location.pathname]}
                    defaultOpenKeys={['/reception/room-management', '/reception/guests']}
                    items={menuItems}
                    style={{ border: 'none' }}
                />
            </Sider>
            <Layout style={{ marginLeft: collapsed ? 80 : 250, transition: 'margin-left 0.2s' }}>
                <Content style={{  overflow: 'auto' }}>
                    <Outlet />
                </Content>
            </Layout>
        </Layout>
    );
};

export default ReceptionLayout;
