// src/components/skeletons/EventDetailSkeletonContent.tsx
import React from 'react';
import { Skeleton } from "@/components/ui/skeleton";

const EventDetailSkeletonContent: React.FC = () => {
  return (
    <div className="bg-white py-12 md:py-16 animate-pulse"> {/* Page specific background */}
      <div className="container max-w-3xl mx-auto px-4">
        <article>
          {/* Back to All Events link skeleton */}
          <Skeleton className="h-5 w-32 mb-8 rounded" />

          {/* Header Skeletons */}
          <header className="mb-8 md:mb-10">
            <Skeleton className="h-6 w-24 mb-4 rounded-full" /> {/* Badge */}
            <Skeleton className="h-10 md:h-12 w-full mb-3 rounded" /> {/* Title line 1 */}
            <Skeleton className="h-10 md:h-12 w-3/4 mb-4 rounded" /> {/* Title line 2 */}
            
            <div className="flex flex-col sm:flex-row gap-x-6 gap-y-2 mt-4">
                <Skeleton className="h-5 w-48 mb-1 rounded" /> {/* Date info */}
                <Skeleton className="h-5 w-40 rounded" /> {/* Location info */}
            </div>
            <Skeleton className="h-4 w-1/2 mt-2 rounded" /> {/* Address line */}
          </header>

          {/* Featured Image Skeleton */}
          <Skeleton className="aspect-video w-full rounded-xl mb-8 md:mb-10" />

          {/* Content Skeletons */}
          <div className="space-y-3">
            {Array.from({ length: 3 }).map((_, i) => ( // Paragraph 1
                <React.Fragment key={`p1-${i}`}>
                    <Skeleton className="h-4 w-full rounded" />
                    <Skeleton className="h-4 w-full rounded" />
                    <Skeleton className="h-4 w-5/6 rounded mb-3" />
                </React.Fragment>
            ))}
             {Array.from({ length: 2 }).map((_, i) => ( // Paragraph 2
                <React.Fragment key={`p2-${i}`}>
                    <Skeleton className="h-4 w-full rounded" />
                    <Skeleton className="h-4 w-11/12 rounded mb-3" />
                </React.Fragment>
            ))}
          </div>

          {/* Event URL Button Skeleton */}
          <div className="mt-10 pt-8 border-t border-gray-200">
            <Skeleton className="h-12 w-48 rounded-lg" />
          </div>
        </article>
      </div>
    </div>
  );
};

export default EventDetailSkeletonContent;