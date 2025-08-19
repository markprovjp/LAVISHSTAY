import React, { useState, useEffect } from 'react';
import { useSearchParams, useNavigate } from 'react-router-dom';
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
    Radio,
    theme,
    Alert,
    Spin,
    Result,
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
    PlusOutlined,
    CheckCircleOutlined,
} from '@ant-design/icons';
import type { FormProps } from 'antd';
import type { UploadFile, UploadProps } from 'antd/es/upload/interface';
import axiosInstance from '../utils/api';

const { Content } = Layout;
const { Title, Text, Paragraph } = Typography;

// --- Type Definitions ---
interface BookingSummary {
    id: number;
    booking_code: string;
    room_name: string;
    check_in_date: string;
    check_out_date: string;
    total_amount: number;
}

interface EligibilityResponse {
    eligible: boolean;
    reason?: string;
    booking_summary?: BookingSummary;
}

interface ReviewMediaItem {
    id: number;
    file_url: string;
    file_type: string;
    meta?: any;
}

interface ReviewResponse {
    review_id: number;
    rating: number;
    title: string;
    comment: string;
    detailed_scores?: Record<string, number>;
    pros?: string;
    cons?: string;
    travel_type?: string;
    review_date?: string;
    status?: string;
    media?: ReviewMediaItem[];
}

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

interface SubmitReviewPayload {
    rating: number;
    title: string;
    comment: string;
    detailed_scores: Record<string, number>;
    pros: string;
    cons: string;
    travel_type: string;
    review_date: string;
    media_urls: string[];
}

// --- Custom Icons cho Rate Component ---
const customIcons: Record<number, React.ReactNode> = {
    1: <FrownOutlined />,
    2: <FrownOutlined />,
    3: <MehOutlined />,
    4: <SmileOutlined />,
    5: <SmileOutlined />,
};

