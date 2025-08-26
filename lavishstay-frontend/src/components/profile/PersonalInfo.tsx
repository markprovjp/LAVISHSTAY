import React, { useState, useEffect } from 'react';
import {
  Card,
  Form,
  Input,
  Button,
  Avatar,
  Upload,
  message,
  Tag,
  Space,
  Divider,
  Modal,
  Row,
  Col,
  Typography,
  Spin,
} from 'antd';
import {
  UserOutlined,
  EditOutlined,
  SaveOutlined,
  CloseOutlined,
  CameraOutlined,
  DeleteOutlined,
  LockOutlined,
  PhoneOutlined,
  MailOutlined,
} from '@ant-design/icons';
import { useSelector, useDispatch } from 'react-redux';
import { RootState } from '../../store';
import { updateUser } from '../../store/slices/authSlice';
import profileService from '../../services/profileService';
import type { UserProfile, UpdateProfileData, ChangePasswordData } from '../../services/profileService';

const { Title, Text } = Typography;
const { TextArea } = Input;

const PersonalInfo: React.FC = () => {
  const dispatch = useDispatch();
  const user = useSelector((state: RootState) => state.auth.user);

  const [loading, setLoading] = useState(false);
  const [isEditing, setIsEditing] = useState(false);
  const [uploadLoading, setUploadLoading] = useState(false);
  const [passwordModalVisible, setPasswordModalVisible] = useState(false);
  const [profileData, setProfileData] = useState<UserProfile | null>(null);

  const [form] = Form.useForm();
  const [passwordForm] = Form.useForm();

  // Load profile data on mount
  useEffect(() => {
    loadProfile();
  }, []);

  // Update form when user data changes
  useEffect(() => {
    if (profileData) {
      form.setFieldsValue({
        name: profileData.name,
        phone: profileData.phone || '',
        address: profileData.address || '',
      });
    }
  }, [profileData, form]);

  const loadProfile = async () => {
    try {
      setLoading(true);
      const data = await profileService.getCurrentUser();
      setProfileData(data);
      // Update Redux store
      dispatch(updateUser(data));
    } catch (error: any) {
      message.error(error.message || 'Không thể tải thông tin profile');
    } finally {
      setLoading(false);
    }
  };

  const handleEdit = () => {
    setIsEditing(true);
  };

  const handleCancel = () => {
    setIsEditing(false);
    if (profileData) {
      form.setFieldsValue({
        name: profileData.name,
        phone: profileData.phone || '',
        address: profileData.address || '',
      });
    }
  };

  const handleSave = async () => {
    try {
      const values = await form.validateFields();
      setLoading(true);

      const updateData: UpdateProfileData = {
        name: values.name,
        phone: values.phone,
        address: values.address,
      };

      const updatedUser = await profileService.updateProfile(updateData);

      setProfileData(updatedUser);
      dispatch(updateUser(updatedUser));
      setIsEditing(false);
      message.success('Cập nhật thông tin thành công!');
    } catch (error: any) {
      message.error(error.message || 'Không thể cập nhật thông tin');
    } finally {
      setLoading(false);
    }
  };

  const handleAvatarUpload = async (file: File) => {
    try {
      setUploadLoading(true);

      const updatedUser = await profileService.uploadAvatar(file);

      setProfileData(updatedUser);
      dispatch(updateUser(updatedUser));
      message.success('Cập nhật ảnh đại diện thành công!');

      return false; // Prevent default upload behavior
    } catch (error: any) {
      message.error(error.message || 'Không thể tải lên ảnh đại diện');
      return false;
    } finally {
      setUploadLoading(false);
    }
  };

  const handleDeleteAvatar = async () => {
    try {
      setUploadLoading(true);

      const updatedUser = await profileService.deleteAvatar();

      setProfileData(updatedUser);
      dispatch(updateUser(updatedUser));
      message.success('Xóa ảnh đại diện thành công!');
    } catch (error: any) {
      message.error(error.message || 'Không thể xóa ảnh đại diện');
    } finally {
      setUploadLoading(false);
    }
  };

  const handleChangePassword = async () => {
    try {
      const values = await passwordForm.validateFields();
      setLoading(true);

      const passwordData: ChangePasswordData = {
        current_password: values.current_password,
        new_password: values.new_password,
        new_password_confirmation: values.new_password_confirmation,
      };

      await profileService.changePassword(passwordData);

      message.success('Đổi mật khẩu thành công!');
      setPasswordModalVisible(false);
      passwordForm.resetFields();
    } catch (error: any) {
      message.error(error.message || 'Không thể đổi mật khẩu');
    } finally {
      setLoading(false);
    }
  };

  const uploadProps = {
    beforeUpload: (file: File) => {
      const isImage = file.type.startsWith('image/');
      if (!isImage) {
        message.error('Chỉ có thể tải lên file hình ảnh!');
        return false;
      }

      const isLt2M = file.size / 1024 / 1024 < 2;
      if (!isLt2M) {
        message.error('Kích thước file phải nhỏ hơn 2MB!');
        return false;
      }

      return handleAvatarUpload(file);
    },
    showUploadList: false,
  };

  if (loading && !profileData) {
    return (
      <div style={{ display: 'flex', justifyContent: 'center', padding: '50px' }}>
        <Spin size="large" />
      </div>
    );
  }

  return (
    <div>
      <Card>
        <Row gutter={[24, 24]}>
          <Col xs={24} sm={8} style={{ textAlign: 'center' }}>
            <div style={{ position: 'relative', display: 'inline-block' }}>
              <Avatar
                size={120}
                src={profileData?.avatar}
                icon={<UserOutlined />}
                style={{ marginBottom: 16 }}
              />
              {uploadLoading && (
                <div
                  style={{
                    position: 'absolute',
                    top: 0,
                    left: 0,
                    right: 0,
                    bottom: 0,
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    backgroundColor: 'rgba(0,0,0,0.5)',
                    borderRadius: '50%',
                    marginBottom: 16,
                  }}
                >
                  <Spin />
                </div>
              )}
            </div>

            <div>
              <Space direction="vertical" size="small">
                <Upload {...uploadProps}>
                  <Button icon={<CameraOutlined />} size="small">
                    Thay đổi ảnh
                  </Button>
                </Upload>

                {profileData?.avatar && (
                  <Button
                    icon={<DeleteOutlined />}
                    size="small"
                    danger
                    onClick={handleDeleteAvatar}
                    loading={uploadLoading}
                  >
                    Xóa ảnh
                  </Button>
                )}
              </Space>
            </div>

            <Divider />

            <div style={{ textAlign: 'left' }}>
              <Text strong>Vai trò:</Text>
              <div style={{ marginTop: 8 }}>
                {profileData?.roles?.map((role: string) => (
                  <Tag key={role} color="blue">
                    {role === 'receptionist' ? 'Lễ tân' :
                      role === 'admin' ? 'Quản trị viên' :
                        role === 'manager' ? 'Quản lý' :
                          role === 'guest' ? 'Khách hàng' : role}
                  </Tag>
                ))}
              </div>
            </div>
          </Col>

          <Col xs={24} sm={16}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 24 }}>
              <Title level={3} style={{ margin: 0 }}>
                Thông tin cá nhân
              </Title>

              {!isEditing ? (
                <Space>
                  <Button icon={<EditOutlined />} onClick={handleEdit}>
                    Chỉnh sửa
                  </Button>
                  <Button
                    icon={<LockOutlined />}
                    onClick={() => setPasswordModalVisible(true)}
                  >
                    Đổi mật khẩu
                  </Button>
                </Space>
              ) : (
                <Space>
                  <Button
                    icon={<SaveOutlined />}
                    type="primary"
                    onClick={handleSave}
                    loading={loading}
                  >
                    Lưu
                  </Button>
                  <Button icon={<CloseOutlined />} onClick={handleCancel}>
                    Hủy
                  </Button>
                </Space>
              )}
            </div>

            <Form
              form={form}
              layout="vertical"
              disabled={!isEditing}
            >
              <Form.Item
                label="Tên"
                name="name"
                rules={[{ required: true, message: 'Vui lòng nhập tên!' }]}
              >
                <Input prefix={<UserOutlined />} placeholder="Nhập tên của bạn" />
              </Form.Item>

              <Form.Item
                label="Email"
                name="email"
                initialValue={profileData?.email}
              >
                <Input prefix={<MailOutlined />} disabled />
              </Form.Item>

              <Form.Item
                label="Số điện thoại"
                name="phone"
              >
                <Input prefix={<PhoneOutlined />} placeholder="Nhập số điện thoại" />
              </Form.Item>

              <Form.Item
                label="Địa chỉ"
                name="address"
              >
                <TextArea
                  placeholder="Nhập địa chỉ"
                  rows={3}
                />
              </Form.Item>
            </Form>
          </Col>
        </Row>
      </Card>

      {/* Change Password Modal */}
      <Modal
        title="Đổi mật khẩu"
        open={passwordModalVisible}
        onCancel={() => {
          setPasswordModalVisible(false);
          passwordForm.resetFields();
        }}
        footer={[
          <Button key="cancel" onClick={() => setPasswordModalVisible(false)}>
            Hủy
          </Button>,
          <Button
            key="submit"
            type="primary"
            loading={loading}
            onClick={handleChangePassword}
          >
            Đổi mật khẩu
          </Button>,
        ]}
      >
        <Form
          form={passwordForm}
          layout="vertical"
        >
          <Form.Item
            label="Mật khẩu hiện tại"
            name="current_password"
            rules={[{ required: true, message: 'Vui lòng nhập mật khẩu hiện tại!' }]}
          >
            <Input.Password placeholder="Nhập mật khẩu hiện tại" />
          </Form.Item>

          <Form.Item
            label="Mật khẩu mới"
            name="new_password"
            rules={[
              { required: true, message: 'Vui lòng nhập mật khẩu mới!' },
              { min: 8, message: 'Mật khẩu phải có ít nhất 8 ký tự!' },
            ]}
          >
            <Input.Password placeholder="Nhập mật khẩu mới" />
          </Form.Item>

          <Form.Item
            label="Xác nhận mật khẩu mới"
            name="new_password_confirmation"
            dependencies={['new_password']}
            rules={[
              { required: true, message: 'Vui lòng xác nhận mật khẩu mới!' },
              ({ getFieldValue }) => ({
                validator(_, value) {
                  if (!value || getFieldValue('new_password') === value) {
                    return Promise.resolve();
                  }
                  return Promise.reject(new Error('Mật khẩu xác nhận không khớp!'));
                },
              }),
            ]}
          >
            <Input.Password placeholder="Xác nhận mật khẩu mới" />
          </Form.Item>
        </Form>
      </Modal>
    </div>
  );
};

export default PersonalInfo;
