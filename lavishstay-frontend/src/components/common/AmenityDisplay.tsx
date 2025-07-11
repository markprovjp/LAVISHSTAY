import React from 'react';
import { Tag, Tooltip } from 'antd';
import { getAmenityIcon, getCategoryColor } from '../../constants/Icons';

interface AmenityProps {
    id: number;
    name: string;
    icon: string;
    category: string;
    description?: string;
    isHighlighted?: boolean;
    size?: 'small' | 'default' | 'large';
    showTooltip?: boolean;
}

interface AmenityDisplayProps {
    amenities: AmenityProps[];
    maxDisplay?: number;
    layout?: 'grid' | 'inline';
    showCategories?: boolean;
}

const AmenityItem: React.FC<AmenityProps & { size?: 'small' | 'default' | 'large', showTooltip?: boolean }> = ({
    name,
    icon,
    category,
    description,
    isHighlighted = false,
    size = 'default',
    showTooltip = true
}) => {
    const categoryColor = getCategoryColor(category);
    const iconElement = getAmenityIcon(icon, category, isHighlighted);

    const tagStyle: React.CSSProperties = {
        padding: size === 'small' ? '2px 6px' : size === 'large' ? '6px 12px' : '4px 8px',
        borderRadius: '6px',
        border: `1px solid ${categoryColor}20`,
        background: isHighlighted
            ? `linear-gradient(135deg, ${categoryColor}15, ${categoryColor}08)`
            : `${categoryColor}08`,
        color: categoryColor,
        fontSize: size === 'small' ? '12px' : size === 'large' ? '14px' : '13px',
        fontWeight: isHighlighted ? 500 : 400,
        display: 'flex',
        alignItems: 'center',
        gap: '4px',
        margin: '2px',
        transition: 'all 0.2s ease-in-out',
    };

    const amenityElement = (
        <div
            style={tagStyle}
            className={`
        amenity-item cursor-default
        ${isHighlighted ? 'shadow-sm' : ''}
        hover:shadow-md hover:scale-105 transition-all duration-200
      `}
        >
            {iconElement}
            <span>{name}</span>
        </div>
    );

    if (showTooltip && description) {
        return (
            <Tooltip title={description} placement="top">
                {amenityElement}
            </Tooltip>
        );
    }

    return amenityElement;
};

const AmenityDisplay: React.FC<AmenityDisplayProps> = ({
    amenities,
    maxDisplay,
    layout = 'grid',
    showCategories = false,
}) => {
    const displayAmenities = maxDisplay ? amenities.slice(0, maxDisplay) : amenities;
    const remainingCount = maxDisplay && amenities.length > maxDisplay
        ? amenities.length - maxDisplay
        : 0;

    if (showCategories) {
        // Group by category
        const groupedAmenities = displayAmenities.reduce((groups, amenity) => {
            const category = amenity.category;
            if (!groups[category]) {
                groups[category] = [];
            }
            groups[category].push(amenity);
            return groups;
        }, {} as Record<string, AmenityProps[]>);

        return (
            <div className="space-y-4">
                {Object.entries(groupedAmenities).map(([category, categoryAmenities]) => (
                    <div key={category}>
                        <h4 className="text-sm font-medium text-gray-700 mb-2 capitalize">
                            {category.replace('_', ' ')}
                        </h4>
                        <div className={layout === 'grid' ? 'grid grid-cols-2 gap-2' : 'flex flex-wrap'}>
                            {categoryAmenities.map((amenity) => (
                                <AmenityItem key={amenity.id} {...amenity} />
                            ))}
                        </div>
                    </div>
                ))}
            </div>
        );
    }

    return (
        <div className={layout === 'grid' ? 'grid grid-cols-2 gap-2' : 'flex flex-wrap'}>
            {displayAmenities.map((amenity) => (
                <AmenityItem key={amenity.id} {...amenity} />
            ))}
            {remainingCount > 0 && (
                <Tag color="default" className="flex items-center">
                    +{remainingCount} more
                </Tag>
            )}
        </div>
    );
};

export default AmenityDisplay;
export { AmenityItem };
