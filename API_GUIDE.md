# Hướng Dẫn Tích Hợp API - Backend Laravel và Frontend React

Tài liệu này cung cấp hướng dẫn chi tiết về cách triển khai API trong Laravel (Backend) và cách kết nối với những API này từ React (Frontend) bằng Axios.

## Công Nghệ Sử Dụng

### Backend
- **Framework**: Laravel 12.x
- **Database**: MySQL 
- **Authentication**: Laravel Sanctum (Token-based Authentication)

### Frontend
- **Framework**: React 18
- **Language**: TypeScript
- **UI Library**: Ant Design
- **State Management**: Redux Toolkit, Zustand
- **API Client**: Axios
- **Data Fetching**: React Query

## I. PHẦN BACKEND: TẠO VÀ TRIỂN KHAI API TRONG LARAVEL

### 1. Cài Đặt Laravel Sanctum

Laravel Sanctum cung cấp hệ thống xác thực nhẹ để API, SPA và mobile applications.

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### 2. Cấu Hình Cors (config/cors.php)

Để Frontend có thể gọi API từ domain khác:

```php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:5173'], // Frontend URL
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

### 3. Tạo Route API (routes/api.php)

Tạo file `routes/api.php` và định nghĩa các routes cho API:

```php
<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PropertyController;
use App\Http\Controllers\API\BookingController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/profile', [AuthController::class, 'profile']);
    
    // Properties
    Route::get('/properties', [PropertyController::class, 'index']);
    Route::get('/properties/featured', [PropertyController::class, 'featured']);
    Route::get('/properties/{id}', [PropertyController::class, 'show']);
    Route::get('/properties/search', [PropertyController::class, 'search']);
    
    // Bookings
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/user', [BookingController::class, 'userBookings']);
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel']);
});
```

### 4. Tạo Models

#### Property Model

```bash
php artisan make:model Property -m
```

File: `app/Models/Property.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'address',
        'city',
        'price_per_night',
        'bedrooms',
        'bathrooms',
        'max_guests',
        'image_url',
        'is_featured',
        'rating',
    ];
    
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
```

File: `database/migrations/xxxx_xx_xx_create_properties_table.php`
```php
public function up()
{
    Schema::create('properties', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description');
        $table->string('address');
        $table->string('city');
        $table->decimal('price_per_night', 10, 2);
        $table->integer('bedrooms');
        $table->integer('bathrooms');
        $table->integer('max_guests');
        $table->string('image_url')->nullable();
        $table->boolean('is_featured')->default(false);
        $table->decimal('rating', 3, 1)->default(0);
        $table->timestamps();
    });
}
```

#### Booking Model

```bash
php artisan make:model Booking -m
```

File: `app/Models/Booking.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'property_id',
        'check_in_date',
        'check_out_date',
        'total_price',
        'guests',
        'status',
    ];
    
    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
