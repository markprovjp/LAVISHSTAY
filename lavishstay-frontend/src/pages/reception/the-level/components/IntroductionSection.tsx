import React from 'react';
import { Row, Col, Typography, Button, Image } from 'antd';
import { motion } from 'framer-motion';

const { Title, Paragraph } = Typography;

// Logo SVG component từ file attachment
const LevelLogo: React.FC = () => (
    <svg
        xmlns="http://www.w3.org/2000/svg"
        width="144"
        height="63"
        viewBox="78.00000762939453 23 143.61557006835938 62.999603271484375"
        fill="none"
        className="level-logo"
        role="img"
        aria-hidden="true"
        focusable="false"
    >
        <path fillRule="evenodd" clipRule="evenodd" d="M168.816 23.0173C162.868 39.1323 156.953 55.155 150.994 71.3001C144.959 55.2552 138.967 39.3235 132.912 23.2225C134.114 23.2225 135.2 23.1839 136.279 23.2564C136.48 23.27 136.718 23.7009 136.83 23.9848C139.876 31.6924 142.908 39.4062 145.942 47.119C147.486 51.0407 149.027 54.9635 150.571 58.8855C150.681 59.1649 150.803 59.4399 150.98 59.8622C152.446 56.0598 153.849 52.4223 155.25 48.7848C158.463 40.4421 161.682 32.1018 164.876 23.7515C165.094 23.1811 165.365 22.9688 165.958 23.0037C166.864 23.0567 167.776 23.0173 168.816 23.0173Z" fill="#252525"></path>
        <path fillRule="evenodd" clipRule="evenodd" d="M80.1123 60.8V23H83.539V24.1349C83.539 34.7808 83.5496 45.4268 83.5197 56.0727C83.5171 56.9562 83.7269 57.1787 84.5408 57.1766C96.7814 57.1478 109.021 57.1565 121.262 57.1565H122.352V60.8H80.1123Z" fill="#252525"></path>
        <path fillRule="evenodd" clipRule="evenodd" d="M219.504 60.8H177.264V57.145H216.059V23H219.504V60.8Z" fill="#252525"></path>
        <path fillRule="evenodd" clipRule="evenodd" d="M177.264 29.3001H198.384V25.1001H177.264V29.3001Z" fill="#252525"></path>
        <path fillRule="evenodd" clipRule="evenodd" d="M101.232 29.3001H122.352V25.1001H101.232V29.3001Z" fill="#252525"></path>
        <path fillRule="evenodd" clipRule="evenodd" d="M177.264 46.0999H198.384V41.8999H177.264V46.0999Z" fill="#252525"></path>
        <path fillRule="evenodd" clipRule="evenodd" d="M101.232 46.0999H122.352V41.8999H101.232V46.0999Z" fill="#252525"></path>
        <path fillRule="evenodd" clipRule="evenodd" d="M99.1195 85.9996C98.9211 85.9825 98.7636 85.9688 98.5509 85.9503V81.9927C97.5771 81.9927 96.6588 81.9739 95.7422 82.0201C95.6459 82.0252 95.4857 82.4084 95.4805 82.6219C95.4535 83.6996 95.4681 84.7793 95.4681 85.9356C95.2739 85.9572 95.1048 85.976 94.8955 85.9989V77.693C95.0577 77.6708 95.2246 77.6482 95.4604 77.616V81.0495H98.5125V77.7142C98.7344 77.6725 98.9043 77.6403 99.1195 77.5996V85.9996Z" fill="#252525"></path>
        <path fillRule="evenodd" clipRule="evenodd" d="M118.034 81.8864H114.656V85.2639C115.623 85.2639 116.573 85.282 117.523 85.2559C117.98 85.2434 118.21 85.3835 118.052 85.9996H113.904V77.5996H118.06C118.229 78.1625 118.09 78.4024 117.552 78.3861C116.598 78.3565 115.644 78.3778 114.654 78.3778V81.0394C115.625 81.0394 116.555 81.062 117.484 81.0303C117.976 81.0133 118.222 81.1583 118.034 81.8864Z" fill="#252525"></path>
        <path fillRule="evenodd" clipRule="evenodd" d="M201.233 81.8682V85.2716C202.206 85.2716 203.156 85.2827 204.106 85.2667C204.533 85.2598 204.826 85.3441 204.655 85.9996H200.496V77.5996H204.682C204.694 77.8298 204.705 78.0641 204.72 78.3683H201.27V81.0537C202.24 81.0537 203.196 81.0756 204.152 81.045C204.652 81.029 204.795 81.2551 204.648 81.8682H201.233Z" fill="#252525"></path>
        <path fillRule="evenodd" clipRule="evenodd" d="M162.48 85.9996V77.5996H166.616C166.792 78.1168 166.734 78.4111 166.16 78.398C165.202 78.3758 164.243 78.3921 163.226 78.3921V81.0458C164.176 81.0458 165.125 81.0662 166.073 81.0378C166.605 81.0226 166.758 81.2528 166.567 81.8767H163.174C163.174 82.9164 163.152 83.8895 163.199 84.8577C163.206 85.0009 163.538 85.2269 163.727 85.2376C164.509 85.2813 165.294 85.2474 166.078 85.2609C166.604 85.2702 166.604 85.2844 166.602 85.9996H162.48Z" fill="#252525"></path>
        <path fillRule="evenodd" clipRule="evenodd" d="M179.376 77.6926C179.991 77.4526 180.21 77.7589 180.393 78.2609C181.063 80.095 181.764 81.9148 182.52 83.9152C183.135 82.3321 183.687 80.9119 184.238 79.4911C184.404 79.0604 184.593 78.6378 184.722 78.1929C184.887 77.6189 185.196 77.481 185.712 77.6949C184.682 80.3803 183.653 83.06 182.526 85.9996C181.422 83.0876 180.403 80.4003 179.376 77.6926Z" fill="#252525"></path>
        <path fillRule="evenodd" clipRule="evenodd" d="M79.7665 78.5027C79.0875 78.3942 78.5479 78.3186 78.0176 78.1972C77.9811 78.1892 78.0127 77.8368 78.0127 77.5996H82.1945C82.2828 78.1719 82.2828 78.1719 80.5458 78.5038V85.9996H79.7665V78.5027Z" fill="#252525"></path>
        <path fillRule="evenodd" clipRule="evenodd" d="M217.392 77.5996C217.676 77.612 217.907 77.622 218.212 77.6355V85.2654C219.169 85.2654 220.053 85.2788 220.937 85.2602C221.47 85.2488 221.759 85.3869 221.544 85.9996H217.392V77.5996Z" fill="#252525"></path>
        <path fillRule="evenodd" clipRule="evenodd" d="M149.727 85.9996H145.584V77.5996H146.401V83.3095C146.401 85.2474 146.401 85.2474 148.452 85.2477C148.694 85.2477 148.938 85.2626 149.179 85.246C149.786 85.2041 149.919 85.4695 149.727 85.9996Z" fill="#252525"></path>
    </svg>
);

