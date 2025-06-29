import React, { createContext, useContext, useState, ReactNode } from 'react';

interface RoomType {
  id: number;
  name: string;
  // Add other properties of your room type here as needed
  // based on the API response structure
  base_price: number;
  size: number;
  view: string;
  rating: number;
  max_guests: number;
  room_code: string;
  images: Array<{ id: number; image_url: string; alt_text?: string; is_main: boolean; }>;
  main_image: { id: number; image_url: string; alt_text?: string; is_main: boolean; };
  amenities: Array<{ id: number; name: string; icon?: string; category: string; description?: string; }>;
  highlighted_amenities: Array<{ id: number; name: string; icon?: string; category: string; description?: string; }>;
}

interface RoomTypesContextType {
  roomTypes: RoomType[];
  setRoomTypes: (roomTypes: RoomType[]) => void;
}

const RoomTypesContext = createContext<RoomTypesContextType | undefined>(undefined);

export const RoomTypesProvider: React.FC<{ children: ReactNode }> = ({ children }) => {
  const [roomTypes, setRoomTypes] = useState<RoomType[]>([]);

  return (
    <RoomTypesContext.Provider value={{ roomTypes, setRoomTypes }}>
      {children}
    </RoomTypesContext.Provider>
  );
};

export const useRoomTypes = () => {
  const context = useContext(RoomTypesContext);
  if (context === undefined) {
    throw new Error('useRoomTypes must be used within a RoomTypesProvider');
  }
  return context;
};