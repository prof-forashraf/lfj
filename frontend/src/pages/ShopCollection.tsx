
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
  const collectionName = slug
    ? slug
        .split('-')
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ')
    : 'Collection';

  const collectionIntroText = `Our ${collectionName.toLowerCase()} collection is curated for timeless style, thoughtful gifting and everyday polish. Each piece has been selected to work well on its own or layered with your current favourites. When choosing, consider the occasion and your preferred metal tone so the jewellery feels personal and effortless. If you're not sure where to start, browse related categories or read our styling notes for a smoother choice.`;
  
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
          <h1 className="text-3xl font-bold text-navy-800">{collectionName} Collection</h1>
          <p className="text-gray-600 mt-2 max-w-2xl leading-relaxed">
            {collectionIntroText}
          </p>
          <p className="text-sm text-gray-500 mt-4 max-w-2xl">
            Perfect for special moments, elegant everyday style and anyone who loves jewellery with refined presence.
          </p>
          <div className="mt-4 text-sm text-gray-500 max-w-2xl">
            We partner with trusted retailers for secure checkout and reliable delivery. Our selections are chosen for design, value and everyday wearability.
          </div>
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
                       alt={product.name || `${collectionName} jewellery item`}
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
                     <p className="text-sm text-gray-600 line-clamp-2 mb-2">{product.notes || 'A thoughtfully selected jewellery piece'}</p>
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

        <div className="mb-16 rounded-3xl bg-rose-50 border border-rose-100 p-8">
          <h2 className="text-2xl font-semibold text-navy-800 mb-3">Want a more polished edit?</h2>
          <p className="text-gray-600 mb-6 max-w-3xl">
            Explore matching collections and curated categories for gifts, date nights and elevated daily wear.
          </p>
          <div className="flex flex-wrap gap-3">
            {['Necklaces', 'Earrings', 'Bracelets', 'Rings'].map((cat) => (
              <Button key={cat} variant="outline" asChild>
                <Link to={`/shop/category/${cat.toLowerCase()}`}>{cat}</Link>
              </Button>
            ))}
          </div>
        </div>

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
          <p className="mt-6 text-sm text-gray-600 max-w-3xl">
            Need styling ideas? <Link to="/blog" className="text-primary hover:underline">Read our jewellery guides</Link> and match your next look with the right category.
          </p>
        </div>

        <section className="mt-16 bg-gray-50 rounded-3xl border border-gray-200 p-8">
          <h2 className="text-2xl font-semibold text-navy-800 mb-4">How this collection works</h2>
          <div className="grid gap-6 md:grid-cols-3">
            <div>
              <p className="font-semibold text-gray-800 mb-2">Why these pieces are here</p>
              <p className="text-sm text-gray-600 leading-relaxed">
                Each product is chosen for its balance of style, wearability and thoughtful detail.
              </p>
            </div>
            <div>
              <p className="font-semibold text-gray-800 mb-2">What happens at checkout?</p>
              <p className="text-sm text-gray-600 leading-relaxed">
                You are redirected to the retailer's site for secure payment and delivery handling.
              </p>
            </div>
            <div>
              <p className="font-semibold text-gray-800 mb-2">What to do next</p>
              <p className="text-sm text-gray-600 leading-relaxed">
                Compare the collection with related categories and choose the style that feels most personal.
              </p>
            </div>
          </div>
        </section>
      </main>

    </>
  );
};

export default ShopCollection;
