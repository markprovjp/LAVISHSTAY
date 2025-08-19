import { useState, useEffect } from 'react';
import { message } from 'antd';

export interface PaymentSettings {
  vietqr: {
    bank_id: string;
    account_no: string;
    account_name: string;
    template: string;
    enabled: boolean;
  };
  cpay: {
    timeout: number;
    enabled: boolean;
  };
  vnpay: {
    enabled: boolean;
  };
  pay_at_hotel: {
    enabled: boolean;
  };
  general: {
    default_payment_method: string;
    api_base_url: string;
    payment_timeout: number;
  };
}

const DEFAULT_SETTINGS: PaymentSettings = {
  vietqr: {
    bank_id: 'MBBank',
    account_no: '0335920306',
    account_name: 'NGUYEN VAN QUYEN',
    template: 'print',
    enabled: true
  },
  cpay: {
    timeout: 30,
    enabled: true
  },
  vnpay: {
    enabled: false
  },
  pay_at_hotel: {
    enabled: true
  },
  general: {
    default_payment_method: 'vietqr',
    api_base_url: 'http://localhost:8888/api',
    payment_timeout: 900
  }
};

export const usePaymentSettings = () => {
  const [settings, setSettings] = useState<PaymentSettings>(DEFAULT_SETTINGS);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const fetchSettings = async () => {
    try {
      setLoading(true);
      setError(null);

      const response = await fetch(`${DEFAULT_SETTINGS.general.api_base_url}/payment-settings`, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      });

      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
      }

      const result = await response.json();

      if (result.success && result.data) {
        // Merge with defaults to ensure all required fields exist
        const mergedSettings = {
          vietqr: { ...DEFAULT_SETTINGS.vietqr, ...result.data.vietqr },
          cpay: { ...DEFAULT_SETTINGS.cpay, ...result.data.cpay },
          vnpay: { ...DEFAULT_SETTINGS.vnpay, ...result.data.vnpay },
          pay_at_hotel: { ...DEFAULT_SETTINGS.pay_at_hotel, ...result.data.pay_at_hotel },
          general: { ...DEFAULT_SETTINGS.general, ...result.data.general }
        };

        setSettings(mergedSettings);
        
        // Cache settings in localStorage for offline use
        localStorage.setItem('payment_settings', JSON.stringify(mergedSettings));
        localStorage.setItem('payment_settings_timestamp', Date.now().toString());
      } else {
        throw new Error(result.message || 'Không thể lấy cấu hình thanh toán');
      }
    } catch (err) {
      console.error('Failed to fetch payment settings:', err);
      setError(err instanceof Error ? err.message : 'Lỗi không xác định');
      
      // Try to load from cache if available
      try {
        const cachedSettings = localStorage.getItem('payment_settings');
        const cachedTimestamp = localStorage.getItem('payment_settings_timestamp');
        
        if (cachedSettings && cachedTimestamp) {
          const timestamp = parseInt(cachedTimestamp);
          const now = Date.now();
          const oneHour = 60 * 60 * 1000;
          
          // Use cache if less than 1 hour old
          if (now - timestamp < oneHour) {
            setSettings(JSON.parse(cachedSettings));
            console.log('Using cached payment settings');
          }
        }
      } catch (cacheError) {
        console.error('Failed to load cached settings:', cacheError);
      }
      
      message.warning('Sử dụng cấu hình thanh toán mặc định');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchSettings();
  }, []);

  const refreshSettings = () => {
    fetchSettings();
  };

  // Generate VietQR URL using current settings
  const generateVietQRUrl = (amount: number, content: string) => {
    const { bank_id, account_no, account_name, template } = settings.vietqr;
    const encodedContent = encodeURIComponent(content);
    const encodedAccountName = encodeURIComponent(account_name);

    return `https://img.vietqr.io/image/${bank_id}-${account_no}-${template}.png?amount=${amount}&addInfo=${encodedContent}&accountName=${encodedAccountName}`;
  };

  // Get available payment methods based on settings
  const getAvailablePaymentMethods = () => {
    const methods = [];
    
    if (settings.vietqr.enabled) {
      methods.push({
        key: 'vietqr',
        name: 'VietQR',
        description: 'Thanh toán qua mã QR'
      });
    }
    
    if (settings.vnpay.enabled) {
      methods.push({
        key: 'vnpay',
        name: 'VNPay',
        description: 'Thanh toán qua VNPay'
      });
    }
    
    if (settings.pay_at_hotel.enabled) {
      methods.push({
        key: 'pay_at_hotel',
        name: 'Thanh toán tại khách sạn',
        description: 'Thanh toán khi nhận phòng'
      });
    }
    
    return methods;
  };

  return {
    settings,
    loading,
    error,
    refreshSettings,
    generateVietQRUrl,
    getAvailablePaymentMethods
  };
};