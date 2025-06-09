import React, { useState, useEffect } from 'react';
import {
  Card,
  Typography,
  Button,
  Row,
  Col,
  Empty,
  Rate,
  Modal,
  message,
  Input,
  Select,
  Spin,
  Checkbox,
  Popconfirm,
  Divider
} from 'antd';
import {
  HeartOutlined,
  HeartFilled,
  DeleteOutlined,
  EyeOutlined,
  CalendarOutlined
} from '@ant-design/icons';
import { useNavigate } from 'react-router-dom';
import { wishlistService, type WishlistItem } from '../../services/wishlistService';

const { Title, Text, Paragraph } = Typography;
const { Search } = Input;
const { Option } = Select;

const Wishlist: React.FC = () => {
  const navigate = useNavigate();
  const [wishlistItems, setWishlistItems] = useState<WishlistItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const [sortBy, setSortBy] = useState('recent');
  const [selectedItems, setSelectedItems] = useState<string[]>([]);

  useEffect(() => {
    const loadWishlist = async () => {
      try {
        const items = await wishlistService.getWishlist();
        setWishlistItems(items);
      } catch (error) {
        console.error('Error loading wishlist:', error);
        message.error('Không thể tải danh sách yêu thích');
      } finally {
        setLoading(false);
      }
    }; loadWishlist();
  }, []);

  const handleViewDetails = (roomId: string) => {
    navigate(`/rooms/${roomId}`);
  };

  const getMainAmenities = (item: WishlistItem) => {
    // Use mainAmenities if available, otherwise fall back to first 4 amenities
    const amenitiesArray = item.mainAmenities && item.mainAmenities.length > 0
      ? item.mainAmenities
      : item.amenities;
    return amenitiesArray.slice(0, 4);
  };

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('vi-VN');
  }; const handleRemoveFromWishlist = async (itemId: string) => {
    try {
      await wishlistService.removeFromWishlist(itemId);
      setWishlistItems(items => items.filter(item => item.id !== itemId));
      message.success('Đã xóa loại phòng khỏi danh sách yêu thích');
    } catch (error) {
      message.error('Không thể xóa khỏi danh sách yêu thích');
    }
  }; const handleBulkRemove = async () => {
    if (selectedItems.length === 0) {
      message.warning('Vui lòng chọn loại phòng để xóa');
      return;
    }

    Modal.confirm({
      title: `Xóa ${selectedItems.length} loại phòng khỏi danh sách yêu thích?`,
      content: 'Hành động này không thể hoàn tác.',
      okText: 'Xóa tất cả',
      cancelText: 'Hủy',
      okType: 'danger',
      async onOk() {
        try {
          await wishlistService.removeMultipleFromWishlist(selectedItems);
          setWishlistItems(items => items.filter(item => !selectedItems.includes(item.id)));
          setSelectedItems([]);
          message.success(`Đã xóa ${selectedItems.length} loại phòng khỏi danh sách yêu thích`);
        } catch (error) {
          message.error('Không thể xóa các loại phòng đã chọn');
        }
      },
    });
  }; const filteredItems = wishlistItems.filter(item => {
    const matchesSearch = item.name.toLowerCase().includes(searchTerm.toLowerCase());
    return matchesSearch;
  }).sort((a, b) => {
    switch (sortBy) {
      case 'rating':
        return b.rating - a.rating;
      case 'name':
        return a.name.localeCompare(b.name);
      case 'recent':
      default:
        return new Date(b.addedDate).getTime() - new Date(a.addedDate).getTime();
    }
  });

  const handleItemSelect = (itemId: string, checked: boolean) => {
    if (checked) {
      setSelectedItems([...selectedItems, itemId]);
    } else {
      setSelectedItems(selectedItems.filter(id => id !== itemId));
    }
  };
  if (loading) {
    return (
      <div style={{ display: 'flex', justifyContent: 'center', padding: '50px' }}>
        <Spin size="large" />
      </div>
    );
  }
  return (
    <div style={{
      padding: '24px',
      background: '#fafafa',
      minHeight: '100vh'
    }}>
      {/* Header */}
      <div style={{
        marginBottom: '32px',
        background: 'white',
        padding: '24px',
        borderRadius: '12px',
        boxShadow: '0 2px 8px rgba(0,0,0,0.06)'
      }}>        <Row align="middle" justify="space-between">
          <Col>
            <Title level={2} style={{ margin: 0, display: 'flex', alignItems: 'center', gap: '12px', color: '#262626' }}>
              <HeartFilled style={{ color: '#ff4d4f', fontSize: '28px' }} />
              Danh sách yêu thích
            </Title>
            <Text type="secondary" style={{ fontSize: '16px', marginTop: '4px', display: 'block' }}>
              {wishlistItems.length} loại phòng đã lưu
            </Text>
          </Col>
          <Col>
            {selectedItems.length > 0 && (
              <Button
                danger
                icon={<DeleteOutlined />}
                onClick={handleBulkRemove}
              >
                Xóa đã chọn ({selectedItems.length})
              </Button>
            )}
          </Col>
        </Row>
      </div>      {/* Filters */}
      <div style={{
        marginBottom: '32px',
        background: 'white',
        padding: '20px',
        borderRadius: '12px',
        boxShadow: '0 2px 8px rgba(0,0,0,0.06)'
      }}>
        <Row gutter={[16, 16]}>          <Col xs={24} sm={12} md={8}>
          <Search
            placeholder="Tìm kiếm theo tên loại phòng..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            allowClear
            size="large"
          />
        </Col>
          <Col xs={12} sm={6} md={4}><Select
            value={sortBy}
            onChange={setSortBy}
            style={{ width: '100%' }}
            size="large"
            placeholder="Sắp xếp"
          >
            <Option value="recent">Mới nhất</Option>
            <Option value="name">Tên A-Z</Option>
            <Option value="rating">Đánh giá cao nhất</Option>
          </Select>
          </Col>
        </Row>
      </div>

      {/* Content */}      {filteredItems.length === 0 ? (
        <div style={{ textAlign: 'center', padding: '80px 20px' }}>
          <Empty
            image={<HeartOutlined style={{ fontSize: 72, color: '#bfbfbf' }} />}
            description={
              <div>
                <Title level={3} style={{ color: '#8c8c8c', marginTop: '24px' }}>
                  {searchTerm ? 'Không tìm thấy loại phòng nào' : 'Chưa có loại phòng nào trong danh sách yêu thích'}
                </Title>
                <Paragraph type="secondary" style={{ fontSize: '16px', marginTop: '12px' }}>
                  {searchTerm
                    ? `Không có loại phòng nào phù hợp với "${searchTerm}"`
                    : 'Hãy khám phá và lưu những loại phòng yêu thích của bạn tại LavishStay Premium Hotel'
                  }
                </Paragraph>
                {!searchTerm && (
                  <Button type="primary" size="large" style={{ marginTop: '16px' }}>
                    Khám phá loại phòng
                  </Button>
                )}
              </div>
            }
          />
        </div>
      ) : (
        <Row gutter={[24, 24]}>
          {filteredItems.map((item) => (
            <Col xs={24} sm={12} lg={8} key={item.id}>
              <Card
                hoverable cover={
                  <div style={{ position: 'relative', height: '240px', overflow: 'hidden' }}>
                    <img
                      src={item.image}
                      alt={item.name}
                      style={{
                        width: '100%',
                        height: '100%',
                        objectFit: 'cover'
                      }}
                      onError={(e) => {
                        e.currentTarget.src = '/images/placeholder-hotel.jpg';
                      }}
                    />

                    {/* Selection checkbox */}
                    <div style={{
                      position: 'absolute',
                      top: '12px',
                      left: '12px'
                    }}>
                      <Checkbox
                        checked={selectedItems.includes(item.id)}
                        onChange={(e) => handleItemSelect(item.id, e.target.checked)}
                        style={{
                          background: 'rgba(255,255,255,0.95)',
                          borderRadius: '6px',
                          padding: '6px',
                          boxShadow: '0 2px 8px rgba(0,0,0,0.1)'
                        }}
                      />
                    </div>{/* Overlay actions */}
                    <div style={{
                      position: 'absolute',
                      top: '12px',
                      right: '12px',
                      display: 'flex',
                      gap: '8px'
                    }}>
                      <Popconfirm
                        title="Xóa khỏi yêu thích"
                        description="Bạn có chắc chắn muốn xóa loại phòng này?"
                        okText="Xóa"
                        cancelText="Hủy"
                        onConfirm={() => handleRemoveFromWishlist(item.id)}
                      >
                        <Button
                          type="text"
                          shape="circle"
                          icon={<DeleteOutlined />}
                          style={{
                            background: 'rgba(255,255,255,0.95)',
                            color: '#ff4d4f',
                            border: 'none',
                            boxShadow: '0 2px 8px rgba(0,0,0,0.1)'
                          }}
                        />
                      </Popconfirm>
                    </div>
                  </div>}
                style={{
                  borderRadius: '12px',
                  overflow: 'hidden',
                  boxShadow: '0 4px 12px rgba(0,0,0,0.1)',
                  border: 'none'
                }}
                bodyStyle={{ padding: '20px' }} actions={[
                  <Button
                    type="link"
                    icon={<EyeOutlined />}
                    style={{ color: '#1890ff' }}
                    onClick={() => handleViewDetails(item.id)}
                  >
                    Xem chi tiết
                  </Button>
                ]}
              >                <div style={{ padding: '4px 0' }}>
                  {/* Room name and rating */}
                  <div style={{ marginBottom: '16px' }}>
                    <Title level={4} style={{ margin: 0, color: '#262626' }} ellipsis>
                      {item.name}
                    </Title>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '8px', marginTop: '8px' }}>
                      <Rate
                        disabled
                        value={item.rating}
                        allowHalf
                        style={{ fontSize: '16px', color: '#faad14' }}
                      />
                      <Text type="secondary" style={{ fontSize: '14px', fontWeight: 500 }}>
                        {item.rating} • {item.reviews} đánh giá
                      </Text>
                    </div>
                  </div>                  {/* Amenities */}
                  <div style={{ marginBottom: '16px' }}>
                    <Text strong style={{ fontSize: '14px', color: '#595959', marginBottom: '8px', display: 'block' }}>
                      Tiện ích chính
                    </Text>
                    <div style={{ display: 'flex', flexWrap: 'wrap', gap: '8px' }}>
                      {getMainAmenities(item).map((amenity, index) => (
                        <div
                          key={index}
                          style={{
                            padding: '6px 12px',
                            background: '#f0f6ff',
                            border: '1px solid #d6e4ff',
                            borderRadius: '16px',
                            fontSize: '13px',
                            color: '#1890ff',
                            fontWeight: 500
                          }}
                        >
                          {amenity}
                        </div>
                      ))}
                      {(item.mainAmenities?.length || item.amenities.length) > 4 && (
                        <div style={{
                          padding: '6px 12px',
                          background: '#f5f5f5',
                          border: '1px solid #e8e8e8',
                          borderRadius: '16px',
                          fontSize: '13px',
                          color: '#8c8c8c',
                          fontWeight: 500
                        }}>
                          +{(item.mainAmenities?.length || item.amenities.length) - 4} khác
                        </div>
                      )}
                    </div>
                  </div>

                  <Divider style={{ margin: '16px 0', borderColor: '#f0f0f0' }} />

                  {/* Added date */}
                  <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                    <CalendarOutlined style={{ fontSize: '14px', color: '#bfbfbf' }} />
                    <Text type="secondary" style={{ fontSize: '13px' }}>
                      Đã thêm {formatDate(item.addedDate)}
                    </Text>
                  </div>
                </div>
              </Card>
            </Col>
          ))}
        </Row>
      )}
    </div>
  );
};

export default Wishlist;
