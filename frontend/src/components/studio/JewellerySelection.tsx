// src/components/studio/JewellerySelection.tsx

import React, { useState, useMemo } from "react";
import { Card, CardContent } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Input } from "@/components/ui/input";
import { ScrollArea } from "@/components/ui/scroll-area";
import { AspectRatio } from "@/components/ui/aspect-ratio";
import { Skeleton } from "@/components/ui/skeleton";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import ProductImage from "@/components/ui/ProductImage";
import { cn } from "@/lib/utils";
import { motion, AnimatePresence } from "framer-motion";
import {
  Heart,
  Star,
  Filter,
  Grid,
  List,
  SortAsc,
  SortDesc,
  Search,
  Sparkles,
} from "lucide-react";
import { useWishlist } from "../../hooks/useWishlist";
import { Product } from "@/services/productService";

interface JewellerySelectionProps {
  availableItems: Product[];
  onSelect: (jewellery: Product) => void;
  onPreview: (jewellery: Product | null) => void;
  isLoading: boolean;
  searchTerm: string;
  onSearchChange: (value: string) => void;
}

type SortOption = "name" | "featured" | "recent";
type ViewModeType = "grid" | "list";

const JewellerySelection: React.FC<JewellerySelectionProps> = ({
  availableItems,
  onSelect,
  onPreview,
  isLoading,
  searchTerm,
  onSearchChange,
}) => {
  const [activeCategory, setActiveCategory] = useState("all");
  const [activeStyle, setActiveStyle] = useState("all");
  const [filterMode, setFilterMode] = useState<"all" | "favorites">("all");
  const [sortBy, setSortBy] = useState<SortOption>("featured");
  const [sortOrder, setSortOrder] = useState<"asc" | "desc">("desc");
  const [viewMode, setViewMode] = useState<ViewModeType>("grid");
  const [showFilters, setShowFilters] = useState(false);

  const { wishlist, toggleWishlist } = useWishlist();

  const { uniqueCategories, uniqueStyles, filteredItems, totalItemsCount } =
    useMemo(() => {
      const categories = [
        "all",
        ...Array.from(new Set(availableItems.map((item) => item.item_type))),
      ];
      const styles = [
        "all",
        ...Array.from(
          new Set(availableItems.flatMap((item) => item.tags || []))
        ),
      ];
      const sourceItems =
        filterMode === "favorites"
          ? availableItems.filter((item) => wishlist.includes(item.id))
          : availableItems;
      let items = sourceItems.filter((item) => {
        const matchesCategory =
          activeCategory === "all" || item.item_type === activeCategory;
        const matchesStyle =
          activeStyle === "all" ||
          (item.tags && item.tags.includes(activeStyle));
        return matchesCategory && matchesStyle;
      });
      items = items.sort((a, b) => {
        let comparison = 0;
        switch (sortBy) {
          case "name":
            comparison = a.name.localeCompare(b.name);
            break;
          case "featured":
            comparison =
              ((b as any).is_featured ? 1 : 0) -
              ((a as any).is_featured ? 1 : 0);
            break;
          case "recent":
            comparison = b.id - a.id;
            break;
        }
        return sortOrder === "asc" ? comparison : -comparison;
      });
      return {
        uniqueCategories: categories,
        uniqueStyles: styles,
        filteredItems: items,
        totalItemsCount: sourceItems.length,
      };
    }, [
      availableItems,
      activeCategory,
      activeStyle,
      filterMode,
      wishlist,
      sortBy,
      sortOrder,
    ]);

  const toggleSort = () =>
    setSortOrder((prev) => (prev === "asc" ? "desc" : "asc"));
  const resetFilters = () => {
    onSearchChange("");
    setActiveCategory("all");
    setActiveStyle("all");
    setFilterMode("all");
    setSortBy("featured");
    setSortOrder("desc");
  };

  if (isLoading) {
    return (
      <div className="space-y-4 p-4">
        <Skeleton className="h-10 w-full" />
        <Skeleton className="h-10 w-full" />
        <div className="grid grid-cols-2 gap-3 pt-4">
          {Array.from({ length: 6 }).map((_, i) => (
            <Skeleton key={i} className="h-32 w-full" />
          ))}
        </div>
      </div>
    );
  }

  return (
    <div className="h-full flex flex-col gap-4">
      {/* --- Filter & Control Section (Fixed Height) --- */}
      <div className="flex-shrink-0 space-y-4">
        <div className="relative">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
          <Input
            type="text"
            placeholder="Search jewellery..."
            value={searchTerm}
            onChange={(e) => onSearchChange(e.target.value)}
            className="pl-10"
          />
        </div>
        <div className="grid grid-cols-2 gap-2">
          <Button
            variant={filterMode === "all" ? "default" : "outline"}
            onClick={() => setFilterMode("all")}
            className="justify-start"
          >
            <Sparkles className="mr-2 h-4 w-4" /> All Items ({totalItemsCount})
          </Button>
          <Button
            variant={filterMode === "favorites" ? "default" : "outline"}
            onClick={() => setFilterMode("favorites")}
            className="relative justify-start"
          >
            <Heart className="mr-2 h-4 w-4" /> Favorites
            {wishlist.length > 0 && (
              <Badge
                variant="destructive"
                className="absolute -top-2 -right-2 px-1.5 py-0"
              >
                {wishlist.length}
              </Badge>
            )}
          </Button>
        </div>
        <div className="flex gap-2">
          <Button
            variant="outline"
            size="sm"
            onClick={() => setShowFilters(!showFilters)}
            className="flex-1"
          >
            <Filter className="h-4 w-4 mr-2" /> Filters
          </Button>
          <Button
            variant="outline"
            size="icon"
            onClick={() => setViewMode(viewMode === "grid" ? "list" : "grid")}
          >
            {viewMode === "grid" ? (
              <List className="h-4 w-4" />
            ) : (
              <Grid className="h-4 w-4" />
            )}
          </Button>
        </div>
        <AnimatePresence>
          {showFilters && (
            <motion.div
              initial={{ opacity: 0, height: 0 }}
              animate={{ opacity: 1, height: "auto" }}
              exit={{ opacity: 0, height: 0 }}
              className="space-y-4 border rounded-lg p-4 bg-gray-50 dark:bg-gray-800"
            >
              {/* Expanded Filters JSX */}
            </motion.div>
          )}
        </AnimatePresence>
      </div>

      {/* --- Scrollable Product List (Flexible Height) --- */}
      <ScrollArea className="flex-grow pr-3 -mr-3">
        <div className="text-sm text-gray-500 mb-2">
          {filteredItems.length} items found
        </div>
        <motion.div
          className={
            viewMode === "grid" ? "grid grid-cols-2 gap-3" : "space-y-3"
          }
          layout
        >
          <AnimatePresence>
            {filteredItems.map((item) => (
              <motion.div
                key={item.id}
                layout
                initial={{ opacity: 0, scale: 0.9 }}
                animate={{ opacity: 1, scale: 1 }}
                exit={{ opacity: 0, scale: 0.9 }}
                transition={{ type: "spring", stiffness: 500, damping: 30 }}
              >
                <Card
                  className="cursor-pointer hover:shadow-lg transition-all group relative overflow-hidden"
                  onClick={() => onSelect(item)}
                  onMouseEnter={() => onPreview(item)}
                  onMouseLeave={() => onPreview(null)}
                >
                  <CardContent
                    className={viewMode === "grid" ? "p-2" : "p-3 flex gap-3"}
                  >
                    <div className={viewMode === "grid" ? "" : "flex-shrink-0"}>
                      <AspectRatio
                        ratio={1 / 1}
                        className={viewMode === "grid" ? "" : "w-16"}
                      >
                        <ProductImage
                          src={item.image_url || undefined}
                          alt={item.name}
                          fallbackSrc="/images/placeholder.svg"
                          showViewer={false}
                          className="w-full h-full rounded bg-white"
                          ratio={1}
                        />
                      </AspectRatio>
                    </div>
                    <div
                      className={
                        viewMode === "grid" ? "mt-2" : "flex-1 min-w-0"
                      }
                    >
                      <h3
                        className={`font-medium text-dark-slate ${
                          viewMode === "grid" ? "text-xs truncate" : "text-sm"
                        }`}
                      >
                        {item.name}
                      </h3>
                    </div>
                    <Button
                      size="icon"
                      variant="ghost"
                      className={`absolute ${
                        viewMode === "grid"
                          ? "top-1 right-1 h-7 w-7"
                          : "top-2 right-2 h-8 w-8"
                      } rounded-full bg-white/80 hover:bg-white`}
                      onClick={(e) => {
                        e.stopPropagation();
                        toggleWishlist(item.id);
                      }}
                    >
                      <Heart
                        className={cn(
                          "h-4 w-4",
                          wishlist.includes(item.id)
                            ? "text-red-500 fill-current"
                            : "text-gray-500"
                        )}
                      />
                    </Button>
                  </CardContent>
                </Card>
              </motion.div>
            ))}
          </AnimatePresence>
        </motion.div>
        {!isLoading && filteredItems.length === 0 && (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            className="text-center p-12"
          >
            <div className="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
              <Search className="h-8 w-8 text-gray-400" />
            </div>
            <h3 className="text-lg font-medium text-gray-900 mb-2">
              No items found
            </h3>
            <p className="text-gray-500 mb-4">
              Try adjusting your search or filters.
            </p>
            <Button variant="outline" onClick={resetFilters}>
              Clear all filters
            </Button>
          </motion.div>
        )}
      </ScrollArea>
    </div>
  );
};

export default JewellerySelection;
