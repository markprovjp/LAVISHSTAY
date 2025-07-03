import React from 'react';
import { Card, Typography, Empty, Spin, Statistic, Row, Col, Table } from 'antd';
import FullCalendar from '@fullcalendar/react';
import resourceTimelinePlugin from '@fullcalendar/resource-timeline';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import { FullCalendarEvent, FullCalendarResource } from '../../types/room';
import { fullCalendarStatusColors } from '../../constants/roomStatus';
import dayjs from 'dayjs';
import './RoomTimelineView.css';

const { Title } = Typography;

interface RoomTimelineViewProps {
    rooms: any[];
    loading?: boolean;
    onEventClick?: (event: FullCalendarEvent) => void;
    onDateSelect?: (start: string, end: string) => void;
}

const RoomTimelineView: React.FC<RoomTimelineViewProps> = ({
    rooms,
    loading = false,
    onEventClick,
    onDateSelect
}) => {
    if (loading) {
        return (
            <div className="flex justify-center items-center h-64">
                <Spin size="large" />
            </div>
        );
    }

    if (rooms.length === 0) {
        return (
            <Empty
                description="Không có dữ liệu phòng"
                image={Empty.PRESENTED_IMAGE_SIMPLE}
            />
        );
    }

    const currentDate = dayjs();

    // Group rooms by floor - extract floor from room name
    const roomsByFloor = rooms.reduce((acc: any, room: any) => {
        const floor = room.floor || Math.floor(parseInt(room.name) / 100) || 1;
        if (!acc[floor]) {
            acc[floor] = [];
        }
        acc[floor].push(room);
        return acc;
    }, {});

    // Create resources for FullCalendar - each room is a separate row
    const resources: FullCalendarResource[] = [];

    // Sort floors and add rooms
    Object.keys(roomsByFloor)
        .sort((a, b) => Number(a) - Number(b))
        .forEach(floor => {
            const floorRooms = roomsByFloor[Number(floor)];
            floorRooms.forEach((room: any) => {
                resources.push({
                    id: room.id,
                    title: room.name,
                    group: `Tầng ${floor}`,
                });
            });
        });

    // Create events for FullCalendar
    const events: FullCalendarEvent[] = rooms
        .filter((room: any) => room.status !== 'available' && room.checkInDate && room.checkOutDate)
        .map((room: any) => ({
            id: room.id,
            resourceId: room.id,
            title: room.guestName || (room.status === 'maintenance' ? 'Bảo trì' : 'Khách'),
            start: room.checkInDate!,
            end: room.checkOutDate!,
            backgroundColor: fullCalendarStatusColors[room.status as keyof typeof fullCalendarStatusColors] || '#1890ff',
            guestCount: room.guestCount,
            status: room.status,
        }));

    // Calculate statistics
    const totalRooms = rooms.length;
    const occupiedRooms = rooms.filter((room: any) => room.status === 'occupied').length;
    const availableRooms = rooms.filter((room: any) => room.status === 'available').length;
    const maintenanceRooms = rooms.filter((room: any) => room.status === 'maintenance').length;
    const cleaningRooms = rooms.filter((room: any) => room.status === 'cleaning').length;

    const handleEventClick = (clickInfo: any) => {
        const event = clickInfo.event;
        const eventData: FullCalendarEvent = {
            id: event.id,
            resourceId: event.getResources()[0]?.id || '',
            title: event.title,
            start: event.start?.toISOString().split('T')[0] || '',
            end: event.end?.toISOString().split('T')[0] || '',
            backgroundColor: event.backgroundColor,
            guestCount: event.extendedProps.guestCount,
            status: event.extendedProps.status,
        };
        onEventClick?.(eventData);
    };

    const handleDateSelect = (selectInfo: any) => {
        const start = selectInfo.start.toISOString().split('T')[0];
        const end = selectInfo.end.toISOString().split('T')[0];
        onDateSelect?.(start, end);
    };

    const handleEventDrop = (dropInfo: any) => {
        console.log('Event dropped:', dropInfo);
        // Handle drag and drop logic here
    };

    const handleEventResize = (resizeInfo: any) => {
        console.log('Event resized:', resizeInfo);
        // Handle resize logic here
    };

    // Generate dates for the next 7 days for statistics
    const dates = Array.from({ length: 7 }, (_, i) => currentDate.add(i, 'day'));

    const calculateRoomStats = (date: dayjs.Dayjs) => {
        // Mock calculation for demo - you can implement real logic here
        const dateStr = date.format('YYYY-MM-DD');
        const eventsOnDate = events.filter(event =>
            dayjs(event.start).isSameOrBefore(date) &&
            dayjs(event.end).isAfter(date)
        );

        return {
            occupied: eventsOnDate.length,
            available: totalRooms - eventsOnDate.length,
        };
    };

    return (
        <div className="space-y-6">
            {/* Statistics */}
            <Card>
                <Row gutter={16}>
                    <Col xs={12} sm={6} md={4}>
                        <Statistic title="Tổng số phòng" value={totalRooms} />
                    </Col>
                    <Col xs={12} sm={6} md={4}>
                        <Statistic
                            title="Phòng đã thuê"
                            value={occupiedRooms}
                            valueStyle={{ color: '#f5222d' }}
                        />
                    </Col>
                    <Col xs={12} sm={6} md={4}>
                        <Statistic
                            title="Phòng trống"
                            value={availableRooms}
                            valueStyle={{ color: '#52c41a' }}
                        />
                    </Col>
                    <Col xs={12} sm={6} md={4}>
                        <Statistic
                            title="Bảo trì"
                            value={maintenanceRooms}
                            valueStyle={{ color: '#8c8c8c' }}
                        />
                    </Col>
                    <Col xs={12} sm={6} md={4}>
                        <Statistic
                            title="Dọn dẹp"
                            value={cleaningRooms}
                            valueStyle={{ color: '#fa8c16' }}
                        />
                    </Col>
                    <Col xs={12} sm={6} md={4}>
                        <Statistic
                            title="Tỷ lệ lấp đầy"
                            value={totalRooms > 0 ? Math.round((occupiedRooms / totalRooms) * 100) : 0}
                            suffix="%"
                            valueStyle={{ color: occupiedRooms / totalRooms > 0.8 ? '#f5222d' : '#1890ff' }}
                        />
                    </Col>
                </Row>
            </Card>

            {/* Status Filter Pills */}
            <Card>
                <div className="flex items-center justify-between mb-4">
                    <Title level={5} className="mb-0">Bộ lọc trạng thái phòng</Title>
                    <div className="text-xs text-gray-500">
                        {currentDate.format('DD/MM/YYYY')}
                    </div>
                </div>
                <div className="flex flex-wrap gap-2">
                    <div className="px-3 py-1 bg-green-500 text-white rounded-full text-xs">
                        Phòng đón khách
                    </div>
                    <div className="px-3 py-1 bg-orange-400 text-white rounded-full text-xs">
                        Khách nghỉ ngơi
                    </div>
                    <div className="px-3 py-1 bg-red-500 text-white rounded-full text-xs">
                        Phòng đang có khách ở
                    </div>
                    <div className="px-3 py-1 bg-pink-400 text-white rounded-full text-xs">
                        Không đến
                    </div>
                    <div className="px-3 py-1 bg-gray-400 text-white rounded-full text-xs">
                        Phòng đang sửa
                    </div>
                    <div className="px-3 py-1 bg-blue-500 text-white rounded-full text-xs">
                        Khách đã cọc tiền
                    </div>
                </div>
            </Card>

            {/* Timeline Calendar */}
            <Card>
                <div className="fullcalendar-container">
                    <FullCalendar
                        plugins={[resourceTimelinePlugin, dayGridPlugin, interactionPlugin]}
                        initialView="resourceTimelineWeek"
                        initialDate={currentDate.format('YYYY-MM-DD')}
                        headerToolbar={{
                            left: 'prev,next',
                            center: 'title',
                            right: 'today'
                        }}
                        resources={resources}
                        events={events}
                        resourceGroupField="group"
                        resourceAreaHeaderContent="Phòng"
                        selectable={true}
                        selectMirror={true}
                        editable={true}
                        droppable={true}
                        eventDrop={handleEventDrop}
                        eventResize={handleEventResize}
                        eventClick={handleEventClick}
                        select={handleDateSelect}
                        height="500px"
                        locale="vi"
                        slotMinTime="00:00:00"
                        slotMaxTime="24:00:00"
                        resourceAreaWidth="120px"
                        resourceAreaColumns={[
                            {
                                field: 'title',
                                headerContent: 'Số phòng',
                                width: 120
                            }
                        ]}
                        eventContent={(eventInfo) => {
                            const room = rooms.find((r: any) => r.id === eventInfo.event.getResources()[0]?.id);
                            return (
                                <div className="p-1 text-xs text-white overflow-hidden">
                                    <div className="font-medium truncate">{eventInfo.event.title}</div>
                                    {room?.guestCount && (
                                        <div className="opacity-75">{room.guestCount} người</div>
                                    )}
                                </div>
                            );
                        }}
                        eventClassNames="cursor-pointer hover:opacity-80"
                        businessHours={{
                            daysOfWeek: [0, 1, 2, 3, 4, 5, 6],
                            startTime: '00:00',
                            endTime: '24:00',
                        }}
                        slotLabelFormat={{
                            day: '2-digit',
                            month: '2-digit',
                            weekday: 'short'
                        }}
                        dayHeaderFormat={{
                            weekday: 'short',
                            month: '2-digit',
                            day: '2-digit'
                        }}
                        resourceOrder="title"
                        eventOverlap={false}
                        eventMinHeight={30}
                    />
                </div>
            </Card>

            {/* Room Statistics Footer */}
            <Card>
                <Title level={5} className="mb-4">Thống kê phòng theo ngày</Title>
                <div className="overflow-x-auto">
                    <Table
                        size="small"
                        pagination={false}
                        columns={[
                            {
                                title: 'Ngày',
                                dataIndex: 'date',
                                key: 'date',
                                width: 100,
                                render: (date: dayjs.Dayjs) => date.format('DD/MM'),
                            },
                            {
                                title: 'Phòng đang sử dụng',
                                dataIndex: 'occupied',
                                key: 'occupied',
                                width: 120,
                                render: (occupied: number) => (
                                    <span className="text-red-600 font-medium">{occupied}</span>
                                ),
                            },
                            {
                                title: 'Phòng còn trống',
                                dataIndex: 'available',
                                key: 'available',
                                width: 120,
                                render: (available: number) => (
                                    <span className="text-green-600 font-medium">{available}</span>
                                ),
                            },
                        ]}
                        dataSource={dates.map((date, index) => ({
                            key: index,
                            date: date,
                            ...calculateRoomStats(date),
                        }))}
                        scroll={{ x: true }}
                    />
                </div>
            </Card>
        </div>
    );
};

export default RoomTimelineView;
