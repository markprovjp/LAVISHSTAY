import React, { useState, useEffect } from 'react';
import {
  Card,
  Form,
  Input,
  Button,
  Typography,
  Space,
  Alert,
  Row,
  Col,
  Steps,
  Modal,
  Statistic
} from 'antd';
import {
  MailOutlined,
  SafetyCertificateOutlined,
  LockOutlined,
  CheckCircleOutlined,
  ArrowLeftOutlined,
  ClockCircleOutlined,
  KeyOutlined
} from '@ant-design/icons';
import './ForgotPassword.css';

const { Title, Text, Paragraph } = Typography;
const { Countdown } = Statistic;

interface ForgotPasswordProps {
  onBack?: () => void;
  onSuccess?: () => void;
}

const ForgotPassword: React.FC<ForgotPasswordProps> = ({ onBack, onSuccess }) => {
  const [emailForm] = Form.useForm();
  const [otpForm] = Form.useForm();
  const [resetForm] = Form.useForm();
  
  const [currentStep, setCurrentStep] = useState(0);
  const [loading, setLoading] = useState(false);
  const [email, setEmail] = useState('');
  const [otpSent, setOtpSent] = useState(false);
  const [resendCountdown, setResendCountdown] = useState(0);
  const [passwordStrength, setPasswordStrength] = useState(0);

  const steps = [
    {
      title: 'Email Verification',
      icon: <MailOutlined />,
      description: 'Enter your email address'
    },
    {
      title: 'OTP Verification',
      icon: <SafetyCertificateOutlined />,
      description: 'Enter the verification code'
    },
    {
      title: 'Reset Password',
      icon: <LockOutlined />,
      description: 'Create a new password'
    },
    {
      title: 'Complete',
      icon: <CheckCircleOutlined />,
      description: 'Password reset successfully'
    }
  ];

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

  const handleEmailSubmit = async (values: { email: string }) => {
    setLoading(true);
    try {
      // API call to send OTP
      await new Promise(resolve => setTimeout(resolve, 2000));
      setEmail(values.email);
      setOtpSent(true);
      setCurrentStep(1);
      setResendCountdown(60); // 60 seconds countdown
    } catch (error) {
      Modal.error({
        title: 'Error',
        content: 'Failed to send verification code. Please try again.',
      });
    } finally {
      setLoading(false);
    }
  };

  const handleOtpSubmit = async (values: { otp: string }) => {
    setLoading(true);
    try {
      // API call to verify OTP
      await new Promise(resolve => setTimeout(resolve, 1500));
      setCurrentStep(2);
    } catch (error) {
      Modal.error({
        title: 'Invalid Code',
        content: 'The verification code is incorrect. Please try again.',
      });
    } finally {
      setLoading(false);
    }
  };

  const handlePasswordReset = async (values: { password: string; confirmPassword: string }) => {
    setLoading(true);
    try {
      // API call to reset password
      await new Promise(resolve => setTimeout(resolve, 2000));
      setCurrentStep(3);
      setTimeout(() => {
        onSuccess?.();
      }, 3000);
    } catch (error) {
      Modal.error({
        title: 'Error',
        content: 'Failed to reset password. Please try again.',
      });
    } finally {
      setLoading(false);
    }
  };

  const handleResendOtp = async () => {
    setLoading(true);
    try {
      // API call to resend OTP
      await new Promise(resolve => setTimeout(resolve, 1000));
      setResendCountdown(60);
      Modal.success({
        title: 'Code Sent',
        content: 'A new verification code has been sent to your email.',
      });
    } catch (error) {
      Modal.error({
        title: 'Error',
        content: 'Failed to resend verification code. Please try again.',
      });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    let interval: NodeJS.Timeout;
    if (resendCountdown > 0) {
      interval = setInterval(() => {
        setResendCountdown(prev => prev - 1);
      }, 1000);
    }
    return () => clearInterval(interval);
  }, [resendCountdown]);

  const renderStepContent = () => {
    switch (currentStep) {
      case 0:
        return (
          <Card className="step-card">
            <div className="step-header">
              <MailOutlined className="step-icon" />
              <Title level={3}>Forgot Your Password?</Title>
              <Paragraph type="secondary">
                Don't worry! Enter your email address and we'll send you a verification code to reset your password.
              </Paragraph>
            </div>

            <Form
              form={emailForm}
              layout="vertical"
              onFinish={handleEmailSubmit}
              autoComplete="off"
            >
              <Form.Item
                label="Email Address"
                name="email"
                rules={[
                  { required: true, message: 'Please enter your email address' },
                  { type: 'email', message: 'Please enter a valid email address' }
                ]}
              >
                <Input
                  prefix={<MailOutlined />}
                  placeholder="Enter your email address"
                  size="large"
                />
              </Form.Item>

              <Form.Item>
                <Button
                  type="primary"
                  htmlType="submit"
                  loading={loading}
                  size="large"
                  block
                  className="submit-btn"
                >
                  Send Verification Code
                </Button>
              </Form.Item>
            </Form>
          </Card>
        );

      case 1:
        return (
          <Card className="step-card">
            <div className="step-header">
              <SafetyCertificateOutlined className="step-icon" />
              <Title level={3}>Enter Verification Code</Title>
              <Paragraph type="secondary">
                We've sent a 6-digit verification code to <strong>{email}</strong>
              </Paragraph>
            </div>

            <Form
              form={otpForm}
              layout="vertical"
              onFinish={handleOtpSubmit}
              autoComplete="off"
            >
              <Form.Item
                label="Verification Code"
                name="otp"
                rules={[
                  { required: true, message: 'Please enter the verification code' },
                  { len: 6, message: 'Verification code must be 6 digits' }
                ]}
              >
                <Input
                  placeholder="Enter 6-digit code"
                  size="large"
                  maxLength={6}
                  style={{ textAlign: 'center', fontSize: '20px', letterSpacing: '8px' }}
                />
              </Form.Item>

              <div className="resend-section">
                {resendCountdown > 0 ? (
                  <Text type="secondary">
                    <ClockCircleOutlined /> Resend code in {resendCountdown} seconds
                  </Text>
                ) : (
                  <Button
                    type="link"
                    onClick={handleResendOtp}
                    loading={loading}
                    className="resend-btn"
                  >
                    Resend Verification Code
                  </Button>
                )}
              </div>

              <Form.Item>
                <Button
                  type="primary"
                  htmlType="submit"
                  loading={loading}
                  size="large"
                  block
                  className="submit-btn"
                >
                  Verify Code
                </Button>
              </Form.Item>
            </Form>
          </Card>
        );

      case 2:
        return (
          <Card className="step-card">
            <div className="step-header">
              <LockOutlined className="step-icon" />
              <Title level={3}>Create New Password</Title>
              <Paragraph type="secondary">
                Please create a strong password for your account
              </Paragraph>
            </div>

            <Form
              form={resetForm}
              layout="vertical"
              onFinish={handlePasswordReset}
              autoComplete="off"
            >
              <Form.Item
                label="New Password"
                name="password"
                rules={[
                  { required: true, message: 'Please enter your new password' },
                  { min: 8, message: 'Password must be at least 8 characters' }
                ]}
              >
                <Input.Password
                  placeholder="Enter new password"
                  size="large"
                  onChange={handlePasswordChange}
                />
              </Form.Item>

              {passwordStrength > 0 && (
                <div className="password-strength">
                  <Text strong>Password Strength: </Text>
                  <div className="strength-bar">
                    <div
                      className="strength-fill"
                      style={{
                        width: `${passwordStrength}%`,
                        backgroundColor: getPasswordStrengthColor()
                      }}
                    />
                  </div>
                  <Text style={{ color: getPasswordStrengthColor() }}>
                    {getPasswordStrengthText()}
                  </Text>
                </div>
              )}

              <Form.Item
                label="Confirm New Password"
                name="confirmPassword"
                dependencies={['password']}
                rules={[
                  { required: true, message: 'Please confirm your new password' },
                  ({ getFieldValue }) => ({
                    validator(_, value) {
                      if (!value || getFieldValue('password') === value) {
                        return Promise.resolve();
                      }
                      return Promise.reject(new Error('Passwords do not match'));
                    },
                  }),
                ]}
              >
                <Input.Password
                  placeholder="Confirm new password"
                  size="large"
                />
              </Form.Item>

              <Form.Item>
                <Button
                  type="primary"
                  htmlType="submit"
                  loading={loading}
                  size="large"
                  block
                  className="submit-btn"
                >
                  Reset Password
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
        );

      case 3:
        return (
          <Card className="step-card success-card">
            <div className="step-header">
              <CheckCircleOutlined className="step-icon success-icon" />
              <Title level={3}>Password Reset Successfully!</Title>
              <Paragraph type="secondary">
                Your password has been updated successfully. You will be redirected to login shortly.
              </Paragraph>
            </div>

            <div className="success-actions">
              <Button
                type="primary"
                size="large"
                className="submit-btn"
                onClick={onSuccess}
              >
                Continue to Login
              </Button>
            </div>
          </Card>
        );

      default:
        return null;
    }
  };

  return (
    <div className="forgot-password-container">
      <Row justify="center">
        <Col xs={24} sm={20} md={16} lg={12} xl={10}>
          <div className="forgot-password-wrapper">
            {onBack && (
              <Button
                type="text"
                icon={<ArrowLeftOutlined />}
                onClick={onBack}
                className="back-btn"
              >
                Back to Login
              </Button>
            )}

            <Steps
              current={currentStep}
              items={steps}
              className="forgot-password-steps"
            />

            <div className="step-content">
              {renderStepContent()}
            </div>
          </div>
        </Col>
      </Row>
    </div>
  );
};

export default ForgotPassword;
