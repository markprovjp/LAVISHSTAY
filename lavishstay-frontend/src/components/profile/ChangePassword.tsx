import React, { useState } from 'react';
import { 
  Card, 
  Form, 
  Input, 
  Button, 
  Typography, 
  Space, 
  Alert, 
  Progress,
  Modal,
  Row,
  Col,
  Divider
} from 'antd';
import {
  LockOutlined,
  EyeInvisibleOutlined,
  EyeTwoTone,
  CheckCircleOutlined,
  ArrowLeftOutlined,
  KeyOutlined,
  SafetyCertificateOutlined
} from '@ant-design/icons';
import { useNavigate } from 'react-router-dom';
import './ChangePassword.css';

const { Title, Text, Paragraph } = Typography;

const ChangePassword: React.FC = () => {
  const navigate = useNavigate();
  const [form] = Form.useForm();
  const [loading, setLoading] = useState(false);
  const [passwordStrength, setPasswordStrength] = useState(0);

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

  const handleSubmit = async (values: any) => {
    setLoading(true);
    try {
      // API call to change password
      await new Promise(resolve => setTimeout(resolve, 2000));
        Modal.success({
        title: 'Password Changed Successfully',
        content: 'Your password has been updated successfully. You will be redirected to home page.',
        onOk: () => {
          navigate('/');
        }
      });
      
      form.resetFields();
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

  const handleBack = () => {
    navigate(-1);
  };

  return (
    <div className="change-password-container">
      <div className="change-password-wrapper">
        <Button 
          type="text" 
          icon={<ArrowLeftOutlined />}
          onClick={handleBack}
          className="back-btn"
        >
          Back
        </Button>

        <Card className="change-password-card">
          <div className="change-password-header">
            <div className="header-icon">
              <KeyOutlined />
            </div>
            <Title level={2}>Change Password</Title>
            <Paragraph type="secondary">
              Create a new secure password for your LavishStay account
            </Paragraph>
          </div>

          <Divider />

          <Form
            form={form}
            layout="vertical"
            onFinish={handleSubmit}
            autoComplete="off"
            className="change-password-form"
          >
            <Form.Item
              label="Current Password"
              name="currentPassword"
              rules={[
                { required: true, message: 'Please enter your current password' }
              ]}
            >
              <Input.Password
                size="large"
                placeholder="Enter your current password"
                prefix={<LockOutlined />}
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
                size="large"
                placeholder="Enter your new password"
                prefix={<LockOutlined />}
                onChange={handlePasswordChange}
                iconRender={(visible) => (visible ? <EyeTwoTone /> : <EyeInvisibleOutlined />)}
              />
            </Form.Item>

            {passwordStrength > 0 && (
              <div className="password-strength">
                <div className="strength-label">
                  <Text strong>Password Strength: </Text>
                  <Text style={{ color: getPasswordStrengthColor() }}>
                    {getPasswordStrengthText()}
                  </Text>
                </div>
                <Progress
                  percent={passwordStrength}
                  strokeColor={getPasswordStrengthColor()}
                  showInfo={false}
                  size="small"
                />
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
                size="large"
                placeholder="Confirm your new password"
                prefix={<LockOutlined />}
                iconRender={(visible) => (visible ? <EyeTwoTone /> : <EyeInvisibleOutlined />)}
              />
            </Form.Item>

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
              icon={<SafetyCertificateOutlined />}
              className="requirements-alert"
            />

            <Form.Item>
              <Button
                type="primary"
                htmlType="submit"
                loading={loading}
                size="large"
                block
                className="submit-btn"
                icon={<CheckCircleOutlined />}
              >
                Update Password
              </Button>
            </Form.Item>
          </Form>

          <div className="security-tips">
            <Title level={4}>Security Tips</Title>
            <Row gutter={[16, 16]}>
              <Col xs={24} sm={12}>
                <div className="tip-item">
                  <CheckCircleOutlined className="tip-icon" />
                  <Text>Use a unique password</Text>
                </div>
              </Col>
              <Col xs={24} sm={12}>
                <div className="tip-item">
                  <CheckCircleOutlined className="tip-icon" />
                  <Text>Avoid personal information</Text>
                </div>
              </Col>
              <Col xs={24} sm={12}>
                <div className="tip-item">
                  <CheckCircleOutlined className="tip-icon" />
                  <Text>Mix letters, numbers & symbols</Text>
                </div>
              </Col>
              <Col xs={24} sm={12}>
                <div className="tip-item">
                  <CheckCircleOutlined className="tip-icon" />
                  <Text>Update regularly</Text>
                </div>
              </Col>
            </Row>
          </div>
        </Card>
      </div>
    </div>
  );
};

export default ChangePassword;
