// import React, { useState } from 'react';
// import {
//   Card,
//   Table,
//   Tag,
//   Button,
//   Space,
//   Typography,
//   Row,
//   Col,
//   Statistic,
//   Modal,
//   Form,
//   Input,
//   Select,
//   message,
//   Badge,
//   Tooltip
// } from 'antd';
// import {
//   EyeOutlined,
//   EditOutlined,
//   CheckCircleOutlined,
//   UserOutlined,
//   CalendarOutlined,
//   HomeOutlined,
//   ToolOutlined
// } from '@ant-design/icons';

// const { Title, Text } = Typography;
// const { Option } = Select;

// const RoomManagementToday: React.FC = () => {
//   const [loading, setLoading] = useState(false);
//   const [modalVisible, setModalVisible] = useState(false);
//   const [selectedRoom, setSelectedRoom] = useState<any>(null);
//   const [form] = Form.useForm();

//   // Mock data for today's rooms
//   const todayRooms = [
//     {
//       id: 1,
//       roomNumber: '101',
//       type: 'Deluxe',
//       status: 'occupied',
//       guest: 'Nguyễn Văn A',
//       checkIn: '2024-06-14',
//       checkOut: '2024-06-16',
//       phone: '0901234567',
//       cleaning: 'completed',
//       maintenance: 'none'
//     },
//     {
//       id: 2,
//       roomNumber: '102',
//       type: 'Premium',
//       status: 'check-out-today',
//       guest: 'Trần Thị B',
//       checkIn: '2024-06-12',
//       checkOut: '2024-06-14',
//       phone: '0907654321',
//       cleaning: 'pending',
//       maintenance: 'none'
//     },
//     {
//       id: 3,
//       roomNumber: '103',
//       type: 'Deluxe',
//       status: 'check-in-today',
//       guest: 'Lê Văn C',
//       checkIn: '2024-06-14',
//       checkOut: '2024-06-17',
//       phone: '0912345678',
//       cleaning: 'completed',
//       maintenance: 'none'
//     },
//     {
//       id: 4,
//       roomNumber: '201',
//       type: 'Suite',
//       status: 'available',
//       guest: null,
//       checkIn: null,
//       checkOut: null,
//       phone: null,
//       cleaning: 'completed',
//       maintenance: 'requested'
//     },
//     {
//       id: 5,
//       roomNumber: '202',
//       type: 'Premium',
//       status: 'maintenance',
//       guest: null,
//       checkIn: null,
//       checkOut: null,
//       phone: null,
//       cleaning: 'pending',
//       maintenance: 'in-progress'
//     }
//   ];

//   const getStatusColor = (status: string) => {
//     switch (status) {
//       case 'occupied': return 'red';
//       case 'available': return 'green';
//       case 'check-in-today': return 'blue';
//       case 'check-out-today': return 'orange';
//       case 'maintenance': return 'purple';
//       default: return 'default';
//     }
//   };

//   const getStatusText = (status: string) => {
//     switch (status) {
//       case 'occupied': return 'Có khách';
//       case 'available': return 'Trống';
//       case 'check-in-today': return 'Check-in hôm nay';
//       case 'check-out-today': return 'Check-out hôm nay';
//       case 'maintenance': return 'Bảo trì';
//       default: return status;
//     }
//   };

//   const getCleaningStatus = (status: string) => {
//     switch (status) {
//       case 'completed': return <Tag color="green">Đã dọn</Tag>;
//       case 'pending': return <Tag color="orange">Chờ dọn</Tag>;
//       case 'in-progress': return <Tag color="blue">Đang dọn</Tag>;
//       default: return <Tag>Chưa xác định</Tag>;
//     }
//   };

//   const getMaintenanceStatus = (status: string) => {
//     switch (status) {
//       case 'none': return <Tag color="green">Bình thường</Tag>;
//       case 'requested': return <Tag color="orange">Yêu cầu bảo trì</Tag>;
//       case 'in-progress': return <Tag color="blue">Đang bảo trì</Tag>;
//       default: return <Tag>Chưa xác định</Tag>;
//     }
//   };

//   const handleUpdateRoom = (room: any) => {
//     setSelectedRoom(room);
//     form.setFieldsValue(room);
//     setModalVisible(true);
//   };

