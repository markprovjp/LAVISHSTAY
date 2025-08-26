import React, { useEffect } from 'react';
import { useSearchParams, useNavigate } from 'react-router-dom';
import { message, Spin } from 'antd';
import { useDispatch } from 'react-redux';
import { loginStart, loginSuccess, loginFailure, updateUser } from '../../store/slices/authSlice';
import authService from '../../services/authService';

const GoogleOAuthCallback: React.FC = () => {
    const [searchParams] = useSearchParams();
    const navigate = useNavigate();
    const dispatch = useDispatch();

    useEffect(() => {
        const handleGoogleCallback = async () => {
            try {
                console.log('🔄 Processing Google OAuth callback...');

                // Lấy authorization code từ URL
                const code = searchParams.get('code');
                const error = searchParams.get('error');
                const state = searchParams.get('state');

                console.log('📋 Callback params:', { code: !!code, error, state });

                if (error) {
                    console.error('❌ OAuth error:', error);
                    const errorDescription = searchParams.get('error_description');
                    message.error(`Lỗi Google OAuth: ${errorDescription || error}`);
                    navigate('/');
                    return;
                }

                if (!code) {
                    console.error('❌ No authorization code received');
                    message.error('Không nhận được mã xác thực từ Google');
                    navigate('/');
                    return;
                }

                dispatch(loginStart());

                // Gửi authorization code đến backend
                console.log('📤 Sending authorization code to backend...');
                const response = await authService.googleLogin(code, true); // true = isCode

                console.log('✅ Backend response:', response);
                console.log('👤 User data from backend:', JSON.stringify(response.user, null, 2));
                console.log('🖼️ Avatar URL from backend:', response.user?.avatar);


                dispatch(loginSuccess({
                    user: response.user,
                    token: response.token
                }));

                // Immediately fetch /auth/me to ensure roles and fresh data are stored
                try {
                    const me = await authService.getCurrentUser();
                    // Update redux store with full user including roles
                    dispatch(updateUser(me));
                } catch (e) {
                    // If /me fails, don't block the login flow; the initial user is already stored
                    console.warn('Failed to fetch /auth/me after Google login:', e);
                }

                message.success(`Chào mừng ${response.user.name}! Đăng nhập Google thành công.`);

                // Redirect về trang chủ hoặc trang trước đó
                const returnUrl = localStorage.getItem('pre_login_url') || '/';
                localStorage.removeItem('pre_login_url');
                navigate(returnUrl);

            } catch (error: any) {
                console.error('❌ Google OAuth callback error:', error);

                const errorMessage = error?.response?.data?.message ||
                    error?.response?.data?.error ||
                    error?.message ||
                    'Đăng nhập Google thất bại. Vui lòng thử lại!';

                dispatch(loginFailure(errorMessage));
                message.error(errorMessage);
                navigate('/');
            }
        };

        handleGoogleCallback();
    }, [searchParams, navigate, dispatch]);

    return (
        <div style={{
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'center',
            justifyContent: 'center',
            minHeight: '50vh',
            gap: 16
        }}>
            <Spin size="large" />
            <div style={{ fontSize: 16, color: '#666' }}>
                Đang xử lý đăng nhập Google...
            </div>
        </div>
    );
};

export default GoogleOAuthCallback;
