// src/components/payment/CouponInput.tsx
import React, { useState, useCallback, useEffect } from 'react';
import {
    Input,
    Button,
    Tag,
    Typography,
    Space,
    Divider,
    Alert,
    Row,
    Col
} from 'antd';
import {
    GiftOutlined,
    CheckCircleOutlined,
    CloseOutlined,
    LoadingOutlined
} from '@ant-design/icons';
import {
    couponService,
    getCouponErrorMessage,
    AppliedCoupon,
    CouponValidateRequest
} from '../../services/couponService';

const { Text } = Typography;

// Simple debounce utility
const useDebounce = (callback: (...args: any[]) => void, delay: number) => {
    const [debounceTimer, setDebounceTimer] = useState<NodeJS.Timeout | null>(null);

    const debouncedCallback = useCallback((...args: any[]) => {
        if (debounceTimer) {
            clearTimeout(debounceTimer);
        }
        const newTimer = setTimeout(() => callback(...args), delay);
        setDebounceTimer(newTimer);
    }, [callback, delay, debounceTimer]);

    const flush = useCallback(() => {
        if (debounceTimer) {
            clearTimeout(debounceTimer);
            setDebounceTimer(null);
        }
    }, [debounceTimer]);

    const cancel = useCallback(() => {
        if (debounceTimer) {
            clearTimeout(debounceTimer);
            setDebounceTimer(null);
        }
    }, [debounceTimer]);

    return { debouncedCallback, flush, cancel };
};

interface CouponInputProps {
    // Booking preview data for validation
    bookingPreview: {
        base_price_vnd: number;
        taxes_vnd?: number;
        fees_vnd?: number;
        room_type_id: number;
    };
    // Current applied coupon
    appliedCoupon?: AppliedCoupon | null;
    // Callback when coupon is applied/removed
    onCouponChange: (coupon: AppliedCoupon | null) => void;
    // Format VND function
    formatVND: (amount: number) => string;
    // Disabled state
    disabled?: boolean;
}