//   const handleModalOk = async () => {
//     try {
//       const values = await form.validateFields();
//       setLoading(true);
      
//       // Simulate API call
//       await new Promise(resolve => setTimeout(resolve, 1000));
      
//       console.log('Updated room data:', { ...selectedRoom, ...values });
//       message.success('Cập nhật thông tin phòng thành công!');
      
//       setModalVisible(false);
//       setSelectedRoom(null);
//       form.resetFields();
//     } catch (error) {
//       message.error('Có lỗi xảy ra khi cập nhật!');
//     } finally {
//       setLoading(false);
//     }
//   };

//   const columns = [
//     {
//       title: 'Số phòng',
//       dataIndex: 'roomNumber',
//       key: 'roomNumber',
//       render: (text: string) => (
//         <Text strong style={{ fontSize: '16px' }}>{text}</Text>
//       )
//     },
//     {
//       title: 'Loại phòng',
//       dataIndex: 'type',
//       key: 'type',
//       render: (type: string) => <Tag color="blue">{type}</Tag>
//     },
//     {
//       title: 'Trạng thái',
//       dataIndex: 'status',
//       key: 'status',
//       render: (status: string) => (
//         <Badge 
//           status={getStatusColor(status) as any} 
//           text={getStatusText(status)}
//         />
//       )
//     },
//     {
//       title: 'Khách hàng',
//       dataIndex: 'guest',
//       key: 'guest',
//       render: (guest: string) => guest || <Text type="secondary">-</Text>
//     },
//     {
//       title: 'Check-in',
//       dataIndex: 'checkIn',
//       key: 'checkIn',
//       render: (date: string) => date || <Text type="secondary">-</Text>
//     },
//     {
//       title: 'Check-out',
//       dataIndex: 'checkOut',
//       key: 'checkOut',
//       render: (date: string) => date || <Text type="secondary">-</Text>
//     },
//     {
//       title: 'Dọn phòng',
//       dataIndex: 'cleaning',
//       key: 'cleaning',
//       render: (status: string) => getCleaningStatus(status)
//     },
//     {
//       title: 'Bảo trì',
//       dataIndex: 'maintenance',
//       key: 'maintenance',
//       render: (status: string) => getMaintenanceStatus(status)
//     },
//     {
//       title: 'Thao tác',
//       key: 'action',
//       render: (record: any) => (
//         <Space>
//           <Tooltip title="Xem chi tiết">
//             <Button 
//               type="primary" 
//               icon={<EyeOutlined />} 
//               size="small"
//             />
//           </Tooltip>
//           <Tooltip title="Cập nhật">
//             <Button 
//               icon={<EditOutlined />} 
//               size="small"
//               onClick={() => handleUpdateRoom(record)}
//             />
//           </Tooltip>
//         </Space>
//       )
//     }
//   ];

//   // Calculate statistics
//   const stats = {
//     total: todayRooms.length,
//     occupied: todayRooms.filter(r => r.status === 'occupied').length,
//     available: todayRooms.filter(r => r.status === 'available').length,
//     checkInToday: todayRooms.filter(r => r.status === 'check-in-today').length,
//     checkOutToday: todayRooms.filter(r => r.status === 'check-out-today').length,
//     maintenance: todayRooms.filter(r => r.status === 'maintenance').length
//   };

//   return (
//     <div className="p-6">
//       <Title level={2} className="mb-6">
//         🏠 Quản lý phòng hôm nay
//       </Title>

