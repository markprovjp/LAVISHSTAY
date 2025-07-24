import React, { useState } from 'react';
import { Card, Steps, Alert, Typography, Collapse } from 'antd';
import { InfoCircleOutlined, CheckCircleOutlined, ExclamationCircleOutlined } from '@ant-design/icons';

const { Text, Paragraph, Title } = Typography;
const { Panel } = Collapse;

const GoogleOAuthSetupGuide: React.FC = () => {
    const [currentStep, setCurrentStep] = useState(0);

    const clientId = import.meta.env.VITE_GOOGLE_CLIENT_ID;
    const currentOrigin = window.location.origin;

    const setupSteps = [
        {
            title: 'Kiểm tra Google Console Project',
            description: 'Đảm bảo project đã được tạo và cấu hình đúng',
            content: (
                <div>
                    <Paragraph>
                        1. Truy cập <a href="https://console.cloud.google.com/" target="_blank" rel="noopener noreferrer">Google Cloud Console</a>
                    </Paragraph>
                    <Paragraph>
                        2. Tạo hoặc chọn project
                    </Paragraph>
                    <Paragraph>
                        3. Enable Google+ API và Google OAuth2 API
                    </Paragraph>
                </div>
            )
        },
        {
            title: 'Cấu hình OAuth Consent Screen',
            description: 'Thiết lập thông tin hiển thị cho user khi đăng nhập',
            content: (
                <div>
                    <Paragraph>
                        1. Vào "APIs & Services" → "OAuth consent screen"
                    </Paragraph>
                    <Paragraph>
                        2. Chọn "External" nếu app chưa verify, "Internal" nếu trong tổ chức
                    </Paragraph>
                    <Paragraph>
                        3. Điền thông tin app name, user support email, developer email
                    </Paragraph>
                    <Paragraph>
                        4. Thêm scopes: email, profile, openid
                    </Paragraph>
                </div>
            )
        },
        {
            title: 'Tạo OAuth 2.0 Client IDs',
            description: 'Tạo credentials cho web application',
            content: (
                <div>
                    <Paragraph>
                        1. Vào "APIs & Services" → "Credentials"
                    </Paragraph>
                    <Paragraph>
                        2. Click "Create Credentials" → "OAuth 2.0 Client IDs"
                    </Paragraph>
                    <Paragraph>
                        3. Chọn "Web application"
                    </Paragraph>
                    <Paragraph>
                        4. <strong>Quan trọng:</strong> Thêm Authorized JavaScript origins:
                    </Paragraph>
                    <ul>
                        <li><Text code>http://localhost:3000</Text></li>
                        <li><Text code>http://localhost:5173</Text></li>
                        <li><Text code>{currentOrigin}</Text> (domain hiện tại)</li>
                    </ul>
                    <Paragraph>
                        5. <strong>Không cần</strong> thêm Authorized redirect URIs cho popup mode
                    </Paragraph>
                </div>
            )
        },
        {
            title: 'Cấu hình Environment Variables',
            description: 'Thiết lập Client ID trong .env file',
            content: (
                <div>
                    <Paragraph>
                        Thêm vào file <Text code>.env</Text>:
                    </Paragraph>
                    <Text code style={{ display: 'block', padding: '8px', backgroundColor: '#f5f5f5' }}>
                        VITE_GOOGLE_CLIENT_ID=your-client-id.apps.googleusercontent.com
                    </Text>
                    <Paragraph style={{ marginTop: 16 }}>
                        Client ID hiện tại: <Text code>{clientId || 'Chưa được cấu hình'}</Text>
                    </Paragraph>
                </div>
            )
        }
    ];

    const checkCurrentConfig = () => {
        const issues = [];

        if (!clientId) {
            issues.push('Client ID chưa được cấu hình');
        } else if (!clientId.endsWith('.apps.googleusercontent.com')) {
            issues.push('Client ID không đúng định dạng');
        }

        if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost') {
            issues.push('Cần sử dụng HTTPS hoặc localhost');
        }

        if (window.self !== window.top) {
            issues.push('Đang chạy trong iframe (có thể gây vấn đề)');
        }

        return issues;
    };

    const issues = checkCurrentConfig();

    return (
        <Card title="🔧 Google OAuth Setup Guide" style={{ margin: '16px 0' }}>
            {issues.length > 0 && (
                <Alert
                    type="warning"
                    showIcon
                    icon={<ExclamationCircleOutlined />}
                    message="Cấu hình chưa hoàn chỉnh"
                    description={
                        <ul>
                            {issues.map((issue, index) => (
                                <li key={index}>{issue}</li>
                            ))}
                        </ul>
                    }
                    style={{ marginBottom: 16 }}
                />
            )}

            {issues.length === 0 && (
                <Alert
                    type="success"
                    showIcon
                    icon={<CheckCircleOutlined />}
                    message="Cấu hình có vẻ đúng!"
                    description="Tất cả kiểm tra cơ bản đã passed. Nếu vẫn gặp lỗi, hãy kiểm tra Google Console."
                    style={{ marginBottom: 16 }}
                />
            )}

            <Collapse>
                <Panel header="📖 Hướng dẫn chi tiết" key="guide">
                    <Steps
                        current={currentStep}
                        direction="vertical"
                        items={setupSteps.map((step, index) => ({
                            title: step.title,
                            description: step.description,
                            icon: currentStep === index ? <InfoCircleOutlined /> : undefined
                        }))}
                        onChange={setCurrentStep}
                    />

                    <Card size="small" style={{ marginTop: 16 }}>
                        <Title level={5}>{setupSteps[currentStep].title}</Title>
                        {setupSteps[currentStep].content}
                    </Card>
                </Panel>

                <Panel header="🐛 Troubleshooting" key="troubleshooting">
                    <div>
                        <Title level={5}>Lỗi "Popup window closed":</Title>
                        <ul>
                            <li>User đóng popup quá nhanh → Giải thích cho user đợi</li>
                            <li>Popup bị browser block → Hướng dẫn tắt popup blocker</li>
                            <li>Domain chưa được authorize → Kiểm tra Google Console</li>
                        </ul>

                        <Title level={5}>Lỗi "Invalid client":</Title>
                        <ul>
                            <li>Client ID sai → Kiểm tra VITE_GOOGLE_CLIENT_ID</li>
                            <li>Domain không được authorize → Thêm vào Authorized JavaScript origins</li>
                        </ul>

                        <Title level={5}>Lỗi "Restricted client":</Title>
                        <ul>
                            <li>App chưa được verify → Thêm test users hoặc submit for verification</li>
                            <li>Quota exceeded → Kiểm tra usage limits</li>
                        </ul>
                    </div>
                </Panel>
            </Collapse>
        </Card>
    );
};

export default GoogleOAuthSetupGuide;
