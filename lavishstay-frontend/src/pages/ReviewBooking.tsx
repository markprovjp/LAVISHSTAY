import React from 'react';
import {
    Layout,
    Card,
    Form,
    Input,
    Button,
    Rate,
    Typography,
    Row,
    Col,
    Divider,
    Upload,
    message,
    Descriptions,
    Space,
    Avatar,
    Radio,
    theme,
} from 'antd';
import {
    UploadOutlined,
    StarFilled,
    HomeOutlined,
    CalendarOutlined,
    DollarCircleOutlined,
    FrownOutlined,
    MehOutlined,
    SmileOutlined,
    LikeOutlined,
    DislikeOutlined,
    VideoCameraAddOutlined,
    PlusOutlined,
} from '@ant-design/icons';
import type { FormProps } from 'antd';
import type { UploadFile, UploadProps } from 'antd/es/upload/interface';

const { Content } = Layout;
const { Title, Text, Paragraph } = Typography;

// --- Mock Data Mở Rộng ---
const mockBookingDetails = {
    id: 'BK12345',
    roomName: 'Phòng Deluxe Hướng Biển',
    packageName: 'Gói Kỳ nghỉ Lãng mạn',
    pricePaid: 5500000,
    roomImageUrl: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2070&auto=format&fit=crop',
    checkInDate: '2024-07-15',
    checkOutDate: '2024-07-18',
};

// --- Type Definitions ---
interface ReviewFormValues {
    overallRating: number;
    title: string;
    tripPurpose: string;
    ratings: {
        room_cleanliness: number;
        room_comfort: number;
        room_amenities: number;
        service_reception: number;
        service_housekeeping: number;
        facilities_pool: number;
        food_breakfast: number;
        value_for_money: number;
    };
    liked: string;
    disliked: string;
    media?: {
        fileList: UploadFile[];
    };
}

// --- Custom Icons cho Rate Component (KHÔNG CÓ MÀU TĨNH) ---
const customIcons: Record<number, React.ReactNode> = {
    1: <FrownOutlined />,
    2: <FrownOutlined />,
    3: <MehOutlined />,
    4: <SmileOutlined />,
    5: <SmileOutlined />,
};


