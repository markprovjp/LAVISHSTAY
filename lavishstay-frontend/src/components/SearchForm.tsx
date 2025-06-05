import React, { useState, useEffect } from "react";
import {
  Form,
  Button,
  DatePicker,
  Card,
  Row,
  Col,
  Space,
  Typography,
  Popover,
  theme,
  Divider,
  Affix,
  Input,
  message,
} from "antd";
import {
  SearchOutlined,
  CalendarOutlined,
  UserOutlined,
  PlusOutlined,
  MinusOutlined,
  MessageOutlined,
  UsergroupAddOutlined,
} from "@ant-design/icons";
import { Users, UsersRound, UserCheck } from "lucide-react";
import { useNavigate } from "react-router-dom";
import ButtonSearch from "./ui/ButtonSearch";
import "./SearchForm.css";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";
import type { RangePickerProps } from "antd/es/date-picker";
import { useSearch } from "../hooks/useSearch";

dayjs.extend(customParseFormat);

// Type definitions

const { RangePicker } = DatePicker;
const { Title } = Typography;
const { useToken } = theme;

// Disable dates in the past
const disabledDate: RangePickerProps["disabledDate"] = (current) => {
  return current && current < dayjs().startOf("day");
};

interface SearchFormProps {
  onSearch?: (results: any) => void;
  className?: string;
  style?: React.CSSProperties;
}

