
import React from 'react';
import { Link } from 'react-router-dom';
import { Button } from '@/components/ui/button';
import { useQuery } from '@tanstack/react-query';
import { toast } from 'sonner';
import { getPlaceholderImageUrl } from '@/lib/imageUrl';
import { 
  Breadcrumb, 
  BreadcrumbItem, 
  BreadcrumbLink, 
  BreadcrumbList, 
  BreadcrumbPage, 
  BreadcrumbSeparator 
} from '@/components/ui/breadcrumb';
import SEOMetaTags from '@/components/blog/SEOMetaTags';
import { jewelleryService } from '@/services/jewelleryService';
import StandardCarousel from '@/components/ui/StandardCarousel';

const NewArrivals: React.FC = () => {
  // Fetch new arrival products
  const { data: products = [], isLoading, isError } = useQuery({
    queryKey: ['products', 'new-arrivals'],
    queryFn: () => jewelleryService.getProductsByCollection('new-arrivals'),
  });

  if (isError) {
    toast.error("Failed to load new arrivals. Please try again later.");
  }

  const handleProductClick = (affiliateUrl?: string) => {
    if (affiliateUrl) {
      window.open(affiliateUrl, '_blank');
    } else {
      toast.info("This is an affiliate site. Product links will direct to Amazon.");
    }
  };

  // Transform products for carousel
  const carouselItems = products.map((product) => ({
    id: product.id,
    image: product.image_url || getPlaceholderImageUrl(),
    title: product.name,
    description: product.notes,
    badge: 'New',
    onClick: () => handleProductClick(product.affiliate_url)
  }));

  return (
    <>
      <SEOMetaTags
        title="New Arrivals | Latest Fashion Jewellery"
        description="Discover the latest additions to our jewelry collection. Fresh styles and trending pieces just arrived at Latest Fashion Jewellery."
        keywords="new jewelry arrivals, latest jewelry, trending accessories, new fashion jewelry"
      />
      
      <main className="container mx-auto px-4 py-8 mt-20">
        <div className="mb-6">
          <Breadcrumb>
            <BreadcrumbList>
              <BreadcrumbItem>
                <BreadcrumbLink asChild>
                  <Link to="/">Home</Link>
                </BreadcrumbLink>
              </BreadcrumbItem>
              <BreadcrumbSeparator />
              <BreadcrumbItem>
                <BreadcrumbLink asChild>
                  <Link to="/shop">Shop</Link>
                </BreadcrumbLink>
              </BreadcrumbItem>
              <BreadcrumbSeparator />
              <BreadcrumbItem>
                <BreadcrumbPage>New Arrivals</BreadcrumbPage>
              </BreadcrumbItem>
            </BreadcrumbList>
          </Breadcrumb>
        </div>

        {/* Header */}
        <div className="mb-10 text-center">
          <h1 className="text-4xl font-bold text-navy-800 mb-4">New Arrivals</h1>
          <p className="text-gray-600 max-w-2xl mx-auto text-lg">
            Discover the latest additions to our curated jewelry collection. Fresh styles and trending pieces that define modern elegance.
          </p>
        </div>

        {/* Products Carousel */}
        {isLoading ? (
          <div className="py-8">
            <div className="animate-pulse">
              <div className="flex space-x-4 overflow-hidden">
                {Array.from({ length: 4 }).map((_, i) => (
                  <div key={i} className="flex-shrink-0 w-64">
                    <div className="bg-gray-200 aspect-square rounded-md mb-3"></div>
                    <div className="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                    <div className="h-4 bg-gray-200 rounded w-1/2"></div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        ) : products.length > 0 ? (
          <StandardCarousel
            items={carouselItems}
            theme="light"
            showPricing={true}
            autoPlay={true}
            className="mb-12"
          />
        ) : (
          <div className="text-center py-16">
            <div className="text-4xl mb-4">✨</div>
            <h3 className="text-xl font-semibold mb-2">New arrivals coming soon!</h3>
            <p className="text-gray-600 mb-6">We're constantly updating our collection with fresh styles</p>
            <Button asChild>
              <Link to="/shop">Browse current collection</Link>
            </Button>
          </div>
        )}

        {/* Call to Action */}
        <div className="mt-16 bg-gradient-to-r from-teal-500 to-blue-600 rounded-lg p-8 text-center text-white">
          <h2 className="text-2xl font-bold mb-4">Stay Updated</h2>
          <p className="mb-6 max-w-2xl mx-auto">
            Be the first to know about our latest arrivals and exclusive offers. Join our community of jewelry lovers.
          </p>
          <Button variant="secondary" asChild>
            <Link to="/contact">Subscribe to Updates</Link>
          </Button>
        </div>
      </main>
    </>
  );
};

export default NewArrivals;
