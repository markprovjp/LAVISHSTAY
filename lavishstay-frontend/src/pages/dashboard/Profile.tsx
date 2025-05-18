import React, { useState } from "react";
import {
  Typography,
  Form,
  Input,
  Button,
  Upload,
  message,
  Divider,
  Card,
  Avatar,
} from "antd";
import { UploadOutlined, UserOutlined } from "@ant-design/icons";
import type { UploadProps } from "antd";
import { useSelector, useDispatch } from "react-redux";
import { RootState } from "../../store";
import { updateUser } from "../../store/slices/authSlice";
import userService from "../../services/userService";

const { Title } = Typography;

const Profile: React.FC = () => {
  const user = useSelector((state: RootState) => state.auth.user);
  const dispatch = useDispatch();
  const [form] = Form.useForm();
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (values: any) => {
    if (!user?.id) return;

    try {
      setLoading(true);
      const updatedUser = await userService.updateUser(user.id, values);
      dispatch(updateUser(updatedUser));
      message.success("Thông tin cá nhân đã được cập nhật thành công!");
    } catch (error) {
      message.error("Đã xảy ra lỗi khi cập nhật thông tin cá nhân.");
      console.error(error);
    } finally {
      setLoading(false);
    }
  };

  const uploadProps: UploadProps = {
    name: "avatar",
    action: `${process.env.REACT_APP_API_URL}/users/${user?.id}/avatar`,
    headers: {
      authorization: `Bearer ${localStorage.getItem("authToken")}`,
    },
    onChange(info) {
      if (info.file.status === "done") {
        message.success(`${info.file.name} đã tải lên thành công`);
        // Update user avatar in state
        if (info.file.response?.data) {
          dispatch(
            updateUser({
              ...user,
              avatar: info.file.response.data.avatar,
            })
          );
        }
      } else if (info.file.status === "error") {
        message.error(`${info.file.name} tải lên thất bại.`);
      }
    },
  };

  return (
    <div>
      <Title level={2}>Hồ sơ cá nhân</Title>

      <Card bordered={false} className="shadow-sm mb-6">
        <div className="flex items-center">
          <Avatar
            size={100}
            icon={<UserOutlined />}
            src={user?.avatar}
            className="mr-6"
          />
          <div>
            <Title level={4} className="m-0">
              {user?.name}
            </Title>
            <p className="text-gray-500">{user?.email}</p>
            <Upload {...uploadProps}>
              <Button icon={<UploadOutlined />}>Tải lên ảnh đại diện</Button>
            </Upload>
          </div>
        </div>
      </Card>

      <Card bordered={false} className="shadow-sm">
        <Title level={4} className="mb-4">
          Thông tin cá nhân
        </Title>

        <Form
          form={form}
          layout="vertical"
          initialValues={{
            name: user?.name,
            email: user?.email,
          }}
          onFinish={handleSubmit}
        >
          <Form.Item
            name="name"
            label="Họ tên"
            rules={[
              { required: true, message: "Vui lòng nhập họ tên của bạn!" },
            ]}
          >
            <Input />
          </Form.Item>

          <Form.Item
            name="email"
            label="Email"
            rules={[
              { required: true, message: "Vui lòng nhập email của bạn!" },
              { type: "email", message: "Email không hợp lệ!" },
            ]}
          >
            <Input />
          </Form.Item>

          <Divider />

          <Title level={4} className="mb-4">
            Đổi mật khẩu
          </Title>

          <Form.Item
            name="currentPassword"
            label="Mật khẩu hiện tại"
            rules={[{ required: false }]}
          >
            <Input.Password />
          </Form.Item>

          <Form.Item
            name="password"
            label="Mật khẩu mới"
            rules={[
              {
                required: false,
                pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/,
                message:
                  "Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường và số!",
              },
            ]}
          >
            <Input.Password />
          </Form.Item>

          <Form.Item
            name="password_confirmation"
            label="Xác nhận mật khẩu mới"
            dependencies={["password"]}
            rules={[
              { required: false },
              ({ getFieldValue }) => ({
                validator(_, value) {
                  if (!value || getFieldValue("password") === value) {
                    return Promise.resolve();
                  }
                  return Promise.reject(
                    new Error("Mật khẩu xác nhận không khớp!")
                  );
                },
              }),
            ]}
          >
            <Input.Password />
          </Form.Item>

          <Form.Item>
            <Button type="primary" htmlType="submit" loading={loading}>
              Cập nhật thông tin
            </Button>
          </Form.Item>
        </Form>
      </Card>
    </div>
  );
};

export default Profile;
