import React from 'react';
import { Card, Table, Button, Tag, Space, Typography, message, Modal } from 'antd';
import { ToolOutlined, CheckCircleOutlined, ExclamationCircleOutlined } from '@ant-design/icons';

const { Title } = Typography;

const MaintenanceManagement: React.FC = () => {
    const maintenanceRooms = [
        {
            id: 1,
            roomNumber: '201',
            type: 'Suite',
            issue: 'Máy lạnh không hoạt động',
            priority: 'high',
            status: 'requested',
            reportedBy: 'Lễ tân',
            reportedAt: '2024-06-14 09:00',
            estimatedTime: '2 giờ'
        },
        {
            id: 2,
            roomNumber: '105',
            type: 'Deluxe',
            issue: 'Vòi nước bồn tắm bị rò',
            priority: 'medium',
            status: 'in-progress',
            reportedBy: 'Housekeeping',
            reportedAt: '2024-06-14 10:30',
            estimatedTime: '1 giờ'
        },
        {
            id: 3,
            roomNumber: '307',
            type: 'Premium',
            issue: 'Bóng đèn hỏng',
            priority: 'low',
            status: 'completed',
            reportedBy: 'Khách hàng',
            reportedAt: '2024-06-13 16:00',
            estimatedTime: '30 phút'
        }
    ];

    const getPriorityColor = (priority: string) => {
        switch (priority) {
            case 'high': return 'red';
            case 'medium': return 'orange';
            case 'low': return 'green';
            default: return 'default';
        }
    };

    const getPriorityText = (priority: string) => {
        switch (priority) {
            case 'high': return 'Khẩn cấp';
            case 'medium': return 'Trung bình';
            case 'low': return 'Thấp';
            default: return priority;
        }
    };

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'requested': return 'orange';
            case 'in-progress': return 'blue';
            case 'completed': return 'green';
            default: return 'default';
        }
    };

    const getStatusText = (status: string) => {
        switch (status) {
            case 'requested': return 'Chờ xử lý';
            case 'in-progress': return 'Đang xử lý';
            case 'completed': return 'Hoàn thành';
            default: return status;
        }
    };

    const handleStartMaintenance = (record: any) => {
        Modal.confirm({
            title: 'Xác nhận bắt đầu bảo trì',
            content: `Bắt đầu bảo trì phòng ${record.roomNumber}?`,
            onOk() {
                message.success(`Đã bắt đầu bảo trì phòng ${record.roomNumber}`);
            }
        });
    };

    const handleCompleteMaintenance = (record: any) => {
        Modal.confirm({
            title: 'Xác nhận hoàn thành bảo trì',
            content: `Xác nhận hoàn thành bảo trì phòng ${record.roomNumber}?`,
            onOk() {
                message.success(`Đã hoàn thành bảo trì phòng ${record.roomNumber}`);
            }
        });
    };

    const columns = [
        {
            title: 'Phòng',
            dataIndex: 'roomNumber',
            key: 'roomNumber',
            render: (text: string) => <strong>{text}</strong>
        },
        {
            title: 'Loại phòng',
            dataIndex: 'type',
            key: 'type',
            render: (type: string) => <Tag color="blue">{type}</Tag>
        },
        {
            title: 'Vấn đề',
            dataIndex: 'issue',
            key: 'issue'
        },
        {
            title: 'Độ ưu tiên',
            dataIndex: 'priority',
            key: 'priority',
            render: (priority: string) => (
                <Tag color={getPriorityColor(priority)}>
                    {getPriorityText(priority)}
                </Tag>
            )
        },
        {
            title: 'Trạng thái',
            dataIndex: 'status',
            key: 'status',
            render: (status: string) => (
                <Tag color={getStatusColor(status)}>
                    {getStatusText(status)}
                </Tag>
            )
        },
        {
            title: 'Người báo',
            dataIndex: 'reportedBy',
            key: 'reportedBy'
        },
        {
            title: 'Thời gian ước tính',
            dataIndex: 'estimatedTime',
            key: 'estimatedTime'
        },
        {
            title: 'Thao tác',
            key: 'action',
            render: (record: any) => (
                <Space>
                    {record.status === 'requested' && (
                        <Button
                            type="primary"
                            icon={<ToolOutlined />}
                            onClick={() => handleStartMaintenance(record)}
                            size="small"
                        >
                            Bắt đầu
                        </Button>
                    )}
                    {record.status === 'in-progress' && (
                        <Button
                            type="primary"
                            icon={<CheckCircleOutlined />}
                            onClick={() => handleCompleteMaintenance(record)}
                            size="small"
                        >
                            Hoàn thành
                        </Button>
                    )}
                    <Button icon={<ExclamationCircleOutlined />} size="small">
                        Chi tiết
                    </Button>
                </Space>
            )
        }
    ];

    return (
        <div>
            <Title level={3} className="mb-4">
                🔧 Quản lý bảo trì phòng
            </Title>

            <Card>
                <Table
                    columns={columns}
                    dataSource={maintenanceRooms}
                    rowKey="id"
                    pagination={false}
                />
            </Card>
        </div>
    );
};

export default MaintenanceManagement;
