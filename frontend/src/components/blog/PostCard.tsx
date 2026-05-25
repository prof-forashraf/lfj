// src/components/blog/PostCard.tsx
import React from "react";
import { Link } from "react-router-dom";
import { Post as PostType } from "@/services/postService";
import {
  Card,
  CardContent,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { AspectRatio } from "@/components/ui/aspect-ratio";
import { Badge } from "@/components/ui/badge";
import { CalendarIcon, UserIcon, ImageIcon } from "lucide-react";
import { format } from "date-fns";

interface PostCardProps {
  post: PostType;
}

const PostCard: React.FC<PostCardProps> = ({ post }) => {
  const formattedDate = post.published_at_iso
    ? format(new Date(post.published_at_iso), "MMMM d, yyyy")
    : "Date not available";

  const primaryCategory =
    post.categories && post.categories.length > 0 ? post.categories[0] : null;

  return (
    <Card className="flex flex-col h-full overflow-hidden transition-shadow duration-300 border shadow-sm group hover:shadow-xl dark:border-gray-700/50">
      <CardHeader className="p-0">
        <Link
          to={`/blog/${post.slug}`}
          className="block overflow-hidden"
          aria-label={post.title}
        >
          <AspectRatio ratio={16 / 9} className="bg-muted">
            {post.featured_image_url ? (
              <img
                src={post.featured_image_url}
                alt={`${post.title} featured image`}
                className="object-cover w-full h-full transition-transform duration-500 group-hover:scale-105"
                loading="lazy"
                width={640}
                height={360}
                onError={(event) => {
                  event.currentTarget.src = "/images/placeholder.svg";
                }}
              />
            ) : (
              // ✅ IMPROVED: Better placeholder for missing images
              <div className="flex flex-col items-center justify-center w-full h-full text-gray-500 bg-gray-100 dark:bg-gray-800">
                <ImageIcon className="w-12 h-12 text-gray-400" />
                <span className="mt-2 text-xs">No Image Available</span>
              </div>
            )}
          </AspectRatio>
        </Link>
      </CardHeader>

      <CardContent className="p-6 flex-grow flex flex-col">
        {primaryCategory && (
          <Link
            to={`/blog?category=${primaryCategory.slug}`}
            className="inline-block mb-3"
            onClick={(e) => e.stopPropagation()}
          >
            {/* ✅ IMPROVED: Added hover/focus states for better UX */}
            <Badge
              variant="secondary"
              className="transition-colors hover:bg-primary hover:text-primary-foreground focus:ring-2 focus:ring-ring focus:ring-offset-2"
            >
              {primaryCategory.name}
            </Badge>
          </Link>
        )}
        <CardTitle className="mb-3 text-xl font-semibold leading-snug font-playfair">
          <Link
            to={`/blog/${post.slug}`}
            className="hover:text-primary transition-colors focus:outline-none focus:ring-2 focus:ring-ring rounded"
          >
            {post.title}
          </Link>
        </CardTitle>
        {/* ✅ IMPROVED: Added fallback for excerpt */}
        <p className="text-gray-600 dark:text-gray-400 line-clamp-3 font-lato text-ellipsis">
          {post.excerpt ||
            "No summary available for this post. Click to read the full article."}
        </p>
      </CardContent>

      <CardFooter className="p-6 pt-0 mt-auto text-sm text-gray-500 dark:text-gray-400">
        <div className="flex items-center w-full">
          <div className="flex items-center">
            <UserIcon className="w-4 h-4 mr-2" />
            {/* ✅ IMPROVED: Better fallback for author */}
            <span>{post.author?.name || "LFJ Staff"}</span>
          </div>
          <div className="flex items-center ml-auto">
            <CalendarIcon className="w-4 h-4 mr-2" />
            <time dateTime={post.published_at_iso || undefined}>
              {formattedDate}
            </time>
          </div>
        </div>
      </CardFooter>
    </Card>
  );
};

export default PostCard;
