
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
  BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import SEOMetaTags from '@/components/blog/SEOMetaTags';
import { jewelleryService } from '@/services/jewelleryService';
import { getPlaceholderImageUrl } from '@/lib/imageUrl';
import AffiliateProductCard from '@/components/shop/AffiliateProductCard';

const COLLECTION_META: Record<
  string,
  { title: string; subtitle: string; description: string; theme: string }
> = {
  featured: {
    title: 'Gift-ready favorites',
    subtitle: 'Handpicked designs for every celebration',
    description:
      'Exceptional jewelry curated for gifting, special occasions and meaningful moments.',
    theme: 'featured',
  },
  'new-arrivals': {
    title: 'New arrivals',
    subtitle: 'Fresh styles just added',
    description:
      'Discover the latest jewelry pieces selected for their modern style and effortless shine.',
    theme: 'new-arrivals',
  },
  studio: {
    title: 'Studio Edit',
    subtitle: 'Designer-inspired jewelry collections',
    description:
      'A premium edit of unique pieces styled for modern luxury and everyday elegance.',
    theme: 'studio',
  },
};

const ShopCollection: React.FC = () => {
  const { slug } = useParams<{ slug: string }>();
  const normalizedSlug = slug?.toLowerCase() || 'featured';
  const isStudioCollection = normalizedSlug === 'studio';
  const meta = COLLECTION_META[normalizedSlug] || {
    title: normalizedSlug.replace(/-/g, ' ').replace(/\b\w/g, (char) => char.toUpperCase()),
    subtitle: 'Curated jewelry collection',
    description: 'Explore our carefully selected pieces in this collection.',
    theme: normalizedSlug,
  };

  const {
    data: products = [],
    isLoading,
    isError,
  } = useQuery({
    queryKey: ['products', 'collection', normalizedSlug],
    queryFn: () => jewelleryService.getProductsByCollection(normalizedSlug),
    enabled: !!normalizedSlug,
  });

  if (isError) {
    toast.error('Failed to load collection data. Please try again later.');
  }

  const handleProductClick = (amazonUrl?: string | null) => {
    if (amazonUrl) {
      window.open(amazonUrl, '_blank', 'noopener,noreferrer');
    } else {
      toast.info('This is an affiliate site. Product links will direct to Amazon.');
    }
  };

  return (
    <>
      <SEOMetaTags
        title={`${meta.title} | Latest Fashion Jewellery`}
        description={meta.description}
        keywords={`jewelry collection, ${normalizedSlug}, fashion jewelry, curated pieces`}
      />

      <main className="mt-20">
        <section className="bg-gradient-to-br from-rose-50 via-white to-slate-50 py-16">
          <div className="container mx-auto px-6">
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
                    <BreadcrumbPage>{meta.title}</BreadcrumbPage>
                  </BreadcrumbItem>
                </BreadcrumbList>
              </Breadcrumb>
            </div>

            <div className="grid gap-8 lg:grid-cols-[1.3fr_0.7fr] items-center">
              <div>
                <span className="inline-flex rounded-full bg-rose-100 px-3 py-1 text-xs uppercase tracking-[0.2em] text-rose-700">
                  {meta.title}
                </span>
                <h1 className="mt-6 text-4xl md:text-5xl font-bold tracking-tight text-navy-900">
                  {meta.subtitle}
                </h1>
                <p className="mt-5 max-w-2xl text-lg text-slate-600">
                  {meta.description}
                </p>
                <div className="mt-8 flex flex-wrap gap-3">
                  <Button asChild>
                    <Link to="/shop">Back to shop</Link>
                  </Button>
                  <Button variant="outline" asChild>
                    <Link to="/shop/collection/new-arrivals">See new arrivals</Link>
                  </Button>
                </div>

                {isStudioCollection && (
                  <div className="mt-10 rounded-3xl border border-rose-200 bg-rose-50 p-6 shadow-sm">
                    <p className="text-sm uppercase tracking-[0.3em] text-rose-600 mb-3">Studio Edit</p>
                    <h2 className="text-2xl font-semibold text-slate-900 mb-3">
                      Discover handcrafted polish and editorial design.
                    </h2>
                    <p className="text-slate-600">
                      The Studio Edit is our curated design capsule of statement-making jewelry, created to feel premium, polished, and highly collectible.
                    </p>
                    <div className="mt-4 inline-flex items-center gap-3">
                      <span className="rounded-full bg-rose-100 px-2 py-1 text-xs uppercase tracking-[0.24em] text-rose-700">
                        Curated collection
                      </span>
                      <span className="text-sm text-slate-500">
                        This is a curated shop collection spotlighting editorial pieces; for the live virtual experience, visit the Try-On Studio.
                      </span>
                    </div>
                    <div className="mt-6 flex flex-wrap gap-3">
                      <Button asChild>
                        <Link to="/jewellery-studio">Visit Virtual Try-On</Link>
                      </Button>
                      <Button variant="outline" asChild>
                        <Link to="/shop/collection/featured">Browse Featured</Link>
                      </Button>
                    </div>
                  </div>
                )}
              </div>

              <div className="grid gap-4 sm:grid-cols-2">
                {['Signature', 'Luxury', 'Everyday', 'Minimal'].map((label) => (
                  <Card key={label} className="overflow-hidden border border-slate-200 bg-white shadow-sm">
                    <div className="p-6">
                      <p className="text-sm uppercase tracking-[0.18em] text-slate-400">{label}</p>
                      <h3 className="mt-4 text-xl font-semibold text-slate-900">{label} picks</h3>
                    </div>
                  </Card>
                ))}
              </div>
            </div>
          </div>
        </section>

        <section className="container mx-auto px-6 py-16">
          <div className="flex items-center justify-between mb-10 gap-4">
            <div>
              <p className="text-sm uppercase tracking-[0.2em] text-slate-500">Shop collection</p>
              <h2 className="mt-3 text-3xl md:text-4xl font-bold text-navy-900">
                {meta.title}
              </h2>
            </div>
            <div className="text-right text-sm text-slate-500">
              <p>{products.length} products available</p>
            </div>
          </div>

          {isLoading ? (
            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
              {Array.from({ length: 8 }).map((_, index) => (
                <div key={index} className="animate-pulse rounded-3xl bg-slate-200 h-[360px]" />
              ))}
            </div>
          ) : products.length > 0 ? (
            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
              {products.map((product) => (
                <AffiliateProductCard
                  key={product.asin || product.id}
                  product={product}
                  onClick={() => handleProductClick(product.affiliate_url)}
                />
              ))}
            </div>
          ) : (
            <div className="rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-12 text-center">
              <div className="mx-auto mb-6 inline-flex h-16 w-16 items-center justify-center rounded-full bg-rose-100 text-rose-600 text-3xl">
                ✨
              </div>
              <h3 className="text-2xl font-semibold text-slate-900 mb-3">
                {isStudioCollection ? 'Studio Edit is being refreshed' : 'Collection not ready yet'}
              </h3>
              <p className="text-slate-600 mb-8 max-w-2xl mx-auto">
                {isStudioCollection
                  ? 'Our Studio Edit is being updated with a new set of designer-inspired jewelry. Check back soon for curated statement pieces.'
                  : 'We are preparing the best pieces for this collection. Browse our featured selections or explore other categories while you wait.'}
              </p>
              <div className="flex flex-wrap justify-center gap-3">
                <Button asChild>
                  <Link to="/shop">Return to Shop</Link>
                </Button>
                <Button variant="outline" asChild>
                  <Link to="/jewellery-studio">Explore Try-On Studio</Link>
                </Button>
                <Button variant="outline" asChild>
                  <Link to="/shop/collection/featured">Featured Gifts</Link>
                </Button>
              </div>
            </div>
          )}
        </section>

        <section className="bg-slate-950 text-white py-16">
          <div className="container mx-auto px-6">
            <h2 className="text-3xl font-semibold mb-6">Explore related collections</h2>
            <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
              {[
                { title: 'Featured', link: '/shop/collection/featured' },
                { title: 'New Arrivals', link: '/shop/collection/new-arrivals' },
                { title: 'Studio Edit', link: '/shop/collection/studio' },
              ].map((item) => (
                <Card key={item.title} className="overflow-hidden border border-white/10 bg-slate-900 p-6 shadow-lg transition hover:-translate-y-1">
                  <div className="mb-4 text-sm uppercase tracking-[0.24em] text-rose-300">{item.title}</div>
                  <h3 className="text-2xl font-semibold text-white mb-3">{item.title} collection</h3>
                  <p className="text-slate-300 mb-5">
                    Browse the latest {item.title.toLowerCase()} pieces curated for every style.
                  </p>
                  <Button asChild>
                    <Link to={item.link}>{item.title} gallery</Link>
                  </Button>
                </Card>
              ))}
            </div>
          </div>
        </section>
      </main>
    </>
  );
};

export default ShopCollection;
