import React from 'react';
import SimplifiedPostCard from '@/components/blog/SimplifiedPostCard';
import PostCardSkeleton from '@/components/ui/skeletons/PostCardSkeleton';
import { usePosts } from '@/hooks/usePosts';

const RelatedPosts = ({ tag, exclude }) => {
  const { data, isLoading } = usePosts(
    { tag, exclude, limit: 3, perPage: 3 },
    { enabled: Boolean(tag && exclude) }
  );

  const posts = data?.data || [];

  if (isLoading) {
    return (
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {Array.from({ length: 3 }).map((_, index) => <PostCardSkeleton key={index} />)}
      </div>
    );
  }

  if (!posts.length) return null;

  return (
    <section className="mt-16 pt-12 border-t border-gray-200">
      <h2 className="text-2xl md:text-3xl font-playfair font-bold text-dark-slate mb-8">Related Posts</h2>
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {posts.map((post) => <SimplifiedPostCard key={post.id} post={post} />)}
      </div>
    </section>
  );
};

export default RelatedPosts;
