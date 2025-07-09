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
  Affix,
  Input,
  message,
  Select,
  Tag,
  Badge,
} from "antd";
import {
  SearchOutlined,
  CalendarOutlined,
  UserOutlined,
  PlusOutlined,
  MinusOutlined,
  DeleteOutlined,
  HomeOutlined
} from "@ant-design/icons";

import { useNavigate } from "react-router-dom";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";
import type { RangePickerProps } from "antd/es/date-picker";
import { useSelector, useDispatch } from 'react-redux';
import { RootState } from '../store';
import {
  setDateRange,
  addRoom,
  removeRoom,
  updateRoomGuests,
  updateRoomChildAge,
  setSearchResults,
  setSearchLoading,
  setSearchError
} from '../store/slices/searchSlice';
import { searchService } from '../services/searchService';

dayjs.extend(customParseFormat);

const { RangePicker } = DatePicker;
const { Text } = Typography;
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
  const navigate = useNavigate();
  const dispatch = useDispatch();
  const searchData = useSelector((state: RootState) => state.search);

  const [guestPopoverVisible, setGuestPopoverVisible] = useState<boolean>(false);
  const [isSearching, setIsSearching] = useState<boolean>(false);

  // Safe access to rooms array
  const safeRooms = searchData.rooms || [{ adults: 2, children: 0, childrenAges: [] }];

  // Auto-update dates to today and tomorrow if dateRange is in the past
  useEffect(() => {
    const now = dayjs();
    const tomorrow = dayjs().add(1, 'day');

    // Check if current dateRange is in the past or not set
    if (!searchData.dateRange ||
      !Array.isArray(searchData.dateRange) ||
      searchData.dateRange.length < 2 ||
      !searchData.dateRange[0] ||
      dayjs(searchData.dateRange[0]).isBefore(now, 'day')) {

      dispatch(setDateRange([now.format('YYYY-MM-DD'), tomorrow.format('YYYY-MM-DD')]));
    }
  }, []); // Run only on component mount

  // Format guest summary
  const formatGuestSummary = useCallback(() => {
    const totalAdults = safeRooms.reduce((sum, room) => sum + room.adults, 0);
    const totalChildren = safeRooms.reduce((sum, room) => sum + room.children, 0);
    const roomCount = safeRooms.length;
    if (roomCount === 1) {
      return `${totalAdults + totalChildren} khách`;
    }
    return `${roomCount} phòng, ${totalAdults + totalChildren} khách`;
  }, [safeRooms]);

  // Handle room guest count change
  const handleRoomGuestChange = useCallback((roomIndex: number, type: 'adults' | 'children', operation: 'increase' | 'decrease') => {
    dispatch(updateRoomGuests({ roomIndex, type, operation }));
  }, [dispatch]);

  // Handle child age change
  const handleChildAgeChange = useCallback((roomIndex: number, childIndex: number, age: number) => {
    dispatch(updateRoomChildAge({ roomIndex, childIndex, age }));
  }, [dispatch]);

  // Handle add room
  const handleAddRoom = useCallback(() => {
    dispatch(addRoom());
  }, [dispatch]);

  // Handle remove room
  const handleRemoveRoom = useCallback((roomIndex: number) => {
    dispatch(removeRoom(roomIndex));
  }, [dispatch]);

  // Initialize form with search data
  useEffect(() => {
    const formValues: any = {};

    if (searchData.dateRange && Array.isArray(searchData.dateRange)) {
      // Ensure dateRange contains valid dayjs objects
      const isValidDateRange = searchData.dateRange.every((date: any) =>
        date && typeof date.isValid === 'function' && date.isValid()
      );

      if (isValidDateRange) {
        formValues.dateRange = searchData.dateRange;
      }
    }

    formValues.guests = formatGuestSummary();
    form.setFieldsValue(formValues);
  }, [searchData, form, formatGuestSummary]);

  // Handle search form submission
  const handleSearch = useCallback(async (values: any) => {
    try {
      setIsSearching(true);
      dispatch(setSearchLoading(true));

      // Update date range if changed
      if (values.dateRange && values.dateRange !== searchData.dateRange) {
        dispatch(setDateRange(values.dateRange));
      }

      // Validate guest details
      if (safeRooms.length === 0) {
        message.error('Vui lòng chọn ít nhất 1 phòng');
        return;
      }

      // Build search request with new room structure
      const totalAdults = safeRooms.reduce((sum, room) => sum + room.adults, 0);
      const totalChildren = safeRooms.reduce((sum, room) => sum + room.children, 0);

      const searchRequest = {
        dateRange: values.dateRange || searchData.dateRange,
        guests: totalAdults + totalChildren,
        guestDetails: {
          adults: totalAdults,
          children: totalChildren,
          childrenAges: safeRooms.flatMap(room => room.childrenAges),
          rooms: safeRooms,
          totalRooms: safeRooms.length
        },
        rooms: safeRooms,
        totalRooms: safeRooms.length,
        guestType: 'couple' as const,
        location: searchData.location,
        checkIn: searchData.checkIn,
        checkOut: searchData.checkOut
      };

      console.log('🔍 Searching with request:', searchRequest);

      // Call search API
      const results = await searchService.searchRooms(searchRequest);
      console.log('📥 Search results:', results);

      // Save results to Redux store
      dispatch(setSearchResults(results));

      // Close popover
      setGuestPopoverVisible(false);

      // Navigate to search results page
      navigate('/search');

      // Call parent callback if provided
      if (onSearch) {
        onSearch(results);
      }

      message.success(`Tìm thấy ${results.total || 0} phòng phù hợp cho bạn`);
    } catch (error: any) {
      dispatch(setSearchError(error.message || 'Có lỗi xảy ra khi tìm kiếm'));
      message.error(error.message || 'Có lỗi xảy ra khi tìm kiếm');
      console.error('❌ Search error:', error);
    } finally {
      setIsSearching(false);
      dispatch(setSearchLoading(false));
    }
  }, [searchData, navigate, onSearch, dispatch, safeRooms]);

  // Guest selection dropdown content
  const guestPopoverContent = (
    <div className="p-4" style={{ width: "400px", maxHeight: "500px", overflowY: "auto" }}>
      <Space direction="vertical" size="middle" style={{ width: "100%" }}>
        {/* Summary - compact */}
        <div className="text-center">
          <div className="bg-blue-50 p-3 rounded-lg">
            <div className="flex justify-center gap-6 text-sm">
              <div className="text-center">
                <div className="text-lg font-bold text-blue-600">{safeRooms.length}</div>
                <div className="text-gray-600">Phòng</div>
              </div>
              <div className="text-center">
                <div className="text-lg font-bold text-green-600">{safeRooms.reduce((sum, room) => sum + room.adults, 0)}</div>
                <div className="text-gray-600">Người lớn</div>
              </div>
              <div className="text-center">
                <div className="text-lg font-bold text-orange-600">{safeRooms.reduce((sum, room) => sum + room.children, 0)}</div>
                <div className="text-gray-600">Trẻ em</div>
              </div>
            </div>
          </div>
        </div>

        {/* Room configurations - compact */}
        <div>
          <div className="flex items-center justify-between mb-3">
            <Text strong className="text-gray-800">Phòng và khách</Text>
            <Button
              type="dashed"
              icon={<PlusOutlined />}
              onClick={handleAddRoom}
              disabled={safeRooms.length >= 6}
              size="small"
            >
              Thêm
            </Button>
          </div>

          {/* Max rooms warning */}
          {safeRooms.length >= 6 && (
            <div className="mb-3 p-2 bg-yellow-50 border border-yellow-200 rounded text-center">
              <Text className="text-xs text-yellow-700">
                ⚠️ Tối đa 6 phòng. Cần thêm?
                <Button type="link" size="small" className="p-0 ml-1 h-auto text-xs">
                  Hotline: 1900-XXX-XXX
                </Button>
              </Text>
            </div>
          )}

          {/* Room list - compact */}
          <div className="space-y-3">
            {safeRooms.map((room, roomIndex) => (
              <div key={roomIndex} className="border border-gray-200 rounded-lg p-3">
                <div className="flex items-center justify-between mb-2">
                  <div className="flex items-center gap-2">
                    <HomeOutlined className="text-blue-600" />
                    <span className="text-sm font-medium">Phòng {roomIndex + 1}</span>
                    <Tag color="blue">
                      {room.adults + room.children} khách
                    </Tag>
                  </div>
                  {safeRooms.length > 1 && (
                    <Button
                      type="text"
                      danger
                      size="small"
                      icon={<DeleteOutlined />}
                      onClick={() => handleRemoveRoom(roomIndex)}
                      className="w-6 h-6"
                    />
                  )}
                </div>

                {/* Adults & Children in same row */}
                <div className="space-y-2">
                  <div className="flex justify-between items-center">
                    <span className="text-sm text-gray-700">Người lớn</span>
                    <div className="flex items-center gap-2">
                      <Button
                        icon={<MinusOutlined />}
                        size="small"
                        onClick={() => handleRoomGuestChange(roomIndex, 'adults', 'decrease')}
                        disabled={room.adults <= 1}
                        className="w-6 h-6"
                      />
                      <span className="w-6 text-center text-sm font-medium">{room.adults}</span>
                      <Button
                        icon={<PlusOutlined />}
                        size="small"
                        onClick={() => handleRoomGuestChange(roomIndex, 'adults', 'increase')}
                        disabled={room.adults + room.children >= 6}
                        className="w-6 h-6"
                      />
                    </div>
                  </div>

                  <div className="flex justify-between items-center">
                    <span className="text-sm text-gray-700">Trẻ em (0-12)</span>
                    <div className="flex items-center gap-2">
                      <Button
                        icon={<MinusOutlined />}
                        size="small"
                        onClick={() => handleRoomGuestChange(roomIndex, 'children', 'decrease')}
                        disabled={room.children <= 0}
                        className="w-6 h-6"
                      />
                      <span className="w-6 text-center text-sm font-medium">{room.children}</span>
                      <Button
                        icon={<PlusOutlined />}
                        size="small"
                        onClick={() => handleRoomGuestChange(roomIndex, 'children', 'increase')}
                        disabled={room.adults + room.children >= 6}
                        className="w-6 h-6"
                      />
                    </div>
                  </div>

                  {/* Children Ages - compact */}
                  {room.children > 0 && (
                    <div className="mt-2">
                      <div className="text-xs text-gray-600 mb-1">Tuổi trẻ em:</div>
                      <div className="flex flex-wrap gap-2">
                        {Array.from({ length: room.children }, (_, childIndex) => {
                          const childAge = room.childrenAges?.[childIndex]?.age || 8;
                          return (
                            <div key={childIndex} className="flex items-center gap-1">
                              <span className="text-xs text-gray-500">{childIndex + 1}:</span>
                              <Select
                                value={childAge}
                                style={{ width: 60 }}
                                size="small"
                                onChange={(age) => handleChildAgeChange(roomIndex, childIndex, age)}
                              >
                                {Array.from({ length: 13 }, (_, ageIndex) => (
                                  <Option key={ageIndex} value={ageIndex}>
                                    {ageIndex}
                                  </Option>
                                ))}
                              </Select>
                            </div>
                          );
                        })}
                      </div>
                    </div>
                  )}

                  {/* Room limit warning - compact */}
                  {room.adults + room.children >= 6 && (
                    <div className="mt-2 p-2 bg-orange-50 border border-orange-200 rounded">
                      <Text className="text-xs text-orange-700">
                        Đã đạt tối đa 6 khách/phòng
                      </Text>
                    </div>
                  )}
                </div>
              </div>
            ))}
          </div>
        </div>



        {/* Help message - compact */}
        <div className="text-center text-xs text-gray-500">
          Cần hỗ trợ?
          <Button type="link" size="small" className="p-0 ml-1 h-auto text-xs">
            Email: support@lavishstay.com
          </Button>
        </div>
      </Space>
    </div>
  );

  return (
    <Affix offsetTop={88}>
      <div
        className={`mx-auto max-w-4xl ${className}`}
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
                    onChange={(dates) => {
                      if (dates && dates[0] && dates[1]) {
                        // Auto-focus guest field after selecting dates
                        setTimeout(() => {
                          setGuestPopoverVisible(true);
                        }, 300);
                      }
                    }}
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
                      value={formatGuestSummary()}
                      prefix={<UserOutlined className="text-gray-500" />}
                      suffix={
                        safeRooms.length > 1 && (
                          <Badge
                            count={safeRooms.length}
                            size="small"
                            style={{ backgroundColor: '#1890ff' }}
                          />
                        )
                      }
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
                    loading={isSearching}
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
