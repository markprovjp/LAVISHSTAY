import { notification } from 'antd';
import { CheckCircleOutlined } from '@ant-design/icons';

export const showAddRoomNotification = (
    api: any,
    roomName: string,
    optionName: string,
    quantity: number
) => {
    api.success({
        message: (
            <span className="flex items-center gap-2">
                <span className="font-medium">Đã thêm phòng</span>
            </span>
        ),
        description: (
            <div className="text-sm">
                <div className="font-medium text-gray-800">{roomName}</div>
                <div className="text-gray-600">{optionName}</div>
                <div className="text-blue-600 mt-1">Số lượng: {quantity}</div>
            </div>
        ),
        placement: 'topRight',
        duration: 2,
        style: {
            borderRadius: '6px'
        }
    });
};
