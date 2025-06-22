import React, { useState } from "react";
import { Card, Button, Space, Typography, Tag } from "antd";
import {
    StopOutlined,
    CoffeeOutlined,
    WifiOutlined,
    CarOutlined,
    AimOutlined,
    TeamOutlined,
    RestOutlined,
    HeartOutlined,
    FilterOutlined,
} from "@ant-design/icons";

const { Title, Text } = Typography;

interface FilterOption {
    key: string;
    label: string;
    icon: React.ReactNode;
    description: string;
}

interface RoomQuickFiltersProps {
    onFilterChange?: (selectedFilters: string[]) => void;
}

const RoomQuickFilters: React.FC<RoomQuickFiltersProps> = ({
    onFilterChange,
}) => {
    const [selectedFilters, setSelectedFilters] = useState<string[]>([]);

    const filterOptions: FilterOption[] = [
        {
            key: "non-smoking",
            label: "Không hút thuốc",
            icon: <StopOutlined />,
            description: "Phòng cấm hút thuốc",
        },
        {
            key: "breakfast",
            label: "Bữa sáng",
            icon: <CoffeeOutlined />,
            description: "Bao gồm bữa sáng",
        },
        {
            key: "wifi",
            label: "WiFi miễn phí",
            icon: <WifiOutlined />,
            description: "Internet tốc độ cao",
        },
        {
            key: "parking",
            label: "Bãi đậu xe",
            icon: <CarOutlined />,
            description: "Chỗ đậu xe miễn phí",
        },
        {
            key: "city-view",
            label: "View thành phố",
            icon: <AimOutlined />,
            description: "Tầm nhìn đẹp",
        },
        {
            key: "family-friendly",
            label: "Gia đình",
            icon: <TeamOutlined />,
            description: "Phù hợp gia đình",
        },
        {
            key: "spa-access",
            label: "Spa",
            icon: <RestOutlined />,
            description: "Truy cập spa",
        },
        {
            key: "romantic",
            label: "Lãng mạn",
            icon: <HeartOutlined />,
            description: "Phòng lãng mạn",
        },
    ];

    const handleFilterToggle = (filterKey: string) => {
        const newSelectedFilters = selectedFilters.includes(filterKey)
            ? selectedFilters.filter((key) => key !== filterKey)
            : [...selectedFilters, filterKey];

        setSelectedFilters(newSelectedFilters);
        onFilterChange?.(newSelectedFilters);
    };

    const clearAllFilters = () => {
        setSelectedFilters([]);
        onFilterChange?.([]);
    };

    return (
        <Card className="quick-filters-card">
            <div className="flex items-center justify-between mb-4">
                <Title level={4} className="mb-0 flex items-center gap-2">
                    <FilterOutlined className="text-blue-600" />
                    Bộ lọc nhanh
                </Title>
                {selectedFilters.length > 0 && (
                    <Button
                        type="link"
                        onClick={clearAllFilters}
                        size="small"
                        className="text-red-500"
                    >
                        Xóa tất cả ({selectedFilters.length})
                    </Button>
                )}
            </div>

            {/* Selected Filters Display */}
            {selectedFilters.length > 0 && (
                <div className="mb-4 p-3  rounded-lg border border-blue-100">
                    <Text className="block mb-2 text-blue-700 font-medium text-sm">
                        Đã chọn:
                    </Text>
                    <Space wrap size="small">
                        {selectedFilters.map((filterKey) => {
                            const filter = filterOptions.find((f) => f.key === filterKey);
                            return (
                                <Tag
                                    key={filterKey}
                                    closable
                                    onClose={() => handleFilterToggle(filterKey)}
                                    color="blue"
                                    className="mb-1"
                                >
                                    <span className="mr-1">{filter?.icon}</span>
                                    {filter?.label}
                                </Tag>
                            );
                        })}
                    </Space>
                </div>
            )}

            {/* Filter Buttons Grid */}
            <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-2 xl:grid-cols-3 gap-3">
                {filterOptions.map((filter) => {
                    const isSelected = selectedFilters.includes(filter.key);
                    return (
                        <Button
                            key={filter.key}
                            type={isSelected ? "primary" : "default"}
                            size="large"
                            onClick={() => handleFilterToggle(filter.key)}
                            className={`
                h-auto p-3 text-left flex flex-col items-center justify-center min-h-[70px]
              
                transition-all duration-200 shadow-sm hover:shadow-md
              `}
                        >
                            <div className={`text-lg mb-1 ${isSelected ? "" : ""}`}>
                                {filter.icon}
                            </div>
                            <div
                                className={`text-xs text-center font-medium leading-tight ${isSelected ? "" : ""
                                    }`}
                            >
                                {filter.label}
                            </div>
                        </Button>
                    );
                })}
            </div>

            {/* Filter Summary */}
            {selectedFilters.length > 0 && (
                <div className="mt-4 pt-4 border-t border-gray-100">
                    <Text className=" text-sm">
                        <strong>Mô tả:</strong>
                    </Text>
                    <ul className="mt-2 space-y-1">
                        {selectedFilters.map((filterKey) => {
                            const filter = filterOptions.find((f) => f.key === filterKey);
                            return (
                                <li
                                    key={filterKey}
                                    className="flex items-center text-sm "
                                >
                                    <span className="mr-2 text-blue-500">{filter?.icon}</span>
                                    <span className="font-medium mr-2">{filter?.label}:</span>
                                    <span>{filter?.description}</span>
                                </li>
                            );
                        })}
                    </ul>
                </div>
            )}
        </Card>
    );
};

export default RoomQuickFilters;
