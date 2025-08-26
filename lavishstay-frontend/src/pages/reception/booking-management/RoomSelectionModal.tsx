import React, { useState, useEffect, useMemo } from 'react';
import { Modal, Button, message, Spin, Card, Typography, Row, Col, Divider, Empty, Alert, Tag, List, Grid, Flex, Avatar } from 'antd';
import { Building, KeyRound, CheckCircle2 } from 'lucide-react';
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
    // when opened from booking detail, parent may supply the specific booking_room_id to pre-focus
    bookingRoomId?: number | null;
    // callback executed after successful assignment (preferred) — fallback to onUpdate for backward compat
    onAssignmentSuccess?: () => void;
    onUpdate?: () => void;
}

const RoomSelectionModal: React.FC<RoomSelectionModalProps> = ({ visible, onClose, bookingId, bookingRoomId, onAssignmentSuccess, onUpdate }) => {
    const screens = useBreakpoint();
    const { data: previewData, isLoading, error, refetch } = useGetAssignmentPreview(bookingId || 0);
    const assignRoomsMutation = useAssignMultipleRooms();

    // State to manage assignments: { booking_room_id: room_id }
    const [assignments, setAssignments] = useState<Record<number, number | null>>({});
    // Keep a copy of initial assignments coming from preview (so we can mark "pre-assigned" rooms)
    const [initialAssignments, setInitialAssignments] = useState<Record<number, number | null>>({});
    // State to track the currently selected "slot" to be assigned (optional)
    const [selectedSlot, setSelectedSlot] = useState<number | null>(null);

    useEffect(() => {
        if (visible && bookingId) {
            refetch();
        }
        // Reset state when modal is closed or bookingId changes
        setAssignments({});
        setInitialAssignments({});
        setSelectedSlot(null);
    }, [visible, bookingId, refetch]);

    // When previewData loads, prefill assignments if backend provided existing assignments
    useEffect(() => {
        if (!previewData?.data) return;
        const existing = previewData.data.existing_assignments || previewData.data.assignments || null;
        if (existing && typeof existing === 'object') {
            // expected shape: [{ booking_room_id, room_id }, ...] or { booking_room_id: room_id }
            const map: Record<number, number | null> = {};
            if (Array.isArray(existing)) {
                existing.forEach((e: any) => {
                    if (e.booking_room_id) map[e.booking_room_id] = e.room_id ?? null;
                });
            } else {
                Object.keys(existing).forEach(k => {
                    const id = Number(k);
                    map[id] = existing[k] ?? null;
                });
            }
            // Prefill both the current assignments and the initialAssignments marker
            setAssignments(map);
            setInitialAssignments(map);
        }
    }, [previewData]);

    const assignmentOptions: AssignmentOption[] = useMemo(() => (previewData?.data?.assignment_options || []) as AssignmentOption[], [previewData]);

    const handleSelectSlot = (bookingRoomId: number) => {
        setSelectedSlot(prev => (prev === bookingRoomId ? null : bookingRoomId)); // Toggle selection
    };

    const handleSelectRoom = (room: AvailableRoom) => {
        // If a slot is selected manually, assign to that slot. Otherwise auto-assign to the next unassigned slot matching room_type.
        if (selectedSlot) {
            const slot = selectedSlot;
            // toggle
            if (assignments[slot] === room.id) {
                setAssignments(prev => ({ ...prev, [slot]: null }));
                return;
            }
            // prevent double-assign
            if (Object.values(assignments).includes(room.id)) {
                message.warning(`Phòng ${room.name} đã được gán cho một suất khác.`);
                return;
            }
            setAssignments(prev => ({ ...prev, [slot]: room.id }));
            // clear selection after assign
            setSelectedSlot(null);
            return;
        }

        // Auto-assign path: find next unassigned booking_room_id in assignmentOptions with matching room_type
        const options = assignmentOptions;
        // find option that contains this room in available_rooms
        const option = options.find((opt: any) => opt.available_rooms?.some((r: any) => r.id === room.id));
        if (!option) {
            message.error('Không thể tìm suất phù hợp để gán');
            return;
        }
        // find next unassigned slot from this option
        const slotsForOption: number[] = option.booking_room_ids || [];
        const nextSlot = slotsForOption.find(slotId => !assignments[slotId]);
        if (!nextSlot) {
            message.info('Tất cả suất cho loại phòng này đã được gán.');
            return;
        }

        // prevent double-assign
        if (Object.values(assignments).includes(room.id)) {
            message.warning(`Phòng ${room.name} đã được gán cho một suất khác.`);
            return;
        }

        setAssignments(prev => ({ ...prev, [nextSlot]: room.id }));
    };

    const handleSubmit = async () => {
        const finalAssignments = Object.entries(assignments)
            .filter(([, roomId]) => roomId !== null)
            .map(([bookingRoomId, roomId]) => ({
                booking_room_id: Number(bookingRoomId),
                room_id: Number(roomId),
            }));

        const totalNeeded = assignmentOptions.reduce((acc: number, opt: AssignmentOption) => acc + (opt.rooms_needed || 0), 0);

        if (finalAssignments.length < totalNeeded) {
            message.error('Vui lòng gán đủ số lượng phòng theo yêu cầu.');
            return;
        }

        await assignRoomsMutation.mutateAsync(finalAssignments, {
            onSuccess: () => {
                // message.success('Gán phòng thành công!');
                // Prefer the explicit callback (booking-detail integration), fallback to legacy onUpdate
                try {
                    if (typeof onAssignmentSuccess === 'function') onAssignmentSuccess();
                    else if (typeof onUpdate === 'function') onUpdate();
                } catch (e) {
                    // swallow callback errors
                }
                onClose();
            },
            onError: (err: any) => {
                message.error(`Lỗi khi gán phòng: ${err.response?.data?.message || err.message}`);
            }
        });
    };

    const assignedRoomIds = new Set<number>(Object.values(assignments).filter(Boolean).map(v => Number(v)));
    const allSlotsCount = assignmentOptions.reduce((sum: number, opt: AssignmentOption) => sum + (opt.rooms_needed || 0), 0);
    const assignedSlotsCount = Object.values(assignments).filter(Boolean).length;
    const isSubmitDisabled = assignedSlotsCount < allSlotsCount || assignRoomsMutation.isPending;

    const renderContent = () => {
        if (isLoading) return <div className="flex justify-center items-center h-64"><Spin size="large" /></div>;
        if (error) return <Alert message="Lỗi tải dữ liệu" description={(error as any).message} type="error" showIcon />;
        if (assignmentOptions.length === 0) return <Empty description="Không có thông tin gán phòng cho đơn hàng này." />;

        return (
            <div>
                {/* Auto-assignment alert */}
                {previewData?.data?.has_auto_assigned && (
                    <Alert
                        message="Phòng đã được gán tự động"
                        description="Đơn hàng này có phòng đã được hệ thống gán tự động. Bạn có thể gán lại phòng thủ công nếu muốn thay đổi."
                        type="info"
                        showIcon
                        style={{ marginBottom: 16 }}
                        icon={<Building size={16} />}
                    />
                )}

                <Row gutter={[24, 16]}>
                    {/* Left Column: Booking summary + Slots to be Assigned */}
                    <Col xs={24} md={8}>
                        <Card style={{ borderRadius: 12, marginBottom: 16 }} bodyStyle={{ padding: 12 }}>
                            <Title level={5} style={{ margin: 0 }}>Thông tin đơn</Title>
                            <div style={{ marginTop: 8, color: '#595959' }}>
                                <div>Ngày nhận: <b>{previewData?.data?.check_in_date || '-'}</b></div>
                                <div>Ngày trả: <b>{previewData?.data?.check_out_date || '-'}</b></div>

                            </div>
                        </Card>

                        <Title level={5}>Suất cần gán ({assignedSlotsCount}/{allSlotsCount})</Title>
                        <Paragraph type="secondary">Chọn một suất (bên trái) hoặc click trực tiếp 1 phòng (bên phải) để gán/huỷ gán.</Paragraph>
                        <List
                            itemLayout="horizontal"
                            dataSource={assignmentOptions.flatMap((opt: AssignmentOption) => opt.booking_room_ids)}
                            renderItem={(bookingRoomIdRaw) => {
                                const bookingRoomId = Number(bookingRoomIdRaw);
                                const assignedRoomId = assignments[bookingRoomId];
                                const assignedRoom = assignedRoomId ? assignmentOptions.flatMap((opt: AssignmentOption) => opt.available_rooms).find((r: AvailableRoom) => r.id === assignedRoomId) : null;
                                const isSelected = selectedSlot === bookingRoomId;
                                const wasPreAssigned = initialAssignments[bookingRoomId];

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
                                            avatar={<Avatar size={44} icon={<KeyRound />} style={{ backgroundColor: assignedRoom ? '#52c41a' : (wasPreAssigned ? '#1890ff' : '#f0f0f0'), color: '#fff' }} />}
                                            title={<Text strong>Suất #{bookingRoomId}</Text>}
                                            description={
                                                assignedRoom ?
                                                    <Tag color="green" icon={<CheckCircle2 size={14} />}>Đã gán: {assignedRoom.name}</Tag> :
                                                    wasPreAssigned ? <Tag color="blue">Đã được gán trước</Tag> :
                                                        <Tag color="gold">Đang chờ gán...</Tag>
                                            }
                                        />
                                    </List.Item>
                                );
                            }}
                        />
                    </Col>

                    {/* Right Column: Available Rooms grouped by room option & floor */}
                    <Col xs={24} md={16}>
                        <Title level={5}>Phòng trống khả dụng</Title>
                        <Paragraph type="secondary">
                            Hiển thị các phòng trống phù hợp với loại phòng của các suất bên trái.
                        </Paragraph>
                        <div style={{ maxHeight: '60vh', overflowY: 'auto', paddingRight: '8px' }}>
                            {assignmentOptions.map((option: AssignmentOption) => (
                                <div key={option.room_type_id} style={{ marginBottom: 18 }}>
                                    <Divider orientation="left">
                                        <Text strong>{option.room_type_name} ({option.available_rooms.length} phòng trống)</Text>
                                    </Divider>
                                    {/* Group available rooms by floor for better scanning */}
                                    {(() => {
                                        const byFloor: Record<number, AvailableRoom[]> = {};
                                        option.available_rooms.forEach(r => {
                                            const f = r.floor ?? 0;
                                            byFloor[f] = byFloor[f] || [];
                                            byFloor[f].push(r);
                                        });

                                        return Object.keys(byFloor).sort((a, b) => Number(a) - Number(b)).map(floorKey => (
                                            <div key={floorKey} style={{ marginBottom: 12 }}>
                                                <div style={{ marginBottom: 8, fontWeight: 600 }}>{floorKey === '0' ? 'Tầng khác' : `Tầng ${floorKey}`}</div>
                                                <Row gutter={[12, 12]}>
                                                    {byFloor[Number(floorKey)].map(room => {
                                                        const isAssigned = assignedRoomIds.has(room.id);
                                                        // Determine if this room was pre-assigned (from previewData) by checking initialAssignments map
                                                        const preAssignedSlot = Object.keys(initialAssignments).find(k => initialAssignments[Number(k)] === room.id);
                                                        return (
                                                            <Col xs={24} sm={12} md={8} lg={6} key={room.id}>
                                                                <motion.div layout initial={{ opacity: 0, scale: 0.96 }} animate={{ opacity: 1, scale: 1 }} transition={{ duration: 0.18 }}>
                                                                    <Card
                                                                        hoverable
                                                                        onClick={() => handleSelectRoom(room)}
                                                                        bodyStyle={{ padding: 12 }}
                                                                        style={{
                                                                            border: selectedSlot && !isAssigned ? '2px solid #1677ff' : '1px solid #f0f0f0',
                                                                            opacity: isAssigned ? 0.6 : 1,
                                                                            cursor: 'pointer',
                                                                            minHeight: 84,
                                                                        }}
                                                                    >
                                                                        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                                                                            <div>
                                                                                <div style={{ fontWeight: 700 }}>{room.name}</div>
                                                                                <div style={{ color: '#8c8c8c', fontSize: 12 }}>Tầng {room.floor ?? 'N/A'}</div>
                                                                            </div>
                                                                            <div>
                                                                                {preAssignedSlot ? <Tag color="blue">Đã gán #{preAssignedSlot}</Tag> : isAssigned ? <Tag color="green">Đã chọn</Tag> : <Tag color="default">Trống</Tag>}
                                                                            </div>
                                                                        </div>
                                                                    </Card>
                                                                </motion.div>
                                                            </Col>
                                                        );
                                                    })}
                                                </Row>
                                            </div>
                                        ));
                                    })()}
                                </div>
                            ))}
                        </div>
                    </Col>
                </Row>
            </div>
        );
    };

    return (
        <Modal
            title={
                <Flex align="center" gap={12}>
                    <Title level={4} style={{ margin: 0 }}>
                        Gán phòng cho đơn: #{previewData?.data?.booking_id}
                    </Title>
                    {previewData?.data?.has_auto_assigned && (
                        <Tag color="cyan" icon={<Building size={14} />}>
                            Có phòng gán tự động
                        </Tag>
                    )}
                </Flex>
            }
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
                    {previewData?.data?.has_auto_assigned ? 'Gán lại phòng' : 'Xác nhận gán phòng'}
                </Button>,
            ]}
        >
            {renderContent()}
        </Modal>
    );
};

export default RoomSelectionModal;
