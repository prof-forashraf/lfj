import { useState, useEffect, useCallback } from 'react';
import { toast } from 'sonner';

const WISHLIST_KEY = 'jewellery_wishlist';

export interface WishlistItem {
  id: number;
  name: string;
  image_url: string | null;
  affiliate_url: string | null;
}

export const useWishlist = () => {
  const [wishlistIds, setWishlistIds] = useState<number[]>([]);
  const [items, setItems] = useState<WishlistItem[]>([]);

  useEffect(() => {
    try {
      const storedIds = localStorage.getItem(WISHLIST_KEY);
      const storedItems = localStorage.getItem(WISHLIST_KEY + '_items');
      if (storedIds) setWishlistIds(JSON.parse(storedIds));
      if (storedItems) setItems(JSON.parse(storedItems));
    } catch (error) {
      console.error("Failed to parse wishlist from localStorage", error);
    }
  }, []);

  const saveToStorage = (ids: number[], itemsList: WishlistItem[]) => {
    localStorage.setItem(WISHLIST_KEY, JSON.stringify(ids));
    localStorage.setItem(WISHLIST_KEY + '_items', JSON.stringify(itemsList));
  };

  const toggleWishlist = useCallback((itemId: number, itemData?: Omit<WishlistItem, 'id'>) => {
    setWishlistIds(prev => {
      const exists = prev.includes(itemId);
      const newIds = exists ? prev.filter(id => id !== itemId) : [...prev, itemId];
      
      setItems(prevItems => {
        const newItems = exists
          ? prevItems.filter(i => i.id !== itemId)
          : [...prevItems, { id: itemId, ...itemData } as WishlistItem];
        saveToStorage(newIds, newItems);
        return newItems;
      });

      if (!exists) toast.success("Added to wishlist!");
      else toast.info("Removed from wishlist");
      
      return newIds;
    });
  }, []);

  const removeFromWishlist = useCallback((itemId: number) => {
    setWishlistIds(prev => {
      const newIds = prev.filter(id => id !== itemId);
      setItems(prevItems => {
        const newItems = prevItems.filter(i => i.id !== itemId);
        saveToStorage(newIds, newItems);
        return newItems;
      });
      toast.info("Removed from wishlist");
      return newIds;
    });
  }, []);

  const clearWishlist = useCallback(() => {
    setWishlistIds([]);
    setItems([]);
    localStorage.removeItem(WISHLIST_KEY);
    localStorage.removeItem(WISHLIST_KEY + '_items');
    toast.info("Wishlist cleared");
  }, []);

  const isInWishlist = useCallback((itemId: number) => wishlistIds.includes(itemId), [wishlistIds]);

  return { wishlist: wishlistIds, items, toggleWishlist, removeFromWishlist, clearWishlist, isInWishlist };
};