//       {/* Statistics */}
//       <Row gutter={[16, 16]} className="mb-6">
//         <Col xs={24} sm={12} md={8} lg={4}>
//           <Card>
//             <Statistic
//               title="Tổng phòng"
//               value={stats.total}
//               prefix={<HomeOutlined />}
//               valueStyle={{ color: '#1890ff' }}
//             />
//           </Card>
//         </Col>
//         <Col xs={24} sm={12} md={8} lg={4}>
//           <Card>
//             <Statistic
//               title="Có khách"
//               value={stats.occupied}
//               prefix={<UserOutlined />}
//               valueStyle={{ color: '#cf1322' }}
//             />
//           </Card>
//         </Col>
//         <Col xs={24} sm={12} md={8} lg={4}>
//           <Card>
//             <Statistic
//               title="Trống"
//               value={stats.available}
//               prefix={<CheckCircleOutlined />}
//               valueStyle={{ color: '#52c41a' }}
//             />
//           </Card>
//         </Col>
//         <Col xs={24} sm={12} md={8} lg={4}>
//           <Card>
//             <Statistic
//               title="Check-in"
//               value={stats.checkInToday}
//               prefix={<CalendarOutlined />}
//               valueStyle={{ color: '#1890ff' }}
//             />
//           </Card>
//         </Col>
//         <Col xs={24} sm={12} md={8} lg={4}>
//           <Card>
//             <Statistic
//               title="Check-out"
//               value={stats.checkOutToday}
//               prefix={<CalendarOutlined />}
//               valueStyle={{ color: '#fa8c16' }}
//             />
//           </Card>
//         </Col>
//         <Col xs={24} sm={12} md={8} lg={4}>
//           <Card>
//             <Statistic
//               title="Bảo trì"
//               value={stats.maintenance}
//               prefix={<ToolOutlined />}
//               valueStyle={{ color: '#722ed1' }}
//             />
//           </Card>
//         </Col>
//       </Row>

//       {/* Room Table */}
//       <Card title="Danh sách phòng">
//         <Table
//           columns={columns}
//           dataSource={todayRooms}
//           rowKey="id"
//           pagination={{
//             pageSize: 10,
//             showTotal: (total, range) => 
//               `${range[0]}-${range[1]} của ${total} phòng`
//           }}
//           scroll={{ x: 1000 }}
//         />
//       </Card>

//       {/* Update Room Modal */}
//       <Modal
//         title={`Cập nhật phòng ${selectedRoom?.roomNumber}`}
//         open={modalVisible}
//         onOk={handleModalOk}
//         onCancel={() => {
//           setModalVisible(false);
//           setSelectedRoom(null);
//           form.resetFields();
//         }}
//         confirmLoading={loading}
//         width={600}
//       >
//         <Form
//           form={form}
//           layout="vertical"
//         >
//           <Row gutter={16}>
//             <Col span={12}>
//               <Form.Item
//                 label="Trạng thái phòng"
//                 name="status"
//               >
//                 <Select>
//                   <Option value="available">Trống</Option>
//                   <Option value="occupied">Có khách</Option>
//                   <Option value="maintenance">Bảo trì</Option>
//                   <Option value="check-in-today">Check-in hôm nay</Option>
//                   <Option value="check-out-today">Check-out hôm nay</Option>
//                 </Select>
//               </Form.Item>
//             </Col>
//             <Col span={12}>
//               <Form.Item
//                 label="Tình trạng dọn phòng"
//                 name="cleaning"
//               >
//                 <Select>
//                   <Option value="completed">
//                     <Space>
//                       <CheckCircleOutlined />
//                       Đã dọn
//                     </Space>
//                   </Option>
//                   <Option value="pending">Chờ dọn</Option>
//                   <Option value="in-progress">Đang dọn</Option>
//                 </Select>
//               </Form.Item>
//             </Col>
//           </Row>
          
//           <Row gutter={16}>
//             <Col span={12}>
//               <Form.Item
//                 label="Tình trạng bảo trì"
//                 name="maintenance"
//               >
//                 <Select>
//                   <Option value="none">Bình thường</Option>
//                   <Option value="requested">Yêu cầu bảo trì</Option>
//                   <Option value="in-progress">Đang bảo trì</Option>
//                 </Select>
//               </Form.Item>
//             </Col>
//             <Col span={12}>
//               <Form.Item
//                 label="Tên khách hàng"
//                 name="guest"
//               >
//                 <Input placeholder="Nhập tên khách hàng" />
//               </Form.Item>
//             </Col>
//           </Row>

//           <Form.Item
//             label="Ghi chú"
//             name="notes"
//           >
//             <Input.TextArea 
//               rows={3}
//               placeholder="Ghi chú về tình trạng phòng..."
//             />
//           </Form.Item>
//         </Form>
//       </Modal>
//     </div>
//   );
// };

// export default RoomManagementToday;
