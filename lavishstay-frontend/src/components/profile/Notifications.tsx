import React, { useState } from 'react';
import {
  Card,
  Typography,
  Space,
  Button,
  List,
  Avatar,
  Badge,
  Switch,
  Tabs,
  Empty,
  Modal,
  Row,
  Col,
  Select,
  Tooltip,
  Tag,
  Divider
} from 'antd';
import {
  BellOutlined,
  MailOutlined,
  CalendarOutlined,
  GiftOutlined,
  ExclamationCircleOutlined,
  CheckOutlined,
  DeleteOutlined,
  SettingOutlined,
  ClearOutlined,
  FilterOutlined,
  StarFilled,
  HeartFilled,
  ShoppingCartOutlined
} from '@ant-design/icons';
import './Notifications.css';

const { Title, Text, Paragraph } = Typography;
const { TabPane } = Tabs;
const { Option } = Select;

interface Notification {
  id: string;
  type: 'booking' | 'promotion' | 'system' | 'review' | 'wishlist';
  title: string;
  message: string;
  timestamp: string;
  read: boolean;
  priority: 'low' | 'medium' | 'high';
  actionUrl?: string;
  image?: string;
}

interface NotificationSettings {
  emailNotifications: boolean;
  pushNotifications: boolean;
  smsNotifications: boolean;
  promotionalEmails: boolean;
  bookingUpdates: boolean;
  reviewReminders: boolean;
  priceAlerts: boolean;
}

