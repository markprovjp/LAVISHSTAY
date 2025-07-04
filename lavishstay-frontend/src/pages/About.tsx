import React from "react";
import {
  Typography,
  Row,
  Col,
  Card,
  Timeline,
  Button,
  Avatar,
  Collapse,
} from "antd";
import {
  UserOutlined,
  HomeOutlined,
  GlobalOutlined,
  TrophyOutlined,
  SafetyOutlined,
  CustomerServiceOutlined,
  DollarOutlined,
  EnvironmentOutlined,
  PhoneOutlined,
  MailOutlined,
  ClockCircleOutlined,
  QuestionCircleOutlined,
} from "@ant-design/icons";

// Import custom components
import PageHeader from "../components/PageHeader";
import SectionHeader from "../components/SectionHeader";
import FeatureCard from "../components/ui/FeatureCard";
import Stats from "../components/Stats";
import ContactForm from "../components/ContactForm";
import TravelExperience from "../components/ui/TravelExperience";
import HotelAmenities from "../components/ui/HotelAmenities";

const { Title, Paragraph, Text } = Typography;

const About: React.FC = () => {
  // Team members data
  const teamMembers = [
    {
      name: "Nguyễn Văn Quyền",
      position: "Tổng Giám đốc",
      avatar: "images/users/1.jpg",
      bio: "Hơn 15 năm kinh nghiệm trong ngành khách sạn và dịch vụ cao cấp.",
    },
    {
      name: "Nguyễn Anh Đức",
      position: "Giám đốc Vận hành",
      avatar: "/images/users/5.jpg",
      bio: "Chuyên gia vận hành khách sạn với kinh nghiệm tại các thương hiệu quốc tế.",
    },
    {
      name: "Lê Hiểu Phước",
      position: "Giám đốc F&B",
      avatar: "/images/users/12.jpg",
      bio: "Chuyên gia ẩm thực với bề dày kinh nghiệm tại các nhà hàng fine dining.",
    },
    {
      name: "Lê Thị Trang",
      position: "Giám đốc Dịch vụ khách hàng",
      avatar: "/images/users/9.jpg",
      bio: "Tâm huyết với việc tạo ra những trải nghiệm khách hàng đẳng cấp.",
    },
  ]; // Statistics data for hotel
  const stats = [
    {
      title: "Phòng nghỉ",
      value: 295,
      suffix: " phòng",
      icon: <HomeOutlined />,
    },
    {
      title: "Đánh giá trung bình",
      value: 9.4,
      suffix: "/10",
      icon: <TrophyOutlined />,
    },
    {
      title: "Cách sân bay",
      value: 20,
      suffix: " km",
      icon: <GlobalOutlined />,
    },
    {
      title: "Năm xây dựng",
      value: 2018,
      suffix: "",
      icon: <UserOutlined />,
    },
  ]; // FAQ data - Updated based on LavishStay Thanh Hóa information
  const faqData = [
    {
      key: "1",
      label: "LavishStay Thanh Hóa có bể bơi không?",
      children:
        "Có, LavishStay Thanh Hóa có hồ bơi trong nhà và quầy bar bên hồ bơi để quý khách thư giãn và tận hưởng thời gian nghỉ dưỡng.",
    },
    {
      key: "2",
      label: "Khách sạn cách sân bay bao xa?",
      children:
        "LavishStay Thanh Hóa cách sân bay Thọ Xuân (THD) khoảng 20 km, chỉ mất 30 phút lái xe. Rất thuận tiện cho việc di chuyển.",
    },
    {
      key: "3",
      label: "Thời gian nhận và trả phòng như thế nào?",
      children:
        "Thời gian nhận phòng từ 14:00 và trả phòng đến 12:00. Chính sách trẻ em có thể có phụ phí phát sinh.",
    },
    {
      key: "4",
      label: "Khách sạn có mấy phòng nghỉ?",
      children:
        "LavishStay Thanh Hóa có tổng cộng 295 phòng nghỉ sang trọng, được thiết kế hiện đại với đầy đủ tiện nghi cao cấp.",
    },
    {
      key: "5",
      label: "Có dịch vụ spa và massage không?",
      children:
        "Có, khách sạn cung cấp dịch vụ spa, massage và sauna với 75% phản hồi tích cực từ khách hàng đã sử dụng dịch vụ.",
    },
    {
      key: "6",
      label: "Giá bữa sáng như thế nào?",
      children:
        "Khách sạn phục vụ bữa sáng kiểu lục địa và tự chọn với giá 130.000 VND cho trẻ em và 260.000 VND cho người lớn nếu không kèm trong giá phòng.",
    },
    {
      key: "7",
      label: "Điểm đánh giá của khách hàng như thế nào?",
      children:
        "LavishStay Thanh Hóa nhận được điểm đánh giá trung bình 9.4/10 từ khách hàng với các điểm nổi bật: Giá trị 9.3, Tiện nghi 9.3, Sự sạch sẽ 9.5, Vị trí 9.5.",
    },
    {
      key: "8",
      label: "Có Wi-Fi miễn phí không?",
      children:
        "Có, khách sạn cung cấp Wi-Fi miễn phí trong tất cả các phòng và khu vực công cộng để quý khách luôn kết nối.",
    },
    {
      key: "9",
      label: "Có phòng tập gym không?",
      children:
        "Có, LavishStay Thanh Hóa có phòng tập gym miễn phí với đầy đủ trang thiết bị hiện đại cho khách hàng rèn luyện sức khỏe.",
    },
    {
      key: "10",
      label: "Có thể tổ chức sự kiện doanh nghiệp không?",
      children:
        "Có, khách sạn hỗ trợ tổ chức các sự kiện doanh nghiệp như hội nghị, hội thảo với 6 địa điểm, chứa đến 900 người, cùng máy chiếu và thiết bị nghe nhìn.",
    },
    {
      key: "11",
      label: "Có thể tổ chức sự kiện đặc biệt không?",
      children:
        "Có, khách sạn hỗ trợ tổ chức sự kiện đặc biệt như tiệc đính hôn, đám cưới, tiệc sinh nhật với dịch vụ trang trí, âm nhạc và catering.",
    },
    {
      key: "12",
      label: "Địa điểm gần khách sạn có gì?",
      children:
        "Gần khách sạn có Bệnh viện Đa khoa Hợp Lực, Siêu thị Điện máy HC, Hồ Thành, Đền Bà Triệu, và Công viên tượng đài Lê Lợi.",
    },
  ];

  // Core values data
  const values = [
    {
      icon: <TrophyOutlined />,
      title: "Chất lượng hàng đầu",
      description:
        "Chúng tôi chỉ cung cấp những dịch vụ đạt tiêu chuẩn cao nhất về chất lượng và phong cách phục vụ.",
      benefits: [
        "Kiểm soát chất lượng nghiêm ngặt",
        "Đánh giá thường xuyên",
        "Tiêu chuẩn cao về dịch vụ",
      ],
    },
    {
      icon: <CustomerServiceOutlined />,
      title: "Dịch vụ khách hàng 24/7",
      description:
        "Đội ngũ hỗ trợ khách hàng chuyên nghiệp luôn sẵn sàng phục vụ bạn mọi lúc, mọi nơi.",
      benefits: [
        "Hỗ trợ đa ngôn ngữ",
        "Phản hồi nhanh chóng",
        "Tư vấn chuyên nghiệp",
      ],
      highlighted: true,
    },
    {
      icon: <SafetyOutlined />,
      title: "An toàn & Bảo mật",
      description:
        "Chúng tôi đặt sự an toàn và bảo mật thông tin khách hàng lên hàng đầu.",
      benefits: [
        "Thanh toán an toàn",
        "Bảo mật thông tin cá nhân",
        "Kiểm tra an ninh nghiêm ngặt",
      ],
    },
    {
      icon: <DollarOutlined />,
      title: "Giá trị tối ưu",
      description:
        "Chúng tôi cam kết mang lại giá trị tốt nhất cho khách hàng cho mỗi đồng bạn chi tiêu.",
      benefits: [
        "Đảm bảo giá tốt nhất",
        "Chương trình khách hàng thân thiết",
        "Ưu đãi đặc biệt thường xuyên",
      ],
    },
  ];

  return (
    <div>
      {/* Hero Section */}
      <PageHeader
        title="Về LavishStay Thanh Hóa"
        subtitle="Khách sạn cao cấp hàng đầu tại thành phố Thanh Hóa - Nơi kết hợp hoàn hảo giữa sang trọng và truyền thống"
        coverImage="/images/about/banner.avif"
        className="h-60"
        breadcrumbs={[{ title: "Về chúng tôi" }]}
      />

      <div className="container mx-auto px-4 py-12">
        {/* Location and Contact Section */}
        <Row gutter={[48, 48]} className="mb-16">
          <Col xs={24} md={12}>
            <div className="map-container w-full h-full overflow-hidden rounded-lg shadow-md">
              <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5655.00165247648!2d105.7775365!3d19.8099334!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3136f7ff5d015ba9%3A0x2a1fb21a65dd1ca2!2sMeli%C3%A1%20Vinpearl%20Thanh%20Hoa!5e1!3m2!1svi!2s!4v1747969514615!5m2!1svi!2s"
                className="w-full"
                style={{ height: "700px", border: 0 }}
                loading="lazy"
                title="Google Maps - LavishStay Thanh Hoá"
                allowFullScreen
              ></iframe>
            </div>
          </Col>
          <Col xs={24} md={12}>
            <SectionHeader title="Thông tin liên hệ" withDivider />
            <div className="space-y-4 sm:space-y-5 font-bevietnam mt-4">
              <div className="mb-6">
                <Title level={4} className="mb-2 flex items-center">
                  <EnvironmentOutlined style={{ marginRight: "8px" }} />
                  Địa chỉ
                </Title>
                <div className="ml-0 sm:ml-7">
                  <Paragraph className="text-base sm:text-lg">
                    Số 27 Trần Phú, Phường Điện Biên, Thành Phố Thanh Hóa
                  </Paragraph>
                  <Paragraph className="font-medium">
                    Thanh Hoa, Vietnam
                  </Paragraph>
                </div>
              </div>
              <div className="mb-6">
                <Title level={4} className="mb-2 flex items-center">
                  <CustomerServiceOutlined style={{ marginRight: "8px" }} />
                  Liên hệ
                </Title>
                <div className="ml-0 sm:ml-7">
                  <Paragraph className="mb-3 flex items-center flex-wrap">
                    <PhoneOutlined
                      className="mr-2 min-w-[16px]"
                      style={{ color: "#888" }}
                    />
                    <a
                      href="tel:842378936888"
                      className="break-all sm:break-normal"
                    >
                      84-237 893 6888
                    </a>
                  </Paragraph>
                  <Paragraph className="mb-1 flex items-center flex-wrap">
                    <MailOutlined
                      className="mr-2 min-w-[16px]"
                      style={{ color: "#888" }}
                    />
                    <a
                      href="mailto:quyenjpn@gmail.com"
                      className="break-all sm:break-normal"
                    >
                      quyenjpn@gmail.com
                    </a>
                  </Paragraph>
                </div>
              </div>
              <div className="mb-6">
                <Title level={4} className="mb-2 flex items-center">
                  <ClockCircleOutlined style={{ marginRight: "8px" }} />
                  Giờ nhận/trả phòng
                </Title>
                <div className="ml-0 sm:ml-7 grid grid-cols-1 sm:grid-cols-2 gap-2">
                  <Paragraph className="mb-1">
                    <strong>Nhận phòng:</strong> 14:00
                  </Paragraph>
                  <Paragraph className="mb-1">
                    <strong>Trả phòng:</strong> 12:00
                  </Paragraph>
                </div>
              </div>
              <div>
                <Title level={4} className="mb-4 flex items-center">
                  <GlobalOutlined style={{ marginRight: "8px" }} />
                  Thông tin khách sạn
                </Title>

                <div className="ml-0 sm:ml-3 p-4 border border-gray-100 rounded-lg bg-opacity-50">
                  <Paragraph className="mb-2 flex flex-wrap items-center">
                    <span className="inline-block w-full sm:w-32 font-medium mb-1 sm:mb-0">
                      Tên:
                    </span>
                    <span className="font-semibold">LavishStay Thanh Hoá</span>
                  </Paragraph>{" "}
                  <Paragraph className="mb-2 flex flex-wrap items-center">
                    <span className="inline-block w-full sm:w-32 font-medium mb-1 sm:mb-0">
                      Loại:
                    </span>
                    <span>Khách sạn 5 sao</span>
                  </Paragraph>
                  <Paragraph className="mb-2 flex flex-wrap items-center">
                    <span className="inline-block w-full sm:w-32 font-medium mb-1 sm:mb-0">
                      Vị trí:
                    </span>
                    <span>Bãi biển Sầm Sơn</span>
                  </Paragraph>
                  <Paragraph className="mb-2 flex flex-wrap items-center">
                    <span className="inline-block w-full sm:w-32 font-medium mb-1 sm:mb-0">
                      Số phòng:
                    </span>
                    <span>295 phòng nghỉ</span>
                  </Paragraph>
                  <Paragraph className="mb-2 flex flex-wrap items-center">
                    <span className="inline-block w-full sm:w-32 font-medium mb-1 sm:mb-0">
                      Năm xây dựng:
                    </span>
                    <span>2018</span>
                  </Paragraph>
                  <Paragraph className="mb-2 flex flex-wrap items-center">
                    <span className="inline-block w-full sm:w-32 font-medium mb-1 sm:mb-0">
                      Đánh giá:
                    </span>
                    <span className="text-yellow-500 font-semibold">
                      9.4/10 ⭐⭐⭐⭐⭐
                    </span>
                  </Paragraph>
                </div>
              </div>
            </div>
          </Col>
        </Row>
        {/* Stats Section */}
        <div className="mb-16">
          <Stats
            title="LavishStay Thanh Hóa trong số liệu"
            subtitle="Những con số ấn tượng thể hiện chất lượng dịch vụ của chúng tôi"
            stats={stats}
          />
        </div>
        {/* Core Values Section */}
        <div className="mb-16">
          <SectionHeader
            title="Giá trị cốt lõi"
            subtitle="Những nguyên tắc định hướng mọi hoạt động của chúng tôi"
            centered
            withDivider
            className="mb-10"
          />

          <Row gutter={[24, 24]}>
            {values.map((value, index) => (
              <Col xs={24} sm={12} md={6} key={index}>
                <FeatureCard
                  icon={value.icon}
                  title={value.title}
                  description={value.description}
                  benefits={value.benefits}
                  highlighted={value.highlighted}
                />
              </Col>
            ))}
          </Row>
        </div>
        {/* Our Team Section */}
        <div className="mb-16">
          <SectionHeader
            title="Đội ngũ quản lý khách sạn"
            subtitle="Những chuyên gia giàu kinh nghiệm mang đến dịch vụ tốt nhất cho khách hàng"
            centered
            withDivider
            className="mb-10"
          />

          <Row gutter={[24, 24]}>
            {teamMembers.map((member, index) => (
              <Col xs={24} sm={12} md={6} key={index}>
                <Card
                  className="text-center h-full hover:shadow-md transition-all duration-300"
                  cover={
                    <div className="pt-6">
                      <Avatar
                        size={120}
                        src={member.avatar}
                        className="border-4 border-blue-100 mx-auto"
                      />
                    </div>
                  }
                >
                  <Title
                    level={4}
                    className="font-bevietnam font-semibold mb-1"
                  >
                    {member.name}
                  </Title>
                  <Text className="text-blue-600 font-bevietnam font-medium block mb-3">
                    {member.position}
                  </Text>
                  <Paragraph className="font-bevietnam ">
                    {member.bio}
                  </Paragraph>
                </Card>
              </Col>
            ))}
          </Row>
        </div>
        {/* FAQ Section */}
        <div className="mb-16">
          <SectionHeader
            title="Câu hỏi thường gặp"
            subtitle="Những thông tin hữu ích về LavishStay Thanh Hóa"
            centered
            withDivider
            className="mb-10"
          />{" "}
          <div className="max-w-4xl mx-auto">
            <Collapse
              accordion
              size="large"
              expandIcon={({ isActive }) => (
                <QuestionCircleOutlined
                  rotate={isActive ? 180 : 0}
                  style={{
                    color: "#1890ff",
                    fontSize: "18px",
                    transition: "all 0.3s ease",
                  }}
                />
              )}
              className="font-bevietnam faq-collapse"
              ghost
              items={faqData.map((item) => ({
                key: item.key,
                label: (
                  <span className="font-semibold  text-base">{item.label}</span>
                ),
                children: (
                  <div className="bg-gradient-to-r  p-4 rounded-lg">
                    <p className="  leading-relaxed text-base">
                      {item.children}
                    </p>
                  </div>
                ),
                style: {
                  marginBottom: "16px",
                  borderRadius: "12px",
                  border: "1px solid #e5e7eb",
                  boxShadow: "0 2px 8px rgba(0,0,0,0.06)",
                },
              }))}
            />
          </div>
        </div>{" "}
        {/* Travel Experience Section */}
        <div className="mb-16">
          <TravelExperience
            title="Khám phá vẻ đẹp Thanh Hóa"
            subtitle="Trải nghiệm du lịch đẳng cấp cùng LavishStay"
            description="Từ LavishStay Thanh Hóa, bạn có thể dễ dàng khám phá những điểm đến nổi tiếng của tỉnh Thanh Hóa. Từ biển Sầm Sơn tuyệt đẹp đến di tích lịch sử Hồ Đền Lũng, từ núi Hồng Lĩnh hùng vĩ đến những món ăn đặc sản địa phương đầy hấp dẫn."
            image="/images/about/banner.avif"
          />
        </div>
        {/* Hotel Amenities Section */}
        <div className="mb-16">
          <HotelAmenities />
        </div>
        {/* Timeline Section */}
        <div className="mb-16">
          <SectionHeader
            title="Hành trình phát triển"
            subtitle="Những dấu mốc quan trọng trong quá trình phát triển của LavishStay Thanh Hóa"
            centered
            withDivider
            className="mb-10"
          />

          <div className="max-w-3xl mx-auto">
            <Timeline
              mode="alternate"
              items={[
                {
                  color: "blue",
                  children: (
                    <div className="font-bevietnam">
                      <Title level={4} className="text-blue-600 mb-1">
                        2017 - Khởi công xây dựng
                      </Title>
                      <Paragraph className="">
                        Bắt đầu xây dựng khách sạn 5 sao với thiết kế hiện đại
                        tại bãi biển Sầm Sơn.
                      </Paragraph>
                    </div>
                  ),
                },
                {
                  color: "blue",
                  children: (
                    <div className="font-bevietnam">
                      <Title level={4} className="text-blue-600 mb-1">
                        2018 - Hoàn thiện và khai trương
                      </Title>
                      <Paragraph className="">
                        Hoàn thiện 295 phòng nghỉ sang trọng, spa, hồ bơi và các
                        tiện ích giải trí.
                      </Paragraph>
                    </div>
                  ),
                },
                {
                  color: "blue",
                  children: (
                    <div className="font-bevietnam">
                      <Title level={4} className="text-blue-600 mb-1">
                        2019-2023 - Phát triển dịch vụ
                      </Title>
                      <Paragraph className="">
                        Không ngừng cải thiện chất lượng dịch vụ và đạt được
                        điểm đánh giá 9.4/10.
                      </Paragraph>
                    </div>
                  ),
                },
                {
                  color: "green",
                  children: (
                    <div className="font-bevietnam">
                      <Title level={4} className="text-green-600 mb-1">
                        2024-2025 - Vị thế hàng đầu
                      </Title>
                      <Paragraph className="">
                        Trở thành khách sạn 5 sao hàng đầu tại Thanh Hóa với
                        dịch vụ đẳng cấp quốc tế.
                      </Paragraph>
                    </div>
                  ),
                },
              ]}
            />
          </div>
        </div>
        {/* Contact & Newsletter Section */}
        <Row gutter={[48, 48]}>
          <Col xs={24} md={24}>
            <ContactForm />
          </Col>
        </Row>
      </div>
    </div>
  );
};

export default About;
