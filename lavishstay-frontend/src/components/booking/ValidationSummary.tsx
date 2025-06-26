import React from 'react';
import { Alert, Space, Typography, Tag, Progress } from 'antd';
import { TeamOutlined } from '@ant-design/icons';

const { Text } = Typography;

interface ValidationSummaryProps {
    totalGuests: number;
    totalAdults: number;
    totalChildren: number;
    totalCapacity: number;
    selectedRoomsCount: number;
}

const ValidationSummary: React.FC<ValidationSummaryProps> = ({
    totalGuests,
    totalAdults,
    totalChildren,
    totalCapacity,
    selectedRoomsCount
}) => {
    const hasEnoughCapacity = totalCapacity >= totalGuests;
    const missingCapacity = hasEnoughCapacity ? 0 : totalGuests - totalCapacity;
    const capacityPercentage = Math.min((totalCapacity / totalGuests) * 100, 100);

    return (
        <Space direction="vertical" className="w-full" size="small">
            {/* Guest Summary */}
            <div className="bg-gray-50 p-3 rounded-lg">
                <div className="flex items-center justify-between mb-2">
                    <Text strong>
                        <TeamOutlined className="mr-1" />
                        Thông tin khách
                    </Text>
                    <div className="flex gap-2">
                        <Tag color="blue">{totalAdults} người lớn</Tag>
                        {totalChildren > 0 && <Tag color="green">{totalChildren} trẻ em</Tag>}
                    </div>
                </div>
                <div className="text-sm text-gray-600">
                    Tổng: {totalGuests} khách • {selectedRoomsCount} phòng đã chọn
                </div>
            </div>

            {/* Capacity Validation */}
            <div>
                <div className="flex items-center justify-between mb-2">
                    <Text strong>Kiểm tra chỗ ngồi</Text>
                    <Text className={hasEnoughCapacity ? 'text-green-600' : 'text-red-600'}>
                        {totalCapacity}/{totalGuests} chỗ
                    </Text>
                </div>
                
                <Progress
                    percent={capacityPercentage}
                    status={hasEnoughCapacity ? 'success' : 'exception'}
                    strokeColor={hasEnoughCapacity ? '#52c41a' : '#ff4d4f'}
                    showInfo={false}
                    size="small"
                />

                <div className="mt-2">
                    {hasEnoughCapacity ? (
                        <Alert
                            message="✅ Đủ chỗ cho tất cả khách"
                            type="success"
                            showIcon={false}
                            className="text-sm py-1"
                        />
                    ) : (
                        <Alert
                            message={`❌ Thiếu ${missingCapacity} chỗ`}
                            description="Vui lòng chọn thêm phòng hoặc loại phòng lớn hơn"
                            type="error"
                            showIcon={false}
                            className="text-sm py-1"
                        />
                    )}
                </div>
            </div>

            {/* Helpful Tips */}
            <div className="text-xs text-gray-500 space-y-1">
                <div>💡 Trẻ em có thể ở chung phòng với bố mẹ</div>
                <div>🔒 Hệ thống sẽ kiểm tra trước khi thanh toán</div>
            </div>
        </Space>
    );
};

export default ValidationSummary;
