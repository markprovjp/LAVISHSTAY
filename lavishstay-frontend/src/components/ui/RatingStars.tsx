import React from 'react';
import { Rate } from 'antd';

interface RatingStarsProps {
    rating: number;
    reviewCount: number;
    showCount?: boolean;
    size?: 'small' | 'default' | 'large';
    className?: string;
}

const RatingStars: React.FC<RatingStarsProps> = ({
    rating,
    reviewCount,
    showCount = true,
    size = 'default',
    className = ''
}) => {
    const starSize = size === 'small' ? 12 : size === 'large' ? 20 : 16;

    return (
        <div className={`flex items-center gap-2 ${className}`}>
            <Rate
                disabled
                value={rating}
                allowHalf
                style={{ fontSize: starSize }}
            />
            {showCount && (
                <span className="text-sm text-gray-600">
                    {rating.toFixed(1)} ({reviewCount} đánh giá)
                </span>
            )}
        </div>
    );
};

export default React.memo(RatingStars);
