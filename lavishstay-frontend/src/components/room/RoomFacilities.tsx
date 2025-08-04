// src/components/room/RoomFacilities.tsx
import React, { useState } from 'react';
import { Card, Tag, Tooltip, Collapse, Input, Select, Space, Empty } from 'antd';
import { motion, AnimatePresence } from 'framer-motion';
import {
    Wifi,
    Wind,
    Waves,
    Bed,
    Wine,
    Lock,
    Monitor,
    Coffee,
    Bath,
    Bell,
    DoorOpen,
    Search,
    Filter,
    Star,
    CheckCircle,
    Smartphone,
    Car,
    Utensils,
    Dumbbell,
    Tv,
    AirVent,
    Shield,
    Volume2,
    Lamp,
    Refrigerator
} from 'lucide-react';
import { RoomFacility } from '../../types/roomDetail';

const { Panel } = Collapse;
const { Option } = Select;

interface RoomFacilitiesProps {
    facilities: RoomFacility[];
    className?: string;
}

// Icon mapping
const iconMap: { [key: string]: React.ReactNode } = {
    Wifi: <Wifi size={20} />,
    Wind: <Wind size={20} />,
    Waves: <Waves size={20} />,
    Bed: <Bed size={20} />,
    Wine: <Wine size={20} />,
    Lock: <Lock size={20} />,
    Monitor: <Monitor size={20} />,
    Coffee: <Coffee size={20} />,
    Bath: <Bath size={20} />,
    Bell: <Bell size={20} />,
    DoorOpen: <DoorOpen size={20} />,
    Smartphone: <Smartphone size={20} />,
    Car: <Car size={20} />,
    Utensils: <Utensils size={20} />,
    Dumbbell: <Dumbbell size={20} />,
    Tv: <Tv size={20} />,
    AirVent: <AirVent size={20} />,
    Shield: <Shield size={20} />,
    Volume2: <Volume2 size={20} />,
    Lamp: <Lamp size={20} />,
    Refrigerator: <Refrigerator size={20} />,
};

const categoryNames = {
    basic: 'Tiện ích cơ bản',
    entertainment: 'Giải trí',
    bathroom: 'Phòng tắm',
    kitchen: 'Bếp & Đồ uống',
    technology: 'Công nghệ',
    comfort: 'Tiện nghi',
};

const categoryColors = {
    basic: 'blue',
    entertainment: 'purple',
    bathroom: 'cyan',
    kitchen: 'orange',
    technology: 'green',
    comfort: 'magenta',
};

