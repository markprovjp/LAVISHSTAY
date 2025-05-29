import React, { useState } from "react";
import {
  Form,
  Input,
  Button,
  DatePicker,
  Card,
  Row,
  Col,
  Tabs,
  Space,
  Typography,
  Popover,
  Tag,
  theme,
  AutoComplete,
  Divider,
} from "antd";
import {
  SearchOutlined,
  CalendarOutlined,
  UserOutlined,
  HomeOutlined,
  EnvironmentOutlined,
  ClockCircleOutlined,
  FireOutlined,
  CloseOutlined,
  DeleteOutlined,
  PlusOutlined,
  MinusOutlined,
  MessageOutlined,
  UsergroupAddOutlined,
} from "@ant-design/icons";
import { Users, UsersRound, UserCheck, Building2 } from "lucide-react";
import ButtonSearch from "./ui/ButtonSearch";
import "./SearchForm.css";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";
import type { RangePickerProps } from "antd/es/date-picker";

dayjs.extend(customParseFormat);

// Type definitions

const { RangePicker } = DatePicker;
const { Text, Title } = Typography;
const { useToken } = theme;

// Disable dates in the past
const disabledDate: RangePickerProps["disabledDate"] = (current) => {
  return current && current < dayjs().startOf("day");
};

// Room options for autocomplete
const searchedData = [
  { id: "1", name: "Phòng Grand Palace Đà Lạt" },
  { id: "2", name: "Phòng The Level" },
  { id: "3", name: "Phòng Luxury" },
  { id: "4", name: "Phòng Deluxe" },
  { id: "5", name: "Phòng Superior" },
  { id: "6", name: "Phòng Standard" },
  { id: "7", name: "Phòng Executive" },
];

interface SearchFormProps {
  onSearch?: (values: any) => void;
  className?: string;
  style?: React.CSSProperties;
}

// Search history definition
interface SearchHistory {
  id: string;
  name: string;
}

