// src/components/room/RoomRatingStats.tsx
import React from 'react';
import { Card, Progress, Rate, Tag, Divider } from 'antd';
import { motion } from 'framer-motion';
import { Bar } from '@ant-design/plots';
import {
    Star,
    TrendingUp,
    Award,
    Users,
    CheckCircle
} from 'lucide-react';
import { RoomRatingStats } from '../../types/roomDetail';

interface RoomRatingStatsProps {
    stats: RoomRatingStats;
    className?: string;
}

const RoomRatingStatsComponent: React.FC<RoomRatingStatsProps> = ({
    stats,
    className = ''
}) => {
    const categoryLabels = {
        overall: 'Tổng thể',
        cleanliness: 'Vệ sinh',
        comfort: 'Tiện nghi',
        location: 'Vị trí',
        facilities: 'Tiện ích',
        staff: 'Dịch vụ',
        valueForMoney: 'Giá trị'
    };

    const categoryIcons = {
        overall: <Award size={16} />,
        cleanliness: <CheckCircle size={16} />,
        comfort: <Star size={16} />,
        location: <Users size={16} />,
        facilities: <Star size={16} />,
        staff: <Users size={16} />,
        valueForMoney: <TrendingUp size={16} />
    };

    // Prepare data for distribution chart
    const distributionData = Object.entries(stats.ratingDistribution)
        .map(([rating, count]) => ({
            rating: `${rating} sao`,
            count,
            percentage: stats.totalReviews > 0 ? (count / stats.totalReviews) * 100 : 0
        }))
        .reverse(); // Show 5 stars first

    const barConfig = {
        data: distributionData,
        xField: 'count',
        yField: 'rating',
        seriesField: 'rating',
        color: ({ rating }: { rating: string }) => {
            const starCount = parseInt(rating.split(' ')[0]);
            const colors = ['#ff4757', '#ff6b7a', '#ffa502', '#2ed573', '#20bf6b'];
            return colors[starCount - 1];
        },
        label: {
            position: 'right',
            formatter: ({ count, percentage }: { count: number; percentage: number }) =>
                `${count} (${percentage.toFixed(1)}%)`
        },
        meta: {
            count: { alias: 'Số lượng' },
            rating: { alias: 'Đánh giá' }
        },
        height: 200,
    };

    const getScoreColor = (score: number) => {
        if (score >= 4.5) return 'text-green-600 dark:text-green-400';
        if (score >= 4.0) return 'text-lime-600 dark:text-lime-400';
        if (score >= 3.5) return 'text-yellow-600 dark:text-yellow-400';
        if (score >= 3.0) return 'text-orange-600 dark:text-orange-400';
        return 'text-red-600 dark:text-red-400';
    };

    const getProgressColor = (score: number) => {
        if (score >= 4.5) return '#10b981';
        if (score >= 4.0) return '#84cc16';
        if (score >= 3.5) return '#eab308';
        if (score >= 3.0) return '#f97316';
        return '#ef4444';
    };

    const CategoryRating: React.FC<{
        category: keyof typeof categoryLabels;
        value: number;
        index: number
    }> = ({ category, value, index }) => (
        <motion.div
            initial={{ opacity: 0, x: -20 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.3, delay: index * 0.1 }}
            className="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"
        >
            <div className="flex items-center gap-3">
                <div className="text-blue-600 dark:text-blue-400">
                    {categoryIcons[category]}
                </div>
                <span className="font-medium text-gray-900 dark:text-white">
                    {categoryLabels[category]}
                </span>
            </div>
            <div className="flex items-center gap-3">
                <Progress
                    percent={(value / 5) * 100}
                    showInfo={false}
                    strokeColor={getProgressColor(value)}
                    trailColor="rgba(0,0,0,0.1)"
                    strokeWidth={6}
                    className="w-24"
                />
                <span className={`font-bold text-lg ${getScoreColor(value)}`}>
                    {value.toFixed(1)}
                </span>
            </div>
        </motion.div>
    );

    return (
        <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.5 }}
            className={`room-rating-stats ${className}`}
        >
            <Card className="border-0 shadow-lg rounded-2xl overflow-hidden">
                <div className="space-y-6">
                    {/* Header */}
                    <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <h2 className="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <Star size={24} className="text-yellow-500" />
                                Thống kê đánh giá
                            </h2>
                            <p className="text-gray-600 dark:text-gray-400 mt-1">
                                Dựa trên {stats.totalReviews} đánh giá từ khách hàng
                            </p>
                        </div>

                        <div className="flex items-center gap-4">
                            <div className="text-center">
                                <div className="flex items-center gap-2 mb-1">
                                    <Rate disabled value={stats.overall} allowHalf className="text-2xl" />
                                </div>
                                <div className="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                    {stats.overall.toFixed(1)}
                                </div>
                                <div className="text-sm text-gray-500 dark:text-gray-400">
                                    Điểm trung bình
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Overall Stats */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div className="text-center p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
                            <div className="text-2xl font-bold text-green-600 dark:text-green-400 mb-1">
                                {Math.round((stats.ratingDistribution[5] || 0) / stats.totalReviews * 100)}%
                            </div>
                            <div className="text-sm text-green-700 dark:text-green-300">
                                Đánh giá 5 sao
                            </div>
                        </div>

                        <div className="text-center p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                            <div className="text-2xl font-bold text-blue-600 dark:text-blue-400 mb-1">
                                {Math.round(((stats.ratingDistribution[5] || 0) + (stats.ratingDistribution[4] || 0)) / stats.totalReviews * 100)}%
                            </div>
                            <div className="text-sm text-blue-700 dark:text-blue-300">
                                Hài lòng (4-5 sao)
                            </div>
                        </div>

                        <div className="text-center p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-xl">
                            <div className="text-2xl font-bold text-purple-600 dark:text-purple-400 mb-1">
                                {stats.totalReviews}
                            </div>
                            <div className="text-sm text-purple-700 dark:text-purple-300">
                                Tổng đánh giá
                            </div>
                        </div>
                    </div>

                    {/* Category Ratings */}
                    <div>
                        <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            Đánh giá theo tiêu chí
                        </h3>
                        <div className="space-y-3">
                            {(Object.keys(categoryLabels) as Array<keyof typeof categoryLabels>)
                                .filter(key => key !== 'overall')
                                .map((category, index) => (
                                    <CategoryRating
                                        key={category}
                                        category={category}
                                        value={stats[category]}
                                        index={index}
                                    />
                                ))
                            }
                        </div>
                    </div>

                    <Divider />

                    {/* Rating Distribution */}
                    <div>
                        <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            Phân bố đánh giá 
                        </h3>

                        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            {/* Bar Chart */}
                            <div className="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl">
                                <Bar {...barConfig} />
                            </div>

                            {/* Detailed Breakdown */}
                            <div className="space-y-3">
                                {distributionData.map(({ rating, count, percentage }, index) => (
                                    <motion.div
                                        key={rating}
                                        initial={{ opacity: 0, x: 20 }}
                                        animate={{ opacity: 1, x: 0 }}
                                        transition={{ duration: 0.3, delay: index * 0.1 }}
                                        className="flex items-center gap-3"
                                    >
                                        <div className="flex items-center gap-1 w-16">
                                            <span className="text-sm font-medium">
                                                {rating.split(' ')[0]}
                                            </span>
                                            <Star size={14} className="text-yellow-500" />
                                        </div>
                                        <div className="flex-1">
                                            <div className="flex justify-between text-sm mb-1">
                                                <span className="text-gray-600 dark:text-gray-400">
                                                    {count} đánh giá
                                                </span>
                                                <span className="font-medium text-gray-900 dark:text-white">
                                                    {percentage.toFixed(1)}%
                                                </span>
                                            </div>
                                            <Progress
                                                percent={percentage}
                                                showInfo={false}
                                                strokeColor={getProgressColor(parseInt(rating.split(' ')[0]))}
                                                trailColor="rgba(0,0,0,0.1)"
                                                strokeWidth={6}
                                            />
                                        </div>
                                    </motion.div>
                                ))}
                            </div>
                        </div>
                    </div>

                    {/* Summary Tags */}
                    <div className="flex flex-wrap gap-2 justify-center pt-4 border-t border-gray-100 dark:border-gray-700">
                        {stats.overall >= 4.5 && (
                            <Tag color="green" icon={<Award size={14} />} className="px-3 py-1">
                                Xuất sắc
                            </Tag>
                        )}
                        {stats.cleanliness >= 4.8 && (
                            <Tag color="blue" icon={<CheckCircle size={14} />} className="px-3 py-1">
                                Rất sạch sẽ
                            </Tag>
                        )}
                        {stats.staff >= 4.8 && (
                            <Tag color="purple" icon={<Users size={14} />} className="px-3 py-1">
                                Dịch vụ tuyệt vời
                            </Tag>
                        )}
                        {stats.valueForMoney >= 4.3 && (
                            <Tag color="orange" icon={<TrendingUp size={14} />} className="px-3 py-1">
                                Giá trị tốt
                            </Tag>
                        )}
                    </div>
                </div>
            </Card>
        </motion.div>
    );
};

export default RoomRatingStatsComponent;
