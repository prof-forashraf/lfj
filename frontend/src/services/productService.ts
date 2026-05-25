// src/services/productService.ts

import apiClient from "@/lib/apiClient";

// A single, unified Product type for the whole application
export interface Product {
  id: number;
  name: string;
  image_url: string | null; // <-- Added back for the selection grid
  try_on_image_url: string | null;
  item_type: string;
  tags: string[];
}

/**
 * Fetches featured/random products. THIS IS THE ENDPOINT THAT IS WORKING.
 * @param limit The number of products to fetch.
 */
export const getFeaturedProducts = async (
  limit: number = 8
): Promise<Product[]> => {
  try {
    // This endpoint works on your Shop page. We use it as our foundation.
    const response = await apiClient.get<{ data: Product[] }>(
      `/products/random?limit=${limit}`
    );
    return response.data.data;
  } catch (error) {
    console.error("Error fetching featured products:", error);
    return [];
  }
};

/**
 * Fetches products for the Try-On Studio. THIS IS THE ENDPOINT WE ARE FIXING.
 */
export const getStudioItems = async (
  searchTerm: string = ""
): Promise<Product[]> => {
  try {
    const query = searchTerm ? `?search=${encodeURIComponent(searchTerm)}` : "";
    const response = await apiClient.get<{ data: Product[] }>(
      `/products/studio${query}`
    );
    return response.data.data;
  } catch (error) {
    console.error("Error fetching studio items:", error);
    return [];
  }
};
