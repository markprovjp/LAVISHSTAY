import { useEffect, useRef } from "react";
import { useLocation } from "react-router-dom";
import { scrollManager } from "./scrollManager";

/**
 * Component tự động cuộn về đầu trang khi chuyển route
 * Sử dụng để giải quyết vấn đề vị trí cuộn không reset khi chuyển trang
 * Chỉ cuộn khi pathname thực sự thay đổi, không cuộn khi chỉ có state changes
 */
const ScrollToTop: React.FC = () => {
    const { pathname } = useLocation();
    const prevPathnameRef = useRef<string>(pathname);
    const isInitialMount = useRef<boolean>(true);

    useEffect(() => {
        // Skip scroll on initial mount
        if (isInitialMount.current) {
            isInitialMount.current = false;
            prevPathnameRef.current = pathname;
            return;
        }

        // Chỉ cuộn khi pathname thực sự thay đổi
        if (prevPathnameRef.current !== pathname) {
            // Use scroll manager to prevent conflicts
            const timeoutId = setTimeout(() => {
                scrollManager.scrollToTop('smooth');
            }, 100);

            // Cập nhật previous pathname
            prevPathnameRef.current = pathname;

            // Cleanup timeout
            return () => clearTimeout(timeoutId);
        }
    }, [pathname]);

    return null; // Component này không render gì cả
};

export default ScrollToTop;
