import React, { useState } from 'react';
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
    Tag,
    Space,
    DatePicker,
    Select,
    message,
    Badge,
    Tooltip
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
    CrownOutlined,
    GiftOutlined,
    StarOutlined
} from '@ant-design/icons';
import { useSelector } from 'react-redux';
import { RootState } from '../../store';
import dayjs from 'dayjs';
import './PersonalInfo.css';

const { Title, Text, Paragraph } = Typography;
const { Option } = Select;
const { TextArea } = Input;

interface UserData {
    id: number;
    firstName: string;
    lastName: string;
    email: string;
    phone: string;
    dateOfBirth: string;
    address: string;
    city: string;
    country: string;
    bio: string;
    avatar: string;
    idNumber: string;
    memberSince: string;
    loyaltyLevel: string;
    totalSpent: number;
    totalBookings: number;
    averageRating: number;
}

const PersonalInfo: React.FC = () => {
    const { isDarkMode } = useSelector((state: RootState) => state.theme);
    const [isEditing, setIsEditing] = useState(false);
    const [form] = Form.useForm();
    const [loading, setLoading] = useState(false);

    // Mock user data - thay thế bằng data thực từ API
    const [userData, setUserData] = useState<UserData>({
        id: 1,
        firstName: 'Nguyễn Văn',
        lastName: 'A',
        email: 'nguyenvana@email.com',
        phone: '+84 912 345 678',
        dateOfBirth: '1990-05-15',
        address: '123 Đường ABC, Quận 1',
        city: 'Hồ Chí Minh',
        country: 'Việt Nam',
        bio: 'Tôi là một travel blogger yêu thích khám phá những địa điểm mới và trải nghiệm văn hóa khác nhau.',
        avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=300&h=300&fit=crop&crop=face',
        idNumber: '012345678901',
        memberSince: '2023-01-15',
        loyaltyLevel: 'Gold',
        totalSpent: 15750000,
        totalBookings: 12,
        averageRating: 4.9
    });

    const handleEdit = () => {
        setIsEditing(true);
        form.setFieldsValue({
            ...userData,
            dateOfBirth: userData.dateOfBirth ? dayjs(userData.dateOfBirth) : null
        });
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

            setUserData({ ...userData, ...updatedData });
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

    const getLoyaltyColor = (level: string) => {
        switch (level) {
            case 'Bronze': return '#cd7f32';
            case 'Silver': return '#c0c0c0';
            case 'Gold': return '#ffd700';
            case 'Platinum': return '#e5e4e2';
            case 'Diamond': return '#b9f2ff';
            default: return '#1890ff';
        }
    };

    const getLoyaltyIcon = (level: string) => {
        switch (level) {
            case 'Diamond': return <GiftOutlined />;
            case 'Platinum':
            case 'Gold': return <CrownOutlined />;
            default: return <StarOutlined />;
        }
    };

    return (
        <div className="personal-info-container">
            {/* Header Section */}
            <div className="personal-info-header">
                <Row align="middle" justify="space-between">
                    <Col>
                        <Title level={2} className="page-title">
                            <UserOutlined className="title-icon" />
                            Thông tin cá nhân
                        </Title>
                        <Text className="page-subtitle">
                            Quản lý thông tin cá nhân và tài khoản của bạn
                        </Text>
                    </Col>
                    <Col>
                        {!isEditing ? (
                            <Button
                                type="primary"
                                icon={<EditOutlined />}
                                onClick={handleEdit}
                                size="large"
                                className="edit-button"
                            >
                                Chỉnh sửa
                            </Button>
                        ) : (
                            <Space>
                                <Button
                                    icon={<CloseOutlined />}
                                    onClick={handleCancel}
                                    size="large"
                                >
                                    Hủy
                                </Button>
                                <Button
                                    type="primary"
                                    icon={<SaveOutlined />}
                                    onClick={handleSave}
                                    loading={loading}
                                    size="large"
                                    className="save-button"
                                >
                                    Lưu
                                </Button>
                            </Space>
                        )}
                    </Col>
                </Row>
            </div>

            <Row gutter={[24, 24]}>
                {/* Profile Summary Card */}
                <Col xs={24} lg={8}>
                    <Card className="profile-summary-card" bordered={false}>
                        <div className="avatar-section">
                            <div className="avatar-container">
                                <Avatar
                                    size={120}
                                    src={userData.avatar}
                                    className="user-avatar"
                                >
                                    {userData.firstName.charAt(0)}{userData.lastName.charAt(0)}
                                </Avatar>

                                {isEditing && (
                                    <Upload
                                        showUploadList={false}
                                        action="/api/upload-avatar"
                                        onChange={handleAvatarChange}
                                        className="avatar-upload"
                                    >
                                        <Button
                                            shape="circle"
                                            icon={<CameraOutlined />}
                                            className="avatar-upload-button"
                                        />
                                    </Upload>
                                )}
                            </div>

                            <div className="user-basic-info">
                                <Title level={3} className="user-name">
                                    {userData.firstName} {userData.lastName}
                                </Title>

                                <div className="loyalty-info">
                                    <Badge
                                        count={
                                            <span className="loyalty-badge" style={{ backgroundColor: getLoyaltyColor(userData.loyaltyLevel) }}>
                                                {getLoyaltyIcon(userData.loyaltyLevel)}
                                                {userData.loyaltyLevel}
                                            </span>
                                        }
                                    >
                                        <Text className="member-since">
                                            Thành viên từ {dayjs(userData.memberSince).format('DD/MM/YYYY')}
                                        </Text>
                                    </Badge>
                                </div>
                            </div>
                        </div>

                        <Divider />

                        {/* Stats Cards */}
                        <div className="stats-grid">
                            <div className="stat-card">
                                <div className="stat-number">{userData.totalBookings}</div>
                                <div className="stat-label">Lượt đặt phòng</div>
                            </div>

                            <div className="stat-card">
                                <div className="stat-number">
                                    {(userData.totalSpent / 1000000).toFixed(1)}M
                                </div>
                                <div className="stat-label">Tổng chi tiêu</div>
                            </div>

                            <div className="stat-card">
                                <div className="stat-number">{userData.averageRating}</div>
                                <div className="stat-label">Đánh giá TB</div>
                            </div>
                        </div>

                        {/* Bio Section */}
                        <div className="bio-section">
                            <Title level={5}>Giới thiệu</Title>
                            {isEditing ? (
                                <Form.Item name="bio" className="bio-form-item">
                                    <TextArea
                                        rows={3}
                                        placeholder="Giới thiệu về bản thân..."
                                        maxLength={200}
                                        showCount
                                    />
                                </Form.Item>
                            ) : (
                                <Paragraph className="bio-text">
                                    {userData.bio || 'Chưa có thông tin giới thiệu'}
                                </Paragraph>
                            )}
                        </div>
                    </Card>
                </Col>

                {/* Personal Details Card */}
                <Col xs={24} lg={16}>
                    <Card
                        title={
                            <div className="card-title">
                                <IdcardOutlined className="card-icon" />
                                Thông tin chi tiết
                            </div>
                        }
                        className="personal-details-card"
                        bordered={false}
                    >
                        <Form
                            form={form}
                            layout="vertical"
                            disabled={!isEditing}
                            className="personal-form"
                        >
                            <Row gutter={[16, 16]}>
                                <Col xs={24} sm={12}>
                                    <Form.Item
                                        label="Họ và tên đệm"
                                        name="firstName"
                                        rules={[{ required: true, message: 'Vui lòng nhập họ!' }]}
                                    >
                                        <Input
                                            prefix={<UserOutlined />}
                                            placeholder="Nhập họ và tên đệm"
                                            size="large"
                                        />
                                    </Form.Item>
                                </Col>

                                <Col xs={24} sm={12}>
                                    <Form.Item
                                        label="Tên"
                                        name="lastName"
                                        rules={[{ required: true, message: 'Vui lòng nhập tên!' }]}
                                    >
                                        <Input
                                            placeholder="Nhập tên"
                                            size="large"
                                        />
                                    </Form.Item>
                                </Col>

                                <Col xs={24} sm={12}>
                                    <Form.Item
                                        label="Email"
                                        name="email"
                                        rules={[
                                            { required: true, message: 'Vui lòng nhập email!' },
                                            { type: 'email', message: 'Email không hợp lệ!' }
                                        ]}
                                    >
                                        <Input
                                            prefix={<MailOutlined />}
                                            placeholder="email@example.com"
                                            size="large"
                                        />
                                    </Form.Item>
                                </Col>

                                <Col xs={24} sm={12}>
                                    <Form.Item
                                        label="Số điện thoại"
                                        name="phone"
                                        rules={[{ required: true, message: 'Vui lòng nhập số điện thoại!' }]}
                                    >
                                        <Input
                                            prefix={<PhoneOutlined />}
                                            placeholder="+84 xxx xxx xxx"
                                            size="large"
                                        />
                                    </Form.Item>
                                </Col>

                                <Col xs={24} sm={12}>
                                    <Form.Item
                                        label="Ngày sinh"
                                        name="dateOfBirth"
                                    >
                                        <DatePicker
                                            placeholder="Chọn ngày sinh"
                                            format="DD/MM/YYYY"
                                            size="large"
                                            style={{ width: '100%' }}
                                            suffixIcon={<CalendarOutlined />}
                                        />
                                    </Form.Item>
                                </Col>

                                <Col xs={24} sm={12}>
                                    <Form.Item
                                        label="CMND/CCCD"
                                        name="idNumber"
                                    >
                                        <Input
                                            placeholder="Số CMND/CCCD"
                                            size="large"
                                        />
                                    </Form.Item>
                                </Col>

                                <Col xs={24}>
                                    <Form.Item
                                        label="Địa chỉ"
                                        name="address"
                                    >
                                        <Input
                                            prefix={<EnvironmentOutlined />}
                                            placeholder="Số nhà, tên đường, phường/xã"
                                            size="large"
                                        />
                                    </Form.Item>
                                </Col>

                                <Col xs={24} sm={12}>
                                    <Form.Item
                                        label="Thành phố"
                                        name="city"
                                    >
                                        <Select
                                            placeholder="Chọn thành phố"
                                            size="large"
                                            showSearch
                                            filterOption={(input, option) =>
                                                (option?.children as unknown as string)
                                                    .toLowerCase()
                                                    .includes(input.toLowerCase())
                                            }
                                        >
                                            <Option value="Hồ Chí Minh">Hồ Chí Minh</Option>
                                            <Option value="Hà Nội">Hà Nội</Option>
                                            <Option value="Đà Nẵng">Đà Nẵng</Option>
                                            <Option value="Cần Thơ">Cần Thơ</Option>
                                            <Option value="Hải Phòng">Hải Phòng</Option>
                                        </Select>
                                    </Form.Item>
                                </Col>

                                <Col xs={24} sm={12}>
                                    <Form.Item
                                        label="Quốc gia"
                                        name="country"
                                    >
                                        <Select
                                            placeholder="Chọn quốc gia"
                                            size="large"
                                        >
                                            <Option value="Việt Nam">Việt Nam</Option>
                                            <Option value="Singapore">Singapore</Option>
                                            <Option value="Malaysia">Malaysia</Option>
                                            <Option value="Thailand">Thailand</Option>
                                        </Select>
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
