import React, { useState, useMemo } from 'react';
import { Layout, message, Modal, Card, Tag, Button, Space, Spin, Timeline, List, Avatar, Empty, Alert, Typography, Row, Col, Flex, Divider, Statistic } from 'antd';
import { UnorderedListOutlined, UserOutlined, CalendarOutlined, CheckCircleOutlined, CloseCircleOutlined, SyncOutlined, QuestionCircleOutlined, TeamOutlined, HomeOutlined, ApartmentOutlined } from '@ant-design/icons';
import { useNavigate } from 'react-router-dom';
import { useRoomManagementStore } from '../../../stores/roomManagementStore';
import { RoomFilters } from '../../../types/room';
import FilterBar from '../../../components/room-management/FilterBar';
import RoomGridView from '../../../components/room-management/RoomGridView';
import RoomTimelineView from '../../../components/room-management/RoomTimelineView';
import { useGetReceptionRooms, useGetReceptionRoomTypes, useGetAvailableRooms, useGetRoomDetails } from '../../../hooks/useReception';
import { statusOptions } from '../../../constants/roomStatus';
import dayjs from 'dayjs';
import 'dayjs/locale/vi';

dayjs.locale('vi');

const { Content } = Layout;
const { Title, Text, Paragraph } = Typography;

const RoomManagementDashboard: React.FC = () => {
    const navigate = useNavigate();
    const { viewMode } = useRoomManagementStore();
    const [selectedRoomId, setSelectedRoomId] = useState<string | null>(null);
    const [roomDetailVisible, setRoomDetailVisible] = useState(false);
    const [currentFilters, setCurrentFilters] = useState<RoomFilters>({});
    const [multiSelectMode, setMultiSelectMode] = useState(false);
    const [selectedRoomIds, setSelectedRoomIds] = useState<Set<string>>(new Set());
    const [guestCount, setGuestCount] = useState(2);

    // --- API HOOKS ---
    const { data: roomTypesData } = useGetReceptionRoomTypes();
    const roomTypes = roomTypesData?.data || [];

    const { data: allRoomsData, isLoading: isLoadingRooms } = useGetReceptionRooms({ include: 'room_type' });
    const masterRoomList = useMemo(() => allRoomsData?.data || [], [allRoomsData]);

    const hasDateRange = currentFilters.dateRange && currentFilters.dateRange.length === 2;
    const { data: availableRoomsData, isLoading: isLoadingAvailable } = useGetAvailableRooms(
        hasDateRange ? {
            check_in_date: currentFilters.dateRange![0],
            check_out_date: currentFilters.dateRange![1],
        } : undefined
    );

    const { data: roomDetailsData, isLoading: isDetailsLoading, error: detailsError } = useGetRoomDetails(selectedRoomId);

    const availableRoomIdSet = useMemo(() => {
        if (!hasDateRange) return null;
        const ids = new Set<string>();
        (availableRoomsData?.data || []).forEach((roomType: any) => {
            roomType.available_rooms?.forEach((room: any) => ids.add(room.room_id));
        });
        return ids;
    }, [hasDateRange, availableRoomsData]);

    const filteredRoomsToDisplay = useMemo(() => {
        return masterRoomList.filter((room: any) => {
            if (availableRoomIdSet && !availableRoomIdSet.has(room.id)) {
                return false;
            }
            if (currentFilters.roomType && String(room.room_type?.id) !== String(currentFilters.roomType)) {
                return false;
            }
            return true;
        });
    }, [masterRoomList, availableRoomIdSet, currentFilters.roomType]);

    const isLoading = isLoadingRooms || (hasDateRange && isLoadingAvailable);

    const handleSearch = (searchFilters: RoomFilters) => {
        setCurrentFilters(searchFilters);
    };

    const handleRoomClick = (room: any) => {
        setSelectedRoomId(room.id);
        setRoomDetailVisible(true);
    };

    const handleMultiSelectModeChange = (enabled: boolean) => {
        setMultiSelectMode(enabled);
        if (!enabled) {
            setSelectedRoomIds(new Set());
        }
    };

    const handleRoomSelect = (roomId: string, selected: boolean) => {
        setSelectedRoomIds(prev => {
            const newSet = new Set(prev);
            if (selected) newSet.add(roomId);
            else newSet.delete(roomId);
            return newSet;
        });
    };

    const handleBulkRoomSelect = (roomIds: string[], select: boolean) => {
        setSelectedRoomIds(prev => {
            const newSet = new Set(prev);
            roomIds.forEach(id => {
                if (select) newSet.add(id);
                else newSet.delete(id);
            });
            return newSet;
        });
    };

    const handleProceedToBooking = () => {
        if (selectedRoomIds.size === 0) {
            message.warning('Vui lòng chọn ít nhất một phòng');
            return;
        }
        const selectedRoomsList = masterRoomList.filter((room: any) => selectedRoomIds.has(room.id));

        navigate('/reception/confirm-representative-payment', {
            state: {
                selectedRooms: selectedRoomsList,
                guestCount,
                checkInDate: hasDateRange ? currentFilters.dateRange![0] : undefined,
                checkOutDate: hasDateRange ? currentFilters.dateRange![1] : undefined,
                bookingData: hasDateRange ? {
                    checkInDate: currentFilters.dateRange![0],
                    checkOutDate: currentFilters.dateRange![1],
                    adults: 1,
                    children: []
                } : undefined
            }
        });
    };

    const handleCloseModal = () => {
        setRoomDetailVisible(false);
        setSelectedRoomId(null);
    };

    const renderRoomDetailModal = () => {
        const room = roomDetailsData?.data?.room;
        const currentBooking = roomDetailsData?.data?.current_booking;
        const bookingHistory = roomDetailsData?.data?.booking_history || [];
        const statusStyles: { [key: string]: { color: string; icon: React.ReactNode; text: string } } = {
            Completed: { color: 'green', icon: <CheckCircleOutlined />, text: 'Hoàn thành' },
            Confirmed: { color: 'blue', icon: <SyncOutlined spin />, text: 'Đã xác nhận' },
            Operational: { color: 'cyan', icon: <SyncOutlined spin />, text: 'Đang ở' },
            Cancelled: { color: 'red', icon: <CloseCircleOutlined />, text: 'Đã hủy' },
            default: { color: 'default', icon: <QuestionCircleOutlined />, text: 'Không xác định' },
        };

        return (
            <Modal
                title={<Flex align="center" gap="middle"><HomeOutlined /><Title level={3} className="!m-0">Chi tiết phòng {room?.name}</Title></Flex>}
                open={roomDetailVisible}
                onCancel={handleCloseModal}
                footer={<Button key="close" type="primary" onClick={handleCloseModal} size="large">Đóng</Button>}
                width={1000}
                centered
            >
                {isDetailsLoading && <div className="text-center p-20"><Spin size="large" /></div>}
                {detailsError && <Alert message="Lỗi" description="Không thể tải chi tiết phòng." type="error" showIcon />}
                {room && !isDetailsLoading && (
                    <Row gutter={[24, 24]}>
                        <Col span={10}>
                            <Space direction="vertical" size="large" className="w-full">
                                <Card title="Thông tin phòng" >
                                    <Flex justify="space-between">
                                        <Text strong>Loại phòng:</Text>
                                        <Text>{room.room_type_name}</Text>
                                    </Flex>
                                    <Divider className="my-2" />
                                    <Flex justify="space-between">
                                        <Text strong>Tầng:</Text>
                                        <Text>{room.floor_id}</Text>
                                    </Flex>
                                    <Divider className="my-2" />
                                    <Flex justify="space-between">
                                        <Text strong>Trạng thái:</Text>
                                        <Tag color={statusOptions.find(s => s.value === room.status)?.color || 'default'}>
                                            {statusOptions.find(s => s.value === room.status)?.label || room.status}
                                        </Tag>
                                    </Flex>
                                </Card>

                                <Card title="Thông tin hiện tại" >
                                    {currentBooking ? (
                                        <Space direction="vertical" className="w-full">
                                            <Flex align="center" gap={8}>
                                                <UserOutlined className="text-blue-600" />
                                                <Text strong>{currentBooking.guest_name}</Text>
                                            </Flex>
                                            <Flex align="center" gap={8}>
                                                <TeamOutlined className="text-blue-600" />
                                                <Text>{currentBooking.adults} người lớn, {currentBooking.children || 0} trẻ em</Text>
                                            </Flex>
                                            <Flex align="center" gap={8}>
                                                <CalendarOutlined className="text-blue-600" />
                                                <Text>{dayjs(currentBooking.check_in_date).format('DD/MM/YYYY')} - {dayjs(currentBooking.check_out_date).format('DD/MM/YYYY')}</Text>
                                            </Flex>
                                        </Space>
                                    ) : (
                                        <Empty image={Empty.PRESENTED_IMAGE_SIMPLE} description="Phòng hiện đang trống" />
                                    )}
                                </Card>
                            </Space>
                        </Col>

                        <Col span={14}>
                            <Card title="Lịch sử & Đặt phòng tương lai" >
                                {bookingHistory.length > 0 ? (
                                    <div className="max-h-[400px] overflow-y-auto pr-2">
                                        <List
                                            itemLayout="horizontal"
                                            dataSource={bookingHistory}
                                            renderItem={(item: any) => {
                                                const style = statusStyles[item.booking_status] || statusStyles.default;
                                                return (
                                                    <List.Item>
                                                        <List.Item.Meta
                                                            avatar={<Avatar size="large" icon={style.icon} style={{ backgroundColor: style.color }} />}
                                                            title={<Text strong>{item.guest_name}</Text>}
                                                            description={
                                                                <Flex justify="space-between" align="center">
                                                                    <Text type="secondary">
                                                                        {dayjs(item.check_in_date).format('DD/MM/YY')} → {dayjs(item.check_out_date).format('DD/MM/YY')}
                                                                    </Text>
                                                                    <Tag color={style.color}>{style.text}</Tag>
                                                                </Flex>
                                                            }
                                                        />
                                                    </List.Item>
                                                );
                                            }}
                                        />
                                    </div>
                                ) : (
                                    <Empty description="Chưa có lịch sử đặt phòng" />
                                )}
                            </Card>
                        </Col>
                    </Row>
                )}
            </Modal>
        );
    };

    return (
        <Layout >
            <Content style={{ padding: '24px' }}>
                <Card style={{ marginBottom: 24 }}>
                    <Flex justify="space-between" align="start">
                        <div>
                            <Title level={2} className="!mt-0">Quản lý phòng khách sạn</Title>
                            <Paragraph type="secondary">Quản lý trạng thái phòng, đặt phòng và lịch sử khách hàng.</Paragraph>
                        </div>
                        <Button type="primary" size="large" icon={<UnorderedListOutlined />} onClick={() => navigate('/reception/booking-management')}>
                            Quản lý đặt phòng
                        </Button>
                    </Flex>
                </Card>

                <FilterBar
                    roomTypes={roomTypes}
                    onSearch={handleSearch}
                    loading={isLoading}
                    selectedRoomsCount={selectedRoomIds.size}
                    onMultiSelectModeChange={handleMultiSelectModeChange}
                    multiSelectMode={multiSelectMode}
                />

                <Card>
                    {viewMode === 'grid' ? (
                        <RoomGridView
                            rooms={filteredRoomsToDisplay}
                            allRooms={masterRoomList}
                            loading={isLoading}
                            onRoomClick={handleRoomClick}
                            multiSelectMode={multiSelectMode}
                            selectedRooms={selectedRoomIds}
                            onRoomSelect={handleRoomSelect}
                            onBulkRoomSelect={handleBulkRoomSelect}
                            navigate={navigate}
                            hasDateFilter={hasDateRange}
                            checkInDate={hasDateRange ? currentFilters.dateRange![0] : undefined}
                            checkOutDate={hasDateRange ? currentFilters.dateRange![1] : undefined}
                        />
                    ) : (
                        <RoomTimelineView
                            rooms={filteredRoomsToDisplay}
                            loading={isLoading}
                            onEventClick={() => { }}
                            onDateSelect={() => { }}
                        />
                    )}
                </Card>

                {renderRoomDetailModal()}
            </Content>
        </Layout>
    );
};

export default RoomManagementDashboard;
