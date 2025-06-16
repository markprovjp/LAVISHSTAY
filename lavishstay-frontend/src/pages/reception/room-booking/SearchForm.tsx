import React, { useEffect } from 'react';
import { Form, Input, DatePicker, Select, Button, Row, Col, Typography, InputNumber } from 'antd';
import { SearchOutlined, ReloadOutlined } from '@ant-design/icons';
import { useDispatch, useSelector } from 'react-redux';
import dayjs from 'dayjs';

// Import Redux actions and selectors
import {
    updateSearchData,
    setDateRange,
    updateGuestDetails,
    selectSearchData,
    resetSearchForm
} from '../../../store/slices/searchSlice';

const { Title } = Typography;
const { RangePicker } = DatePicker;
const { Option } = Select;

interface SearchFormProps {
    onSearch: (values: any) => void;
}

const SearchForm: React.FC<SearchFormProps> = ({ onSearch }) => {
    const [form] = Form.useForm();
    const dispatch = useDispatch();
    const searchData = useSelector(selectSearchData);

    // Restore form values from Redux state on component mount
    useEffect(() => {
        const formValues = {
            dateRange: searchData.dateRange,
            adults: searchData.guestDetails.adults,
            children: searchData.guestDetails.children,
            budgetMin: undefined, // Default minimum budget
            budgetMax: undefined, // Default maximum budget
            roomType: undefined,
            specialRequests: undefined
        };

        form.setFieldsValue(formValues);
    }, [searchData, form]);

    const handleSearch = (values: any) => {
        // Update Redux state with search criteria
        const searchCriteria = {
            dateRange: values.dateRange,
            checkIn: values.dateRange?.[0]?.format('YYYY-MM-DD'),
            checkOut: values.dateRange?.[1]?.format('YYYY-MM-DD'),
            guestDetails: {
                adults: values.adults || 2,
                children: values.children || 0
            },
            guests: (values.adults || 2) + (values.children || 0)
        };

        // Update search state
        dispatch(updateSearchData(searchCriteria));
        dispatch(setDateRange(searchCriteria.dateRange ? [
            searchCriteria.checkIn!,
            searchCriteria.checkOut!
        ] : null));
        dispatch(updateGuestDetails(searchCriteria.guestDetails));

        // Call parent search handler with all form values including budget and filters
        onSearch({
            ...values,
            ...searchCriteria
        });
    };

    const handleReset = () => {
        // Reset Redux state to defaults
        dispatch(resetSearchForm());

        // Reset form to default values
        const defaultValues = {
            dateRange: [dayjs().add(1, 'day'), dayjs().add(2, 'day')],
            adults: 2,
            children: 0,
            budgetMin: undefined,
            budgetMax: undefined,
            roomType: undefined,
            specialRequests: undefined
        };

        form.setFieldsValue(defaultValues);

        // Trigger search with default values
        onSearch(defaultValues);
    };

    return (
        <div style={{ padding: '24px', background: '#fff', height: '100%' }}>
            <div style={{ marginBottom: '24px', textAlign: 'center' }}>
                <Title level={4} style={{ color: '#262626', marginBottom: '8px', fontSize: '18px' }}>
                    <SearchOutlined style={{ marginRight: '8px', color: '#1890ff' }} />
                    Tìm kiếm phòng
                </Title>
                <div style={{ width: '60px', height: '3px', background: '#1890ff', margin: '0 auto', borderRadius: '2px' }} />
            </div>

            <Form
                form={form}
                layout="vertical"
                onFinish={handleSearch}
                initialValues={{
                    dateRange: searchData.dateRange,
                    adults: searchData.guestDetails.adults,
                    children: searchData.guestDetails.children,
                    budgetMin: undefined,
                    budgetMax: undefined
                }}
            >
                <Form.Item
                    label={<span style={{ fontWeight: 500, color: '#262626' }}>Ngày nhận - trả phòng</span>}
                    name="dateRange"
                    rules={[{ required: true, message: 'Vui lòng chọn ngày' }]}
                >
                    <RangePicker
                        style={{ width: '100%', borderRadius: '6px' }}
                        format="DD/MM/YYYY"
                        placeholder={['Ngày nhận phòng', 'Ngày trả phòng']}
                        disabledDate={(current) => current && current < dayjs().startOf('day')}
                    />
                </Form.Item>

                <Row gutter={12}>
                    <Col span={12}>
                        <Form.Item label={<span style={{ fontWeight: 500, color: '#262626' }}>Người lớn</span>} name="adults">
                            <InputNumber
                                min={1}
                                max={10}
                                style={{ width: '100%', borderRadius: '6px' }}
                                placeholder="Số người lớn"
                            />
                        </Form.Item>
                    </Col>
                    <Col span={12}>
                        <Form.Item label={<span style={{ fontWeight: 500, color: '#262626' }}>Trẻ em</span>} name="children">
                            <InputNumber
                                min={0}
                                max={5}
                                style={{ width: '100%', borderRadius: '6px' }}
                                placeholder="Số trẻ em"
                            />
                        </Form.Item>
                    </Col>
                </Row>

                <Form.Item label={<span style={{ fontWeight: 500, color: '#262626' }}>Loại phòng</span>} name="roomType">
                    <Select
                        placeholder="Tất cả loại phòng"
                        allowClear
                        style={{ borderRadius: '6px' }}
                    >
                        <Option value="deluxe">Deluxe</Option>
                        <Option value="premium">Premium</Option>
                        <Option value="suite">Suite</Option>
                        <Option value="presidential">Presidential</Option>
                        <Option value="theLevel">The Level</Option>
                    </Select>
                </Form.Item>

                <Row gutter={12}>
                    <Col span={12}>
                        <Form.Item label={<span style={{ fontWeight: 500, color: '#262626' }}>Giá từ (VNĐ)</span>} name="budgetMin">
                            <InputNumber
                                style={{ width: '100%', borderRadius: '6px' }}
                                formatter={(value) => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')}
                                parser={(value) => value?.replace(/,/g, '') as any}
                                placeholder="1,000,000"
                                min={0}
                            />
                        </Form.Item>
                    </Col>
                    <Col span={12}>
                        <Form.Item label={<span style={{ fontWeight: 500, color: '#262626' }}>Giá đến (VNĐ)</span>} name="budgetMax">
                            <InputNumber
                                style={{ width: '100%', borderRadius: '6px' }}
                                formatter={(value) => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')}
                                parser={(value) => value?.replace(/,/g, '') as any}
                                placeholder="10,000,000"
                                min={0}
                            />
                        </Form.Item>
                    </Col>
                </Row>

                <Form.Item label={<span style={{ fontWeight: 500, color: '#262626' }}>Yêu cầu đặc biệt</span>} name="specialRequests">
                    <Input.TextArea
                        rows={3}
                        placeholder="Tìm theo tiện ích: wifi, tv, café, view biển, giường đôi..."
                        style={{ borderRadius: '6px' }}
                    />
                </Form.Item>

                <Form.Item style={{ marginBottom: 0 }}>
                    <Row gutter={12}>
                        <Col span={12}>
                            <Button
                                htmlType="button"
                                size="large"
                                block
                                icon={<ReloadOutlined />}
                                onClick={handleReset}
                                style={{
                                    borderRadius: '8px',
                                    height: '48px',
                                    fontWeight: 500,
                                }}
                            >
                                Reset
                            </Button>
                        </Col>
                        <Col span={12}>
                            <Button
                                type="primary"
                                htmlType="submit"
                                size="large"
                                block
                                icon={<SearchOutlined />}
                                style={{
                                    borderRadius: '8px',
                                    height: '48px',
                                    fontWeight: 500,
                                    border: 'none'
                                }}
                            >
                                Tìm kiếm
                            </Button>
                        </Col>
                    </Row>
                </Form.Item>
            </Form>
        </div>
    );
};

export default SearchForm;
