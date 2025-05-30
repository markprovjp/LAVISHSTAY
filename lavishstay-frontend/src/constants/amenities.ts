import React from 'react';
import {
    WifiOutlined,
    DesktopOutlined,
    BuildOutlined,
    ShopOutlined,
    SafetyOutlined,
    CoffeeOutlined,
    HomeOutlined,
    BankOutlined,
    EnvironmentOutlined,
    CustomerServiceOutlined,
    UserOutlined,
    SettingOutlined,
    EyeOutlined,
    ApartmentOutlined,
    RestOutlined,
    CarOutlined,
    SkinOutlined,
    ShoppingOutlined,
    CarryOutOutlined,
    BgColorsOutlined,
    ToolOutlined,
    ThunderboltOutlined,
    PhoneOutlined,
    ClockCircleOutlined,
    BellOutlined,
    SoundOutlined,
    StopOutlined,
    TeamOutlined,
    MedicineBoxOutlined
} from '@ant-design/icons';

// Định nghĩa tiện ích với tên và icon function
export interface Amenity {
    name: string;
    icon: () => React.ReactNode;
}

// Danh sách tiện ích cơ bản với tên và icon
export const AMENITIES: Record<string, Amenity> = {
    // Tiện ích chính (hiển thị ở home)
    'hướng thành phố': {
        name: 'Hướng Thành phố',
        icon: () => React.createElement(ApartmentOutlined)
    },
    'không hút thuốc': {
        name: 'Không hút thuốc',
        icon: () => React.createElement(StopOutlined)
    },
    'bồn tắm/vòi sen riêng': {
        name: 'Bồn tắm/vòi sen riêng',
        icon: () => React.createElement(SkinOutlined)
    },

    // Khả năng tiếp cận
    'khả năng tiếp cận cho người khuyết tật': {
        name: 'Khả năng tiếp cận cho người khuyết tật',
        icon: () => React.createElement(TeamOutlined)
    },
    'bàn trang điểm phù hợp cho người khuyết tật': {
        name: 'Bàn trang điểm phù hợp cho người khuyết tật',
        icon: () => React.createElement(TeamOutlined)
    },
    'khả năng tiếp cận cho người khó đi lại': {
        name: 'Khả năng tiếp cận cho người khó đi lại',
        icon: () => React.createElement(TeamOutlined)
    },

    // Giải trí
    'điện thoại': {
        name: 'Điện thoại',
        icon: () => React.createElement(PhoneOutlined)
    },
    'wifi miễn phí': {
        name: 'Wi-Fi miễn phí trong tất cả các phòng!',
        icon: () => React.createElement(WifiOutlined)
    },
    'truyền hình cáp/vệ tinh': {
        name: 'Truyền hình cáp/vệ tinh',
        icon: () => React.createElement(DesktopOutlined)
    },

    // Bếp
    'ấm nước điện': {
        name: 'Ấm nước điện',
        icon: () => React.createElement(CoffeeOutlined)
    },

    // Phòng tắm và vật dụng vệ sinh
    'áo choàng tắm': {
        name: 'Áo choàng tắm',
        icon: () => React.createElement(SkinOutlined)
    },
    'cân': {
        name: 'Cân',
        icon: () => React.createElement(ToolOutlined)
    },
    'máy sấy tóc': {
        name: 'Máy sấy tóc',
        icon: () => React.createElement(ThunderboltOutlined)
    },
    'phòng tắm riêng': {
        name: 'Phòng tắm riêng',
        icon: () => React.createElement(RestOutlined)
    },
    'các loại khăn': {
        name: 'Các loại khăn',
        icon: () => React.createElement(SkinOutlined)
    },
    'gương': {
        name: 'Gương',
        icon: () => React.createElement(EyeOutlined)
    },
    'phòng tắm đứng': {
        name: 'Phòng tắm đứng',
        icon: () => React.createElement(RestOutlined)
    },
    'vật dụng tắm rửa': {
        name: 'Vật dụng tắm rửa',
        icon: () => React.createElement(MedicineBoxOutlined)
    },

    // Tiện nghi
    'cách âm': {
        name: 'Cách âm',
        icon: () => React.createElement(SoundOutlined)
    },
    'dép đi trong nhà': {
        name: 'Dép đi trong nhà',
        icon: () => React.createElement(RestOutlined)
    },
    'dịch vụ báo thức': {
        name: 'Dịch vụ báo thức',
        icon: () => React.createElement(BellOutlined)
    },
    'điều hòa': {
        name: 'Điều hòa',
        icon: () => React.createElement(BuildOutlined)
    },
    'đồ dùng cho giấc ngủ thoải mái': {
        name: 'Đồ dùng cho giấc ngủ thoải mái',
        icon: () => React.createElement(RestOutlined)
    },
    'đồng hồ báo thức': {
        name: 'Đồng hồ báo thức',
        icon: () => React.createElement(ClockCircleOutlined)
    },
    'ô cắm điện gần giường': {
        name: 'Ô cắm điện gần giường',
        icon: () => React.createElement(ThunderboltOutlined)
    },
    'rèm che ánh sáng': {
        name: 'Rèm che ánh sáng',
        icon: () => React.createElement(HomeOutlined)
    },

    // Ăn uống
    'máy pha trà/cà phê': {
        name: 'Máy pha trà/cà phê',
        icon: () => React.createElement(CoffeeOutlined)
    },
    'nước đóng chai miễn phí': {
        name: 'Nước đóng chai miễn phí',
        icon: () => React.createElement(CoffeeOutlined)
    },
    'tủ lạnh': {
        name: 'Tủ lạnh',
        icon: () => React.createElement(ShopOutlined)
    },
    'tủ lạnh nhỏ trong phòng': {
        name: 'Tủ lạnh nhỏ trong phòng',
        icon: () => React.createElement(ShopOutlined)
    },

    // Dịch vụ và tiện nghi
    'dọn phòng hằng ngày': {
        name: 'Dọn phòng hằng ngày',
        icon: () => React.createElement(CustomerServiceOutlined)
    },

    // Legacy amenities - giữ lại để tương thích ngược
    wifi: {
        name: 'Wi-Fi miễn phí',
        icon: () => React.createElement(WifiOutlined)
    },
    tv: {
        name: 'TV màn hình phẳng',
        icon: () => React.createElement(DesktopOutlined)
    },
    'điều hoà': {
        name: 'Điều hoà không khí',
        icon: () => React.createElement(BuildOutlined)
    },
    minibar: {
        name: 'Minibar',
        icon: () => React.createElement(ShopOutlined)
    },
    'minibar cao cấp': {
        name: 'Minibar cao cấp',
        icon: () => React.createElement(ShoppingOutlined)
    },
    'két sắt': {
        name: 'Két sắt',
        icon: () => React.createElement(SafetyOutlined)
    },
    'két sắt lớn': {
        name: 'Két sắt lớn',
        icon: () => React.createElement(BankOutlined)
    },
    'máy pha cà phê': {
        name: 'Máy pha cà phê',
        icon: () => React.createElement(CoffeeOutlined)
    },
    'máy pha cà phê espresso': {
        name: 'Máy pha espresso',
        icon: () => React.createElement(CoffeeOutlined)
    },
    'bàn làm việc': {
        name: 'Bàn làm việc',
        icon: () => React.createElement(DesktopOutlined)
    },
    'khăn tắm cao cấp': {
        name: 'Khăn tắm cao cấp',
        icon: () => React.createElement(SkinOutlined)
    },
    'nước uống miễn phí': {
        name: 'Nước uống miễn phí',
        icon: () => React.createElement(CoffeeOutlined)
    },
    'dịch vụ phòng 24/7': {
        name: 'Dịch vụ phòng 24/7',
        icon: () => React.createElement(CustomerServiceOutlined)
    },
    'dịch vụ phòng VIP': {
        name: 'Dịch vụ phòng VIP',
        icon: () => React.createElement(CustomerServiceOutlined)
    },
    'quản gia 24/7': {
        name: 'Quản gia riêng 24/7',
        icon: () => React.createElement(UserOutlined)
    },
    'dịch vụ quản gia': {
        name: 'Dịch vụ quản gia',
        icon: () => React.createElement(SettingOutlined)
    },
    'lounge riêng': {
        name: 'Lounge riêng',
        icon: () => React.createElement(RestOutlined)
    },
    'view biển': {
        name: 'View biển',
        icon: () => React.createElement(EyeOutlined)
    },
    'view thành phố': {
        name: 'View thành phố',
        icon: () => React.createElement(ApartmentOutlined)
    },
    'view toàn cảnh biển': {
        name: 'View toàn cảnh biển',
        icon: () => React.createElement(EyeOutlined)
    },
    'view panorama': {
        name: 'View panorama',
        icon: () => React.createElement(EyeOutlined)
    },
    cityView: {
        name: 'City View',
        icon: () => React.createElement(ApartmentOutlined)
    },
    'ban công riêng': {
        name: 'Ban công riêng',
        icon: () => React.createElement(HomeOutlined)
    },
    'ban công panorama': {
        name: 'Ban công panorama',
        icon: () => React.createElement(HomeOutlined)
    },
    'phòng khách riêng': {
        name: 'Phòng khách riêng',
        icon: () => React.createElement(HomeOutlined)
    },
    'phòng ăn riêng': {
        name: 'Phòng ăn riêng',
        icon: () => React.createElement(CarryOutOutlined)
    },
    'bể bơi': {
        name: 'Bể bơi',
        icon: () => React.createElement(BgColorsOutlined)
    },
    'phòng gym': {
        name: 'Phòng tập gym',
        icon: () => React.createElement(ToolOutlined)
    },
    'spa': {
        name: 'Spa & Massage',
        icon: () => React.createElement(SkinOutlined)
    },
    'nhà hàng': {
        name: 'Nhà hàng',
        icon: () => React.createElement(CarryOutOutlined)
    },
    'bar': {
        name: 'Bar',
        icon: () => React.createElement(CoffeeOutlined)
    },
    'đỗ xe': {
        name: 'Chỗ đỗ xe',
        icon: () => React.createElement(CarOutlined)
    },
    'ban công': {
        name: 'Ban công',
        icon: () => React.createElement(HomeOutlined)
    },
    'view núi': {
        name: 'View núi',
        icon: () => React.createElement(EnvironmentOutlined)
    },
    'bồn tắm': {
        name: 'Bồn tắm riêng',
        icon: () => React.createElement(SkinOutlined)
    },
    'máy giặt': {
        name: 'Máy giặt',
        icon: () => React.createElement(ToolOutlined)
    },
    'bếp': {
        name: 'Bếp nấu ăn',
        icon: () => React.createElement(CarryOutOutlined)
    },
};

