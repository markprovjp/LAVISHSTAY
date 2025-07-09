import React from 'react';
import { useGoogleLogin } from '@react-oauth/google';
import { Button, message } from 'antd';
import { FcGoogle } from 'react-icons/fc';

const GoogleOAuthTest: React.FC = () => {
    const testGoogleLogin = useGoogleLogin({
        onSuccess: (tokenResponse) => {
            console.log('✅ Google OAuth Test Success:', tokenResponse);
            message.success('Google OAuth test thành công!');

            // Log chi tiết token
            console.log('📄 Token Response Details:', {
                access_token: tokenResponse.access_token,
                token_type: tokenResponse.token_type,
                expires_in: tokenResponse.expires_in,
                scope: tokenResponse.scope,
            });
        },
        onError: (error) => {
            console.error('❌ Google OAuth Test Error:', error);
            message.error(`Google OAuth test thất bại: ${error.error_description || error.error}`);
        },
        onNonOAuthError: (error) => {
            console.error('❌ Non-OAuth Test Error:', error);
            message.error('Lỗi kết nối. Kiểm tra popup blocker!');
        },
        scope: 'openid profile email',
        flow: 'implicit',
    });

    return (
        <div style={{ margin: '20px', padding: '20px', border: '1px dashed #ccc' }}>
            <h3>🔧 Google OAuth Debug Test</h3>
            <Button
                icon={<FcGoogle size={20} />}
                onClick={() => {
                    console.log('🚀 Testing Google OAuth...');
                    testGoogleLogin();
                }}
                style={{
                    display: 'flex',
                    alignItems: 'center',
                    gap: 8,
                }}
            >
                Test Google OAuth
            </Button>
            <p style={{ marginTop: 10, fontSize: 12, color: '#666' }}>
                Mở Console để xem logs chi tiết
            </p>
        </div>
    );
};

export default GoogleOAuthTest;
