/**
 * Bảng giá phòng chuẩn (đã sale) cho ngày bình thường
 */
export const ROOM_PRICING = {
    deluxe: {
        singleGuest: {
            basic: 5000,      // 5K (rẻ nhất và test)
            premium: 1800000     // 1.8M
        },
        doubleGuest: {
            basic: 1300000,      // 1.2M (rẻ nhất)
            standard: 1500000,   // 1.5M
            premium: 2000000,    // 2M
            luxury: 2500000      // 2.5M (đắt nhất)
        }
    },
    premium: {
        singleGuest: {
            basic: 1560000,      // 1.56M
            premium: 1900000     // 1.9M
        },
        doubleGuest: {
            basic: 1400000,      // 1.4M (rẻ nhất)
            standard: 1700000,   // 1.7M
            premium: 2200000,    // 2.2M
            luxury: 2700000      // 2.7M (đắt nhất)
        }
    },
    suite: {
        singleGuest: {
            basic: 2888000,      // 2.888M
            premium: 2888000     // 2.888M (chỉ có 1 loại)
        },
        doubleGuest: {
            basic: 2650000,      // 2.65M (rẻ nhất)
            standard: 3100000,   // 3.1M
            premium: 3500000,    // 3.5M
            luxury: 3950000      // 3.95M (đắt nhất)
        }
    },
    theLevelPremium: {
        singleGuest: {
            basic: 2150000,      // 2.15M
            premium: 2150000     // 2.15M (chỉ có 1 loại)
        },
        doubleGuest: {
            basic: 3200000,      // 3.2M (rẻ nhất)
            standard: 3500000,   // 3.5M
            premium: 4000000,    // 4M
            luxury: 4500000      // 4.5M (đắt nhất)
        }
    },
    theLevelPremiumCorner: {
        singleGuest: {
            basic: 2400000,      // 2.4M
            premium: 2400000     // 2.4M (chỉ có 1 loại)
        },
        doubleGuest: {
            basic: 3400000,      // 3.4M (rẻ nhất)
            standard: 3700000,   // 3.7M
            premium: 4200000,    // 4.2M
            luxury: 4700000      // 4.7M (đắt nhất)
        }
    },
    suiteTheLevel: {
        singleGuest: {
            basic: 3600000,      // 3.6M
            premium: 3600000     // 3.6M (chỉ có 1 loại)
        },
        doubleGuest: {
            basic: 4600000,      // 4.6M (rẻ nhất)
            standard: 5100000,   // 5.1M
            premium: 5600000,    // 5.6M
            luxury: 6100000      // 6.1M (đắt nhất)
        }
    },
    presidential: {
        fourGuest: 47000000     // 47M (đã sale, duy nhất)
    }
};

/**
 * Hệ số điều chỉnh giá theo ngày đặc biệt
 */
export const PRICING_MULTIPLIERS = {
    normal: 1.0,        // Ngày bình thường
    weekend: 1.15,      // Cuối tuần (+15%)
    holiday: 1.3,       // Ngày lễ (+30%)
    peak: 1.5,          // Cao điểm (+50%)
    tet: 2.0           // Tết (+100%)
};

/**
 * Mapping từ roomType string sang pricing key
 */
export const ROOM_TYPE_MAPPING = {
    'deluxe': 'deluxe',
    'premium': 'premium',
    'suite': 'suite',
    'presidential': 'presidential',
    'theLevel': 'theLevelPremium',
    'theLevelPremium': 'theLevelPremium',
    'theLevelPremiumCorner': 'theLevelPremiumCorner',
    'suiteTheLevel': 'suiteTheLevel'
};
