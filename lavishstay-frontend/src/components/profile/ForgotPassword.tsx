import React, { useState, useEffect } from 'react';
import {
  Card,
  Form,
  Input,
  Button,
  Typography,
  Space,
  Alert,
  Steps,
  Modal,
  Progress,
  Result,
  Divider,
  Radio,
  Row,
  Col
} from 'antd';
import {
  MailOutlined,
  SafetyCertificateOutlined,
  LockOutlined,
  CheckCircleOutlined,
  ClockCircleOutlined,
  ReloadOutlined,
  PhoneOutlined,
  UserOutlined
} from '@ant-design/icons';

const { Title, Text } = Typography;

interface ForgotPasswordProps {
  onBack?: () => void;
  onSuccess?: () => void;
}

const ForgotPassword: React.FC<ForgotPasswordProps> = ({ onSuccess }) => {
  const [emailForm] = Form.useForm();
  const [otpForm] = Form.useForm();
  const [resetForm] = Form.useForm();
  const [currentStep, setCurrentStep] = useState(0);
  const [loading, setLoading] = useState(false);
  const [email, setEmail] = useState('');
  const [phoneNumber, setPhoneNumber] = useState('');
  const [resendCountdown, setResendCountdown] = useState(0);
  const [passwordStrength, setPasswordStrength] = useState(0);
  const [verificationMethod, setVerificationMethod] = useState<'email' | 'phone'>('email'); const steps = [
    {
      title: 'Chọn Phương Thức',
      icon: <UserOutlined />,
    },
    {
      title: verificationMethod === 'email' ? 'Nhập Email' : 'Nhập SĐT',
      icon: verificationMethod === 'email' ? <MailOutlined /> : <PhoneOutlined />,
    },
    {
      title: 'Xác Thực OTP',
      icon: <SafetyCertificateOutlined />,
    },
    {
      title: 'Đặt Lại Mật Khẩu',
      icon: <LockOutlined />,
    },
    {
      title: 'Hoàn Thành',
      icon: <CheckCircleOutlined />,
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
    if (passwordStrength < 25) return 'Yếu';
    if (passwordStrength < 50) return 'Trung bình';
    if (passwordStrength < 75) return 'Tốt';
    return 'Mạnh';
  }; const handleEmailSubmit = async (values: { email: string }) => {
    setLoading(true);
    try {
      // API call to send OTP to email
      await new Promise(resolve => setTimeout(resolve, 2000));
      setEmail(values.email);
      setCurrentStep(2);
      setResendCountdown(60); // 60 seconds countdown
    } catch (error) {
      Modal.error({
        title: 'Lỗi',
        content: 'Không thể gửi mã xác thực. Vui lòng thử lại.',
      });
    } finally {
      setLoading(false);
    }
  };

  const handlePhoneSubmit = async (values: { phoneNumber: string }) => {
    setLoading(true);
    try {
      // API call to send OTP to phone
      await new Promise(resolve => setTimeout(resolve, 2000));
      setPhoneNumber(values.phoneNumber);
      setCurrentStep(2);
      setResendCountdown(60); // 60 seconds countdown
    } catch (error) {
      Modal.error({
        title: 'Lỗi',
        content: 'Không thể gửi mã xác thực. Vui lòng thử lại.',
      });
    } finally {
      setLoading(false);
    }
  };
  const handleOtpSubmit = async (_values: { otp: string }) => {
    setLoading(true);
    try {
      // API call to verify OTP
      await new Promise(resolve => setTimeout(resolve, 1500));
      setCurrentStep(3);
    } catch (error) {
      Modal.error({
        title: 'Mã không hợp lệ',
        content: 'Mã xác thực không đúng. Vui lòng thử lại.',
      });
    } finally {
      setLoading(false);
    }
  };
  const handlePasswordReset = async (_values: { password: string; confirmPassword: string }) => {
    setLoading(true);
    try {
      // API call to reset password
      await new Promise(resolve => setTimeout(resolve, 2000));
      setCurrentStep(4);
      setTimeout(() => {
        onSuccess?.();
      }, 3000);
    } catch (error) {
      Modal.error({
        title: 'Lỗi',
        content: 'Không thể đặt lại mật khẩu. Vui lòng thử lại.',
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
        title: 'Đã gửi mã',
        content: 'Mã xác thực mới đã được gửi đến email của bạn.',
      });
    } catch (error) {
      Modal.error({
        title: 'Lỗi',
        content: 'Không thể gửi lại mã xác thực. Vui lòng thử lại.',
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
  }, [resendCountdown]); const renderStepContent = () => {
    switch (currentStep) {
      case 0:
        return (
          <div style={{ padding: '24px 0' }}>
            <Space direction="vertical" size="large" style={{ width: '100%' }}>
              <div style={{ textAlign: 'center', marginBottom: '24px' }}>
                <div style={{
                  width: '64px',
                  height: '64px',
                  backgroundColor: '#f0f0f0',
                  borderRadius: '50%',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  margin: '0 auto 16px'
                }}>
                  <UserOutlined style={{ fontSize: '24px', color: '#595959' }} />
                </div>
                <Title level={3} style={{ marginBottom: '8px', color: '#262626' }}>
                  Chọn Phương Thức Khôi Phục
                </Title>
                <Text type="secondary" style={{ fontSize: '14px' }}>
                  Lựa chọn cách thức xác thực để khôi phục tài khoản
                </Text>
              </div>

              <div style={{ maxWidth: '400px', margin: '0 auto', width: '100%' }}>
                <Space direction="vertical" size="large" style={{ width: '100%' }}>
                  <Radio.Group
                    value={verificationMethod}
                    onChange={(e) => setVerificationMethod(e.target.value)}
                    style={{ width: '100%' }}
                  >
                    <Row gutter={[16, 16]}>
                      <Col span={12}>
                        <Card
                          hoverable
                          className={verificationMethod === 'email' ? 'selected-card' : ''}
                          onClick={() => setVerificationMethod('email')}
                          style={{
                            textAlign: 'center',
                            border: verificationMethod === 'email' ? '2px solid #1890ff' : '1px solid #d9d9d9',
                            cursor: 'pointer'
                          }}
                        >
                          <Radio value="email" style={{ display: 'none' }} />
                          <div style={{ padding: '16px 0' }}>
                            <MailOutlined style={{ fontSize: '32px', color: '#1890ff', marginBottom: '12px' }} />
                            <div>
                              <Text strong>Email</Text>
                              <br />
                              <Text type="secondary" style={{ fontSize: '12px' }}>
                                Gửi mã qua email
                              </Text>
                            </div>
                          </div>
                        </Card>
                      </Col>
                      <Col span={12}>
                        <Card
                          hoverable
                          className={verificationMethod === 'phone' ? 'selected-card' : ''}
                          onClick={() => setVerificationMethod('phone')}
                          style={{
                            textAlign: 'center',
                            border: verificationMethod === 'phone' ? '2px solid #1890ff' : '1px solid #d9d9d9',
                            cursor: 'pointer'
                          }}
                        >
                          <Radio value="phone" style={{ display: 'none' }} />
                          <div style={{ padding: '16px 0' }}>
                            <PhoneOutlined style={{ fontSize: '32px', color: '#52c41a', marginBottom: '12px' }} />
                            <div>
                              <Text strong>Số điện thoại</Text>
                              <br />
                              <Text type="secondary" style={{ fontSize: '12px' }}>
                                Gửi OTP qua SMS
                              </Text>
                            </div>
                          </div>
                        </Card>
                      </Col>
                    </Row>
                  </Radio.Group>

                  <Button
                    type="primary"
                    size="large"
                    block
                    onClick={() => setCurrentStep(1)}
                  >
                    Tiếp Tục
                  </Button>
                </Space>
              </div>
            </Space>
          </div>
        );

      case 1:
        return verificationMethod === 'email' ? (
          <div style={{ padding: '24px 0' }}>
            <Space direction="vertical" size="large" style={{ width: '100%' }}>
              <div style={{ textAlign: 'center', marginBottom: '24px' }}>
                <div style={{
                  width: '64px',
                  height: '64px',
                  backgroundColor: '#f0f0f0',
                  borderRadius: '50%',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  margin: '0 auto 16px'
                }}>
                  <MailOutlined style={{ fontSize: '24px', color: '#595959' }} />
                </div>
                <Title level={3} style={{ marginBottom: '8px', color: '#262626' }}>
                  Nhập Địa Chỉ Email
                </Title>
                <Text type="secondary" style={{ fontSize: '14px' }}>
                  Nhập email đã đăng ký để nhận mã xác thực
                </Text>
              </div>

              <Form
                form={emailForm}
                layout="vertical"
                onFinish={handleEmailSubmit}
                style={{ maxWidth: '400px', margin: '0 auto', width: '100%' }}
              >
                <Form.Item
                  label="Email"
                  name="email"
                  rules={[
                    { required: true, message: 'Vui lòng nhập email' },
                    { type: 'email', message: 'Email không hợp lệ' }
                  ]}
                >
                  <Input
                    prefix={<MailOutlined style={{ color: '#8c8c8c' }} />}
                    placeholder="Nhập địa chỉ email"
                    size="large"
                  />
                </Form.Item>

                <Space direction="vertical" size="middle" style={{ width: '100%' }}>
                  <Button
                    type="primary"
                    htmlType="submit"
                    loading={loading}
                    size="large"
                    block
                  >
                    Gửi Mã Xác Thực
                  </Button>

                  <Button
                    type="link"
                    onClick={() => setCurrentStep(0)}
                    style={{ padding: 0, fontSize: '14px' }}
                  >
                    Quay lại chọn phương thức khác
                  </Button>
                </Space>
              </Form>
            </Space>
          </div>
        ) : (
          <div style={{ padding: '24px 0' }}>
            <Space direction="vertical" size="large" style={{ width: '100%' }}>
              <div style={{ textAlign: 'center', marginBottom: '24px' }}>
                <div style={{
                  width: '64px',
                  height: '64px',
                  backgroundColor: '#f0f0f0',
                  borderRadius: '50%',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  margin: '0 auto 16px'
                }}>
                  <PhoneOutlined style={{ fontSize: '24px', color: '#595959' }} />
                </div>
                <Title level={3} style={{ marginBottom: '8px', color: '#262626' }}>
                  Nhập Số Điện Thoại
                </Title>
                <Text type="secondary" style={{ fontSize: '14px' }}>
                  Nhập số điện thoại đã đăng ký để nhận OTP
                </Text>
              </div>

              <Form
                layout="vertical"
                onFinish={handlePhoneSubmit}
                style={{ maxWidth: '400px', margin: '0 auto', width: '100%' }}
              >
                <Form.Item
                  label="Số điện thoại"
                  name="phoneNumber"
                  rules={[
                    { required: true, message: 'Vui lòng nhập số điện thoại' },
                    {
                      pattern: /^(0[3|5|7|8|9])+([0-9]{8})$/,
                      message: 'Số điện thoại không hợp lệ'
                    }
                  ]}
                >
                  <Input
                    prefix={<PhoneOutlined style={{ color: '#8c8c8c' }} />}
                    placeholder="Nhập số điện thoại"
                    size="large"
                  />
                </Form.Item>

                <Space direction="vertical" size="middle" style={{ width: '100%' }}>
                  <Button
                    type="primary"
                    htmlType="submit"
                    loading={loading}
                    size="large"
                    block
                  >
                    Gửi Mã OTP
                  </Button>

                  <Button
                    type="link"
                    onClick={() => setCurrentStep(0)}
                    style={{ padding: 0, fontSize: '14px' }}
                  >
                    Quay lại chọn phương thức khác
                  </Button>
                </Space>
              </Form>
            </Space>
          </div>
        ); case 2:
        return (
          <div style={{ padding: '24px 0' }}>
            <Space direction="vertical" size="large" style={{ width: '100%' }}>
              <div style={{ textAlign: 'center', marginBottom: '24px' }}>
                <div style={{
                  width: '64px',
                  height: '64px',
                  backgroundColor: '#f0f0f0',
                  borderRadius: '50%',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  margin: '0 auto 16px'
                }}>
                  <SafetyCertificateOutlined style={{ fontSize: '24px', color: '#595959' }} />
                </div>
                <Title level={3} style={{ marginBottom: '8px', color: '#262626' }}>
                  Xác Thực OTP
                </Title>
                <Text type="secondary" style={{ fontSize: '14px' }}>
                  Nhập mã 6 số đã gửi đến{' '}
                  <strong>
                    {verificationMethod === 'email' ? email : phoneNumber}
                  </strong>
                </Text>
              </div>

              <Form
                form={otpForm}
                layout="vertical"
                onFinish={handleOtpSubmit}
                style={{ maxWidth: '400px', margin: '0 auto', width: '100%' }}
              >
                <Form.Item
                  label="Mã xác thực"
                  name="otp"
                  rules={[
                    { required: true, message: 'Vui lòng nhập mã xác thực' },
                    { len: 6, message: 'Mã xác thực phải có 6 số' }
                  ]}
                >
                  <Input
                    placeholder="000000"
                    size="large"
                    maxLength={6}
                    style={{
                      textAlign: 'center',
                      fontSize: '18px',
                      letterSpacing: '4px',
                      fontWeight: '500'
                    }}
                  />
                </Form.Item>

                <Space direction="vertical" size="middle" style={{ width: '100%' }}>
                  {resendCountdown > 0 ? (
                    <Alert
                      message={`Gửi lại mã sau ${resendCountdown} giây`}
                      type="info"
                      showIcon
                      icon={<ClockCircleOutlined />}
                    />
                  ) : (
                    <Button
                      type="link"
                      onClick={handleResendOtp}
                      loading={loading}
                      icon={<ReloadOutlined />}
                      style={{ padding: 0, fontSize: '14px' }}
                    >
                      Gửi Lại Mã Xác Thực
                    </Button>
                  )}

                  <Button
                    type="primary"
                    htmlType="submit"
                    loading={loading}
                    size="large"
                    block
                  >
                    Xác Thực
                  </Button>

                  <Button
                    type="link"
                    onClick={() => setCurrentStep(1)}
                    style={{ padding: 0, fontSize: '14px' }}
                  >
                    Quay lại
                  </Button>
                </Space>
              </Form>
            </Space>
          </div>
        ); case 3:
        return (
          <div style={{ padding: '24px 0' }}>
            <Space direction="vertical" size="large" style={{ width: '100%' }}>
              <div style={{ textAlign: 'center', marginBottom: '24px' }}>
                <div style={{
                  width: '64px',
                  height: '64px',
                  backgroundColor: '#f0f0f0',
                  borderRadius: '50%',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  margin: '0 auto 16px'
                }}>
                  <LockOutlined style={{ fontSize: '24px', color: '#595959' }} />
                </div>
                <Title level={3} style={{ marginBottom: '8px', color: '#262626' }}>
                  Tạo Mật Khẩu Mới
                </Title>
                <Text type="secondary" style={{ fontSize: '14px' }}>
                  Tạo mật khẩu mạnh cho tài khoản của bạn
                </Text>
              </div>

              <Form
                form={resetForm}
                layout="vertical"
                onFinish={handlePasswordReset}
                style={{ maxWidth: '400px', margin: '0 auto', width: '100%' }}
              >
                <Form.Item
                  label="Mật khẩu mới"
                  name="password"
                  rules={[
                    { required: true, message: 'Vui lòng nhập mật khẩu mới' },
                    { min: 8, message: 'Mật khẩu phải có ít nhất 8 ký tự' }
                  ]}
                >
                  <Input.Password
                    placeholder="Nhập mật khẩu mới"
                    size="large"
                    onChange={handlePasswordChange}
                  />
                </Form.Item>

                {passwordStrength > 0 && (
                  <div style={{ marginBottom: '16px' }}>
                    <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '8px' }}>
                      <Text style={{ fontSize: '12px', color: '#8c8c8c' }}>Độ mạnh:</Text>
                      <Text style={{ fontSize: '12px', color: getPasswordStrengthColor(), fontWeight: 500 }}>
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
                  label="Xác nhận mật khẩu"
                  name="confirmPassword"
                  dependencies={['password']}
                  rules={[
                    { required: true, message: 'Vui lòng xác nhận mật khẩu' },
                    ({ getFieldValue }) => ({
                      validator(_, value) {
                        if (!value || getFieldValue('password') === value) {
                          return Promise.resolve();
                        }
                        return Promise.reject(new Error('Mật khẩu không khớp'));
                      },
                    }),
                  ]}
                >
                  <Input.Password
                    placeholder="Xác nhận mật khẩu mới"
                    size="large"
                  />
                </Form.Item>

                <Alert
                  message="Yêu cầu mật khẩu"
                  description={
                    <div style={{ fontSize: '12px', lineHeight: '1.5' }}>
                      • Ít nhất 8 ký tự<br />
                      • Có chữ hoa và chữ thường<br />
                      • Có ít nhất 1 số và 1 ký tự đặc biệt
                    </div>
                  }
                  type="info"
                  showIcon
                  style={{ marginBottom: '24px' }}
                />

                <Form.Item style={{ marginBottom: 0 }}>
                  <Button
                    type="primary"
                    htmlType="submit"
                    loading={loading}
                    size="large"
                    block
                  >
                    Đặt Lại Mật Khẩu
                  </Button>
                </Form.Item>
              </Form>
            </Space>
          </div>
        );

      case 4:
        return (
          <Result
            status="success"
            title="Đặt Lại Mật Khẩu Thành Công!"
            subTitle="Mật khẩu đã được cập nhật. Bạn có thể đăng nhập với mật khẩu mới."
            extra={[
              <Button
                type="primary"
                size="large"
                onClick={onSuccess}
                key="login"
              >
                Đóng
              </Button>
            ]}
            style={{ padding: '24px 0' }}
          />
        );

      default:
        return null;
    }
  }; return (
    <div style={{ padding: '24px' }}>
      <Card>
        <Space direction="vertical" size="large" style={{ width: '100%' }}>
          {/* Header */}
          <div style={{ textAlign: 'center', borderBottom: '1px solid #f0f0f0', paddingBottom: '16px' }}>
            <Title level={4} style={{ margin: 0, color: '#262626' }}>
              Quên Mật Khẩu
            </Title>
            <Text type="secondary" style={{ fontSize: '14px' }}>
              Khôi phục tài khoản của bạn
            </Text>
          </div>

          {/* Steps */}
          <Steps
            current={currentStep}
            items={steps}
            size="small"
            style={{ marginBottom: '24px' }}
          />

          <Divider style={{ margin: 0 }} />

          {/* Content */}
          {renderStepContent()}
        </Space>
      </Card>
    </div>
  );
};

export default ForgotPassword;