const ReviewBooking: React.FC = () => {
    const [form] = Form.useForm();
    const [loading, setLoading] = React.useState(false);
    const { token } = theme.useToken();

    const onFinish: FormProps<ReviewFormValues>['onFinish'] = (values) => {
        setLoading(true);
        console.log('Dữ liệu đánh giá cuối cùng:', values);

        setTimeout(() => {
            setLoading(false);
            message.success('Đánh giá của bạn đã được gửi thành công. Cảm ơn bạn rất nhiều!');
            form.resetFields();
        }, 1500);
    };

    const uploadProps: UploadProps = {
        action: 'https://660d2bd96ddfa2943b33731c.mockapi.io/api/upload',
        listType: "picture-card",
        maxCount: 5,
        accept: 'image/png, image/jpeg, video/mp4, video/quicktime',
        beforeUpload: file => {
            const isImage = file.type.startsWith('image/');
            const isVideo = file.type.startsWith('video/');
            
            if (!isImage && !isVideo) {
                message.error('Bạn chỉ có thể tải lên file ảnh hoặc video!');
                return Upload.LIST_IGNORE;
            }

            const isLt50M = file.size / 1024 / 1024 < 50;
            if (!isLt50M) {
                message.error('File media phải nhỏ hơn 50MB!');
                return Upload.LIST_IGNORE;
            }
            return true;
        },
    };

    const ratingCriteria = {
        "Phòng ở": [
            { name: 'room_cleanliness', label: 'Sạch sẽ' },
            { name: 'room_comfort', label: 'Thoải mái' },
            { name: 'room_amenities', label: 'Tiện nghi' },
        ],
        "Dịch vụ & Cơ sở vật chất": [
            { name: 'service_reception', label: 'Lễ tân' },
            { name: 'service_housekeeping', label: 'Buồng phòng' },
            { name: 'facilities_pool', label: 'Hồ bơi & Gym' },
        ],
        "Ẩm thực & Giá trị": [
            { name: 'food_breakfast', label: 'Bữa sáng' },
            { name: 'value_for_money', label: 'Đáng giá tiền' },
        ]
    };

    return (
        <Layout style={{ background: '#f0f2f5', minHeight: '100vh' }}>
            <Content style={{ padding: '48px 12px' }}>
                <Row justify="center">
                    <Col xs={24} sm={22} md={20} lg={16} xl={12}>
                        <Card bordered={false} style={{ boxShadow: '0 8px 24px rgba(0,0,0,0.1)' }}>
                            <Title level={2} style={{ textAlign: 'center' }}>Trải nghiệm của bạn thế nào?</Title>
                            <Paragraph style={{ textAlign: 'center', marginBottom: '32px' }} type="secondary">
                                Hãy giúp LavishStay phục vụ tốt hơn bằng cách hoàn thành bài đánh giá dưới đây.
                            </Paragraph>

                            <Card style={{ marginBottom: 32, background: '#fafafa' }} bordered={false}>
                                <Descriptions title="Tóm tắt kỳ nghỉ" column={{ xs: 1, sm: 2 }}>
                                    <Descriptions.Item label={<Space><HomeOutlined />Phòng</Space>}><Text strong>{mockBookingDetails.roomName}</Text></Descriptions.Item>
                                    <Descriptions.Item label={<Space><CalendarOutlined />Thời gian</Space>}>{mockBookingDetails.checkInDate} → {mockBookingDetails.checkOutDate}</Descriptions.Item>
                                    <Descriptions.Item label={<Space><StarFilled />Gói dịch vụ</Space>}>{mockBookingDetails.packageName}</Descriptions.Item>
                                    <Descriptions.Item label={<Space><DollarCircleOutlined />Chi phí</Space>}>{new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(mockBookingDetails.pricePaid)}</Descriptions.Item>
                                </Descriptions>
                            </Card>

                            <Form form={form} layout="vertical" onFinish={onFinish} initialValues={{ tripPurpose: 'Cặp đôi', overallRating: 5 }}>
                                <Title level={4}>1. Đánh giá nhanh</Title>
                                <Card>
                                    <Form.Item name="overallRating" label="Đánh giá tổng quan của bạn" rules={[{ required: true, message: 'Vui lòng cho điểm tổng quan!' }]} style={{ textAlign: 'center' }}>
                                        <Rate style={{ fontSize: 40 }} character={({ index = 0 }) => customIcons[index + 1]} />
                                    </Form.Item>
                                    <Form.Item name="title" label="Tiêu đề cho đánh giá" rules={[{ required: true, message: 'Vui lòng nhập tiêu đề!' }]}>
                                        <Input placeholder="Ví dụ: Một kỳ nghỉ tuyệt vời!" />
                                    </Form.Item>
                                </Card>

                                <Divider />

                                <Title level={4}>2. Mục đích chuyến đi</Title>
                                <Form.Item name="tripPurpose" rules={[{ required: true }]}>
                                    <Radio.Group>
                                        <Radio.Button value="Cặp đôi">Cặp đôi</Radio.Button>
                                        <Radio.Button value="Gia đình">Gia đình</Radio.Button>
                                        <Radio.Button value="Công tác">Công tác</Radio.Button>
                                        <Radio.Button value="Bạn bè">Bạn bè</Radio.Button>
                                        <Radio.Button value="Một mình">Một mình</Radio.Button>
                                    </Radio.Group>
                                </Form.Item>

                                <Divider />

                                <Title level={4}>3. Đánh giá chi tiết</Title>
                                {Object.entries(ratingCriteria).map(([category, items]) => (
                                    <div key={category} style={{ marginBottom: '16px' }}>
                                        <Title level={5}>{category}</Title>
                                        <Row gutter={[24, 8]}>
                                            {items.map(item => (
                                                <Col xs={24} md={12} lg={8} key={item.name}>
                                                    <Form.Item name={['ratings', item.name]} label={item.label} initialValue={4} style={{ marginBottom: 0 }}>
                                                        <Rate tooltips={['Rất tệ', 'Tệ', 'Bình thường', 'Tốt', 'Tuyệt vời']} character={({ index = 0 }) => customIcons[index + 1]} />
                                                    </Form.Item>
                                                </Col>
                                            ))}
                                        </Row>
                                    </div>
                                ))}

                                <Divider />

                                <Title level={4}>4. Chia sẻ thêm</Title>
                                <Row gutter={24}>
                                    <Col xs={24} md={12}>
                                        <Form.Item name="liked" label={<Space><LikeOutlined />Điều bạn thích nhất?</Space>} rules={[{ required: true, message: 'Hãy chia sẻ ít nhất một điều bạn thích nhé!' }]}>
                                            <Input.TextArea rows={4} placeholder="Ví dụ: Hồ bơi rất đẹp, nhân viên thân thiện..." />
                                        </Form.Item>
                                    </Col>
                                    <Col xs={24} md={12}>
                                        <Form.Item name="disliked" label={<Space><DislikeOutlined />Điều có thể được cải thiện?</Space>}>
                                            <Input.TextArea rows={4} placeholder="Ví dụ: Bữa sáng có thể đa dạng hơn..." />
                                        </Form.Item>
                                    </Col>
                                </Row>

                                <Divider />

                                <Title level={4}>5. Tải lên hình ảnh hoặc video</Title>
                                <Form.Item name="media" help="Bạn có thể tải lên tối đa 5 file ảnh/video (dưới 50MB mỗi file).">
                                    <Upload {...uploadProps}>
                                        <div>
                                            <PlusOutlined />
                                            <div style={{ marginTop: 8 }}>Tải lên</div>
                                        </div>
                                    </Upload>
                                </Form.Item>

                                <Form.Item style={{ textAlign: 'center', marginTop: '32px' }}>
                                    <Button type="primary" htmlType="submit" size="large" loading={loading} icon={<StarFilled />} style={{ minWidth: 240 }}>
                                        Hoàn tất & Gửi đánh giá
                                    </Button>
                                </Form.Item>
                            </Form>
                        </Card>
                    </Col>
                </Row>
            </Content>
        </Layout>
    );
};

export default ReviewBooking;