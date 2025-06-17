import React from 'react';
import { Card, Table, Button, Tag, Space, Typography, message } from 'antd';
import { LogoutOutlined, UserOutlined, DollarOutlined } from '@ant-design/icons';

const { Title } = Typography;

const CheckOutManagement: React.FC = () => {
    const todayCheckOuts = [
        {
            id: 1,
            roomNumber: '102',
            guestName: 'Trần Thị B',
            phone: '0907654321',
            email: 'tranthib@email.com',
            checkOutTime: '12:00',
            status: 'pending',
            roomType: 'Premium',
            totalAmount: 4000000,
            paymentStatus: 'paid'
        },
        {
            id: 2,
            roomNumber: '301',
            guestName: 'Hoàng Văn E',
            phone: '0934567890',
            email: 'hoangvane@email.com',
            checkOutTime: '11:30',
            status: 'completed',
            roomType: 'Suite',
            totalAmount: 6000000,
            paymentStatus: 'pending'
        }
    ];

    const handleCheckOut = (record: any) => {
        message.success(`Check-out thành công cho khách ${record.guestName} - Phòng ${record.roomNumber}`);
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
            title: 'Giờ check-out',
            dataIndex: 'checkOutTime',
            key: 'checkOutTime'
        },
        {
            title: 'Tổng tiền',
            dataIndex: 'totalAmount',
            key: 'totalAmount',
            render: (amount: number) => (
                <span style={{ color: '#52c41a', fontWeight: 'bold' }}>
                    {amount.toLocaleString('vi-VN')} VNĐ
                </span>
            )
        },
        {
            title: 'Thanh toán',
            dataIndex: 'paymentStatus',
            key: 'paymentStatus',
            render: (status: string) => (
                <Tag color={status === 'paid' ? 'green' : 'red'}>
                    {status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán'}
                </Tag>
            )
        },
        {
            title: 'Trạng thái',
            dataIndex: 'status',
            key: 'status',
            render: (status: string) => (
                <Tag color={status === 'completed' ? 'green' : 'orange'}>
                    {status === 'completed' ? 'Đã check-out' : 'Chờ check-out'}
                </Tag>
            )
        },
        {
            title: 'Thao tác',
            key: 'action',
            render: (record: any) => (
                <Space>
                    {record.status === 'pending' && (
                        <>
                            {record.paymentStatus === 'pending' && (
                                <Button
                                    icon={<DollarOutlined />}
                                    size="small"
                                    type="default"
                                >
                                    Thu tiền
                                </Button>
                            )}
                            <Button
                                type="primary"
                                icon={<LogoutOutlined />}
                                onClick={() => handleCheckOut(record)}
                                size="small"
                                disabled={record.paymentStatus === 'pending'}
                            >
                                Check-out
                            </Button>
                        </>
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
                🚪 Quản lý Check-out hôm nay
            </Title>

            <Card>
                <Table
                    columns={columns}
                    dataSource={todayCheckOuts}
                    rowKey="id"
                    pagination={false}
                />
            </Card>
        </div>
    );
};

export default CheckOutManagement;
