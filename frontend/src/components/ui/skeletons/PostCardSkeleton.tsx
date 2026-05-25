// src/components/ui/skeletons/PostCardSkeleton.tsx
import React from 'react';
import { Card, CardContent, CardFooter, CardHeader } from "@/components/ui/card"; // Assuming you use these parts
import { AspectRatio } from "@/components/ui/aspect-ratio";
import { Skeleton } from "@/components/ui/skeleton";

const PostCardSkeleton: React.FC = () => {
  return (
    <Card className="overflow-hidden h-full flex flex-col animate-pulse"> {/* Add animate-pulse for effect */}
      <AspectRatio ratio={16 / 9} className="bg-gray-200 dark:bg-gray-700 rounded-t-xl">
        {/* Image placeholder, no actual Skeleton component needed inside if AspectRatio has bg color */}
      </AspectRatio>
      <CardContent className="p-5 flex flex-col flex-grow">
        {/* Category Badges Placeholder */}
        <div className="mb-2 flex flex-wrap gap-1.5">
          <Skeleton className="h-5 w-16 rounded-full" />
          <Skeleton className="h-5 w-20 rounded-full" />
        </div>

        {/* Date & Reading Time Placeholder */}
        <div className="flex items-center text-xs text-gray-500 space-x-3 mb-2">
          <Skeleton className="h-4 w-24" /> {/* Date */}
          <Skeleton className="h-4 w-16" /> {/* Reading Time */}
        </div>

        {/* Title Placeholder */}
        <Skeleton className="h-6 w-4/5 mb-2" /> {/* Title line 1 */}
        <Skeleton className="h-6 w-3/5 mb-3" /> {/* Title line 2 */}


        {/* Excerpt Placeholder */}
        <div className="space-y-1.5 flex-grow">
          <Skeleton className="h-4 w-full" />
          <Skeleton className="h-4 w-full" />
          <Skeleton className="h-4 w-5/6" />
        </div>
      </CardContent>
      <CardFooter className="p-5 pt-0">
        <Skeleton className="h-8 w-28 rounded-md" /> {/* Read More button placeholder */}
      </CardFooter>
    </Card>
  );
};

export default PostCardSkeleton;