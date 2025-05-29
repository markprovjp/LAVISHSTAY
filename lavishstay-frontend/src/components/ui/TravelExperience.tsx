import React, { useState } from "react";
import { Card, Typography, Button, Space, List } from "antd";
import { motion } from "framer-motion";
import {
  ArrowRightOutlined,
  EyeOutlined,
  BookOutlined,
} from "@ant-design/icons";
import "./TravelExperience.css";

const { Title, Paragraph } = Typography;

interface TravelExperienceProps {
  title?: string;
  subtitle?: string;
  description?: string;
  fullContent?: string | React.ReactNode;
  image?: string;
  className?: string;
}

const TravelExperience: React.FC<TravelExperienceProps> = ({
  title = "LavishStay Thanh Hóa - Kỳ nghỉ lý tưởng tại Bãi biển Sầm Sơn",
  subtitle = "Khách sạn 5 sao với 295 phòng nghỉ sang trọng",
  description = "LavishStay Thanh Hóa là một khách sạn 5 sao tọa lạc tại Bãi biển Sầm Sơn, Thanh Hóa, Việt Nam. Với 295 phòng nghỉ sang trọng, khách sạn này mang đến trải nghiệm kỳ nghỉ đáng nhớ bên bờ biển. Khách sạn cung cấp nhiều tiện nghi như hồ bơi, spa, phòng tập gym và nhà hàng.",
  fullContent = (
    <>
      <Paragraph>
        LavishStay Thanh Hóa được xây dựng vào năm 2018 và nằm cách sân bay Thọ Xuân (THD) khoảng 20 km, chỉ mất 30 phút lái xe. Với vị trí thuận lợi ngay trung tâm thành phố Thanh Hóa, khách sạn mang đến sự tiện lợi cho du khách khi khám phá các điểm đến nổi tiếng. Thời gian nhận phòng từ 14:00 và trả phòng đến 12:00. Chính sách trẻ em có thể có phụ phí phát sinh.
      </Paragraph>
      <Title level={4}>Tiện Nghi Nổi Bật</Title>
      <List
        bordered
        dataSource={[
          "Hồ bơi trong nhà và quầy bar bên hồ bơi",
          "Spa, massage và sauna",
          "Phòng tập gym miễn phí",
          "Nhà hàng với bữa sáng kiểu lục địa và tự chọn (phụ phí: 130,000 VND cho trẻ em, 260,000 VND cho người lớn)",
          "Wi-Fi miễn phí trong tất cả các phòng và khu vực công cộng",
          "Dịch vụ giặt là, dịch vụ phòng, hòm đựng đồ an toàn, giữ hành lý",
        ]}
        renderItem={(item) => <List.Item>{item}</List.Item>}
      />
      <Title level={4}>Địa Điểm Gần Đó</Title>
      <List
        bordered
        dataSource={[
          "Bệnh viện Đa khoa Hợp Lực (cách vài bước chân)",
          "Siêu thị Điện máy HC (cách 147m)",
          "Hồ Thành",
          "Đền Bà Triệu",
          "Công viên tượng đài Lê Lợi",
          "Công viên cây xanh",
          "Bệnh viện Đa khoa Thành phố Thanh Hoá",
          "Tiệm thuốc Minh Long",
        ]}
        renderItem={(item) => <List.Item>{item}</List.Item>}
      />
      <Title level={4}>Đánh Giá Khách Hàng</Title>
      <Paragraph>
        LavishStay Thanh Hóa nhận được điểm đánh giá trung bình 9.4 từ khách hàng, với các điểm nổi bật:
      </Paragraph>
      <List
        bordered
        dataSource={[
          "Giá trị: 9.3",
          "Tiện nghi: 9.3",
          "Sự sạch sẽ: 9.5",
          "Vị trí: 9.5",
          "Hiệu suất nhân viên: 9.3",
          "Du khách nội địa: 9.6",
          "Khách đi một mình: 9.4",
          "Dịch vụ spa: 75% phản hồi tích cực",
        ]}
        renderItem={(item) => <List.Item>{item}</List.Item>}
      />
      <Title level={4}>Tổ Chức Sự Kiện</Title>
      <Paragraph>
        LavishStay Thanh Hóa là lựa chọn lý tưởng cho các sự kiện doanh nghiệp (hội nghị, hội thảo, họp mặt) với 6 địa điểm, chứa đến 900 người, cùng máy chiếu, thiết bị nghe nhìn, và Wi-Fi miễn phí. Ngoài ra, khách sạn hỗ trợ tổ chức sự kiện đặc biệt như tiệc đính hôn, đám cưới, tiệc sinh nhật với dịch vụ trang trí, âm nhạc, và catering.
      </Paragraph>
    </>
  ),
  image = "/images/lavishstay-thanh-hoa.jpg",
  className = "",
}) => {
  const [showFullContent, setShowFullContent] = useState(false);

  const toggleContent = () => {
    setShowFullContent(!showFullContent);
  };

  return (
    <div className={`travel-experience-container ${className}`}>
      <motion.div
        initial={{ opacity: 0, y: 20 }}
        whileInView={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.6 }}
        className="text-center mb-8"
      >
        <Title level={2} className="mb-3">
          {title}
        </Title>
        <p className="text-lg opacity-80">{subtitle}</p>
      </motion.div>

      <motion.div
        initial={{ opacity: 0, y: 40 }}
        whileInView={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.8, delay: 0.2 }}
      >
        <Card className="travel-experience-card shadow-lg">
          <div className="relative mb-6">
            <img
              src={image}
              alt="LavishStay Thanh Hóa"
              className="w-full h-64 object-cover rounded-lg"
            />
            <div className="absolute top-4 left-4 inline-flex items-center gap-2  backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium">
              <BookOutlined />
              <span>Khách sạn 5 sao</span>
            </div>
          </div>

          <div className="space-y-4">
            <Paragraph className="text-base leading-relaxed">
              {showFullContent ? fullContent : description}
            </Paragraph>

            <div className="flex flex-col sm:flex-row gap-3 pt-4">
              <Button
                type="default"
                icon={<EyeOutlined />}
                onClick={toggleContent}
                className="flex-1"
              >
                {showFullContent ? "Thu gọn" : "Xem thêm"}
              </Button>
              <Button
                type="primary"
                icon={<ArrowRightOutlined />}
                className="flex-1"
              >
                Đặt phòng ngay
              </Button>
            </div>

            <div className="flex flex-wrap gap-2 pt-2">
              {[
                "Khách sạn 5 sao",
                "Bãi biển Sầm Sơn",
                "Hồ bơi",
                "Spa",
                "Nhà hàng",
                "Gần trung tâm thành phố",
              ].map((tag, index) => (
                <span
                  key={index}
                  className="inline-block px-3 py-1 text-xs rounded-full border opacity-70"
                >
                  {tag}
                </span>
              ))}
            </div>
          </div>
        </Card>
      </motion.div>
    </div>
  );
};

export default TravelExperience;