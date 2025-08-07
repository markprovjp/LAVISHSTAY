import React from 'react';
import { ProSkeleton, CheckCard } from '@ant-design/pro-components';
import { Dropdown, Tag, Typography, Space, Flex, Badge } from 'antd';
import {
    EllipsisOutlined,
    EyeOutlined,
    HomeOutlined,
    UserOutlined,
    CalendarOutlined,
    DollarCircleOutlined,
    TeamOutlined,
    BellOutlined,
    CheckCircleOutlined,
    ClockCircleOutlined,
    StopOutlined
} from '@ant-design/icons';
import { statusOptions } from '../../constants/roomStatus';
import dayjs from 'dayjs';

// Import the new RoomCardGrid component
import RoomCardGrid, { type RoomInfo } from './RoomCardGrid';

const { Text, Title } = Typography;

const RoomCheckCardList = ({ rooms, loading, selectedRooms, onRoomSelect, onViewDetails }) => {
    const handleMenuClick = (e, room) => {
        if (e.key === 'details') onViewDetails(room.id);
    };



    // Hàm format ngày
    const formatDate = (date) => {
        return dayjs(date).format('DD/MM/YYYY');
    };


    // Hàm lấy icon status
    const getStatusIcon = (status) => {
        switch (status) {
            case 'available': return <CheckCircleOutlined />;
            case 'occupied': return <UserOutlined />;
            case 'maintenance': return <StopOutlined />;
            case 'cleaning': return <ClockCircleOutlined />;
            default: return <ClockCircleOutlined />;
        }
    };

    if (loading) {
        return (
            <div className="space-y-8">
                {[1, 2, 3].map(floor => (
                    <div key={floor} className="bg-white rounded-xl p-6 shadow-sm border border-green-200">
                        <div className="flex items-center justify-between mb-6">
                            <div className="h-8 bg-gray-200 rounded-lg w-32 animate-pulse"></div>
                            <div className="h-6 bg-gray-200 rounded w-40 animate-pulse"></div>
                        </div>
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-6 gap-6">
                            {[1, 2, 3, 4, 5, 6].map(card => (
                                <div key={card} className="relative bg-white rounded-xl border border-green-300 shadow-sm p-0 animate-pulse" style={{ height: 180 }}>
                                    {/* Ribbon giả lập */}
                                    <div className="absolute right-0 top-0 bg-green-500 text-white px-4 py-1 rounded-bl-xl font-bold text-lg" style={{ letterSpacing: 2 }}>
                                        <span className="opacity-70">####</span>
                                    </div>
                                    {/* Icon và booking info giả lập */}
                                    <div className="flex flex-row items-center px-4 pt-4">
                                        <div className="flex flex-row items-center gap-3 flex-1">
                                            <div className="w-8 h-8 bg-gray-200 rounded-full"></div>
                                            <div className="w-8 h-8 bg-gray-200 rounded-full"></div>
                                        </div>
                                    </div>
                                    <div className="flex flex-col justify-end px-4 pt-10 flex-1" style={{ minHeight: 56 }}>
                                        <div className="h-5 bg-gray-200 rounded w-32 mb-2"></div>
                                        <div className="flex items-center mt-1">
                                            <div className="w-5 h-5 bg-gray-200 rounded-full mr-2"></div>
                                            <div className="h-4 bg-gray-200 rounded w-20"></div>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                ))}
            </div>
        );
    }

    const roomsByFloor = rooms.reduce((acc, room) => {
        const floor = room.floor || 'Chưa xác định';
        if (!acc[floor]) acc[floor] = [];
        acc[floor].push(room);
        return acc;
    }, {});

    return (
        <div className="space-y-8">
            {Object.keys(roomsByFloor).sort((a, b) => parseInt(a) - parseInt(b)).map(floor => {
                const roomTypesInFloor = Array.from(new Set(roomsByFloor[floor].map(room => room.room_type?.name).filter(Boolean)));
                const availableRoomsCount = roomsByFloor[floor].filter(room => room.status === 'available').length;
                const totalRoomsCount = roomsByFloor[floor].length;

                return (
                    <div key={floor} className=" p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                        <Flex align="center" justify="space-between" className="mb-6">
                            <div className="flex items-center space-x-4">
                                <div>
                                    <Title level={3} className="!mb-1 text-gray-900 font-bold">
                                        Tầng {floor}
                                    </Title>
                                    <Text className="text-gray-600 text-sm font-medium">
                                        {roomTypesInFloor.length > 0 ? roomTypesInFloor.join(' • ') : 'Không có loại phòng'}
                                    </Text>
                                </div>
                            </div>
                            <div className="text-right bg-gradient-to-r from-green-50 to-emerald-50 px-4 py-3 rounded-xl border border-green-200">
                                <div className="text-lg font-bold text-green-700">
                                    {availableRoomsCount}/{totalRoomsCount}
                                </div>
                                <div className="text-xs text-green-600 font-medium">
                                    {totalRoomsCount > 0 ? Math.round((availableRoomsCount / totalRoomsCount) * 100) : 0}% sẵn sàng
                                </div>
                            </div>
                        </Flex>

                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-6 ">
                            {roomsByFloor[floor].map(room => {
                                const isSelected = selectedRooms?.has(room.id);
                                const isAvailable = room.status === 'available';
                                const hasBooking = room.booking_info;
                                let statusColor = 'default';
                                let statusIcon = null;
                                switch (room.status) {
                                    case 'available': statusColor = 'green'; statusIcon = <CheckCircleOutlined />; break;
                                    case 'booked': statusColor = 'gold'; statusIcon = <UserOutlined />; break;
                                    case 'out_of_service': statusColor = 'red'; statusIcon = <StopOutlined />; break;
                                    default: statusColor = 'default'; statusIcon = <ClockCircleOutlined />;
                                }

                                const menuProps = {
                                    items: [
                                        { key: 'details', label: 'Xem chi tiết phòng', icon: <UserOutlined /> },
                                    ],
                                    onClick: () => onViewDetails(room.id),
                                };

                                // Icon logic by room_type
                                let icons = [];
                                const typeName = room.room_type?.name?.toLowerCase() || '';
                                if (typeName.includes('suite')) {
                                    icons = [
                                        <HomeOutlined key="suite" style={{ fontSize: 24, color: '#888' }} />,
                                        <BellOutlined key="cake" style={{ fontSize: 24, color: '#888' }} />,
                                    ];
                                } else if (typeName.includes('deluxe')) {
                                    icons = [
                                        <TeamOutlined key="bed" style={{ fontSize: 24, color: '#888' }} />,
                                        <BellOutlined key="cake" style={{ fontSize: 24, color: '#888' }} />,
                                    ];
                                } else if (typeName.includes('standard')) {
                                    icons = [
                                        <HomeOutlined key="home" style={{ fontSize: 24, color: '#888' }} />,
                                    ];
                                } else {
                                    icons = [
                                        <HomeOutlined key="default" style={{ fontSize: 24, color: '#888' }} />,
                                    ];
                                }

                                // Border color by status
                                let borderColor = 'border-gray-300';
                                if (room.status === 'available') borderColor = 'border-green-500';
                                else if (room.status === 'booked') borderColor = 'border-yellow-400';
                                else if (room.status === 'out_of_service') borderColor = 'border-red-500';
                                else if (room.status === 'occupied') borderColor = 'border-gray-400';

                                // Chọn màu ribbon theo trạng thái phòng
                                let ribbonColor = 'default';
                                if (room.status === 'available') ribbonColor = 'green';
                                else if (room.status === 'booked') ribbonColor = 'gold';
                                else if (room.status === 'out_of_service') ribbonColor = 'red';
                                else if (room.status === 'occupied') ribbonColor = 'gray';

                                return (
                                    <Badge.Ribbon color={ribbonColor}
                                        text={<span style={{ fontSize: '1.2rem', fontWeight: 700, }}>{room.name}</span>}
                                        className="!top-8 !right-6 !absolute">
                                        <Dropdown
                                            key={room.id}
                                            menu={menuProps}
                                            trigger={["contextMenu"]}
                                            placement="bottomLeft"
                                        >
                                            <CheckCard
                                                checked={isSelected}
                                                onChange={checked => onRoomSelect(room.id, checked)}
                                                disabled={room.status === 'out_of_service'}
                                                className={`min-w-[220px] max-w-[400px] shadow-sm border ${borderColor} flex flex-col justify-between transition-all duration-200 hover:shadow-lg hover:-translate-y-1 ${isSelected ? 'bg-blue-50' : ''}`}
                                                style={{ padding: '0', marginBottom: 16, height: 180, background: isSelected ? '#e0f2fe' : '#fff', borderRadius: 10, paddingTop: 2 }}
                                                title={
                                                    <div className="flex flex-row items-center px-4 pt-4">
                                                        <div className="flex flex-row items-center flex-1 gap-3">
                                                            {icons}
                                                        </div>
                                                    </div>
                                                }
                                                description={
                                                    <div className="flex flex-col justify-end px-4 pt-10 flex-1" style={{ minHeight: 56 }}>
                                                        {hasBooking ? (
                                                            <>
                                                                <span className="text-base font-medium text-gray-700">{formatDate(hasBooking.check_in)} - {formatDate(hasBooking.check_out)}</span>
                                                                <span className="flex items-center mt-1 text-gray-700">
                                                                    <UserOutlined style={{ marginRight: 6 }} />
                                                                    {hasBooking.guest_count && <span className="font-bold mr-2">{hasBooking.guest_count}</span>}
                                                                    {hasBooking.guest_name && <span>{hasBooking.guest_name}</span>}
                                                                </span>
                                                            </>
                                                        ) : (
                                                            <span className="text-sm text-gray-400">&nbsp;</span>
                                                        )}
                                                    </div>
                                                }
                                            />
                                        </Dropdown>
                                    </Badge.Ribbon>
                                );
                            })}
                        </div>
                    </div>
                );
            })}
        </div>
    );
};

export default RoomCheckCardList;