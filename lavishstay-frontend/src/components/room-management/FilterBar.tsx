import React from 'react';
import { Form, Input, Select, DatePicker, Button, Space, Card, Row, Col, Switch, Badge } from 'antd';
import { SearchOutlined, ReloadOutlined, CheckSquareOutlined } from '@ant-design/icons';
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
    selectedRoomsCount?: number;
    onMultiSelectModeChange?: (enabled: boolean) => void;
    multiSelectMode?: boolean;
}

const FilterBar: React.FC<FilterBarProps> = ({
    roomTypes,
    onSearch,
    loading = false,
    selectedRoomsCount = 0,
    onMultiSelectModeChange,
    multiSelectMode = false
}) => {
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

    // Hàm search real-time khi thay đổi input
    const handleFieldChange = () => {
        // Delay search một chút để tránh quá nhiều API calls
        setTimeout(() => {
            handleSearch();
        }, 300);
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
        <>
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
                                    onChange={handleFieldChange}
                                />
                            </Form.Item>
                        </Col>

                        <Col xs={24} sm={12} md={6} lg={5}>
                            <Form.Item label="Ngày nhận - trả phòng khả dụng" name="dateRange">
                                <RangePicker
                                    className="w-full"
                                    format="DD/MM/YYYY"
                                    placeholder={['Ngày nhận phòng', 'Ngày trả phòng']}
                                    onChange={handleFieldChange}
                                />
                            </Form.Item>
                        </Col>

                        <Col xs={24} sm={12} md={6} lg={3}>
                            <Form.Item label="Số phòng" name="roomNumber">
                                <Input
                                    placeholder="Nhập số phòng"
                                    allowClear
                                    onChange={handleFieldChange}
                                />
                            </Form.Item>
                        </Col>

                        <Col xs={24} sm={12} md={6} lg={3}>
                            <Form.Item label="Loại phòng" name="roomType">
                                <Select
                                    placeholder="Chọn loại phòng"
                                    allowClear
                                    onChange={handleFieldChange}
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
                                    onChange={handleFieldChange}
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
                                </Space>
                            </Form.Item>
                        </Col>
                    </Row>

                    <div className="flex justify-between items-center mt-4 pt-4 border-t">
                        <div className="flex items-center space-x-4">
                            <span className="text-sm">Chế độ xem:</span>
                            <Space>
                                <span className={`text-sm ${viewMode === 'grid' ? 'font-semibold' : ''}`}>
                                    Lưới
                                </span>
                                <Switch
                                    checked={viewMode === 'timeline'}
                                    onChange={handleViewModeChange}
                                    size="small"
                                />
                                <span className={`text-sm ${viewMode === 'timeline' ? 'font-semibold' : ''}`}>
                                    Timeline
                                </span>
                            </Space>
                        </div>

                        <div className="flex items-center space-x-4">
                            <div className="flex items-center space-x-2">
                                <CheckSquareOutlined className="text-blue-500" />
                                <span className="text-sm">Chọn nhiều:</span>
                                <Switch
                                    checked={multiSelectMode}
                                    onChange={onMultiSelectModeChange}
                                    size="small"
                                />
                                {selectedRoomsCount > 0 && (
                                    <Badge count={selectedRoomsCount} showZero={false}>
                                        <span className="text-sm text-blue-600 font-medium">
                                            {selectedRoomsCount} phòng
                                        </span>
                                    </Badge>
                                )}
                            </div>
                        </div>
                    </div>
                </Form>
            </Card>
        </>
    );
};

export default FilterBar;
