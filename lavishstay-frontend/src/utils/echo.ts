import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// safe env helper for Vite and legacy REACT_APP vars
const env = (import.meta as any)?.env || (window as any).__ENV || {};

// Debug log environment variables
console.log('🔍 Environment variables debug:', {
    VITE_PUSHER_KEY: env.VITE_PUSHER_KEY,
    VITE_PUSHER_HOST: env.VITE_PUSHER_HOST,
    VITE_PUSHER_PORT: env.VITE_PUSHER_PORT,
    VITE_PUSHER_SCHEME: env.VITE_PUSHER_SCHEME,
    VITE_PUSHER_CLUSTER: env.VITE_PUSHER_CLUSTER,
    REACT_APP_PUSHER_KEY: env.REACT_APP_PUSHER_KEY,
    REACT_APP_PUSHER_HOST: env.REACT_APP_PUSHER_HOST,
});

// Use VITE variables first, then REACT_APP as fallback
const PUSHER_KEY = env.VITE_PUSHER_KEY || env.REACT_APP_PUSHER_KEY || 'your-pusher-key';
const PUSHER_HOST = env.VITE_PUSHER_HOST || env.REACT_APP_PUSHER_HOST || 'localhost';
const PUSHER_PORT = Number(env.VITE_PUSHER_PORT || env.REACT_APP_PUSHER_PORT || 6001);
const PUSHER_SCHEME = env.VITE_PUSHER_SCHEME || env.REACT_APP_PUSHER_SCHEME || 'http';
const PUSHER_CLUSTER = env.VITE_PUSHER_CLUSTER || env.REACT_APP_PUSHER_CLUSTER || 'mt1';

const API_URL = env.VITE_API_URL || env.REACT_APP_API_URL || 'http://localhost:8888';

// Attach Pusher to window for Echo
if (typeof window !== 'undefined') {
    (window as any).Pusher = Pusher;
    try {
        (window as any).__ECHO_ENV = {
            PUSHER_KEY: PUSHER_KEY,
            PUSHER_HOST: PUSHER_HOST,
            PUSHER_PORT: PUSHER_PORT,
            PUSHER_SCHEME: PUSHER_SCHEME,
            PUSHER_CLUSTER: PUSHER_CLUSTER,
            API_URL,
        };
        // Log the env object for visibility
        // eslint-disable-next-line no-console
        console.log('Echo env:', (window as any).__ECHO_ENV);

        // If Vite env not set, allow fallback to a developer-specified localStorage key for quick testing
        const storedKey = (window as any).localStorage?.getItem?.('PUSHER_KEY');
        if ((!PUSHER_KEY || PUSHER_KEY === 'your-pusher-key') && storedKey) {
            (window as any).__ECHO_ENV.PUSHER_KEY = storedKey;
            // eslint-disable-next-line no-console
            console.log('Echo: using PUSHER_KEY from localStorage for quick dev:', storedKey);
        }

        if (!((window as any).__ECHO_ENV.PUSHER_KEY)) {
            // eslint-disable-next-line no-console
            console.error('Pusher key not set. Please set VITE_PUSHER_KEY in `lavishstay-frontend/.env`, or set localStorage.PUSHER_KEY in the browser and reload.');
        }
    } catch (e) {
        // ignore
    }
}

// Decide TLS usage
const isLocalhost = PUSHER_HOST === 'localhost' || PUSHER_HOST === '127.0.0.1';
const useTLS = !isLocalhost && (PUSHER_SCHEME === 'https' || PUSHER_SCHEME === 'wss');
const transports = useTLS ? (['wss', 'ws'] as any) : (['ws'] as any);

// Create Echo only in browser
let echo: any = null;
if (typeof window !== 'undefined') {
    // Determine final key to use (prefer Vite/env, otherwise fallback to window.__ECHO_ENV.PUSHER_KEY possibly set from localStorage)
    const RUNTIME_PUSHER_KEY = (window as any).__ECHO_ENV?.PUSHER_KEY || PUSHER_KEY;

    // Log the final values used to initialize Echo
    try {
        // eslint-disable-next-line no-console
        console.log('Echo init - key:', RUNTIME_PUSHER_KEY, 'host:', PUSHER_HOST, 'port:', PUSHER_PORT, 'useTLS:', useTLS);
    } catch (e) { }

    echo = new Echo({
        broadcaster: 'pusher',
        key: RUNTIME_PUSHER_KEY,
        cluster: PUSHER_CLUSTER,
        wsHost: PUSHER_HOST,
        wsPort: Number(PUSHER_PORT),
        wssPort: Number(PUSHER_PORT),
        forceTLS: useTLS,
        encrypted: useTLS,
        disableStats: true,
        enabledTransports: transports,
        authEndpoint: `${API_URL.replace(/\/\/$/, '')}/broadcasting/auth`,
        auth: {
            headers: {
                // prefer authToken, then token, then accessToken
                Authorization: `Bearer ${localStorage.getItem('authToken') || localStorage.getItem('token') || localStorage.getItem('accessToken')}`,
                Accept: 'application/json',
            },
        },
    });
} export default echo;

// Type definitions
export interface NotificationData {
    id: string;
    type: string;
    notifiable_type: string;
    notifiable_id: number;
    data: {
        booking_id: number;
        message: string;
        url: string;
        booking_code?: string;
    };
    created_at: string;
    read_at: string | null;
}

export interface NotificationResponse {
    success: boolean;
    data: NotificationData[];
    meta: {
        total: number;
        page: number;
        per_page: number;
        unread_count: number;
    };
}

export interface UnreadCountResponse {
    success: boolean;
    unread_count: number;
}
