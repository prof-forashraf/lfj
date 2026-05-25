import apiClient from "@/lib/apiClient";
import { TryOnItemFromApi, TryOnJewelleryItem } from "./tryOnService"; // We can reuse the types

// This function fetches the initial set of random items
export const getRandomTryOnItems = async (
  limit: number = 12
): Promise<TryOnItemFromApi[]> => {
  const response = await apiClient.get<{ data: TryOnItemFromApi[] }>(
    `/products/studio?limit=${limit}`
  );
  return response.data.data;
};

// This function fetches items based on a search query
export const searchTryOnItems = async (
  search: string
): Promise<TryOnItemFromApi[]> => {
  if (!search.trim()) return [];
  const response = await apiClient.get<{ data: TryOnItemFromApi[] }>(
    `/products/studio`,
    {
      params: { search },
    }
  );
  return response.data.data;
};
