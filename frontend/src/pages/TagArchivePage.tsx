import React from 'react';
import { Link, useParams } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import BlogLayout from '@/components/layout/BlogLayout';
import SimplifiedPostCard from '@/components/blog/SimplifiedPostCard';
import PostCardSkeleton from '@/components/blog/PostCardSkeleton';
import SeoHead from '@/components/seo/SeoHead';
import Breadcrumbs from '@/components/seo/Breadcrumbs';
import { Button } from '@/components/ui/button';
import { api } from '@/lib/apiClient';
import { usePosts } from '@/hooks/usePosts';
import { ArrowLeft } from 'lucide-react';

const TagArchivePage: React.FC = () => {
  const { tagSlug } = useParams<{ tagSlug: string }>();
  const { data: tag, isLoading, isError } = useQuery({
    queryKey: ['tag', tagSlug],
    queryFn: () => api.get(`/tags/${tagSlug}`),
    enabled: Boolean(tagSlug),
  });
  const { data: postsResponse, isLoading: postsLoading } = usePosts(
    { tag: tagSlug, perPage: 9 },
    { enabled: Boolean(tagSlug) }
  );

  if (isLoading) {
    return (
      <BlogLayout>
        <div className="container mx-auto px-4 py-16 grid grid-cols-1 md:grid-cols-3 gap-8">
          {Array.from({ length: 6 }).map((_, index) => <PostCardSkeleton key={index} />)}
        </div>
      </BlogLayout>
    );
  }

  if (isError || !tag) {
    return (
      <BlogLayout>
        <div className="flex flex-col justify-center items-center min-h-[50vh] text-center px-4">
          <h1 className="text-3xl font-bold font-playfair">Tag Not Found</h1>
          <p className="mt-2 text-muted-foreground">The tag you are looking for does not exist.</p>
          <Button asChild variant="outline" className="mt-6">
            <Link to="/blog"><ArrowLeft className="mr-2 h-4 w-4" /> Back to Blog</Link>
          </Button>
        </div>
      </BlogLayout>
    );
  }

  const crumbs = [
    { label: 'Home', href: '/' },
    { label: 'Blog', href: '/blog' },
    { label: 'Tags', href: '/tags' },
    { label: tag.name, href: `/tag/${tag.slug}` },
  ];

  return (
    <BlogLayout>
      <SeoHead seo={tag.seo} breadcrumbs={crumbs} />
      <div className="container mx-auto px-4 py-12 md:py-16">
        <Breadcrumbs crumbs={crumbs} />
        <header className="text-center mb-12">
          <p className="text-sm font-semibold text-primary-gold uppercase tracking-wider mb-2">Tag</p>
          <h1 className="text-4xl lg:text-5xl font-playfair font-bold text-dark-slate">{tag.name}</h1>
        </header>

        {postsLoading ? (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {Array.from({ length: 6 }).map((_, index) => <PostCardSkeleton key={index} />)}
          </div>
        ) : postsResponse?.data?.length ? (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
            {postsResponse.data.map((post) => <SimplifiedPostCard key={post.id} post={post} />)}
          </div>
        ) : (
          <div className="text-center py-16 border border-dashed rounded-lg">
            <h2 className="text-xl font-medium">No Posts Found</h2>
            <p className="mt-1 text-sm text-gray-500">There are no posts with this tag yet.</p>
          </div>
        )}
      </div>
    </BlogLayout>
  );
};

export default TagArchivePage;
