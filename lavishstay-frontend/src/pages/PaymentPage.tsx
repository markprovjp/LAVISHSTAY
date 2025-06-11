import React, { useState, useEffect } from "react";
import { useSelector, useDispatch } from "react-redux";
import { useParams, useNavigate, useLocation } from "react-router-dom";
import {
  Layout,
  Typography,
  Card,
  Steps,
  Form,
  Input,
  Button,
  Divider,
  Row,
  Col,
  Radio,
  Space,
  message,
  Image,
  Carousel,
} from "antd";
import {
  CreditCardOutlined,
  BankOutlined,
  CheckCircleOutlined,
  DollarOutlined,
  UserOutlined,
  EnvironmentOutlined,
  CalendarOutlined,
  TeamOutlined,
} from "@ant-design/icons";
import { RootState } from "../../store";

const { Title, Text, Paragraph } = Typography;
const { Content } = Layout;
const { Step } = Steps;

const PaymentPage: React.FC = () => {
  const [currentStep, setCurrentStep] = useState(0);
  const [paymentMethod, setPaymentMethod] = useState<string>("credit_card");
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();
  const location = useLocation();
  const { bookingId } = useParams();
  
  // Lấy dữ liệu từ Redux store
  const { isDarkMode } = useSelector((state: RootState) => state.theme);
  const { isAuthenticated, user } = useSelector((state: RootState) => state.auth);
  
  // Giả sử dữ liệu đặt phòng được truyền qua location state hoặc lấy từ Redux store
  const bookingData = location.state?.bookingData || {
    hotelName: "Dynasty Hotel",
    roomType: "Deluxe Room",
    checkIn: "2023-12-01",
    checkOut: "2023-12-05",
    guests: 2,
    nights: 4,
    price: 1200000,
    tax: 120000,
    total: 1320000,
    location: "Quận 1, TP. Hồ Chí Minh",
    description: "Phòng sang trọng với tầm nhìn thành phố, bao gồm bữa sáng miễn phí và dịch vụ đưa đón sân bay.",
    amenities: ["Wi-Fi miễn phí", "Điều hòa", "TV màn hình phẳng", "Minibar", "Két an toàn"],
    // Thêm mảng hình ảnh phòng
    images: [
      "https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80",
      "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80",
      "https://images.unsplash.com/photo-1584132905271-512c958d674a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
    ]
  };

  // Xử lý khi chuyển bước
  const nextStep = () => {
    setCurrentStep(currentStep + 1);
  };

  const prevStep = () => {
    setCurrentStep(currentStep - 1);
  };

  // Xử lý khi hoàn tất thanh toán
  const handlePaymentSubmit = async (values: any) => {
    setLoading(true);
    try {
      // Gọi API thanh toán ở đây
      console.log("Payment data:", { ...values, paymentMethod, bookingId });
      
      // Giả lập API call thành công
      setTimeout(() => {
        setLoading(false);
        nextStep(); // Chuyển đến bước xác nhận
        message.success("Thanh toán thành công!");
      }, 2000);
    } catch (error) {
      setLoading(false);
      message.error("Có lỗi xảy ra khi thanh toán. Vui lòng thử lại.");
    }
  };

  // Xử lý khi hoàn tất quá trình
  const handleFinish = () => {
    navigate("/bookings");
  };

  // Các bước thanh toán
  const steps = [
    {
      title: "Thông tin đặt phòng",
      content: (
        <Card className="mb-6">
          <Row gutter={[24, 24]}>
            {/* Phần hình ảnh phòng */}
            <Col span={24} md={10}>
              <div className="mb-4">
                <Carousel autoplay className="rounded-lg overflow-hidden">
                  {bookingData.images.map((image: string, index: number) => (
                    <div key={index}>
                      <div style={{ height: '300px', position: 'relative' }}>
                        <img 
                          src={image} 
                          alt={`${bookingData.roomType} - Ảnh ${index + 1}`} 
                          style={{ 
                            width: '100%', 
                            height: '100%', 
                            objectFit: 'cover',
                            borderRadius: '8px'
                          }} 
                        />
                      </div>
                    </div>
                  ))}
                </Carousel>
              </div>
              
              <div className="bg-gray-50 p-4 rounded-lg">
                <Title level={5} className="mb-3">Tiện nghi phòng</Title>
                <Row gutter={[8, 8]}>
                  {bookingData.amenities.map((amenity: string, index: number) => (
                    <Col span={12} key={index}>
                      <div className="flex items-center">
                        <div className="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                        <Text>{amenity}</Text>
                      </div>
                    </Col>
                  ))}
                </Row>
              </div>
            </Col>
            
            {/* Phần thông tin đặt phòng */}
            <Col span={24} md={14}>
              <Title level={4}>{bookingData.hotelName}</Title>
              <div className="flex items-center mb-3">
                <EnvironmentOutlined style={{ marginRight: 8, color: '#1890ff' }} />
                <Text type="secondary">{bookingData.location}</Text>
              </div>
              
              <Divider style={{ margin: '12px 0' }} />
              
              <Title level={5} className="mb-3">{bookingData.roomType}</Title>
              <Paragraph className="mb-4">
                {bookingData.description}
              </Paragraph>
              
              <Row gutter={[16, 16]} className="mb-4">
                <Col span={12}>
                  <div className="flex items-center">
                    <CalendarOutlined style={{ marginRight: 8, color: '#1890ff' }} />
                    <div>
                      <Text type="secondary">Nhận phòng</Text>
                      <div className="font-medium">{bookingData.checkIn}</div>
                    </div>
                  </div>
                </Col>
                <Col span={12}>
                  <div className="flex items-center">
                    <CalendarOutlined style={{ marginRight: 8, color: '#1890ff' }} />
                    <div>
                      <Text type="secondary">Trả phòng</Text>
                      <div className="font-medium">{bookingData.checkOut}</div>
                    </div>
                  </div>
                </Col>
                <Col span={12}>
                  <div className="flex items-center">
                    <TeamOutlined style={{ marginRight: 8, color: '#1890ff' }} />
                    <div>
                      <Text type="secondary">Số khách</Text>
                      <div className="font-medium">{bookingData.guests} người</div>
                    </div>
                  </div>
                </Col>
                <Col span={12}>
                  <div className="flex items-center">
                    <CalendarOutlined style={{ marginRight: 8, color: '#1890ff' }} />
                    <div>
                      <Text type="secondary">Số đêm</Text>
                      <div className="font-medium">{bookingData.nights} đêm</div>
                    </div>
                  </div>
                </Col>
              </Row>
              
              <Card className="bg-gray-50 mb-4">
                <div className="flex justify-between mb-2">
                  <Text>Giá phòng ({bookingData.nights} đêm)</Text>
                  <Text>{bookingData.price.toLocaleString()} VND</Text>
                </div>
                <div className="flex justify-between mb-2">
                  <Text>Thuế và phí</Text>
                  <Text>{bookingData.tax.toLocaleString()} VND</Text>
                </div>
                <Divider style={{ margin: '12px 0' }} />
                <div className="flex justify-between">
                  <Title level={5} style={{ margin: 0 }}>Tổng cộng</Title>
                  <Title level={4} style={{ margin: 0, color: "#f5222d" }}>
                    {bookingData.total.toLocaleString()} VND
                  </Title>
                </div>
              </Card>
              
              <div className="flex justify-end">
                <Button type="primary" size="large" onClick={nextStep}>
                  Tiếp tục thanh toán
                </Button>
              </div>
            </Col>
          </Row>
        </Card>
      ),
    },
    {
      title: "Phương thức thanh toán",
      content: (
        <Card className="mb-6">
          <Title level={4}>Chọn phương thức thanh toán</Title>
          <Radio.Group 
            value={paymentMethod} 
            onChange={(e) => setPaymentMethod(e.target.value)}
            className="w-full"
          >
            <Space direction="vertical" className="w-full">
              <Radio value="credit_card">
                <Card className="w-full cursor-pointer hover:border-primary">
                  <div className="flex items-center">
                    <CreditCardOutlined style={{ fontSize: 24, marginRight: 12 }} />
                    <div>
                      <div className="font-medium">Thẻ tín dụng/ghi nợ</div>
                      <Text type="secondary">Visa, Mastercard, JCB</Text>
                    </div>
                  </div>
                </Card>
              </Radio>
              <Radio value="bank_transfer">
                <Card className="w-full cursor-pointer hover:border-primary">
                  <div className="flex items-center">
                    <BankOutlined style={{ fontSize: 24, marginRight: 12 }} />
                    <div>
                      <div className="font-medium">Chuyển khoản ngân hàng</div>
                      <Text type="secondary">Chuyển khoản trực tiếp đến tài khoản của chúng tôi</Text>
                    </div>
                  </div>
                </Card>
              </Radio>
              <Radio value="momo">
                <Card className="w-full cursor-pointer hover:border-primary">
                  <div className="flex items-center">
                    <DollarOutlined style={{ fontSize: 24, marginRight: 12 }} />
                    <div>
                      <div className="font-medium">Ví điện tử MoMo</div>
                      <Text type="secondary">Thanh toán qua ví MoMo</Text>
                    </div>
                  </div>
                </Card>
              </Radio>
            </Space>
          </Radio.Group>
          
          {/* Hiển thị tóm tắt đặt phòng ở bên phải */}
          <div className="mt-6 bg-gray-50 p-4 rounded-lg">
            <Title level={5}>Tóm tắt đặt phòng</Title>
            <div className="flex items-center mb-3">
              <img 
                src={bookingData.images[0]} 
                alt={bookingData.roomType}
                style={{ width: 60, height: 60, objectFit: 'cover', borderRadius: 4, marginRight: 12 }}
              />
              <div>
                <Text strong>{bookingData.hotelName}</Text>
                <div>{bookingData.roomType}</div>
                <Text type="secondary">{bookingData.location}</Text>
              </div>
            </div>
            <Divider style={{ margin: '12px 0' }} />
            <div className="flex justify-between mb-2">
              <Text>Tổng thanh toán</Text>
              <Text strong>{bookingData.total.toLocaleString()} VND</Text>
            </div>
          </div>
          
          <Divider />
          <div className="mt-6 flex justify-between">
            <Button onClick={prevStep}>
              Quay lại
            </Button>
            <Button type="primary" onClick={nextStep}>
              Tiếp tục
            </Button>
          </div>
        </Card>
      ),
    },
    {
      title: "Chi tiết thanh toán",
      content: (
        <Card className="mb-6">
          <Row gutter={24}>
            <Col span={24} lg={16}>
              <Title level={4}>Nhập thông tin thanh toán</Title>
              <Form
                layout="vertical"
                onFinish={handlePaymentSubmit}
                initialValues={{
                  name: user?.name || "",
                  email: user?.email || "",
                }}
              >
                {paymentMethod === "credit_card" && (
                  <>
                    <Form.Item
                      name="cardName"
                      label="Tên chủ thẻ"
                      rules={[{ required: true, message: "Vui lòng nhập tên chủ thẻ" }]}
                    >
                      <Input placeholder="Nhập tên in trên thẻ" />
                    </Form.Item>
                    <Form.Item
                      name="cardNumber"
                      label="Số thẻ"
                      rules={[{ required: true, message: "Vui lòng nhập số thẻ" }]}
                    >
                      <Input placeholder="XXXX XXXX XXXX XXXX" />
                    </Form.Item>
                    <Row gutter={16}>
                      <Col span={12}>
                        <Form.Item
                          name="expiry"
                          label="Ngày hết hạn"
                          rules={[{ required: true, message: "Vui lòng nhập ngày hết hạn" }]}
                        >
                          <Input placeholder="MM/YY" />
                        </Form.Item>
                      </Col>
                      <Col span={12}>
                        <Form.Item
                          name="cvv"
                          label="CVV"
                          rules={[{ required: true, message: "Vui lòng nhập mã CVV" }]}
                        >
                          <Input placeholder="XXX" />
                        </Form.Item>
                      </Col>
                    </Row>
                  </>
                )}

                {paymentMethod === "bank_transfer" && (
                  <>
                    <div className="bg-gray-100 p-4 rounded mb-4">
                      <Text strong>Thông tin chuyển khoản:</Text>
                      <div className="mt-2">
                        <div>Ngân hàng: Vietcombank</div>
                        <div>Số tài khoản: 1234567890</div>
                        <div>Chủ tài khoản: LAVISHSTAY CO., LTD</div>
                        <div>Nội dung: BOOKING-{bookingId || "ID"}</div>
                      </div>
                    </div>
                    <Text type="secondary" className="block mb-4">
                      Sau khi chuyển khoản, vui lòng cung cấp thông tin để chúng tôi xác nhận
                    </Text>
                    <Form.Item
                      name="transferDate"
                      label="Ngày chuyển khoản"
                      rules={[{ required: true, message: "Vui lòng nhập ngày chuyển khoản" }]}
                    >
                      <Input type="date" />
                    </Form.Item>
                    <Form.Item
                      name="transferAmount"
                      label="Số tiền chuyển khoản"
                      rules={[{ required: true, message: "Vui lòng nhập số tiền chuyển khoản" }]}
                    >
                      <Input placeholder="Nhập số tiền đã chuyển khoản" />
                    </Form.Item>
                  </>
                )}

                {paymentMethod === "momo" && (
                  <div className="text-center">
                    <div className="bg-gray-100 p-4 rounded mb-4">
                      <Text strong>Quét mã QR để thanh toán qua MoMo</Text>
                      <div className="mt-4 flex justify-center">
                        {/* Placeholder for QR code */}
                        <div className="w-48 h-48 bg-gray-300 flex items-center justify-center">
                          QR Code
                        </div>
                      </div>
                    </div>
                    <Text type="secondary" className="block mb-4">
                      Vui lòng mở ứng dụng MoMo và quét mã QR để hoàn tất thanh toán
                    </Text>
                    <Form.Item
                      name="momoTransactionId"
                      label="Mã giao dịch MoMo"
                      rules={[{ required: true, message: "Vui lòng nhập mã giao dịch MoMo" }]}
                    >
                      <Input placeholder="Nhập mã giao dịch sau khi thanh toán" />
                    </Form.Item>
                  </div>
                )}

                <Form.Item
                  name="name"
                  label="Họ tên"
                  rules={[{ required: true, message: "Vui lòng nhập họ tên" }]}
                >
                  <Input placeholder="Nhập họ tên của bạn" />
                </Form.Item>
                <Form.Item
                  name="email"
                  label="Email"
                  rules={[
                    { required: true, message: "Vui lòng nhập email" },
                    { type: "email", message: "Email không hợp lệ" }
                  ]}
                >
                  <Input placeholder="Nhập email của bạn" />
                </Form.Item>
                <Form.Item
                  name="phone"
                  label="Số điện thoại"
                  rules={[{ required: true, message: "Vui lòng nhập số điện thoại" }]}
                >
                  <Input placeholder="Nhập số điện thoại của bạn" />
                </Form.Item>

                <Divider />
                <div className="mt-6 flex justify-between">
                  <Button onClick={prevStep}>
                    Quay lại
                  </Button>
                  <Button type="primary" htmlType="submit" loading={loading}>
                    Thanh toán ngay
                  </Button>
                </div>
              </Form>
            </Col>
            
            {/* Hiển thị tóm tắt đặt phòng ở bên phải */}
            <Col span={24} lg={8}>
              <Card className="bg-gray-50">
                <Title level={5}>Tóm tắt đặt phòng</Title>
                <div className="flex items-center mb-3">
                  <img 
                    src={bookingData.images[0]} 
                    alt={bookingData.roomType}
                    style={{ width: 60, height: 60, objectFit: 'cover', borderRadius: 4, marginRight: 12 }}
                  />
                  <div>
                    <Text strong>{bookingData.hotelName}</Text>
                    <div>{bookingData.roomType}</div>
                    <Text type="secondary">{bookingData.location}</Text>
                  </div>
                </div>
                <Divider style={{ margin: '12px 0' }} />
                <div className="mb-2">
                  <Text type="secondary">Nhận phòng:</Text>
                  <div>{bookingData.checkIn}</div>
                </div>
                <div className="mb-2">
                  <Text type="secondary">Trả phòng:</Text>
                  <div>{bookingData.checkOut}</div>
                </div>
                <div className="mb-2">
                  <Text type="secondary">Số đêm:</Text>
                  <div>{bookingData.nights} đêm</div>
                </div>
                <div className="mb-2">
                  <Text type="secondary">Số khách:</Text>
                  <div>{bookingData.guests} người</div>
                </div>
                <Divider style={{ margin: '12px 0' }} />
                <div className="flex justify-between mb-2">
                  <Text>Giá phòng</Text>
                  <Text>{bookingData.price.toLocaleString()} VND</Text>
                </div>
                <div className="flex justify-between mb-2">
                  <Text>Thuế và phí</Text>
                  <Text>{bookingData.tax.toLocaleString()} VND</Text>
                </div>
                <Divider style={{ margin: '12px 0' }} />
                <div className="flex justify-between">
                  <Text strong>Tổng thanh toán</Text>
                  <Text strong style={{ color: "#f5222d", fontSize: 16 }}>
                    {bookingData.total.toLocaleString()} VND
                  </Text>
                </div>
              </Card>
              
              <div className="mt-4 bg-blue-50 p-4 rounded-lg">
                <div className="flex items-start">
                  <CheckCircleOutlined style={{ color: '#1890ff', marginRight: 8, marginTop: 4 }} />
                  <div>
                    <Text strong>Chính sách hủy phòng</Text>
                    <Text type="secondary" className="block mt-1">
                      Miễn phí hủy phòng trước 3 ngày. Sau thời gian này, bạn sẽ bị tính phí 100% giá trị đặt phòng.
                    </Text>
                  </div>
                </div>
              </div>
            </Col>
          </Row>
        </Card>
      ),
    },
    {
      title: "Xác nhận",
      content: (
        <Card className="mb-6 text-center">
          <div className="py-8">
            <CheckCircleOutlined style={{ fontSize: 72, color: "#52c41a" }} />
            <Title level={2} className="mt-4">Thanh toán thành công!</Title>
            <Text className="block mb-6">
              Cảm ơn bạn đã đặt phòng tại LavishStay. Thông tin đặt phòng đã được gửi đến email của bạn.
            </Text>
            
            <Card className="max-w-md mx-auto mb-6 text-left">
              <div className="flex items-center mb-4">
                <img 
                  src={bookingData.images[0]} 
                  alt={bookingData.roomType}
                  style={{ width: 80, height: 80, objectFit: 'cover', borderRadius: 4, marginRight: 16 }}
                />
                <div>
                  <Title level={5} style={{ margin: 0 }}>{bookingData.hotelName}</Title>
                  <Text>{bookingData.roomType}</Text>
                  <div className="text-gray-500">{bookingData.location}</div>
                </div>
              </div>
              
              <Divider style={{ margin: '12px 0' }} />
              
              <Row gutter={[16, 16]}>
                <Col span={12}>
                  <Text type="secondary">Mã đặt phòng:</Text>
                  <div className="font-medium">{bookingId || "BOOKING-123456"}</div>
                </Col>
                <Col span={12}>
                  <Text type="secondary">Trạng thái:</Text>
                  <div className="text-green-500 font-medium">Đã xác nhận</div>
                </Col>
                <Col span={12}>
                  <Text type="secondary">Nhận phòng:</Text>
                  <div>{bookingData.checkIn}</div>
                </Col>
                <Col span={12}>
                  <Text type="secondary">Trả phòng:</Text>
                  <div>{bookingData.checkOut}</div>
                </Col>
                <Col span={12}>
                  <Text type="secondary">Số khách:</Text>
                  <div>{bookingData.guests} người</div>
                </Col>
                <Col span={12}>
                  <Text type="secondary">Tổng thanh toán:</Text>
                  <div className="font-medium">{bookingData.total.toLocaleString()} VND</div>
                </Col>
              </Row>
            </Card>
            
            <div className="mt-8">
              <Button type="primary" size="large" onClick={handleFinish}>
                Xem đặt phòng của tôi
              </Button>
            </div>
          </div>
        </Card>
      ),
    },
  ];

  return (
    <Layout>
      <Content className="container mx-auto px-4 py-8">
        <Title level={2} className="mb-6">Thanh toán</Title>
        
        <Steps current={currentStep} className="mb-8">
          {steps.map(item => (
            <Step key={item.title} title={item.title} />
          ))}
        </Steps>
        
        <div className="steps-content">{steps[currentStep].content}</div>
      </Content>
    </Layout>
  );
};

export default PaymentPage;
