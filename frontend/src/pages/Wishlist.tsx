import React from 'react';
import { Link } from 'react-router-dom';
import { Heart, ShoppingBag, Trash2, ExternalLink } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import SEOMetaTags from '@/components/blog/SEOMetaTags';
import { useWishlist } from '@/hooks/useWishlist';

const Wishlist: React.FC = () => {
  const { items, removeFromWishlist, clearWishlist } = useWishlist();

  return (
    <>
      <SEOMetaTags
        title="My Wishlist | Latest Fashion Jewellery"
        description="View your saved jewellery pieces. Keep track of your favourite items and shop when you're ready."
        keywords="wishlist, saved items, fashion jewellery favourites"
      />

      <div className="min-h-screen pt-28 pb-16 bg-gradient-to-b from-muted/30 to-background">
        <div className="container mx-auto px-6">
          {/* Header */}
          <div className="text-center mb-12">
            <div className="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary text-sm font-medium rounded-full border border-primary/20 mb-4">
              <Heart className="w-4 h-4" />
              My Wishlist
            </div>
            <h1 className="text-4xl md:text-5xl font-playfair font-bold heading-elegant mb-4">
              Saved Pieces
            </h1>
            <p className="text-lg text-muted-foreground max-w-2xl mx-auto font-cormorant">
              Your curated collection of favourite jewellery pieces
            </p>
          </div>

          {items.length === 0 ? (
            <div className="text-center py-20">
              <div className="w-24 h-24 bg-muted/50 rounded-full flex items-center justify-center mx-auto mb-6">
                <Heart className="w-12 h-12 text-muted-foreground/50" />
              </div>
              <h2 className="text-2xl font-playfair font-semibold text-foreground mb-3">
                Your wishlist is empty
              </h2>
              <p className="text-muted-foreground mb-8 max-w-md mx-auto">
                Browse our collections and tap the heart icon on any piece you love to save it here.
              </p>
              <Button asChild size="lg" className="btn-elegant">
                <Link to="/shop">
                  <ShoppingBag className="w-5 h-5 mr-2" />
                  Explore Collections
                </Link>
              </Button>
            </div>
          ) : (
            <>
              <div className="flex justify-between items-center mb-8">
                <p className="text-muted-foreground">
                  {items.length} {items.length === 1 ? 'item' : 'items'} saved
                </p>
                <Button variant="outline" size="sm" onClick={clearWishlist} className="text-destructive hover:text-destructive">
                  <Trash2 className="w-4 h-4 mr-2" />
                  Clear All
                </Button>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                {items.map((item) => (
                  <Card key={item.id} className="card-elegant group h-full flex flex-col">
                    <CardContent className="p-0 flex-grow flex flex-col">
                      <div className="relative aspect-square overflow-hidden rounded-t-2xl bg-muted/30">
                        <img
                          src={item.image_url || 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=400&h=400&fit=crop'}
                          alt={item.name}
                          className="w-full h-full object-contain p-6 group-hover:scale-110 transition-transform duration-700"
                         loading="lazy" width={800} height={600} onError={(event) => { event.currentTarget.src = "/images/placeholder.svg"; }} />
                        <Button
                          variant="ghost"
                          size="icon"
                          onClick={() => removeFromWishlist(item.id)}
                          className="absolute top-3 right-3 h-9 w-9 bg-background/90 backdrop-blur-sm hover:bg-destructive hover:text-destructive-foreground rounded-full"
                        >
                          <Trash2 className="w-4 h-4" />
                        </Button>
                      </div>
                      <div className="p-6 flex-grow flex flex-col justify-between">
                        <h3 className="text-lg font-medium text-foreground group-hover:text-primary transition-colors line-clamp-2 mb-4">
                          {item.name}
                        </h3>
                        <Button asChild className="w-full bg-gradient-to-r from-primary to-primary/90 text-primary-foreground rounded-xl">
                          <a href={item.affiliate_url || '#'} target="_blank" rel="noopener sponsored">
                            Shop Now
                            <ExternalLink className="w-4 h-4 ml-2" />
                          </a>
                        </Button>
                      </div>
                    </CardContent>
                  </Card>
                ))}
              </div>
            </>
          )}
        </div>
      </div>
    </>
  );
};

export default Wishlist;
