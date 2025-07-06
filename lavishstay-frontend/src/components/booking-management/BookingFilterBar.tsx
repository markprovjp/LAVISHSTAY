import React, { useState } from 'react';
import {
    Card,
    Row,
    Col,
    Input,
    Select,
    DatePicker,
    Button,
    Space,
    Typography,
} from 'antd';
import {
    SearchOutlined,
    FilterOutlined,
    ClearOutlined,
} from '@ant-design/icons';
import { BookingFilters } from '../../types/booking';
import dayjs from 'dayjs';

const { RangePicker } = DatePicker;
const { Option } = Select;
const { Text } = Typography;

interface BookingFilterBarProps {
    onSearch: (filters: BookingFilters) => void;
    loading?: boolean;
}

const BookingFilterBar: React.FC<BookingFilterBarProps> = ({ onSearch, loading = false }) => {
    const [filters, setFilters] = useState<BookingFilters>({});

    const handleFilterChange = (key: keyof BookingFilters, value: any) => {
        const newFilters = { ...filters, [key]: value };
        setFilters(newFilters);
    };

    const handleSearch = () => {
        onSearch(filters);
    };

    const handleClear = () => {
        const clearedFilters = {};
        setFilters(clearedFilters);
        onSearch(clearedFilters);
    };

    const paymentStatusOptions = [
        { value: 'pending', label: 'Chờ thanh toán' },
        { value: 'paid', label: 'Đã thanh toán' },
        { value: 'partial', label: 'Thanh toán một phần' },
        { value: 'refunded', label: 'Đã hoàn tiền' },
        { value: 'failed', label: 'Thanh toán thất bại' },
    ];

    const bookingStatusOptions = [
        { value: 'pending', label: 'Chờ xác nhận' },
        { value: 'confirmed', label: 'Đã xác nhận' },
        { value: 'checked_in', label: 'Đã nhận phòng' },
        { value: 'checked_out', label: 'Đã trả phòng' },
        { value: 'cancelled', label: 'Đã hủy' },
        { value: 'no_show', label: 'Không đến' },
    ];

    return (
        <Card
            style={{
                marginBottom: 24,
                borderRadius: 12,
                boxShadow: '0 2px 8px rgba(0,0,0,0.06)'
            }}
            bodyStyle={{ padding: '20px 24px' }}
        >
            <div style={{ marginBottom: 16 }}>
                <Text strong style={{ fontSize: 16, color: '#262626' }}>
                    <FilterOutlined style={{ marginRight: 8, color: '#1890ff' }} />
                    Bộ lọc tìm kiếm
                </Text>
            </div>

            <Row gutter={[16, 16]}>
                <Col xs={24} sm={12} md={6}>
                    <div style={{ marginBottom: 8 }}>
                        <Text style={{ fontSize: 13, fontWeight: 500, color: '#595959' }}>
                            Mã đặt phòng
                        </Text>
                    </div>
                    <Input
                        placeholder="Nhập mã đặt phòng"
                        value={filters.booking_code}
                        onChange={(e) => handleFilterChange('booking_code', e.target.value)}
                        prefix={<SearchOutlined style={{ color: '#bfbfbf' }} />}
                        style={{ borderRadius: 8 }}
                    />
                </Col>

                <Col xs={24} sm={12} md={6}>
                    <div style={{ marginBottom: 8 }}>
                        <Text style={{ fontSize: 13, fontWeight: 500, color: '#595959' }}>
                            Tên khách hàng
                        </Text>
                    </div>
                    <Input
                        placeholder="Nhập tên khách"
                        value={filters.guest_name}
                        onChange={(e) => handleFilterChange('guest_name', e.target.value)}
                        prefix={<SearchOutlined style={{ color: '#bfbfbf' }} />}
                        style={{ borderRadius: 8 }}
                    />
                </Col>

                <Col xs={24} sm={12} md={6}>
                    <div style={{ marginBottom: 8 }}>
                        <Text style={{ fontSize: 13, fontWeight: 500, color: '#595959' }}>
                            Số điện thoại
                        </Text>
                    </div>
                    <Input
                        placeholder="Nhập số điện thoại"
                        value={filters.guest_phone}
                        onChange={(e) => handleFilterChange('guest_phone', e.target.value)}
                        prefix={<SearchOutlined style={{ color: '#bfbfbf' }} />}
                        style={{ borderRadius: 8 }}
                    />
                </Col>

                <Col xs={24} sm={12} md={6}>
                    <div style={{ marginBottom: 8 }}>
                        <Text style={{ fontSize: 13, fontWeight: 500, color: '#595959' }}>
                            Trạng thái thanh toán
                        </Text>
                    </div>
                    <Select
                        placeholder="Chọn trạng thái thanh toán"
                        value={filters.payment_status}
                        onChange={(value) => handleFilterChange('payment_status', value)}
                        allowClear
                        style={{ width: '100%', borderRadius: 8 }}
                    >
                        {paymentStatusOptions.map(option => (
                            <Option key={option.value} value={option.value}>
                                {option.label}
                            </Option>
                        ))}
                    </Select>
                </Col>

                <Col xs={24} sm={12} md={6}>
                    <div style={{ marginBottom: 8 }}>
                        <Text style={{ fontSize: 13, fontWeight: 500, color: '#595959' }}>
                            Trạng thái đặt phòng
                        </Text>
                    </div>
                    <Select
                        placeholder="Chọn trạng thái đặt phòng"
                        value={filters.booking_status}
                        onChange={(value) => handleFilterChange('booking_status', value)}
                        allowClear
                        style={{ width: '100%', borderRadius: 8 }}
                    >
                        {bookingStatusOptions.map(option => (
                            <Option key={option.value} value={option.value}>
                                {option.label}
                            </Option>
                        ))}
                    </Select>
                </Col>

                <Col xs={24} sm={12} md={8}>
                    <div style={{ marginBottom: 8 }}>
                        <Text style={{ fontSize: 13, fontWeight: 500, color: '#595959' }}>
                            Thời gian lưu trú
                        </Text>
                    </div>
                    <RangePicker
                        placeholder={['Ngày nhận phòng', 'Ngày trả phòng']}
                        value={filters.date_range ? [dayjs(filters.date_range[0]), dayjs(filters.date_range[1])] : null}
                        onChange={(dates) => {
                            handleFilterChange('date_range', dates ? [dates[0]?.toDate(), dates[1]?.toDate()] : null);
                        }}
                        style={{ width: '100%', borderRadius: 8 }}
                        format="DD/MM/YYYY"
                    />
                </Col>

                <Col xs={24} sm={12} md={5}>
                    <div style={{ marginBottom: 8 }}>
                        <Text style={{ fontSize: 13, fontWeight: 500, color: '#595959' }}>
                            Ngày tạo
                        </Text>
                    </div>
                    <RangePicker
                        placeholder={['Từ ngày', 'Đến ngày']}
                        value={filters.created_date_range ? [dayjs(filters.created_date_range[0]), dayjs(filters.created_date_range[1])] : null}
                        onChange={(dates) => {
                            handleFilterChange('created_date_range', dates ? [dates[0]?.toDate(), dates[1]?.toDate()] : null);
                        }}
                        style={{ width: '100%', borderRadius: 8 }}
                        format="DD/MM/YYYY"
                    />
                </Col>
            </Row>

            <Row justify="end" style={{ marginTop: 20 }}>
                <Col>
                    <Space>
                        <Button
                            icon={<ClearOutlined />}
                            onClick={handleClear}
                            style={{ borderRadius: 8 }}
                        >
                            Xóa bộ lọc
                        </Button>
                        <Button
                            type="primary"
                            icon={<SearchOutlined />}
                            onClick={handleSearch}
                            loading={loading}
                            style={{
                                borderRadius: 8,
                                background: 'linear-gradient(135deg, #1890ff 0%, #096dd9 100%)',
                                border: 'none',
                                boxShadow: '0 2px 8px rgba(24, 144, 255, 0.3)'
                            }}
                        >
                            Tìm kiếm
                        </Button>
                    </Space>
                </Col>
            </Row>
        </Card>
    );
};

export default BookingFilterBar;
