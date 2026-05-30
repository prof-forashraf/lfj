
import React from 'react';
import { useParams, Link } from 'react-router-dom';
import { ChevronRight } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { useQuery } from '@tanstack/react-query';
import { toast } from 'sonner';
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
import { getPlaceholderImageUrl } from '@/lib/imageUrl';

const ShopCollection: React.FC = () => {
  const { slug } = useParams<{ slug: string }>();
  
  // Fetch collection details  
  const { data: products = [], isLoading: loadingProducts, isError: productsError } = useQuery({
    queryKey: ['products', 'collection', slug],
    queryFn: () => jewelleryService.getProductsByCollection(slug || ''),
    enabled: !!slug,
  });

  if (productsError) {
    toast.error("Failed to load collection data. Please try again later.");
  }

  const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
      minimumFractionDigits: 2
    }).format(price);
  };

  const handleProductClick = (amazonUrl?: string) => {
    if (amazonUrl) {
      window.open(amazonUrl, '_blank');
    } else {
      toast.info("This is an affiliate site. Product links will direct to Amazon.");
    }
  };

  return (
    <>
      <SEOMetaTags
        title={`${slug?.charAt(0).toUpperCase() + slug?.slice(1)} Collection | Latest Fashion Jewellery`}
        description={`Browse our ${slug} collection at Latest Fashion Jewellery. Find elegant and trendy pieces for every occasion.`}
        keywords={`jewelry collection, ${slug?.toLowerCase() || ''}, fashion jewelry, affordable luxury`}
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
                <BreadcrumbPage>{slug?.charAt(0).toUpperCase() + slug?.slice(1)}</BreadcrumbPage>
              </BreadcrumbItem>
            </BreadcrumbList>
          </Breadcrumb>
        </div>

        {/* Collection Header */}
        <div className="mb-10">
          <h1 className="text-3xl font-bold text-navy-800">{slug?.charAt(0).toUpperCase() + slug?.slice(1)} Collection</h1>
          <p className="text-gray-600 mt-2 max-w-2xl">Discover our curated selection of {slug} pieces.</p>
        </div>

        {/* Products Grid */}
        {loadingProducts ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            {Array.from({ length: 8 }).map((_, i) => (
              <div key={i} className="animate-pulse">
                <div className="bg-gray-200 aspect-square rounded-md mb-3"></div>
                <div className="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                <div className="h-4 bg-gray-200 rounded w-1/2"></div>
              </div>
            ))}
          </div>
        ) : products.length > 0 ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            {products.map((product) => (
                 <Card 
                   key={product.id} 
                   className="overflow-hidden group transition-all duration-300 hover:shadow-md border border-gray-100 cursor-pointer"
                   onClick={() => handleProductClick(product.affiliate_url)}
                 >
                   <div className="relative h-[250px] overflow-hidden">
                     <img
                       src={product.image_url || getPlaceholderImageUrl()}
                       alt={product.name}
                       className="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105"
                       loading="lazy"
                      width={800} height={600} onError={(event) => { event.currentTarget.src = "/images/placeholder.svg"; }} />
                     {product.is_featured && (
                       <span className="absolute top-2 right-2 bg-yellow-400 text-yellow-900 text-xs px-2 py-1 rounded-full">
                         Featured
                       </span>
                     )}
                   </div>
                   <CardContent className="p-4">
                     <h3 className="font-medium text-navy-800 line-clamp-2 mb-1 group-hover:text-teal-600 transition-colors">
                       {product.name}
                     </h3>
                     <p className="text-sm text-gray-600 line-clamp-2 mb-2">{product.notes || 'Premium quality jewelry piece'}</p>
                   </CardContent>
                 </Card>
            ))}
          </div>
        ) : (
          <div className="text-center py-16">
            <div className="text-4xl mb-4">🔍</div>
            <h3 className="text-xl font-semibold mb-2">No products found</h3>
            <p className="text-gray-600 mb-6">We couldn't find any products in this collection</p>
            <Button asChild>
              <Link to="/shop">Browse all products</Link>
            </Button>
          </div>
        )}

        {/* Related Categories */}
        <div className="mt-16">
          <h2 className="text-2xl font-bold text-navy-800 mb-6">Related Categories</h2>
          <div className="flex flex-wrap gap-3">
            {['Necklaces', 'Earrings', 'Bracelets', 'Rings', 'Watches'].map((cat) => (
              <Button 
                key={cat} 
                variant="outline"
                asChild
              >
                <Link to={`/shop/category/${cat.toLowerCase()}`}>
                  {cat} <ChevronRight className="ml-1 h-4 w-4" />
                </Link>
              </Button>
            ))}
          </div>
        </div>
      </main>

    </>
  );
};

export default ShopCollection;
