import React, { useState, useEffect } from 'react';
import {
    Card,
    Row,
    Col,
    Form,
    Input,
    Button,
    Avatar,
    Upload,
    Typography,
    Divider,
    Space,
    DatePicker,
    message,
    Spin
} from 'antd';
import {
    EditOutlined,
    SaveOutlined,
    CloseOutlined,
    CameraOutlined,
    UserOutlined,
    PhoneOutlined,
    MailOutlined,
    CalendarOutlined,
    EnvironmentOutlined,
    IdcardOutlined
} from '@ant-design/icons';
import { profileService, type UserProfile } from '../../services/profileService';
import dayjs from 'dayjs';

const { Title, Text } = Typography;

const PersonalInfo: React.FC = () => {
    const [isEditing, setIsEditing] = useState(false);
    const [form] = Form.useForm();
    const [loading, setLoading] = useState(false);
    const [userData, setUserData] = useState<UserProfile | null>(null);

    useEffect(() => {
        const loadUserProfile = async () => {
            try {
                const profile = await profileService.getUserProfile();
                setUserData(profile);
                form.setFieldsValue({
                    ...profile,
                    dateOfBirth: profile.dateOfBirth ? dayjs(profile.dateOfBirth) : null
                });
            } catch (error) {
                console.error('Error loading user profile:', error);
                message.error('Không thể tải thông tin người dùng');
            }
        };

        loadUserProfile();
    }, [form]);

    const handleEdit = () => {
        setIsEditing(true);
        if (userData) {
            form.setFieldsValue({
                ...userData,
                dateOfBirth: userData.dateOfBirth ? dayjs(userData.dateOfBirth) : null
            });
        }
    };

    const handleSave = async () => {
        try {
            setLoading(true);
            const values = await form.validateFields();

            // Convert dayjs to string for dateOfBirth
            const updatedData = {
                ...values,
                dateOfBirth: values.dateOfBirth ? values.dateOfBirth.format('YYYY-MM-DD') : ''
            };

            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 1000));

            setUserData({ ...userData!, ...updatedData });
            setIsEditing(false);
            message.success('Cập nhật thông tin thành công!');
        } catch (error) {
            console.error('Validation failed:', error);
        } finally {
            setLoading(false);
        }
    };

    const handleCancel = () => {
        setIsEditing(false);
        form.resetFields();
    };

    const handleAvatarChange = (info: any) => {
        if (info.file.status === 'done') {
            message.success('Cập nhật ảnh đại diện thành công!');
            // Handle avatar upload
        }
    };

    if (!userData) {
        return (
            <div style={{ display: 'flex', justifyContent: 'center', padding: '50px' }}>
                <Spin size="large" />
            </div>);
    } return (
        <div>
            {/* Header Section */}
            <div style={{ marginBottom: '32px' }}>
                <Row align="middle" justify="space-between">
                    <Col>
                        <Title level={2} style={{
                            margin: 0,
                            color: '#262626',
                            fontWeight: 500
                        }}>
                            <UserOutlined style={{ marginRight: '12px', color: '#8c8c8c' }} />
                            Thông tin cá nhân
                        </Title>
                        <Text style={{
                            fontSize: '16px',
                            color: '#8c8c8c',
                            marginTop: '8px',
                            display: 'block'
                        }}>
                            Quản lý thông tin cá nhân của bạn tại LavishStay Premium Hotel
                        </Text>
                    </Col>
                    <Col>
                        {!isEditing ? (
                            <Button
                                type="default"
                                icon={<EditOutlined />}
                                onClick={handleEdit}
                                size="large"
                                style={{
                                    borderRadius: '8px',
                                    height: '44px'
                                }}
                            >
                                Chỉnh sửa
                            </Button>
                        ) : (
                            <Space>
                                <Button
                                    icon={<CloseOutlined />}
                                    onClick={handleCancel}
                                    size="large"
                                    style={{
                                        borderRadius: '8px',
                                        height: '44px'
                                    }}
                                >
                                    Hủy
                                </Button>
                                <Button
                                    type="primary"
                                    icon={<SaveOutlined />}
                                    onClick={handleSave}
                                    loading={loading}
                                    size="large"
                                    style={{
                                        borderRadius: '8px',
                                        height: '44px',
                                        background: '#262626',
                                        borderColor: '#262626'
                                    }}
                                >
                                    Lưu
                                </Button>
                            </Space>
                        )}
                    </Col>
                </Row>
            </div>

            <Row gutter={[32, 32]}>
                {/* Profile Summary Card */}
                <Col xs={24} lg={8}>
                    <Card
                        bordered={false}
                        style={{
                            borderRadius: '12px',
                            background: '#fafafa'
                        }}
                    >
                        <div style={{ textAlign: 'center', marginBottom: '24px' }}>
                            <div style={{ position: 'relative', display: 'inline-block' }}>
                                <Avatar
                                    size={100}
                                    src={userData.avatar}
                                    style={{
                                        backgroundColor: '#d9d9d9',
                                        border: '4px solid white',
                                        boxShadow: '0 4px 12px rgba(0,0,0,0.1)'
                                    }}
                                >
                                    {userData.name?.charAt(0)}
                                </Avatar>

                                {isEditing && (
                                    <Upload
                                        showUploadList={false}
                                        action="/api/upload-avatar"
                                        onChange={handleAvatarChange}
                                        style={{
                                            position: 'absolute',
                                            bottom: '0',
                                            right: '0'
                                        }}
                                    >
                                        <Button
                                            shape="circle"
                                            icon={<CameraOutlined />}
                                            size="small"
                                            style={{
                                                background: 'white',
                                                border: '2px solid #f0f0f0'
                                            }}
                                        />
                                    </Upload>
                                )}
                            </div>

                            <div style={{ marginTop: '20px' }}>
                                <Title level={3} style={{
                                    margin: '0 0 4px 0',
                                    color: '#262626',
                                    fontWeight: 500
                                }}>
                                    {userData.name}
                                </Title>
                                <Text style={{
                                    color: '#8c8c8c',
                                    fontSize: '14px'
                                }}>
                                    Thành viên từ {dayjs(userData.memberSince).format('DD/MM/YYYY')}
                                </Text>
                            </div>
                        </div>

                        <Divider style={{ margin: '24px 0', borderColor: '#e8e8e8' }} />

                        {/* Stats */}
                        <Row gutter={16} style={{ textAlign: 'center' }}>
                            <Col span={8}>
                                <div style={{ padding: '12px 8px' }}>
                                    <div style={{
                                        fontSize: '28px',
                                        fontWeight: 600,
                                        color: '#262626',
                                        marginBottom: '4px'
                                    }}>
                                        {userData.totalBookings}
                                    </div>
                                    <div style={{
                                        fontSize: '12px',
                                        color: '#8c8c8c',
                                        fontWeight: 500
                                    }}>
                                        Đặt phòng
                                    </div>
                                </div>
                            </Col>
                            <Col span={8}>
                                <div style={{ padding: '12px 8px' }}>
                                    <div style={{
                                        fontSize: '28px',
                                        fontWeight: 600,
                                        color: '#262626',
                                        marginBottom: '4px'
                                    }}>
                                        {userData.loyaltyPoints.toLocaleString()}
                                    </div>
                                    <div style={{
                                        fontSize: '12px',
                                        color: '#8c8c8c',
                                        fontWeight: 500
                                    }}>
                                        Điểm tích lũy
                                    </div>
                                </div>
                            </Col>
                            <Col span={8}>
                                <div style={{ padding: '12px 8px' }}>
                                    <div style={{
                                        fontSize: '28px',
                                        fontWeight: 600,
                                        color: '#262626',
                                        marginBottom: '4px'
                                    }}>
                                        {userData.avgRating.toFixed(1)}
                                    </div>
                                    <div style={{
                                        fontSize: '12px',
                                        color: '#8c8c8c',
                                        fontWeight: 500
                                    }}>
                                        Đánh giá
                                    </div>
                                </div>
                            </Col>
                        </Row>
                    </Card>
                </Col>

                {/* Personal Details Card */}
                <Col xs={24} lg={16}>
                    <Card
                        title={
                            <div style={{
                                fontSize: '18px',
                                fontWeight: 500,
                                color: '#262626'
                            }}>
                                <IdcardOutlined style={{ marginRight: '12px', color: '#8c8c8c' }} />
                                Thông tin chi tiết
                            </div>
                        }
                        bordered={false}
                        style={{
                            borderRadius: '12px'
                        }}
                    >
                        <Form
                            form={form}
                            layout="vertical"
                            disabled={!isEditing}
                        >
                            <Row gutter={[24, 24]}>
                                <Col xs={24} sm={12}>
                                    <Form.Item
                                        label={<span style={{ fontWeight: 500, color: '#595959' }}>Họ và tên</span>}
                                        name="name"
                                        rules={[{ required: true, message: 'Vui lòng nhập họ tên!' }]}
                                    >
                                        <Input
                                            prefix={<UserOutlined style={{ color: '#bfbfbf' }} />}
                                            placeholder="Nhập họ và tên"
                                            size="large"
                                            style={{
                                                borderRadius: '8px',
                                                borderColor: '#d9d9d9'
                                            }}
                                        />
                                    </Form.Item>
                                </Col>

                                <Col xs={24} sm={12}>
                                    <Form.Item
                                        label={<span style={{ fontWeight: 500, color: '#595959' }}>Email</span>}
                                        name="email"
                                        rules={[
                                            { required: true, message: 'Vui lòng nhập email!' },
                                            { type: 'email', message: 'Email không hợp lệ!' }
                                        ]}
                                    >
                                        <Input
                                            prefix={<MailOutlined style={{ color: '#bfbfbf' }} />}
                                            placeholder="email@example.com"
                                            size="large"
                                            style={{
                                                borderRadius: '8px',
                                                borderColor: '#d9d9d9'
                                            }}
                                        />
                                    </Form.Item>
                                </Col>

                                <Col xs={24} sm={12}>
                                    <Form.Item
                                        label={<span style={{ fontWeight: 500, color: '#595959' }}>Số điện thoại</span>}
                                        name="phone"
                                        rules={[{ required: true, message: 'Vui lòng nhập số điện thoại!' }]}
                                    >
                                        <Input
                                            prefix={<PhoneOutlined style={{ color: '#bfbfbf' }} />}
                                            placeholder="+84 xxx xxx xxx"
                                            size="large"
                                            style={{
                                                borderRadius: '8px',
                                                borderColor: '#d9d9d9'
                                            }}
                                        />
                                    </Form.Item>
                                </Col>

                                <Col xs={24} sm={12}>
                                    <Form.Item
                                        label={<span style={{ fontWeight: 500, color: '#595959' }}>Ngày sinh</span>}
                                        name="dateOfBirth"
                                    >
                                        <DatePicker
                                            placeholder="Chọn ngày sinh"
                                            format="DD/MM/YYYY"
                                            size="large"
                                            style={{
                                                width: '100%',
                                                borderRadius: '8px',
                                                borderColor: '#d9d9d9'
                                            }}
                                            suffixIcon={<CalendarOutlined style={{ color: '#bfbfbf' }} />}
                                        />
                                    </Form.Item>
                                </Col>

                                <Col xs={24}>
                                    <Form.Item
                                        label={<span style={{ fontWeight: 500, color: '#595959' }}>Địa chỉ</span>}
                                        name="address"
                                    >
                                        <Input
                                            prefix={<EnvironmentOutlined style={{ color: '#bfbfbf' }} />}
                                            placeholder="Số nhà, tên đường, phường/xã"
                                            size="large"
                                            style={{
                                                borderRadius: '8px',
                                                borderColor: '#d9d9d9'
                                            }}
                                        />
                                    </Form.Item>
                                </Col>
                            </Row>
                        </Form>
                    </Card>
                </Col>
            </Row>
        </div>
    );
};

export default PersonalInfo;