```

File: `database/migrations/xxxx_xx_xx_create_bookings_table.php`
```php
public function up()
{
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('property_id')->constrained()->onDelete('cascade');
        $table->date('check_in_date');
        $table->date('check_out_date');
        $table->decimal('total_price', 10, 2);
        $table->integer('guests');
        $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
        $table->timestamps();
    });
}
```

### 5. Tạo Controllers

#### AuthController

```bash
php artisan make:controller API/AuthController
```

File: `app/Http/Controllers/API/AuthController.php`
```php
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Thông tin đăng nhập không chính xác.'],
            ]);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Đăng xuất thành công.']);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }
}
```

#### PropertyController

```bash
php artisan make:controller API/PropertyController
```

File: `app/Http/Controllers/API/PropertyController.php`
```php
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $properties = Property::query();
        
        // Áp dụng các bộ lọc từ request
        if ($request->has('city')) {
            $properties->where('city', 'like', '%' . $request->city . '%');
        }
        
        if ($request->has('min_price')) {
            $properties->where('price_per_night', '>=', $request->min_price);
        }
        
        if ($request->has('max_price')) {
            $properties->where('price_per_night', '<=', $request->max_price);
        }
        
        if ($request->has('bedrooms')) {
            $properties->where('bedrooms', '>=', $request->bedrooms);
        }
        
        // Phân trang kết quả
        $perPage = $request->get('per_page', 10);
        
        return response()->json($properties->paginate($perPage));
    }
    
    public function featured()
    {
        $properties = Property::where('is_featured', true)
            ->orderBy('rating', 'desc')
            ->take(6)
            ->get();
            
        return response()->json($properties);
    }
    
    public function show($id)
    {
        $property = Property::findOrFail($id);
        
        return response()->json($property);
    }
    
    public function search(Request $request)
    {
        $query = Property::query();
        
        // Tìm kiếm theo từ khóa
        if ($request->has('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('city', 'like', "%{$keyword}%")
                  ->orWhere('address', 'like', "%{$keyword}%");
            });
        }
        
        // Các bộ lọc khác giống như trong hàm index
        
        // Sắp xếp
        if ($request->has('sort_by')) {
            $sortField = $request->sort_by;
            $sortDirection = $request->has('sort_direction') ? $request->sort_direction : 'asc';
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $perPage = $request->get('per_page', 10);
        
        return response()->json($query->paginate($perPage));
    }
}
```

#### BookingController

```bash
php artisan make:controller API/BookingController
```

File: `app/Http/Controllers/API/BookingController.php`
```php
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guests' => 'required|integer|min:1',
        ]);
        
        $property = Property::findOrFail($request->property_id);
        
        // Kiểm tra xem có đặt phòng nào bị trùng thời gian không
        $overlappingBookings = Booking::where('property_id', $property->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                $query->whereBetween('check_in_date', [$request->check_in_date, $request->check_out_date])
                    ->orWhereBetween('check_out_date', [$request->check_in_date, $request->check_out_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('check_in_date', '<=', $request->check_in_date)
                            ->where('check_out_date', '>=', $request->check_out_date);
                    });
            })
            ->exists();
            
        if ($overlappingBookings) {
            return response()->json([
                'message' => 'Phòng đã được đặt trong khoảng thời gian này.'
            ], 422);
        }
        
        // Tính tổng số ngày
        $checkIn = new \DateTime($request->check_in_date);
        $checkOut = new \DateTime($request->check_out_date);
        $nights = $checkIn->diff($checkOut)->days;
        
        // Tính tổng giá
        $totalPrice = $property->price_per_night * $nights;
        
        // Tạo đặt phòng mới
        $booking = Booking::create([
            'user_id' => $request->user()->id,
            'property_id' => $property->id,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'total_price' => $totalPrice,
            'guests' => $request->guests,
            'status' => 'confirmed',
        ]);
        
        return response()->json([
            'message' => 'Đặt phòng thành công!',
            'booking' => $booking
        ], 201);
    }
    
    public function userBookings(Request $request)
    {
        $bookings = Booking::with('property')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($bookings);
    }
    
    public function show($id)
    {
        $booking = Booking::with('property')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
            
        return response()->json($booking);
    }
    
    public function cancel($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
            
        // Kiểm tra xem đã quá thời gian hủy chưa
        $checkInDate = new \DateTime($booking->check_in_date);
        $now = new \DateTime();
        
        if ($now >= $checkInDate) {
            return response()->json([
                'message' => 'Không thể hủy đặt phòng sau ngày check-in.'
            ], 422);
        }
        
        $booking->status = 'cancelled';
        $booking->save();
        
        return response()->json([
            'message' => 'Hủy đặt phòng thành công!',
            'booking' => $booking
        ]);
    }
}
```

### 6. Seeder để Tạo Dữ Liệu Mẫu

```bash
php artisan make:seeder PropertySeeder
```

File: `database/seeders/PropertySeeder.php`
```php
<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run()
    {
        $properties = [
            [
                'name' => 'Lavish Suite - Diamond Bay',
                'description' => 'Phòng suite sang trọng với view biển tuyệt đẹp.',
                'address' => '12 Trần Phú',
                'city' => 'Nha Trang',
                'price_per_night' => 2500000,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'max_guests' => 4,
                'image_url' => 'https://example.com/images/hotel1.jpg',
                'is_featured' => true,
                'rating' => 4.8,
            ],
            // Thêm các mẫu khác
        ];
        
        foreach ($properties as $property) {
            Property::create($property);
        }
    }
}
```

Cập nhật file `database/seeders/DatabaseSeeder.php`:
```php
public function run()
{
    $this->call([
        PropertySeeder::class,
    ]);
}
```

Chạy seeder:
```bash
php artisan db:seed
```

## II. PHẦN FRONTEND: KẾT NỐI VỚI API TỪ REACT

### 1. Cấu Hình Axios

#### Cấu hình cơ bản

File: `src/config/axios.ts`
```typescript
import axios from 'axios';
import env from './env';

