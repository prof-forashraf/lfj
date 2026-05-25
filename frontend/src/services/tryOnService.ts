// src/services/tryOnService.ts
import apiClient from '@/lib/apiClient';

// This is the shape of the data that comes directly from your API
export interface TryOnItemFromApi {
  id: number;
  asin: string;
  name: string | null;
  affiliate_url: string;
  notes: string;
  image_url: string | null;      // The raw path, e.g., "products/image.jpg"
  try_on_image: string | null; // The raw path, e.g., "try_on_images/B0CC1XRMFT.jpg"
  item_type: string;
  is_featured: boolean;
  status: string;
}

// This interface can be removed or kept if used elsewhere. The hook will create the final object.
export interface TryOnJewelleryItem extends TryOnItemFromApi {
    tags: string[];
}

interface TryOnItemsApiResponse {
    data: TryOnItemFromApi[];
}

export const getTryOnItems = async (): Promise<TryOnItemsApiResponse> => {
  const url = '/products/studio';
  try {
    const response = await apiClient.get<TryOnItemsApiResponse>(url);
    // ✅ SIMPLIFIED: Return the raw API response directly.
    // The hook will now handle all data transformation.
    return response.data;
  } catch (error: any) {
    console.error(`TRYON SERVICE: Error fetching try-on items.`, error);
    throw error;
  }
};