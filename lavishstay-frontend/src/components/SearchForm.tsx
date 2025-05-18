import React from "react";
import { Form, Input, Button, DatePicker, Select, Card, Row, Col } from "antd";
import {
  SearchOutlined,
  CalendarOutlined,
  UserOutlined,
} from "@ant-design/icons";

const { Option } = Select;
const { RangePicker } = DatePicker;

interface SearchFormProps {
  onSearch?: (values: any) => void;
  className?: string;
  style?: React.CSSProperties;
}

const SearchForm: React.FC<SearchFormProps> = ({
  onSearch,
  className = "",
  style = {},
}) => {
  const [form] = Form.useForm();

  const handleSearch = (values: any) => {
    if (onSearch) {
      onSearch(values);
    }
  };
  return (
    <Card
      className={`shadow-lg rounded-xl ${className}`}
      style={style}
      bodyStyle={{ padding: "24px" }}
    >
      <Form
        form={form}
        layout="horizontal"
        onFinish={handleSearch}
        className="font-bevietnam"
      >
        <Row gutter={[16, 16]} align="middle">
          <Col xs={24} md={6} lg={7}>
            <Form.Item name="destination" className="mb-0">
              <Input
                size="large"
                placeholder="Nhập thành phố hoặc địa điểm..."
                prefix={<SearchOutlined className="text-gray-400" />}
                className="rounded-lg"
              />
            </Form.Item>
          </Col>

          <Col xs={24} md={8} lg={8}>
            <Form.Item name="dateRange" className="mb-0">
              <RangePicker
                size="large"
                style={{ width: "100%" }}
                format="DD/MM/YYYY"
                placeholder={["Ngày nhận phòng", "Ngày trả phòng"]}
                className="rounded-lg"
                suffixIcon={<CalendarOutlined className="text-gray-400" />}
              />
            </Form.Item>
          </Col>

          <Col xs={24} md={5} lg={5}>
            <Form.Item name="guests" className="mb-0">
              <Select
                size="large"
                placeholder="Số lượng khách"
                suffixIcon={<UserOutlined className="text-gray-400" />}
                className="rounded-lg"
              >
                {[1, 2, 3, 4, 5, 6].map((num) => (
                  <Option key={num} value={num}>
                    {num} {num === 1 ? "khách" : "khách"}
                  </Option>
                ))}
                <Option value="more">Hơn 6 khách</Option>
              </Select>
            </Form.Item>
          </Col>

          <Col xs={24} md={5} lg={4}>
            <Form.Item className="mb-0">
              <Button
                type="primary"
                htmlType="submit"
                size="large"
                block
                icon={<SearchOutlined />}
                className="font-semibold h-[40px] text-base rounded-lg"
              >
                Tìm kiếm
              </Button>
            </Form.Item>
          </Col>
        </Row>
      </Form>
    </Card>
  );
};

export default SearchForm;
