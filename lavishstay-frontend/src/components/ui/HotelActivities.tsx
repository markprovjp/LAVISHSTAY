import React from "react";
import { Card, Button } from "antd";
import { Swiper, SwiperSlide } from "swiper/react";
import {
  Navigation,
  Pagination,
  Autoplay,
  EffectCoverflow,
} from "swiper/modules";
// import { motion } from "framer-motion";
import { CalendarOutlined, ArrowRightOutlined } from "@ant-design/icons";
import { Waves, Dumbbell, Sparkles } from "lucide-react";

// Import Swiper styles
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/effect-coverflow";
import "./HotelActivities.css";

interface Activity {
  id: number;
  title: string;
  description: string;
  image: string;
  icon: React.ReactNode;
  features: string[];
  gradient: string;
  color: string;
}

const HotelActivities: React.FC = () => {
  const activities: Activity[] = [
    {
      id: 1,
      title: "Spa YHI",
      description:
        "Tận hưởng liệu pháp mát-xa mặt hoặc chăm sóc cơ thể với các sản phẩm hữu cơ, tự nhiên. Các tiện nghi bao gồm tám phòng mát xa, mát xa thủy lực, xông hơi khô và xông hơi ướt cho khách nằm trong khách sạn tích hợp trung tâm spa ở Thanh Hóa này.",
      image: "../../public/images/home/spa.avif",
      icon: <Sparkles className="w-6 h-6" />,
      features: [
        "8 phòng mát xa",
        "Mát xa thủy lực",
        "Xông hơi khô & ướt",
        "Sản phẩm hữu cơ",
      ],
      gradient: "from-purple-500 to-pink-500",
      color: "#8b5cf6",
    },
    {
      id: 2,
      title: "Bể bơi",
      description:
        "Sau một ngày dài tham quan Thanh Hóa, không gì tuyệt vời hơn khi vừa ngâm mình thư giãn trong bể bơi nước nóng trong nhà vừa chiêm ngưỡng vẻ đẹp của toàn thành phố.",
      image: "../../public/images/home/beboi.avif",
      icon: <Waves className="w-6 h-6" />,
      features: [
        "Bể bơi nước nóng",
        "Tầm nhìn thành phố",
        "Không gian thư giãn",
        "Dịch vụ 24/7",
      ],
      gradient: "from-blue-500 to-cyan-500",
      color: "#3b82f6",
    },
    {
      id: 3,
      title: "Trung tâm thể dục",
      description:
        "Nằm cạnh bể bơi, du khách sẽ được tận hưởng ánh sáng tự nhiên cùng với tầm nhìn tuyệt đẹp trong lúc rèn luyện sức khỏe tại một trong những phòng tập gym hiện đại nhất thành phố.",
      image: "../../public/images/home/gym.avif",
      icon: <Dumbbell className="w-6 h-6" />,
      features: [
        "Thiết bị hiện đại",
        "Ánh sáng tự nhiên",
        "Tầm nhìn tuyệt đẹp",
        "Huấn luyện viên chuyên nghiệp",
      ],
      gradient: "from-orange-500 to-red-500",
      color: "#f59e0b",
    },
  ];

  return (
    <div className="relative py-6 overflow-hidden">
      {/* Background decoration */}
      <div className="absolute inset-0 bg-gradient-to-br "></div>
      <div className="absolute top-10 left-20 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse"></div>
      <div className="absolute top-10 right-0 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse animation-delay-2000"></div>
      <div className="absolute bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse animation-delay-4000"></div>

      <div className="container mx-auto px-4 relative z-10">
        {/* Header */}
        <div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6 }}
          className="text-center mb-12"
        >
          <h2 className="text-4xl font-bold     mb-4">Hoạt động & Tiện ích</h2>
          <p className="text-lg  max-w-2xl mx-auto">
            Khám phá những trải nghiệm đẳng cấp tại khách sạn với các tiện ích
            hiện đại và dịch vụ chăm sóc tận tâm
          </p>
        </div>

        {/* Activities Swiper */}
        <div
          initial={{ opacity: 0, y: 40 }}
          whileInView={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.8, delay: 0.2 }}
        >
          {" "}
          <Swiper
            effect={"coverflow"}
            grabCursor={true}
            centeredSlides={true}
            slidesPerView={"auto"}
            initialSlide={1}
            coverflowEffect={{
              rotate: 45,
              stretch: 0,
              depth: 100,
              modifier: 1,
              slideShadows: true,
            }}
            pagination={{
              clickable: true,
              dynamicBullets: true,
            }}
            navigation={true}
            // autoplay={{
            //    delay: 6000,
            //   disableOnInteraction: false,
            // }}
            loop={false}
            modules={[EffectCoverflow, Pagination, Navigation, Autoplay]}
            className="activities-swiper pb-12"
            style={{
              paddingLeft: "20px",
              paddingRight: "20px",
            }}
          >
            {activities.map((activity, index) => (
              <SwiperSlide
                key={activity.id}
                style={{ width: "520px", height: "auto" }}
              >
                <div
                  initial={{ opacity: 0, scale: 0.8 }}
                  whileInView={{ opacity: 1, scale: 1 }}
                  transition={{ duration: 0.6, delay: index * 0.1 }}
                  whileHover={{ y: -10 }}
                  className="h-full"
                >
                  <Card
                    className="activity-card h-full overflow-hidden shadow-xl border-0 bg-white dark:bg-slate-800"
                    bodyStyle={{ padding: 0 }}
                    style={{
                      borderRadius: "20px",
                      boxShadow: "0 25px 50px -12px rgba(0, 0, 0, 0.25)",
                    }}
                  >
                    {/* Image Section */}
                    <div className="relative h-64 overflow-hidden">
                      <img
                        src={activity.image}
                        alt={activity.title}
                        className="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                      />
                      <div
                        className={`absolute inset-0 bg-gradient-to-t ${activity.gradient} opacity-20`}
                      ></div>

                      {/* Icon overlay */}
                      <div className="absolute top-4 right-4">
                        <div
                          className="w-12 h-12 rounded-full flex items-center justify-center text-white shadow-lg"
                          style={{ backgroundColor: activity.color }}
                        >
                          {activity.icon}
                        </div>
                      </div>
                    </div>

                    {/* Content Section */}
                    <div className="p-6">
                      <h3 className="text-2xl font-bold mb-3 text-gray-800 dark:text-white">
                        {activity.title}
                      </h3>
                      <p className="text-gray-600 dark:text-gray-300 mb-4 leading-relaxed">
                        {activity.description}
                      </p>

                      {/* Features */}
                      <div className="mb-6">
                        <h4 className="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wide">
                          Tiện ích nổi bật
                        </h4>
                        <div className="grid grid-cols-2 gap-2">
                          {activity.features.map((feature, idx) => (
                            <div
                              key={idx}
                              className="flex items-center text-sm text-gray-600 dark:text-gray-300"
                            >
                              <div
                                className="w-2 h-2 rounded-full mr-2"
                                style={{ backgroundColor: activity.color }}
                              ></div>
                              {feature}
                            </div>
                          ))}
                        </div>
                      </div>

                      {/* CTA Button */}
                      <Button
                        type="primary"
                        size="large"
                        icon={<ArrowRightOutlined />}
                        className="w-full rounded-lg font-semibold transition-all duration-300 hover:shadow-lg"
                        style={{
                          backgroundColor: activity.color,
                          borderColor: activity.color,
                          height: "48px",
                        }}
                      >
                        Khám phá ngay
                      </Button>
                    </div>
                  </Card>
                </div>
              </SwiperSlide>
            ))}
          </Swiper>
        </div>

        {/* Bottom CTA */}
        <div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6, delay: 0.4 }}
          className="text-center mt-12"
        >
          <Button
            type="primary"
            size="large"
            icon={<CalendarOutlined />}
            className="bg-gradient-to-r from-blue-500 to-purple-600 border-0 rounded-lg px-8 py-6 h-auto font-semibold text-lg shadow-xl hover:shadow-2xl transition-all duration-300"
          >
            Đặt lịch trải nghiệm ngay
          </Button>{" "}
        </div>
      </div>
    </div>
  );
};

export default HotelActivities;
