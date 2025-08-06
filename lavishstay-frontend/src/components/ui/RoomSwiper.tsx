import React, { useMemo } from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation, Autoplay } from "swiper/modules";
import RoomTypeCard, { RoomTypeProps } from "./RoomTypeCard";

// Import Swiper styles
import "swiper/css";
import "swiper/css/navigation";

interface RoomSwiperProps {
  roomTypes: RoomTypeProps[];
  className?: string;
}

const RoomSwiper: React.FC<RoomSwiperProps> = React.memo(({ roomTypes, className = "" }) => {
  // Memoize swiper configuration
  const swiperConfig = useMemo(() => ({
    modules: [Navigation, Autoplay],
    spaceBetween: 24,
    slidesPerView: 1,
    autoplay: { delay: 5000, disableOnInteraction: false },
    breakpoints: {
      640: {
        slidesPerView: 1,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
    },
    loop: true,
    className: "p-2 pb-4"
  }), []);

  return (
    <div className={`room-swiper-container ${className}`}>
      <Swiper {...swiperConfig}>
        {roomTypes.map((room) => (
          <SwiperSlide key={room.id}>
            <RoomTypeCard {...room} />
          </SwiperSlide>
        ))}
      </Swiper>
    </div>
  );
});

RoomSwiper.displayName = 'RoomSwiper';

export default RoomSwiper;