const SearchForm: React.FC<SearchFormProps> = ({
  onSearch,
  className = "",
  style = {},
}) => {
  const [form] = Form.useForm();
  const { token } = useToken();
  const navigate = useNavigate();
  const {
    searchData,
    isValidSearchData,
    setSearchDateRange,
    setSearchGuestType,
    handleGuestCountChange,
    performSearch,
    formatGuestSelection,
    clearError,
  } = useSearch();
  const [guestPopoverVisible, setGuestPopoverVisible] = useState<boolean>(false);

  // Initialize form with search data
  useEffect(() => {
    const formValues: any = {};

    if (searchData.dateRange && Array.isArray(searchData.dateRange)) {
      // Ensure dateRange contains valid dayjs objects
      const isValidDateRange = searchData.dateRange.every(date =>
        date && typeof date.isValid === 'function' && date.isValid()
      );

      if (isValidDateRange) {
        formValues.dateRange = searchData.dateRange;
      }
    }

    formValues.guests = formatGuestSelection();
    form.setFieldsValue(formValues);
  }, [searchData, form, formatGuestSelection]);

  // Handle search form submission
  const handleSearch = async (values: any) => {
    try {
      // Clear any previous errors
      clearError();

      // Update date range if changed
      if (values.dateRange && values.dateRange !== searchData.dateRange) {
        setSearchDateRange(values.dateRange);
      }      // Validate before search
      if (!isValidSearchData) {
        message.error('Vui lòng điền đầy đủ thông tin tìm kiếm');
        return;
      }

      // Perform search
      const results = await performSearch();

      // Close popover
      setGuestPopoverVisible(false);

      // Navigate to search results page
      navigate('/search');

      // Call parent callback if provided
      if (onSearch) {
        onSearch(results);
      }

      message.success(`Tìm thấy ${results.total} phòng phù hợp`);
    } catch (error: any) {
      message.error(error.message || 'Có lỗi xảy ra khi tìm kiếm');
    }
  };

  // Guest selection dropdown content
  const guestPopoverContent = (
    <div
      className="p-6 guest-popover-content"
      style={{ width: "100%", maxWidth: "420px" }}
    >
      <Space direction="vertical" size="large" style={{ width: "100%" }}>
        <div>          <Title level={5} className="mb-4 text-center font-semibold">
          Bạn đi du lịch với ai?
        </Title>          <Row gutter={[12, 12]}>            <Col span={12}>
          <Button
            type={searchData.guestType === "solo" ? "primary" : "default"}
            block onClick={() => {
              setSearchGuestType("solo");
              form.setFieldsValue({ guests: "1 người" });
              // Auto-close popover for solo, couple, business
              setGuestPopoverVisible(false);
            }}
            className={`rounded-2xl h-auto py-3 transition-all font-medium ${searchData.guestType === "solo" ? "shadow-lg scale-105" : "hover:scale-105"
              }`}
          >
            <UserOutlined className="text-lg mr-2" /> Đi một mình
          </Button>
        </Col>
            <Col span={12}>
              <Button
                type={searchData.guestType === "couple" ? "primary" : "default"}
                block onClick={() => {
                  setSearchGuestType("couple");
                  form.setFieldsValue({ guests: "2 người" });
                  // Auto-close popover for solo, couple, business
                  setGuestPopoverVisible(false);
                }}
                className={`rounded-2xl h-auto py-3 transition-all font-medium ${searchData.guestType === "couple" ? "shadow-lg scale-105" : "hover:scale-105"
                  }`}
              >
                <UsersRound className="text-lg mr-2" /> Cặp đôi
              </Button>
            </Col>
            <Col span={12}>
              <Button
                type={searchData.guestType === "business" ? "primary" : "default"}
                block
                onClick={() => {
                  setSearchGuestType("business");
                  form.setFieldsValue({ guests: "1 người (Công tác)" });
                  // Auto-close popover for solo, couple, business
                  setGuestPopoverVisible(false);
                }}
                className={`rounded-2xl h-auto py-3 transition-all font-medium ${searchData.guestType === "business" ? "shadow-lg scale-105" : "hover:scale-105"
                  }`}
              >
                <UserCheck className="text-lg mr-2" /> Công tác
              </Button>
            </Col>
            <Col span={12}>
              <Button
                type={searchData.guestType === "family_young" ? "primary" : "default"}
                block
                onClick={() => {
                  setSearchGuestType("family_young");
                  form.setFieldsValue({ guests: formatGuestSelection() });
                }}
                className={`rounded-2xl h-auto py-3 transition-all font-medium ${searchData.guestType === "family_young" ? "shadow-lg scale-105" : "hover:scale-105"
                  }`}
              >
                <Users className="text-lg mr-2" /> Gia đình trẻ
              </Button>
            </Col>
            <Col span={12}>
              <Button
                type={searchData.guestType === "group" ? "primary" : "default"}
                block
                onClick={() => {
                  setSearchGuestType("group");
                  form.setFieldsValue({ guests: formatGuestSelection() });
                }}
                className={`rounded-2xl h-auto py-3 transition-all font-medium ${searchData.guestType === "group" ? "shadow-lg scale-105" : "hover:scale-105"
                  }`}
              >
                <UsergroupAddOutlined className="text-lg mr-2" /> Đi theo nhóm
              </Button>
            </Col>
          </Row>
        </div>        {(searchData.guestType === "family_young" || searchData.guestType === "group") && (
          <div className="animate-fadeIn">
            <Divider className="my-4" />
            <div className="  p-4 space-y-4">
              <div className="flex justify-between items-center">
                <div className="flex items-center">
                  <span className="text-sm font-semibold">Người lớn</span>
                  <span className="ml-2 text-xs text-gray-500">(tối thiểu 1)</span>
                </div>
                <div className="flex items-center space-x-3">                  <Button
                  icon={<MinusOutlined />}
                  size="small"
                  onClick={() => {
                    handleGuestCountChange("adults", "decrease");
                    // Update form immediately
                    setTimeout(() => {
                      form.setFieldsValue({ guests: formatGuestSelection() });
                    }, 100);
                  }}
                  disabled={searchData.guestDetails.adults <= 1}
                  className=" w-8 h-8 flex items-center justify-center"
                />
                  <span className="min-w-[30px] text-center font-bold text-lg">
                    {searchData.guestDetails.adults}
                  </span>
                  <Button
                    icon={<PlusOutlined />}
                    size="small"
                    onClick={() => {
                      handleGuestCountChange("adults", "increase");
                      // Update form immediately
                      setTimeout(() => {
                        form.setFieldsValue({ guests: formatGuestSelection() });
                      }, 100);
                    }}
                    className=" w-8 h-8 flex items-center justify-center"
                  />
                </div>
              </div>
              <div className="flex justify-between items-center">
                <div className="flex items-center">
                  <span className="text-sm font-semibold">Trẻ em</span>
                  <span className="ml-2 text-xs ">(0-17 tuổi)</span>
                </div>
                <div className="flex items-center space-x-3">                  <Button
                  icon={<MinusOutlined />}
                  size="small"
                  onClick={() => {
                    handleGuestCountChange("children", "decrease");
                    // Update form immediately
                    setTimeout(() => {
                      form.setFieldsValue({ guests: formatGuestSelection() });
                    }, 100);
                  }}
                  disabled={searchData.guestDetails.children <= 0}
                  className=" w-8 h-8 flex items-center justify-center"
                />
                  <span className="min-w-[30px] text-center font-bold text-lg">
                    {searchData.guestDetails.children}
                  </span>
                  <Button
                    icon={<PlusOutlined />}
                    size="small"
                    onClick={() => {
                      handleGuestCountChange("children", "increase");
                      // Update form immediately
                      setTimeout(() => {
                        form.setFieldsValue({ guests: formatGuestSelection() });
                      }, 100);
                    }}
                    className=" w-8 h-8 flex items-center justify-center"
                  />
                </div>
              </div>
            </div>
            {searchData.guestType === "group" && (
              <div className="mt-4 p-4 rounded-2xl border-2 border-blue-100 ">
                <p className="text-sm mb-3 flex items-start">
                  <UsergroupAddOutlined className="mr-2 text-blue-600 flex-shrink-0 mt-0.5" />
                  <span className="font-medium">
                    Cần đặt nhiều phòng? Chat với LAVISHSTAY để nhận ưu đãi đặc biệt!
                  </span>
                </p>
                <Button
                  type="primary"
                  icon={<MessageOutlined />}
                  size="small"
                  block
                  className="rounded-xl font-medium"
                  onClick={() => console.log("Chat with support")}
                >
                  Chat ngay
                </Button>
              </div>
            )}
          </div>)}
      </Space>
    </div>
  ); return (
    <Affix offsetTop={88}>
      <div
        className={`search-form-blur-container mx-auto max-w-4xl px-4 ${className}`}
        style={{ ...style, zIndex: 1000 }}
      >
        <Card
          className="search-form-compact shadow-2xl transition-all duration-300 "
          styles={{ body: { padding: "12px 16px" } }}
          variant="outlined"
        >
          <Form
            form={form}
            layout="horizontal"
            onFinish={handleSearch}
            className="font-bevietnam"
          >
            <Row gutter={[8, 8]} align="middle" justify="center">
              {/* Date Range Field */}
              <Col xs={24} sm={11} md={9} lg={10}>
                <Form.Item name="dateRange" className="mb-0">
                  <RangePicker
                    size="large"
                    style={{ width: "100%" }}
                    format="DD/MM/YYYY"
                    placeholder={["Ngày nhận phòng", "Ngày trả phòng"]}
                    className="rounded-full border-0 shadow-sm transition-all duration-200"
                    suffixIcon={<CalendarOutlined className="text-gray-800" />}
                    disabledDate={disabledDate}
                    classNames={{ popup: { root: "date-range-popup" } }}
                    getPopupContainer={(trigger) =>
                      trigger.parentElement || document.body
                    }
                  />
                </Form.Item>
              </Col>

              {/* Guests Field */}
              <Col xs={24} sm={8} md={6} lg={7}>
                <Form.Item name="guests" className="mb-0">                  <Popover
                  content={guestPopoverContent}
                  trigger="click"
                  open={guestPopoverVisible}
                  onOpenChange={setGuestPopoverVisible}
                  placement="bottomRight"
                  overlayClassName="guest-popover-rounded"
                  overlayStyle={{
                    borderRadius: "20px",
                    padding: 0,
                  }}
                  getPopupContainer={(trigger) =>
                    trigger.parentElement || document.body
                  }
                >
                  <Input
                    size="large"
                    placeholder="Số khách"
                    readOnly
                    value={formatGuestSelection()} prefix={
                      <span className="flex items-center text-gray-500">
                        {searchData.guestType === "solo" && <UserOutlined />}
                        {searchData.guestType === "couple" && (
                          <>
                            <UserOutlined />
                            <UserOutlined className="-ml-1" />
                          </>
                        )}
                        {searchData.guestType === "business" && <UserCheck />}
                        {searchData.guestType === "family_young" && (
                          <>
                            <UserOutlined />
                            <UserOutlined className="-ml-1" />
                            <UserOutlined className="-ml-1 text-xs" />
                          </>
                        )}
                        {searchData.guestType === "group" && (
                          <>
                            <UserOutlined />
                            <UserOutlined className="-ml-1" />
                            <UserOutlined className="-ml-1" />
                            <UserOutlined className="-ml-1" />
                          </>
                        )}
                      </span>
                    }
                    className="rounded-full border-0 shadow-sm cursor-pointer transition-all duration-200"
                    onClick={() => setGuestPopoverVisible(true)}
                  />
                </Popover>
                </Form.Item>
              </Col>

              {/* Search Button */}
              <Col xs={24} sm={5} md={3} lg={7}>
                <Form.Item className="mb-2">
                  <ButtonSearch
                    type="submit"
                    text="Tìm kiếm"
                    className="w-full search-button-compact"
                    icon={<SearchOutlined className="text-lg" />}
                    style={{
                      borderColor: token.colorPrimary,
                      borderRadius: "50px",
                      fontWeight: "600",
                    }}
                  />
                </Form.Item>
              </Col>
            </Row>
          </Form>
        </Card>
      </div>
    </Affix>
  );
};

export default SearchForm;
