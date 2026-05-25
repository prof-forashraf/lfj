// src/components/blog/PostCardSkeleton.tsx
import React from "react";
import { Card, CardContent, CardFooter } from "@/components/ui/card";
import { AspectRatio } from "@/components/ui/aspect-ratio";
import { Skeleton } from "@/components/ui/skeleton";

const PostCardSkeleton: React.FC = () => {
  return (
    <Card className="flex flex-col h-full overflow-hidden animate-pulse">
      {/* Matches the image container */}
      <AspectRatio ratio={16 / 9} className="bg-muted" />

      {/* Matches the CardContent structure */}
      <CardContent className="p-6 flex-grow flex flex-col">
        <Skeleton className="h-5 w-1/3 mb-4 rounded-md" />{" "}
        {/* Category Badge */}
        <div className="space-y-2 mb-4">
          <Skeleton className="h-6 w-full" /> {/* Title line 1 */}
          <Skeleton className="h-6 w-3/4" /> {/* Title line 2 */}
        </div>
        <div className="space-y-2 flex-grow">
          <Skeleton className="h-4 w-full" /> {/* Excerpt line 1 */}
          <Skeleton className="h-4 w-full" /> {/* Excerpt line 2 */}
          <Skeleton className="h-4 w-5/6" /> {/* Excerpt line 3 */}
        </div>
      </CardContent>

      {/* Matches the CardFooter structure */}
      <CardFooter className="p-6 pt-0 flex items-center justify-between">
        <div className="flex items-center space-x-2">
          <Skeleton className="h-5 w-5 rounded-full" /> {/* Author Icon */}
          <Skeleton className="h-4 w-16" /> {/* Author Name */}
        </div>
        <div className="flex items-center space-x-2">
          <Skeleton className="h-5 w-5 rounded-full" /> {/* Calendar Icon */}
          <Skeleton className="h-4 w-24" /> {/* Date */}
        </div>
      </CardFooter>
    </Card>
  );
};

export default PostCardSkeleton;