// Helper functions đơn giản để làm việc với amenities
export const getAmenity = (key: string): Amenity | null => {
    return AMENITIES[key] || null;
};

export const getAllKeys = (): string[] => {
    return Object.keys(AMENITIES);
};

export const getAll = (): Record<string, Amenity> => {
    return AMENITIES;
};

export const formatAmenitiesForDisplay = (amenityKeys: string[]): Array<{
    key: string;
    name: string;
    icon: React.ReactNode;
}> => {
    return amenityKeys.map(key => {
        const amenity = AMENITIES[key];
        if (amenity) {
            return {
                key,
                name: amenity.name,
                icon: amenity.icon()
            };
        }
        // Fallback cho amenity không tồn tại
        return {
            key,
            name: key,
            icon: React.createElement(SettingOutlined)
        };
    });
};

export const exists = (key: string): boolean => {
    return key in AMENITIES;
};

export const filterValid = (amenityKeys: string[]): string[] => {
    return amenityKeys.filter(key => AMENITIES[key]);
};

// Danh sách tiện ích chính để hiển thị ở home (4-5 tiện ích)
export const getMainAmenities = (): string[] => {
    return [
        'hướng thành phố',
        'không hút thuốc', 
        'bồn tắm/vòi sen riêng',
        'wifi miễn phí',
        'điều hòa'
    ];
};

// Export grouped object for backward compatibility
export const AmenityUtils = {
    getAmenity,
    getAllKeys,
    getAll,
    formatAmenitiesForDisplay,
    exists,
    filterValid,
    getMainAmenities
};
