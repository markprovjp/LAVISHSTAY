import React from 'react';
import { Form, Input, Select, DatePicker, Button, Space, Card, Row, Col, Switch } from 'antd';
import { SearchOutlined, ReloadOutlined, FilterOutlined } from '@ant-design/icons';
import { useRoomManagementStore } from '../../stores/roomManagementStore';
import { statusOptions } from '../../constants/roomStatus';
import { RoomFilters } from '../../types/room';
import dayjs from 'dayjs';

const { RangePicker } = DatePicker;
const { Option } = Select;

interface FilterBarProps {
    roomTypes: any[];
    onSearch: (filters: RoomFilters) => void;
    loading?: boolean;
}

const FilterBar: React.FC<FilterBarProps> = ({ roomTypes, onSearch, loading = false }) => {
    const { viewMode, filters, setViewMode, setFilters, resetFilters } = useRoomManagementStore();
    const [form] = Form.useForm();

    const handleSearch = () => {
        const values = form.getFieldsValue();
        const searchFilters: RoomFilters = {
            customerName: values.customerName?.trim() || undefined,
            dateRange: values.dateRange
                ? [values.dateRange[0]?.format('YYYY-MM-DD'), values.dateRange[1]?.format('YYYY-MM-DD')]
                : undefined,
            roomNumber: values.roomNumber?.trim() || undefined,
            roomType: values.roomType || undefined,
            roomStatus: values.roomStatus || undefined,
        };
        setFilters(searchFilters);
        onSearch(searchFilters);
    };

    const handleReset = () => {
        form.resetFields();
        resetFilters();
        onSearch({});
    };

    const handleViewModeChange = (checked: boolean) => {
        setViewMode(checked ? 'timeline' : 'grid');
    };

    return (
        <Card className="mb-6 shadow-sm">
            <Form
                form={form}
                layout="vertical"
                onFinish={handleSearch}
                initialValues={{
                    customerName: filters.customerName || '',
                    dateRange: filters.dateRange
                        ? [dayjs(filters.dateRange[0]), dayjs(filters.dateRange[1])]
                        : undefined,
                    roomNumber: filters.roomNumber || '',
                    roomType: filters.roomType || undefined,
                    roomStatus: filters.roomStatus || undefined,
                }}
            >
                <Row gutter={16}>
                    <Col xs={24} sm={12} md={6} lg={4}>
                        <Form.Item label="Tên khách hàng" name="customerName">
                            <Input
                                placeholder="Nhập tên khách hàng"
                                allowClear
                            />
                        </Form.Item>
                    </Col>

                    <Col xs={24} sm={12} md={6} lg={5}>
                        <Form.Item label="Thời gian" name="dateRange">
                            <RangePicker
                                className="w-full"
                                format="DD/MM/YYYY"
                                placeholder={['Ngày bắt đầu', 'Ngày kết thúc']}
                            />
                        </Form.Item>
                    </Col>

                    <Col xs={24} sm={12} md={6} lg={3}>
                        <Form.Item label="Số phòng" name="roomNumber">
                            <Input
                                placeholder="Nhập số phòng"
                                allowClear
                            />
                        </Form.Item>
                    </Col>

                    <Col xs={24} sm={12} md={6} lg={3}>
                        <Form.Item label="Loại phòng" name="roomType">
                            <Select
                                placeholder="Chọn loại phòng"
                                allowClear
                            >
                                {roomTypes.map(type => (
                                    <Option key={type.id} value={type.id}>
                                        {type.name}
                                    </Option>
                                ))}
                            </Select>
                        </Form.Item>
                    </Col>

                    <Col xs={24} sm={12} md={6} lg={3}>
                        <Form.Item label="Trạng thái" name="roomStatus">
                            <Select
                                placeholder="Chọn trạng thái"
                                allowClear
                            >
                                {statusOptions.map(status => (
                                    <Option key={status.value} value={status.value}>
                                        {status.label}
                                    </Option>
                                ))}
                            </Select>
                        </Form.Item>
                    </Col>

                    <Col xs={24} sm={12} md={6} lg={6}>
                        <Form.Item label="Hành động" className="mb-0">
                            <Space wrap>
                                <Button
                                    type="primary"
                                    icon={<SearchOutlined />}
                                    onClick={handleSearch}
                                    loading={loading}
                                >
                                    Tìm kiếm
                                </Button>
                                <Button
                                    icon={<ReloadOutlined />}
                                    onClick={handleReset}
                                >
                                    Đặt lại
                                </Button>
                                <Button
                                    icon={<FilterOutlined />}
                                    type="dashed"
                                >
                                    Bộ lọc
                                </Button>
                            </Space>
                        </Form.Item>
                    </Col>
                </Row>

                <div className="flex justify-between items-center mt-4 pt-4 border-t">
                    <div className="flex items-center space-x-4">
                        <span className="text-sm text-gray-600">Chế độ xem:</span>
                        <Space>
                            <span className={`text-sm ${viewMode === 'grid' ? 'font-semibold text-blue-600' : 'text-gray-500'}`}>
                                Lưới
                            </span>
                            <Switch
                                checked={viewMode === 'timeline'}
                                onChange={handleViewModeChange}
                                size="small"
                            />
                            <span className={`text-sm ${viewMode === 'timeline' ? 'font-semibold text-blue-600' : 'text-gray-500'}`}>
                                Timeline
                            </span>
                        </Space>
                    </div>

                    <div className="text-xs text-gray-500">
                        Hiển thị theo: {viewMode === 'grid' ? 'Ngày, Loại phòng hoặc Tầng' : 'Timeline theo loại phòng'}
                    </div>
                </div>
            </Form>
        </Card>
    );
};

export default FilterBar;
