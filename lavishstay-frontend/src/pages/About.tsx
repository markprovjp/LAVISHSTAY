import React from "react";
import { Typography, Row, Col, Card, Timeline, Image, Avatar } from "antd";
import {
  UserOutlined,
  HomeOutlined,
  GlobalOutlined,
  TrophyOutlined,
  SafetyOutlined,
  CustomerServiceOutlined,
  DollarOutlined,
} from "@ant-design/icons";

// Import custom components
import PageHeader from "../components/PageHeader";
import SectionHeader from "../components/SectionHeader";
import FeatureCard from "../components/ui/FeatureCard";
import Stats from "../components/Stats";

import ContactForm from "../components/ContactForm";

const { Title, Paragraph, Text } = Typography;

const About: React.FC = () => {
  // Team members data
  const teamMembers = [
    {
      name: "Nguyễn Văn Quyền",
      position: "Đồng sáng lập & CEO",
      avatar: "../../public/images/users/1.jpg",
      bio: "Hơn 15 năm kinh nghiệm trong ngành khách sạn và du lịch cao cấp.",
    },
    {
      name: "Nguyễn Anh Đức",
      position: "Giám đốc Marketing",
      avatar: "../../public/images/users/5.jpg",
      bio: "Chuyên gia marketing với kinh nghiệm tại các tập đoàn đa quốc gia.",
    },
    {
      name: "Lê Hiểu Phước",
      position: "Giám đốc Công nghệ",
      avatar: "../../public/images/users/12.jpg",
      bio: "Chuyên gia công nghệ với bề dày kinh nghiệm phát triển các nền tảng đặt phòng.",
    },
    {
      name: "Lê Thị Trang",
      position: "Giám đốc Trải nghiệm khách hàng",
      avatar: "../../public/images/users/9.jpg",
      bio: "Tâm huyết với việc tạo ra những trải nghiệm khách hàng đẳng cấp.",
    },
  ];

  // Statistics data
  const stats = [
    {
      title: "Bất động sản",
      value: 5000,
      suffix: "+",
      icon: <HomeOutlined />,
    },
    {
      title: "Khách hàng",
      value: 100000,
      suffix: "+",
      icon: <UserOutlined />,
    },
    {
      title: "Điểm đến",
      value: 120,
      suffix: "+",
      icon: <GlobalOutlined />,
    },
    {
      title: "Đánh giá 5 sao",
      value: 97,
      suffix: "%",
      icon: <TrophyOutlined />,
    },
  ];

  // Core values data
  const values = [
    {
      icon: <TrophyOutlined />,
      title: "Chất lượng hàng đầu",
      description:
        "Chúng tôi chỉ hợp tác với những chỗ ở đạt tiêu chuẩn cao nhất về chất lượng và dịch vụ.",
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
        title="Về LavishStay"
        subtitle="Nền tảng đặt phòng cao cấp kết nối du khách với những trải nghiệm lưu trú xa hoa"
        coverImage="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1920&q=80"
        breadcrumbs={[{ title: "Giới thiệu" }]}
      />

      <div className="container mx-auto px-4 py-12">
        {/* Our Story Section */}
        <Row gutter={[48, 48]} className="mb-16">
          <Col xs={24} md={12}>
            <SectionHeader title="Câu chuyện của chúng tôi" withDivider />
            <div className="space-y-4 font-bevietnam">
              <Paragraph className=" text-lg">
                LavishStay được thành lập vào năm 2022 với sứ mệnh mang đến
                những trải nghiệm lưu trú xa hoa, độc đáo cho những du khách
                đang tìm kiếm không gian nghỉ dưỡng sang trọng và đẳng cấp.
              </Paragraph>
              <Paragraph className=" text-lg">
                Từ một ý tưởng đơn giản về việc kết nối khách hàng với những bất
                động sản cao cấp, chúng tôi đã phát triển thành một nền tảng
                toàn diện cung cấp dịch vụ đặt phòng tại các biệt thự, căn hộ và
                khách sạn sang trọng trên toàn Việt Nam.
              </Paragraph>
              <Paragraph className=" text-lg">
                Với đội ngũ nhân viên giàu kinh nghiệm và am hiểu về ngành du
                lịch cao cấp, chúng tôi cam kết mang đến cho khách hàng những
                trải nghiệm độc đáo và dịch vụ chất lượng cao nhất.
              </Paragraph>
            </div>
          </Col>
          <Col xs={24} md={12}>
            <div className="relative h-full flex items-center">
              <Image
                src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=800&h=600&q=80"
                alt="Luxury accommodation"
                className="rounded-lg shadow-lg"
              />
            </div>
          </Col>
        </Row>

        {/* Stats Section */}
        <div className="mb-16">
          <Stats
            stats={stats}
            title="LavishStay trong những con số"
            subtitle="Chúng tôi tự hào về sự phát triển và những thành tựu đã đạt được"
          />
        </div>

        {/* Our Values Section */}
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
              <Col xs={24} sm={12} lg={6} key={index}>
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
            title="Đội ngũ của chúng tôi"
            subtitle="Những con người tài năng và đầy nhiệt huyết đứng sau thành công của LavishStay"
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
                  <Paragraph className="font-bevietnam text-gray-600">
                    {member.bio}
                  </Paragraph>
                </Card>
              </Col>
            ))}
          </Row>
        </div>



        {/* Timeline Section */}
        <div className="mb-16">
          <SectionHeader
            title="Hành trình phát triển"
            subtitle="Những dấu mốc quan trọng trong quá trình phát triển của LavishStay"
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
                        2022 - Khởi đầu
                      </Title>
                      <Paragraph className="text-gray-600">
                        LavishStay được thành lập với mục tiêu kết nối du khách
                        với những chỗ ở cao cấp.
                      </Paragraph>
                    </div>
                  ),
                },
                {
                  color: "blue",
                  children: (
                    <div className="font-bevietnam">
                      <Title level={4} className="text-blue-600 mb-1">
                        2023 - Mở rộng
                      </Title>
                      <Paragraph className="text-gray-600">
                        Mở rộng hoạt động đến các thành phố lớn trên cả nước với
                        hơn 1000 đối tác.
                      </Paragraph>
                    </div>
                  ),
                },
                {
                  color: "blue",
                  children: (
                    <div className="font-bevietnam">
                      <Title level={4} className="text-blue-600 mb-1">
                        2024 - Đổi mới
                      </Title>
                      <Paragraph className="text-gray-600">
                        Ra mắt ứng dụng di động và nhiều tính năng mới, nâng cao
                        trải nghiệm người dùng.
                      </Paragraph>
                    </div>
                  ),
                },
                {
                  color: "green",
                  children: (
                    <div className="font-bevietnam">
                      <Title level={4} className="text-green-600 mb-1">
                        2025 - Hiện tại
                      </Title>
                      <Paragraph className="text-gray-600">
                        Phát triển thành nền tảng hàng đầu về đặt phòng cao cấp
                        tại Việt Nam với hơn 5000 bất động sản.
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
          {/* <Col xs={24} md={12} className="flex items-center">
            <Newsletter />
          </Col> */}
        </Row>
      </div>
    </div>
  );
};

export default About;