const Notifications: React.FC = () => {
  const [activeTab, setActiveTab] = useState('all');
  const [filterType, setFilterType] = useState('all');
  const [showUnreadOnly, setShowUnreadOnly] = useState(false);

  const [notifications] = useState<Notification[]>([
    {
      id: '1',
      type: 'booking',
      title: 'Booking Confirmation',
      message: 'Your booking at Luxury Ocean View Resort has been confirmed for January 25-28, 2024.',
      timestamp: '2024-01-20T10:30:00Z',
      read: false,
      priority: 'high',
      actionUrl: '/bookings/123',
      image: '/images/hotel1.jpg'
    },
    {
      id: '2',
      type: 'promotion',
      title: 'Special Weekend Offer',
      message: 'Get 30% off on all beach resorts this weekend. Limited time offer!',
      timestamp: '2024-01-19T15:45:00Z',
      read: false,
      priority: 'medium',
      actionUrl: '/promotions/weekend-offer'
    },
    {
      id: '3',
      type: 'system',
      title: 'Profile Updated',
      message: 'Your profile information has been successfully updated.',
      timestamp: '2024-01-18T09:15:00Z',
      read: true,
      priority: 'low'
    },
    {
      id: '4',
      type: 'review',
      title: 'Review Reminder',
      message: 'How was your stay at City Center Business Hotel? Share your experience.',
      timestamp: '2024-01-17T14:20:00Z',
      read: false,
      priority: 'medium',
      actionUrl: '/reviews/write'
    },
    {
      id: '5',
      type: 'wishlist',
      title: 'Price Drop Alert',
      message: 'Good news! The price for Mountain Villa Retreat has dropped by 20%.',
      timestamp: '2024-01-16T11:00:00Z',
      read: true,
      priority: 'medium',
      actionUrl: '/property/mountain-villa'
    }
  ]);

  const [settings, setSettings] = useState<NotificationSettings>({
    emailNotifications: true,
    pushNotifications: true,
    smsNotifications: false,
    promotionalEmails: true,
    bookingUpdates: true,
    reviewReminders: true,
    priceAlerts: true
  });

  const getNotificationIcon = (type: string) => {
    switch (type) {
      case 'booking':
        return <CalendarOutlined style={{ color: '#52c41a' }} />;
      case 'promotion':
        return <GiftOutlined style={{ color: '#fa8c16' }} />;
      case 'system':
        return <SettingOutlined style={{ color: '#1890ff' }} />;
      case 'review':
        return <StarFilled style={{ color: '#d4af37' }} />;
      case 'wishlist':
        return <HeartFilled style={{ color: '#ff4d4f' }} />;
      default:
        return <BellOutlined style={{ color: '#666' }} />;
    }
  };

  const getPriorityColor = (priority: string) => {
    switch (priority) {
      case 'high':
        return '#ff4d4f';
      case 'medium':
        return '#fa8c16';
      case 'low':
        return '#52c41a';
      default:
        return '#d9d9d9';
    }
  };

  const formatTimestamp = (timestamp: string) => {
    const date = new Date(timestamp);
    const now = new Date();
    const diffInHours = Math.floor((now.getTime() - date.getTime()) / (1000 * 60 * 60));
    
    if (diffInHours < 1) {
      return 'Just now';
    } else if (diffInHours < 24) {
      return `${diffInHours}h ago`;
    } else if (diffInHours < 168) {
      return `${Math.floor(diffInHours / 24)}d ago`;
    } else {
      return date.toLocaleDateString();
    }
  };

  const filteredNotifications = notifications.filter(notification => {
    if (showUnreadOnly && notification.read) return false;
    if (filterType !== 'all' && notification.type !== filterType) return false;
    if (activeTab !== 'all' && activeTab !== notification.type) return false;
    return true;
  });

  const unreadCount = notifications.filter(n => !n.read).length;

  const handleMarkAsRead = (id: string) => {
    // API call to mark notification as read
    console.log('Mark as read:', id);
  };

  const handleMarkAllAsRead = () => {
    Modal.confirm({
      title: 'Mark All as Read',
      content: 'Are you sure you want to mark all notifications as read?',
      onOk() {
        // API call to mark all as read
        console.log('Mark all as read');
      },
    });
  };

  const handleDeleteNotification = (id: string) => {
    Modal.confirm({
      title: 'Delete Notification',
      content: 'Are you sure you want to delete this notification?',
      okType: 'danger',
      onOk() {
        // API call to delete notification
        console.log('Delete notification:', id);
      },
    });
  };

  const handleClearAll = () => {
    Modal.confirm({
      title: 'Clear All Notifications',
      content: 'This will permanently delete all notifications. This action cannot be undone.',
      okType: 'danger',
      onOk() {
        // API call to clear all notifications
        console.log('Clear all notifications');
      },
    });
  };

  const handleSettingChange = (key: keyof NotificationSettings, value: boolean) => {
    setSettings(prev => ({
      ...prev,
      [key]: value
    }));
    // API call to update settings
    console.log('Update setting:', key, value);
  };

  const renderNotificationItem = (item: Notification) => (
    <List.Item
      key={item.id}
      className={`notification-item ${!item.read ? 'unread' : ''}`}
      actions={[
        <Tooltip title="Mark as read">
          <Button
            type="text"
            icon={<CheckOutlined />}
            onClick={() => handleMarkAsRead(item.id)}
            disabled={item.read}
          />
        </Tooltip>,
        <Tooltip title="Delete">
          <Button
            type="text"
            icon={<DeleteOutlined />}
            onClick={() => handleDeleteNotification(item.id)}
            danger
          />
        </Tooltip>
      ]}
    >
      <List.Item.Meta
        avatar={
          <Badge dot={!item.read} color={getPriorityColor(item.priority)}>
            <Avatar
              icon={getNotificationIcon(item.type)}
              style={{ backgroundColor: '#f5f5f5' }}
            />
          </Badge>
        }
        title={
          <Space>
            <span className="notification-title">{item.title}</span>
            <Tag color={getPriorityColor(item.priority)} size="small">
              {item.priority}
            </Tag>
          </Space>
        }
        description={
          <div className="notification-content">
            <Paragraph ellipsis={{ rows: 2 }} className="notification-message">
              {item.message}
            </Paragraph>
            <div className="notification-meta">
              <Text type="secondary" className="notification-time">
                {formatTimestamp(item.timestamp)}
              </Text>
              {item.actionUrl && (
                <Button type="link" size="small" className="action-link">
                  View Details
                </Button>
              )}
            </div>
          </div>
        }
      />
      {item.image && (
        <div className="notification-image">
          <img src={item.image} alt="" />
        </div>
      )}
    </List.Item>
  );

  const renderNotificationSettings = () => (
    <Card title="Notification Preferences" className="settings-card">
      <div className="settings-section">
        <Title level={5}>Communication Preferences</Title>
        <div className="setting-item">
          <div className="setting-info">
            <Text strong>Email Notifications</Text>
            <br />
            <Text type="secondary">Receive notifications via email</Text>
          </div>
          <Switch
            checked={settings.emailNotifications}
            onChange={(checked) => handleSettingChange('emailNotifications', checked)}
          />
        </div>

        <div className="setting-item">
          <div className="setting-info">
            <Text strong>Push Notifications</Text>
            <br />
            <Text type="secondary">Receive push notifications in browser</Text>
          </div>
          <Switch
            checked={settings.pushNotifications}
            onChange={(checked) => handleSettingChange('pushNotifications', checked)}
          />
        </div>

        <div className="setting-item">
          <div className="setting-info">
            <Text strong>SMS Notifications</Text>
            <br />
            <Text type="secondary">Receive important updates via SMS</Text>
          </div>
          <Switch
            checked={settings.smsNotifications}
            onChange={(checked) => handleSettingChange('smsNotifications', checked)}
          />
        </div>

        <Divider />

        <Title level={5}>Content Preferences</Title>
        <div className="setting-item">
          <div className="setting-info">
            <Text strong>Promotional Emails</Text>
            <br />
            <Text type="secondary">Receive offers and promotions</Text>
          </div>
          <Switch
            checked={settings.promotionalEmails}
            onChange={(checked) => handleSettingChange('promotionalEmails', checked)}
          />
        </div>

        <div className="setting-item">
          <div className="setting-info">
            <Text strong>Booking Updates</Text>
            <br />
            <Text type="secondary">Get updates about your reservations</Text>
          </div>
          <Switch
            checked={settings.bookingUpdates}
            onChange={(checked) => handleSettingChange('bookingUpdates', checked)}
          />
        </div>

        <div className="setting-item">
          <div className="setting-info">
            <Text strong>Review Reminders</Text>
            <br />
            <Text type="secondary">Reminders to review your stays</Text>
          </div>
          <Switch
            checked={settings.reviewReminders}
            onChange={(checked) => handleSettingChange('reviewReminders', checked)}
          />
        </div>

        <div className="setting-item">
          <div className="setting-info">
            <Text strong>Price Alerts</Text>
            <br />
            <Text type="secondary">Get notified when prices drop for saved properties</Text>
          </div>
          <Switch
            checked={settings.priceAlerts}
            onChange={(checked) => handleSettingChange('priceAlerts', checked)}
          />
        </div>
      </div>
    </Card>
  );

  return (
    <div className="notifications-container">
      <div className="notifications-header">
        <div className="header-content">
          <div className="title-section">
            <Badge count={unreadCount} size="small">
              <BellOutlined className="title-icon" />
            </Badge>
            <div>
              <Title level={2}>Notifications</Title>
              <Text type="secondary">
                {unreadCount} unread messages
              </Text>
            </div>
          </div>

          <div className="header-actions">
            <Space>
              <Button
                icon={<CheckOutlined />}
                onClick={handleMarkAllAsRead}
                disabled={unreadCount === 0}
              >
                Mark All Read
              </Button>
              <Button
                icon={<ClearOutlined />}
                onClick={handleClearAll}
                danger
              >
                Clear All
              </Button>
            </Space>
          </div>
        </div>

        <div className="filters-section">
          <Row gutter={[16, 16]} align="middle">
            <Col xs={24} sm={8} md={6}>
              <Select
                value={filterType}
                onChange={setFilterType}
                style={{ width: '100%' }}
                prefix={<FilterOutlined />}
              >
                <Option value="all">All Types</Option>
                <Option value="booking">Bookings</Option>
                <Option value="promotion">Promotions</Option>
                <Option value="system">System</Option>
                <Option value="review">Reviews</Option>
                <Option value="wishlist">Wishlist</Option>
              </Select>
            </Col>
            <Col xs={24} sm={8} md={6}>
              <div className="unread-filter">
                <Switch
                  checked={showUnreadOnly}
                  onChange={setShowUnreadOnly}
                  size="small"
                />
                <Text style={{ marginLeft: 8 }}>Unread only</Text>
              </div>
            </Col>
          </Row>
        </div>
      </div>

      <Tabs
        activeKey={activeTab}
        onChange={setActiveTab}
        className="notifications-tabs"
      >
        <TabPane tab="All" key="all">
          {filteredNotifications.length === 0 ? (
            <Empty
              image={<BellOutlined style={{ fontSize: 64, color: '#d4af37' }} />}
              description={
                <div>
                  <Title level={4}>No notifications</Title>
                  <Paragraph type="secondary">
                    You're all caught up! Check back later for new updates.
                  </Paragraph>
                </div>
              }
            />
          ) : (
            <List
              className="notifications-list"
              dataSource={filteredNotifications}
              renderItem={renderNotificationItem}
              pagination={{
                pageSize: 10,
                showSizeChanger: false,
                showQuickJumper: true
              }}
            />
          )}
        </TabPane>

        <TabPane tab="Bookings" key="booking">
          <List
            className="notifications-list"
            dataSource={filteredNotifications.filter(n => n.type === 'booking')}
            renderItem={renderNotificationItem}
            locale={{
              emptyText: (
                <Empty
                  image={<CalendarOutlined style={{ fontSize: 48, color: '#d4af37' }} />}
                  description="No booking notifications"
                />
              )
            }}
          />
        </TabPane>

        <TabPane tab="Promotions" key="promotion">
          <List
            className="notifications-list"
            dataSource={filteredNotifications.filter(n => n.type === 'promotion')}
            renderItem={renderNotificationItem}
            locale={{
              emptyText: (
                <Empty
                  image={<GiftOutlined style={{ fontSize: 48, color: '#d4af37' }} />}
                  description="No promotional notifications"
                />
              )
            }}
          />
        </TabPane>

        <TabPane tab="Settings" key="settings">
          {renderNotificationSettings()}
        </TabPane>
      </Tabs>
    </div>
  );
};

export default Notifications;
