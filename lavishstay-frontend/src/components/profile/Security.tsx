import React, { useState } from 'react';
import {
    Card,
    Form,
    Input,
    Button,
    Typography,
    Space,
    Alert,
    Divider,
    Row,
    Col,
    Tooltip,
    Progress,
    List,
    Switch,
    Modal,
    Badge
} from 'antd';
import {
    LockOutlined,
    SafetyCertificateOutlined,
    EyeInvisibleOutlined,
    EyeTwoTone,
    CheckCircleOutlined,
    ExclamationCircleOutlined,
    ClockCircleOutlined,
    MobileOutlined,
    GlobalOutlined,
    DeleteOutlined
} from '@ant-design/icons';
import { Shield } from 'lucide-react';

const { Title, Text, Paragraph } = Typography;

interface LoginActivity {
    id: string;
    device: string;
    location: string;
    ip: string;
    time: string;
    isCurrent: boolean;
}

interface SecuritySettings {
    twoFactorEnabled: boolean;
    emailNotifications: boolean;
    smsNotifications: boolean;
    loginAlerts: boolean;
}

const Security: React.FC = () => {
    const [passwordForm] = Form.useForm();
    const [loading, setLoading] = useState(false);
    const [passwordStrength, setPasswordStrength] = useState(0);
    const [securitySettings, setSecuritySettings] = useState<SecuritySettings>({
        twoFactorEnabled: false,
        emailNotifications: true,
        smsNotifications: false,
        loginAlerts: true
    });

    const [loginActivities] = useState<LoginActivity[]>([
        {
            id: '1',
            device: 'Windows 11 - Chrome',
            location: 'Ho Chi Minh City, Vietnam',
            ip: '192.168.1.1',
            time: '2024-01-15 14:30',
            isCurrent: true
        },
        {
            id: '2',
            device: 'iPhone 15 - Safari',
            location: 'Hanoi, Vietnam',
            ip: '192.168.1.2',
            time: '2024-01-14 09:15',
            isCurrent: false
        },
        {
            id: '3',
            device: 'MacBook Pro - Chrome',
            location: 'Da Nang, Vietnam',
            ip: '192.168.1.3',
            time: '2024-01-13 16:45',
            isCurrent: false
        }
    ]);

    const calculatePasswordStrength = (password: string) => {
        let strength = 0;
        if (password.length >= 8) strength += 25;
        if (/[A-Z]/.test(password)) strength += 25;
        if (/[0-9]/.test(password)) strength += 25;
        if (/[^A-Za-z0-9]/.test(password)) strength += 25;
        return strength;
    };

    const handlePasswordChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const password = e.target.value;
        setPasswordStrength(calculatePasswordStrength(password));
    };

    const getPasswordStrengthColor = () => {
        if (passwordStrength < 25) return '#ff4d4f';
        if (passwordStrength < 50) return '#fa8c16';
        if (passwordStrength < 75) return '#fadb14';
        return '#52c41a';
    };

    const getPasswordStrengthText = () => {
        if (passwordStrength < 25) return 'Weak';
        if (passwordStrength < 50) return 'Fair';
        if (passwordStrength < 75) return 'Good';
        return 'Strong';
    };

    const handlePasswordSubmit = async (values: any) => {
        setLoading(true);
        try {
            // API call to change password
            await new Promise(resolve => setTimeout(resolve, 2000));
            Modal.success({
                title: 'Password Changed Successfully',
                content: 'Your password has been updated successfully.',
            });
            passwordForm.resetFields();
            setPasswordStrength(0);
        } catch (error) {
            Modal.error({
                title: 'Password Change Failed',
                content: 'Please try again later.',
            });
        } finally {
            setLoading(false);
        }
    };

    const handleSecuritySettingChange = (key: keyof SecuritySettings, value: boolean) => {
        setSecuritySettings(prev => ({
            ...prev,
            [key]: value
        }));
    };

    const handleTerminateSession = (sessionId: string) => {
        Modal.confirm({
            title: 'Terminate Session',
            content: 'Are you sure you want to terminate this session?',
            icon: <ExclamationCircleOutlined />,
            onOk() {
                // API call to terminate session
                console.log('Terminating session:', sessionId);
            },
        });
    };

    return (
        <div className="security-container">            <div className="security-header">
            <div className="security-title">
                <Shield className="title-icon" size={32} />
                <div>
                    <Title level={2}>Security Settings</Title>
                    <Text type="secondary">Manage your account security and privacy</Text>
                </div>
            </div>
        </div>

            <Row gutter={[24, 24]}>
                {/* Password Change Section */}
                <Col xs={24} lg={12}>
                    <Card
                        title={
                            <Space>
                                <LockOutlined />
                                Change Password
                            </Space>
                        }
                        className="security-card"
                    >
                        <Form
                            form={passwordForm}
                            layout="vertical"
                            onFinish={handlePasswordSubmit}
                            autoComplete="off"
                        >
                            <Form.Item
                                label="Current Password"
                                name="currentPassword"
                                rules={[
                                    { required: true, message: 'Please enter your current password' }
                                ]}
                            >
                                <Input.Password
                                    placeholder="Enter current password"
                                    iconRender={(visible) => (visible ? <EyeTwoTone /> : <EyeInvisibleOutlined />)}
                                />
                            </Form.Item>

                            <Form.Item
                                label="New Password"
                                name="newPassword"
                                rules={[
                                    { required: true, message: 'Please enter your new password' },
                                    { min: 8, message: 'Password must be at least 8 characters' }
                                ]}
                            >
                                <Input.Password
                                    placeholder="Enter new password"
                                    onChange={handlePasswordChange}
                                    iconRender={(visible) => (visible ? <EyeTwoTone /> : <EyeInvisibleOutlined />)}
                                />
                            </Form.Item>

                            {passwordStrength > 0 && (
                                <div className="password-strength">
                                    <Text strong>Password Strength: </Text>
                                    <Progress
                                        percent={passwordStrength}
                                        strokeColor={getPasswordStrengthColor()}
                                        showInfo={false}
                                        size="small"
                                    />
                                    <Text
                                        style={{ color: getPasswordStrengthColor(), marginLeft: 8 }}
                                    >
                                        {getPasswordStrengthText()}
                                    </Text>
                                </div>
                            )}

                            <Form.Item
                                label="Confirm New Password"
                                name="confirmPassword"
                                dependencies={['newPassword']}
                                rules={[
                                    { required: true, message: 'Please confirm your new password' },
                                    ({ getFieldValue }) => ({
                                        validator(_, value) {
                                            if (!value || getFieldValue('newPassword') === value) {
                                                return Promise.resolve();
                                            }
                                            return Promise.reject(new Error('Passwords do not match'));
                                        },
                                    }),
                                ]}
                            >
                                <Input.Password
                                    placeholder="Confirm new password"
                                    iconRender={(visible) => (visible ? <EyeTwoTone /> : <EyeInvisibleOutlined />)}
                                />
                            </Form.Item>

                            <Form.Item>
                                <Button
                                    type="primary"
                                    htmlType="submit"
                                    loading={loading}
                                    className="submit-btn"
                                    block
                                >
                                    Update Password
                                </Button>
                            </Form.Item>
                        </Form>

                        <Alert
                            message="Password Requirements"
                            description={
                                <ul className="password-requirements">
                                    <li>At least 8 characters long</li>
                                    <li>Include uppercase and lowercase letters</li>
                                    <li>Include at least one number</li>
                                    <li>Include at least one special character</li>
                                </ul>
                            }
                            type="info"
                            showIcon
                        />
                    </Card>
                </Col>

                {/* Security Settings */}
                <Col xs={24} lg={12}>
                    <Card
                        title={
                            <Space>
                                <SafetyCertificateOutlined />
                                Security Options
                            </Space>
                        }
                        className="security-card"
                    >
                        <div className="security-options">
                            <div className="security-option">
                                <div className="option-content">
                                    <div className="option-info">
                                        <Text strong>Two-Factor Authentication</Text>
                                        <br />
                                        <Text type="secondary">Add an extra layer of security</Text>
                                    </div>
                                    <Switch
                                        checked={securitySettings.twoFactorEnabled}
                                        onChange={(checked) => handleSecuritySettingChange('twoFactorEnabled', checked)}
                                    />
                                </div>
                            </div>

                            <Divider />

                            <div className="security-option">
                                <div className="option-content">
                                    <div className="option-info">
                                        <Text strong>Email Notifications</Text>
                                        <br />
                                        <Text type="secondary">Receive security alerts via email</Text>
                                    </div>
                                    <Switch
                                        checked={securitySettings.emailNotifications}
                                        onChange={(checked) => handleSecuritySettingChange('emailNotifications', checked)}
                                    />
                                </div>
                            </div>

                            <Divider />

                            <div className="security-option">
                                <div className="option-content">
                                    <div className="option-info">
                                        <Text strong>SMS Notifications</Text>
                                        <br />
                                        <Text type="secondary">Receive security alerts via SMS</Text>
                                    </div>
                                    <Switch
                                        checked={securitySettings.smsNotifications}
                                        onChange={(checked) => handleSecuritySettingChange('smsNotifications', checked)}
                                    />
                                </div>
                            </div>

                            <Divider />

                            <div className="security-option">
                                <div className="option-content">
                                    <div className="option-info">
                                        <Text strong>Login Alerts</Text>
                                        <br />
                                        <Text type="secondary">Get notified of new login attempts</Text>
                                    </div>
                                    <Switch
                                        checked={securitySettings.loginAlerts}
                                        onChange={(checked) => handleSecuritySettingChange('loginAlerts', checked)}
                                    />
                                </div>
                            </div>
                        </div>
                    </Card>
                </Col>

                {/* Login Activity */}
                <Col xs={24}>
                    <Card
                        title={
                            <Space>
                                <ClockCircleOutlined />
                                Recent Login Activity
                            </Space>
                        }
                        className="security-card"
                    >
                        <List
                            dataSource={loginActivities}
                            renderItem={(item) => (
                                <List.Item
                                    actions={[
                                        item.isCurrent ? (
                                            <Badge status="success" text="Current Session" />
                                        ) : (
                                            <Tooltip title="Terminate Session">
                                                <Button
                                                    type="text"
                                                    danger
                                                    icon={<DeleteOutlined />}
                                                    onClick={() => handleTerminateSession(item.id)}
                                                />
                                            </Tooltip>
                                        )
                                    ]}
                                    className="login-activity-item"
                                >
                                    <List.Item.Meta
                                        avatar={
                                            <div className="device-icon">
                                                {item.device.includes('iPhone') ? <MobileOutlined /> : <GlobalOutlined />}
                                            </div>
                                        }
                                        title={
                                            <Space>
                                                <Text strong>{item.device}</Text>
                                                {item.isCurrent && <Badge status="success" />}
                                            </Space>
                                        }
                                        description={
                                            <div>
                                                <Text type="secondary">{item.location}</Text>
                                                <br />
                                                <Text type="secondary">IP: {item.ip}</Text>
                                                <br />
                                                <Text type="secondary">{item.time}</Text>
                                            </div>
                                        }
                                    />
                                </List.Item>
                            )}
                        />
                    </Card>
                </Col>
            </Row>
        </div>
    );
};

export default Security;
