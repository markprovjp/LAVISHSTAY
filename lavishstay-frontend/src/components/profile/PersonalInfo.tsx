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
    IdcardOutlined,
    BookOutlined,
    StarOutlined,
    ClockCircleOutlined,
    ContactsOutlined
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
                            Quản lý thông tin cá nhân của bạn tại LavishStay  Hotel
                        </Text>
                    </Col>
                    <Col>                        {!isEditing ? (
                        <Button
                            type="default"
                            icon={<EditOutlined />}
                            onClick={handleEdit}
                            size="large"
                            style={{
                                borderRadius: '8px',
                                height: '44px',
                                fontWeight: 500,
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
                                    height: '44px',
                                    borderColor: '#ff4d4f',
                                    fontWeight: 500
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
                                    borderColor: 'transparent',
                                    fontWeight: 500,
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
                <Col xs={24} lg={8}>                    <Card
                    bordered={false}
                    style={{
                        borderRadius: '12px',
                        boxShadow: '0 4px 16px rgba(0,0,0,0.1)'
                    }}
                >
                    <div style={{ textAlign: 'center', marginBottom: '24px' }}>
                        <div style={{ position: 'relative', display: 'inline-block' }}>                                <Avatar
                            size={100}
                            src={userData.avatar}
                            style={{
                                border: '4px solid white',
                                boxShadow: '0 6px 20px rgba(24, 144, 255, 0.3)',
                                fontSize: '36px',
                                fontWeight: 600
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
                        </div>                            <div style={{ marginTop: '20px' }}>
                            <Title level={3} style={{
                                margin: '0 0 8px 0',
                                color: '#262626',
                                fontWeight: 500
                            }}>
                                <ContactsOutlined style={{
                                    marginRight: '8px',
                                    color: '#1890ff',
                                    fontSize: '20px'
                                }} />
                                {userData.name}
                            </Title>
                        </div>
                    </div>

                    <Divider style={{ margin: '24px 0', borderColor: '#e8e8e8' }} />                        {/* Stats */}
                    <Row gutter={16} style={{ textAlign: 'center' }}>
                        <Col span={12}>
                            <div style={{
                                padding: '16px 8px',
                                borderRadius: '8px',
                                marginBottom: '8px'
                            }}>
                                <BookOutlined style={{ fontSize: '20px', marginBottom: '8px' }} />
                                <div style={{
                                    fontSize: '24px',
                                    fontWeight: 600,
                                    marginBottom: '4px'
                                }}>
                                    {userData.totalBookings}
                                </div>
                                <div style={{
                                    fontSize: '12px',
                                    fontWeight: 500,
                                    opacity: 0.9
                                }}>
                                    Lần đặt phòng
                                </div>
                            </div>
                        </Col>
                        <Col span={12}>
                            <div style={{
                                padding: '16px 8px',
                                borderRadius: '8px',
                                marginBottom: '8px'
                            }}>
                                <StarOutlined style={{ fontSize: '20px', marginBottom: '8px' }} />
                                <div style={{
                                    fontSize: '24px',
                                    fontWeight: 600,
                                    marginBottom: '4px'
                                }}>
                                    {userData.avgRating.toFixed(1)}
                                </div>
                                <div style={{
                                    fontSize: '12px',
                                    fontWeight: 500,
                                    opacity: 0.9
                                }}>
                                    Đánh giá trung bình
                                </div>
                            </div>
                        </Col>
                    </Row>

                    {/* Member Since */}
                    <div style={{
                        marginTop: '16px',
                        padding: '12px',
                        background: '#f0f2f5',
                        borderRadius: '8px',
                        textAlign: 'center'
                    }}>
                        <ClockCircleOutlined style={{
                            color: '#1890ff',
                            fontSize: '16px',
                            marginRight: '8px'
                        }} />
                        <Text style={{ color: '#595959', fontSize: '14px' }}>
                            Thành viên từ {dayjs(userData.memberSince).format('DD/MM/YYYY')}
                        </Text>
                    </div>
                </Card>
                </Col>

                {/* Personal Details Card */}
                <Col xs={24} lg={16}>                    <Card
                    title={
                        <div style={{
                            fontSize: '18px',
                            fontWeight: 500,
                            color: '#262626',
                            padding: '8px 0'
                        }}>
                            <IdcardOutlined style={{
                                marginRight: '12px',
                                color: '#1890ff',
                                fontSize: '20px'
                            }} />
                            Thông tin chi tiết
                        </div>
                    }
                    bordered={false}
                    style={{
                        borderRadius: '12px',
                        boxShadow: '0 2px 8px rgba(0,0,0,0.06)'
                    }}
                >
                    <Form
                        form={form}
                        layout="vertical"
                        disabled={!isEditing}
                    >
                        <Row gutter={[24, 24]}>                                <Col xs={24} sm={12}>
                            <Form.Item
                                label={
                                    <span style={{
                                        fontWeight: 500,
                                        color: '#595959',
                                        fontSize: '14px'
                                    }}>
                                        <UserOutlined style={{ marginRight: '6px', color: '#1890ff' }} />
                                        Họ và tên
                                    </span>
                                }
                                name="name"
                                rules={[{ required: true, message: 'Vui lòng nhập họ tên!' }]}
                            >
                                <Input
                                    placeholder="Nhập họ và tên"
                                    size="large"
                                    style={{
                                        borderRadius: '8px',
                                        borderColor: '#d9d9d9',
                                        boxShadow: 'none'
                                    }}
                                />
                            </Form.Item>
                        </Col>

                            <Col xs={24} sm={12}>
                                <Form.Item
                                    label={
                                        <span style={{
                                            fontWeight: 500,
                                            color: '#595959',
                                            fontSize: '14px'
                                        }}>
                                            <MailOutlined style={{ marginRight: '6px', color: '#1890ff' }} />
                                            Email
                                        </span>
                                    }
                                    name="email"
                                    rules={[
                                        { required: true, message: 'Vui lòng nhập email!' },
                                        { type: 'email', message: 'Email không hợp lệ!' }
                                    ]}
                                >
                                    <Input
                                        placeholder="email@example.com"
                                        size="large"
                                        style={{
                                            borderRadius: '8px',
                                            borderColor: '#d9d9d9',
                                            boxShadow: 'none'
                                        }}
                                    />
                                </Form.Item>
                            </Col>

                            <Col xs={24} sm={12}>
                                <Form.Item
                                    label={
                                        <span style={{
                                            fontWeight: 500,
                                            color: '#595959',
                                            fontSize: '14px'
                                        }}>
                                            <PhoneOutlined style={{ marginRight: '6px', color: '#1890ff' }} />
                                            Số điện thoại
                                        </span>
                                    }
                                    name="phone"
                                    rules={[{ required: true, message: 'Vui lòng nhập số điện thoại!' }]}
                                >
                                    <Input
                                        placeholder="+84 xxx xxx xxx"
                                        size="large"
                                        style={{
                                            borderRadius: '8px',
                                            borderColor: '#d9d9d9',
                                            boxShadow: 'none'
                                        }}
                                    />
                                </Form.Item>
                            </Col>

                            <Col xs={24} sm={12}>
                                <Form.Item
                                    label={
                                        <span style={{
                                            fontWeight: 500,
                                            color: '#595959',
                                            fontSize: '14px'
                                        }}>
                                            <CalendarOutlined style={{ marginRight: '6px', color: '#1890ff' }} />
                                            Ngày sinh
                                        </span>
                                    }
                                    name="dateOfBirth"
                                >
                                    <DatePicker
                                        placeholder="Chọn ngày sinh"
                                        format="DD/MM/YYYY"
                                        size="large"
                                        style={{
                                            width: '100%',
                                            borderRadius: '8px',
                                            borderColor: '#d9d9d9',
                                            boxShadow: 'none'
                                        }}
                                    />
                                </Form.Item>
                            </Col>

                            <Col xs={24}>
                                <Form.Item
                                    label={
                                        <span style={{
                                            fontWeight: 500,
                                            color: '#595959',
                                            fontSize: '14px'
                                        }}>
                                            <EnvironmentOutlined style={{ marginRight: '6px', color: '#1890ff' }} />
                                            Địa chỉ
                                        </span>
                                    }
                                    name="address"
                                >
                                    <Input
                                        placeholder="Số nhà, tên đường, phường/xã"
                                        size="large"
                                        style={{
                                            borderRadius: '8px',
                                            borderColor: '#d9d9d9',
                                            boxShadow: 'none'
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
