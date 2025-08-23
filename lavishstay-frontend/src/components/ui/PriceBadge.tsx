import React from 'react';
import { Tag } from 'antd';
import { formatCurrency } from '../../utils/formatUtils';

interface PriceBadgeProps {
    price: number | null;
    currency: string;
    className?: string;
}

const PriceBadge: React.FC<PriceBadgeProps> = ({
    price,
    currency,
    className = ''
}) => {
    if (price === null || price === undefined) {
        return (
            <Tag
                color="default"
                className={`text-sm font-medium px-3 py-1 ${className}`}
            >
                Liên hệ
            </Tag>
        );
    }

    return (
        <div className={`text-lg font-bold text-primary ${className}`}>
            {formatCurrency(price, currency)}
            <span className="text-sm font-normal text-gray-500 ml-1">
                / đêm
            </span>
        </div>
    );
};

export default React.memo(PriceBadge);
