import React, { useState, useMemo, useCallback } from 'react';
import {
    Layout, message, Modal, Card, Button, Space, Spin, List, Avatar, Empty,
    Alert, Typography, Row, Col, Flex, Divider, Statistic, Radio, Collapse, Descriptions, Tabs, Select, Badge
} from 'antd';
import { Tag } from 'antd';
import { CheckCard } from '@ant-design/pro-components';
import {
    UnorderedListOutlined, UserOutlined, CalendarOutlined, CheckCircleOutlined, CloseCircleOutlined,
    SyncOutlined, QuestionCircleOutlined, TeamOutlined, HomeOutlined, ApartmentOutlined, WalletOutlined,
    InfoCircleOutlined, StopOutlined
} from '@ant-design/icons';
import { useNavigate } from 'react-router-dom';
import { useRoomManagementStore } from '../../../stores/roomManagementStore';
import { RoomFilters } from '../../../types/room';
import { ProFormDigit, ProFormGroup } from '@ant-design/pro-components';

import FilterBar from '../../../components/room-management/FilterBar';
import RoomCheckCardList from '../../../components/room-management/RoomCheckCardList';
import RoomTimelineView from '../../../components/room-management/RoomTimelineView';
import { useGetReceptionRooms, useGetReceptionRoomTypes, useGetAvailableRooms, useGetRoomDetails } from '../../../hooks/useReception';
import { statusOptions } from '../../../constants/roomStatus';
import dayjs from 'dayjs';
import 'dayjs/locale/vi';
import { formatCurrency } from '../../../utils/helpers';

dayjs.locale('vi');

const { Content } = Layout;
const { Title, Text, Paragraph } = Typography;
const { Panel } = Collapse;

