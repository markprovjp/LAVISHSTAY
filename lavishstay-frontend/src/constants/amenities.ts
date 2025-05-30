import React from 'react';
import {
    WifiOutlined,
    DesktopOutlined,
    SafetyOutlined,
    CoffeeOutlined,
    HomeOutlined,
    PhoneOutlined,
    ThunderboltOutlined,
    SoundOutlined,
    ShopOutlined,
    RestOutlined,
    EyeOutlined,
    ApartmentOutlined,
    CustomerServiceOutlined,
    BuildOutlined,
    SkinOutlined,
    BgColorsOutlined,
    MedicineBoxOutlined,
    EnvironmentOutlined,
    TeamOutlined,
    SettingOutlined
} from '@ant-design/icons';

// Định nghĩa tiện ích với tên và icon function
export interface Amenity {
    name: string;
    icon: () => React.ReactNode;
}

// Danh sách tiện ích với tên và icon dựa trên dữ liệu từ model
export const AMENITIES: Record<string, Amenity> = {
    // Main Amenities - Tiện ích chính từ model
    'Nhìn ra thành phố': {
        name: 'Nhìn ra thành phố',
        icon: () => React.createElement(ApartmentOutlined)
    },
    'Điều hòa không khí': {
        name: 'Điều hòa không khí',
        icon: () => React.createElement(BuildOutlined)
    },
    'Phòng tắm riêng trong phòng': {
        name: 'Phòng tắm riêng trong phòng',
        icon: () => React.createElement(RestOutlined)
    },
    'TV màn hình phẳng': {
        name: 'TV màn hình phẳng',
        icon: () => React.createElement(DesktopOutlined)
    },
    'Hệ thống cách âm': {
        name: 'Hệ thống cách âm',
        icon: () => React.createElement(SoundOutlined)
    },
    'Minibar': {
        name: 'Minibar',
        icon: () => React.createElement(ShopOutlined)
    },
    'WiFi miễn phí': {
        name: 'WiFi miễn phí',
        icon: () => React.createElement(WifiOutlined)
    },
    'Suite riêng tư': {
        name: 'Suite riêng tư',
        icon: () => React.createElement(HomeOutlined)
    },
    'Máy pha cà phê': {
        name: 'Máy pha cà phê',
        icon: () => React.createElement(CoffeeOutlined)
    },
    'Có cung cấp nôi/cũi theo yêu cầu': {
        name: 'Có cung cấp nôi/cũi theo yêu cầu',
        icon: () => React.createElement(TeamOutlined)
    },

    // Standard Amenities - Tiện ích phòng tiêu chuẩn từ model
    'Đồ vệ sinh cá nhân miễn phí': {
        name: 'Đồ vệ sinh cá nhân miễn phí',
        icon: () => React.createElement(MedicineBoxOutlined)
    },
    'Áo choàng tắm': {
        name: 'Áo choàng tắm',
        icon: () => React.createElement(SkinOutlined)
    },
    'Két an toàn': {
        name: 'Két an toàn',
        icon: () => React.createElement(SafetyOutlined)
    },
    'Nhà vệ sinh': {
        name: 'Nhà vệ sinh',
        icon: () => React.createElement(RestOutlined)
    },
    'Bồn tắm hoặc Vòi sen': {
        name: 'Bồn tắm hoặc Vòi sen',
        icon: () => React.createElement(SkinOutlined)
    },
    'Khăn tắm': {
        name: 'Khăn tắm',
        icon: () => React.createElement(SkinOutlined)
    },
    'Ổ điện gần giường': {
        name: 'Ổ điện gần giường',
        icon: () => React.createElement(ThunderboltOutlined)
    },
    'Bàn làm việc': {
        name: 'Bàn làm việc',
        icon: () => React.createElement(DesktopOutlined)
    },
    'Khu vực tiếp khách': {
        name: 'Khu vực tiếp khách',
        icon: () => React.createElement(HomeOutlined)
    },
    'TV': {
        name: 'TV',
        icon: () => React.createElement(DesktopOutlined)
    },
    'Dép': {
        name: 'Dép',
        icon: () => React.createElement(RestOutlined)
    },
    'Tủ lạnh': {
        name: 'Tủ lạnh',
        icon: () => React.createElement(ShopOutlined)
    },
    'Điện thoại': {
        name: 'Điện thoại',
        icon: () => React.createElement(PhoneOutlined)
    },
    'Máy sấy tóc': {
        name: 'Máy sấy tóc',
        icon: () => React.createElement(ThunderboltOutlined)
    },
    'Sàn trải thảm': {
        name: 'Sàn trải thảm',
        icon: () => React.createElement(HomeOutlined)
    },
    'Ấm đun nước điện': {
        name: 'Ấm đun nước điện',
        icon: () => React.createElement(CoffeeOutlined)
    },
    'Tủ hoặc phòng để quần áo': {
        name: 'Tủ hoặc phòng để quần áo',
        icon: () => React.createElement(HomeOutlined)
    },
    'Giấy vệ sinh': {
        name: 'Giấy vệ sinh',
        icon: () => React.createElement(MedicineBoxOutlined)
    },

    // View & Location Features
    'Tầm nhìn cảnh biển': {
        name: 'Tầm nhìn cảnh biển',
        icon: () => React.createElement(EyeOutlined)
    },
    'Tầm nhìn toàn cảnh biển': {
        name: 'Tầm nhìn toàn cảnh biển',
        icon: () => React.createElement(EyeOutlined)
    },
    'Hướng ngoài trời': {
        name: 'Hướng ngoài trời',
        icon: () => React.createElement(EnvironmentOutlined)
    },

    // Additional Amenities
    'Truyền hình cáp/vệ tinh': {
        name: 'Truyền hình cáp/vệ tinh',
        icon: () => React.createElement(DesktopOutlined)
    },
    'máy pha trà/cà phê': {
        name: 'Máy pha trà/cà phê',
        icon: () => React.createElement(CoffeeOutlined)
    },
    'tủ lạnh nhỏ trong phòng': {
        name: 'Tủ lạnh nhỏ trong phòng',
        icon: () => React.createElement(ShopOutlined)
    },
    'khăn tắm cao cấp': {
        name: 'Khăn tắm cao cấp',
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
    'nước đóng chai miễn phí': {
        name: 'Nước đóng chai miễn phí',
        icon: () => React.createElement(CoffeeOutlined)
    },
    'dép đi trong nhà': {
        name: 'Dép đi trong nhà',
        icon: () => React.createElement(RestOutlined)
    },
    'Không hút thuốc': {
        name: 'Không hút thuốc',
        icon: () => React.createElement(EnvironmentOutlined)
    },
    'Phòng tắm đứng & bồn tắm': {
        name: 'Phòng tắm đứng & bồn tắm',
        icon: () => React.createElement(SkinOutlined)
    },
    'phòng khách riêng': {
        name: 'Phòng khách riêng',
        icon: () => React.createElement(HomeOutlined)
    },
    'view toàn cảnh biển': {
        name: 'View toàn cảnh biển',
        icon: () => React.createElement(EyeOutlined)
    },
    'minibar cao cấp': {
        name: 'Minibar cao cấp',
        icon: () => React.createElement(ShopOutlined)
    },
    'két sắt': {
        name: 'Két sắt',
        icon: () => React.createElement(SafetyOutlined)
    },
    'dịch vụ phòng 24/7': {
        name: 'Dịch vụ phòng 24/7',
        icon: () => React.createElement(CustomerServiceOutlined)
    },
    'Phòng chờ thương gia': {
        name: 'Phòng chờ thương gia',
        icon: () => React.createElement(TeamOutlined)
    },
    'Bể bơi riêng': {
        name: 'Bể bơi riêng',
        icon: () => React.createElement(BgColorsOutlined)
    },
    'Bồn tắm/vòi sen riêng': {
        name: 'Bồn tắm/vòi sen riêng',
        icon: () => React.createElement(SkinOutlined)
    },
    'Phòng chờ Thương Gia': {
        name: 'Phòng chờ Thương Gia',
        icon: () => React.createElement(TeamOutlined)
    },
    '2 phòng tắm': {
        name: '2 phòng tắm',
        icon: () => React.createElement(RestOutlined)
    },
    'Phòng tắm vòi sen & bồn tắm': {
        name: 'Phòng tắm vòi sen & bồn tắm',
        icon: () => React.createElement(SkinOutlined)
    },
    '2 phòng ngủ': {
        name: '2 phòng ngủ',
        icon: () => React.createElement(HomeOutlined)
    },

    // Legacy & Alternative Mappings - Để tương thích ngược
    'wifi miễn phí': {
        name: 'WiFi miễn phí',
        icon: () => React.createElement(WifiOutlined)
    },
    'tv màn hình phẳng': {
        name: 'TV màn hình phẳng',
        icon: () => React.createElement(DesktopOutlined)
    },
    'điều hòa không khí': {
        name: 'Điều hòa không khí',
        icon: () => React.createElement(BuildOutlined)
    },
    'minibar': {
        name: 'Minibar',
        icon: () => React.createElement(ShopOutlined)
    },
    'phòng tắm riêng trong phòng': {
        name: 'Phòng tắm riêng trong phòng',
        icon: () => React.createElement(RestOutlined)
    },
    'hệ thống cách âm': {
        name: 'Hệ thống cách âm',
        icon: () => React.createElement(SoundOutlined)
    },
    'suite riêng tư': {
        name: 'Suite riêng tư',
        icon: () => React.createElement(HomeOutlined)
    },
    'máy pha cà phê': {
        name: 'Máy pha cà phê',
        icon: () => React.createElement(CoffeeOutlined)
    }
};// Helper functions để làm việc với amenities
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
        'Điều hòa không khí',
        'WiFi miễn phí',
        'TV màn hình phẳng',
        'Phòng tắm riêng trong phòng',
        'Minibar'
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
