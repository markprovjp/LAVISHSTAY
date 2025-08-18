import React, { useEffect, useState } from 'react';
import { Card, Button, List, Badge, Typography, Space, message, Spin } from 'antd';
import { BellOutlined, CheckOutlined } from '@ant-design/icons';
import { useNotifications } from '../contexts/NotificationContext';
import { NotificationData } from '../utils/echo';

const { Title, Text } = Typography;

const NotificationsPage: React.FC = () => {
    const {
        notifications,
        unreadCount,
        loading,
        fetchNotifications,
        markAsRead,
        markAllAsRead
    } = useNotifications();

    const [localLoading, setLocalLoading] = useState(false);

    useEffect(() => {
        fetchNotifications();
    }, [fetchNotifications]);

    const handleMarkAsRead = async (notificationId: string) => {
        setLocalLoading(true);
        try {
            await markAsRead(notificationId);
            message.success('Đã đánh dấu là đã đọc');
        } catch (error) {
            message.error('Có lỗi xảy ra khi đánh dấu đã đọc');
        } finally {
            setLocalLoading(false);
        }
    };

    const handleMarkAllAsRead = async () => {
        setLocalLoading(true);
        try {
            await markAllAsRead();
            message.success('Đã đánh dấu tất cả là đã đọc');
        } catch (error) {
            message.error('Có lỗi xảy ra khi đánh dấu tất cả đã đọc');
        } finally {
            setLocalLoading(false);
        }
    };

    const formatDate = (dateString: string) => {
        const date = new Date(dateString);
        return date.toLocaleString('vi-VN', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
        });
    };

    return (
        <div style={{ padding: '24px', maxWidth: '800px', margin: '0 auto' }}>
            <Card>
                <Space direction="vertical" style={{ width: '100%' }}>
                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                        <Title level={2} style={{ margin: 0 }}>
                            <BellOutlined style={{ marginRight: '8px' }} />
                            Thông báo
                            {unreadCount > 0 && (
                                <Badge count={unreadCount} style={{ marginLeft: '12px' }} />
                            )}
                        </Title>
                        {unreadCount > 0 && (
                            <Button
                                type="primary"
                                icon={<CheckOutlined />}
                                onClick={handleMarkAllAsRead}
                                loading={localLoading}
                            >
                                Đánh dấu tất cả đã đọc
                            </Button>
                        )}
                    </div>

                    {loading ? (
                        <div style={{ textAlign: 'center', padding: '40px' }}>
                            <Spin size="large" />
                        </div>
                    ) : notifications.length === 0 ? (
                        <div style={{ textAlign: 'center', padding: '40px' }}>
                            <BellOutlined style={{ fontSize: '48px', color: '#d9d9d9' }} />
                            <Title level={4} style={{ color: '#8c8c8c', marginTop: '16px' }}>
                                Không có thông báo nào
                            </Title>
                            <Text type="secondary">
                                Các thông báo mới sẽ xuất hiện ở đây
                            </Text>
                        </div>
                    ) : (
                        <List
                            dataSource={notifications}
                            renderItem={(notification: NotificationData) => (
                                <List.Item
                                    style={{
                                        backgroundColor: !notification.read_at ? '#f6ffed' : 'white',
                                        border: !notification.read_at ? '1px solid #b7eb8f' : '1px solid #f0f0f0',
                                        borderRadius: '8px',
                                        margin: '8px 0',
                                        padding: '16px',
                                    }}
                                    actions={[
                                        !notification.read_at && (
                                            <Button
                                                type="link"
                                                size="small"
                                                onClick={() => handleMarkAsRead(notification.id)}
                                                loading={localLoading}
                                            >
                                                Đánh dấu đã đọc
                                            </Button>
                                        ),
                                    ].filter(Boolean)}
                                >
                                    <List.Item.Meta
                                        avatar={
                                            <div style={{
                                                width: '48px',
                                                height: '48px',
                                                borderRadius: '50%',
                                                backgroundColor: !notification.read_at ? '#52c41a' : '#d9d9d9',
                                                display: 'flex',
                                                alignItems: 'center',
                                                justifyContent: 'center'
                                            }}>
                                                <BellOutlined style={{
                                                    color: 'white',
                                                    fontSize: '20px'
                                                }} />
                                            </div>
                                        }
                                        title={
                                            <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                                                <Text strong>
                                                    {notification.data?.message || 'Thông báo mới'}
                                                </Text>
                                                {!notification.read_at && (
                                                    <Badge status="processing" />
                                                )}
                                            </div>
                                        }
                                        description={
                                            <Space direction="vertical" size="small">
                                                {notification.data?.booking_code && (
                                                    <Text type="secondary">
                                                        Mã đặt phòng: {notification.data.booking_code}
                                                    </Text>
                                                )}
                                                <Text type="secondary">
                                                    {formatDate(notification.created_at)}
                                                </Text>
                                            </Space>
                                        }
                                    />
                                </List.Item>
                            )}
                        />
                    )}
                </Space>
            </Card>
        </div>
    );
};

export default NotificationsPage;
