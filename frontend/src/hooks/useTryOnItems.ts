// src/hooks/useTryOnItems.ts
import { useQuery, UseQueryResult, UseQueryOptions } from '@tanstack/react-query';
import { getTryOnItems, TryOnItemFromApi, TryOnJewelleryItem } from '@/services/tryOnService';
import { getPublicImageUrl } from '@/lib/imageUrl';

const addSimulatedTags = (item: TryOnItemFromApi): string[] => {
    const nameLower = (item.name || '').toLowerCase();
    const tags = [];
    if (nameLower.includes('bohemian') || nameLower.includes('feather')) tags.push('Boho');
    if (nameLower.includes('minimalist') || nameLower.includes('delicate')) tags.push('Minimalist');
    if (nameLower.includes('gemstone') || nameLower.includes('artisan')) tags.push('Artisan');
    if (nameLower.includes('elegant') || nameLower.includes('pearl')) tags.push('Classic');
    if (tags.length === 0) tags.push('Modern');
    return tags;
}

export const useTryOnItems = (
  options?: Omit<UseQueryOptions<TryOnJewelleryItem[], Error>, 'queryKey' | 'queryFn' | 'select'>
): UseQueryResult<TryOnJewelleryItem[], Error> => {
  return useQuery({
    queryKey: ['tryOnItems'],
    queryFn: async () => (await getTryOnItems()).data, // Fetch the raw data array
    staleTime: 1000 * 60 * 10,
    
    // All transformations now happen here.
    select: (data: TryOnItemFromApi[]): TryOnJewelleryItem[] => {
      return data.map(item => ({
        ...item,
        name: item.name || 'Untitled Product',
        image_url: getPublicImageUrl(item.image_url),
        try_on_image: getPublicImageUrl(item.try_on_image),
        tags: addSimulatedTags(item)
      }));
    },
    ...options,
  });
}; 