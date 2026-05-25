// src/components/blog/SimplifiedPostCard.tsx
import React from 'react';
import { Link } from 'react-router-dom';
import { Post as PostType } from '@/services/postService'; // Or your shared types file
import { Card, CardContent, CardFooter } from "@/components/ui/card";
import { AspectRatio } from '@/components/ui/aspect-ratio';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge'; // If you show categories/tags here
import { ArrowRight, CalendarDays as CalendarDaysIcon, Clock as ClockIcon } from 'lucide-react'; // Corrected imports

const SimplifiedPostCard: React.FC<{ post: PostType }> = ({ post }) => {
  // Helper to safely calculate reading time
  const calculateReadingTime = (content: string | null | undefined) => {
    if (!content) return 1;
    const wordsPerMinute = 200;
    const textContent = content.replace(/<[^>]*>?/gm, '');
    const wordCount = textContent.trim().split(/\s+/).filter(Boolean).length;
    return Math.max(1, Math.ceil(wordCount / wordsPerMinute));
  };

  return (
    <Link to={`/blog/${post.slug}`} className="group block h-full"> {/* Ensure Link takes full height if Card does */}
      <Card className="overflow-hidden h-full flex flex-col shadow-lg hover:shadow-xl transition-shadow duration-300 rounded-xl">
        {post.featured_image_url ? (
          <AspectRatio ratio={16 / 9} className="bg-gray-100 rounded-t-xl overflow-hidden">
            <img
              src={post.featured_image_url}
              alt={post.title}
              className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
             loading="lazy" width={800} height={600} onError={(event) => { event.currentTarget.src = "/images/placeholder.svg"; }} />
          </AspectRatio>
        ) : (
          <AspectRatio ratio={16 / 9} className="bg-gray-200 flex items-center justify-center rounded-t-xl">
            <span className="text-gray-400 text-sm">No Image</span>
          </AspectRatio>
        )}
        <CardContent className="p-5 flex flex-col flex-grow">
          {post.categories && post.categories.length > 0 && (
            <div className="mb-2 flex flex-wrap gap-1.5">
              {post.categories.slice(0, 1).map(cat => ( // Show only the first category for brevity
                <Badge key={cat.id} variant="secondary" className="text-xs bg-pink-100 text-pink-700">
                  {cat.name}
                </Badge>
              ))}
            </div>
          )}
          <h3 className="text-md lg:text-lg font-playfair font-semibold mb-1 group-hover:text-primary-gold transition-colors line-clamp-2 leading-snug text-dark-slate">
            {post.title}
          </h3>
          <p className="text-xs text-muted-foreground mb-3">
            {post.published_at_formatted}
            {/* <span className="mx-1">•</span>
            <span className="flex items-center text-xs">
              <ClockIcon className="mr-1 h-3 w-3" />
              {calculateReadingTime(post.content_html)} min read
            </span> */}
          </p>
          <p className="text-gray-600 text-sm line-clamp-3 flex-grow mb-3">{post.excerpt}</p>
        </CardContent>
        <CardFooter className="p-5 pt-0 mt-auto"> {/* Ensure footer is at the bottom */}
          <Button variant="link" size="sm" className="p-0 h-auto text-primary-gold self-start font-semibold group-hover:translate-x-1 transition-transform text-xs">
            Read More <ArrowRight size={14} className="ml-1" />
          </Button>
        </CardFooter>
      </Card>
    </Link>
  );
};

export default SimplifiedPostCard;