const CouponInput: React.FC<CouponInputProps> = ({
    bookingPreview,
    appliedCoupon,
    onCouponChange,
    formatVND,
    disabled = false
}) => {
    const [inputValue, setInputValue] = useState('');
    const [isChecking, setIsChecking] = useState(false);
    const [isValidating, setIsValidating] = useState(false);
    const [errorMessage, setErrorMessage] = useState('');
    const [successMessage, setSuccessMessage] = useState('');
    // Auto-apply is always true in current UX. Use constant to avoid unused setter lint.
    const shouldApplyToBooking = true;

    // Validation function
    const validateCoupon = async (code: string) => {
        if (!code.trim()) {
            setErrorMessage('');
            setSuccessMessage('');
            return;
        }

        setIsValidating(true);
        setErrorMessage('');
        setSuccessMessage('');

        try {
            // First check if code exists
            const checkResult = await couponService.checkCode(code);

            if (!checkResult.exists) {
                setErrorMessage(checkResult.message || 'Mã giảm giá không tồn tại');
                return;
            }

            // If exists, validate against booking
            const validateRequest: CouponValidateRequest = {
                code: code.trim(),
                booking_preview: bookingPreview
            };

            // Debug: log the exact payload sent to backend (helps diagnose 422)
            // eslint-disable-next-line no-console
            console.debug('Coupon validate request payload:', validateRequest);

            const validateResult = await couponService.validateCoupon(validateRequest);

            if (validateResult.valid && validateResult.coupon && validateResult.discount_vnd !== undefined) {
                const newAppliedCoupon: AppliedCoupon = {
                    code: validateResult.coupon.code,
                    type: validateResult.coupon.type,
                    value: validateResult.coupon.value,
                    description: validateResult.coupon.description,
                    discount_vnd: validateResult.discount_vnd,
                    new_total_vnd: validateResult.new_total_vnd || bookingPreview.base_price_vnd
                };

                setSuccessMessage('Mã giảm giá hợp lệ!');

                // Auto-apply if checkbox is checked
                if (shouldApplyToBooking) {
                    onCouponChange(newAppliedCoupon);
                }
            } else {
                setErrorMessage(getCouponErrorMessage(validateResult.reason));
            }
        } catch (error: any) {
            // eslint-disable-next-line no-console
            console.error('Coupon validation error:', error);

            // Laravel returns 422 with validation errors in `errors` or a `message` string.
            if (error.response?.status === 422) {
                const data = error.response.data || {};
                // Prefer first field validation message
                if (data.errors && typeof data.errors === 'object') {
                    const firstKey = Object.keys(data.errors)[0];
                    const firstMsg = Array.isArray(data.errors[firstKey]) ? data.errors[firstKey][0] : null;
                    setErrorMessage(firstMsg || data.message || getCouponErrorMessage('min_amount'));
                } else if (data.message) {
                    setErrorMessage(data.message);
                } else {
                    setErrorMessage(getCouponErrorMessage('network_error'));
                }
            } else if (error.response?.status === 429) {
                setErrorMessage(getCouponErrorMessage('rate_limit'));
            } else if (error.response?.status >= 500) {
                setErrorMessage(getCouponErrorMessage('server_error'));
            } else {
                setErrorMessage(getCouponErrorMessage('network_error'));
            }
        } finally {
            setIsValidating(false);
        }
    };

    // Debounced validation using useDebounce hook
    const { debouncedCallback: debouncedValidate, flush, cancel } = useDebounce(validateCoupon, 300);

    // Handle input change
    const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const value = e.target.value.toUpperCase();
        setInputValue(value);

        if (value.trim()) {
            debouncedValidate(value);
        } else {
            setErrorMessage('');
            setSuccessMessage('');
            onCouponChange(null);
        }
    };

    // Handle manual check button
    const handleCheck = async () => {
        if (!inputValue.trim()) return;

        setIsChecking(true);
        try {
            flush(); // Clear any pending debounced calls
            await validateCoupon(inputValue); // Execute immediately
        } finally {
            setIsChecking(false);
        }
    };

    // Handle apply/remove coupon
    const handleApplyCoupon = () => {
        if (appliedCoupon) {
            // Remove coupon
            onCouponChange(null);
            setInputValue('');
            setErrorMessage('');
            setSuccessMessage('');
        } else if (successMessage && inputValue.trim()) {
            // Apply coupon (re-validate to be safe)
            handleCheck();
        }
    };

    // Handle remove applied coupon
    const handleRemoveCoupon = () => {
        onCouponChange(null);
        setInputValue('');
        setErrorMessage('');
        setSuccessMessage('');
    };

    // Cleanup debounce on unmount
    useEffect(() => {
        return () => {
            cancel();
        };
    }, [cancel]);

    // Set input value when applied coupon changes externally
    useEffect(() => {
        if (appliedCoupon && appliedCoupon.code !== inputValue) {
            setInputValue(appliedCoupon.code);
            setSuccessMessage('Mã giảm giá đã được áp dụng');
            setErrorMessage('');
        } else if (!appliedCoupon && inputValue && successMessage) {
            // Coupon was removed externally
            setSuccessMessage('');
        }
    }, [appliedCoupon, inputValue, successMessage]);

    const isLoading = isChecking || isValidating;

    return (
        <div style={{ marginBottom: 16 }}>
            <Divider orientation="left">
                <Space>
                    <GiftOutlined />
                    <Text strong>Mã giảm giá</Text>
                </Space>
            </Divider>

            {/* Applied Coupon Display (cleaner card with left accent) */}
            {appliedCoupon && (
                <div
                    style={{
                        display: 'flex',
                        width: '100%',
                        background: '#fff',
                        borderRadius: 8,
                        border: '1px solid rgba(0,0,0,0.06)',
                        boxShadow: '0 1px 2px rgba(16,24,40,0.03)',
                        marginBottom: 16,
                        overflow: 'hidden'
                    }}
                >
                    {/* accent */}
                    <div style={{ width: 6, background: '#52c41a' }} />

                    <div style={{ padding: 12, flex: 1 }}>
                        <Row justify="space-between" align="middle">
                            <Col>
                                <Space>
                                    <Tag color="success" icon={<CheckCircleOutlined />} style={{ padding: '0 8px', height: 28 }}>
                                        {appliedCoupon.code}
                                    </Tag>
                                    <Text style={{ color: '#237804', fontWeight: 700 }}>Đã áp dụng</Text>
                                </Space>
                            </Col>
                            <Col>
                                <Button
                                    type="text"
                                    size="small"
                                    icon={<CloseOutlined />}
                                    onClick={handleRemoveCoupon}
                                    disabled={disabled}
                                >
                                    Gỡ mã
                                </Button>
                            </Col>
                        </Row>

                        {appliedCoupon.description && (
                            <div style={{ marginTop: 8 }}>
                                <Text type="secondary">{appliedCoupon.description}</Text>
                            </div>
                        )}

                        <div style={{ marginTop: 12 }}>
                            <Row justify="space-between" align="middle">
                                <Col>
                                    <Text>Giảm giá</Text>
                                </Col>
                                <Col>
                                    <Text strong style={{ color: '#389e0d' }}>-{formatVND(appliedCoupon.discount_vnd)}</Text>
                                </Col>
                            </Row>

                            <Row justify="space-between" align="middle" style={{ marginTop: 6 }}>
                                <Col>
                                    <Text strong>Tổng mới</Text>
                                </Col>
                                <Col>
                                    <Text strong style={{ fontSize: 16, color: '#096dd9' }}>{formatVND(appliedCoupon.new_total_vnd)}</Text>
                                </Col>
                            </Row>
                        </div>
                    </div>
                </div>
            )}

            {/* Coupon Input */}
            {!appliedCoupon && (
                <Space.Compact style={{ width: '100%' }}>
                    <Input
                        size="large"
                        placeholder="Nhập mã giảm giá"
                        value={inputValue}
                        onChange={handleInputChange}
                        disabled={disabled}
                        prefix={<GiftOutlined style={{ color: '#bfbfbf' }} />}
                        suffix={isLoading ? <LoadingOutlined /> : null}
                        onPressEnter={handleCheck}
                        style={{ textTransform: 'uppercase' }}
                    />
                    <Button
                        type="primary"
                        size="large"
                        onClick={handleCheck}
                        loading={isLoading}
                        disabled={disabled || !inputValue.trim()}
                    >
                        Kiểm tra
                    </Button>
                </Space.Compact>
            )}



            {/* Error Message */}
            {errorMessage && (
                <Alert
                    message={errorMessage}
                    type="error"
                    showIcon
                    style={{ marginTop: 8 }}
                    role="alert"
                    aria-live="polite"
                />
            )}

            {/* Success Message */}
            {successMessage && !appliedCoupon && (
                <Alert
                    message={successMessage}
                    type="success"
                    showIcon
                    style={{ marginTop: 8 }}
                    action={
                        <Button
                            type="link"
                            size="small"
                            onClick={handleApplyCoupon}
                            disabled={disabled}
                        >
                            Áp dụng
                        </Button>
                    }
                    role="alert"
                    aria-live="polite"
                />
            )}
        </div>
    );
};

export default CouponInput;
