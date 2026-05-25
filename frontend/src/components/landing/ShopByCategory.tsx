
// src/components/landing/ShopByCategory.tsx
import React from 'react';
import { Link } from 'react-router-dom';
import { Card } from '@/components/ui/card';
import { Category } from '@/services/categoryService'; // Your existing type
import { ArrowRight } from 'lucide-react';

interface ShopByCategoryProps {
  categories: Category[];
}

const ShopByCategory: React.FC<ShopByCategoryProps> = ({ categories }) => {
  return (
    <section id="shop-by-category" className="py-16 md:py-24 bg-warm-white">
      <div className="container mx-auto px-6">
        <h2 className="text-4xl font-playfair text-center mb-12 text-luxury-navy">Shop by Category</h2>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
          {categories.map((category) => (
            <Link to={`/shop/category/${category.slug}`} key={category.id} className="group block">
              <Card className="overflow-hidden border-gray-200 shadow-sm hover:shadow-xl transition-shadow duration-300 rounded-lg">
                <div className="relative h-64">
                 <img 
                   src={category.image} 
                   alt={category.name} 
                   className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                   loading="lazy"
                   decoding="async"
                   width={800} height={600} onError={(event) => { event.currentTarget.src = "/images/placeholder.svg"; }} />
                  <div className="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition-colors duration-300"></div>
                </div>
                <div className="p-5 bg-white">
                  <h3 className="text-xl font-playfair font-semibold text-luxury-navy">{category.name}</h3>
                  <p className="text-sm text-gray-500 mt-1 flex items-center group-hover:text-luxury-gold transition-colors">
                    Explore Now <ArrowRight className="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" />
                  </p>
                </div>
              </Card>
            </Link>
          ))}
        </div>
      </div>
    </section>
  );
};

export default ShopByCategory;
