// src/hooks/useHotels.ts
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '../utils/api';

// Type for Hotel data
interface Hotel {
  id: number;
  name: string;
  description: string;
  location: string;
  price: number;
  rating: number;
  imageUrl: string;
}

// Fetch all hotels
export const useHotels = () => {
  return useQuery({
    queryKey: ['hotels'],
    queryFn: async () => {
      const response = await api.get('/hotels');
      return response.data as Hotel[];
    },
  });
};

// Fetch a single hotel by ID
export const useHotel = (id: number | string) => {
  return useQuery({
    queryKey: ['hotels', id],
    queryFn: async () => {
      const response = await api.get(`/hotels/${id}`);
      return response.data as Hotel;
    },
    enabled: !!id, // Only run query if ID is present
  });
};

// Add a new hotel
export const useAddHotel = () => {
  const queryClient = useQueryClient();
  
  return useMutation({
    mutationFn: async (newHotel: Omit<Hotel, 'id'>) => {
      const response = await api.post('/hotels', newHotel);
      return response.data;
    },
    onSuccess: () => {
      // Invalidate and refetch hotels list query
      queryClient.invalidateQueries({ queryKey: ['hotels'] });
    },
  });
};

// Update an existing hotel
export const useUpdateHotel = () => {
  const queryClient = useQueryClient();
  
  return useMutation({
    mutationFn: async ({ id, ...data }: Partial<Hotel> & { id: number }) => {
      const response = await api.put(`/hotels/${id}`, data);
      return response.data;
    },
    onSuccess: (_, variables) => {
      // Invalidate and refetch the specific hotel and hotels list
      queryClient.invalidateQueries({ queryKey: ['hotels', variables.id] });
      queryClient.invalidateQueries({ queryKey: ['hotels'] });
    },
  });
};

// Delete a hotel
export const useDeleteHotel = () => {
  const queryClient = useQueryClient();
  
  return useMutation({
    mutationFn: async (id: number) => {
      await api.delete(`/hotels/${id}`);
      return id;
    },
    onSuccess: () => {
      // Invalidate and refetch hotels list query
      queryClient.invalidateQueries({ queryKey: ['hotels'] });
    },
  });
};