const SearchForm: React.FC<SearchFormProps> = ({
  onSearch,
  className = "",
  style = {},
}) => {
  const [form] = Form.useForm();
  const { token } = useToken();
  const [activeTab, setActiveTab] = useState<string>("hotel");
  const [guestPopoverVisible, setGuestPopoverVisible] =
    useState<boolean>(false);
  const [guestType, setGuestType] = useState<string>("solo");
  const [guestCount, setGuestCount] = useState<{
    adults: number;
    children: number;
    rooms: number;
  }>({
    adults: 1,
    children: 0,
    rooms: 1,
  });

  // Mock data for search history
  const searchHistory: SearchHistory[] = [
    { id: "1", name: "Khách sạn Grand Palace Đà Lạt" },
  ];

  // Handle search form submission
  const handleSearch = (values: any) => {
    if (onSearch) {
      const searchData = {
        ...values,
        accommodationType: activeTab,
      };
      onSearch(searchData);
    }
    setGuestPopoverVisible(false);
  };

  // Handle tab change
  const handleTabChange = (key: string) => {
    setActiveTab(key);
    form.resetFields();
  };

  // Function to clear search history
  const clearSearchHistory = () => {
    console.log("Lịch sử tìm kiếm đã xóa");
  }; // Destination search dropdown content
  const destinationPopoverContent = (
    <div
      className="p-4"
      style={{
        width: "100%",
        maxWidth: "1000px",
      }}
    >
      <Row gutter={[20, 20]}>
        {/* Search History */}
        <Col xs={24} md={9}>
          <div className="flex justify-between items-center mb-3">
            <Title level={5} className="m-0 flex items-center">
              <ClockCircleOutlined className="mr-2" />
              Lịch sử tìm kiếm
            </Title>
            <Button
              type="text"
              size="small"
              icon={<DeleteOutlined />}
              onClick={clearSearchHistory}
              className="hover:text-red-500 dark:hover:text-red-400 transition-colors"
            >
              Xóa
            </Button>
          </div>
          <div className="search-history-container rounded-lg p-2">
            <Space
              direction="vertical"
              style={{ width: "100%" }}
              className="animate-fadeIn"
            >
              {searchHistory.map((item) => (
                <Button
                  key={item.id}
                  type="text"
                  block
                  style={{
                    textAlign: "left",
                    height: "auto",
                    padding: "8px 12px",
                    borderRadius: "8px",
                    whiteSpace: "normal",
                    wordBreak: "break-word",
                  }}
                  className="   flex items-center justify-between transition-colors relative overflow-hidden group"
                  onClick={() => {
                    form.setFieldsValue({ destination: item.name });
                    setGuestPopoverVisible(false);
                  }}
                >
                  <div className="flex items-start relative z-10">
                    <ClockCircleOutlined className=" mt-1 mr-2" />
                    <div style={{ maxWidth: "100%" }}>{item.name}</div>
                  </div>
                  <div className="opacity-0 group-hover:opacity-100 transition-opacity relative z-10">
                    <CloseOutlined
                      className=" hover:text-red-500 ml-2"
                      onClick={(e) => {
                        e.stopPropagation();
                        // Would dispatch a Redux action to remove this item
                        console.log("Remove history item:", item.id);
                      }}
                    />
                  </div>
                  <div className="absolute inset-0   transform scale-x-0 group-hover:scale-x-100 origin-left transition-transform"></div>
                </Button>
              ))}
            </Space>
          </div>
        </Col>{" "}
        <Divider
          type="vertical"
          style={{ height: "100%" }}
          className="hidden md:block"
        />
        {/* Popular Destinations */}
        {/* <Col xs={24} md={14}>
          <Title
            level={5}
            className="mb-3 dark:text-gray-100 flex items-center"
          >
            <EnvironmentOutlined className="mr-2" />
            Phòng nổi bật
          </Title>
          <Row gutter={[16, 16]} className="animate-fadeIn">
            {popularDestinations.map((destination) => (
              <Col xs={12} sm={8} md={6} lg={5} key={destination.id}>
                <div
                  onClick={() => {
                    form.setFieldsValue({ destination: destination.name });
                    setDestinationPopoverVisible(false);
                  }}
                  className="text-center cursor-pointer group"
                >
                  <div className="relative mb-2 overflow-hidden  transform transition-all group-hover:scale-105 ">
                    <Avatar
                      size={64}
                      src={destination.image}
                      className="shadow-sm transition-all"
                    />
                  </div>
                  <div>
                    <Text strong className="  transition-colors">
                      {destination.name}
                    </Text>
                  </div>
                </div>
              </Col>
            ))}
          </Row>
        </Col> */}
      </Row>
    </div>
  );

  // Handle guest count changes
  const handleGuestCountChange = (
    type: "adults" | "children" | "rooms",
    operation: "increase" | "decrease"
  ) => {
    setGuestCount((prevCount) => {
      const newCount = { ...prevCount };

      if (operation === "increase") {
        newCount[type] += 1;
      } else if (
        operation === "decrease" &&
        newCount[type] > (type === "adults" ? 1 : 0)
      ) {
        newCount[type] -= 1;
      }

      // Ensure at least 1 room for every 4 people
      if (type === "adults" || type === "children") {
        const totalPeople = newCount.adults + newCount.children;
        newCount.rooms = Math.max(newCount.rooms, Math.ceil(totalPeople / 4));
      }

      return newCount;
    });
  }; // Format guest selection text
  const formatGuestSelection = () => {
    if (guestType === "solo") {
      return "1 người";
    } else if (guestType === "couple") {
      return "2 người";
    } else if (guestType === "family" || guestType === "group") {
      const { adults, children, rooms } = guestCount;
      const totalPeople = adults + children;
      return `${totalPeople} người, ${rooms} phòng`;
    }
    return "Số lượng khách";
  };
  // Guest selection dropdown content
  const guestPopoverContent = (
    <div
      className="p-4   transition-all"
      style={{ width: "100%", maxWidth: "440px" }}
    >
      <Space direction="vertical" size="large" style={{ width: "100%" }}>
        <div>
          <Title level={5} className="mb-3 ">
            Bạn đi du lịch với ai?
          </Title>
          <Row gutter={[8, 8]}>
            <Col span={12}>
              <Button
                type={guestType === "solo" ? "primary" : "default"}
                block
                onClick={() => {
                  setGuestType("solo");
                  setGuestCount({ adults: 1, children: 0, rooms: 1 });
                }}
                className={`rounded-lg h-auto py-2 transition-all ${
                  guestType === "solo" ? "shadow-md" : " "
                }`}
              >
                <UserOutlined className="text-lg mr-1" /> Đi một mình
              </Button>
            </Col>
            <Col span={12}>
              <Button
                type={guestType === "couple" ? "primary" : "default"}
                block
                onClick={() => {
                  setGuestType("couple");
                  setGuestCount({ adults: 2, children: 0, rooms: 1 });
                }}
                className={`rounded-lg h-auto py-2 transition-all ${
                  guestType === "couple" ? "shadow-md" : ""
                }`}
              >
                <UsersRound className="text-lg mr-1" /> Cặp đôi
              </Button>
            </Col>
            <Col span={12}>
              <Button
                type={guestType === "family" ? "primary" : "default"}
                block
                onClick={() => setGuestType("family")}
                className={`rounded-lg h-auto py-2 transition-all ${
                  guestType === "family" ? "shadow-md" : ""
                }`}
              >
                <Users className="text-lg mr-1" /> Gia đình
              </Button>
            </Col>
            <Col span={12}>
              <Button
                type={guestType === "group" ? "primary" : "default"}
                block
                onClick={() => setGuestType("group")}
                className={`rounded-lg h-auto py-2 transition-all ${
                  guestType === "group" ? "shadow-md" : ""
                }`}
              >
                <UsergroupAddOutlined className="text-lg mr-1" /> Đi theo nhóm
              </Button>
            </Col>
          </Row>
        </div>
        {(guestType === "family" || guestType === "group") && (
          <div className="animate-fadeIn">
            <Divider className="my-2 dark:border-gray-600" />{" "}
            <div className="flex justify-between items-center mb-3 p-2 rounded-lg ">
              <div className="flex items-center">
                <span className="text-sm font-medium">Phòng</span>
                <span className="ml-1 text-xs ">(tối thiểu 1)</span>
              </div>
              <div className="flex items-center">
                <Button
                  icon={<MinusOutlined />}
                  size="small"
                  onClick={() => handleGuestCountChange("rooms", "decrease")}
                  disabled={guestCount.rooms <= 1}
                  className="  transition-all"
                />
                <span className="mx-2 min-w-[30px] text-center font-semibold">
                  {guestCount.rooms}
                </span>
                <Button
                  icon={<PlusOutlined />}
                  size="small"
                  onClick={() => handleGuestCountChange("rooms", "increase")}
                  className="  transition-all"
                />
              </div>
            </div>
            <div className="flex justify-between items-center mb-3 p-2 rounded-lg ">
              <div className="flex items-center">
                <span className="text-sm font-medium">Người lớn</span>
                <span className="ml-1 text-xs ">(tối thiểu 1)</span>
              </div>
              <div className="flex items-center">
                <Button
                  icon={<MinusOutlined />}
                  size="small"
                  onClick={() => handleGuestCountChange("adults", "decrease")}
                  disabled={guestCount.adults <= 1}
                  className=" transition-all"
                />
                <span className="mx-2 min-w-[30px] text-center font-semibold">
                  {guestCount.adults}
                </span>
                <Button
                  icon={<PlusOutlined />}
                  size="small"
                  onClick={() => handleGuestCountChange("adults", "increase")}
                  className=" transition-all"
                />
              </div>
            </div>
            <div className="flex justify-between items-center p-2 rounded-lg ">
              <div className="flex items-center">
                <span className="text-sm font-medium">Trẻ em</span>
                <span className="ml-1 text-xs ">(0-17 tuổi)</span>
              </div>
              <div className="flex items-center">
                <Button
                  icon={<MinusOutlined />}
                  size="small"
                  onClick={() => handleGuestCountChange("children", "decrease")}
                  disabled={guestCount.children <= 0}
                  className="  transition-all"
                />
                <span className="mx-2 min-w-[30px] text-center font-semibold">
                  {guestCount.children}
                </span>
                <Button
                  icon={<PlusOutlined />}
                  size="small"
                  onClick={() => handleGuestCountChange("children", "increase")}
                  className=" transition-all"
                />
              </div>
            </div>{" "}
            {guestType === "group" && guestCount.rooms >= 1 && (
              <div className="     p-3 rounded-lg border border-blue-100/70 dark:border-blue-800/30 shadow-sm transform hover:scale-[1.02] transition-transform">
                <p className="text-sm mb-2 flex items-start">
                  <UsergroupAddOutlined className="mr-1   flex-shrink-0" />
                  <span>
                    Cần từ 16 phòng trở lên? Chat với LAVISHSTAY để nhận ưu đãi
                    đặc biệt!
                  </span>
                </p>
                <Button
                  type="primary"
                  icon={<MessageOutlined />}
                  size="small"
                  block
                  className="  transition-colors"
                  onClick={() => console.log("Chat with support")}
                >
                  Chat ngay
                </Button>
              </div>
            )}
          </div>
        )}{" "}
        <div className="flex justify-end ">
          <Button
            type="primary"
            size="middle"
            onClick={() => {
              setGuestPopoverVisible(false);
              form.setFieldsValue({ guests: formatGuestSelection() });
            }}
            className="rounded-lg shadow-sm hover:shadow-md transition-all px-4   "
            icon={<UserCheck />}
          >
            Xác nhận lựa chọn
          </Button>
        </div>
      </Space>
    </div>
  );
  return (
    <Card
      className={`shadow-lg rounded-xl transition-all search-form-container ${className}`}
      style={{
        ...style,
        background: token.colorBgContainer,
        color: token.colorText,
        borderColor: token.colorBorderSecondary,
      }}
      bodyStyle={{ padding: "16px 24px" }}
    >
      {/* Tab Selection */}{" "}
      <Tabs
        activeKey={activeTab}
        onChange={handleTabChange}
        className="mb-3 font-bevietnam"
        items={[
          {
            key: "hotel",
            label: (
              <div className="px-1 relative h-12 flex items-center">
                <div className="flex items-center z-10 relative">
                  <Building2 className="mr-1" />
                  <span className="mr-8">Phòng thường</span>
                </div>
                <div className="absolute bottom-9 right-0">
                  <Tag
                    color="red"
                    className="rounded-lg mt-1 mr-0 discount-tag"
                  >
                    Giảm 500K
                  </Tag>
                </div>
              </div>
            ),
          },
          {
            key: "villa",
            label: (
              <div className="px-1 relative h-12 flex items-center">
                <div className="flex items-center z-10 relative">
                  <HomeOutlined className="mr-1" />
                  <span className="mr-8">Phòng The Level </span>
                </div>
                <div className="absolute bottom-9 right-0">
                  <Tag color="orange" className="rounded-lg mt-1 mr-0 ">
                    Giảm 300K
                  </Tag>
                </div>
              </div>
            ),
          },
        ]}
        tabBarStyle={{
          marginBottom: "12px",
          borderBottom: `1px solid ${token.colorBorderSecondary}`,
        }}
      />
      {/* Search Form */}
      <Form
        form={form}
        layout="vertical"
        onFinish={handleSearch}
        className="font-bevietnam"
      >
        {" "}
        <Row gutter={[16, 16]} align="middle">
          {/* Destination Field */}
          <Col xs={24} sm={24} md={6} lg={7}>
            <Form.Item
              name="destination"
              className="mb-0"
              label={
                <Text strong className="text-sm flex items-center">
                  <EnvironmentOutlined className="mr-1" /> Phòng
                </Text>
              }
            >
              <AutoComplete
                className="search-history-container"
                options={searchedData.map((data) => ({
                  value: data.name,
                }))}
                placeholder="Nhập tên phòng..."
                filterOption={(inputValue, searchedData) =>
                  searchedData!.value
                    .toUpperCase()
                    .indexOf(inputValue.toUpperCase()) !== -1
                }
              />
            </Form.Item>
          </Col>
          {/* Date Range Field */}
          <Col xs={24} sm={24} md={8} lg={8}>
            <Form.Item
              name="dateRange"
              className="mb-0"
              label={
                <Text strong className="text-sm flex items-center">
                  <CalendarOutlined className="mr-1" /> Ngày đến - Ngày về
                </Text>
              }
            >
              {" "}
              <RangePicker
                size="large"
                style={{ width: "100%" }}
                format="DD/MM/YYYY"
                placeholder={["Ngày nhận phòng", "Ngày trả phòng"]}
                className="rounded-lg"
                suffixIcon={<CalendarOutlined className="text-gray-400" />}
                disabledDate={disabledDate}
                popupClassName="date-range-popup"
                getPopupContainer={(trigger) =>
                  trigger.parentElement || document.body
                }
              />
            </Form.Item>
          </Col>{" "}
          {/* Guests Field */}
          <Col xs={24} sm={12} md={5} lg={6}>
            <Form.Item
              name="guests"
              className="mb-0"
              label={
                <Text strong className="text-sm flex items-center">
                  <UserOutlined className="mr-1" /> Số khách
                </Text>
              }
            >
              {" "}
              <Popover
                content={guestPopoverContent}
                trigger="click"
                open={guestPopoverVisible}
                onOpenChange={setGuestPopoverVisible}
                placement="bottomRight"
                overlayClassName="guest-popover"
                overlayStyle={{
                  borderRadius: "12px",
                  padding: 0,
                }}
                getPopupContainer={(trigger) =>
                  trigger.parentElement || document.body
                }
              >
                {" "}
                <Input
                  size="large"
                  placeholder="Số lượng khách"
                  readOnly
                  value={formatGuestSelection()}
                  style={{}}
                  prefix={
                    <span className="flex items-center">
                      {guestType === "solo" && <UserOutlined className="" />}
                      {guestType === "couple" && (
                        <>
                          <UserOutlined className="" />
                          <UserOutlined className=" -ml-1" />
                        </>
                      )}
                      {guestType === "family" && (
                        <>
                          <UserOutlined className="" />
                          <UserOutlined className=" -ml-1" />
                          <UserOutlined className=" -ml-1 text-xs" />
                        </>
                      )}
                      {guestType === "group" && (
                        <>
                          <UserOutlined className="" />
                          <UserOutlined className=" -ml-1" />
                          <UserOutlined className=" -ml-1" />
                          <UserOutlined className=" -ml-1" />
                        </>
                      )}
                    </span>
                  }
                  className="rounded-lg cursor-pointer transition-colors"
                  onClick={() => setGuestPopoverVisible(true)}
                />
              </Popover>
            </Form.Item>
          </Col>
          {/* Search Button */}{" "}
          <Col
            xs={24}
            sm={12}
            md={activeTab === "hotel" ? 3 : 3}
            lg={activeTab === "hotel" ? 3 : 3}
          >
            {" "}
            <Form.Item
              className="mb-0"
              label={<div className="opacity-0 text-sm">.</div>}
            >
              {" "}
              <ButtonSearch
                type="submit"
                text="Tìm kiếm"
                className="w-full search-button"
                icon={<SearchOutlined className="search-icon text-xl" />}
                style={{
                  borderColor: token.colorPrimary,
                }}
              />
            </Form.Item>
          </Col>
        </Row>
        {/* Special deal tag (only for hotel tab) */}
        {activeTab === "hotel" && (
          <div className="mt-3">
            <Tag
              color="orange"
              icon={<FireOutlined />}
              className="rounded-lg px-2 py-1"
            >
              Ưu đãi đặc biệt: Giảm 15% cho đặt phòng trước 7 ngày
            </Tag>
          </div>
        )}
      </Form>
    </Card>
  );
};

export default SearchForm;