const IntroductionSection: React.FC = () => {
    return (
        <section style={{
            padding: '120px 0',
            backgroundColor: '#fafafa',
            overflow: 'hidden'
        }}>
            <div style={{
                maxWidth: '1200px',
                margin: '0 auto',
                padding: '0 24px'
            }}>
                <Row gutter={[48, 48]} align="middle">
                    {/* Left Column - Text Content */}
                    <Col xs={24} lg={12}>
                        <motion.div
                            initial={{ opacity: 0, x: -50 }}
                            whileInView={{ opacity: 1, x: 0 }}
                            viewport={{ once: true }}
                            transition={{ duration: 0.8 }}
                        >
                            <Title
                                level={1}
                                style={{
                                    fontSize: '2.5rem',
                                    fontWeight: 400,
                                    lineHeight: 1.2,
                                    marginBottom: '32px',
                                    color: '#1a1a1a'
                                }}
                            >
                                Hãy để bản thân đắm mình trong thế giới xa hoa cùng tiện nghi độc quyền
                            </Title>

                            <Paragraph style={{
                                fontSize: '1.1rem',
                                lineHeight: 1.8,
                                color: '#666',
                                marginBottom: '40px'
                            }}>
                                Tại Meliá Vinpearl Thanh Hóa, chúng tôi chào đón bạn với những trải nghiệm
                                được thiết kế riêng, phù hợp với từng khách hàng. The Level cung cấp hàng loạt
                                các đặc quyền, dịch vụ và không gian độc quyền nhằm nâng cao mức độ hài lòng,
                                sự riêng tư và thoải mái của khách hàng.
                            </Paragraph>
                        </motion.div>
                    </Col>

                    {/* Right Column - Image */}
                    <Col xs={24} lg={12}>
                        <motion.div
                            initial={{ opacity: 0, x: 50 }}
                            whileInView={{ opacity: 1, x: 0 }}
                            viewport={{ once: true }}
                            transition={{ duration: 0.8, delay: 0.2 }}
                            style={{ position: 'relative' }}
                        >
                            <div style={{
                                position: 'relative',
                                borderRadius: '12px',
                                overflow: 'hidden',
                                boxShadow: '0 20px 60px rgba(0, 0, 0, 0.1)'
                            }}>
                                <Image
                                    src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                                    alt="Luxury Hotel Room"
                                    style={{
                                        width: '100%',
                                        height: '400px',
                                        objectFit: 'cover'
                                    }}
                                    preview={false}
                                />

                                {/* Gallery Button */}
                                {/* <Button
                                    style={{
                                        position: 'absolute',
                                        bottom: '20px',
                                        right: '20px',
                                        background: 'rgba(255, 255, 255, 0.9)',
                                        border: 'none',
                                        borderRadius: '25px',
                                        padding: '8px 20px',
                                        fontSize: '12px',
                                        fontWeight: 500,
                                        color: '#1a1a1a',
                                        backdropFilter: 'blur(10px)',
                                        boxShadow: '0 4px 15px rgba(0, 0, 0, 0.1)',
                                        transition: 'all 0.3s ease'
                                    }}
                                    onMouseEnter={(e) => {
                                        const target = e.target as HTMLElement;
                                        target.style.transform = 'translateY(-2px)';
                                        target.style.boxShadow = '0 6px 20px rgba(0, 0, 0, 0.15)';
                                    }}
                                    onMouseLeave={(e) => {
                                        const target = e.target as HTMLElement;
                                        target.style.transform = 'translateY(0)';
                                        target.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
                                    }}
                                >
                                    XEM THƯ VIỆN
                                </Button> */}
                            </div>
                        </motion.div>
                    </Col>
                </Row>

                {/* Logo Section */}
                <motion.div
                    initial={{ opacity: 0, y: 30 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true }}
                    transition={{ duration: 0.8, delay: 0.4 }}
                    style={{
                        textAlign: 'center',
                        marginTop: '80px'
                    }}
                >
                    <div style={{
                        display: 'inline-flex',
                        alignItems: 'center',
                        gap: '16px',
                        padding: '20px 40px',
                        background: 'white',
                        borderRadius: '50px',
                        boxShadow: '0 10px 30px rgba(0, 0, 0, 0.08)',
                        border: '1px solid rgba(0, 0, 0, 0.05)'
                    }}>
                        <LevelLogo />
                        <div style={{
                            fontSize: '1.2rem',
                            fontWeight: 300,
                            letterSpacing: '2px',
                            color: '#1a1a1a'
                        }}>
                            THE LEVEL
                        </div>
                    </div>
                </motion.div>
            </div>
        </section>
    );
};

export default IntroductionSection;
