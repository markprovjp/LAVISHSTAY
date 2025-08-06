import React, { useState, useMemo } from 'react';
import { Card, Typography, Empty, Spin, Statistic, Row, Col, Table, Modal, Form, Select, DatePicker, Input, Button, message } from 'antd';
import FullCalendar from '@fullcalendar/react';
import resourceTimelinePlugin from '@fullcalendar/resource-timeline';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import { FullCalendarEvent, FullCalendarResource } from '../../types/room';
import { fullCalendarStatusColors } from '../../constants/roomStatus';
import { useGetRoomBookings } from '../../hooks/useReception';
import dayjs from 'dayjs';

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
    const [modalVisible, setModalVisible] = useState(false);
    const [selectedSlot, setSelectedSlot] = useState<any>(null);
    const [selectedEvent, setSelectedEvent] = useState<any>(null);
    const [form] = Form.useForm();
    const [statusFilter, setStatusFilter] = useState<string[]>([]);
    const [currentDate, setCurrentDate] = useState(dayjs());

    // Fetch real bookings data from API
    const { data: bookingsData, isLoading: bookingsLoading } = useGetRoomBookings({
        start_date: currentDate.startOf('week').format('YYYY-MM-DD'),
        end_date: currentDate.endOf('week').format('YYYY-MM-DD'),
    });

    const bookings = bookingsData?.data || [];

    // Generate dates for the current week for statistics
    const weekDates = useMemo(() => {
        const start = currentDate.startOf('week');
        return Array.from({ length: 7 }, (_, i) => start.add(i, 'day'));
    }, [currentDate]);

    // Create events for FullCalendar from API bookings data
    const events: FullCalendarEvent[] = useMemo(() => {
        return bookings.map((booking: any) => ({
            id: booking.id,
            resourceId: booking.resourceId,
            title: booking.title,
            start: booking.start,
            end: booking.end,
            backgroundColor: fullCalendarStatusColors[booking.extendedProps?.booking_status as keyof typeof fullCalendarStatusColors] || '#1890ff',
            borderColor: fullCalendarStatusColors[booking.extendedProps?.booking_status as keyof typeof fullCalendarStatusColors] || '#1890ff',
            extendedProps: booking.extendedProps
        }));
    }, [bookings]);

    // Calculate room statistics for each day and room
    const roomStatistics = useMemo(() => {
        return rooms.map(room => {
            const roomData = {
                roomId: room.id,
                roomName: room.name,
                floor: room.floor || Math.floor(parseInt(room.name) / 100) || 1,
            };

            // For each day, check if room has booking
            weekDates.forEach((date, index) => {
                const roomBookings = events.filter((event: any) =>
                    event.resourceId === room.id &&
                    (date.isSame(dayjs(event.start), 'day') ||
                        (date.isAfter(dayjs(event.start), 'day') && date.isBefore(dayjs(event.end), 'day')))
                );

                (roomData as any)[`day_${index}`] = roomBookings.length > 0 ? 'occupied' : 'available';
            });

            return roomData;
        });
    }, [rooms, events, weekDates]);

    if (loading || bookingsLoading) {
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

    // Filter events by status
    const filteredEvents = statusFilter.length === 0
        ? events
        : events.filter((event: any) => statusFilter.includes(event.extendedProps?.booking_status));

    // Calculate statistics
    const totalRooms = rooms.length;
    const occupiedRooms = rooms.filter((room: any) => room.status === 'occupied').length;
    const availableRooms = rooms.filter((room: any) => room.status === 'available').length;
    const maintenanceRooms = rooms.filter((room: any) => room.status === 'maintenance').length;
    const cleaningRooms = rooms.filter((room: any) => room.status === 'cleaning').length;

    // Status filter options
    const statusFilterOptions = [
        { key: 'confirmed', label: 'Đã xác nhận', color: '#1890ff' },
        { key: 'checked_in', label: 'Đã nhận phòng', color: '#52c41a' },
        { key: 'checked_out', label: 'Đã trả phòng', color: '#8c8c8c' },
        { key: 'cancelled', label: 'Đã hủy', color: '#ff4d4f' },
    ];

    // Toggle status filter
    const handleStatusFilterToggle = (status: string) => {
        setStatusFilter(prev =>
            prev.includes(status)
                ? prev.filter(s => s !== status)
                : [...prev, status]
        );
    };

    // Event handlers for drag & drop và click
    const handleEventClick = (clickInfo: any) => {
        const event = clickInfo.event;
        setSelectedEvent({
            id: event.id,
            title: event.title,
            start: event.start,
            end: event.end,
            resourceId: event.getResources()[0]?.id,
            ...event.extendedProps
        });
        onEventClick?.({
            id: event.id,
            resourceId: event.getResources()[0]?.id || '',
            title: event.title,
            start: event.start?.toISOString().split('T')[0] || '',
            end: event.end?.toISOString().split('T')[0] || '',
            backgroundColor: event.backgroundColor,
            guestCount: event.extendedProps?.guest_count,
            status: event.extendedProps?.booking_status,
        });
    };

    const handleDateSelect = (selectInfo: any) => {
        const start = selectInfo.start.toISOString().split('T')[0];
        const end = selectInfo.end.toISOString().split('T')[0];
        const resourceId = selectInfo.resource?.id;

        if (resourceId) {
            setSelectedSlot({
                start,
                end,
                resourceId,
                roomName: selectInfo.resource.title
            });
            setModalVisible(true);
        }

        onDateSelect?.(start, end);
    };

    const handleEventDrop = (dropInfo: any) => {
        const event = dropInfo.event;
        const newStart = event.start?.toISOString().split('T')[0];
        const newEnd = event.end?.toISOString().split('T')[0];
        const newResourceId = event.getResources()[0]?.id;

        console.log('Event dropped:', {
            eventId: event.id,
            newStart,
            newEnd,
            newResourceId,
            oldResourceId: dropInfo.oldResource?.id
        });

        message.success(`Đã chuyển booking "${event.title}" sang phòng ${event.getResources()[0]?.title}`);

        // TODO: Call API để cập nhật booking
        // transferBooking({ booking_id: event.id, old_room_id: dropInfo.oldResource?.id, new_room_id: newResourceId });
    };

    const handleEventResize = (resizeInfo: any) => {
        const event = resizeInfo.event;
        const newStart = event.start?.toISOString().split('T')[0];
        const newEnd = event.end?.toISOString().split('T')[0];

        console.log('Event resized:', {
            eventId: event.id,
            newStart,
            newEnd
        });

        message.success(`Đã thay đổi thời gian booking "${event.title}"`);

        // TODO: Call API để cập nhật thời gian booking
        // updateBooking(event.id, { check_in: newStart, check_out: newEnd });
    };

    const handleCreateBooking = (values: any) => {
        console.log('Creating booking:', {
            room_id: selectedSlot.resourceId,
            guest_name: values.guestName,
            guest_count: values.guestCount,
            check_in: values.dateRange[0].format('YYYY-MM-DD'),
            check_out: values.dateRange[1].format('YYYY-MM-DD'),
            status: values.status,
            booking_type: 'confirmed'
        });

        setModalVisible(false);
        form.resetFields();
        message.success('Đã tạo booking mới!');

        // TODO: Call API để tạo booking mới
        // createBooking(newBooking);
    };

    // Statistics table columns
    const statisticsColumns = [
        {
            title: 'Phòng',
            dataIndex: 'roomName',
            key: 'roomName',
            width: 80,
            fixed: 'left' as const,
        },
        ...weekDates.map((date, index) => ({
            title: date.format('DD/MM'),
            dataIndex: `day_${index}`,
            key: `day_${index}`,
            width: 80,
            render: (status: string) => {
                if (status === 'occupied') {
                    return <div className="w-4 h-4 bg-red-500 rounded mx-auto"></div>;
                }
                return <div className="w-4 h-4 bg-green-500 rounded mx-auto"></div>;
            },
        }))
    ];

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

            {/* Status Filter Buttons */}
            <Card>
                <div className="flex items-center justify-between mb-4">
                    <Title level={5} className="mb-0">Bộ lọc trạng thái booking</Title>
                    <div className="text-xs text-gray-500">
                        {currentDate.format('DD/MM/YYYY')}
                    </div>
                </div>
                <div className="flex flex-wrap gap-2">
                    {statusFilterOptions.map((option) => (
                        <Button
                            key={option.key}
                            type={statusFilter.includes(option.key) ? "primary" : "default"}
                            size="small"
                            onClick={() => handleStatusFilterToggle(option.key)}
                            style={{
                                backgroundColor: statusFilter.includes(option.key) ? option.color : undefined,
                                borderColor: option.color
                            }}
                        >
                            {option.label}
                        </Button>
                    ))}
                    {statusFilter.length > 0 && (
                        <Button
                            type="text"
                            size="small"
                            onClick={() => setStatusFilter([])}
                            className="text-gray-500"
                        >
                            Xóa bộ lọc
                        </Button>
                    )}
                </div>
            </Card>

            {/* Timeline Calendar */}
            <Card>
                <FullCalendar
                    plugins={[resourceTimelinePlugin, dayGridPlugin, interactionPlugin]}
                    initialView="resourceTimelineWeek"
                    initialDate={currentDate.format('YYYY-MM-DD')}
                    headerToolbar={{
                        left: 'prev,next today',
                        center: 'title',
                        right: 'resourceTimelineWeek,resourceTimelineDay'
                    }}
                    resources={resources}
                    events={filteredEvents}
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
                    height="600px"
                    locale="vi"
                    slotMinTime="00:00:00"
                    slotMaxTime="24:00:00"
                    resourceAreaWidth="150px"
                    resourceAreaColumns={[
                        {
                            field: 'title',
                            headerContent: 'Số phòng',
                            width: 150
                        }
                    ]}
                    eventContent={(eventInfo) => {
                        const booking = eventInfo.event.extendedProps;
                        return (
                            <div className="p-1 text-xs text-white overflow-hidden">
                                <div className="font-medium truncate">{eventInfo.event.title}</div>
                                {booking?.guest_count && (
                                    <div className="opacity-75">{booking.guest_count} người</div>
                                )}
                                <div className="opacity-75 text-xs">
                                    {dayjs(eventInfo.event.start).format('DD/MM')} - {dayjs(eventInfo.event.end).format('DD/MM')}
                                </div>
                            </div>
                        );
                    }}
                    eventClassNames="cursor-pointer hover:opacity-80 transition-opacity"
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
                    eventMinHeight={40}
                    eventResizableFromStart={true}
                    schedulerLicenseKey="CC-Attribution-NonCommercial-NoDerivatives"
                    selectConstraint={{
                        start: currentDate.format('YYYY-MM-DD'),
                        end: currentDate.add(30, 'day').format('YYYY-MM-DD')
                    }}
                    datesSet={(dateInfo) => {
                        setCurrentDate(dayjs(dateInfo.start));
                    }}
                />
            </Card>

            {/* Room Statistics Table - Match with Timeline */}
            <Card>
                <Title level={5} className="mb-4">Thống kê phòng theo ngày (khớp timeline)</Title>
                <div className="overflow-x-auto">
                    <Table
                        size="small"
                        pagination={false}
                        columns={statisticsColumns}
                        dataSource={roomStatistics}
                        rowKey="roomId"
                        scroll={{ x: 'max-content' }}
                        rowClassName={(record) => `floor-${record.floor}`}
                    />
                </div>
            </Card>

            {/* Modal tạo/sửa booking */}
            <Modal
                title={selectedEvent ? "Sửa booking" : "Tạo booking mới"}
                open={modalVisible}
                onCancel={() => {
                    setModalVisible(false);
                    setSelectedEvent(null);
                    form.resetFields();
                }}
                footer={null}
                width={600}
            >
                <Form
                    form={form}
                    layout="vertical"
                    onFinish={handleCreateBooking}
                    initialValues={selectedEvent ? {
                        guestName: selectedEvent.guest_name,
                        guestCount: selectedEvent.guest_count,
                        status: selectedEvent.booking_status,
                        dateRange: [dayjs(selectedEvent.start), dayjs(selectedEvent.end)]
                    } : {
                        dateRange: selectedSlot ? [dayjs(selectedSlot.start), dayjs(selectedSlot.end)] : []
                    }}
                >
                    <Form.Item
                        label="Tên khách hàng"
                        name="guestName"
                        rules={[{ required: true, message: 'Vui lòng nhập tên khách hàng!' }]}
                    >
                        <Input placeholder="Nhập tên khách hàng" />
                    </Form.Item>

                    <Form.Item
                        label="Số khách"
                        name="guestCount"
                        rules={[{ required: true, message: 'Vui lòng nhập số khách!' }]}
                    >
                        <Select placeholder="Chọn số khách">
                            <Select.Option value={1}>1 khách</Select.Option>
                            <Select.Option value={2}>2 khách</Select.Option>
                            <Select.Option value={3}>3 khách</Select.Option>
                            <Select.Option value={4}>4 khách</Select.Option>
                        </Select>
                    </Form.Item>

                    <Form.Item
                        label="Thời gian lưu trú"
                        name="dateRange"
                        rules={[{ required: true, message: 'Vui lòng chọn thời gian!' }]}
                    >
                        <DatePicker.RangePicker
                            className="w-full"
                            format="DD/MM/YYYY"
                            placeholder={['Ngày nhận phòng', 'Ngày trả phòng']}
                        />
                    </Form.Item>

                    <Form.Item
                        label="Trạng thái"
                        name="status"
                        rules={[{ required: true, message: 'Vui lòng chọn trạng thái!' }]}
                    >
                        <Select placeholder="Chọn trạng thái">
                            <Select.Option value="confirmed">Đã xác nhận</Select.Option>
                            <Select.Option value="checked_in">Đã nhận phòng</Select.Option>
                            <Select.Option value="checked_out">Đã trả phòng</Select.Option>
                            <Select.Option value="cancelled">Đã hủy</Select.Option>
                        </Select>
                    </Form.Item>

                    {selectedSlot && (
                        <div className="mb-4 p-3 bg-blue-50 rounded">
                            <p><strong>Phòng:</strong> {selectedSlot.roomName}</p>
                            <p><strong>Thời gian:</strong> {selectedSlot.start} đến {selectedSlot.end}</p>
                        </div>
                    )}

                    <div className="flex justify-end space-x-2">
                        <Button onClick={() => setModalVisible(false)}>
                            Hủy
                        </Button>
                        <Button type="primary" htmlType="submit">
                            {selectedEvent ? "Cập nhật" : "Tạo booking"}
                        </Button>
                    </div>
                </Form>
            </Modal>
        </div>
    );
};

export default RoomTimelineView;
