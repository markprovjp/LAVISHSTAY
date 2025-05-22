import React from "react";
import { Row, Col, Button } from "antd";
import {
  HomeOutlined,
  SafetyOutlined,
  CustomerServiceOutlined,
} from "@ant-design/icons";
import { useTranslation } from "react-i18next";

// Import custom components
import HeroBanner from "../components/ui/HeroBanner";
import SearchForm from "../components/SearchForm";
import HotelCard from "../components/ui/HotelCard";
import SectionHeader from "../components/SectionHeader";
import FeatureCard from "../components/ui/FeatureCard";
import Testimonial from "../components/Testimonial";
import Newsletter from "../components/Newsletter";

const Home: React.FC = () => {
  const { t } = useTranslation();

  // Dữ liệu mẫu cho khách sạn/tài sản
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
  
  // Mẫu lời chứng thực
  const testimonials = [
    {
      id: 1,
      name: "Nguyễn Văn Quyền",
      avatar: "../../public/images/users/1.jpg",
      rating: 5,
      position: "Doanh nhân",
      comment:
        "Đi nghỉ dưỡng tại LavishStay mà cứ như ở thiên đường! Hướng dẫn viên chuyên nghiệp, nhân viên phục vụ tận tình, cảnh quan tuyệt đẹp. Đề nghị cho 10 điểm chất lượng!",
      date: "10/04/2025",
      tour: "Biệt thự hướng biển Nha Trang",
    },
    {
      id: 2,
      name: "Nguyễn Anh Đức",
      avatar: "../../public/images/users/5.jpg",
      rating: 5,
      position: "Giám đốc marketing",
      comment:
        "Chuyến đi Đà Nẵng - Hội An đúng kiểu 'work hard, play harder'. Căn hộ cao cấp view biển tuyệt đẹp, dịch vụ spa thư giãn hoàn hảo. Lần sau nhất định sẽ quay lại!",
      date: "02/05/2025",
      tour: "Penthouse cao cấp Đà Nẵng",
    },
    {
      id: 3,
      name: "Lê Hiểu Phước",
      avatar: "../../public/images/users/12.jpg",
      rating: 5,
      position: "Nhiếp ảnh gia",
      comment:
        "Đặt phòng với LavishStay mà tưởng đi nghỉ dưỡng 5 sao. Đồ ăn ngon, dịch vụ chuyên nghiệp, cảnh quan tuyệt vời cho chụp ảnh. Quá chất lượng!",
      date: "25/04/2025",
      tour: "Biệt thự trên đồi Đà Lạt",
    },
    {
      id: 4,
      name: "Lê Thị Trang",
      avatar: "../../public/images/users/9.jpg",
      rating: 4,
      position: "CEO Startup",
      comment:
        "Kỳ nghỉ ở Biệt thự LavishStay thực sự là trải nghiệm đáng nhớ. Không gian riêng tư, tiện nghi đầy đủ, nhân viên phục vụ tận tâm. Tôi đã có những giây phút thư giãn tuyệt vời cùng gia đình.",
      date: "15/04/2025",
      tour: "Biệt thự sang trọng Phú Quốc",
    },
    {
      id: 5,
      name: "Trần Minh Tuấn",
      avatar: "../../public/images/users/1.jpg",
      rating: 5,
      position: "Kiến trúc sư",
      comment:
        "Tôi thực sự ấn tượng với thiết kế của căn penthouse. Không gian mở thoáng đãng, nội thất tinh tế đến từng chi tiết. LavishStay mang đến cảm giác như được ở nhà nhưng vẫn trải nghiệm sự sang trọng của một resort 5 sao.",
      date: "05/05/2025",
      tour: "Penthouse view biển Vũng Tàu",
    },
  ];

  return (
    <div className="pb-1 ">
      {" "}
      {/* Hero Section */}
      <HeroBanner />
      {/* Search Form */}
      <div className="container mx-auto  -mt-4 relative z-10 mb-20">
        <SearchForm className="mx-auto shadow-xl" />
      </div>
      {/* Featured Properties Section */}
      <div className="container mx-auto px-4 mb-16 ">
        {" "}
        <SectionHeader
          title={t("home.featured.title")}
          subtitle={t("home.featured.subtitle")}
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
            Xem tất cả phòng
          </Button>
        </div>
      </div>
      {/* Features Section */}
      <div className="container mx-auto px-4 mb-16">
        {" "}
        <SectionHeader
          title={t("home.why.title")}
          subtitle={t("home.why.subtitle")}
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
      <div className=" py-16 mb-16">
        <div className="container mx-auto px-4">
          {" "}
          <SectionHeader
            title={t("home.testimonials.title")}
            subtitle={t("home.testimonials.subtitle")}
            centered
            withDivider
          />
          <div className="mt-12">
            <Testimonial testimonials={testimonials} />
          </div>
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