const RoomFacilities: React.FC<RoomFacilitiesProps> = ({
    facilities,
    className = ''
}) => {
    const [searchTerm, setSearchTerm] = useState('');
    const [selectedCategory, setSelectedCategory] = useState<string>('all');
    const [showHighlightedOnly, setShowHighlightedOnly] = useState(false);

    // Filter facilities
    const filteredFacilities = facilities.filter(facility => {
        const matchesSearch = facility.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (facility.description?.toLowerCase().includes(searchTerm.toLowerCase()) || false);
        const matchesCategory = selectedCategory === 'all' || facility.category === selectedCategory;
        const matchesHighlighted = !showHighlightedOnly || facility.isHighlighted;

        return matchesSearch && matchesCategory && matchesHighlighted;
    });

    // Group facilities by category
    const facilitiesByCategory = filteredFacilities.reduce((acc, facility) => {
        if (!acc[facility.category]) {
            acc[facility.category] = [];
        }
        acc[facility.category].push(facility);
        return acc;
    }, {} as Record<string, RoomFacility[]>);

    // Highlighted facilities
    const highlightedFacilities = facilities.filter(f => f.isHighlighted);

    const FacilityCard: React.FC<{ facility: RoomFacility; index: number }> = ({
        facility,
        index
    }) => (
        <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.3, delay: index * 0.05 }}
            whileHover={{ y: -2 }}
            className="group"
        >
            <Tooltip
                title={facility.description || facility.name}
                placement="top"
            >
                <Card
                    className={`
                        relative h-full transition-all duration-300 cursor-pointer
                        hover:shadow-lg hover:border-blue-300 dark:hover:border-blue-600
                        ${facility.isHighlighted
                            ? 'border-blue-200 bg-blue-50/50 dark:bg-blue-900/20'
                            : 'border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700'
                        }
                    `}
                    bodyStyle={{ padding: '16px' }}
                >
                    {facility.isHighlighted && (
                        <div className="absolute top-2 right-2">
                            <Star size={14} className="text-yellow-500 fill-current" />
                        </div>
                    )}

                    <div className="flex flex-col items-center text-center space-y-3">
                        <div className={`
                            p-3 rounded-xl transition-colors
                            ${facility.isHighlighted
                                ? 'bg-blue-100 dark:bg-blue-800 text-blue-600 dark:text-blue-400'
                                : 'bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-400 group-hover:bg-blue-100 group-hover:text-blue-600'
                            }
                        `}>
                            {iconMap[facility.icon] || <CheckCircle size={20} />}
                        </div>

                        <div>
                            <h4 className="font-medium text-gray-900 dark:text-white text-sm">
                                {facility.name}
                            </h4>
                            {facility.description && (
                                <p className="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                                    {facility.description}
                                </p>
                            )}
                        </div>
                    </div>
                </Card>
            </Tooltip>
        </motion.div>
    );

    return (
        <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.5 }}
            className={`room-facilities ${className}`}
        >
            <Card className="border-0 shadow-lg rounded-2xl overflow-hidden">
                <div className="space-y-6">
                    {/* Header */}
                    <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <h2 className="text-2xl font-bold text-gray-900 dark:text-white">
                                Tiện ích phòng
                            </h2>
                            <p className="text-gray-600 dark:text-gray-400 mt-1">
                                Khám phá các tiện ích đẳng cấp trong phòng
                            </p>
                        </div>

                        <div className="flex items-center gap-3">
                            <Tag
                                color="blue"
                                className="px-3 py-1 text-sm font-medium rounded-full"
                            >
                                {facilities.length} tiện ích
                            </Tag>
                            <Tag
                                color="gold"
                                className="px-3 py-1 text-sm font-medium rounded-full"
                            >
                                {highlightedFacilities.length} nổi bật
                            </Tag>
                        </div>
                    </div>

                    {/* Highlighted Facilities */}
                    {highlightedFacilities.length > 0 && (
                        <div>
                            <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <Star size={18} className="text-yellow-500" />
                                Tiện ích nổi bật
                            </h3>
                            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                                {highlightedFacilities.map((facility, index) => (
                                    <FacilityCard
                                        key={facility.id}
                                        facility={facility}
                                        index={index}
                                    />
                                ))}
                            </div>
                        </div>
                    )}

                    {/* Search and Filter */}
                    <div className="bg-gray-50 dark:bg-gray-800 p-4 rounded-xl">
                        <Space.Compact className="w-full">
                            <Input
                                placeholder="Tìm kiếm tiện ích..."
                                prefix={<Search size={16} />}
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                                className="flex-1"
                            />
                            <Select
                                value={selectedCategory}
                                onChange={setSelectedCategory}
                                className="w-48"
                                placeholder="Lọc theo danh mục"
                                suffixIcon={<Filter size={16} />}
                            >
                                <Option value="all">Tất cả danh mục</Option>
                                {Object.entries(categoryNames).map(([key, name]) => (
                                    <Option key={key} value={key}>{name}</Option>
                                ))}
                            </Select>
                        </Space.Compact>

                        <div className="mt-3 flex items-center gap-2">
                            <Tag
                                color={showHighlightedOnly ? 'blue' : 'default'}
                                className="cursor-pointer transition-colors"
                                onClick={() => setShowHighlightedOnly(!showHighlightedOnly)}
                            >
                                {showHighlightedOnly ? 'Đang hiện nổi bật' : 'Hiện tất cả'}
                            </Tag>
                            {(searchTerm || selectedCategory !== 'all' || showHighlightedOnly) && (
                                <Tag
                                    color="red"
                                    className="cursor-pointer"
                                    onClick={() => {
                                        setSearchTerm('');
                                        setSelectedCategory('all');
                                        setShowHighlightedOnly(false);
                                    }}
                                >
                                    Xóa bộ lọc
                                </Tag>
                            )}
                        </div>
                    </div>

                    {/* All Facilities by Category */}
                    {Object.keys(facilitiesByCategory).length > 0 ? (
                        <Collapse
                            ghost
                            expandIconPosition="end"
                            className="custom-collapse"
                        >
                            {Object.entries(facilitiesByCategory).map(([category, categoryFacilities]) => (
                                <Panel
                                    key={category}
                                    header={
                                        <div className="flex items-center gap-3">
                                            <Tag
                                                color={categoryColors[category as keyof typeof categoryColors]}
                                                className="px-2 py-1 text-xs rounded-full"
                                            >
                                                {categoryNames[category as keyof typeof categoryNames]}
                                            </Tag>
                                            <span className="text-gray-600 dark:text-gray-400">
                                                ({categoryFacilities.length} tiện ích)
                                            </span>
                                        </div>
                                    }
                                >
                                    <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 pt-4">
                                        <AnimatePresence>
                                            {categoryFacilities.map((facility, index) => (
                                                <FacilityCard
                                                    key={facility.id}
                                                    facility={facility}
                                                    index={index}
                                                />
                                            ))}
                                        </AnimatePresence>
                                    </div>
                                </Panel>
                            ))}
                        </Collapse>
                    ) : (
                        <Empty
                            description="Không tìm thấy tiện ích nào"
                            className="py-8"
                        />
                    )}
                </div>
            </Card>

            <style jsx>{`
                .custom-collapse .ant-collapse-header {
                    font-weight: 600 !important;
                }
                
                .line-clamp-2 {
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                }
            `}</style>
        </motion.div>
    );
};

export default RoomFacilities;
