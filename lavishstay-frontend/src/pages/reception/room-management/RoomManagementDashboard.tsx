import React, { useState, useMemo } from 'react';
import {
    Layout, message, Modal, Card, Tag, Button, Space, Spin, List, Avatar, Empty,
    Alert, Typography, Row, Col, Flex, Divider, Statistic, Radio, Collapse, Descriptions, Tabs
} from 'antd';
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
import RoomGridView from '../../../components/room-management/RoomGridView';
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

    const { data: allRoomsData, isLoading: isLoadingRooms } = useGetReceptionRooms({ include: 'room_type' });
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
        return masterRoomList.filter((room: any) => {
            if (availableRoomIdSet && !availableRoomIdSet.has(room.id.toString())) {
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
        setSelectedRoomIds(new Set());
        setSelectedPackages({});
    };

    const handleViewDetails = (roomId: string) => {
        setSelectedRoomId(roomId);
        setRoomDetailVisible(true);
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

        const roomsWithGuests = selectedRoomsList.map((room: any) => {
            const guestConfig = guestDetails[room.id] || { adults: 1, children: 0, childrenAges: [] };
            const roomTypeId = room.room_type.id.toString();
            const packageId = selectedPackages[roomTypeId];
            let option_id = null, option_name = null, room_price = 0;

            if (availableRoomsData) {
                const roomTypeData = availableRoomsData.data.find((rt: any) => rt.room_type_id.toString() === roomTypeId);
                if (roomTypeData) {
                    const pkg = roomTypeData.package_options.find((p: any) => p.package_id === packageId);
                    if (pkg) {
                        option_id = pkg.package_id;
                        option_name = pkg.package_name;
                        room_price = pkg.price_per_room_per_night;
                    }
                }
            }

            return {
                room_id: room.id,
                package_id: packageId,
                option_id,
                option_name,
                room_price,
                adults: guestConfig.adults,
                children: guestConfig.childrenAges, // Backend expects an array of ages
                children_age: guestConfig.childrenAges,
            };
        });

        const bookingData = {
            checkInDate: currentFilters.dateRange![0],
            checkOutDate: currentFilters.dateRange![1],
            guestRooms: selectedRoomsList.map(room => {
                const details = guestDetails[room.id] || { adults: 1, children: 0, childrenAges: [] };
                return {
                    adults: details.adults,
                    children: details.childrenAges.map(age => ({ age }))
                };
            }),
            selectedPackages,
            availableRoomsData: availableRoomsData,
            roomsWithGuests,
        };

              navigate('/reception/confirm-representative-payment', {
            state: {
                selectedRooms: selectedRoomsList,
                bookingData,
                guestDetails: selectedRoomsList.map(room => {
                    const details = guestDetails[room.id] || { adults: 1, children: 0, childrenAges: [] };
                    return {
                        adults: details.adults,
                        children: details.children,
                        childrenAges: details.childrenAges,
                    };
                }),
                adults: selectedRoomsList.reduce((sum, room) => sum + (guestDetails[room.id]?.adults || 1), 0),
                children: selectedRoomsList.reduce((sum, room) => sum + (guestDetails[room.id]?.children || 0), 0),
                checkInDate: currentFilters.dateRange?.[0],
                checkOutDate: currentFilters.dateRange?.[1],
            }
        });
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
                                    title={<Text strong>{`${roomTypeName} (${rooms.length} phòng)`}</Text>}
                                    extra={<Text>Phòng: {rooms.map(r => r.name).join(', ')}</Text>}
                                >
                                    <Radio.Group
                                        onChange={(e) => setSelectedPackages(prev => ({ ...prev, [roomTypeId]: e.target.value }))}
                                        value={selectedPackageId}
                                        style={{ width: '100%' }}
                                    >
                                        <Space direction="vertical" style={{ width: '100%' }}>
                                            {roomTypeData.package_options.map((pkg: any) => (
                                                <Radio key={pkg.package_id} value={pkg.package_id} style={{ width: '100%' }}>
                                                    <Card size="small" hoverable>
                                                        <Flex justify="space-between" align="center">
                                                            <div>
                                                                <Text strong>{pkg.package_name}</Text>
                                                                <Paragraph type="secondary" className="!mb-0">{pkg.package_description}</Paragraph>
                                                            </div>
                                                            <Statistic
                                                                title="Giá mỗi đêm"
                                                                value={formatCurrency(pkg.price_per_room_per_night)}
                                                                valueStyle={{ color: '#3f8600', fontSize: '1.2rem' }}
                                                            />
                                                        </Flex>
                                                    </Card>
                                                </Radio>
                                            ))}
                                        </Space>
                                    </Radio.Group>

                                    <Divider orientation="left" className="!mt-6">Số lượng khách từng phòng</Divider>
                                    <ProFormGroup>
                                        {rooms.map((room: any) => {
                                            const details = guestDetails[room.id] || { adults: 1, children: 0, childrenAges: [] };
                                            return (
                                                <Card key={room.id} size="small" style={{ marginBottom: 12, background: '#fafafa' }} bordered={false}>
                                                    <Text strong>Phòng {room.name}</Text>
                                                    <Row gutter={16} style={{ marginTop: 8 }}>
                                                        <Col>
                                                            <ProFormDigit
                                                                label="Người lớn"
                                                                fieldProps={{
                                                                    min: 1,
                                                                    max: roomTypeData?.max_adults || 2,
                                                                    value: details.adults,
                                                                    onChange: val => handleGuestDetailChange(room.id, 'adults', val || 1),
                                                                }}
                                                                width="small"
                                                            />
                                                        </Col>
                                                        <Col>
                                                            <ProFormDigit
                                                                label="Trẻ em"
                                                                fieldProps={{
                                                                    min: 0,
                                                                    max: roomTypeData?.max_children || 4,
                                                                    value: details.children,
                                                                    onChange: val => handleGuestDetailChange(room.id, 'children', val || 0),
                                                                }}
                                                                width="small"
                                                            />
                                                        </Col>
                                                    </Row>
                                                    {details.children > 0 && (
                                                        <div style={{ marginTop: 8 }}>
                                                            <Text type="secondary" style={{ fontSize: 12, display: 'block', marginBottom: 4 }}>Tuổi của trẻ em:</Text>
                                                            <Space wrap>
                                                                {[...Array(details.children)].map((_, idx) => (
                                                                    <ProFormDigit
                                                                        key={idx}
                                                                        label={`Trẻ ${idx + 1}`}
                                                                        fieldProps={{
                                                                            min: 0,
                                                                            max: 17,
                                                                            value: details.childrenAges[idx] || 0,
                                                                            onChange: val => handleGuestDetailChange(room.id, 'childrenAges', val || 0, idx),
                                                                            style: { width: 80 }
                                                                        }}
                                                                        width="small"
                                                                        labelProps={{ style: { fontSize: 12 } }}
                                                                    />
                                                                ))}
                                                            </Space>
                                                        </div>
                                                    )}
                                                </Card>
                                            );
                                        })}
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
                        <RoomGridView
                            rooms={filteredRoomsToDisplay}
                            loading={isLoading}
                            multiSelectMode={true}
                            selectedRooms={selectedRoomIds}
                            onRoomSelect={handleRoomSelect}
                            onBulkRoomSelect={handleBulkRoomSelect}
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