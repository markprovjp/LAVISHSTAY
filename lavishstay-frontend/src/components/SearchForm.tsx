import React, { useState, useEffect, useCallback } from "react";
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
  Select,
  InputNumber,
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

const { RangePicker } = DatePicker;
const { Title } = Typography;
const { useToken } = theme;
const { Option } = Select;

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
  const navigate = useNavigate();

  const {
    searchData,
    isValidSearchData,
    setSearchDateRange,
    setSearchGuestType,
    updateGuests,
    updateChildAgeHandler,
    performSearch,
    clearError,
  } = useSearch();

  const [guestPopoverVisible, setGuestPopoverVisible] = useState<boolean>(false);

  // Local state for guest details editing (for family_young and group types)
  const [localGuestDetails, setLocalGuestDetails] = useState({
    adults: searchData.guestDetails.adults,
    children: searchData.guestDetails.children,
    childrenAges: searchData.guestDetails.childrenAges || []
  });

  // Auto-update dates to today and tomorrow if dateRange is in the past
  useEffect(() => {
    const now = dayjs();
    const tomorrow = dayjs().add(1, 'day');

    // Check if current dateRange is in the past or not set
    if (!searchData.dateRange ||
      !Array.isArray(searchData.dateRange) ||
      searchData.dateRange.length < 2 ||
      !searchData.dateRange[0] ||
      searchData.dateRange[0].isBefore(now, 'day')) {

      console.log('🗓️ Auto-updating dates to today and tomorrow');
      setSearchDateRange([now, tomorrow]);
    }
  }, []); // Run only on component mount

  // Update local state when searchData changes (from external sources)
  useEffect(() => {
    setLocalGuestDetails({
      adults: searchData.guestDetails.adults,
      children: searchData.guestDetails.children,
      childrenAges: searchData.guestDetails.childrenAges || []
    });
  }, [searchData.guestDetails.adults, searchData.guestDetails.children, searchData.guestDetails.childrenAges]);

  // Handle child age change
  const handleChildAgeChange = useCallback((index: number, age: number) => {
    setLocalGuestDetails(prev => {
      const newAges = [...(prev.childrenAges || [])];
      if (newAges[index]) {
        newAges[index] = { ...newAges[index], age };
      } else {
        newAges[index] = { age, id: `child_${index + 1}` };
      }
      return {
        ...prev,
        childrenAges: newAges
      };
    });
  }, []);

  // Handle local guest count change (doesn't update Redux store immediately) - memoized
  const handleLocalGuestCountChange = useCallback((
    type: "adults" | "children",
    operation: "increase" | "decrease"
  ) => {
    setLocalGuestDetails(prev => {
      const newDetails = { ...prev };
      if (operation === "increase") {
        newDetails[type] += 1;
        // If adding children, add new child age entry
        if (type === "children") {
          const newAges = [...(prev.childrenAges || [])];
          newAges.push({ age: 8, id: `child_${newAges.length + 1}` }); // Default age 8
          newDetails.childrenAges = newAges;
        }
      } else if (
        operation === "decrease" &&
        newDetails[type] > (type === "adults" ? 1 : 0)
      ) {
        newDetails[type] -= 1;
        // If removing children, remove last child age entry
        if (type === "children") {
          const newAges = [...(prev.childrenAges || [])];
          newAges.pop();
          newDetails.childrenAges = newAges;
        }
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
  }, [searchData, form, formatLocalGuestSelection]);

  // Handle search form submission - memoized
  const handleSearch = useCallback(async (values: any) => {
    try {
      // Clear any previous errors
      clearError();

      // Sync local guest details with Redux store for family_young and group types
      if (searchData.guestType === "family_young" || searchData.guestType === "group") {
        // Update Redux with local values including children ages
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
        message.error('Kiểm tra lại ngày tháng và số lượng khách trước khi tìm kiếm');
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
  }, [searchData, localGuestDetails, navigate, onSearch, clearError, setSearchDateRange, updateGuests, isValidSearchData, performSearch]);

  // Guest selection dropdown content
  const guestPopoverContent = (
    <div
      className="p-6 bg-white"
      style={{ width: "100%", maxWidth: "400px" }}
    >
      <Space direction="vertical" size="large" style={{ width: "100%" }}>
        <div>
          <Title level={5} className="mb-6 text-center font-medium text-gray-800">
            Bạn đi du lịch với ai?
          </Title>
          <Row gutter={[12, 12]}>
            <Col span={12}>
              <Button
                type={searchData.guestType === "solo" ? "primary" : "default"}
                block
                onClick={() => {
                  setSearchGuestType("solo");
                  form.setFieldsValue({ guests: "1 người" });
                  setGuestPopoverVisible(false);
                  setTimeout(() => {
                    const formValues = form.getFieldsValue();
                    handleSearch(formValues);
                  }, 100);
                }}
                className={`rounded-lg h-auto py-4 transition-all ${searchData.guestType === "solo"
                    ? "bg-blue-600 border-blue-600 text-white"
                    : "bg-white border-gray-200 text-gray-700 hover:border-gray-300"
                  }`}
              >
                <UserOutlined className="mr-2" /> Đi một mình
              </Button>
            </Col>
            <Col span={12}>
              <Button
                type={searchData.guestType === "couple" ? "primary" : "default"}
                block
                onClick={() => {
                  setSearchGuestType("couple");
                  form.setFieldsValue({ guests: "2 người" });
                  setGuestPopoverVisible(false);
                  setTimeout(() => {
                    const formValues = form.getFieldsValue();
                    handleSearch(formValues);
                  }, 100);
                }}
                className={`rounded-lg h-auto py-4 transition-all ${searchData.guestType === "couple"
                    ? "bg-blue-600 border-blue-600 text-white"
                    : "bg-white border-gray-200 text-gray-700 hover:border-gray-300"
                  }`}
              >
                <UsersRound className="mr-2" /> Cặp đôi
              </Button>
            </Col>
            <Col span={12}>
              <Button
                type={searchData.guestType === "business" ? "primary" : "default"}
                block
                onClick={() => {
                  setSearchGuestType("business");
                  form.setFieldsValue({ guests: "1 người (Công tác)" });
                  setGuestPopoverVisible(false);
                  setTimeout(() => {
                    const formValues = form.getFieldsValue();
                    handleSearch(formValues);
                  }, 100);
                }}
                className={`rounded-lg h-auto py-4 transition-all ${searchData.guestType === "business"
                    ? "bg-blue-600 border-blue-600 text-white"
                    : "bg-white border-gray-200 text-gray-700 hover:border-gray-300"
                  }`}
              >
                <UserCheck className="mr-2" /> Công tác
              </Button>
            </Col>
            <Col span={12}>
              <Button
                type={searchData.guestType === "family_young" ? "primary" : "default"}
                block
                onClick={() => {
                  setSearchGuestType("family_young");
                  form.setFieldsValue({ guests: formatLocalGuestSelection() });
                }}
                className={`rounded-lg h-auto py-4 transition-all ${searchData.guestType === "family_young"
                    ? "bg-blue-600 border-blue-600 text-white"
                    : "bg-white border-gray-200 text-gray-700 hover:border-gray-300"
                  }`}
              >
                <Users className="mr-2" /> Gia đình trẻ
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
                className={`rounded-lg h-auto py-4 transition-all ${searchData.guestType === "group"
                    ? "bg-blue-600 border-blue-600 text-white"
                    : "bg-white border-gray-200 text-gray-700 hover:border-gray-300"
                  }`}
              >
                <UsergroupAddOutlined className="mr-2" /> Đi theo nhóm
              </Button>
            </Col>
          </Row>
        </div>

        {(searchData.guestType === "family_young" || searchData.guestType === "group") && (
          <div>
            <Divider className="my-6" />
            <div className="p-4 space-y-6 bg-gray-50 rounded-lg">
              <div className="flex justify-between items-center">
                <div>
                  <span className="text-sm font-medium text-gray-700">Người lớn</span>
                  <span className="ml-2 text-xs text-gray-500">(tối thiểu 1)</span>
                </div>
                <div className="flex items-center space-x-4">
                  <Button
                    icon={<MinusOutlined />}
                    size="small"
                    onClick={() => {
                      handleLocalGuestCountChange("adults", "decrease");
                      setTimeout(() => {
                        form.setFieldsValue({ guests: formatLocalGuestSelection() });
                      }, 50);
                    }}
                    disabled={localGuestDetails.adults <= 1}
                    className="w-8 h-8 flex items-center justify-center rounded-full border-gray-300"
                  />
                  <span className="min-w-[40px] text-center font-medium text-lg text-gray-800">
                    {localGuestDetails.adults}
                  </span>
                  <Button
                    icon={<PlusOutlined />}
                    size="small"
                    onClick={() => {
                      handleLocalGuestCountChange("adults", "increase");
                      setTimeout(() => {
                        form.setFieldsValue({ guests: formatLocalGuestSelection() });
                      }, 50);
                    }}
                    className="w-8 h-8 flex items-center justify-center rounded-full border-gray-300"
                  />
                </div>
              </div>

              <div className="flex justify-between items-center">
                <div>
                  <span className="text-sm font-medium text-gray-700">Trẻ em</span>
                  <span className="ml-2 text-xs text-gray-500">(0-17 tuổi)</span>
                </div>
                <div className="flex items-center space-x-4">
                  <Button
                    icon={<MinusOutlined />}
                    size="small"
                    onClick={() => {
                      handleLocalGuestCountChange("children", "decrease");
                      setTimeout(() => {
                        form.setFieldsValue({ guests: formatLocalGuestSelection() });
                      }, 50);
                    }}
                    disabled={localGuestDetails.children <= 0}
                    className="w-8 h-8 flex items-center justify-center rounded-full border-gray-300"
                  />
                  <span className="min-w-[40px] text-center font-medium text-lg text-gray-800">
                    {localGuestDetails.children}
                  </span>
                  <Button
                    icon={<PlusOutlined />}
                    size="small"
                    onClick={() => {
                      handleLocalGuestCountChange("children", "increase");
                      setTimeout(() => {
                        form.setFieldsValue({ guests: formatLocalGuestSelection() });
                      }, 50);
                    }}
                    className="w-8 h-8 flex items-center justify-center rounded-full border-gray-300"
                  />
                </div>
              </div>

              {/* Children Age Inputs */}
              {localGuestDetails.children > 0 && (
                <div className="mt-4">
                  <div className="text-sm font-medium text-gray-700 mb-3">
                    Tuổi của từng trẻ em:
                  </div>
                  <div className="space-y-3">
                    {Array.from({ length: localGuestDetails.children }, (_, index) => {
                      const childAge = localGuestDetails.childrenAges?.[index]?.age || 8;
                      return (
                        <div key={index} className="flex items-center justify-between">
                          <span className="text-sm text-gray-600">
                            Trẻ em {index + 1}:
                          </span>
                          <Select
                            value={childAge}
                            style={{ width: 80 }}
                            size="small"
                            onChange={(age) => handleChildAgeChange(index, age)}
                          >
                            {Array.from({ length: 18 }, (_, ageIndex) => (
                              <Option key={ageIndex} value={ageIndex}>
                                {ageIndex} tuổi
                              </Option>
                            ))}
                          </Select>
                        </div>
                      );
                    })}
                  </div>
                </div>
              )}
            </div>

            {searchData.guestType === "group" && (
              <div className="mt-6 p-4 rounded-lg border border-gray-200 bg-blue-50">
                <p className="text-sm mb-3 flex items-start text-gray-700">
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
                  className="rounded-lg bg-blue-600 hover:bg-blue-700 border-0"
                  onClick={() => console.log("Chat with support")}
                >
                  Chat ngay
                </Button>
              </div>
            )}

            {/* Apply button for guest details */}
            <div className="mt-4">
              <Button
                type="primary"
                block
                onClick={() => {
                  setGuestPopoverVisible(false);
                  setTimeout(() => {
                    const formValues = form.getFieldsValue();
                    handleSearch(formValues);
                  }, 100);
                }}
                className="rounded-lg bg-blue-600 hover:bg-blue-700 border-0"
              >
                Áp dụng
              </Button>
            </div>
          </div>
        )}
      </Space>
    </div>
  );

  return (
    <Affix offsetTop={88}>
      <div
        className={`mx-auto max-w-3xl ${className}`}
        style={{ ...style, zIndex: 1000 }}
      >
        <Card className="shadow-sm border border-gray-200 rounded-2xl">
          <Form
            form={form}
            layout="vertical"
            onFinish={handleSearch}
            className="w-full"
          >
            <Row gutter={[24, 24]} align="middle">
              {/* Date Range Field */}
              <Col xs={24} sm={12}>
                <Form.Item name="dateRange" className="mb-0">
                  <RangePicker
                    size="large"
                    style={{ width: "100%", height: "52px" }}
                    format="DD/MM/YYYY"
                    placeholder={["Nhận phòng", "Trả phòng"]}
                    className="rounded-lg border border-gray-300 hover:border-gray-400 focus:border-blue-500"
                    suffixIcon={<CalendarOutlined className="text-gray-500" />}
                    disabledDate={disabledDate}
                    getPopupContainer={(trigger) => trigger.parentElement || document.body}
                  />
                </Form.Item>
              </Col>

              {/* Guests Field */}
              <Col xs={24} sm={7}>
                <Form.Item name="guests" className="mb-0">
                  <Popover
                    content={guestPopoverContent}
                    trigger="click"
                    open={guestPopoverVisible}
                    onOpenChange={setGuestPopoverVisible}
                    placement="bottomRight"
                    overlayStyle={{ borderRadius: "12px" }}
                    getPopupContainer={(trigger) => trigger.parentElement || document.body}
                  >
                    <Input
                      size="large"
                      placeholder="Số khách"
                      readOnly
                      value={formatLocalGuestSelection()}
                      prefix={<UserOutlined className="text-gray-500" />}
                      className="rounded-lg border border-gray-300 hover:border-gray-400 cursor-pointer h-[52px]"
                      onClick={() => setGuestPopoverVisible(true)}
                    />
                  </Popover>
                </Form.Item>
              </Col>

              {/* Search Button */}
              <Col xs={24} sm={5}>
                <Form.Item className="mb-0">
                  <Button
                    type="primary"
                    htmlType="submit"
                    size="large"
                    className="w-full rounded-lg h-[52px] bg-blue-600 hover:bg-blue-700 border-0 font-medium text-base"
                    icon={<SearchOutlined />}
                  >
                    Tìm kiếm
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
