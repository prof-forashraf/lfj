// src/components/skeletons/EventCardSkeleton.tsx
import React from 'react';
import { Card, CardContent, CardFooter, CardHeader } from "@/components/ui/card";
import { AspectRatio } from "@/components/ui/aspect-ratio";
import { Skeleton } from "@/components/ui/skeleton";

const EventCardSkeleton: React.FC = () => {
  return (
    <Card className="flex flex-col overflow-hidden animate-pulse h-full">
      <AspectRatio ratio={16 / 9} className="bg-gray-200 dark:bg-gray-700 rounded-t-lg" />
      <CardHeader className="pb-2 pt-4 px-4">
        <Skeleton className="h-4 w-20 mb-2 rounded-full" /> {/* Badge placeholder */}
        <Skeleton className="h-6 w-3/4 mb-1" /> {/* Title */}
        <Skeleton className="h-5 w-1/2" />   {/* Title line 2 or subtitle */}
      </CardHeader>
      <CardContent className="text-sm text-muted-foreground flex-grow p-4 pt-1">
        <Skeleton className="h-4 w-full mb-1.5" /> {/* Date placeholder */}
        <Skeleton className="h-4 w-2/3" />        {/* Location placeholder */}
      </CardContent>
      <CardFooter className="p-4">
        <Skeleton className="h-9 w-full rounded-md" /> {/* Button placeholder */}
      </CardFooter>
    </Card>
  );
};

export default EventCardSkeleton; // Make sure to export itو