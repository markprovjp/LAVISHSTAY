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
} from '@ant-design/icons';

// Định nghĩa tiện ích với tên và icon function
export interface Amenity {
    name: string;
    icon: () => React.ReactNode;
}

// Danh sách tiện ích cơ bản với tên và icon
export const AMENITIES: Record<string, Amenity> = {
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
    'máy sấy tóc': {
        name: 'Máy sấy tóc',
        icon: () => React.createElement(ThunderboltOutlined)
    },
    'bàn làm việc': {
        name: 'Bàn làm việc',
        icon: () => React.createElement(DesktopOutlined)
    },
    'khăn tắm cao cấp': {
        name: 'Khăn tắm cao cấp',
        icon: () => React.createElement(SkinOutlined)
    },
    'dép đi trong phòng': {
        name: 'Dép đi trong phòng',
        icon: () => React.createElement(RestOutlined)
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

// Export grouped object for backward compatibility
export const AmenityUtils = {
    getAmenity,
    getAllKeys,
    getAll,
    formatAmenitiesForDisplay,
    exists,
    filterValid
};