// Tạo một instance axios với cấu hình mặc định
const axiosInstance = axios.create({
  baseURL: env.API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  timeout: 10000, // 10 giây
});

// Interceptor cho request
axiosInstance.interceptors.request.use(
  (config) => {
    // Lấy token từ localStorage
    const token = localStorage.getItem('authToken');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Interceptor cho response
axiosInstance.interceptors.response.use(
  (response) => {
    return response;
  },
  (error) => {
    // Xử lý lỗi toàn cục (đăng nhập lại, thông báo lỗi, ...)
    if (error.response?.status === 401) {
      // Chưa đăng nhập hoặc hết hạn token
      localStorage.removeItem('authToken');
      // Có thể điều hướng đến trang đăng nhập ở đây
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default axiosInstance;
```

#### Biến môi trường

File: `.env.local`
```
VITE_API_URL=http://localhost:8000/api
```

### 2. Tạo API Services

#### Authentication Service

File: `src/services/authService.ts`
```typescript
import axiosInstance from '../config/axios';

// Interface cho dữ liệu đăng nhập
export interface LoginCredentials {
  email: string;
  password: string;
}

// Interface cho dữ liệu đăng ký
export interface RegisterData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

// Interface cho user
export interface User {
  id: number;
  name: string;
  email: string;
  created_at: string;
  updated_at: string;
}

// Interface cho response đăng nhập
export interface AuthResponse {
  user: User;
  access_token: string;
  token_type: string;
}

const authService = {
  // Đăng nhập
  login: async (credentials: LoginCredentials): Promise<AuthResponse> => {
    const response = await axiosInstance.post('/auth/login', credentials);
    return response.data;
  },

  // Đăng ký
  register: async (data: RegisterData): Promise<AuthResponse> => {
    const response = await axiosInstance.post('/auth/register', data);
    return response.data;
  },

  // Đăng xuất
  logout: async (): Promise<void> => {
    await axiosInstance.post('/auth/logout');
    localStorage.removeItem('authToken');
  },

  // Lấy thông tin người dùng
  getCurrentUser: async (): Promise<User> => {
    const response = await axiosInstance.get('/auth/profile');
    return response.data;
  },

  // Kiểm tra đã đăng nhập chưa
  isAuthenticated: (): boolean => {
    return !!localStorage.getItem('authToken');
  },

  // Lưu token vào localStorage
  setToken: (token: string): void => {
    localStorage.setItem('authToken', token);
  },

  // Lấy token
  getToken: (): string | null => {
    return localStorage.getItem('authToken');
  },
};

export default authService;
```

#### Property Service

File: `src/services/propertyService.ts`
```typescript
import axiosInstance from '../config/axios';

export interface Property {
  id: number;
  name: string;
  description: string;
  address: string;
  city: string;
  price_per_night: number;
  bedrooms: number;
  bathrooms: number;
  max_guests: number;
  image_url: string | null;
  is_featured: boolean;
  rating: number;
  created_at: string;
  updated_at: string;
}

export interface PropertySearchParams {
  keyword?: string;
  city?: string;
  min_price?: number;
  max_price?: number;
  bedrooms?: number;
  check_in?: string;
  check_out?: string;
  guests?: number;
  sort_by?: string;
  sort_direction?: 'asc' | 'desc';
  page?: number;
  per_page?: number;
}

export interface PaginatedResponse<T> {
  data: T[];
  current_page: number;
  from: number;
  last_page: number;
  per_page: number;
  to: number;
  total: number;
}

const propertyService = {
  // Lấy danh sách tất cả properties (có phân trang)
  getAll: async (params?: Partial<PropertySearchParams>): Promise<PaginatedResponse<Property>> => {
    const response = await axiosInstance.get('/properties', { params });
    return response.data;
  },

  // Lấy danh sách properties nổi bật
  getFeatured: async (): Promise<Property[]> => {
    const response = await axiosInstance.get('/properties/featured');
    return response.data;
  },

  // Lấy chi tiết một property
  getById: async (id: number): Promise<Property> => {
    const response = await axiosInstance.get(`/properties/${id}`);
    return response.data;
  },

  // Tìm kiếm property với các bộ lọc
  search: async (params: PropertySearchParams): Promise<PaginatedResponse<Property>> => {
    const response = await axiosInstance.get('/properties/search', { params });
    return response.data;
  },
};

export default propertyService;
```

#### Booking Service

File: `src/services/bookingService.ts`
```typescript
import axiosInstance from '../config/axios';
import { Property } from './propertyService';

export interface Booking {
  id: number;
  user_id: number;
  property_id: number;
  check_in_date: string;
  check_out_date: string;
  total_price: number;
  guests: number;
  status: 'pending' | 'confirmed' | 'cancelled' | 'completed';
  created_at: string;
  updated_at: string;
  property?: Property;
}

export interface BookingCreate {
  property_id: number;
  check_in_date: string;
  check_out_date: string;
  guests: number;
}

const bookingService = {
  // Tạo booking mới
  create: async (data: BookingCreate): Promise<Booking> => {
    const response = await axiosInstance.post('/bookings', data);
    return response.data.booking;
  },

  // Lấy danh sách booking của user hiện tại
  getUserBookings: async (): Promise<Booking[]> => {
    const response = await axiosInstance.get('/bookings/user');
    return response.data;
  },

  // Lấy chi tiết booking
  getById: async (id: number): Promise<Booking> => {
    const response = await axiosInstance.get(`/bookings/${id}`);
    return response.data;
  },

  // Hủy booking
  cancel: async (id: number): Promise<Booking> => {
    const response = await axiosInstance.post(`/bookings/${id}/cancel`);
    return response.data.booking;
  },
};

export default bookingService;
```

### 3. Tích Hợp React Query

#### Cấu hình React Query

File: `src/App.tsx` hoặc `src/main.tsx` (tùy vào cấu trúc dự án)
```tsx
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { ReactQueryDevtools } from '@tanstack/react-query-devtools';

const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      refetchOnWindowFocus: false,
      retry: 1,
    },
  },
});

function App() {
  return (
    <QueryClientProvider client={queryClient}>
      {/* Các component khác của ứng dụng */}
      <ReactQueryDevtools initialIsOpen={false} />
    </QueryClientProvider>
  );
}
```

#### Custom Hooks cho API

File: `src/hooks/useApi.ts`
```typescript
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { message } from 'antd';

import propertyService, { PropertySearchParams } from '../services/propertyService';
import bookingService, { BookingCreate } from '../services/bookingService';
import authService, { LoginCredentials, RegisterData } from '../services/authService';

// QueryClient để invalidate queries khi cần
const queryClient = useQueryClient();

// Property related hooks
export const useGetProperties = (params?: Partial<PropertySearchParams>) => {
  return useQuery({
    queryKey: ['properties', params],
    queryFn: () => propertyService.getAll(params),
  });
};

export const useGetFeaturedProperties = () => {
  return useQuery({
    queryKey: ['featuredProperties'],
    queryFn: propertyService.getFeatured,
  });
};

export const useGetProperty = (id: number) => {
  return useQuery({
    queryKey: ['property', id],
    queryFn: () => propertyService.getById(id),
  });
};

export const useSearchProperties = (params: PropertySearchParams) => {
  return useQuery({
    queryKey: ['propertySearch', params],
    queryFn: () => propertyService.search(params),
  });
};

// Booking related hooks
export const useCreateBooking = () => {
  return useMutation({
    mutationFn: (bookingData: BookingCreate) => bookingService.create(bookingData),
    onSuccess: () => {
      message.success('Đặt phòng thành công!');
      queryClient.invalidateQueries({ queryKey: ['userBookings'] });
    },
    onError: (error: any) => {
      message.error(error.response?.data?.message || 'Có lỗi xảy ra khi đặt phòng');
    },
  });
};

export const useGetUserBookings = () => {
  return useQuery({
    queryKey: ['userBookings'],
    queryFn: bookingService.getUserBookings,
  });
};

export const useCancelBooking = () => {
  return useMutation({
    mutationFn: (id: number) => bookingService.cancel(id),
    onSuccess: () => {
      message.success('Hủy đặt phòng thành công!');
      queryClient.invalidateQueries({ queryKey: ['userBookings'] });
    },
    onError: (error: any) => {
      message.error(error.response?.data?.message || 'Có lỗi xảy ra khi hủy đặt phòng');
    },
  });
};

// Auth related hooks
export const useLogin = () => {
  return useMutation({
    mutationFn: (credentials: LoginCredentials) => authService.login(credentials),
    onSuccess: (response) => {
      authService.setToken(response.access_token);
      message.success('Đăng nhập thành công!');
    },
    onError: (error: any) => {
      message.error(error.response?.data?.message || 'Đăng nhập thất bại');
    },
  });
};

export const useRegister = () => {
  return useMutation({
    mutationFn: (userData: RegisterData) => authService.register(userData),
    onSuccess: () => {
      message.success('Đăng ký thành công! Vui lòng đăng nhập.');
    },
    onError: (error: any) => {
      message.error(error.response?.data?.message || 'Đăng ký thất bại');
    },
  });
};

export const useLogout = () => {
  return useMutation({
    mutationFn: authService.logout,
    onSuccess: () => {
      localStorage.removeItem('authToken');
      message.success('Đăng xuất thành công!');
      queryClient.clear();
    },
  });
};

export const useGetUserProfile = () => {
  return useQuery({
    queryKey: ['userProfile'],
    queryFn: authService.getCurrentUser,
    retry: false,
    enabled: !!authService.getToken(),
  });
};
```

### 4. Sử Dụng API trong Components

#### Login Component

File: `src/pages/Login.tsx`
```tsx
import React, { useState } from 'react';
import { Form, Input, Button, Typography, Divider, Alert } from 'antd';
import { UserOutlined, LockOutlined } from '@ant-design/icons';
import { useNavigate } from 'react-router-dom';
import { useLogin } from '../hooks/useApi';

const { Title } = Typography;

interface LoginFormValues {
  email: string;
  password: string;
}

const Login: React.FC = () => {
  const [form] = Form.useForm();
  const navigate = useNavigate();
  
  // Sử dụng custom hook để call API
  const { mutate: login, isPending, error } = useLogin();

  const onFinish = (values: LoginFormValues) => {
    login(values, {
      onSuccess: () => {
        navigate('/dashboard');
      }
    });
  };

  return (
    <div className="login-container">
      <div className="login-form">
        <Title level={2} className="text-center">Đăng Nhập</Title>
        
        {error && (
          <Alert message={error.message} type="error" showIcon className="mb-4" />
        )}
        
        <Form
          form={form}
          name="login"
          onFinish={onFinish}
          layout="vertical"
        >
          <Form.Item
            name="email"
            rules={[
              { required: true, message: 'Vui lòng nhập email!' },
              { type: 'email', message: 'Email không hợp lệ!' }
            ]}
          >
            <Input prefix={<UserOutlined />} placeholder="Email" size="large" />
          </Form.Item>
          
          <Form.Item
            name="password"
            rules={[{ required: true, message: 'Vui lòng nhập mật khẩu!' }]}
          >
            <Input.Password
              prefix={<LockOutlined />}
              placeholder="Mật khẩu"
              size="large"
            />
          </Form.Item>
          
          <Form.Item>
            <Button 
              type="primary" 
              htmlType="submit" 
              size="large" 
              block
              loading={isPending}
            >
              Đăng Nhập
            </Button>
          </Form.Item>
        </Form>
        
        <Divider>Hoặc</Divider>
        
        <Button 
          block 
          size="large" 
          onClick={() => navigate('/register')}
        >
          Đăng Ký Tài Khoản Mới
        </Button>
      </div>
    </div>
  );
};

export default Login;
```

#### Property List Component

File: `src/pages/PropertyList.tsx`
```tsx
import React, { useState } from 'react';
import { Row, Col, Card, Pagination, Input, Select, Slider, Empty, Spin } from 'antd';
import { SearchOutlined } from '@ant-design/icons';
import { useSearchProperties } from '../hooks/useApi';
import PropertyCard from '../components/PropertyCard';

const { Search } = Input;
const { Option } = Select;

const PropertyList: React.FC = () => {
  const [searchParams, setSearchParams] = useState({
    keyword: '',
    city: '',
    min_price: 0,
    max_price: 10000000,
    bedrooms: 0,
    page: 1,
    per_page: 12,
    sort_by: 'created_at',
    sort_direction: 'desc' as 'asc' | 'desc',
  });
  
  // Sử dụng custom hook để search properties
  const { data, isLoading, error } = useSearchProperties(searchParams);
  
  const handleSearch = (value: string) => {
    setSearchParams(prev => ({ ...prev, keyword: value, page: 1 }));
  };
  
  const handleFilterChange = (field: string, value: any) => {
    setSearchParams(prev => ({ ...prev, [field]: value, page: 1 }));
  };
  
  const handlePageChange = (page: number) => {
    setSearchParams(prev => ({ ...prev, page }));
  };
  
  if (isLoading) {
    return <div className="loading-container"><Spin size="large" /></div>;
  }
  
  if (error) {
    return <div className="error-container">Có lỗi xảy ra khi tải dữ liệu</div>;
  }
  
  return (
    <div className="property-list-container">
      <div className="search-filters">
        <Row gutter={[16, 16]} align="middle">
          <Col xs={24} sm={24} md={8}>
            <Search
              placeholder="Tìm kiếm theo tên, địa chỉ..."
              enterButton={<SearchOutlined />}
              size="large"
              onSearch={handleSearch}
            />
          </Col>
          
          <Col xs={24} sm={12} md={4}>
            <Select
              placeholder="Thành phố"
              style={{ width: '100%' }}
              onChange={(value) => handleFilterChange('city', value)}
            >
              <Option value="">Tất cả</Option>
              <Option value="Hà Nội">Hà Nội</Option>
              <Option value="TP.HCM">TP.HCM</Option>
              <Option value="Đà Nẵng">Đà Nẵng</Option>
              <Option value="Nha Trang">Nha Trang</Option>
              <Option value="Đà Lạt">Đà Lạt</Option>
            </Select>
          </Col>
          
          <Col xs={24} sm={12} md={4}>
            <Select
              placeholder="Sắp xếp theo"
              style={{ width: '100%' }}
              onChange={(value) => handleFilterChange('sort_by', value)}
            >
              <Option value="price_per_night">Giá</Option>
              <Option value="rating">Đánh giá</Option>
              <Option value="created_at">Mới nhất</Option>
            </Select>
          </Col>
          
          <Col xs={24} sm={12} md={4}>
            <Select
              placeholder="Thứ tự"
              style={{ width: '100%' }}
              onChange={(value) => handleFilterChange('sort_direction', value)}
            >
              <Option value="asc">Tăng dần</Option>
              <Option value="desc">Giảm dần</Option>
            </Select>
          </Col>
          
          <Col xs={24} sm={12} md={4}>
            <Select
              placeholder="Số phòng ngủ"
              style={{ width: '100%' }}
              onChange={(value) => handleFilterChange('bedrooms', value)}
            >
              <Option value={0}>Tất cả</Option>
              <Option value={1}>1+</Option>
              <Option value={2}>2+</Option>
              <Option value={3}>3+</Option>
              <Option value={4}>4+</Option>
            </Select>
          </Col>
        </Row>
        
        <Row className="mt-4">
          <Col span={24}>
            <p>Khoảng giá (VNĐ):</p>
            <Slider
              range
              min={0}
              max={10000000}
              step={100000}
              defaultValue={[0, 10000000]}
              tipFormatter={(value) => `${value.toLocaleString()} VNĐ`}
              onChange={(value: [number, number]) => {
                handleFilterChange('min_price', value[0]);
                handleFilterChange('max_price', value[1]);
              }}
            />
          </Col>
        </Row>
      </div>
      
      <div className="property-list mt-4">
        {data?.data?.length ? (
          <>
            <Row gutter={[16, 16]}>
              {data.data.map(property => (
                <Col key={property.id} xs={24} sm={12} md={8} lg={6}>
                  <PropertyCard property={property} />
                </Col>
              ))}
            </Row>
            
            <div className="pagination-container mt-4 text-center">
              <Pagination
                current={data.current_page}
                total={data.total}
                pageSize={data.per_page}
                onChange={handlePageChange}
                showSizeChanger={false}
              />
            </div>
          </>
        ) : (
          <Empty description="Không tìm thấy kết quả phù hợp" />
        )}
      </div>
    </div>
  );
};

export default PropertyList;
```

#### Booking Component

File: `src/pages/PropertyDetail.tsx` (một phần của component)
```tsx
// ... phần component khác

const BookingForm: React.FC<{ propertyId: number, price: number }> = ({ propertyId, price }) => {
  const [form] = Form.useForm();
  const { mutate: createBooking, isPending, error } = useCreateBooking();
  
  const onFinish = (values: any) => {
    const bookingData = {
      property_id: propertyId,
      check_in_date: values.dates[0].format('YYYY-MM-DD'),
      check_out_date: values.dates[1].format('YYYY-MM-DD'),
      guests: values.guests,
    };
    
    createBooking(bookingData, {
      onSuccess: () => {
        form.resetFields();
      }
    });
  };
  
  // Tính số ngày và tổng tiền
  const calculateTotal = (values: any) => {
    if (values.dates && values.dates[0] && values.dates[1]) {
      const checkIn = values.dates[0];
      const checkOut = values.dates[1];
      const days = checkOut.diff(checkIn, 'days');
      
      return price * days;
    }
    return 0;
  };
  
  return (
    <Card title="Đặt Phòng" className="booking-card">
      {error && <Alert message={error.message} type="error" className="mb-4" />}
      
      <Form
        form={form}
        name="booking"
        onFinish={onFinish}
        layout="vertical"
      >
        <Form.Item
          name="dates"
          label="Ngày nhận - trả phòng"
          rules={[{ required: true, message: 'Vui lòng chọn ngày!' }]}
        >
          <DatePicker.RangePicker 
            style={{ width: '100%' }}
            disabledDate={(current) => current && current < moment().startOf('day')}
            format="DD/MM/YYYY"
          />
        </Form.Item>
        
        <Form.Item
          name="guests"
          label="Số khách"
          rules={[{ required: true, message: 'Vui lòng chọn số khách!' }]}
        >
          <InputNumber min={1} max={10} style={{ width: '100%' }} />
        </Form.Item>
        
        <Form.Item shouldUpdate>
          {({ getFieldsValue }) => {
            const values = getFieldsValue();
            const total = calculateTotal(values);
            
            return (
              <div className="booking-summary">
                <p><strong>Giá mỗi đêm:</strong> {price.toLocaleString()} VNĐ</p>
                {total > 0 && (
                  <>
                    <p><strong>Tổng tiền:</strong> {total.toLocaleString()} VNĐ</p>
                  </>
                )}
              </div>
            );
          }}
        </Form.Item>
        
        <Form.Item>
          <Button 
            type="primary" 
            htmlType="submit" 
            block 
            size="large"
            loading={isPending}
          >
            Đặt Ngay
          </Button>
        </Form.Item>
      </Form>
    </Card>
  );
};

// ... phần component khác
```

## III. KẾT HỢP THÀNH LUỒNG HOÀN CHỈNH

### 1. Luồng Đăng Nhập và Xác Thực

**Backend**:
1. Laravel Sanctum tạo token khi người dùng đăng nhập
2. Kiểm tra token trong mỗi request đến API được bảo vệ
3. Đảm bảo CORS được cấu hình đúng để cho phép Frontend gọi API

**Frontend**:
1. Gửi thông tin đăng nhập đến backend thông qua Axios
2. Lưu token nhận được vào localStorage
3. Gửi token trong header của mỗi request
4. Xử lý các lỗi xác thực (401) bằng interceptor
5. Điều hướng đến trang đăng nhập khi token hết hạn

### 2. Luồng Hiển Thị và Tìm Kiếm Sản Phẩm

**Backend**:
1. API endpoint `/properties` để lấy danh sách
2. API endpoint `/properties/search` để tìm kiếm với các bộ lọc
3. API endpoint `/properties/{id}` để xem chi tiết
4. Trả về dữ liệu phân trang và sắp xếp

**Frontend**:
1. Sử dụng React Query để fetch và cache dữ liệu
2. Component hiển thị danh sách với các bộ lọc
3. Xử lý phân trang và sắp xếp
4. Hiển thị chi tiết sản phẩm
5. Xử lý loading và error states

### 3. Luồng Đặt Phòng

**Backend**:
1. API endpoint `/bookings` để tạo đơn đặt phòng mới
2. Kiểm tra xem phòng có sẵn trong khoảng thời gian không
3. Tính toán giá tiền dựa trên số đêm
4. Lưu thông tin đặt phòng vào database

**Frontend**:
1. Form đặt phòng với ngày check-in/out và số khách
2. Gửi thông tin đặt phòng lên API
3. Hiển thị xác nhận đặt phòng hoặc thông báo lỗi
4. Cập nhật UI để hiển thị đặt phòng mới trong trang "Đơn đặt phòng của tôi"

## KẾT LUẬN

Hướng dẫn này cung cấp một cái nhìn toàn diện về cách triển khai API RESTful trong Laravel và cách kết nối với những API này từ ứng dụng React bằng Axios. Bằng cách tuân theo cách tiếp cận có cấu trúc này, bạn có thể xây dựng một hệ thống đặt phòng khách sạn hoàn chỉnh với backend và frontend giao tiếp một cách hiệu quả.

Một số điểm cần nhớ:
- Luôn xử lý lỗi một cách phù hợp ở cả backend và frontend
- Sử dụng các công cụ như React Query để quản lý cache và trạng thái
- Đảm bảo bảo mật bằng cách xác thực đúng cách tất cả các API endpoints
- Tuân theo quy ước đặt tên nhất quán cho code dễ đọc và bảo trì

Chúc bạn thành công trong việc triển khai hệ thống LavishStay!
