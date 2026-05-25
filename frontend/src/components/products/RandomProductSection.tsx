import React from "react";
import { useFeaturedAffiliateProducts } from "@/hooks/useAffiliateProducts";
import ProductCard from "./ProductCard";
import { ExploreMoreCard } from "./ExploreMoreCard"; // <-- Import the new card

interface RandomProductSectionProps {
  title: string;
  limit?: number; // Now this limit is for product cards only
  sectionId: string;
}

const RandomProductSection: React.FC<RandomProductSectionProps> = ({
  title,
  limit = 4, // Default to 3 products + 1 explore card = 4 total items
  sectionId,
}) => {
  const {
    data: products,
    isLoading,
    isError,
  } = useFeaturedAffiliateProducts(limit, sectionId);

  if (isLoading) {
    // You can add a skeleton loader here if you wish
    return (
      <div className="text-center text-gray-400">Loading treasures...</div>
    );
  }

  if (isError || !products || products.length === 0) {
    return null;
  }

  return (
    <div className="w-full">
      <h2 className="text-3xl font-playfair font-bold text-dark-slate text-center mb-12">
        {title}
      </h2>
      {/* --- THIS IS THE UPGRADE --- */}
      {/* The grid now accommodates one extra item for the "Explore More" card */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        {products.map((product) => (
          <ProductCard key={product.id} product={product} />
        ))}
        {/* Add the ExploreMoreCard as the last item in the grid */}
        <ExploreMoreCard />
      </div>
    </div>
  );
};

export default RandomProductSection;
