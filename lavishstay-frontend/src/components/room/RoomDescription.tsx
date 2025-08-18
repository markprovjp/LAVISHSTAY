// src/components/room/RoomDescription.tsx
import React, { useState } from 'react';
import { Card, Button, Divider, Tag, Space } from 'antd';
import { motion, AnimatePresence } from 'framer-motion';
import {
    ChevronDown,
    ChevronUp,
    FileText,
    Info,
    Award,
    Clock,
    Users,
    Star
} from 'lucide-react';
import { RoomTypeDetail } from '../../types/roomDetail';

interface RoomDescriptionProps {
    room: RoomTypeDetail;
    className?: string;
}

const RoomDescription: React.FC<RoomDescriptionProps> = ({
    room,
    className = ''
}) => {
    const [isExpanded, setIsExpanded] = useState(false);
    const [activeSection, setActiveSection] = useState<'description' | 'specifications'>('description');

    const toggleExpanded = () => {
        setIsExpanded(!isExpanded);
    };

    const formatDescription = (text: string) => {
        return text.split('\n').map((paragraph, index) => (
            <p key={index} className="mb-4 last:mb-0 leading-relaxed">
                {paragraph.trim()}
            </p>
        ));
    };

    const groupedSpecs = room.specifications.reduce((acc, spec) => {
        if (!acc[spec.category]) {
            acc[spec.category] = [];
        }
        acc[spec.category].push(spec);
        return acc;
    }, {} as Record<string, typeof room.specifications>);

    const specCategoryNames = {
        room: 'Thông tin phòng',
        bed: 'Giường ngủ',
        bathroom: 'Phòng tắm',
        technology: 'Công nghệ',
        other: 'Khác',
    };

    const specCategoryIcons = {
        room: <Info size={16} />,
        bed: <Award size={16} />,
        bathroom: <Clock size={16} />,
        technology: <Star size={16} />,
        other: <FileText size={16} />,
    };

    return (
        <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5 }}
            className={`room-description ${className}`}
        >
            <Card className="border-0 shadow-lg rounded-2xl overflow-hidden">
                <div className="space-y-6">
                    {/* Header with Navigation */}
                    <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 className="text-2xl font-bold text-gray-900 dark:text-white">
                                Chi tiết phòng
                            </h2>
                            <p className="text-gray-600 dark:text-gray-400 mt-1">
                                Tìm hiểu thêm về phòng này
                            </p>
                        </div>

                        <div className="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                            <button
                                onClick={() => setActiveSection('description')}
                                className={`
                                    px-4 py-2 rounded-md text-sm font-medium transition-all duration-200
                                    ${activeSection === 'description'
                                        ? 'bg-white dark:bg-gray-600 text-blue-600 dark:text-blue-400 shadow-sm'
                                        : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'
                                    }
                                `}
                            >
                                <FileText size={16} className="inline mr-2" />
                                Mô tả
                            </button>
                            <button
                                onClick={() => setActiveSection('specifications')}
                                className={`
                                    px-4 py-2 rounded-md text-sm font-medium transition-all duration-200
                                    ${activeSection === 'specifications'
                                        ? 'bg-white dark:bg-gray-600 text-blue-600 dark:text-blue-400 shadow-sm'
                                        : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'
                                    }
                                `}
                            >
                                <Info size={16} className="inline mr-2" />
                                Thông số
                            </button>
                        </div>
                    </div>

                    <AnimatePresence mode="wait">
                        {activeSection === 'description' ? (
                            <motion.div
                                key="description"
                                initial={{ opacity: 0, x: -20 }}
                                animate={{ opacity: 1, x: 0 }}
                                exit={{ opacity: 0, x: 20 }}
                                transition={{ duration: 0.3 }}
                            >
                                {/* Quick Description */}
                                <div className="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl mb-6">
                                    <p className="text-gray-700 dark:text-gray-300 font-medium">
                                        {room.description}
                                    </p>
                                </div>

                                {/* Full Description */}
                                <div className="text-gray-700 dark:text-gray-300">
                                    <div className="relative">
                                        <div
                                            className={`
                                                transition-all duration-500 overflow-hidden
                                                ${isExpanded ? 'max-h-none' : 'max-h-48'}
                                            `}
                                        >
                                            {formatDescription(room.fullDescription)}
                                        </div>

                                        {!isExpanded && (
                                            <div className="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-white dark:from-gray-800 to-transparent" />
                                        )}
                                    </div>

                                    <div className="mt-4 text-center">
                                        <Button
                                            type="link"
                                            onClick={toggleExpanded}
                                            className="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium"
                                            icon={isExpanded ? <ChevronUp size={16} /> : <ChevronDown size={16} />}
                                        >
                                            {isExpanded ? 'Thu gọn' : 'Xem thêm'}
                                        </Button>
                                    </div>
                                </div>

                                {/* Key Features */}
                                <div className="mt-8 p-4 bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 rounded-xl">
                                    <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        <Award size={18} className="text-green-600 dark:text-green-400" />
                                        Điểm nổi bật
                                    </h3>
                                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div className="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                            <div className="w-2 h-2 bg-green-500 rounded-full"></div>
                                            Tầm nhìn tuyệt đẹp ra đại dương
                                        </div>
                                        <div className="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                            <div className="w-2 h-2 bg-green-500 rounded-full"></div>
                                            Thiết kế hiện đại và sang trọng
                                        </div>
                                        <div className="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                            <div className="w-2 h-2 bg-green-500 rounded-full"></div>
                                            Ban công riêng với ghế nghỉ
                                        </div>
                                        <div className="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                            <div className="w-2 h-2 bg-green-500 rounded-full"></div>
                                            Phòng tắm đá cẩm thạch
                                        </div>
                                    </div>
                                </div>
                            </motion.div>
                        ) : (
                            <motion.div
                                key="specifications"
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                exit={{ opacity: 0, x: -20 }}
                                transition={{ duration: 0.3 }}
                            >
                                <div className="space-y-6">
                                    {Object.entries(groupedSpecs).map(([category, specs]) => (
                                        <div key={category}>
                                            <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                                {specCategoryIcons[category as keyof typeof specCategoryIcons]}
                                                {specCategoryNames[category as keyof typeof specCategoryNames]}
                                            </h3>

                                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                {specs.map((spec, index) => (
                                                    <motion.div
                                                        key={spec.id}
                                                        initial={{ opacity: 0, y: 10 }}
                                                        animate={{ opacity: 1, y: 0 }}
                                                        transition={{ duration: 0.3, delay: index * 0.1 }}
                                                        className="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                                                    >
                                                        <div className="flex items-center gap-3">
                                                            {spec.icon && (
                                                                <div className="text-blue-600 dark:text-blue-400">
                                                                    {/* Icon would be rendered here if available */}
                                                                </div>
                                                            )}
                                                            <span className="font-medium text-gray-700 dark:text-gray-300">
                                                                {spec.label}
                                                            </span>
                                                        </div>
                                                        <span className="text-gray-900 dark:text-white font-semibold">
                                                            {spec.value}
                                                        </span>
                                                    </motion.div>
                                                ))}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </motion.div>
                        )}
                    </AnimatePresence>

                    <Divider className="my-6" />

                    {/* Additional Info */}
                    <div className="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-xl">
                        <div className="flex items-start gap-3">
                            <div className="p-2 bg-amber-100 dark:bg-amber-800 rounded-lg">
                                <Info size={18} className="text-amber-600 dark:text-amber-400" />
                            </div>
                            <div>
                                <h4 className="font-semibold text-amber-800 dark:text-amber-200 mb-2">
                                    Lưu ý quan trọng
                                </h4>
                                <ul className="text-sm text-amber-700 dark:text-amber-300 space-y-1">
                                    <li>• Giá phòng có thể thay đổi theo mùa và sự kiện đặc biệt</li>
                                    <li>• Vui lòng kiểm tra chính sách hủy phòng trước khi đặt</li>
                                    <li>• Một số tiện ích có thể tính phí bổ sung</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </Card>
        </motion.div>
    );
};

export default RoomDescription;
