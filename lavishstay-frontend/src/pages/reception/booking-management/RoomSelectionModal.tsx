import React, { useState, useEffect, useMemo } from 'react';
import { Modal, Button, message, Spin, Card, Typography, Row, Col, Divider, Empty, Alert, Tag, List, Grid, Flex, Avatar } from 'antd';
import { Building, BedDouble, ArrowRight, Hotel, KeyRound, CheckCircle2 } from 'lucide-react';
import { useGetAssignmentPreview, useAssignMultipleRooms } from '../../../hooks/useReception';
import { motion, AnimatePresence } from 'framer-motion';

const { Title, Text, Paragraph } = Typography;
const { useBreakpoint } = Grid;

// Interfaces matching the API response
interface AvailableRoom {
    id: number;
    name: string;
    floor: number;
    room_type_id: number;
}

interface AssignmentOption {
    room_type_id: number;
    room_type_name: string;
    rooms_needed: number;
    available_rooms: AvailableRoom[];
    booking_room_ids: number[];
}

interface RoomSelectionModalProps {
    visible: boolean;
    onClose: () => void;
    bookingId: number | null;
    onUpdate: () => void; // Changed from onAssignmentSuccess for consistency
}

const RoomSelectionModal: React.FC<RoomSelectionModalProps> = ({ visible, onClose, bookingId, onUpdate }) => {
    const screens = useBreakpoint();
    const { data: previewData, isLoading, error, refetch } = useGetAssignmentPreview(bookingId);
    const assignRoomsMutation = useAssignMultipleRooms();

    // State to manage assignments: { booking_room_id: room_id }
    const [assignments, setAssignments] = useState<Record<number, number | null>>({});
    // State to track the currently selected "slot" to be assigned
    const [selectedSlot, setSelectedSlot] = useState<number | null>(null);

    useEffect(() => {
        if (visible && bookingId) {
            refetch();
        }
        // Reset state when modal is closed or bookingId changes
        setAssignments({});
        setSelectedSlot(null);
    }, [visible, bookingId, refetch]);

    const assignmentOptions = useMemo(() => previewData?.data?.assignment_options || [], [previewData]);

    const handleSelectSlot = (bookingRoomId: number) => {
        setSelectedSlot(prev => (prev === bookingRoomId ? null : bookingRoomId)); // Toggle selection
    };

    const handleSelectRoom = (room: AvailableRoom) => {
        if (!selectedSlot) {
            message.info('Vui lòng chọn một suất cần gán ở cột bên trái trước.');
            return;
        }

        // Un-assign if the room is already assigned to the current slot
        if (assignments[selectedSlot] === room.id) {
            setAssignments(prev => ({ ...prev, [selectedSlot]: null }));
            return;
        }

        // Check if the room is already assigned to another slot
        const isRoomAlreadyAssigned = Object.values(assignments).includes(room.id);
        if (isRoomAlreadyAssigned) {
            message.warning(`Phòng ${room.name} đã được gán cho một suất khác.`);
            return;
        }

        // Assign the room
        setAssignments(prev => ({ ...prev, [selectedSlot]: room.id }));
        
        // Automatically select the next unassigned slot
        const allSlots = assignmentOptions.flatMap(opt => opt.booking_room_ids);
        const currentIndex = allSlots.indexOf(selectedSlot);
        const nextSlot = allSlots.find((slotId, index) => index > currentIndex && !assignments[slotId]);
        setSelectedSlot(nextSlot || null);
    };

    const handleSubmit = async () => {
        const finalAssignments = Object.entries(assignments)
            .filter(([, roomId]) => roomId !== null)
            .map(([bookingRoomId, roomId]) => ({
                booking_room_id: Number(bookingRoomId),
                room_id: Number(roomId),
            }));

        const totalNeeded = assignmentOptions.reduce((acc, opt) => acc + opt.rooms_needed, 0);

        if (finalAssignments.length < totalNeeded) {
            message.error('Vui lòng gán đủ số lượng phòng theo yêu cầu.');
            return;
        }

        await assignRoomsMutation.mutateAsync(finalAssignments, {
            onSuccess: () => {
                message.success('Gán phòng thành công!');
                onUpdate(); // Call refetch from parent
                onClose();
            },
            onError: (err: any) => {
                message.error(`Lỗi khi gán phòng: ${err.response?.data?.message || err.message}`);
            }
        });
    };

    const assignedRoomIds = new Set(Object.values(assignments).filter(Boolean));
    const allSlotsCount = assignmentOptions.reduce((sum, opt) => sum + opt.rooms_needed, 0);
    const assignedSlotsCount = Object.values(assignments).filter(Boolean).length;
    const isSubmitDisabled = assignedSlotsCount < allSlotsCount || assignRoomsMutation.isPending;

    const renderContent = () => {
        if (isLoading) return <div className="flex justify-center items-center h-64"><Spin size="large" /></div>;
        if (error) return <Alert message="Lỗi tải dữ liệu" description={(error as any).message} type="error" showIcon />;
        if (assignmentOptions.length === 0) return <Empty description="Không có thông tin gán phòng cho đơn hàng này." />;

        return (
            <Row gutter={[32, 16]}>
                {/* Left Column: Slots to be Assigned */}
                <Col xs={24} md={8}>
                    <Title level={5}>Suất cần gán ({assignedSlotsCount}/{allSlotsCount})</Title>
                    <Paragraph type="secondary">Chọn một suất rồi chọn phòng trống bên phải để gán.</Paragraph>
                    <List
                        itemLayout="horizontal"
                        dataSource={assignmentOptions.flatMap(opt => opt.booking_room_ids)}
                        renderItem={(bookingRoomId) => {
                            const assignedRoomId = assignments[bookingRoomId];
                            const assignedRoom = assignedRoomId ? assignmentOptions.flatMap(opt => opt.available_rooms).find(r => r.id === assignedRoomId) : null;
                            const isSelected = selectedSlot === bookingRoomId;

                            return (
                                <List.Item
                                    onClick={() => handleSelectSlot(bookingRoomId)}
                                    style={{
                                        borderLeft: isSelected ? '4px solid #1677ff' : '4px solid transparent',
                                        backgroundColor: isSelected ? '#e6f4ff' : '#fff',
                                        padding: '12px',
                                        borderRadius: '8px',
                                        cursor: 'pointer',
                                        transition: 'all 0.2s ease-in-out',
                                    }}
                                >
                                    <List.Item.Meta
                                        avatar={<Avatar size="large" icon={<KeyRound />} style={{ backgroundColor: assignedRoom ? '#52c41a' : '#f0f0f0', color: assignedRoom ? '#fff' : '#595959' }} />}
                                        title={<Text strong>Suất #{bookingRoomId}</Text>}
                                        description={
                                            assignedRoom ?
                                            <Tag color="green" icon={<CheckCircle2 size={14} />}>Đã gán: {assignedRoom.name}</Tag> :
                                            <Tag color="gold">Đang chờ gán...</Tag>
                                        }
                                    />
                                </List.Item>
                            );
                        }}
                    />
                </Col>

                {/* Right Column: Available Rooms */}
                <Col xs={24} md={16}>
                    <Title level={5}>Phòng trống khả dụng</Title>
                    <Paragraph type="secondary">
                        Hiển thị các phòng trống phù hợp với loại phòng của các suất bên trái.
                    </Paragraph>
                    <div style={{ maxHeight: '60vh', overflowY: 'auto', paddingRight: '8px' }}>
                        {assignmentOptions.map(option => (
                            <div key={option.room_type_id}>
                                <Divider orientation="left">
                                    <Text strong>{option.room_type_name} ({option.available_rooms.length} phòng trống)</Text>
                                </Divider>
                                <Row gutter={[16, 16]}>
                                    <AnimatePresence>
                                        {option.available_rooms.map(room => {
                                            const isAssigned = assignedRoomIds.has(room.id);
                                            return (
                                                <Col xs={24} sm={12} lg={8} key={room.id}>
                                                    <motion.div
                                                        layout
                                                        initial={{ opacity: 0, scale: 0.8 }}
                                                        animate={{ opacity: 1, scale: 1 }}
                                                        exit={{ opacity: 0, scale: 0.8 }}
                                                        transition={{ duration: 0.2 }}
                                                    >
                                                        <Card
                                                            hoverable
                                                            onClick={() => handleSelectRoom(room)}
                                                            bodyStyle={{ padding: 16 }}
                                                            style={{
                                                                border: selectedSlot && !isAssigned ? '2px solid #1677ff' : '1px solid #f0f0f0',
                                                                opacity: isAssigned ? 0.5 : 1,
                                                                cursor: isAssigned ? 'not-allowed' : 'pointer',
                                                            }}
                                                        >
                                                            <Flex justify="space-between" align="center">
                                                                <Flex vertical>
                                                                    <Text strong style={{ fontSize: 16 }}>Phòng {room.name}</Text>
                                                                    <Text type="secondary"><Building size={14} /> Tầng {room.floor}</Text>
                                                                </Flex>
                                                                {isAssigned && <Tag color="blue">Đã gán</Tag>}
                                                            </Flex>
                                                        </Card>
                                                    </motion.div>
                                                </Col>
                                            );
                                        })}
                                    </AnimatePresence>
                                </Row>
                            </div>
                        ))}
                    </div>
                </Col>
            </Row>
        );
    };

    return (
        <Modal
            title={<Title level={4}>Gán phòng cho đơn: #{previewData?.data?.booking_id}</Title>}
            open={visible}
            onCancel={onClose}
            width={screens.lg ? '70%' : '90%'}
            destroyOnClose
            footer={[
                <Button key="back" onClick={onClose}>Hủy</Button>,
                <Button
                    key="submit"
                    type="primary"
                    loading={assignRoomsMutation.isPending}
                    onClick={handleSubmit}
                    disabled={isSubmitDisabled}
                    icon={<CheckCircle2 size={14} />}
                >
                    Xác nhận gán phòng
                </Button>,
            ]}
        >
            {renderContent()}
        </Modal>
    );
};

export default RoomSelectionModal;
