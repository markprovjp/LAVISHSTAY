
export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    role: 'user' | 'admin' | 'hotel_reception';
    phone?: string;
    address?: string;
    dateOfBirth?: string;
    created_at: string;
    updated_at: string;
}

// Mock users data
export const sampleUsers: User[] = [
    {
        id: 1,
        name: "Nguyễn Văn Quyền",
        email: "user@example.com",
        avatar: "images/users/1.jpg",
        role: "user",
        phone: "0123456789",
        address: "123 Nguyễn Huệ, Q1, TP.HCM",
        dateOfBirth: "1990-01-15",
        created_at: "2024-01-01T00:00:00Z",
        updated_at: "2024-01-01T00:00:00Z"
    },
    {
        id: 2,
        name: "Huỳnh Thị Bích Tuyền",
        email: "reception@hotel.com",
        avatar: "/images/users/2.jpg",
        role: "hotel_reception",
        phone: "0987654321",
        address: "456 Lê Lợi, Q1, TP.HCM",
        dateOfBirth: "1985-05-20",
        created_at: "2024-01-01T00:00:00Z",
        updated_at: "2024-01-01T00:00:00Z"
    },
    {
        id: 3,
        name: "Lê Văn Cường",
        email: "admin@example.com",
        avatar: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face",
        role: "admin",
        phone: "0369852147",
        address: "789 Pasteur, Q3, TP.HCM",
        dateOfBirth: "1992-12-10",
        created_at: "2024-01-01T00:00:00Z",
        updated_at: "2024-01-01T00:00:00Z"
    }
];

// Thông tin đăng nhập để kiểm tra
export const mockCredentials = [
    { email: "user@example.com", password: "123456" },
    { email: "reception@hotel.com", password: "reception123" },
    { email: "admin@example.com", password: "admin123" }
];