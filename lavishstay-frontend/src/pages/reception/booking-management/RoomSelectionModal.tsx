import React, { useState, useEffect, useMemo } from 'react';
import { Modal, Button, message, Spin, Card, Typography, Row, Col, Empty, Alert, Tag, Collapse, Select , Checkbox } from 'antd';
import { Building, BedDouble, ArrowRight, Hotel } from 'lucide-react';
import { useGetAssignmentPreview, useAssignMultipleRooms } from '../../../hooks/useReception';


const { Title, Text } = Typography;
const { Panel } = Collapse;
const { Option } = Select;

interface Room {
    id: number;
    room_number: string;
    floor: number;
    room_type_name: string;
}

interface AssignmentOption {
    option_name: string;
    room_type_id: number;
    rooms_needed: number;
    available_rooms: Room[];
    booking_room_ids: number[];
    error?: string;
}

interface PreviewData {
    booking_id: string;
    check_in_date: string;
    check_out_date: string;
    assignment_options: AssignmentOption[];
}

interface RoomSelectionModalProps {
    visible: boolean;
    onClose: () => void;
    bookingId: number | null;
    onAssignmentSuccess: () => void;
}

// Interface for the assignment payload
interface Assignment {
    booking_room_id: number;
    room_id: number;
}

const RoomSelectionModal: React.FC<RoomSelectionModalProps> = ({
    visible,
    onClose,
    bookingId,
    onAssignmentSuccess
}) => {
    const { data: previewData, isLoading, error, refetch } = useGetAssignmentPreview(bookingId!);
    const assignMultipleRoomsMutation = useAssignMultipleRooms();
    const [selectedRooms, setSelectedRooms] = useState<Record<string, number | null>>({});
    const [isSubmitting, setIsSubmitting] = useState(false);

    useEffect(() => {
        if (visible && bookingId) {
            refetch();
            setSelectedRooms({}); // Reset selections when modal opens
        }
    }, [visible, bookingId, refetch]);

    const handleSelectRoom = (bookingRoomId: number, roomId: number | null) => {
        setSelectedRooms(prev => {
            const newSelectedRooms = { ...prev };

            // If a room is being unselected
            if (roomId === null) {
                newSelectedRooms[bookingRoomId] = null;
                return newSelectedRooms;
            }

            // Check if the selected room is already assigned to another booking_room_id
            for (const [key, value] of Object.entries(newSelectedRooms)) {
                if (value === roomId && Number(key) !== bookingRoomId) {
                    message.warning(`Phòng ${roomId} đã được chọn cho mục #${key}. Vui lòng bỏ chọn trước.`);
                    return prev; // Prevent selection if already assigned elsewhere
                }
            }

            newSelectedRooms[bookingRoomId] = roomId;
            return newSelectedRooms;
        });
    };

    const handleSubmit = async () => {
        const assignments: Assignment[] = Object.entries(selectedRooms)
            .filter(([, room_id]) => room_id !== null)
            .map(([booking_room_id, room_id]) => ({
                booking_room_id: Number(booking_room_id),
                room_id: room_id!,
            }));

        if (assignments.length === 0) {
            message.error('Bạn chưa chọn phòng nào để gán.');
            return;
        }

        setIsSubmitting(true);
        try {
            await assignMultipleRoomsMutation.mutateAsync(assignments);

            onAssignmentSuccess();
            onClose();
        } catch (err: any) {
            message.error('Đã có lỗi xảy ra khi gán phòng: ' + (err.response?.data?.message || err.message));
        } finally {
            setIsSubmitting(false);
        }
    };

    const assignmentOptions = previewData?.data?.assignment_options || [];
    const totalRoomsToAssign = assignmentOptions.reduce((acc, opt) => acc + (opt.rooms_needed || 0), 0);
    const totalRoomsSelected = Object.values(selectedRooms).filter(id => id !== null).length;

    return (
        <Modal
            title={<span className="text-lg font-semibold text-gray-800">Gán phòng cho Đơn hàng #{previewData?.data?.booking_id || ''}</span>}
            open={visible}
            onCancel={onClose}
            width={1200}
            footer={[
                <Button key="back" onClick={onClose} className="px-4 py-2 rounded-md">
                    Hủy
                </Button>,
                <Button
                    key="submit"
                    type="primary"
                    loading={isSubmitting || assignMultipleRoomsMutation.isPending}
                    onClick={handleSubmit}
                    disabled={totalRoomsSelected === 0}
                    className="px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white"
                >
                    Xác nhận gán {totalRoomsSelected} phòng
                </Button>,
            ]}
            className="p-4"
        >
            <Spin spinning={isLoading} tip="Đang tải dữ liệu...">
                {error && <Alert message="Lỗi tải dữ liệu" description={(error as any).message} type="error" showIcon className="mb-4" />}
                {!isLoading && !error && (
                    assignmentOptions.length > 0 ? (
                        <div className="space-y-6">
                            {assignmentOptions.map((option, index) => (
                                <Card key={index} className="shadow-lg rounded-lg border border-gray-200">
                                    <div className="flex items-center justify-between mb-4 pb-2 border-b border-gray-200">
                                        <Title level={4} className="!mb-0 text-blue-700 flex items-center">
                                            <Hotel size={24} className="mr-2" />
                                            {option.option_name} - <span className="text-gray-600 ml-2">Cần {option.rooms_needed} phòng</span>
                                        </Title>
                                        {option.error && <Alert message={option.error} type="error" showIcon className="ml-4" />}
                                    </div>

                                    <Row gutter={[24, 24]}>
                                        <Col span={10}>
                                            <Title level={5} className="!mb-4 text-gray-700">Các phòng cần gán</Title>
                                            <div className="space-y-3">
                                                {option.booking_room_ids.map(bookingRoomId => (
                                                    <div key={bookingRoomId} className="flex items-center p-2 bg-gray-50 rounded-md shadow-sm">
                                                        <Tag color="blue" className="text-base py-1 px-3 rounded-full">Mục #{bookingRoomId}</Tag>
                                                        <ArrowRight size={20} className="mx-3 text-gray-500" />
                                                        <Text className="font-semibold text-gray-800">
                                                            {selectedRooms[bookingRoomId] ? 
                                                                `Đã gán: Phòng ${option.available_rooms.find(r => r.id === selectedRooms[bookingRoomId])?.room_number}` :
                                                                "Chưa gán"
                                                            }
                                                        </Text>
                                                    </div>
                                                ))}
                                            </div>
                                        </Col>
                                        <Col span={14}>
                                            <Title level={5} className="!mb-4 text-gray-700">Phòng trống khả dụng</Title>
                                            {option.available_rooms.length > 0 ? (
                                                <RoomSelectionByFloor
                                                    availableRooms={option.available_rooms}
                                                    selectedRooms={selectedRooms}
                                                    handleSelectRoom={handleSelectRoom}
                                                    bookingRoomIds={option.booking_room_ids}
                                                />
                                            ) : (
                                                <Text type="danger" className="text-lg">Không c�� phòng trống cho loại phòng này.</Text>
                                            )}
                                        </Col>
                                    </Row>
                                </Card>
                            ))}
                        </div>
                    ) : (
                        <Empty description="Không tìm thấy thông tin gán phòng cho đơn hàng này." className="py-8" />
                    )
                )}
            </Spin>
        </Modal>
    );
};

interface RoomSelectionByFloorProps {
    availableRooms: Room[];
    selectedRooms: Record<string, number | null>;
    handleSelectRoom: (bookingRoomId: number, roomId: number | null) => void;
    bookingRoomIds: number[];
}

const RoomSelectionByFloor: React.FC<RoomSelectionByFloorProps> = ({
    availableRooms,
    selectedRooms,
    handleSelectRoom,
    bookingRoomIds,
}) => {
    const roomsGroupedByFloor = useMemo(() => {
        return availableRooms.reduce((acc, room) => {
            const floor = room.floor.toString();
            if (!acc[floor]) {
                acc[floor] = [];
            }
            acc[floor].push(room);
            return acc;
        }, {} as Record<string, Room[]>);
    }, [availableRooms]);

    const sortedFloors = Object.keys(roomsGroupedByFloor).sort((a, b) => parseInt(a) - parseInt(b));

    const getAssignedBookingRoomId = (roomId: number) => {
        for (const [bookingRoomId, assignedRoomId] of Object.entries(selectedRooms)) {
            if (assignedRoomId === roomId) {
                return Number(bookingRoomId);
            }
        }
        return null;
    };

    const handleRoomCardClick = (room: Room) => {
        const assignedToBookingRoomId = getAssignedBookingRoomId(room.id);

        if (assignedToBookingRoomId !== null) {
            // If the room is already selected, unselect it
            handleSelectRoom(assignedToBookingRoomId, null);
        } else {
            // Find the first booking_room_id that needs a room from this option
            const unassignedBookingRoomId = bookingRoomIds.find(
                (brId) => selectedRooms[brId] === null
            );

            if (unassignedBookingRoomId !== undefined) {
                // Assign the room to the found booking_room_id
                handleSelectRoom(unassignedBookingRoomId, room.id);
            } else {
                message.warning('Tất cả các mục đặt phòng đã được gán phòng. Vui lòng bỏ chọn một phòng khác trước.');
            }
        }
    };

    return (
        <Collapse accordion className="bg-white rounded-lg shadow-md">
            {sortedFloors.map(floor => (
                <Panel
                    header={<span className="font-semibold text-lg text-gray-800 flex items-center"><Building size={20} className="mr-2" /> Tầng {floor}</span>}
                    key={floor}
                    className="border-b border-gray-200 last:border-b-0"
                >
                    <Row gutter={[16, 16]} className="p-2">
                        {roomsGroupedByFloor[floor].map(room => {
                            const isSelected = Object.values(selectedRooms).includes(room.id);
                            const assignedBookingRoomId = getAssignedBookingRoomId(room.id);
                            const isAssignedToCurrentOption = assignedBookingRoomId !== null && bookingRoomIds.includes(assignedBookingRoomId);
                            const isAssignedToOtherOption = assignedBookingRoomId !== null && !bookingRoomIds.includes(assignedBookingRoomId);

                            let cardClassName = 'cursor-pointer hover:shadow-lg';
                            let tagColor = 'blue';
                            let tagText = '';

                            if (isAssignedToCurrentOption) {
                                cardClassName += ' border-blue-500 ring-2 ring-blue-200 bg-blue-50';
                                tagColor = 'green';
                                tagText = `Mục #${assignedBookingRoomId}`;
                            } else if (isAssignedToOtherOption) {
                                cardClassName += ' border-gray-400 bg-gray-100 opacity-70 cursor-not-allowed';
                                tagColor = 'red';
                                tagText = `Đã gán cho #${assignedBookingRoomId}`;
                            } else {
                                cardClassName += ' border-gray-200';
                            }

                            return (
                                <Col xs={24} sm={12} md={8} lg={6} key={room.id}>
                                    <Card
                                        className={`transition-all duration-200 ease-in-out ${cardClassName}`}
                                        bodyStyle={{ padding: '12px' }}
                                        onClick={() => !isAssignedToOtherOption && handleRoomCardClick(room)}
                                    >
                                        <div className="flex items-center justify-between">
                                            <div className="flex items-center">
                                                <BedDouble size={20} className="text-gray-600 mr-2" />
                                                <Text className="font-bold text-lg text-gray-800">{room.room_number}</Text>
                                            </div>
                                            {assignedBookingRoomId !== null && (
                                                <Tag color={tagColor} className="text-xs">{tagText}</Tag>
                                            )}
                                        </div>
                                        <Text type="secondary" className="text-sm mt-1 block">{room.room_type_name}</Text>
                                    </Card>
                                </Col>
                            );
                        })}
                    </Row>
                </Panel>
            ))}
        </Collapse>
    );
};

export default RoomSelectionModal;
