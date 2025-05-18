import React from "react";
import { Row, Col, Button } from "antd";
import {
  HomeOutlined,
  SafetyOutlined,
  CustomerServiceOutlined,
} from "@ant-design/icons";

// Import custom components
import HeroBanner from "../components/ui/HeroBanner";
import SearchForm from "../components/SearchForm";
import HotelCard from "../components/ui/HotelCard";
import SectionHeader from "../components/SectionHeader";
import FeatureCard from "../components/ui/FeatureCard";
import Testimonial from "../components/Testimonial";
import Newsletter from "../components/Newsletter";

const Home: React.FC = () => {
  // Sample data for hotels/properties
  const featuredProperties = [
    {
      id: 1,
      title: "Biệt thự hướng biển",
      location: "Nha Trang, Việt Nam",
      price: 299,
      rating: 4.8,
      image:
        "https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80",
      category: "Biệt thự",
      features: ["Hồ bơi riêng", "Hướng biển", "3 phòng ngủ"],
    },
    {
      id: 2,
      title: "Penthouse cao cấp",
      location: "Đà Nẵng, Việt Nam",
      price: 349,
      rating: 4.7,
      image:
        "https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1080&q=80",
      category: "Căn hộ",
      features: ["Tầm nhìn thành phố", "Ban công rộng", "2 phòng ngủ"],
    },
    {
      id: 3,
      title: "Biệt thự trên đồi",
      location: "Đà Lạt, Việt Nam",
      price: 259,
      rating: 4.9,
      image:
        "https://images.unsplash.com/photo-1580587771525-78b9dba3b914?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80",
      category: "Biệt thự",
      features: ["Vườn rộng", "Không gian yên tĩnh", "4 phòng ngủ"],
    },
  ];

  // Sample testimonials
  const testimonials = [
    {
      id: 1,
      name: "Nguyễn Thị Mai",
      avatar: "https://randomuser.me/api/portraits/women/44.jpg",
      rating: 5,
      comment:
        "Kỳ nghỉ tuyệt vời nhất mà tôi từng có! Biệt thự sang trọng, view đẹp và dịch vụ hoàn hảo. LavishStay vượt xa mong đợi của tôi.",
      date: "10/04/2025",
      location: "Hà Nội, VN",
    },
    {
      id: 2,
      name: "Trần Văn Minh",
      avatar: "https://randomuser.me/api/portraits/men/32.jpg",
      rating: 5,
      comment:
        "Đội ngũ nhân viên rất chuyên nghiệp và tận tình. Căn penthouse có tầm nhìn tuyệt đẹp ra toàn thành phố. Chắc chắn sẽ quay lại!",
      date: "02/05/2025",
      location: "TP. Hồ Chí Minh, VN",
    },
    {
      id: 3,
      name: "Lê Hoàng Anh",
      avatar: "https://randomuser.me/api/portraits/men/67.jpg",
      rating: 4,
      comment:
        "Trải nghiệm tuyệt vời, không gian sống rộng rãi và đầy đủ tiện nghi. Chỉ tiếc là thời gian nghỉ quá ngắn!",
      date: "25/04/2025",
      location: "Đà Nẵng, VN",
    },  ];

  return (
    <div className="pb-12">
      {/* Hero Section */}
      <HeroBanner
        title="Khám phá những kỳ nghỉ xa hoa"
        subtitle="Tìm và đặt phòng tại những chỗ nghỉ sang trọng nhất cho chuyến du lịch tiếp theo của bạn."
        ctaText="Khám phá ngay"
        backgroundImage="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1760&q=80"
      />

      {/* Search Form */}
      <div className="container mx-auto px-4 -mt-24 relative z-10 mb-20">
        <SearchForm className="mx-auto shadow-xl" />
      </div>

      {/* Featured Properties Section */}
      <div className="container mx-auto px-4 mb-16">
        <SectionHeader
          title="Chỗ nghỉ nổi bật"
          subtitle="Khám phá những chỗ nghỉ được yêu thích nhất của chúng tôi"
          centered
          withDivider
        />

        <Row gutter={[24, 24]} className="mt-8">
          {featuredProperties.map((property) => (
            <Col xs={24} sm={12} md={8} key={property.id}>
              <HotelCard {...property} />
            </Col>
          ))}
        </Row>

        <div className="text-center mt-8">
          <Button type="primary" size="large">
            Xem tất cả chỗ nghỉ
          </Button>
        </div>
      </div>

      {/* Features Section */}
      <div className="container mx-auto px-4 mb-16">
        <SectionHeader
          title="Tại sao chọn LavishStay?"
          subtitle="Chúng tôi cung cấp những trải nghiệm lưu trú sang trọng và độc đáo"
          centered
          withDivider
        />

        <Row gutter={[24, 24]} className="mt-8">
          <Col xs={24} sm={12} md={8}>
            <FeatureCard
              icon={<HomeOutlined />}
              title="Chỗ nghỉ sang trọng"
              description="Tất cả chỗ nghỉ của chúng tôi đều được chọn lọc kỹ lưỡng để đảm bảo chất lượng cao nhất."
              benefits={[
                "Nội thất cao cấp, thiết kế hiện đại",
                "Tầm nhìn đẹp, không gian riêng tư",
                "Tiện nghi đầy đủ, hiện đại",
              ]}
              highlighted
            />
          </Col>
          <Col xs={24} sm={12} md={8}>
            <FeatureCard
              icon={<CustomerServiceOutlined />}
              title="Dịch vụ 24/7"
              description="Đội ngũ concierge luôn sẵn sàng hỗ trợ bạn mọi lúc, mọi nơi."
              benefits={[
                "Hỗ trợ khách hàng 24/7",
                "Dịch vụ đón tiễn sân bay",
                "Tư vấn du lịch chuyên nghiệp",
              ]}
            />
          </Col>
          <Col xs={24} sm={12} md={8}>
            <FeatureCard
              icon={<SafetyOutlined />}
              title="Đảm bảo an toàn"
              description="Chúng tôi đặt sự an toàn và thoải mái của khách hàng lên hàng đầu."
              benefits={[
                "Hệ thống an ninh 24/7",
                "Kiểm tra chất lượng định kỳ",
                "Dịch vụ y tế khẩn cấp",
              ]}
            />
          </Col>
        </Row>
      </div>

      {/* Testimonials Section */}
      <div className="bg-gray-50 py-16 mb-16">
        <div className="container mx-auto px-4">
          <SectionHeader
            title="Khách hàng nói gì về chúng tôi"
            subtitle="Những đánh giá từ khách hàng đã trải nghiệm dịch vụ của LavishStay"
            centered
            withDivider
          />

          <Row gutter={[24, 24]} className="mt-8">
            {testimonials.map((testimonial) => (
              <Col xs={24} sm={12} md={8} key={testimonial.id}>
                <Testimonial {...testimonial} />
              </Col>
            ))}
          </Row>
        </div>
      </div>

      {/* Newsletter Section */}
      <div className="container mx-auto px-4 mb-16">
        <Newsletter />
      </div>
    </div>
  );
};

export default Home;
