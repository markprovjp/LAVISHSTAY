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
  // Aliases for missing icons
  City: Building2,
  Chair: Armchair,
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