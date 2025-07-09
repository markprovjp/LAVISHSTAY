import React from 'react';
import { GoogleOAuthProvider } from '@react-oauth/google';

interface GoogleAuthProviderProps {
    children: React.ReactNode;
}

const GoogleAuthProviderWrapper: React.FC<GoogleAuthProviderProps> = ({ children }) => {
    // Google OAuth Client ID - cần được cấu hình trong Google Console
    const clientId = import.meta.env.VITE_GOOGLE_CLIENT_ID || "your-google-client-id.googleusercontent.com";

    console.log('🔧 Google OAuth Client ID:', clientId);
    console.log('🔧 Environment:', import.meta.env.MODE);
    console.log('🔧 Current URL:', window.location.origin);

    if (!clientId || clientId === "your-google-client-id.googleusercontent.com") {
        console.error('❌ Google Client ID không được cấu hình đúng!');
    }

    return (
        <GoogleOAuthProvider clientId={clientId}>
            {children}
        </GoogleOAuthProvider>
    );
};

export default GoogleAuthProviderWrapper;
