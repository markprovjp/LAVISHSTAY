// Rate limit notification component
import React, { useEffect, useState } from 'react';
import { notification, Button, Progress } from 'antd';
import { ClockCircleOutlined, ExclamationCircleOutlined } from '@ant-design/icons';

export const showRateLimitNotification = (
    remainingTime: number,
    onResetRequested?: () => void
) => {
    const remainingMinutes = Math.ceil(remainingTime / (60 * 1000));

    notification.warning({
        message: 'Đã đạt giới hạn đặt phòng',
        description: `Bạn đã thực hiện quá nhiều lần đặt phòng. Vui lòng thử lại sau ${remainingMinutes} phút.`,
        icon: <ExclamationCircleOutlined style={{ color: '#faad14' }} />,
        duration: 0, // Don't auto close
        key: 'rate-limit-warning',
        btn: onResetRequested ? (
            <Button
                size="small"
                onClick={() => {
                    onResetRequested();
                    notification.destroy('rate-limit-warning');
                }}
            >
                Xóa giới hạn (Dev)
            </Button>
        ) : undefined,
        placement: 'topRight'
    });
};

export const showBookingReuseNotification = (bookingCode: string) => {
    notification.info({
        message: 'Sử dụng lại đặt phòng',
        description: `Mã đặt phòng ${bookingCode} được tái sử dụng vì thông tin phòng và ngày không thay đổi.`,
        icon: <ClockCircleOutlined style={{ color: '#1890ff' }} />,
        duration: 4,
        key: 'booking-reuse',
        placement: 'topRight'
    });
};

export const showSpamAttemptWarning = (attemptsLeft: number) => {
    notification.warning({
        message: 'Cảnh báo spam',
        description: `Bạn còn ${attemptsLeft} lần thử. Sau đó sẽ bị khóa 15 phút.`,
        icon: <ExclamationCircleOutlined style={{ color: '#faad14' }} />,
        duration: 3,
        key: 'spam-warning',
        placement: 'topRight'
    });
};

// Real-time countdown component
const CountdownNotification: React.FC<{
    remainingTime: number;
    onComplete: () => void;
}> = ({ remainingTime, onComplete }) => {
    const [timeLeft, setTimeLeft] = useState(remainingTime);

    useEffect(() => {
        const timer = setInterval(() => {
            setTimeLeft(prev => {
                if (prev <= 1000) {
                    clearInterval(timer);
                    onComplete();
                    return 0;
                }
                return prev - 1000;
            });
        }, 1000);

        return () => clearInterval(timer);
    }, [onComplete]);

    const minutes = Math.floor(timeLeft / (60 * 1000));
    const seconds = Math.floor((timeLeft % (60 * 1000)) / 1000);
    const progressPercent = ((remainingTime - timeLeft) / remainingTime) * 100;

    return (
        <div>
            <div style={{ marginBottom: 8 }}>
                Thời gian còn lại: <strong>{minutes}:{seconds.toString().padStart(2, '0')}</strong>
            </div>
            <Progress percent={progressPercent} size="small" status="active" />
        </div>
    );
};

export const showCountdownNotification = (
    remainingTime: number,
    onComplete: () => void,
    onResetRequested?: () => void
) => {
    notification.open({
        message: 'Đang chờ hết thời gian khóa',
        description: (
            <CountdownNotification
                remainingTime={remainingTime}
                onComplete={onComplete}
            />
        ),
        icon: <ClockCircleOutlined style={{ color: '#1890ff' }} />,
        duration: 0,
        key: 'cooldown-countdown',
        btn: onResetRequested ? (
            <Button
                size="small"
                onClick={() => {
                    onResetRequested();
                    notification.destroy('cooldown-countdown');
                }}
            >
                Bỏ qua (Dev)
            </Button>
        ) : undefined,
        placement: 'topRight'
    });
};
