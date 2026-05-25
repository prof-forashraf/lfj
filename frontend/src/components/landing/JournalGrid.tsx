
// src/components/landing/JournalGrid.tsx
import React from 'react';
import { Link } from 'react-router-dom';
import { Card, CardContent } from '@/components/ui/card';
import { Post } from '@/services/postService'; // Your existing type
import { format } from 'date-fns';

interface JournalGridProps {
  posts: Post[];
}

const JournalGrid: React.FC<JournalGridProps> = ({ posts }) => {
  if (!posts || posts.length === 0) return null;

  const firstPost = posts[0];
  const otherPosts = posts.slice(1);

  return (
    <section className="py-16 md:py-24 bg-soft-blue">
      <div className="container mx-auto px-6">
        <h2 className="text-4xl font-playfair text-center mb-12 text-luxury-navy">From The Journal</h2>
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
          {/* Featured Post */}
          <Link to={`/blog/${firstPost.slug}`} className="group block">
            <Card className="border-gray-200 shadow-sm hover:shadow-xl transition-shadow duration-300 rounded-lg overflow-hidden">
              <img src={firstPost.featured_image_url || 'https://images.unsplash.com/photo-1599395359489-94b62ced2a55?q=80&w=800&auto=format&fit=crop'} alt={`${firstPost.title} featured image`} className="w-full h-80 object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" decoding="async" width={800} height={400} onError={(event) => { event.currentTarget.src = '/images/placeholder.svg'; }} />
              <div className="p-6 bg-white">
                <p className="text-sm text-luxury-gold font-semibold mb-2">{firstPost.tags?.[0]?.name || 'Featured'}</p>
                <h3 className="text-2xl font-playfair font-semibold text-luxury-navy mb-3 group-hover:text-luxury-gold transition-colors">{firstPost.title}</h3>
                <p className="text-gray-600 font-inter line-clamp-2 mb-4">{firstPost.excerpt}</p>
                <p className="text-sm text-gray-500 font-inter">{format(new Date(firstPost.published_at_iso || Date.now()), 'MMMM d, yyyy')}</p>
              </div>
            </Card>
          </Link>
          {/* Other Posts */}
          <div className="space-y-6">
            {otherPosts.map(post => (
              <Link to={`/blog/${post.slug}`} key={post.id} className="group block">
                 <Card className="border-gray-200 shadow-sm hover:shadow-lg transition-shadow duration-300 rounded-lg bg-white">
                    <CardContent className="flex items-center gap-6 p-4">
                        <img src={post.featured_image_url || 'https://images.unsplash.com/photo-1599395359489-94b62ced2a55?q=80&w=400&auto=format&fit=crop'} alt={`${post.title} featured image`} className="w-32 h-32 object-cover rounded-md flex-shrink-0" loading="lazy" decoding="async" width={128} height={128} onError={(event) => { event.currentTarget.src = '/images/placeholder.svg'; }} />
                        <div>
                            <p className="text-sm text-luxury-gold font-semibold mb-1">{post.tags?.[0]?.name || 'Insights'}</p>
                            <h4 className="text-lg font-playfair font-semibold text-luxury-navy group-hover:text-luxury-gold transition-colors">{post.title}</h4>
                            <p className="text-sm text-gray-500 font-inter mt-2">{format(new Date(post.published_at_iso || Date.now()), 'MMMM d, yyyy')}</p>
                        </div>
                    </CardContent>
                 </Card>
              </Link>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
};

export default JournalGrid;
