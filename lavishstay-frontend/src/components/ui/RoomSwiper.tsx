import React from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation, Pagination, Autoplay } from "swiper/modules";
import RoomCard, { RoomProps } from "./RoomCard";

// Import Swiper styles
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";

interface RoomSwiperProps {
  rooms: RoomProps[];
  className?: string;
}

const RoomSwiper: React.FC<RoomSwiperProps> = ({ rooms, className = "" }) => {
  return (
    <div className={`room-swiper-container ${className}`}>
      <Swiper
        modules={[Navigation, Pagination, Autoplay]}
        spaceBetween={24}
        slidesPerView={1}
        pagination={{ clickable: true }}
        autoplay={{ delay: 5000, disableOnInteraction: false }}
        breakpoints={{
          640: {
            slidesPerView: 1,
          },
          768: {
            slidesPerView: 2,
          },
          1024: {
            slidesPerView: 3,
          },
        }}
        loop={true}
        className="p-2 pb-12"
      >
        {" "}
        {rooms.map((room) => (
          <SwiperSlide key={room.id}>
            <RoomCard {...room} />
          </SwiperSlide>
        ))}
      </Swiper>
    </div>
  );
};

export default RoomSwiper;
