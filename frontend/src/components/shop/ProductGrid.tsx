import React from "react";
import AffiliateProductCard from "./AffiliateProductCard";
import ProductCardSkeleton from "./ProductCardSkeleton";
import { AffiliateProduct } from "@/services/jewelleryService";

// 1. Ensure 'isLoading: boolean;' is defined in this interface
interface ProductGridProps {
  products?: AffiliateProduct[];
  isLoading: boolean; // <-- This line is likely missing or incorrect in your file
  onProductClick: (url?: string | null) => void;
}

const ProductGrid: React.FC<ProductGridProps> = ({
  products = [],
  isLoading, // 2. Ensure 'isLoading' is destructured from the props here
  onProductClick,
}) => {
  // 3. This 'if' block handles the loading state
  if (isLoading) {
    return (
      <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        {Array.from({ length: 8 }).map((_, i) => (
          <ProductCardSkeleton key={i} />
        ))}
      </div>
    );
  }

  if (products.length === 0) {
    return (
      <div className="text-center py-10 bg-gray-50 rounded-lg col-span-full">
        <p className="text-gray-500">No products found matching your search.</p>
      </div>
    );
  }

  return (
    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      {products.map((product) => (
        <AffiliateProductCard
          key={product.id}
          product={product}
          onClick={() => onProductClick(product.affiliate_url)}
        />
      ))}
    </div>
  );
};

export default ProductGrid;
