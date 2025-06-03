import { useEffect } from "react";
import { useLocation } from "react-router-dom";

/**
 * Component tự động cuộn về đầu trang khi chuyển route
 * Sử dụng để giải quyết vấn đề vị trí cuộn không reset khi chuyển trang
 */
const ScrollToTop: React.FC = () => {
    const { pathname } = useLocation();

    useEffect(() => {
        // Cuộn về đầu trang mỗi khi pathname thay đổi
        window.scrollTo({
            top: 0,
            left: 0,
            behavior: "instant", // Cuộn ngay lập tức, không có animation
        });
    }, [pathname]);

    return null; // Component này không render gì cả
};

export default ScrollToTop;
