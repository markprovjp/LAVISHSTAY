import React from 'react';
import { Card, Tag, Badge } from 'antd';
import { UserOutlined, HomeOutlined } from '@ant-design/icons';
import { RoomType, BADGE_CONFIG, TAG_CONFIG, BadgeType, TagType } from '../../types/roomTypes';
import PriceBadge from './PriceBadge';
import RatingStars from './RatingStars';
import AmenitiesList from './AmenitiesList';
import GalleryCarousel from './GalleryCarousel';
import { generateRoomAriaLabel, getImageUrl } from '../../utils/formatUtils';

interface RoomTypeOverviewCardProps {
    roomType: RoomType;
    onClick?: (roomType: RoomType) => void;
    className?: string;
}

const RoomTypeOverviewCard: React.FC<RoomTypeOverviewCardProps> = ({
    roomType,
    onClick,
    className = ''
}) => {
    const handleClick = () => {
        if (onClick) {
            onClick(roomType);
        } else {
            // Default behavior: navigate to room details
            window.open(roomType.slug_url, '_blank');
        }
    };

    const handleKeyDown = (event: React.KeyboardEvent) => {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            handleClick();
        }
    };

    const ariaLabel = generateRoomAriaLabel(
        roomType.title,
        roomType.starting_price,
        roomType.price_unit,
        roomType.avg_rating
    );

    return (
        <Card
            hoverable
            className={`room-type-overview-card h-full transition-all duration-300 hover:shadow-xl ${className}`}
            cover={
                <div className="relative">
                    <GalleryCarousel
                        images={
                            roomType.gallery && roomType.gallery.length > 0
                                ? roomType.gallery.map((g) => getImageUrl(g))
                                : [getImageUrl(roomType.thumbnail)]
                        }
                        alt={roomType.title}
                    />

                    {/* Badges overlay */}
                    {roomType.badges.length > 0 && (
                        <div className="absolute top-2 left-2 flex flex-wrap gap-1">
                            {roomType.badges.slice(0, 2).map((badge) => {
                                const badgeConfig = BADGE_CONFIG[badge as BadgeType];
                                return badgeConfig ? (
                                    <Badge key={badge} color={badgeConfig.color} text={badgeConfig.text} />
                                ) : null;
                            })}
                        </div>
                    )}

                    {/* Availability indicator */}
                    <div className="absolute top-2 right-2 bg-white bg-opacity-90 px-2 py-1 rounded-md text-xs">
                        <HomeOutlined className="mr-1" />
                        {roomType.available_rooms}/{roomType.total_rooms} phòng
                    </div>
                </div>
            }
            onClick={handleClick}
            onKeyDown={handleKeyDown}
            tabIndex={0}
            role="button"
            aria-label={ariaLabel}
        >
            <div className="space-y-3">
                {/* Title and Description */}
                <div>
                    <h3 className="text-lg font-semibold mb-2 line-clamp-1">
                        {roomType.title}
                    </h3>
                    <p className="text-gray-600 dark:text-gray-300 text-sm line-clamp-2 mb-3">
                        {roomType.short_description}
                    </p>
                </div>

                {/* Tags */}
                {roomType.tags.length > 0 && (
                    <div className="flex flex-wrap gap-1 mb-3">
                        {roomType.tags.slice(0, 3).map((tag) => {
                            const tagConfig = TAG_CONFIG[tag as TagType];
                            return tagConfig ? (
                                <Tag key={tag} color={tagConfig.color} className="text-xs">
                                    {tagConfig.text}
                                </Tag>
                            ) : (
                                <Tag key={tag} className="text-xs">{tag}</Tag>
                            );
                        })}
                    </div>
                )}

                {/* Capacity and Rating */}
                <div className="flex items-center justify-between mb-3">
                    <div className="flex items-center gap-4 text-sm text-gray-600">
                        <span className="flex items-center gap-1">
                            <UserOutlined />
                            {roomType.max_adults} người lớn
                        </span>
                        {roomType.max_children > 0 && (
                            <span>
                                +{roomType.max_children} trẻ em
                            </span>
                        )}
                    </div>

                    <RatingStars
                        rating={roomType.avg_rating}
                        reviewCount={roomType.review_count}
                        size="small"
                        showCount={false}
                    />
                </div>

                {/* Amenities */}
                <div className="mb-4">
                    <AmenitiesList
                        amenities={roomType.amenities}
                        maxDisplay={4}
                        showTooltip
                    />
                </div>

                {/* Price */}
                <div className="flex items-end justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                    <PriceBadge
                        price={roomType.starting_price}
                        currency={roomType.price_unit}
                    />

                    {roomType.available_rooms > 0 ? (
                        <span className="text-green-600 text-xs font-medium">
                            Còn trống
                        </span>
                    ) : (
                        <span className="text-red-500 text-xs font-medium">
                            Hết phòng
                        </span>
                    )}
                </div>
            </div>
        </Card>
    );
};

export default React.memo(RoomTypeOverviewCard);
