import React from 'react';
import { Button, message } from 'antd';
import { FcGoogle } from 'react-icons/fc';

interface GoogleLoginButtonProps {
    onSuccess?: () => void;
    size?: "large" | "middle" | "small";
    block?: boolean;
    style?: React.CSSProperties;
    disabled?: boolean;
}

const GoogleLoginButton: React.FC<GoogleLoginButtonProps> = ({
    onSuccess,
    size = "middle",
    block = false,
    style,
    disabled = false
}) => {
    const handleGoogleLogin = () => {
        const clientId = import.meta.env.VITE_GOOGLE_CLIENT_ID;
        const redirectUri = window.location.origin + '/auth/google/callback';

        console.log('🚀 Starting Google OAuth Redirect...');
        console.log('🔧 Client ID:', clientId);
        console.log('🌐 Current Origin:', window.location.origin);
        console.log('🔗 Redirect URI:', redirectUri);

        // Kiểm tra Client ID
        if (!clientId) {
            console.error('❌ VITE_GOOGLE_CLIENT_ID not configured!');
            message.error('Client ID chưa được cấu hình!');
            return;
        }

        if (!clientId.endsWith('.apps.googleusercontent.com')) {
            console.error('❌ Invalid Client ID format!');
            message.error('Client ID không đúng định dạng!');
            return;
        }

        // Lưu callback nếu có để gọi sau khi login thành công
        if (onSuccess) {
            localStorage.setItem('google_login_callback', 'true');
        }

        // Tạo state để bảo mật
        const state = Math.random().toString(36).substring(2, 15);
        localStorage.setItem('google_oauth_state', state);

        // Tạo URL Google OAuth
        const googleAuthUrl = new URL('https://accounts.google.com/o/oauth2/v2/auth');
        googleAuthUrl.searchParams.set('client_id', clientId);
        googleAuthUrl.searchParams.set('redirect_uri', redirectUri);
        googleAuthUrl.searchParams.set('response_type', 'code');
        googleAuthUrl.searchParams.set('scope', 'openid profile email');
        googleAuthUrl.searchParams.set('state', state);
        googleAuthUrl.searchParams.set('include_granted_scopes', 'true');
        googleAuthUrl.searchParams.set('access_type', 'online');

        console.log('🔗 Redirecting to Google OAuth:', googleAuthUrl.toString());

        // Redirect đến Google
        window.location.href = googleAuthUrl.toString();
    };

    return (
        <Button
            icon={<FcGoogle size={20} />}
            onClick={handleGoogleLogin}
            size={size}
            block={block}
            disabled={disabled}
            style={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                gap: 8,
                fontWeight: 500,
                ...style
            }}
        >
            Đăng nhập với Google
        </Button>
    );
};

export default GoogleLoginButton;