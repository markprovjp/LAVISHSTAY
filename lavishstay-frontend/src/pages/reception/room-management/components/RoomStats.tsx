import React from 'react';
import { Card, Statistic, Row, Col, Badge, Typography } from 'antd';
import {
    HomeOutlined,
    UserOutlined,
    ToolOutlined,
    ClockCircleOutlined,
    CheckCircleOutlined,
    DollarOutlined
} from '@ant-design/icons';

const { Title } = Typography;

interface RoomStatsProps {
    stats: {
        total: number;
        available: number;
        occupied: number;
        maintenance: number;
        reserved: number;
        occupancyRate: number;
        revenue: number;
    };
    loading?: boolean;
}

const RoomStats: React.FC<RoomStatsProps> = ({ stats, loading = false }) => {
    return (
        <Row gutter={16} style={{ marginBottom: 24 }}>
            <Col xs={24} sm={12} md={6}>
                <Card size="small">
                    <Statistic
                        title="Tổng số phòng"
                        value={stats.total}
                        prefix={<HomeOutlined />}
                        loading={loading}
                    />
                </Card>
            </Col>
            <Col xs={24} sm={12} md={6}>
                <Card size="small">
                    <Statistic
                        title="Phòng trống"
                        value={stats.available}
                        prefix={<CheckCircleOutlined />}
                        valueStyle={{ color: '#52c41a' }}
                        loading={loading}
                    />
                </Card>
            </Col>
            <Col xs={24} sm={12} md={6}>
                <Card size="small">
                    <Statistic
                        title="Có khách"
                        value={stats.occupied}
                        prefix={<UserOutlined />}
                        valueStyle={{ color: '#1890ff' }}
                        loading={loading}
                    />
                </Card>
            </Col>
            <Col xs={24} sm={12} md={6}>
                <Card size="small">
                    <Statistic
                        title="Tỷ lệ lấp đầy"
                        value={stats.occupancyRate}
                        suffix="%"
                        precision={1}
                        valueStyle={{ color: stats.occupancyRate > 80 ? '#52c41a' : '#faad14' }}
                        loading={loading}
                    />
                </Card>
            </Col>
            <Col xs={24} sm={12} md={6}>
                <Card size="small">
                    <Statistic
                        title="Bảo trì"
                        value={stats.maintenance}
                        prefix={<ToolOutlined />}
                        valueStyle={{ color: '#faad14' }}
                        loading={loading}
                    />
                </Card>
            </Col>
            <Col xs={24} sm={12} md={6}>
                <Card size="small">
                    <Statistic
                        title="Đã đặt"
                        value={stats.reserved}
                        prefix={<ClockCircleOutlined />}
                        valueStyle={{ color: '#722ed1' }}
                        loading={loading}
                    />
                </Card>
            </Col>
            <Col xs={24} sm={12} md={6}>
                <Card size="small">
                    <Statistic
                        title="Doanh thu hôm nay"
                        value={stats.revenue}
                        prefix={<DollarOutlined />}
                        suffix="VNĐ"
                        precision={0}
                        valueStyle={{ color: '#1890ff' }}
                        loading={loading}
                    />
                </Card>
            </Col>
            <Col xs={24} sm={12} md={6}>
                <Card size="small" style={{ textAlign: 'center' }}>
                    <Title level={5} style={{ margin: 0, color: '#8c8c8c' }}>
                        Trạng thái tổng quan
                    </Title>
                    <div style={{ marginTop: 8 }}>
                        <Badge
                            status={stats.occupancyRate > 90 ? 'error' : stats.occupancyRate > 70 ? 'warning' : 'success'}
                            text={
                                stats.occupancyRate > 90 ? 'Gần đầy' :
                                    stats.occupancyRate > 70 ? 'Bình thường' : 'Còn nhiều phòng'
                            }
                        />
                    </div>
                </Card>
            </Col>
        </Row>
    );
};

export default RoomStats;
