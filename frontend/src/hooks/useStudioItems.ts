import { useQuery, keepPreviousData } from "@tanstack/react-query";
import {
  getRandomTryOnItems,
  searchTryOnItems,
} from "@/services/studioService";
import { TryOnItemFromApi, TryOnJewelleryItem } from "@/services/tryOnService";
import { getPublicImageUrl } from '@/lib/imageUrl';


export const useStudioItems = (debouncedSearchTerm: string) => {
  return useQuery<TryOnJewelleryItem[], Error>({
    queryKey: ["studioItems", debouncedSearchTerm],
    queryFn: async () => {
      let rawItems: TryOnItemFromApi[];

      if (debouncedSearchTerm) {
        rawItems = await searchTryOnItems(debouncedSearchTerm);
      } else {
        rawItems = await getRandomTryOnItems(12);
      }

      if (!Array.isArray(rawItems)) return [];
      return rawItems.map((item) => ({
        ...item,
        name: item.name,
        image_url: getPublicImageUrl(item.image_url),
        try_on_image: getPublicImageUrl(item.try_on_image),
        tags: [],
      }));
    },
    staleTime: 1000 * 60 * 5,
    placeholderData: keepPreviousData,
  });
};
