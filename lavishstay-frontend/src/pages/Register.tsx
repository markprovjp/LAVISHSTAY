// import React, { useState } from "react";
// import { Form, Input, Button, Typography, Divider, message } from "antd";
// import { UserOutlined, LockOutlined, MailOutlined } from "@ant-design/icons";
// import { Link, useNavigate } from "react-router-dom";
// import { useDispatch } from "react-redux";
// import { loginSuccess } from "../store/slices/authSlice";
// import { authService } from "../services";

// const { Title, Text } = Typography;

// const Register: React.FC = () => {
//   const [loading, setLoading] = useState(false);
//   const dispatch = useDispatch();
//   const navigate = useNavigate();

//   const onFinish = async (values: any) => {
//     try {
//       setLoading(true);
//       const response = await authService.register(values);

//       // Lưu token và thông tin user vào redux store
//       dispatch(
//         loginSuccess({
//           user: response.user,
//           token: response.token,
//         })
//       );

//       message.success("Đăng ký thành công!");
//       navigate("/dashboard");
//     } catch (error: any) {
//       message.error(
//         error.response?.data?.message || "Đăng ký thất bại. Vui lòng thử lại."
//       );
//     } finally {
//       setLoading(false);
//     }
//   };

//   return (
//     <div>
//       <div className="text-center mb-6">
//         <Title level={3}>Đăng Ký</Title>
//         <Text className="text-gray-500">
//           Tạo tài khoản mới để trải nghiệm LavishStay
//         </Text>
//       </div>

//       <Form
//         name="register"
//         onFinish={onFinish}
//         layout="vertical"
//         requiredMark={false}
//       >
//         <Form.Item
//           name="name"
//           rules={[{ required: true, message: "Vui lòng nhập họ tên của bạn!" }]}
//         >
//           <Input prefix={<UserOutlined />} placeholder="Họ tên" size="large" />
//         </Form.Item>

//         <Form.Item
//           name="email"
//           rules={[
//             { required: true, message: "Vui lòng nhập email của bạn!" },
//             { type: "email", message: "Email không hợp lệ!" },
//           ]}
//         >
//           <Input prefix={<MailOutlined />} placeholder="Email" size="large" />
//         </Form.Item>

//         <Form.Item
//           name="password"
//           rules={[
//             { required: true, message: "Vui lòng nhập mật khẩu!" },
//             {
//               min: 8,
//               message: "Mật khẩu phải có ít nhất 8 ký tự!",
//             },
//           ]}
//         >
//           <Input.Password
//             prefix={<LockOutlined />}
//             placeholder="Mật khẩu"
//             size="large"
//           />
//         </Form.Item>

//         <Form.Item
//           name="password_confirmation"
//           dependencies={["password"]}
//           rules={[
//             { required: true, message: "Vui lòng xác nhận mật khẩu!" },
//             ({ getFieldValue }) => ({
//               validator(_, value) {
//                 if (!value || getFieldValue("password") === value) {
//                   return Promise.resolve();
//                 }
//                 return Promise.reject(
//                   new Error("Mật khẩu xác nhận không khớp!")
//                 );
//               },
//             }),
//           ]}
//         >
//           <Input.Password
//             prefix={<LockOutlined />}
//             placeholder="Xác nhận mật khẩu"
//             size="large"
//           />
//         </Form.Item>

//         <Form.Item>
//           <Button
//             type="primary"
//             htmlType="submit"
//             size="large"
//             block
//             loading={loading}
//           >
//             Đăng Ký
//           </Button>
//         </Form.Item>
//       </Form>

//       <Divider plain>Hoặc</Divider>

//       <div className="text-center">
//         <Text className="text-gray-500">
//           Đã có tài khoản?{" "}
//           <Link to="/login" className="text-primary font-medium">
//             Đăng nhập ngay
//           </Link>
//         </Text>
//       </div>
//     </div>
//   );
// };

// export default Register;
