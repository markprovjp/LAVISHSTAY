import React from 'react';
import { Card, Table, Button, Tag, Space, Typography, message } from 'antd';
import { CheckCircleOutlined, UserOutlined } from '@ant-design/icons';

const { Title } = Typography;

const CheckInManagement: React.FC = () => {
    const todayCheckIns = [
        {
            id: 1,
            roomNumber: '103',
            guestName: 'Lê Văn C',
            phone: '0912345678',
            email: 'levanc@email.com',
            checkInTime: '14:00',
            status: 'pending',
            roomType: 'Deluxe',
            nights: 3
        },
        {
            id: 2,
            roomNumber: '205',
            guestName: 'Phạm Thị D',
            phone: '0987654321',
            email: 'phamthid@email.com',
            checkInTime: '15:30',
            status: 'completed',
            roomType: 'Premium',
            nights: 2
        }
    ];

    const handleCheckIn = (record: any) => {
        message.success(`Check-in thành công cho khách ${record.guestName} - Phòng ${record.roomNumber}`);
    };

    const columns = [
        {
            title: 'Phòng',
            dataIndex: 'roomNumber',
            key: 'roomNumber',
            render: (text: string) => <strong>{text}</strong>
        },
        {
            title: 'Khách hàng',
            dataIndex: 'guestName',
            key: 'guestName'
        },
        {
            title: 'Loại phòng',
            dataIndex: 'roomType',
            key: 'roomType',
            render: (type: string) => <Tag color="blue">{type}</Tag>
        },
        {
            title: 'Giờ check-in',
            dataIndex: 'checkInTime',
            key: 'checkInTime'
        },
        {
            title: 'Số đêm',
            dataIndex: 'nights',
            key: 'nights',
            render: (nights: number) => `${nights} đêm`
        },
        {
            title: 'Trạng thái',
            dataIndex: 'status',
            key: 'status',
            render: (status: string) => (
                <Tag color={status === 'completed' ? 'green' : 'orange'}>
                    {status === 'completed' ? 'Đã check-in' : 'Chờ check-in'}
                </Tag>
            )
        },
        {
            title: 'Thao tác',
            key: 'action',
            render: (record: any) => (
                <Space>
                    {record.status === 'pending' && (
                        <Button
                            type="primary"
                            icon={<CheckCircleOutlined />}
                            onClick={() => handleCheckIn(record)}
                            size="small"
                        >
                            Check-in
                        </Button>
                    )}
                    <Button icon={<UserOutlined />} size="small">
                        Chi tiết
                    </Button>
                </Space>
            )
        }
    ];

    return (
        <div>
            <Title level={3} className="mb-4">
                ✅ Quản lý Check-in hôm nay
            </Title>

            <Card>
                <Table
                    columns={columns}
                    dataSource={todayCheckIns}
                    rowKey="id"
                    pagination={false}
                />
            </Card>
        </div>
    );
};

export default CheckInManagement;
