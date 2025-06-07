import React, { useMemo } from "react";
import { Button, Tabs, Spin } from "antd";
import { useTranslation } from "react-i18next";

// Import custom components
import HeroBanner from "../components/ui/HeroBanner";
import SearchForm from "../components/SearchForm";
import SectionHeader from "../components/SectionHeader";
import Testimonial from "../components/Testimonial";
import Newsletter from "../components/Newsletter";
import RoomSwiper from "../components/ui/RoomSwiper";
import Awards from "../components/ui/Awards";
import HotelActivities from "../components/ui/HotelActivities";
import SwiftPande from "../components/ui/swiftPanda";

// Import API hooks with axios
import { useGetAllRooms, useGetRoomsByType } from "../hooks/useApi";
// Import Room type from models
import { Room } from "../mirage/models";

const Home: React.FC = () => {
  const { t } = useTranslation();

  // Fetch all rooms for the sale section using axios
  const { data: allRoomsData, isLoading: isAllRoomsLoading } = useGetAllRooms();
  // Fetch rooms by type for the tabs using axios
  const { data: deluxeRoomsData, isLoading: isDeluxeLoading } = useGetRoomsByType("deluxe");
  const { data: premiumRoomsData, isLoading: isPremiumLoading } = useGetRoomsByType("premium");
  const { data: suiteRoomsData, isLoading: isSuiteLoading } = useGetRoomsByType("suite");
  const { data: presidentialRoomsData, isLoading: isPresidentialLoading } = useGetRoomsByType("presidential");
  const { data: theLevelRoomsData, isLoading: isTheLevelLoading } = useGetRoomsByType("theLevel");

  // Process rooms with discounts for the sale section
  const saleRooms = useMemo(() => {
    if (allRoomsData?.rooms) {
      return allRoomsData.rooms
        .filter((room: Room) => room.discount)
        .map((room: Room) => ({ ...room, isSale: true }));
    }
    return [];
  }, [allRoomsData]);

  // Mẫu lời chứng thực
  const testimonials = [
    {
      id: 1,
      name: "Nguyễn Văn Quyền",
      avatar: "images/users/1.jpg",
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
      avatar: "images/users/5.jpg",
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
      avatar: "images/users/12.jpg",
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
      avatar: "images/users/9.jpg",
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
      avatar: "images/users/1.jpg",
      rating: 5,
      position: "Kiến trúc sư",
      comment:
        "Tôi thực sự ấn tượng với thiết kế của căn penthouse. Không gian mở thoáng đãng, nội thất tinh tế đến từng chi tiết. LavishStay mang đến cảm giác như được ở nhà nhưng vẫn trải nghiệm sự sang trọng của một resort 5 sao.",
      date: "05/05/2025",
      tour: "Penthouse view biển Vũng Tàu",
    },
  ];

  return (
    <div className="pb-1">
      {/* Hero Section */}
      <HeroBanner />
      {/* Search Form */}
      <div className="container mx-auto mt-4 mb-4 relative z-10 ">
        <SearchForm className="mx-auto shadow-xl" />
      </div>{" "}      {/* Awards Section */}
      <Awards />


      {/* Sale Rooms Section */}
      <div className="container mx-auto px-4 mb-16">
        <SectionHeader
          title="Ưu đãi đặc biệt"
          subtitle="Những phòng cao cấp với giá ưu đãi hấp dẫn"
          centered
          withDivider
        />{" "}
        <div className="mt-8">
          {isAllRoomsLoading ? (
            <div className="text-center py-10">
              <Spin size="large" tip="Đang tải phòng..." />
            </div>
          ) : saleRooms.length > 0 ? (
            <RoomSwiper rooms={saleRooms} />
          ) : (
            <div className="text-center py-10">
              <p>Không có phòng khuyến mãi nào vào thời điểm hiện tại</p>
            </div>
          )}
        </div>
      </div>{" "}
      {/* All Rooms Section with Tabs */}
      <div className="container mx-auto px-4 mb-16">
        <SectionHeader
          title="Các loại phòng của chúng tôi"
          subtitle="Khám phá đa dạng các loại phòng để có kỳ nghỉ hoàn hảo"
          centered
          withDivider
        />{" "}
        <div className="mt-8">
          <Tabs
            defaultActiveKey="theLevel"
            centered
            size="large"
            tabBarStyle={{
              marginBottom: "24px",
              fontWeight: 500,
            }}
            tabBarGutter={40}
            className="room-category-tabs" items={[{
              key: "deluxe",
              label: (
                <span style={{ fontSize: "18px", color: "#6366f1", fontWeight: "600" }}>Phòng Deluxe</span>
              ), children: (
                <div
                  className="py-4 px-2"
                  style={{
                    backgroundColor: "rgba(99, 102, 241, 0.08)",
                    borderRadius: "12px",
                  }}
                >
                  {isDeluxeLoading ? (
                    <div className="text-center py-10">
                      <Spin size="large" tip="Đang tải phòng..." />
                    </div>
                  ) : deluxeRoomsData?.rooms?.length ? (
                    <RoomSwiper rooms={deluxeRoomsData.rooms} />
                  ) : (
                    <div className="text-center py-10">
                      <p>Không có phòng Deluxe nào</p>
                    </div>
                  )}
                </div>
              ),
            }, {
              key: "premium",
              label: (
                <span style={{ fontSize: "18px", color: "#059669", fontWeight: "600" }}>Phòng Premium</span>
              ), children: (
                <div
                  className="py-4 px-2"
                  style={{
                    backgroundColor: "rgba(5, 150, 105, 0.08)",
                    borderRadius: "12px",
                  }}
                >
                  {isPremiumLoading ? (
                    <div className="text-center py-10">
                      <Spin size="large" tip="Đang tải phòng..." />
                    </div>
                  ) : premiumRoomsData?.rooms?.length ? (
                    <RoomSwiper rooms={premiumRoomsData.rooms} />
                  ) : (
                    <div className="text-center py-10">
                      <p>Không có phòng Premium nào</p>
                    </div>
                  )}
                </div>
              ),
            }, {
              key: "suite",
              label: (
                <span style={{ fontSize: "18px", color: "#dc2626", fontWeight: "600" }}>Phòng Suite</span>
              ), children: (
                <div
                  className="py-4 px-2"
                  style={{
                    backgroundColor: "rgba(220, 38, 38, 0.08)",
                    borderRadius: "12px",
                  }}
                >
                  {isSuiteLoading ? (
                    <div className="text-center py-10">
                      <Spin size="large" tip="Đang tải phòng..." />
                    </div>
                  ) : suiteRoomsData?.rooms?.length ? (
                    <RoomSwiper rooms={suiteRoomsData.rooms} />
                  ) : (
                    <div className="text-center py-10">
                      <p>Không có phòng Suite nào</p>
                    </div>
                  )}
                </div>
              ),
            }, {
              key: "theLevel",
              label: (
                <span style={{ fontSize: "18px", color: "#0ea5e9", fontWeight: "600" }}>
                  The Level
                </span>
              ),
              children: (<div
                className="py-4 px-2"
                style={{
                  backgroundColor: "rgba(14, 165, 233, 0.08)",
                  borderRadius: "12px",
                }}
              >
                {isTheLevelLoading ? (
                  <div className="text-center py-10">
                    <Spin size="large" tip="Đang tải phòng..." />
                  </div>
                ) : theLevelRoomsData?.rooms?.length ? (
                  <RoomSwiper rooms={theLevelRoomsData.rooms} />
                ) : (
                  <div className="text-center py-10">
                    <p>Không có phòng The Level nào</p>
                  </div>
                )}
              </div>
              ),
            }, {
              key: "presidential",
              label: (
                <span
                  style={{
                    fontSize: "18px",
                    color: "#d97706",
                    fontWeight: "600",
                  }}
                >
                  Phòng Presidential
                </span>
              ),
              children: (<div
                className="py-4 px-2"
                style={{
                  backgroundColor: "rgba(217, 119, 6, 0.08)",
                  borderRadius: "12px",
                }}
              >
                {isPresidentialLoading ? (
                  <div className="text-center py-10">
                    <Spin size="large" tip="Đang tải phòng..." />
                  </div>
                ) : presidentialRoomsData?.rooms?.length ? (
                  <RoomSwiper rooms={presidentialRoomsData.rooms} />
                ) : (
                  <div className="text-center py-10">
                    <p>Không có phòng tổng thống nào</p>
                  </div>
                )}
              </div>
              ),
            },
            ]}
          />
        </div>
        <div className="text-center mt-10">
          <Button type="primary" size="large">
            Xem tất cả phòng
          </Button>
        </div>
      </div>{" "}
      {/* Phần hoạt động của khách sạn */}
      <HotelActivities />     
       
      {/* Hotel Gallery 3D Carousel */}
      <div className="mt-11">
        <div className="container mx-auto ">
          <SectionHeader
            title="Khám phá LavishStay"
            subtitle="Trải nghiệm không gian sang trọng qua từng góc nhìn"
            centered
            withDivider
          />
        </div>
        <SwiftPande />
      </div>

      {/* Testimonials Section */}
      <div className="py-16  ">
        <div className="container mx-auto px-4">
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