const ReviewBooking: React.FC = () => {
    const [searchParams] = useSearchParams();
    const navigate = useNavigate();
    const [form] = Form.useForm();
    const { token } = theme.useToken();

    // States
    const [loading, setLoading] = useState(true);
    const [submitting, setSubmitting] = useState(false);
    const [uploading, setUploading] = useState(false);
    const [eligibility, setEligibility] = useState<EligibilityResponse | null>(null);
    const [uploadedFiles, setUploadedFiles] = useState<string[]>([]);
    const [submitted, setSubmitted] = useState(false);
    const [existingReview, setExistingReview] = useState<ReviewResponse | null>(null);
    const [loadingReview, setLoadingReview] = useState(false);

    const bookingId = searchParams.get('booking');

    // Travel type mapping
    const travelTypeMapping: Record<string, string> = {
        'Cặp đôi': 'couple',
        'Gia đình': 'family_young',
        'Công tác': 'business',
        'Bạn bè': 'group',
        'Một mình': 'solo',
    };

    // Rating criteria
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

    // Check eligibility on mount
    useEffect(() => {
        if (!bookingId) {
            message.error('Không tìm thấy mã booking');
            navigate('/');
            return;
        }

        checkEligibility();
    }, [bookingId]);

    const checkEligibility = async () => {
        try {
            setLoading(true);
            const response = await axiosInstance.get(`/public/bookings/${bookingId}/review-eligibility`);
            setEligibility(response.data);
        } catch (error: any) {
            if (error.response?.data) {
                setEligibility(error.response.data);

                // If backend says already reviewed, fetch the existing review to display
                const status = error.response.status;
                const reason = (error.response.data && error.response.data.reason) ? String(error.response.data.reason) : '';
                if (status === 400 && (reason.includes('đã được đánh giá') || reason.toLowerCase().includes('đánh giá'))) {
                    fetchExistingReview();
                }
            } else {
                message.error('Lỗi khi kiểm tra điều kiện đánh giá');
                setEligibility({
                    eligible: false,
                    reason: 'Lỗi hệ thống'
                });
            }
        } finally {
            setLoading(false);
        }
    };

    const fetchExistingReview = async () => {
        if (!bookingId) return;
        try {
            setLoadingReview(true);
            const resp = await axiosInstance.get(`/public/bookings/${bookingId}/review`);
            if (resp.data && resp.data.review) {
                setExistingReview(resp.data.review);
            }
        } catch (err) {
            // ignore — keep existingReview null
            console.error('Fetch existing review error', err);
        } finally {
            setLoadingReview(false);
        }
    };

    const uploadProps: UploadProps = {
        listType: "picture-card",
        maxCount: 5,
        accept: 'image/*,video/*',
        customRequest: async (options) => {
            const { file, onSuccess, onError, onProgress } = options;

            try {
                setUploading(true);
                const formData = new FormData();
                formData.append('files[]', file as File);

                const response = await axiosInstance.post('/public/review-media/upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                    onUploadProgress: (progressEvent) => {
                        const percent = Math.round((progressEvent.loaded * 100) / (progressEvent.total || 1));
                        onProgress?.({ percent });
                    },
                });

                if (response.data.files && response.data.files.length > 0) {
                    const fileUrl = response.data.files[0].url;
                    setUploadedFiles(prev => [...prev, fileUrl]);
                    onSuccess?.(response.data);
                } else {
                    throw new Error('Không nhận được URL file');
                }
            } catch (error: any) {
                console.error('Upload error:', error);
                onError?.(error);
                message.error('Lỗi khi tải file');
            } finally {
                setUploading(false);
            }
        },
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
        onRemove: (file) => {
            // Remove from uploaded files list if needed
            setUploadedFiles(prev => prev.filter(url => !url.includes(file.name || '')));
        }
    };

    const onFinish: FormProps<ReviewFormValues>['onFinish'] = async (values) => {
        try {
            setSubmitting(true);

            // Prepare payload
            const payload: SubmitReviewPayload = {
                rating: values.overallRating,
                title: values.title,
                comment: `Điều tôi thích: ${values.liked}\n\nĐiều cần cải thiện: ${values.disliked || 'Không có'}`,
                detailed_scores: values.ratings,
                pros: values.liked,
                cons: values.disliked || '',
                travel_type: travelTypeMapping[values.tripPurpose] || 'couple',
                review_date: new Date().toISOString().split('T')[0],
                media_urls: uploadedFiles
            };

            const response = await axiosInstance.post(`/public/bookings/${bookingId}/review`, payload);

            if (response.status === 201) {
                setSubmitted(true);
                message.success('Đánh giá của bạn đã được gửi thành công!');
                form.resetFields();
            }
        } catch (error: any) {
            console.error('Submit error:', error);
            if (error.response?.data?.details) {
                message.error('Dữ liệu không hợp lệ. Vui lòng kiểm tra lại.');
            } else {
                message.error('Lỗi khi gửi đánh giá. Vui lòng thử lại.');
            }
        } finally {
            setSubmitting(false);
        }
    };

    // Loading state
    if (loading) {
        return (
            <Layout style={{ minHeight: '100vh' }}>
                <Content style={{ padding: '48px 12px', display: 'flex', justifyContent: 'center', alignItems: 'center' }}>
                    <Spin size="large" />
                </Content>
            </Layout>
        );
    }

    // Not eligible: if backend says booking already reviewed, show the existing review
    if (!eligibility?.eligible) {
        if (existingReview) {
            return (
                <Layout style={{ minHeight: '100vh' }}>
                    <Content style={{ padding: '48px 12px' }}>
                        <Row justify="center">
                            <Col xs={24} sm={22} md={20} lg={16} xl={12}>
                                <Card bordered={false} style={{ boxShadow: '0 8px 24px rgba(0,0,0,0.1)' }}>
                                    <Title level={2} style={{ textAlign: 'center' }}>Đánh giá đã tồn tại</Title>
                                    <Paragraph style={{ textAlign: 'center', marginBottom: '32px' }} type="secondary">
                                        Đây là đánh giá mà khách đã gửi cho booking này.
                                    </Paragraph>

                                    <Card style={{ marginBottom: 16 }}>
                                        <Row gutter={16} align="middle">
                                            <Col flex="none">
                                                <Rate disabled defaultValue={existingReview.rating} />
                                            </Col>
                                            <Col>
                                                <Title level={4} style={{ margin: 0 }}>{existingReview.title}</Title>
                                                <Text type="secondary">{existingReview.review_date}</Text>
                                            </Col>
                                        </Row>
                                        <Divider />
                                        <Paragraph>{existingReview.comment}</Paragraph>

                                        {existingReview.media && existingReview.media.length > 0 && (
                                            <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}>
                                                {existingReview.media.map(m => (
                                                    <div key={m.id} style={{ width: 120 }}>
                                                        {m.file_type === 'image' ? (
                                                            <img src={m.file_url} alt="media" style={{ width: '100%', borderRadius: 6 }} />
                                                        ) : (
                                                            <video src={m.file_url} controls style={{ width: '100%', borderRadius: 6 }} />
                                                        )}
                                                    </div>
                                                ))}
                                            </div>
                                        )}

                                        <Divider />
                                        <Space>
                                            <Button type="primary" onClick={() => navigate('/')}>Về trang chủ</Button>
                                        </Space>
                                    </Card>
                                </Card>
                            </Col>
                        </Row>
                    </Content>
                </Layout>
            );
        }

        // If not eligible and no existing review to show, display warning
        return (
            <Layout style={{ minHeight: '100vh' }}>
                <Content style={{ padding: '48px 12px' }}>
                    <Row justify="center">
                        <Col xs={24} sm={22} md={20} lg={16} xl={12}>
                            <Result
                                status="warning"
                                title="Không thể đánh giá"
                                subTitle={eligibility?.reason || 'Booking này không đủ điều kiện để đánh giá'}
                                extra={
                                    <Button type="primary" onClick={() => navigate('/')}>
                                        Về trang chủ
                                    </Button>
                                }
                            />
                        </Col>
                    </Row>
                </Content>
            </Layout>
        );
    }

    // Submitted successfully
    if (submitted) {
        return (
            <Layout style={{ minHeight: '100vh' }}>
                <Content style={{ padding: '48px 12px' }}>
                    <Row justify="center">
                        <Col xs={24} sm={22} md={20} lg={16} xl={12}>
                            <Result
                                icon={<CheckCircleOutlined style={{ color: token.colorSuccess }} />}
                                title="Đánh giá đã được gửi thành công!"
                                subTitle="Cảm ơn bạn đã dành thời gian đánh giá. Đánh giá của bạn sẽ được xem xét và phê duyệt trong thời gian sớm nhất."
                                extra={[
                                    <Button type="primary" key="home" onClick={() => navigate('/')}>
                                        Về trang chủ
                                    </Button>,
                                    <Button key="another" onClick={() => {
                                        setSubmitted(false);
                                        setUploadedFiles([]);
                                        form.resetFields();
                                    }}>
                                        Đánh giá khác
                                    </Button>,
                                ]}
                            />
                        </Col>
                    </Row>
                </Content>
            </Layout>
        );
    }

    return (
        <Layout style={{ minHeight: '100vh' }}>
            <Content style={{ padding: '48px 12px' }}>
                <Row justify="center">
                    <Col xs={24} sm={22} md={20} lg={16} xl={12}>
                        <Card bordered={false} style={{ boxShadow: '0 8px 24px rgba(0,0,0,0.1)' }}>
                            <Title level={2} style={{ textAlign: 'center' }}>Trải nghiệm của bạn thế nào?</Title>
                            <Paragraph style={{ textAlign: 'center', marginBottom: '32px' }} type="secondary">
                                Hãy giúp LavishStay phục vụ tốt hơn bằng cách hoàn thành bài đánh giá dưới đây.
                            </Paragraph>

                            {eligibility.booking_summary && (
                                <Card style={{ marginBottom: 32 }} bordered={false}>
                                    <Descriptions title="Tóm tắt kỳ nghỉ" column={{ xs: 1, sm: 2 }}>
                                        <Descriptions.Item label={<Space><HomeOutlined />Phòng</Space>}>
                                            <Text strong>{eligibility.booking_summary.room_name}</Text>
                                        </Descriptions.Item>
                                        <Descriptions.Item label={<Space><CalendarOutlined />Thời gian</Space>}>
                                            {eligibility.booking_summary.check_in_date} → {eligibility.booking_summary.check_out_date}
                                        </Descriptions.Item>
                                        <Descriptions.Item label="Mã booking">
                                            {eligibility.booking_summary.booking_code}
                                        </Descriptions.Item>
                                        <Descriptions.Item label={<Space><DollarCircleOutlined />Chi phí</Space>}>
                                            {new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(eligibility.booking_summary.total_amount)}
                                        </Descriptions.Item>
                                    </Descriptions>
                                </Card>
                            )}

                            <Form
                                form={form}
                                layout="vertical"
                                onFinish={onFinish}
                                initialValues={{
                                    tripPurpose: 'Cặp đôi',
                                    overallRating: 5,
                                    ratings: {
                                        room_cleanliness: 4,
                                        room_comfort: 4,
                                        room_amenities: 4,
                                        service_reception: 4,
                                        service_housekeeping: 4,
                                        facilities_pool: 4,
                                        food_breakfast: 4,
                                        value_for_money: 4,
                                    }
                                }}
                            >
                                <Title level={4}>1. Đánh giá nhanh</Title>
                                <Card>
                                    <Form.Item
                                        name="overallRating"
                                        label="Đánh giá tổng quan của bạn"
                                        rules={[{ required: true, message: 'Vui lòng cho điểm tổng quan!' }]}
                                        style={{ textAlign: 'center' }}
                                    >
                                        <Rate
                                            style={{ fontSize: 40 }}
                                            character={({ index = 0 }) => customIcons[index + 1]}
                                        />
                                    </Form.Item>
                                    <Form.Item
                                        name="title"
                                        label="Tiêu đề cho đánh giá"
                                        rules={[{ required: true, message: 'Vui lòng nhập tiêu đề!' }]}
                                    >
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
                                                    <Form.Item
                                                        name={['ratings', item.name]}
                                                        label={item.label}
                                                        style={{ marginBottom: 0 }}
                                                    >
                                                        <Rate
                                                            tooltips={['Rất tệ', 'Tệ', 'Bình thường', 'Tốt', 'Tuyệt vời']}
                                                            character={({ index = 0 }) => customIcons[index + 1]}
                                                        />
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
                                        <Form.Item
                                            name="liked"
                                            label={<Space><LikeOutlined />Điều bạn thích nhất?</Space>}
                                            rules={[{ required: true, message: 'Hãy chia sẻ ít nhất một điều bạn thích nhé!' }]}
                                        >
                                            <Input.TextArea rows={4} placeholder="Ví dụ: Hồ bơi rất đẹp, nhân viên thân thiện..." />
                                        </Form.Item>
                                    </Col>
                                    <Col xs={24} md={12}>
                                        <Form.Item
                                            name="disliked"
                                            label={<Space><DislikeOutlined />Điều có thể được cải thiện?</Space>}
                                        >
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
                                    <Button
                                        type="primary"
                                        htmlType="submit"
                                        size="large"
                                        loading={submitting}
                                        icon={<StarFilled />}
                                        style={{ minWidth: 240 }}
                                    >
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