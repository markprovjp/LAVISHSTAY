import { describe, it, expect, vi, beforeEach } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import { BrowserRouter } from 'react-router-dom';
import RoomTypeOverviewCard from '../RoomTypeOverviewCard';
import { RoomType } from '../../../types/roomTypes';

// Mock data
const mockRoomType: RoomType = {
    room_type_id: 1,
    slug: 'deluxe-double',
    title: 'Deluxe Double Room',
    short_description: 'Spacious room with sea view',
    thumbnail: 'https://example.com/image.jpg',
    gallery: ['https://example.com/image1.jpg', 'https://example.com/image2.jpg'],
    starting_price: 500000,
    price_unit: 'VND',
    max_adults: 2,
    max_children: 1,
    total_rooms: 5,
    available_rooms: 3,
    avg_rating: 4.5,
    review_count: 23,
    amenities: [
        { id: 1, name: 'WiFi', icon: 'wifi' },
        { id: 2, name: 'Pool', icon: 'pool' }
    ],
    tags: ['sea-view', 'premium'],
    badges: ['featured'],
    slug_url: '/room-types/deluxe-double'
};

const renderWithRouter = (component: React.ReactElement) => {
    return render(
        <BrowserRouter>
            {component}
        </BrowserRouter>
    );
};

describe('RoomTypeOverviewCard', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('renders room type information correctly', () => {
        renderWithRouter(<RoomTypeOverviewCard roomType={mockRoomType} />);

        expect(screen.getByText('Deluxe Double Room')).toBeInTheDocument();
        expect(screen.getByText('Spacious room with sea view')).toBeInTheDocument();
        expect(screen.getByText('2 người lớn')).toBeInTheDocument();
        expect(screen.getByText('3/5 phòng')).toBeInTheDocument();
    });

    it('calls onClick when card is clicked', () => {
        const mockOnClick = vi.fn();
        renderWithRouter(
            <RoomTypeOverviewCard roomType={mockRoomType} onClick={mockOnClick} />
        );

        const card = screen.getByRole('button');
        fireEvent.click(card);

        expect(mockOnClick).toHaveBeenCalledWith(mockRoomType);
    });

    it('shows availability status correctly', () => {
        renderWithRouter(<RoomTypeOverviewCard roomType={mockRoomType} />);
        expect(screen.getByText('Còn trống')).toBeInTheDocument();
    });

    it('shows sold out status when no rooms available', () => {
        const soldOutRoom = { ...mockRoomType, available_rooms: 0 };
        renderWithRouter(<RoomTypeOverviewCard roomType={soldOutRoom} />);
        expect(screen.getByText('Hết phòng')).toBeInTheDocument();
    });

    it('displays price correctly', () => {
        renderWithRouter(<RoomTypeOverviewCard roomType={mockRoomType} />);
        expect(screen.getByText(/500.000/)).toBeInTheDocument();
    });

    it('shows contact for price when price is null', () => {
        const noPriceRoom = { ...mockRoomType, starting_price: null };
        renderWithRouter(<RoomTypeOverviewCard roomType={noPriceRoom} />);
        expect(screen.getByText('Liên hệ')).toBeInTheDocument();
    });
});
