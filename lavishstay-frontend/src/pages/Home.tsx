import React from "react";
import { Spin } from "antd";
import { useTranslation } from "react-i18next";

// Import custom components
import HeroBanner from "../components/ui/HeroBanner";
import SearchForm from "../components/SearchForm";
import SectionHeader from "../components/SectionHeader";
import Testimonial from "../components/Testimonial";
import Newsletter from "../components/Newsletter";
import RoomTypeCard from "../components/ui/RoomTypeCard";
import Awards from "../components/ui/Awards";
import HotelActivities from "../components/ui/HotelActivities";

// Import API hooks for backend integration
import { useGetRoomTypes } from "../hooks/useRoomTypes";

const Home: React.FC = () => {
  const { t } = useTranslation();

  // Fetch room types for display using backend API
  const { data: roomTypesData, isLoading: isRoomTypesLoading } = useGetRoomTypes();

  // Process rooms with discounts for the sale section (commented out for now)
  // const saleRooms = useMemo(() => {
  //   if (allRoomsData?.data) {
  //     return allRoomsData.data
  //       .filter((room: Room) => room.discount && room.discount > 0)
  //       .map((room: Room) => ({ ...room, isSale: true }));
  //   }
  //   return [];
  // }, [allRoomsData]);

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
      <Awards />      {/* Room Types Section */}
      <div className="container mx-auto px-4 mb-16">
        <SectionHeader
          title="Các loại phòng của chúng tôi"
          subtitle="Khám phá đa dạng các loại phòng sang trọng tại LavishStay"
          centered
          withDivider
        />
        <div className="mt-8">
          {isRoomTypesLoading ? (
            <div className="text-center py-10">
              <Spin size="large" tip="Đang tải loại phòng..." />
            </div>
          ) : roomTypesData?.data?.length ? (<div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {roomTypesData.data.map((roomType: any) => (
              <RoomTypeCard
                key={roomType.id}
                id={roomType.id}
                name={roomType.name}
                base_price={roomType.base_price}
                avg_price={roomType.avg_price}
                lavish_plus_discount={roomType.lavish_plus_discount}
                room_code={roomType.room_code}
                size={roomType.size}
                avg_size={roomType.avg_size}
                view={roomType.view}
                common_views={roomType.common_views}
                maxGuests={roomType.max_guests}
                amenities={roomType.amenities || []}
                highlighted_amenities={roomType.highlighted_amenities || []}
                mainAmenities={roomType.main_amenities || []}
                                                  main_image={roomType.main_image}
                rating={roomType.rating}
                avg_rating={roomType.avg_rating}
                rooms_count={roomType.rooms_count}
                available_rooms_count={roomType.available_rooms_count}
              />
            ))}
          </div>
          ) : (
            <div className="text-center py-10">
              <p>Không có loại phòng nào để hiển thị</p>
            </div>
          )}
        </div>
      </div>

      {/* Sale Rooms Section - Commented out */}
      {/* <div className="container mx-auto px-4 mb-16">
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
        </div>      {/* Phần hoạt động của khách sạn */}
      <HotelActivities />

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



