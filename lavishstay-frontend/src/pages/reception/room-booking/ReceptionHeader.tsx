import React from 'react';
import { Row, Col, Typography } from 'antd';
import { HomeOutlined, CheckCircleOutlined, DollarOutlined } from '@ant-design/icons';
import { formatVND } from '../../../utils/helpers';

const { Title, Text } = Typography;

interface ReceptionHeaderProps {
    availableRoomsCount: number;
    selectedRoomsCount: number;
    totalAmount: number;
}

const ReceptionHeader: React.FC<ReceptionHeaderProps> = ({
    availableRoomsCount,
    selectedRoomsCount,
    totalAmount
}) => {
    return (
        <div style={{
            backgroundColor: '#ffffff',
            borderBottom: '1px solid #f0f0f0',
            padding: '16px 32px',
            boxShadow: '0 2px 8px rgba(0,0,0,0.06)',
            position: 'sticky',
            zIndex: 10
        }}>
            <Row align="middle" justify="space-between" style={{ height: '94px' }}>
                <Col>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '16px' }}>
                        <div style={{
                            width: '48px',
                            height: '48px',
                            backgroundColor: '#1a202c',
                            borderRadius: '12px',
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center'
                        }}>
                            <HomeOutlined style={{ color: '#ffffff', fontSize: '20px' }} />
                        </div>
                        <div>
                            <Title level={3} style={{
                                margin: 0,
                                color: '#1a202c',
                                fontSize: '20px',
                                fontWeight: 600,
                                lineHeight: 1.2
                            }}>
                                Hệ thống Lễ tân
                            </Title>
                            <Text style={{
                                color: '#64748b',
                                fontSize: '14px',
                                margin: 0,
                                display: 'block'
                            }}>
                                Quản lý đặt phòng khách sạn 
                            </Text>
                        </div>
                    </div>
                </Col>

                <Col>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '24px' }}>
                        {/* Phòng khả dụng */}
                        <div style={{ textAlign: 'center' }}>
                            <div style={{
                                backgroundColor: '#eff6ff',
                                borderRadius: '12px',
                                padding: '16px 20px',
                                minWidth: '120px',
                                border: '1px solid #e0f2fe'
                            }}>
                                <HomeOutlined style={{
                                    color: '#3b82f6',
                                    fontSize: '18px',
                                    display: 'block',
                                    marginBottom: '4px'
                                }} />
                                <div style={{
                                    fontSize: '24px',
                                    fontWeight: 'bold',
                                    color: '#1e40af',
                                    lineHeight: 1
                                }}>
                                    {availableRoomsCount}
                                </div>
                                <div style={{
                                    fontSize: '12px',
                                    color: '#64748b',
                                    marginTop: '4px'
                                }}>
                                    Phòng khả dụng
                                </div>
                            </div>
                        </div>

                        {/* Đã chọn */}
                        <div style={{ textAlign: 'center' }}>
                            <div style={{
                                backgroundColor: selectedRoomsCount > 0 ? '#f0fdf4' : '#f8fafc',
                                borderRadius: '12px',
                                padding: '16px 20px',
                                minWidth: '120px',
                                border: selectedRoomsCount > 0 ? '1px solid #dcfce7' : '1px solid #e2e8f0'
                            }}>
                                <CheckCircleOutlined style={{
                                    color: selectedRoomsCount > 0 ? '#22c55e' : '#94a3b8',
                                    fontSize: '18px',
                                    display: 'block',
                                    marginBottom: '4px'
                                }} />
                                <div style={{
                                    fontSize: '24px',
                                    fontWeight: 'bold',
                                    color: selectedRoomsCount > 0 ? '#16a34a' : '#94a3b8',
                                    lineHeight: 1
                                }}>
                                    {selectedRoomsCount}
                                </div>
                                <div style={{
                                    fontSize: '12px',
                                    color: '#64748b',
                                    marginTop: '4px'
                                }}>
                                    Đã chọn
                                </div>
                            </div>
                        </div>

                        {/* Tổng tiền */}
                        <div style={{ textAlign: 'center' }}>
                            <div style={{
                                backgroundColor: totalAmount > 0 ? '#fff7ed' : '#f8fafc',
                                borderRadius: '12px',
                                padding: '16px 20px',
                                minWidth: '160px',
                                border: totalAmount > 0 ? '1px solid #fed7aa' : '1px solid #e2e8f0'
                            }}>
                                <DollarOutlined style={{
                                    color: totalAmount > 0 ? '#ea580c' : '#94a3b8',
                                    fontSize: '18px',
                                    display: 'block',
                                    marginBottom: '4px'
                                }} />
                                <div style={{
                                    fontSize: '18px',
                                    fontWeight: 'bold',
                                    color: totalAmount > 0 ? '#c2410c' : '#94a3b8',
                                    lineHeight: 1
                                }}>
                                    {totalAmount > 0 ? formatVND(totalAmount) : '0 VNĐ'}
                                </div>
                                <div style={{
                                    fontSize: '12px',
                                    color: '#64748b',
                                    marginTop: '4px'
                                }}>
                                    Tổng tiền
                                </div>
                            </div>
                        </div>
                    </div>
                </Col>
            </Row>
        </div>
    );
};


export default ReceptionHeader;
