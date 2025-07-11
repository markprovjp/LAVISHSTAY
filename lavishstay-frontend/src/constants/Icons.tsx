import React from 'react';
import {
  Wifi,
  Tv,
  CupSoda,
  Lock,
  ShowerHead,
  Bath,
  Droplet,
  Wind,
  Building2,
  EyeOff,
  Palette,
  Sofa,
  Layers,
  Grid,
  Briefcase,
  Armchair,
  Unlock,
  Wine,
  Book,
  Coffee,
  BedDouble,
  Bed,
  Usb,
  Phone,
  Speaker,
  Shirt,
  Footprints,
  Brush,
  GlassWater,
  Snowflake,
  Home,
  Image,
  User,
  TreePine,
  Utensils,
  BookOpen,
  Moon,
  Sparkles,
  Car,
  Shield,
  Waves,
  Cpu,
  Mountain,
  Gamepad2,
  Headphones,
  Users,
  Zap,
  Clock,
} from "lucide-react";

import {
  HomeOutlined,
  CrownOutlined,
  CoffeeOutlined,
  BellOutlined,
  CalendarOutlined,
  StarOutlined,
  LockOutlined,
  MoonOutlined,
  PictureOutlined,
} from "@ant-design/icons";

const lucideIcons: { [key: string]: React.ComponentType<any> } = {
  Wifi,
  Tv,
  Tv2: Tv, // Alias for Tv2
  CupSoda,
  Lock,
  ShowerHead,
  Bath,
  Droplet,
  Wind,
  Building2,
  EyeOff,
  Palette,
  Sofa,
  Layers,
  Grid,
  Briefcase,
  Armchair,
  Unlock,
  Wine,
  Book,
  Coffee,
  BedDouble,
  Bed,
  Usb,
  Phone,
  Speaker,
  Shirt,
  Footprints,
  Brush,
  GlassWater,
  Snowflake,
  Home,
  Image,
  User,
  TreePine,
  Utensils,
  BookOpen,
  Moon,
  Sparkles,
  Car,
  Shield,
  Waves,
  Cpu,
  Mountain,
  Gamepad2,
  Headphones,
  Users,
  Zap,
  Clock,

  // Amenity-specific mappings from API
  Soap: Droplet, // Đồ vệ sinh cao cấp
  Window: Mountain, // Cửa kính kịch trần
  City: Building2, // Tường kính bao quanh
  Pillow: Layers, // Gối memory foam
  Bathrobe: Shirt, // Áo choàng tắm
  Slippers: Footprints, // Dép đi trong phòng
  Broom: Brush, // Dọn phòng
  RoomService: Utensils, // Dịch vụ phòng
  Log: TreePine, // Tường ốp gỗ
  Fabric: Layers, // Trang trí vải nỉ
  Chair: Armchair, // Ghế/Bàn gỗ với ghế nệm da
};

const antdIcons: { [key: string]: React.ComponentType<any> } = {
  HomeOutlined,
  CrownOutlined,
  CoffeeOutlined,
  BellOutlined,
  CalendarOutlined,
  StarOutlined,
  LockOutlined,
  MoonOutlined,
  PictureOutlined,
  // Aliases for names used in the app
  Crown: CrownOutlined,
  Bell: BellOutlined,
  Calendar: CalendarOutlined,
  Star: StarOutlined,
  Moon: MoonOutlined,
};

// Category color mapping
const categoryColors: { [key: string]: string } = {
  basic: '#3B82F6', // Blue
  entertainment: '#8B5CF6', // Purple  
  connectivity: '#10B981', // Green
  security: '#F59E0B', // Amber
  bathroom: '#06B6D4', // Cyan
  view: '#EF4444', // Red
  comfort: '#EC4899', // Pink
  service: '#F97316', // Orange
};

// Get category color
export const getCategoryColor = (category: string): string => {
  return categoryColors[category] || '#6B7280'; // Default gray
};

// Get amenity icon with enhanced styling
export const getAmenityIcon = (iconName?: string, category?: string, isHighlighted?: boolean) => {
  if (!iconName) {
    return <StarOutlined style={{ fontSize: "16px", color: '#6B7280' }} />;
  }

  const color = category ? getCategoryColor(category) : '#6B7280';
  const size = isHighlighted ? 20 : 16;

  if (lucideIcons[iconName]) {
    const IconComponent = lucideIcons[iconName];
    return <IconComponent size={size} color={color} />;
  }

  if (antdIcons[iconName]) {
    const IconComponent = antdIcons[iconName];
    return <IconComponent style={{ fontSize: `${size}px`, color }} />;
  }

  console.warn(`Icon '${iconName}' not found. Using fallback.`);
  return <StarOutlined style={{ fontSize: `${size}px`, color }} />;
};

export const getIcon = (iconName?: string) => {
  if (!iconName) {
    return <StarOutlined style={{ fontSize: "20px" }} />;
  }

  if (lucideIcons[iconName]) {
    const IconComponent = lucideIcons[iconName];
    return <IconComponent size={20} />;
  }

  if (antdIcons[iconName]) {
    const IconComponent = antdIcons[iconName];
    return <IconComponent style={{ fontSize: "20px" }} />;
  }

  console.warn(`Icon '${iconName}' not found. Using fallback.`);
  return <StarOutlined style={{ fontSize: "20px" }} />;
};