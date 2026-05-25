
import React from 'react';
import { Link } from 'react-router-dom';
import { Card, CardContent } from '@/components/ui/card';
import { AspectRatio } from '@/components/ui/aspect-ratio';
import { ArrowRight, Clock } from 'lucide-react';
import { HoverCard, HoverCardContent, HoverCardTrigger } from '@/components/ui/hover-card';

interface BlogPost {
  id: number | string;
  title: string;
  slug: string;
  excerpt: string;
  featuredImage?: string;
  publishedAt: string;
  readingTime?: number;
  category?: string;
}

interface RelatedBlogsProps {
  posts: BlogPost[];
  currentPostId: number | string;
}

const RelatedBlogs: React.FC<RelatedBlogsProps> = ({ posts, currentPostId }) => {
  // Filter out current post and limit to max 4 related posts
  const relatedPosts = posts
    .filter(post => post.id !== currentPostId)
    .slice(0, 4);

  if (relatedPosts.length === 0) {
    return null;
  }

  return (
    <div className="mt-16 pt-8 border-t">
      <h3 className="text-2xl font-playfair font-medium mb-6">You May Also Like</h3>
      
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {relatedPosts.map((post) => (
          <Link to={`/blog/${post.slug}`} key={post.id}>
            <Card className="luxury-card hover-lift h-full">
              <CardContent className="p-0">
                <AspectRatio ratio={16/9}>
                  <img
                    src={post.featuredImage || 'https://images.unsplash.com/photo-1617038260897-43528e855mdb?q=80&w=600&auto=format&fit=crop'}
                    alt={post.title}
                    className="w-full h-full object-cover"
                   loading="lazy" width={800} height={600} onError={(event) => { event.currentTarget.src = "/images/placeholder.svg"; }} />
                </AspectRatio>
                
                <div className="p-4">
                  {post.category && (
                    <div className="mb-2">
                      <span className="text-xs uppercase tracking-wider text-gold-600 font-medium">{post.category}</span>
                    </div>
                  )}
                  
                  <HoverCard>
                    <HoverCardTrigger asChild>
                      <h4 className="font-playfair font-medium text-lg line-clamp-2 group-hover:text-gold-600 transition-colors">
                        {post.title}
                      </h4>
                    </HoverCardTrigger>
                    <HoverCardContent className="w-80">
                      <div className="space-y-2">
                        <h4 className="font-playfair font-medium">{post.title}</h4>
                        <p className="text-sm text-muted-foreground">{post.excerpt}</p>
                      </div>
                    </HoverCardContent>
                  </HoverCard>
                  
                  <div className="flex items-center justify-between mt-3 text-sm">
                    <div className="flex items-center text-muted-foreground">
                      <Clock className="mr-1 h-3 w-3" />
                      <span>{post.readingTime || 5} min read</span>
                    </div>
                    <span className="text-gold-600 flex items-center group">
                      Read More 
                      <ArrowRight size={14} className="ml-1 transition-transform group-hover:translate-x-1" />
                    </span>
                  </div>
                </div>
              </CardContent>
            </Card>
          </Link>
        ))}
      </div>
    </div>
  );
};

export default RelatedBlogs;
