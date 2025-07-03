import { RoomStatus } from '../types/room';

export const statusColorMap: Record<RoomStatus, string> = {
    occupied: 'bg-red-500',
    available: 'bg-green-500',
    empty: 'bg-green-500',
    cleaning: 'bg-orange-400',
    maintenance: 'bg-gray-400',
    no_show: 'bg-pink-400',
    check_in: 'bg-blue-500',
    check_out: 'bg-purple-500',
    deposited: 'bg-yellow-500',
};

export const statusTextMap: Record<RoomStatus, string> = {
    occupied: 'Khách nghỉ ngơi',
    available: 'Phòng trống',
    empty: 'Phòng trống',
    cleaning: 'Phòng dọn khách',
    maintenance: 'Phòng đang sửa',
    no_show: 'Không đến',
    check_in: 'Phòng đón khách',
    check_out: 'Phòng đang có khách ở',
    deposited: 'Khách đã cọc tiền',
};

export const statusOptions = [
    { value: 'available', label: 'Phòng trống', color: '#52c41a' },
    { value: 'check_in', label: 'Phòng đón khách', color: '#1890ff' },
    { value: 'occupied', label: 'Khách nghỉ ngơi', color: '#52c41a' },
    { value: 'check_out', label: 'Phòng đang có khách ở', color: '#722ed1' },
    { value: 'no_show', label: 'Không đến', color: '#ff4d4f' },
    { value: 'maintenance', label: 'Phòng đang sửa', color: '#8c8c8c' },
    { value: 'deposited', label: 'Khách đã cọc tiền', color: '#faad14' },
];

export const fullCalendarStatusColors = {
    available: '#52c41a',       // Phòng trống - xanh lá
    check_in: '#1890ff',        // Phòng đón khách - xanh dương
    occupied: '#52c41a',        // Khách nghỉ ngơi - xanh lá
    check_out: '#722ed1',       // Phòng đang có khách ở - tím
    no_show: '#ff4d4f',         // Không đến - đỏ
    maintenance: '#8c8c8c',     // Phòng đang sửa - xám
    deposited: '#faad14',       // Khách đã cọc tiền - vàng
};
