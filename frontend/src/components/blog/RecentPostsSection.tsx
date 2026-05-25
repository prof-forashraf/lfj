import React from "react";
import { usePosts } from "@/hooks/usePosts"; // We assume you have this hook
import SimplifiedPostCard from "./SimplifiedPostCard";
import PostCardSkeleton from "./PostCardSkeleton";
import { motion } from "framer-motion";

interface RecentPostsSectionProps {
  title: string;
  limit?: number;
}

const RecentPostsSection: React.FC<RecentPostsSectionProps> = ({
  title,
  limit = 4,
}) => {
  // Fetch the latest posts. Adjust params as needed (e.g., to fetch featured posts).
  const {
    data: postsResponse,
    isLoading,
    isError,
  } = usePosts({ page: 1, perPage: limit });

  const posts = postsResponse?.data;

  // Don't render the section if there's an error or no posts after loading.
  if (!isLoading && (isError || !posts || posts.length === 0)) {
    return null;
  }

  return (
    <section>
      <h2 className="text-4xl font-playfair font-bold text-dark-slate text-center mb-12">
        {title}
      </h2>
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
        {isLoading
          ? Array.from({ length: limit }).map((_, i) => (
              <PostCardSkeleton key={i} />
            ))
          : posts?.map((post, index) => (
              <motion.div
                key={post.id}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: index * 0.1 }}
              >
                <SimplifiedPostCard post={post} />
              </motion.div>
            ))}
      </div>
    </section>
  );
};

export default RecentPostsSection;
