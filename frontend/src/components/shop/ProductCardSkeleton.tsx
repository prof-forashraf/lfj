//app/components/shop/ProductCardSkeleton.tsx
import React from "react";
import { Card } from "@/components/ui/card"; // Assuming Card is enough for the skeleton structure
import { AspectRatio } from "@/components/ui/aspect-ratio";
import { Skeleton } from "@/components/ui/skeleton";

const ProductCardSkeleton: React.FC = () => {
  return (
    <Card className="flex flex-col animate-pulse overflow-hidden rounded-xl">
      <AspectRatio ratio={1} className="bg-gray-200 dark:bg-gray-700">
        {/* No actual img needed for skeleton */}
      </AspectRatio>
      <div className="p-4">
        <Skeleton className="h-5 w-3/4 mb-2" /> {/* Product Name */}
        <Skeleton className="h-5 w-1/2 mb-3" />{" "}
        {/* Maybe a short description line */}
        <Skeleton className="h-9 w-full" /> {/* Button */}
      </div>
    </Card>
  );
};

export default ProductCardSkeleton;
