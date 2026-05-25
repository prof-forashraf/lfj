
import React from 'react';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';

interface BlogCategory {
  id: string | number;
  name: string;
  slug: string;
}

interface BlogFilterProps {
  categories: BlogCategory[];
  activeCategory: string;
  onCategoryChange: (category: string) => void;
}

const BlogFilter: React.FC<BlogFilterProps> = ({ categories, activeCategory, onCategoryChange }) => {
  return (
    <div className="mb-8">
      <div className="flex overflow-x-auto pb-2 space-x-2 no-scrollbar">
        <Button
          variant={activeCategory === 'all' ? 'default' : 'outline'}
          className={cn(
            "rounded-full whitespace-nowrap",
            activeCategory === 'all' ? 'bg-gold-500 hover:bg-gold-600' : 'text-gray-700 border-gold-200 hover:bg-gold-50'
          )}
          onClick={() => onCategoryChange('all')}
        >
          All Posts
        </Button>
        
        {categories.map((category) => (
          <Button
            key={category.id}
            variant={activeCategory === category.slug ? 'default' : 'outline'}
            className={cn(
              "rounded-full whitespace-nowrap",
              activeCategory === category.slug ? 'bg-gold-500 hover:bg-gold-600' : 'text-gray-700 border-gold-200 hover:bg-gold-50'
            )}
            onClick={() => onCategoryChange(category.slug)}
          >
            {category.name}
          </Button>
        ))}
      </div>
    </div>
  );
};

export default BlogFilter;
