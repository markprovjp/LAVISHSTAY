import React from 'react';
import { FloatButton } from 'antd';
import { CalendarOutlined } from '@ant-design/icons';

interface BookingFloatButtonProps {
    totalItems: number;
    onClick: () => void;
}

const BookingFloatButton: React.FC<BookingFloatButtonProps> = ({
    totalItems,
    onClick
}) => {
    return (
        <FloatButton
            type="primary"
            style={{
                right: 24,
                bottom: 110, // Tránh trùng với BackToTop button
                width: '56px',
                height: '56px',
            }}
            icon={<CalendarOutlined style={{ fontSize: '20px' }} />}
            badge={{
                count: totalItems,
                overflowCount: 99,
                style: {
                    backgroundColor: '#1890ff',
                    color: 'white',
                    fontWeight: 'bold'
                }
            }}
            onClick={onClick} tooltip={
                <div className="text-center">
                    <div className="font-semibold">Lựa chọn phòng</div>
                    <div className="text-xs">{totalItems} phòng đã chọn</div>
                </div>
            }
        />
    );
};

export default BookingFloatButton;
