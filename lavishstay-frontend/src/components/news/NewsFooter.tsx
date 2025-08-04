// src/components/news/NewsFooter.tsx
import React from 'react';
import { Layout, Row, Col, Typography, Space, Divider, Button } from 'antd';
import { motion } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import {
    FacebookOutlined,
    TwitterOutlined,
    YoutubeOutlined,
    InstagramOutlined,
    LinkedinOutlined,
    MailOutlined,
    PhoneOutlined,
    EnvironmentOutlined,
    RightOutlined,
    HeartOutlined
} from '@ant-design/icons';

const { Footer } = Layout;
const { Title, Text, Link } = Typography;

interface FooterLink {
    title: string;
    href: string;
}

interface FooterSection {
    title: string;
    links: FooterLink[];
}

const NewsFooter: React.FC = () => {
    const { t } = useTranslation();

    const footerSections: FooterSection[] = [
        {
            title: t('news.footer.news', 'Tin tức'),
            links: [
                { title: 'Thời sự', href: '/news/thoi-su' },
                { title: 'Kinh tế', href: '/news/kinh-te' },
                { title: 'Thế giới', href: '/news/the-gioi' },
                { title: 'Thể thao', href: '/news/the-thao' },
                { title: 'Công nghệ', href: '/news/cong-nghe' },
            ]
        },
        {
            title: t('news.footer.entertainment', 'Giải trí'),
            links: [
                { title: 'Âm nhạc', href: '/news/am-nhac' },
                { title: 'Phim ảnh', href: '/news/phim-anh' },
                { title: 'Sao Việt', href: '/news/sao-viet' },
                { title: 'Sao Quốc tế', href: '/news/sao-quoc-te' },
                { title: 'Thời trang', href: '/news/thoi-trang' },
            ]
        },
        {
            title: t('news.footer.lifestyle', 'Đời sống'),
            links: [
                { title: 'Sức khỏe', href: '/news/suc-khoe' },
                { title: 'Gia đình', href: '/news/gia-dinh' },
                { title: 'Du lịch', href: '/news/du-lich' },
                { title: 'Ẩm thực', href: '/news/am-thuc' },
                { title: 'Xe cộ', href: '/news/xe-co' },
            ]
        },
        {
            title: t('news.footer.services', 'Dịch vụ'),
            links: [
                { title: 'Về chúng tôi', href: '/about' },
                { title: 'Liên hệ', href: '/contact' },
                { title: 'Quảng cáo', href: '/advertising' },
                { title: 'Tuyển dụng', href: '/careers' },
                { title: 'RSS', href: '/rss' },
            ]
        }
    ];

    const socialLinks = [
        {
            icon: <FacebookOutlined />,
            href: 'https://facebook.com',
            color: '#1877f2',
            name: 'Facebook'
        },
        {
            icon: <TwitterOutlined />,
            href: 'https://twitter.com',
            color: '#1da1f2',
            name: 'Twitter'
        },
        {
            icon: <YoutubeOutlined />,
            href: 'https://youtube.com',
            color: '#ff0000',
            name: 'YouTube'
        },
        {
            icon: <InstagramOutlined />,
            href: 'https://instagram.com',
            color: '#e4405f',
            name: 'Instagram'
        },
        {
            icon: <LinkedinOutlined />,
            href: 'https://linkedin.com',
            color: '#0077b5',
            name: 'LinkedIn'
        },
    ];

    const contactInfo = [
        {
            icon: <EnvironmentOutlined className="text-red-500" />,
            text: '123 Đường ABC, Quận 1, TP.HCM'
        },
        {
            icon: <PhoneOutlined className="text-green-500" />,
            text: '(028) 1234-5678'
        },
        {
            icon: <MailOutlined className="text-blue-500" />,
            text: 'contact@lavishstay.com'
        }
    ];

    const containerVariants = {
        hidden: { opacity: 0 },
        visible: {
            opacity: 1,
            transition: {
                duration: 0.6,
                staggerChildren: 0.1
            }
        }
    };

    const itemVariants = {
        hidden: { opacity: 0, y: 20 },
        visible: {
            opacity: 1,
            y: 0,
            transition: { duration: 0.5 }
        }
    };

    return (
        <Footer className="bg-gray-900 text-white py-12 mt-16">
            <motion.div
                variants={containerVariants}
                initial="hidden"
                whileInView="visible"
                viewport={{ once: true, margin: "-100px" }}
                className="max-w-7xl mx-auto px-4"
            >
                <Row gutter={[32, 32]}>
                    {/* Brand Section */}
                    <Col xs={24} sm={24} md={6}>
                        <motion.div variants={itemVariants} className="space-y-6">
                            <div>
                                <Title level={2} className="text-white mb-2">
                                    LavishStay News
                                </Title>
                                <Text className="text-gray-400 leading-relaxed">
                                    {t('news.footer.description', 'Kênh tin tức uy tín, cập nhật nhanh chóng và chính xác nhất về các sự kiện trong nước và quốc tế.')}
                                </Text>
                            </div>

                            {/* Social Links */}
                            <div>
                                <Title level={5} className="text-white mb-4">
                                    {t('news.footer.followUs', 'Theo dõi chúng tôi')}
                                </Title>
                                <Space size="middle">
                                    {socialLinks.map((social, index) => (
                                        <motion.div
                                            key={social.name}
                                            whileHover={{ scale: 1.2, y: -2 }}
                                            whileTap={{ scale: 0.9 }}
                                        >
                                            <Button
                                                type="text"
                                                icon={social.icon}
                                                size="large"
                                                href={social.href}
                                                target="_blank"
                                                className="text-gray-400 hover:text-white border-gray-600 hover:border-white"
                                                style={{
                                                    borderColor: 'transparent',
                                                    color: social.color
                                                }}
                                            />
                                        </motion.div>
                                    ))}
                                </Space>
                            </div>

                            {/* Contact Info */}
                            <div className="space-y-3">
                                {contactInfo.map((info, index) => (
                                    <motion.div
                                        key={index}
                                        variants={itemVariants}
                                        className="flex items-center space-x-3"
                                    >
                                        {info.icon}
                                        <Text className="text-gray-400 text-sm">
                                            {info.text}
                                        </Text>
                                    </motion.div>
                                ))}
                            </div>
                        </motion.div>
                    </Col>

                    {/* Footer Sections */}
                    {footerSections.map((section, index) => (
                        <Col key={section.title} xs={12} sm={12} md={4}>
                            <motion.div variants={itemVariants}>
                                <Title level={5} className="text-white mb-4">
                                    {section.title}
                                </Title>
                                <ul className="space-y-2">
                                    {section.links.map((link, linkIndex) => (
                                        <motion.li
                                            key={link.title}
                                            initial={{ opacity: 0, x: -10 }}
                                            whileInView={{ opacity: 1, x: 0 }}
                                            transition={{ delay: linkIndex * 0.05 }}
                                            viewport={{ once: true }}
                                        >
                                            <Link
                                                href={link.href}
                                                className="text-gray-400 hover:text-white text-sm flex items-center group transition-all duration-300"
                                            >
                                                <RightOutlined className="mr-2 text-xs opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all duration-300" />
                                                <span>{link.title}</span>
                                            </Link>
                                        </motion.li>
                                    ))}
                                </ul>
                            </motion.div>
                        </Col>
                    ))}

                    {/* Newsletter Section */}
                    <Col xs={24} sm={24} md={6}>
                        <motion.div variants={itemVariants} className="space-y-4">
                            <Title level={5} className="text-white">
                                {t('news.footer.newsletter', 'Đăng ký nhận tin')}
                            </Title>
                            <Text className="text-gray-400 text-sm">
                                {t('news.footer.newsletterDesc', 'Nhận những tin tức mới nhất từ chúng tôi')}
                            </Text>
                            <div className="space-y-3">
                                <input
                                    type="email"
                                    placeholder={t('news.footer.emailPlaceholder', 'Nhập email của bạn')}
                                    className="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all"
                                />
                                <motion.div
                                    whileHover={{ scale: 1.05 }}
                                    whileTap={{ scale: 0.95 }}
                                >
                                    <Button
                                        type="primary"
                                        size="large"
                                        className="w-full bg-gradient-to-r from-blue-500 to-purple-600 border-none hover:from-blue-600 hover:to-purple-700"
                                    >
                                        {t('news.footer.subscribe', 'Đăng ký')}
                                    </Button>
                                </motion.div>
                            </div>
                        </motion.div>
                    </Col>
                </Row>

                <Divider className="border-gray-700 my-8" />

                {/* Bottom Section */}
                <motion.div
                    variants={itemVariants}
                    className="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0"
                >
                    <div className="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-6">
                        <Text className="text-gray-400 text-sm">
                            © 2024 LavishStay News. {t('news.footer.allRightsReserved', 'Tất cả quyền được bảo lưu.')}
                        </Text>
                        <div className="flex items-center space-x-4">
                            <Link href="/privacy" className="text-gray-400 hover:text-white text-sm">
                                {t('news.footer.privacy', 'Chính sách bảo mật')}
                            </Link>
                            <Text className="text-gray-600">|</Text>
                            <Link href="/terms" className="text-gray-400 hover:text-white text-sm">
                                {t('news.footer.terms', 'Điều khoản sử dụng')}
                            </Link>
                        </div>
                    </div>

                    <motion.div
                        whileHover={{ scale: 1.05 }}
                        className="flex items-center space-x-2 text-gray-400"
                    >
                        <Text className="text-sm">
                            {t('news.footer.madeWith', 'Được tạo với')}
                        </Text>
                        <HeartOutlined className="text-red-500 animate-pulse" />
                        <Text className="text-sm">
                            {t('news.footer.inVietnam', 'tại Việt Nam')}
                        </Text>
                    </motion.div>
                </motion.div>
            </motion.div>
        </Footer>
    );
};

export default NewsFooter;
