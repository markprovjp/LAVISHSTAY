// src/components/news/NewsCategoryFilter.tsx
import React from 'react';
import { Select, Spin, Space } from 'antd';
import { useNewsCategories } from '../../hooks/useNews';

const { Option } = Select;

interface NewsCategoryFilterProps {
    value?: number;
    onChange: (categoryId: number | undefined) => void;
    placeholder?: string;
    allowClear?: boolean;
    style?: React.CSSProperties;
}

const NewsCategoryFilter: React.FC<NewsCategoryFilterProps> = ({
    value,
    onChange,
    placeholder = "Chọn chuyên mục",
    allowClear = true,
    style,
}) => {
    const { data: categoriesResponse, isLoading, error } = useNewsCategories(true);

    const handleChange = (categoryId: number | undefined) => {
        onChange(categoryId);
    };

    if (error) {
        console.error('Error loading categories:', error);
        return null;
    }

    return (
        <Select
            value={value}
            onChange={handleChange}
            placeholder={placeholder}
            allowClear={allowClear}
            style={{ minWidth: 200, ...style }}
            loading={isLoading}
            notFoundContent={isLoading ? <Spin size="small" /> : 'Không có dữ liệu'}
        >
            {categoriesResponse?.data?.map((category) => (
                <Option key={category.id} value={category.id}>
                    <Space>
                        {category.name}
                        {/* Show news count if available */}
                        {(category as any).news_count !== undefined && (
                            <span className="text-gray-400 text-xs">
                                ({(category as any).news_count})
                            </span>
                        )}
                    </Space>
                </Option>
            ))}
        </Select>
    );
};

export default NewsCategoryFilter;
