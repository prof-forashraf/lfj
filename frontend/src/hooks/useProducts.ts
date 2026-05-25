// src/hooks/useProducts.ts

import { useQuery } from "@tanstack/react-query";
import { getStudioItems, getFeaturedProducts, Product } from "@/services/productService";
import { getPublicImageUrl } from '@/lib/imageUrl';

// A helper to add a random rating, just like in your working Shop page hook
const getRandomRating = (): number =>
  parseFloat((Math.random() * (5.0 - 4.2) + 4.2).toFixed(1));

/**
 * Hook for fetching FEATURED products (for your Shop page).
 * Based on your working implementation.
 */
export const useFeaturedProducts = (limit: number = 8) => {
  return useQuery({
    queryKey: ["products", "featured", limit],
    queryFn: () => getFeaturedProducts(limit),
    select: (data) => {
      // Add the random rating just like your working hook does
      return data.map((product) => ({
        ...product,
        rating: getRandomRating(),
      }));
    },
  });
};

/**
 * Hook for fetching TRY-ON STUDIO products (for your JewelleryStudio page).
 */
export const useStudioProducts = (searchTerm: string) => {
  return useQuery({
    // The queryKey now includes the searchTerm, so React Query automatically refetches when it changes.
    queryKey: ["products", "studio", searchTerm],

    // The queryFn now passes the searchTerm to the service.
    queryFn: () => getStudioItems(searchTerm),

    // ✅ THIS IS THE FIX FOR BROKEN IMAGES
    // The 'select' option transforms the data after it's fetched.
    select: (data: Product[]): Product[] => {
      return data.map((product) => ({
        ...product,
        // We construct the full, absolute URL for both images
        image_url: getPublicImageUrl(product.image_url),
        try_on_image_url: getPublicImageUrl(product.try_on_image_url),
      }));
    },
    staleTime: 1000 * 60 * 5, // Cache for 5 minutes
  });
};