const RoomManagementDashboard: React.FC = () => {
    const navigate = useNavigate();
    const { viewMode } = useRoomManagementStore();
    const [selectedRoomId, setSelectedRoomId] = useState<string | null>(null);
    const [roomDetailVisible, setRoomDetailVisible] = useState(false);
    const [bookingModalVisible, setBookingModalVisible] = useState(false);
    const [currentFilters, setCurrentFilters] = useState<RoomFilters>({});
    const [selectedRoomIds, setSelectedRoomIds] = useState<Set<string>>(new Set());
    const [selectedPackages, setSelectedPackages] = useState<Record<string, number>>({});
    const [guestDetails, setGuestDetails] = useState<Record<string, { adults: number; children: number; childrenAges: number[] }>>({});

    const handleGuestDetailChange = (roomId: string, field: 'adults' | 'children' | 'childrenAges', value: number | number[], childIndex?: number) => {
        setGuestDetails(prev => {
            const current = prev[roomId] || { adults: 1, children: 0, childrenAges: [] };
            let updated = { ...current };
            if (field === 'adults') {
                updated.adults = value as number;
            } else if (field === 'children') {
                updated.children = value as number;
                updated.childrenAges = updated.childrenAges.slice(0, updated.children);
            } else if (field === 'childrenAges' && typeof childIndex === 'number') {
                const ages = [...updated.childrenAges];
                ages[childIndex] = value as number;
                updated.childrenAges = ages;
            }
            return { ...prev, [roomId]: updated };
        });
    };

    // --- API HOOKS ---
    const { data: roomTypesData } = useGetReceptionRoomTypes();
    const roomTypes = roomTypesData?.data || [];

    const { data: allRoomsData, isLoading: isLoadingRooms } = useGetReceptionRooms({ ...currentFilters, include: 'room_type' });
    const masterRoomList = useMemo(() => allRoomsData?.data || [], [allRoomsData]);

    const hasDateRange = currentFilters.dateRange && currentFilters.dateRange.length === 2;
    const { data: availableRoomsData, isLoading: isLoadingAvailable } = useGetAvailableRooms(
        hasDateRange ? {
            check_in_date: currentFilters.dateRange![0],
            check_out_date: currentFilters.dateRange![1],
        } : undefined,
        {
            enabled: hasDateRange,
        }
    );

    const { data: roomDetailsData, isLoading: isDetailsLoading, error: detailsError } = useGetRoomDetails(selectedRoomId);

    const availableRoomIdSet = useMemo(() => {
        if (!hasDateRange || !availableRoomsData) return null;
        const ids = new Set<string>();
        (availableRoomsData.data || []).forEach((roomType: any) => {
            roomType.available_rooms?.forEach((room: any) => ids.add(room.room_id.toString()));
        });
        return ids;
    }, [hasDateRange, availableRoomsData]);

    const filteredRoomsToDisplay = useMemo(() => {
        if (availableRoomIdSet) {
            return masterRoomList.filter((room: any) => availableRoomIdSet.has(room.id.toString()));
        }
        return masterRoomList;
    }, [masterRoomList, availableRoomIdSet]);

    const isLoading = isLoadingRooms || (hasDateRange && isLoadingAvailable);

    const handleSearch = useCallback((searchFilters: RoomFilters) => {
        setCurrentFilters(searchFilters);
        setSelectedRoomIds(new Set());
        setSelectedPackages({});
    }, []);

    const handleViewDetails = useCallback((roomId: string) => {
        setSelectedRoomId(roomId);
        setRoomDetailVisible(true);
    }, []);

    const handleRoomSelect = useCallback((roomId: string, selected: boolean) => {
        setSelectedRoomIds(prev => {
            const newSet = new Set(prev);
            if (selected) newSet.add(roomId);
            else newSet.delete(roomId);
            return newSet;
        });
    }, []);

    const handleBulkRoomSelect = useCallback((roomIds: string[], select: boolean) => {
        setSelectedRoomIds(prev => {
            const newSet = new Set(prev);
            roomIds.forEach(id => {
                if (select) newSet.add(id);
                else newSet.delete(id);
            });
            return newSet;
        });
    }, []);

    const handleProceedToBooking = () => {
        if (selectedRoomIds.size === 0) {
            message.warning('Vui lòng chọn ít nhất một phòng.');
            return;
        }
        if (!hasDateRange) {
            message.error('Vui lòng chọn ngày nhận và trả phòng để tiếp tục.');
            return;
        }
        const initialPackages: Record<string, number> = {};
        const selectedRoomsList = masterRoomList.filter((room: any) => selectedRoomIds.has(room.id));
        const groupedByRoomType = groupRoomsByType(selectedRoomsList);

        const initialGuestDetails: Record<string, { adults: number; children: number; childrenAges: number[] }> = {};
        selectedRoomsList.forEach((room: any) => {
            initialGuestDetails[room.id] = { adults: 1, children: 0, childrenAges: [] };
        });
        setGuestDetails(initialGuestDetails);

        Object.keys(groupedByRoomType).forEach(roomTypeId => {
            const roomTypeData = availableRoomsData?.data.find((rt: any) => rt.room_type_id.toString() === roomTypeId);
            if (roomTypeData && roomTypeData.package_options && roomTypeData.package_options.length > 0) {
                const cheapestPackage = roomTypeData.package_options.reduce((prev: any, curr: any) =>
                    prev.price_per_room_per_night < curr.price_per_room_per_night ? prev : curr
                );
                initialPackages[roomTypeId] = cheapestPackage.package_id;
            }
        });
        setSelectedPackages(initialPackages);
        setBookingModalVisible(true);
    };

    const handleConfirmAndPay = () => {
        const selectedRoomsList = masterRoomList.filter((room: any) => selectedRoomIds.has(room.id));

        // Validate số lượng khách từng phòng
        for (const room of selectedRoomsList) {
            const details = guestDetails[room.id] || { adults: 1, children: 0, childrenAges: [] };
            const roomTypeData = availableRoomsData.data.find((rt: any) => rt.room_type_id.toString() === room.room_type.id.toString());
            const maxAdults = roomTypeData?.max_adults || 2;
            const maxChildren = roomTypeData?.max_children || 4;

            if (details.adults > maxAdults) {
                message.error(`Phòng ${room.name} vượt quá số lượng người lớn cho phép (tối đa ${maxAdults}).`);
                return;
            }
            if (details.children > maxChildren) {
                message.error(`Phòng ${room.name} vượt quá số lượng trẻ em cho phép (tối đa ${maxChildren}).`);
                return;
            }
        }

        // Ensure childrenAges array is always filled to match children count
        const bookingData = {
            checkInDate: currentFilters.dateRange![0],
            checkOutDate: currentFilters.dateRange![1],
            guestRooms: selectedRoomsList.map(room => {
                const details = guestDetails[room.id] || { adults: 1, children: 0, childrenAges: [] };
                // Fill childrenAges with default age if not set
                const childrenAges = Array.from({ length: details.children }, (_, idx) => details.childrenAges[idx] || 8);
                return {
                    adults: details.adults,
                    children: childrenAges.map(age => ({ age }))
                };
            }),
            selectedPackages,
            availableRoomsData: availableRoomsData,
            rooms: selectedRoomsList.map(room => {
                const details = guestDetails[room.id] || { adults: 1, children: 0, childrenAges: [] };
                const childrenAges = Array.from({ length: details.children }, (_, idx) => details.childrenAges[idx] || 8);
                return {
                    room_id: room.id,
                    package_id: selectedPackages[room.room_type.id.toString()],
                    room_type_id: room.room_type.id, // Ensure room_type_id is included
                    adults: details.adults,
                    children: childrenAges.map(age => ({ age }))
                };
            })
        };

        const navigateState = {
            state: {
                selectedRooms: selectedRoomsList,
                bookingData,
            }
        };

        console.log("Navigating to ConfirmRepresentativePayment with state:", navigateState);

        navigate('/reception/confirm-representative-payment', navigateState);
    };


    const handleCloseModal = () => {
        setRoomDetailVisible(false);
        setSelectedRoomId(null);
    };

    const groupRoomsByType = (rooms: any[]) => {
        return rooms.reduce((acc, room) => {
            const typeId = room.room_type.id.toString();
            if (!acc[typeId]) {
                acc[typeId] = {
                    roomTypeName: room.room_type.name,
                    rooms: [],
                };
            }
            acc[typeId].rooms.push(room);
            return acc;
        }, {} as Record<string, { roomTypeName: string; rooms: any[] }>);
    };

    const renderPolicy = (policy: any, type: 'cancellation' | 'deposit' | 'check_out') => {
        if (!policy) return <Text type="secondary">Không có thông tin.</Text>;

        const descriptions = {
            cancellation: (
                <>
                    <Descriptions.Item label="Tên chính sách">{policy.name}</Descriptions.Item>
                    <Descriptions.Item label="Huỷ miễn phí trước">{policy.free_cancellation_days} ngày</Descriptions.Item>
                    <Descriptions.Item label="Phí huỷ">{policy.penalty_percentage > 0 ? `${policy.penalty_percentage}%` : formatCurrency(policy.penalty_fixed_amount_vnd)}</Descriptions.Item>
                    <Descriptions.Item label="Mô tả" span={3}>{policy.description}</Descriptions.Item>
                </>
            ),
            deposit: (
                <>
                    <Descriptions.Item label="Tên chính sách">{policy.name}</Descriptions.Item>
                    <Descriptions.Item label="Phần trăm cọc">{policy.deposit_percentage}%</Descriptions.Item>
                    <Descriptions.Item label="Số tiền cọc cố định">{formatCurrency(policy.deposit_fixed_amount_vnd)}</Descriptions.Item>
                    <Descriptions.Item label="Mô tả" span={3}>{policy.description}</Descriptions.Item>
                </>
            ),
            check_out: (
                <>
                    <Descriptions.Item label="Tên chính sách">{policy.name}</Descriptions.Item>
                    <Descriptions.Item label="Giờ trả phòng">{policy.standard_check_out_time}</Descriptions.Item>
                    <Descriptions.Item label="Phí trả phòng trễ">{formatCurrency(policy.late_check_out_fee_vnd)}</Descriptions.Item>
                    <Descriptions.Item label="Mô tả" span={3}>{policy.description}</Descriptions.Item>
                </>
            ),
        };

        return (
            <Descriptions bordered size="small" column={1}>
                {descriptions[type]}
            </Descriptions>
        );
    };

    const renderBookingConfirmationModal = () => {
        const selectedRoomsList = masterRoomList.filter((room: any) => selectedRoomIds.has(room.id));
        const groupedRooms = groupRoomsByType(selectedRoomsList);

        if (!availableRoomsData) {
            return (
                <Modal title="Xác nhận đặt phòng" open={bookingModalVisible} onCancel={() => setBookingModalVisible(false)} footer={null}>
                    <Spin tip="Đang tải thông tin gói phòng..." />
                </Modal>
            );
        }

        let totalCost = 0;
        Object.entries(groupedRooms).forEach(([roomTypeId, { rooms }]) => {
            const roomTypeData = availableRoomsData.data.find((rt: any) => rt.room_type_id.toString() === roomTypeId);
            const selectedPackageId = selectedPackages[roomTypeId];
            if (roomTypeData && selectedPackageId) {
                const selectedPackage = roomTypeData.package_options.find((p: any) => p.package_id === selectedPackageId);
                if (selectedPackage) {
                    totalCost += selectedPackage.price_per_room_per_night * rooms.length;
                }
            }
        });
        // consolog ra toàn bộ thông tin để kiểm tra
        console.log("Booking Modal Data:", {
            selectedRooms: selectedRoomsList,
            groupedRooms,
            availableRoomsData,
            selectedPackages,
            guestDetails,
            totalCost
        });
        return (
            <Modal
                title={<Flex align="center" gap="middle"><ApartmentOutlined /><Title level={3} className="!m-0">Xác nhận lựa chọn gói phòng</Title></Flex>}
                open={bookingModalVisible}
                onCancel={() => setBookingModalVisible(false)}
                width={1200}
                centered
                footer={[
                    <Button key="back" onClick={() => setBookingModalVisible(false)}>
                        Huỷ
                    </Button>,
                    <Button key="submit" type="primary" icon={<WalletOutlined />} onClick={handleConfirmAndPay} disabled={Object.keys(selectedPackages).length === 0}>
                        Xác nhận & Thanh toán ({formatCurrency(totalCost)})
                    </Button>,
                ]}
            >
                <Paragraph type="secondary">
                    Vui lòng chọn gói dịch vụ và điền số lượng khách cho từng loại phòng bạn đã chọn.
                    Giá và chính sách sẽ được áp dụng tương ứng.
                </Paragraph>
                <Row gutter={[16, 16]}>
                    {Object.entries(groupedRooms).map(([roomTypeId, { roomTypeName, rooms }]) => {
                        const roomTypeData = availableRoomsData.data.find((rt: any) => rt.room_type_id.toString() === roomTypeId);
                        if (!roomTypeData) return null;

                        const selectedPackageId = selectedPackages[roomTypeId];
                        const selectedPackageDetails = roomTypeData.package_options.find((p: any) => p.package_id === selectedPackageId);

                        return (
                            <Col span={24} key={roomTypeId}>
                                <Card
                                    title={<Text strong>{roomTypeName}</Text>}
                                    extra={<Badge.Ribbon color="blue" text={`${rooms.length} phòng đã chọn`} />}
                                    style={{ marginBottom: 24, borderRadius: 16, boxShadow: '0 2px 8px #e0e7ff' }}
                                >
                                    {/* CheckCard horizontal for package options */}
                                    <div style={{ marginBottom: 16 }}>
                                        <CheckCard.Group
                                            multiple={false}
                                            onChange={value => setSelectedPackages(prev => ({ ...prev, [roomTypeId]: value }))}
                                            value={selectedPackageId}
                                            style={{ display: 'flex', gap: 16 }}
                                        >
                                            {roomTypeData.package_options.map((pkg: any) => (
                                                <CheckCard
                                                    key={pkg.package_id}
                                                    title={<span style={{ fontWeight: 600, fontSize: 16 }}>{pkg.package_name}</span>}
                                                    description={<span style={{ fontSize: 13 }}>{pkg.package_description}</span>}
                                                    value={pkg.package_id}
                                                    style={{ minWidth: 220, borderRadius: 12, background: selectedPackageId === pkg.package_id ? '#e6f7ff' : '#fff', border: selectedPackageId === pkg.package_id ? '2px solid #1890ff' : '1px solid #eee', marginBottom: 0 }}
                                                    extra={<Tag color="blue">Gói #{pkg.package_id}</Tag>}
                                                >
                                                    <div style={{ textAlign: 'right', marginTop: 8 }}>
                                                        <Text strong style={{ fontSize: 18, color: '#16a34a' }}>{formatCurrency(pkg.price_per_room_per_night)}</Text>
                                                        <div style={{ fontSize: 12, color: '#64748b' }}>/đêm</div>
                                                    </div>
                                                </CheckCard>
                                            ))}
                                        </CheckCard.Group>
                                    </div>

                                    <Divider orientation="left" className="!mt-6">Số lượng khách từng phòng</Divider>
                                    <ProFormGroup>
                                        <Flex gap={16} wrap="wrap">
                                            {rooms.map((room: any) => {
                                                const details = guestDetails[room.id] || { adults: 1, children: 0, childrenAges: [] };
                                                const maxPeople = 6;
                                                return (
                                                    <Badge.Ribbon color="cyan" text={`Phòng ${room.name}`}> {/* Ribbon wraps Card for correct display */}
                                                        <Card key={room.id} size="small" style={{ minWidth: 430, marginBottom: 12, background: '#fff', boxShadow: '0 1px 4px #e0e7ff', borderRadius: 12 }} bordered={false}>
                                                            <Flex gap={8} align="center" style={{ marginTop: 8 }}>
                                                                <ProFormDigit
                                                                    label="Người lớn"
                                                                    fieldProps={{
                                                                        min: 1,
                                                                        max: Math.min(roomTypeData?.max_adults || 2, maxPeople),
                                                                        value: details.adults,
                                                                        onChange: val => handleGuestDetailChange(room.id, 'adults', val || 1),
                                                                    }}
                                                                    width="xs"
                                                                    labelProps={{ style: { fontSize: 13 } }}
                                                                />
                                                                <ProFormDigit
                                                                    label="Trẻ em"
                                                                    fieldProps={{
                                                                        min: 0,
                                                                        max: Math.min(roomTypeData?.max_children || 4, maxPeople - details.adults),
                                                                        value: details.children,
                                                                        onChange: val => handleGuestDetailChange(room.id, 'children', val || 0),
                                                                    }}
                                                                    width="xs"
                                                                    labelProps={{ style: { fontSize: 13 } }}
                                                                />
                                                            </Flex>
                                                            {details.children > 0 && (
                                                                <Flex gap={8} align="center" style={{ marginTop: 8, flexWrap: 'wrap' }}>
                                                                    <Text type="secondary" style={{ fontSize: 12, marginRight: 4 }}>Tuổi trẻ em:</Text>
                                                                    {[...Array(details.children)].map((_, idx) => (
                                                                        <div key={idx} className="flex items-center gap-1">
                                                                            <span className="text-xs text-gray-500">{idx + 1}:</span>
                                                                            <Select
                                                                                value={details.childrenAges[idx] || 8}
                                                                                style={{ width: 60 }}
                                                                                size="small"
                                                                                onChange={age => handleGuestDetailChange(room.id, 'childrenAges', age, idx)}
                                                                                options={Array.from({ length: 9 }, (_, ageIndex) => ({
                                                                                    label: ageIndex + 4,
                                                                                    value: ageIndex + 4
                                                                                }))}
                                                                            />
                                                                        </div>
                                                                    ))}
                                                                </Flex>
                                                            )}
                                                        </Card>
                                                    </Badge.Ribbon>
                                                );
                                            })}
                                        </Flex>
                                    </ProFormGroup>

                                    {selectedPackageDetails && (
                                        <Collapse ghost style={{ marginTop: 16 }}>
                                            <Panel header={<Text strong>Chi tiết chính sách của gói đã chọn</Text>} key="1">
                                                <Tabs defaultActiveKey="cancellation" size="small">
                                                    <Tabs.TabPane tab={<><StopOutlined /> Chính sách huỷ</>} key="cancellation">
                                                        {renderPolicy(selectedPackageDetails.policies.cancellation, 'cancellation')}
                                                    </Tabs.TabPane>
                                                    <Tabs.TabPane tab={<><WalletOutlined /> Chính sách cọc</>} key="deposit">
                                                        {renderPolicy(selectedPackageDetails.policies.deposit, 'deposit')}
                                                    </Tabs.TabPane>
                                                    <Tabs.TabPane tab={<><CalendarOutlined /> Chính sách trả phòng</>} key="check_out">
                                                        {renderPolicy(selectedPackageDetails.policies.check_out, 'check_out')}
                                                    </Tabs.TabPane>
                                                </Tabs>
                                            </Panel>
                                        </Collapse>
                                    )}
                                </Card>
                            </Col>
                        );
                    })}
                </Row>
            </Modal>
        );
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
                    onProceedToBooking={handleProceedToBooking}
                />

                <Card>
                    {viewMode === 'grid' ? (
                        <RoomCheckCardList
                            rooms={filteredRoomsToDisplay}
                            loading={isLoading}
                            selectedRooms={selectedRoomIds}
                            onRoomSelect={handleRoomSelect}
                            onViewDetails={handleViewDetails}
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
                {renderBookingConfirmationModal()}
            </Content>
        </Layout>
    );
};

export default RoomManagementDashboard;