// src/components/CategoryFilter.tsx
import React from 'react';
import { NewsCategory } from '../../types/News';

// Props cho component CategoryFilter
interface CategoryFilterProps {
  categories: NewsCategory[];
  selectedCategory: number | null;
  onCategoryChange: (categoryId: number | null) => void;
}

// Component hiển thị bộ lọc danh mục
const CategoryFilter: React.FC<CategoryFilterProps> = ({ categories, selectedCategory, onCategoryChange }) => {
  return (
    <div className="mb-6">
      <h2 className="text-xl font-bold text-gray-800 mb-3">Danh mục tin tức</h2>
      <div className="flex flex-wrap gap-2">
        {/* Nút chọn tất cả danh mục */}
        <button
          className={`px-4 py-2 rounded-full text-sm font-medium ${
            selectedCategory === null
              ? 'bg-blue-600 text-white'
              : 'bg-gray-200 text-gray-800 hover:bg-blue-100'
          }`}
          onClick={() => onCategoryChange(null)}
        >
          Tất cả
        </button>
        {/* Danh sách danh mục */}
        {categories.map((category) => (
          <button
            key={category.id}
            className={`px-4 py-2 rounded-full text-sm font-medium ${
              selectedCategory === category.id
                ? 'bg-blue-600 text-white'
                : 'bg-gray-200 text-gray-800 hover:bg-blue-100'
            }`}
            onClick={() => onCategoryChange(category.id)}
          >
            {category.name}
          </button>
        ))}
      </div>
    </div>
  );
};

export default CategoryFilter;