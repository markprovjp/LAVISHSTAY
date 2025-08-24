import React from 'react';
import { Card, Row, Col, Select, Input, DatePicker, Space, Button } from 'antd';
import { SearchOutlined, ClearOutlined, FilterOutlined } from '@ant-design/icons';

const { RangePicker } = DatePicker;

interface RoomFiltersProps {
    filters: {
        status?: string;
        roomType?: string;
        floor?: string;
        search?: string;
        dateRange?: [string, string];
    };
    onFiltersChange: (filters: any) => void;
    onClearFilters: () => void;
    roomTypes: string[];
    floors: string[];
}

const RoomFilters: React.FC<RoomFiltersProps> = ({
    filters,
    onFiltersChange,
    onClearFilters,
    roomTypes,
    floors
}) => {
    const handleFilterChange = (key: string, value: any) => {
        onFiltersChange({
            ...filters,
            [key]: value
        });
    };

    return (
        <Card size="small" title={<Space><FilterOutlined /> Bộ lọc phòng</Space>}>
            <Row gutter={16}>
                <Col xs={24} sm={12} md={6}>
                    <Input
                        placeholder="Tìm số phòng, tên khách..."
                        prefix={<SearchOutlined />}
                        value={filters.search}
                        onChange={(e) => handleFilterChange('search', e.target.value)}
                        allowClear
                    />
                </Col>
                <Col xs={24} sm={12} md={4}>
                    <Select
                        placeholder="Trạng thái"
                        value={filters.status}
                        onChange={(value) => handleFilterChange('status', value)}
                        allowClear
                        style={{ width: '100%' }}
                    >
                        <Select.Option value="available">Trống</Select.Option>
                        <Select.Option value="occupied">Có khách</Select.Option>
                        <Select.Option value="maintenance">Bảo trì</Select.Option>
                        <Select.Option value="reserved">Đã đặt</Select.Option>
                    </Select>
                </Col>
                <Col xs={24} sm={12} md={4}>
                    <Select
                        placeholder="Loại phòng"
                        value={filters.roomType}
                        onChange={(value) => handleFilterChange('roomType', value)}
                        allowClear
                        style={{ width: '100%' }}
                    >
                        {roomTypes.map(type => (
                            <Select.Option key={type} value={type}>{type}</Select.Option>
                        ))}
                    </Select>
                </Col>
                <Col xs={24} sm={12} md={3}>
                    <Select
                        placeholder="Tầng"
                        value={filters.floor}
                        onChange={(value) => handleFilterChange('floor', value)}
                        allowClear
                        style={{ width: '100%' }}
                    >
                        {floors.map(floor => (
                            <Select.Option key={floor} value={floor}>Tầng {floor}</Select.Option>
                        ))}
                    </Select>
                </Col>
                <Col xs={24} sm={12} md={5}>
                    <RangePicker
                        placeholder={['Từ ngày', 'Đến ngày']}
                        style={{ width: '100%' }}
                        onChange={(dates, dateStrings) =>
                            handleFilterChange('dateRange', dateStrings)
                        }
                    />
                </Col>
                <Col xs={24} sm={12} md={2}>
                    <Button
                        icon={<ClearOutlined />}
                        onClick={onClearFilters}
                        title="Xóa bộ lọc"
                    >
                        Xóa
                    </Button>
                </Col>
            </Row>
        </Card>
    );
};

export default RoomFilters;
