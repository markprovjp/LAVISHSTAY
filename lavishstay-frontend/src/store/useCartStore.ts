// src/store/useCartStore.ts
import { create } from 'zustand';
import { persist } from 'zustand/middleware';

// Define the item type
interface CartItem {
  id: number;
  name: string;
  price: number;
  quantity: number;
  imageUrl?: string;
}

// Define the store state type
interface CartState {
  items: CartItem[];
  totalItems: number;
  totalPrice: number;
  
  // Actions
  addItem: (item: Omit<CartItem, 'quantity'>) => void;
  removeItem: (itemId: number) => void;
  updateQuantity: (itemId: number, quantity: number) => void;
  clearCart: () => void;
}

// Calculate totals helper function
const calculateTotals = (items: CartItem[]) => {
  return items.reduce(
    (acc, item) => {
      acc.totalItems += item.quantity;
      acc.totalPrice += item.price * item.quantity;
      return acc;
    },
    { totalItems: 0, totalPrice: 0 }
  );
};

// Create the store with persistence
const useCartStore = create<CartState>()(
  persist(
    (set, get) => ({
      items: [],
      totalItems: 0,
      totalPrice: 0,
      
      addItem: (item) => set((state) => {
        // Check if item already exists in cart
        const existingItemIndex = state.items.findIndex(
          (i) => i.id === item.id
        );

        let newItems: CartItem[];
        
        if (existingItemIndex >= 0) {
          // Item exists, increase quantity
          newItems = [...state.items];
          newItems[existingItemIndex].quantity += 1;
        } else {
          // Item doesn't exist, add with quantity 1
          newItems = [...state.items, { ...item, quantity: 1 }];
        }
        
        const { totalItems, totalPrice } = calculateTotals(newItems);
        
        return { items: newItems, totalItems, totalPrice };
      }),
      
      removeItem: (itemId) => set((state) => {
        const newItems = state.items.filter((item) => item.id !== itemId);
        const { totalItems, totalPrice } = calculateTotals(newItems);
        
        return { items: newItems, totalItems, totalPrice };
      }),
      
      updateQuantity: (itemId, quantity) => set((state) => {
        if (quantity <= 0) {
          return get().removeItem(itemId);
        }
        
        const newItems = state.items.map((item) =>
          item.id === itemId ? { ...item, quantity } : item
        );
        
        const { totalItems, totalPrice } = calculateTotals(newItems);
        
        return { items: newItems, totalItems, totalPrice };
      }),
      
      clearCart: () => set({ items: [], totalItems: 0, totalPrice: 0 }),
    }),
    {
      name: 'lavishstay-cart', // unique name for localStorage
    }
  )
);

export default useCartStore;
