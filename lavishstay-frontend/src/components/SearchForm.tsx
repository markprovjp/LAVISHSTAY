import React, { useState, useEffect, useCallback, useMemo } from "react";
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
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";
import type { RangePickerProps } from "antd/es/date-picker";
import { useSearch } from "../hooks/useSearch";

dayjs.extend(customParseFormat);

// Type definitions

const { RangePicker } = DatePicker;
const { Title } = Typography;
const { useToken } = theme;

// Disable dates in the past - memoized
const disabledDate: RangePickerProps["disabledDate"] = (current) => {
  return current && current < dayjs().startOf("day");
};

interface SearchFormProps {
  onSearch?: (results: any) => void;
  className?: string;
  style?: React.CSSProperties;
}

const SearchForm: React.FC<SearchFormProps> = React.memo(({
  onSearch,
  className = "",
  style = {},
}) => {
  const [form] = Form.useForm();
  const { token } = useToken();
  const navigate = useNavigate(); const {
    searchData,
    isValidSearchData,
    setSearchDateRange,
    setSearchGuestType,
    updateGuests,
    performSearch,
    clearError,
  } = useSearch();
  const [guestPopoverVisible, setGuestPopoverVisible] = useState<boolean>(false);

  // Local state for guest details editing (for family_young and group types)
  const [localGuestDetails, setLocalGuestDetails] = useState({
    adults: searchData.guestDetails.adults,
    children: searchData.guestDetails.children
  });

  // Update local state when searchData changes (from external sources)
  useEffect(() => {
    setLocalGuestDetails({
      adults: searchData.guestDetails.adults,
      children: searchData.guestDetails.children
    });
  }, [searchData.guestDetails.adults, searchData.guestDetails.children]);

  // Handle local guest count change (doesn't update Redux store immediately) - memoized
  const handleLocalGuestCountChange = useCallback((
    type: "adults" | "children",
    operation: "increase" | "decrease"
  ) => {
    setLocalGuestDetails(prev => {
      const newDetails = { ...prev };
      if (operation === "increase") {
        newDetails[type] += 1;
      } else if (
        operation === "decrease" &&
        newDetails[type] > (type === "adults" ? 1 : 0)
      ) {
        newDetails[type] -= 1;
      }
      return newDetails;
    });
  }, []);

  // Format guest selection using local state for family_young and group types - memoized
  const formatLocalGuestSelection = useCallback(() => {
    switch (searchData.guestType) {
      case "solo":
        return "1 người";
      case "couple":
        return "2 người";
      case "business":
        return "1 người (Công tác)";
      case "family_young":
      case "group":
        const totalPeople = localGuestDetails.adults + localGuestDetails.children;
        return `${totalPeople} người`;
      default:
        return "Số lượng khách";
    }
  }, [searchData.guestType, localGuestDetails.adults, localGuestDetails.children]);

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

    // Use local formatting for guest display
    formValues.guests = formatLocalGuestSelection();
    form.setFieldsValue(formValues);
  }, [searchData, form, formatLocalGuestSelection]);// Handle search form submission - memoized
  const handleSearch = useCallback(async (values: any) => {
    try {
      // Clear any previous errors
      clearError();

      // Sync local guest details with Redux store for family_young and group types
      if (searchData.guestType === "family_young" || searchData.guestType === "group") {
        // Directly update Redux with local values
        updateGuests(localGuestDetails);

        // Wait a bit for Redux update to complete
        await new Promise(resolve => setTimeout(resolve, 100));
      }

      // Update date range if changed
      if (values.dateRange && values.dateRange !== searchData.dateRange) {
        setSearchDateRange(values.dateRange);
      }

      // Validate before search
      if (!isValidSearchData) {
        message.error('Vui lòng điền đầy đủ thông tin tìm kiếm');
        return;
      }      // Perform search
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
  }, [form, searchData, localGuestDetails, navigate, onSearch]);

  // Guest selection dropdown content
  const guestPopoverContent = (
    <div
      className="p-6 guest-popover-content"
      style={{ width: "100%", maxWidth: "420px" }}
    >
      <Space direction="vertical" size="large" style={{ width: "100%" }}>
        <div>          <Title level={5} className="mb-4 text-center font-semibold">
          Bạn đi du lịch với ai?
        </Title>          <Row gutter={[12, 12]}>            <Col span={12}>              <Button
          type={searchData.guestType === "solo" ? "primary" : "default"}
          block onClick={() => {
            setSearchGuestType("solo");
            form.setFieldsValue({ guests: "1 người" });
            // Auto-close popover and auto-search for simple guest types
            setGuestPopoverVisible(false);
            // Auto-search after form update
            setTimeout(() => {
              const formValues = form.getFieldsValue();
              handleSearch(formValues);
            }, 100);
          }}
          className={`rounded-2xl h-auto py-3 transition-all font-medium ${searchData.guestType === "solo" ? "shadow-lg scale-105" : "hover:scale-105"
            }`}
        >
          <UserOutlined className="text-lg mr-2" /> Đi một mình
        </Button>
        </Col>
            <Col span={12}>              <Button
              type={searchData.guestType === "couple" ? "primary" : "default"}
              block onClick={() => {
                setSearchGuestType("couple");
                form.setFieldsValue({ guests: "2 người" });
                // Auto-close popover and auto-search for simple guest types
                setGuestPopoverVisible(false);
                // Auto-search after form update
                setTimeout(() => {
                  const formValues = form.getFieldsValue();
                  handleSearch(formValues);
                }, 100);
              }}
              className={`rounded-2xl h-auto py-3 transition-all font-medium ${searchData.guestType === "couple" ? "shadow-lg scale-105" : "hover:scale-105"
                }`}
            >
              <UsersRound className="text-lg mr-2" /> Cặp đôi
            </Button>
            </Col>
            <Col span={12}>              <Button
              type={searchData.guestType === "business" ? "primary" : "default"}
              block
              onClick={() => {
                setSearchGuestType("business");
                form.setFieldsValue({ guests: "1 người (Công tác)" });
                // Auto-close popover and auto-search for simple guest types
                setGuestPopoverVisible(false);
                // Auto-search after form update
                setTimeout(() => {
                  const formValues = form.getFieldsValue();
                  handleSearch(formValues);
                }, 100);
              }}
              className={`rounded-2xl h-auto py-3 transition-all font-medium ${searchData.guestType === "business" ? "shadow-lg scale-105" : "hover:scale-105"
                }`}
            >
              <UserCheck className="text-lg mr-2" /> Công tác
            </Button>
            </Col>
            <Col span={12}>              <Button
              type={searchData.guestType === "family_young" ? "primary" : "default"}
              block
              onClick={() => {
                setSearchGuestType("family_young");
                form.setFieldsValue({ guests: formatLocalGuestSelection() });
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
                  form.setFieldsValue({ guests: formatLocalGuestSelection() });
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
                    handleLocalGuestCountChange("adults", "decrease");
                    // Update form display with local values
                    setTimeout(() => {
                      form.setFieldsValue({ guests: formatLocalGuestSelection() });
                    }, 50);
                  }}
                  disabled={localGuestDetails.adults <= 1}
                  className=" w-8 h-8 flex items-center justify-center"
                />
                  <span className="min-w-[30px] text-center font-bold text-lg">
                    {localGuestDetails.adults}
                  </span>                  <Button
                    icon={<PlusOutlined />}
                    size="small"
                    onClick={() => {
                      handleLocalGuestCountChange("adults", "increase");
                      // Update form display with local values
                      setTimeout(() => {
                        form.setFieldsValue({ guests: formatLocalGuestSelection() });
                      }, 50);
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
                    handleLocalGuestCountChange("children", "decrease");
                    // Update form display with local values
                    setTimeout(() => {
                      form.setFieldsValue({ guests: formatLocalGuestSelection() });
                    }, 50);
                  }}
                  disabled={localGuestDetails.children <= 0}
                  className=" w-8 h-8 flex items-center justify-center"
                />
                  <span className="min-w-[30px] text-center font-bold text-lg">
                    {localGuestDetails.children}
                  </span>                  <Button
                    icon={<PlusOutlined />}
                    size="small"
                    onClick={() => {
                      handleLocalGuestCountChange("children", "increase");
                      // Update form display with local values
                      setTimeout(() => {
                        form.setFieldsValue({ guests: formatLocalGuestSelection() });
                      }, 50);
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
        className={`search-form-blur-container mx-auto max-w-2xl px-2 ${className}`}
        style={{ ...style, zIndex: 1000 }}
      >
        <Card
          className="rounded-3xl shadow-xl border border-gray-100 search-form-card"
          variant="outlined"
        >
          <Form
            form={form}
            layout="vertical"
            onFinish={handleSearch}
            className="font-bevietnam"
          >
            <Row gutter={[18, 18]} align="middle" justify="center">
              {/* Date Range Field */}
              <Col xs={24} sm={12}>
                <Form.Item name="dateRange" className="mb-0" label={false}>
                  <RangePicker
                    size="large"
                    style={{ width: "100%" }}
                    format="DD/MM/YYYY"
                    placeholder={["Nhận phòng", "Trả phòng"]}
                    className="rounded-full border-0 shadow-sm search-form-input"
                    suffixIcon={<CalendarOutlined />}
                    disabledDate={disabledDate}
                    getPopupContainer={(trigger) => trigger.parentElement || document.body}
                  />
                </Form.Item>
              </Col>

              {/* Guests Field */}
              <Col xs={24} sm={6}>
                <Form.Item name="guests" className="mb-0" label={false}>
                  <Popover
                    content={guestPopoverContent}
                    trigger="click"
                    open={guestPopoverVisible}
                    onOpenChange={setGuestPopoverVisible}
                    placement="bottomRight"
                    overlayClassName="guest-popover-rounded"
                    overlayStyle={{ borderRadius: "20px", padding: 0 }}
                    getPopupContainer={(trigger) => trigger.parentElement || document.body}
                  >
                    <Input
                      size="large"
                      placeholder="Số khách"
                      readOnly
                      value={formatLocalGuestSelection()}
                      prefix={<UserOutlined />}
                      className="rounded-full border-0 shadow-sm cursor-pointer search-form-input"
                      onClick={() => setGuestPopoverVisible(true)}
                    />
                  </Popover>
                </Form.Item>
              </Col>

              {/* Search Button */}
              <Col xs={24} sm={6} className="flex items-end">
                <Form.Item className="mb-0 w-full">
                  <Button
                    type="primary"
                    htmlType="submit"
                    size="large"
                    className="w-full rounded-full font-bold search-button-compact flex items-center justify-center"
                    style={{ minHeight: 48, fontWeight: 600, fontSize: 16, padding: 0 }}
                    icon={<SearchOutlined className="text-lg" />}
                  >
                    <span className="ml-1">Tìm kiếm</span>
                  </Button>
                </Form.Item>
              </Col>
            </Row>
          </Form>
        </Card>
      </div>
    </Affix>
  );
});

SearchForm.displayName = 'SearchForm';

export default SearchForm;
