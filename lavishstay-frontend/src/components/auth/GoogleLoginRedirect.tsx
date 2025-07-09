import React from 'react';
import { Button, message } from 'antd';
import { FcGoogle } from 'react-icons/fc';
import { useDispatch } from 'react-redux';
import { loginStart, loginSuccess, loginFailure } from '../../store/slices/authSlice';
import authService from '../../services/authService';

interface GoogleLoginRedirectProps {
    onSuccess?: () => void;
    size?: "large" | "middle" | "small";
    block?: boolean;
    style?: React.CSSProperties;
    disabled?: boolean;
}

const GoogleLoginRedirect: React.FC<GoogleLoginRedirectProps> = ({
    onSuccess,
    size = "middle",
    block = false,
    style,
    disabled = false
}) => {
    const dispatch = useDispatch();

    const handleGoogleLogin = () => {
        const clientId = import.meta.env.VITE_GOOGLE_CLIENT_ID;
        const redirectUri = window.location.origin + '/auth/google/callback';

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
            Đăng nhập với Google (Redirect)
        </Button>
    );
};

export default GoogleLoginRedirect;
