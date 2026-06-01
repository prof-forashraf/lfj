  // src/components/landing/FeaturedProducts.tsx
  import React from 'react';import ProductImage from '@/components/ui/ProductImage';  import { Card, CardContent } from '@/components/ui/card';
  import { Button } from '@/components/ui/button';
  import { AffiliateProduct } from '@/services/affiliateProductService';
  import { Sparkles, Star, Heart, ExternalLink } from 'lucide-react';
  import { Link } from 'react-router-dom';

  interface FeaturedProductsProps {
    products: AffiliateProduct[];
  }

  const FeaturedProducts: React.FC<FeaturedProductsProps> = ({ products }) => {
    return (
      <section id="featured-collections" className="section-padding bg-gradient-to-br from-background to-muted/20">
        <div className="container mx-auto px-6">
          {/* Section Header */}
          <div className="text-center mb-16">
            <div className="inline-block px-4 py-2 bg-primary/10 text-primary text-sm font-medium rounded-full border border-primary/20 mb-4">
              ✨ Featured Collection
            </div>
            <h2 className="text-4xl md:text-5xl font-playfair font-bold heading-elegant mb-4">
              Handpicked Treasures
            </h2>
            <p className="text-lg md:text-xl text-elegant max-w-2xl mx-auto font-cormorant">
              Discover our carefully curated selection of the most exquisite pieces, chosen for their exceptional beauty and craftsmanship
            </p>
          </div>

          {/* Products Grid */}
          <div className="product-grid mb-12">
            {products.map((product) => (
              <Card key={product.id} className="card-elegant group h-full flex flex-col">
                {/* Wishlist Button */}
                <div className="absolute top-4 right-4 z-10 transition-all duration-300">
                  <Button
                    variant="ghost"
                    size="icon"
                    className="h-9 w-9 bg-background/90 backdrop-blur-sm hover:bg-primary hover:text-primary-foreground rounded-full"
                  >
                    <Heart className="w-4 h-4" />
                  </Button>
                </div>

                <CardContent className="p-0 flex-grow flex flex-col">
                  {/* Product Image */}
                  <ProductImage
                    src={product.image_url || 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=400&h=400&fit=crop'}
                    alt={product.name}
                    fallbackSrc="/images/placeholder.svg"
                    ratio={1}
                    showViewer={true}
                    className="rounded-t-xl"
                  />

                  {/* Product Info */}
                  <div className="p-6 flex-grow flex flex-col justify-between">
                    <div>
                      <h3 className="text-lg font-medium font-inter text-foreground group-hover:text-primary transition-colors duration-300 line-clamp-2 mb-3">
                        {product.name}
                      </h3>
                      
                      {/* ✅ CORRECTED RATING SECTION */}
                      {product.rating && (
                        <div className="flex items-center space-x-1 mb-4">
                          {Array.from({ length: 5 }, (_, i) => (
                            <Star 
                              key={i} 
                              className={`w-4 h-4 ${i < Math.floor(product.rating ?? 0) ? 'text-yellow-400 fill-current' : 'text-gray-300'}`} 
                            />
                          ))}
                          <span className="text-sm text-muted-foreground ml-2">({product.rating.toFixed(1)})</span>
                        </div>
                      )}
                    </div>

                    {/* CTA */}
                    <div className="space-y-4">
                      <Button 
                        asChild 
                        className="w-full bg-gradient-to-r from-primary to-primary/90 hover:from-primary/90 hover:to-primary text-primary-foreground font-medium rounded-lg hover:shadow-lg transition-all duration-300 group"
                      >
                        <a href={product.affiliate_url || '#'} target="_blank" rel="noopener sponsored">
                          <Sparkles className="w-4 h-4 mr-2 group-hover:animate-sparkle" />
                          Shop Now
                          <ExternalLink className="w-4 h-4 ml-2 group-hover:translate-x-0.5 transition-transform" />
                        </a>
                      </Button>
                    </div>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>

          {/* View All Products CTA */}
          <div className="text-center">
            <Button 
              asChild 
              size="lg" 
              className="btn-outline-elegant group"
            >
              <Link to="/shop">
                View All Collections
                <span className="ml-2 group-hover:translate-x-1 transition-transform">→</span>
              </Link>
            </Button>
          </div>
        </div>
      </section>
    );
  };

  export default FeaturedProducts;
