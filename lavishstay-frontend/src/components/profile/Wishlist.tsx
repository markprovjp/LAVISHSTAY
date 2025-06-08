import React, { useState } from 'react';
import {
  Card,
  Typography,
  Space,
  Button,
  Row,
  Col,
  Empty,
  Rate,
  Tag,
  Modal,
  message,
  Tooltip,
  Input,
  Select,
  Divider
} from 'antd';
import {
  HeartOutlined,
  HeartFilled,
  ShareAltOutlined,
  DeleteOutlined,
  EyeOutlined,
  CalendarOutlined,
  EnvironmentOutlined,
  FilterOutlined,
  SearchOutlined,
  StarFilled
} from '@ant-design/icons';
import { AMENITIES } from '../../constants/amenities';
import './Wishlist.css';

const { Title, Text, Paragraph } = Typography;
const { Search } = Input;
const { Option } = Select;

interface WishlistItem {
  id: string;
  name: string;
  location: string;
  price: number;
  originalPrice?: number;
  rating: number;
  reviews: number;
  image: string;
  amenities: string[];
  addedDate: string;
  category: 'hotel' | 'resort' | 'villa' | 'apartment';
  available: boolean;
}

const Wishlist: React.FC = () => {
  const [searchTerm, setSearchTerm] = useState('');
  const [sortBy, setSortBy] = useState('recent');
  const [filterCategory, setFilterCategory] = useState('all');
  const [selectedItems, setSelectedItems] = useState<string[]>([]);

  const [wishlistItems] = useState<WishlistItem[]>([
    {
      id: '1',
      name: 'Luxury Ocean View Resort',
      location: 'Phu Quoc, Vietnam',
      price: 2500000,
      originalPrice: 3000000,
      rating: 4.8,
      reviews: 245,
      image: '/images/hotel1.jpg',
      amenities: ['wifi', 'pool', 'spa', 'restaurant', 'gym'],
      addedDate: '2024-01-15',
      category: 'resort',
      available: true
    },
    {
      id: '2',
      name: 'City Center Business Hotel',
      location: 'Ho Chi Minh City, Vietnam',
      price: 1200000,
      rating: 4.5,
      reviews: 189,
      image: '/images/hotel2.jpg',
      amenities: ['wifi', 'restaurant', 'gym', 'meeting-room'],
      addedDate: '2024-01-10',
      category: 'hotel',
      available: true
    },
    {
      id: '3',
      name: 'Mountain Villa Retreat',
      location: 'Da Lat, Vietnam',
      price: 1800000,
      originalPrice: 2200000,
      rating: 4.9,
      reviews: 156,
      image: '/images/villa1.jpg',
      amenities: ['wifi', 'fireplace', 'garden', 'kitchen'],
      addedDate: '2024-01-08',
      category: 'villa',
      available: false
    },
    {
      id: '4',
      name: 'Beach Front Apartment',
      location: 'Nha Trang, Vietnam',
      price: 900000,
      rating: 4.3,
      reviews: 98,
      image: '/images/apartment1.jpg',
      amenities: ['wifi', 'kitchen', 'balcony', 'beach-access'],
      addedDate: '2024-01-05',
      category: 'apartment',
      available: true
    }
  ]);

  const getAmenityIcon = (amenityKey: string) => {
    const amenity = AMENITIES.find(a => a.key === amenityKey);
    return amenity ? amenity.icon : null;
  };

  const formatPrice = (price: number) => {
    return new Intl.NumberFormat('vi-VN', {
      style: 'currency',
      currency: 'VND'
    }).format(price);
  };

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('vi-VN');
  };

  const handleRemoveFromWishlist = (itemId: string) => {
    Modal.confirm({
      title: 'Remove from Wishlist',
      content: 'Are you sure you want to remove this item from your wishlist?',
      okText: 'Remove',
      okType: 'danger',
      onOk() {
        message.success('Item removed from wishlist');
      },
    });
  };

  const handleShare = (item: WishlistItem) => {
    if (navigator.share) {
      navigator.share({
        title: item.name,
        text: `Check out this amazing place: ${item.name} in ${item.location}`,
        url: window.location.href,
      });
    } else {
      navigator.clipboard.writeText(window.location.href);
      message.success('Link copied to clipboard');
    }
  };

  const handleBulkRemove = () => {
    if (selectedItems.length === 0) {
      message.warning('Please select items to remove');
      return;
    }

    Modal.confirm({
      title: `Remove ${selectedItems.length} items from wishlist?`,
      content: 'This action cannot be undone.',
      okText: 'Remove All',
      okType: 'danger',
      onOk() {
        setSelectedItems([]);
        message.success(`${selectedItems.length} items removed from wishlist`);
      },
    });
  };

  const filteredItems = wishlistItems.filter(item => {
    const matchesSearch = item.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         item.location.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesCategory = filterCategory === 'all' || item.category === filterCategory;
    return matchesSearch && matchesCategory;
  }).sort((a, b) => {
    switch (sortBy) {
      case 'price-low':
        return a.price - b.price;
      case 'price-high':
        return b.price - a.price;
      case 'rating':
        return b.rating - a.rating;
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

  return (
    <div className="wishlist-container">
      <div className="wishlist-header">
        <div className="header-content">
          <div className="title-section">
            <HeartFilled className="title-icon" />
            <div>
              <Title level={2}>My Wishlist</Title>
              <Text type="secondary">
                {wishlistItems.length} saved properties
              </Text>
            </div>
          </div>

          <div className="header-actions">
            {selectedItems.length > 0 && (
              <Button
                danger
                icon={<DeleteOutlined />}
                onClick={handleBulkRemove}
              >
                Remove Selected ({selectedItems.length})
              </Button>
            )}
          </div>
        </div>

        <div className="filters-section">
          <Row gutter={[16, 16]} align="middle">
            <Col xs={24} sm={12} md={8}>
              <Search
                placeholder="Search by name or location..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                prefix={<SearchOutlined />}
                allowClear
              />
            </Col>
            <Col xs={12} sm={6} md={4}>
              <Select
                value={filterCategory}
                onChange={setFilterCategory}
                style={{ width: '100%' }}
                prefix={<FilterOutlined />}
              >
                <Option value="all">All Categories</Option>
                <Option value="hotel">Hotels</Option>
                <Option value="resort">Resorts</Option>
                <Option value="villa">Villas</Option>
                <Option value="apartment">Apartments</Option>
              </Select>
            </Col>
            <Col xs={12} sm={6} md={4}>
              <Select
                value={sortBy}
                onChange={setSortBy}
                style={{ width: '100%' }}
              >
                <Option value="recent">Recently Added</Option>
                <Option value="price-low">Price: Low to High</Option>
                <Option value="price-high">Price: High to Low</Option>
                <Option value="rating">Highest Rated</Option>
              </Select>
            </Col>
          </Row>
        </div>
      </div>

      {filteredItems.length === 0 ? (
        <div className="empty-state">
          <Empty
            image={<HeartOutlined style={{ fontSize: 64, color: '#d4af37' }} />}
            description={
              <div>
                <Title level={4}>No items in your wishlist</Title>
                <Paragraph type="secondary">
                  Start exploring and save your favorite properties to see them here.
                </Paragraph>
                <Button type="primary" className="explore-btn">
                  Explore Properties
                </Button>
              </div>
            }
          />
        </div>
      ) : (
        <Row gutter={[24, 24]}>
          {filteredItems.map((item) => (
            <Col xs={24} sm={12} lg={8} xl={6} key={item.id}>
              <Card
                className={`wishlist-card ${!item.available ? 'unavailable' : ''}`}
                cover={
                  <div className="card-cover">
                    <img
                      src={item.image}
                      alt={item.name}
                      onError={(e) => {
                        e.currentTarget.src = '/images/placeholder-hotel.jpg';
                      }}
                    />
                    <div className="card-overlay">
                      <div className="overlay-actions">
                        <Tooltip title="View Details">
                          <Button
                            type="primary"
                            shape="circle"
                            icon={<EyeOutlined />}
                            className="action-btn"
                          />
                        </Tooltip>
                        <Tooltip title="Share">
                          <Button
                            type="default"
                            shape="circle"
                            icon={<ShareAltOutlined />}
                            className="action-btn"
                            onClick={() => handleShare(item)}
                          />
                        </Tooltip>
                        <Tooltip title="Remove from Wishlist">
                          <Button
                            danger
                            shape="circle"
                            icon={<DeleteOutlined />}
                            className="action-btn"
                            onClick={() => handleRemoveFromWishlist(item.id)}
                          />
                        </Tooltip>
                      </div>
                      <div className="availability-badge">
                        {item.available ? (
                          <Tag color="success">Available</Tag>
                        ) : (
                          <Tag color="error">Not Available</Tag>
                        )}
                      </div>
                    </div>
                    {item.originalPrice && (
                      <div className="discount-badge">
                        {Math.round((1 - item.price / item.originalPrice) * 100)}% OFF
                      </div>
                    )}
                  </div>
                }
                actions={[
                  <Button
                    type="link"
                    size="small"
                    onClick={() => handleItemSelect(item.id, !selectedItems.includes(item.id))}
                  >
                    {selectedItems.includes(item.id) ? 'Deselect' : 'Select'}
                  </Button>
                ]}
              >
                <div className="card-content">
                  <div className="property-header">
                    <Title level={5} className="property-name" ellipsis>
                      {item.name}
                    </Title>
                    <div className="rating-section">
                      <Rate
                        disabled
                        value={item.rating}
                        allowHalf
                        character={<StarFilled />}
                      />
                      <Text type="secondary" className="rating-text">
                        {item.rating} ({item.reviews})
                      </Text>
                    </div>
                  </div>

                  <div className="location-section">
                    <EnvironmentOutlined className="location-icon" />
                    <Text type="secondary" ellipsis>
                      {item.location}
                    </Text>
                  </div>

                  <div className="amenities-section">
                    <div className="amenities-list">
                      {item.amenities.slice(0, 4).map((amenity) => {
                        const icon = getAmenityIcon(amenity);
                        return icon ? (
                          <Tooltip key={amenity} title={amenity}>
                            <div className="amenity-icon">
                              {icon}
                            </div>
                          </Tooltip>
                        ) : null;
                      })}
                      {item.amenities.length > 4 && (
                        <div className="amenity-more">
                          +{item.amenities.length - 4}
                        </div>
                      )}
                    </div>
                  </div>

                  <Divider style={{ margin: '12px 0' }} />

                  <div className="price-section">
                    <div className="price-info">
                      {item.originalPrice && (
                        <Text delete type="secondary" className="original-price">
                          {formatPrice(item.originalPrice)}
                        </Text>
                      )}
                      <Title level={5} className="current-price">
                        {formatPrice(item.price)}
                        <Text type="secondary" className="price-unit">/night</Text>
                      </Title>
                    </div>
                  </div>

                  <div className="added-date">
                    <CalendarOutlined />
                    <Text type="secondary">
                      Added on {formatDate(item.addedDate)}
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
