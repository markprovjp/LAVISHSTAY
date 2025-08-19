import React from 'react';
import { Row, Col, Typography, Card } from 'antd';
import { motion } from 'framer-motion';
import {
    HomeOutlined,
    CalendarOutlined,
    CustomerServiceOutlined,
    CrownOutlined,
    BarChartOutlined,
    ScheduleOutlined
} from '@ant-design/icons';

const { Title, Text } = Typography;

interface ServiceItem {
    id: number;
    icon: React.ReactNode;
    title: string;
    description: string;
}

const services: ServiceItem[] = [
    {
        id: 1,
        icon: <HomeOutlined style={{ fontSize: '2.5rem', color: '#d4af37' }} />,
        title: "Phòng Cao Cấp",
        description: "Các phòng suite sang trọng với view tuyệt đẹp và nội thất đẳng cấp"
    },
    {
        id: 2,
        icon: <CalendarOutlined style={{ fontSize: '2.5rem', color: '#d4af37' }} />,
        title: "Ưu Tiên Đặt Phòng",
        description: "Đặt phòng ưu tiên và check-in/check-out linh hoạt theo yêu cầu"
    },
    {
        id: 3,
        icon: <CustomerServiceOutlined style={{ fontSize: '2.5rem', color: '#d4af37' }} />,
        title: "Dịch Vụ Butler",
        description: "Dịch vụ butler cá nhân 24/7 để phục vụ mọi nhu cầu của quý khách"
    },
    {
        id: 4,
        icon: <CrownOutlined style={{ fontSize: '2.5rem', color: '#d4af37' }} />,
        title: "Đặc Quyền VIP",
        description: "Truy cập độc quyền vào các khu vực riêng tư và dịch vụ đặc biệt"
    },
    {
        id: 5,
        icon: <BarChartOutlined style={{ fontSize: '2.5rem', color: '#d4af37' }} />,
        title: "Spa & Wellness",
        description: "Trung tâm spa cao cấp với các liệu pháp thư giãn đẳng cấp thế giới"
    },
    {
        id: 6,
        icon: <ScheduleOutlined style={{ fontSize: '2.5rem', color: '#d4af37' }} />,
        title: "Concierge 24h",
        description: "Dịch vụ tư vấn và hỗ trợ khách hàng chuyên nghiệp suốt 24h"
    }
];

const ServicesSection: React.FC = () => {
    const containerVariants = {
        hidden: { opacity: 0 },
        visible: {
            opacity: 1,
            transition: {
                staggerChildren: 0.1
            }
        }
    };

    const itemVariants = {
        hidden: { opacity: 0, y: 30 },
        visible: {
            opacity: 1,
            y: 0,
            transition: {
                duration: 0.6,
                ease: "easeOut"
            }
        }
    };

    return (
        <section style={{
            padding: '120px 0',
            backgroundColor: '#ffffff',
            overflow: 'hidden'
        }}>
            <div style={{
                maxWidth: '1200px',
                margin: '0 auto',
                padding: '0 24px'
            }}>
                {/* Section Header */}
                <motion.div
                    initial={{ opacity: 0, y: 30 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true }}
                    transition={{ duration: 0.8 }}
                    style={{ textAlign: 'center', marginBottom: '80px' }}
                >
                    <Title
                        level={2}
                        style={{
                            fontSize: '2.5rem',
                            fontWeight: 300,
                            color: '#1a1a1a',
                            marginBottom: '24px',
                            letterSpacing: '1px'
                        }}
                    >
                        Đặc Quyền Độc Tôn
                    </Title>
                    <Text style={{
                        fontSize: '1.2rem',
                        color: '#666',
                        lineHeight: 1.6,
                        maxWidth: '600px',
                        margin: '0 auto',
                        display: 'block'
                    }}>
                        Trải nghiệm những dịch vụ đẳng cấp thế giới được thiết kế riêng cho The Level
                    </Text>
                </motion.div>

                {/* Services Grid */}
                <motion.div
                    variants={containerVariants}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                >
                    <Row gutter={[32, 48]}>
                        {services.map((service) => (
                            <Col xs={24} sm={12} lg={8} key={service.id}>
                                <motion.div variants={itemVariants}>
                                    <Card
                                        hoverable
                                        bordered={false}
                                        style={{
                                            height: '100%',
                                            borderRadius: '16px',
                                            padding: '20px',
                                            background: 'linear-gradient(135deg, #fafafa 0%, #ffffff 100%)',
                                            boxShadow: '0 4px 20px rgba(0, 0, 0, 0.04)',
                                            transition: 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)',
                                            border: '1px solid rgba(212, 175, 55, 0.1)'
                                        }}
                                        bodyStyle={{
                                            padding: 0,
                                            textAlign: 'center',
                                            height: '100%',
                                            display: 'flex',
                                            flexDirection: 'column',
                                            justifyContent: 'center'
                                        }}
                                        onMouseEnter={(e) => {
                                            const target = e.currentTarget as HTMLElement;
                                            target.style.transform = 'translateY(-8px)';
                                            target.style.boxShadow = '0 12px 40px rgba(212, 175, 55, 0.15)';
                                            target.style.borderColor = 'rgba(212, 175, 55, 0.3)';
                                        }}
                                        onMouseLeave={(e) => {
                                            const target = e.currentTarget as HTMLElement;
                                            target.style.transform = 'translateY(0)';
                                            target.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.04)';
                                            target.style.borderColor = 'rgba(212, 175, 55, 0.1)';
                                        }}
                                    >
                                        <div style={{ marginBottom: '24px' }}>
                                            <motion.div
                                                whileHover={{ scale: 1.1, rotate: 5 }}
                                                transition={{ duration: 0.3 }}
                                                style={{
                                                    display: 'inline-flex',
                                                    alignItems: 'center',
                                                    justifyContent: 'center',
                                                    width: '80px',
                                                    height: '80px',
                                                    borderRadius: '50%',
                                                    background: 'linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(212, 175, 55, 0.05) 100%)',
                                                    border: '2px solid rgba(212, 175, 55, 0.2)',
                                                    marginBottom: '16px'
                                                }}
                                            >
                                                {service.icon}
                                            </motion.div>
                                        </div>

                                        <Title
                                            level={4}
                                            style={{
                                                fontSize: '1.3rem',
                                                fontWeight: 500,
                                                color: '#1a1a1a',
                                                marginBottom: '16px',
                                                lineHeight: 1.3
                                            }}
                                        >
                                            {service.title}
                                        </Title>

                                        <Text style={{
                                            fontSize: '1rem',
                                            color: '#666',
                                            lineHeight: 1.6,
                                            textAlign: 'center'
                                        }}>
                                            {service.description}
                                        </Text>
                                    </Card>
                                </motion.div>
                            </Col>
                        ))}
                    </Row>
                </motion.div>
            </div>
        </section>
    );
};

export default ServicesSection;
