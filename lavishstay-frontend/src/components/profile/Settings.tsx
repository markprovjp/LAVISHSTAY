import React, { useState } from 'react';
import {
  Card,
  Typography,
  Switch,
  Select,
  Button,
  Row,
  Col,
  Divider,
  Modal,
  message,
  Space,
  Alert,
  Form,
  Input,
  Radio
} from 'antd';
import {
  SettingOutlined,
  GlobalOutlined,
  EyeOutlined,
  BellOutlined,
  
  DeleteOutlined,
  DownloadOutlined,
  ExclamationCircleOutlined,
  MoonOutlined,
  SunOutlined,
  TranslationOutlined
} from '@ant-design/icons';
import { Shield } from 'lucide-react';

import './Settings.css';

const { Title, Text, Paragraph } = Typography;
const { Option } = Select;

interface UserSettings {
  language: string;
  currency: string;
  timezone: string;
  theme: 'light' | 'dark' | 'auto';
  dateFormat: string;
  emailNotifications: boolean;
  pushNotifications: boolean;
  marketingEmails: boolean;
  dataSharing: boolean;
  analytics: boolean;
  locationServices: boolean;
  twoFactorAuth: boolean;
  autoSave: boolean;
  soundEffects: boolean;
}

const Settings: React.FC = () => {
  const [form] = Form.useForm();
  const [loading, setLoading] = useState(false);
  const [exportLoading, setExportLoading] = useState(false);

  const [settings, setSettings] = useState<UserSettings>({
    language: 'en',
    currency: 'VND',
    timezone: 'Asia/Ho_Chi_Minh',
    theme: 'light',
    dateFormat: 'DD/MM/YYYY',
    emailNotifications: true,
    pushNotifications: true,
    marketingEmails: false,
    dataSharing: true,
    analytics: true,
    locationServices: true,
    twoFactorAuth: false,
    autoSave: true,
    soundEffects: true
  });

  const languages = [
    { value: 'en', label: 'English', flag: '🇺🇸' },
    { value: 'vi', label: 'Tiếng Việt', flag: '🇻🇳' },
    { value: 'fr', label: 'Français', flag: '🇫🇷' },
    { value: 'es', label: 'Español', flag: '🇪🇸' },
    { value: 'de', label: 'Deutsch', flag: '🇩🇪' },
    { value: 'ja', label: '日本語', flag: '🇯🇵' },
    { value: 'ko', label: '한국어', flag: '🇰🇷' },
    { value: 'zh', label: '中文', flag: '🇨🇳' }
  ];

  const currencies = [
    { value: 'VND', label: 'Vietnamese Dong (₫)', symbol: '₫' },
    { value: 'USD', label: 'US Dollar ($)', symbol: '$' },
    { value: 'EUR', label: 'Euro (€)', symbol: '€' },
    { value: 'GBP', label: 'British Pound (£)', symbol: '£' },
    { value: 'JPY', label: 'Japanese Yen (¥)', symbol: '¥' },
    { value: 'KRW', label: 'Korean Won (₩)', symbol: '₩' },
    { value: 'CNY', label: 'Chinese Yuan (¥)', symbol: '¥' }
  ];

  const timezones = [
    { value: 'Asia/Ho_Chi_Minh', label: 'Ho Chi Minh City (GMT+7)' },
    { value: 'Asia/Bangkok', label: 'Bangkok (GMT+7)' },
    { value: 'Asia/Singapore', label: 'Singapore (GMT+8)' },
    { value: 'Asia/Tokyo', label: 'Tokyo (GMT+9)' },
    { value: 'Asia/Seoul', label: 'Seoul (GMT+9)' },
    { value: 'America/New_York', label: 'New York (GMT-5)' },
    { value: 'America/Los_Angeles', label: 'Los Angeles (GMT-8)' },
    { value: 'Europe/London', label: 'London (GMT+0)' },
    { value: 'Europe/Paris', label: 'Paris (GMT+1)' }
  ];

  const dateFormats = [
    { value: 'DD/MM/YYYY', label: '31/12/2024' },
    { value: 'MM/DD/YYYY', label: '12/31/2024' },
    { value: 'YYYY-MM-DD', label: '2024-12-31' },
    { value: 'DD-MM-YYYY', label: '31-12-2024' }
  ];

  const handleSettingChange = (key: keyof UserSettings, value: any) => {
    setSettings(prev => ({
      ...prev,
      [key]: value
    }));
  };

  const handleSaveSettings = async () => {
    setLoading(true);
    try {
      // API call to save settings
      await new Promise(resolve => setTimeout(resolve, 1500));
      message.success('Settings saved successfully!');
    } catch (error) {
      message.error('Failed to save settings. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  const handleResetSettings = () => {
    Modal.confirm({
      title: 'Reset All Settings',
      content: 'Are you sure you want to reset all settings to default values? This action cannot be undone.',
      icon: <ExclamationCircleOutlined />,
      okType: 'danger',
      onOk() {
        // Reset to default settings
        setSettings({
          language: 'en',
          currency: 'VND',
          timezone: 'Asia/Ho_Chi_Minh',
          theme: 'light',
          dateFormat: 'DD/MM/YYYY',
          emailNotifications: true,
          pushNotifications: true,
          marketingEmails: false,
          dataSharing: true,
          analytics: true,
          locationServices: true,
          twoFactorAuth: false,
          autoSave: true,
          soundEffects: true
        });
        message.success('Settings reset to default values');
      },
    });
  };

  const handleExportData = async () => {
    setExportLoading(true);
    try {
      // API call to export user data
      await new Promise(resolve => setTimeout(resolve, 2000));
      
      // Simulate file download
      const dataStr = JSON.stringify({
        profile: 'User profile data...',
        bookings: 'Booking history...',
        preferences: settings,
        exportDate: new Date().toISOString()
      }, null, 2);
      
      const dataBlob = new Blob([dataStr], { type: 'application/json' });
      const url = URL.createObjectURL(dataBlob);
      const link = document.createElement('a');
      link.href = url;
      link.download = `lavishstay-data-${new Date().toISOString().split('T')[0]}.json`;
      link.click();
      URL.revokeObjectURL(url);
      
      message.success('Your data has been exported successfully!');
    } catch (error) {
      message.error('Failed to export data. Please try again.');
    } finally {
      setExportLoading(false);
    }
  };

  const handleDeleteAccount = () => {
    Modal.confirm({
      title: 'Delete Account',
      content: (
        <div>
          <Paragraph>
            <strong>This action is permanent and cannot be undone.</strong>
          </Paragraph>
          <Paragraph>
            Deleting your account will remove all your personal data, bookings, and preferences from our system.
            You will no longer be able to access your account or recover any data.
          </Paragraph>
          <Paragraph>
            Are you sure you want to proceed?
          </Paragraph>
        </div>
      ),
      icon: <ExclamationCircleOutlined style={{ color: '#ff4d4f' }} />,
      okText: 'Delete Account',
      okType: 'danger',
      cancelText: 'Cancel',
      onOk() {
        Modal.confirm({
          title: 'Final Confirmation',
          content: 'Please type "DELETE" to confirm account deletion.',
          icon: <ExclamationCircleOutlined style={{ color: '#ff4d4f' }} />,
          okText: 'Confirm Delete',
          okType: 'danger',
          onOk() {
            // API call to delete account
            message.success('Account deletion request submitted. You will receive a confirmation email.');
          },
        });
      },
    });
  };

  return (
    <div className="settings-container">
      <div className="settings-header">
        <div className="title-section">
          <SettingOutlined className="title-icon" />
          <div>
            <Title level={2}>Settings</Title>
            <Text type="secondary">Manage your account preferences and privacy</Text>
          </div>
        </div>
      </div>

      <Row gutter={[24, 24]}>
        {/* General Settings */}
        <Col xs={24} lg={12}>
          <Card
            title={
              <Space>
                <GlobalOutlined />
                General Settings
              </Space>
            }
            className="settings-card"
          >
            <div className="settings-section">
              <div className="setting-group">
                <Title level={5}>Language & Region</Title>
                
                <div className="setting-item">
                  <div className="setting-info">
                    <Text strong>Language</Text>
                    <br />
                    <Text type="secondary">Choose your preferred language</Text>
                  </div>
                  <Select
                    value={settings.language}
                    onChange={(value) => handleSettingChange('language', value)}
                    style={{ width: 200 }}
                    className="setting-select"
                  >
                    {languages.map(lang => (
                      <Option key={lang.value} value={lang.value}>
                        <Space>
                          <span>{lang.flag}</span>
                          <span>{lang.label}</span>
                        </Space>
                      </Option>
                    ))}
                  </Select>
                </div>

                <div className="setting-item">
                  <div className="setting-info">
                    <Text strong>Currency</Text>
                    <br />
                    <Text type="secondary">Default currency for prices</Text>
                  </div>
                  <Select
                    value={settings.currency}
                    onChange={(value) => handleSettingChange('currency', value)}
                    style={{ width: 200 }}
                    className="setting-select"
                  >
                    {currencies.map(currency => (
                      <Option key={currency.value} value={currency.value}>
                        {currency.label}
                      </Option>
                    ))}
                  </Select>
                </div>

                <div className="setting-item">
                  <div className="setting-info">
                    <Text strong>Timezone</Text>
                    <br />
                    <Text type="secondary">Your local timezone</Text>
                  </div>
                  <Select
                    value={settings.timezone}
                    onChange={(value) => handleSettingChange('timezone', value)}
                    style={{ width: 200 }}
                    className="setting-select"
                  >
                    {timezones.map(tz => (
                      <Option key={tz.value} value={tz.value}>
                        {tz.label}
                      </Option>
                    ))}
                  </Select>
                </div>

                <div className="setting-item">
                  <div className="setting-info">
                    <Text strong>Date Format</Text>
                    <br />
                    <Text type="secondary">How dates are displayed</Text>
                  </div>
                  <Select
                    value={settings.dateFormat}
                    onChange={(value) => handleSettingChange('dateFormat', value)}
                    style={{ width: 200 }}
                    className="setting-select"
                  >
                    {dateFormats.map(format => (
                      <Option key={format.value} value={format.value}>
                        {format.label}
                      </Option>
                    ))}
                  </Select>
                </div>
              </div>

              <Divider />

              <div className="setting-group">
                <Title level={5}>Appearance</Title>
                
                <div className="setting-item">
                  <div className="setting-info">
                    <Text strong>Theme</Text>
                    <br />
                    <Text type="secondary">Choose your preferred theme</Text>
                  </div>
                  <Radio.Group
                    value={settings.theme}
                    onChange={(e) => handleSettingChange('theme', e.target.value)}
                    className="theme-radio"
                  >
                    <Radio.Button value="light">
                      <SunOutlined /> Light
                    </Radio.Button>
                    <Radio.Button value="dark">
                      <MoonOutlined /> Dark
                    </Radio.Button>
                    <Radio.Button value="auto">
                      Auto
                    </Radio.Button>
                  </Radio.Group>
                </div>
              </div>
            </div>
          </Card>
        </Col>

        {/* Privacy & Notifications */}
        <Col xs={24} lg={12}>
          <Card
            title={
              <Space>
                <BellOutlined />
                Privacy & Notifications
              </Space>
            }
            className="settings-card"
          >
            <div className="settings-section">
              <div className="setting-group">
                <Title level={5}>Notifications</Title>
                
                <div className="setting-item">
                  <div className="setting-info">
                    <Text strong>Email Notifications</Text>
                    <br />
                    <Text type="secondary">Receive booking updates via email</Text>
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
                    <Text type="secondary">Receive real-time notifications</Text>
                  </div>
                  <Switch
                    checked={settings.pushNotifications}
                    onChange={(checked) => handleSettingChange('pushNotifications', checked)}
                  />
                </div>

                <div className="setting-item">
                  <div className="setting-info">
                    <Text strong>Marketing Emails</Text>
                    <br />
                    <Text type="secondary">Receive promotional offers</Text>
                  </div>
                  <Switch
                    checked={settings.marketingEmails}
                    onChange={(checked) => handleSettingChange('marketingEmails', checked)}
                  />
                </div>
              </div>

              <Divider />

              <div className="setting-group">
                <Title level={5}>Privacy</Title>
                
                <div className="setting-item">
                  <div className="setting-info">
                    <Text strong>Data Sharing</Text>
                    <br />
                    <Text type="secondary">Share anonymized data for service improvement</Text>
                  </div>
                  <Switch
                    checked={settings.dataSharing}
                    onChange={(checked) => handleSettingChange('dataSharing', checked)}
                  />
                </div>

                <div className="setting-item">
                  <div className="setting-info">
                    <Text strong>Analytics</Text>
                    <br />
                    <Text type="secondary">Help us improve with usage analytics</Text>
                  </div>
                  <Switch
                    checked={settings.analytics}
                    onChange={(checked) => handleSettingChange('analytics', checked)}
                  />
                </div>

                <div className="setting-item">
                  <div className="setting-info">
                    <Text strong>Location Services</Text>
                    <br />
                    <Text type="secondary">Allow location-based recommendations</Text>
                  </div>
                  <Switch
                    checked={settings.locationServices}
                    onChange={(checked) => handleSettingChange('locationServices', checked)}
                  />
                </div>
              </div>
            </div>
          </Card>
        </Col>

        {/* Security Settings */}
        <Col xs={24} lg={12}>
          <Card
            title={
              <Space>
                <Shield className="title-icon" size={32} />
                Security
              </Space>
            }
            className="settings-card"
          >
            <div className="settings-section">
              <div className="setting-item">
                <div className="setting-info">
                  <Text strong>Two-Factor Authentication</Text>
                  <br />
                  <Text type="secondary">Add an extra layer of security</Text>
                </div>
                <Switch
                  checked={settings.twoFactorAuth}
                  onChange={(checked) => handleSettingChange('twoFactorAuth', checked)}
                />
              </div>

              <div className="setting-item">
                <div className="setting-info">
                  <Text strong>Auto Save</Text>
                  <br />
                  <Text type="secondary">Automatically save form data</Text>
                </div>
                <Switch
                  checked={settings.autoSave}
                  onChange={(checked) => handleSettingChange('autoSave', checked)}
                />
              </div>

              <div className="setting-item">
                <div className="setting-info">
                  <Text strong>Sound Effects</Text>
                  <br />
                  <Text type="secondary">Play sounds for notifications</Text>
                </div>
                <Switch
                  checked={settings.soundEffects}
                  onChange={(checked) => handleSettingChange('soundEffects', checked)}
                />
              </div>
            </div>
          </Card>
        </Col>

        {/* Data Management */}
        <Col xs={24} lg={12}>
          <Card
            title={
              <Space>
                <EyeOutlined />
                Data Management
              </Space>
            }
            className="settings-card"
          >
            <div className="settings-section">
              <Alert
                message="Your Privacy Matters"
                description="You have full control over your personal data. Export your data or delete your account at any time."
                type="info"
                showIcon
                style={{ marginBottom: 24 }}
              />

              <div className="data-action">
                <div className="action-info">
                  <Text strong>Export Your Data</Text>
                  <br />
                  <Text type="secondary">Download a copy of all your data</Text>
                </div>
                <Button
                  icon={<DownloadOutlined />}
                  onClick={handleExportData}
                  loading={exportLoading}
                  className="action-btn"
                >
                  Export Data
                </Button>
              </div>

              <Divider />

              <div className="data-action">
                <div className="action-info">
                  <Text strong>Delete Account</Text>
                  <br />
                  <Text type="secondary" style={{ color: '#ff4d4f' }}>
                    Permanently delete your account and all data
                  </Text>
                </div>
                <Button
                  danger
                  icon={<DeleteOutlined />}
                  onClick={handleDeleteAccount}
                  className="action-btn"
                >
                  Delete Account
                </Button>
              </div>
            </div>
          </Card>
        </Col>
      </Row>

      {/* Action Buttons */}
      <div className="settings-actions">
        <Space size="large">
          <Button
            size="large"
            onClick={handleResetSettings}
            className="reset-btn"
          >
            Reset to Default
          </Button>
          <Button
            type="primary"
            size="large"
            onClick={handleSaveSettings}
            loading={loading}
            className="save-btn"
          >
            Save Changes
          </Button>
        </Space>
      </div>
    </div>
  );
};

export default Settings;
