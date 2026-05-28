import React, { useState } from "react";
import { Link } from "react-router-dom";
import { ArrowRight, Search, X } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { useQuery } from "@tanstack/react-query";
import { getPublicImageUrl, getPlaceholderImageUrl } from '@/lib/imageUrl';
import SEOMetaTags from "@/components/blog/SEOMetaTags";
import { jewelleryService, Category } from "@/services/jewelleryService";
import StandardCarousel from "@/components/ui/StandardCarousel";
import ProductCardSkeleton from "@/components/shop/ProductCardSkeleton";
import {
  useFeaturedAffiliateProducts,
  useSearchProducts,
} from "@/hooks/useAffiliateProducts";
import { useDebounce } from "@/hooks/useDebounce";
import ProductGrid from "@/components/shop/ProductGrid";
import { toast } from "sonner";

const Shop: React.FC = () => {
  const [searchQuery, setSearchQuery] = useState("");
  const debouncedSearchQuery = useDebounce(searchQuery, 400);

  const { data: categories = [], isLoading: isLoadingCategories } = useQuery<
    Category[]
  >({
    queryKey: ["categories"],
    queryFn: jewelleryService.getCategories,
  });

  const { data: featuredProducts = [], isLoading: isLoadingFeatured } =
    useFeaturedAffiliateProducts(8);

  const {
    data: searchResults,
    isLoading: isLoadingSearch,
    isError: isSearchError,
  } = useSearchProducts(debouncedSearchQuery);

  const showSearchResults = debouncedSearchQuery.length >= 2;

  const handleViewCollections = () => {
    document
      .getElementById("collections")
      ?.scrollIntoView({ behavior: "smooth" });
  };

  const handleProductClick = (amazonUrl?: string | null) => {
    if (amazonUrl) {
      window.open(amazonUrl, "_blank", "noopener,noreferrer");
    } else {
      toast.info(
        "This is an affiliate site. Product links will direct to Amazon."
      );
    }
  };

  const carouselItems = featuredProducts.map((product) => ({
    id: product.id,
    image:
      product.image_url ||
      getPlaceholderImageUrl(),
    title: product.name,
    description: product.notes,
    badge: product.is_featured ? "Featured" : undefined,
    rating: product.rating,
    onClick: () => handleProductClick(product.affiliate_url),
  }));

  return (
    <>
      <SEOMetaTags
        title="Shop | Latest Fashion Jewellery"
        description="Discover our curated collection of exquisite fashion jewellery. From trendy pieces to timeless classics, find the perfect accessory for any occasion."
        keywords="jewelry shop, fashion jewelry, necklaces, earrings, bracelets, rings, accessories"
      />
      <main className="mt-20">
        {/* --- HERO SECTION WITH SEARCH --- */}
        <section className="relative h-auto min-h-[500px] md:min-h-[600px] lg:min-h-[700px] overflow-hidden bg-gray-50 flex items-center justify-center py-16">
          <div className="absolute inset-0 bg-gradient-to-br from-rose-400 via-fuchsia-500 to-indigo-600"></div>
          <div className="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_50%_50%,_rgba(255,255,255,0.1)_1px,_transparent_1px)] bg-[length:40px_40px]"></div>

          <div className="relative z-10 h-full flex items-center justify-center px-4">
            <div className="text-center max-w-4xl mx-auto">
              <h1 className="text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-playfair font-bold mb-6">
                <span className="bg-gradient-to-r from-white via-yellow-100 to-white bg-clip-text text-transparent drop-shadow-lg">
                  Curated Elegance
                </span>
              </h1>
              <p className="text-xl md:text-2xl lg:text-3xl text-white/95 mb-6 font-light tracking-wide">
                Find jewellery that feels personal, polished and easy to wear.
              </p>

              <p className="text-sm text-white/80 max-w-2xl mx-auto mb-8">
                Thoughtfully curated Amazon jewellery with the clarity and confidence you need for gifting and everyday style.
              </p>

              <div className="max-w-2xl mx-auto mb-10">
                <div className="relative">
                  <Search className="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                  <Input
                    type="text"
                    placeholder="Search for earrings, gold necklaces, bracelets..."
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                    className="w-full h-16 pl-12 pr-12 text-lg bg-white/90 border-2 border-transparent focus:border-rose-300 focus:ring-rose-300 rounded-full shadow-lg"
                  />
                  {searchQuery && (
                    <Button
                      variant="ghost"
                      size="icon"
                      className="absolute right-4 top-1/2 -translate-y-1/2 h-8 w-8 rounded-full"
                      onClick={() => setSearchQuery("")}
                    >
                      <X className="h-5 w-5 text-gray-500" />
                    </Button>
                  )}
                </div>
              </div>

              <div className="flex flex-col sm:flex-row justify-center gap-4 mb-8">
                <Button
                  size="lg"
                  onClick={handleViewCollections}
                  className="bg-white/20 backdrop-blur-md border border-white/30 text-white hover:bg-white/30 hover:scale-105 transition-all duration-300 px-8 py-4 text-lg font-semibold"
                >
                  Explore Collections
                </Button>
                <Button
                  variant="outline"
                  size="lg"
                  className="bg-transparent backdrop-blur-md border-2 border-white/50 text-white hover:bg-white hover:text-primary-gold hover:scale-105 transition-all duration-300 px-8 py-4 text-lg font-semibold"
                  asChild
                >
                  <Link to="/shop/collection/new-arrivals">New Arrivals</Link>
                </Button>
              </div>

              <div className="flex flex-wrap justify-center gap-6 text-white/80 text-sm md:text-base">
                <div className="flex items-center gap-2">
                  <div className="w-2 h-2 bg-yellow-300 rounded-full"></div>
                  <span>Premium Quality</span>
                </div>
                <div className="flex items-center gap-2">
                  <div className="w-2 h-2 bg-pink-300 rounded-full"></div>
                  <span>Curated Selection</span>
                </div>
                <div className="flex items-center gap-2">
                  <div className="w-2 h-2 bg-blue-300 rounded-full"></div>
                  <span>Trusted Partners</span>
                </div>
              </div>
            </div>
          </div>
          <div className="absolute bottom-0 left-0 right-0">
            <svg
              className="w-full h-16 md:h-20"
              viewBox="0 0 1440 120"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L0,320Z"
                fill="rgb(249 250 251)"
              />
            </svg>
          </div>
        </section>

        {/* --- SEARCH RESULTS SECTION --- */}
        {showSearchResults && (
          <section id="search-results" className="py-16 bg-white">
            <div className="container mx-auto px-6">
              <h2 className="text-3xl md:text-4xl font-bold text-navy-800 mb-2 text-center">
                Search Results for "{debouncedSearchQuery}"
              </h2>
              <p className="text-gray-600 max-w-2xl mx-auto text-center mb-10">
                {isSearchError
                  ? "Something went wrong. Please try again later."
                  : `Showing products related to your search.`}
              </p>
              <ProductGrid
                isLoading={isLoadingSearch}
                products={searchResults?.data}
                onProductClick={handleProductClick}
              />
            </div>
          </section>
        )}

        {!showSearchResults && (
          <>
            {/* --- SHOP BY OCCASION --- */}
            <section className="py-16 bg-gradient-to-r from-rose-50 via-white to-amber-50">
              <div className="container mx-auto px-6">
                <div className="text-center max-w-3xl mx-auto mb-12">
                  <p className="text-sm uppercase tracking-[0.28em] font-semibold text-rose-500 mb-3">Style destination</p>
                  <h2 className="text-3xl md:text-4xl font-bold text-navy-800 mb-4">Shop by occasion</h2>
                  <p className="text-gray-600 leading-relaxed">
                    Discover jewellery that works beautifully for gifts, everyday elegance and special celebrations.
                  </p>
                </div>
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                  {[
                    { label: 'Everyday elegance', slug: 'necklaces' },
                    { label: 'Gift-worthy pieces', slug: 'rings' },
                    { label: 'Statement accents', slug: 'earrings' },
                    { label: 'Layered luxury', slug: 'bracelets' },
                  ].map((item) => (
                    <Link
                      key={item.slug}
                      to={`/shop/category/${item.slug}`}
                      className="block rounded-3xl border border-slate-200 bg-white p-8 text-center transition hover:shadow-lg"
                    >
                      <p className="text-sm uppercase tracking-[0.2em] text-rose-500 mb-3">{item.label}</p>
                      <p className="text-lg font-semibold text-navy-800">Explore</p>
                    </Link>
                  ))}
                </div>
              </div>
            </section>

            {/* --- OUR COLLECTIONS SECTION --- */}
            <section id="collections" className="py-16 bg-gray-50">
              <div className="container mx-auto px-6">
                <h2 className="text-3xl md:text-4xl font-bold text-navy-800 mb-2 text-center">
                  Our Collections
                </h2>
                <p className="text-gray-600 max-w-2xl mx-auto text-center mb-10">
                  Browse our carefully curated collections designed to match every occasion and style preference.
                </p>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                  {isLoadingCategories
                    ? Array.from({ length: 3 }).map((_, i) => (
                        <div
                          key={i}
                          className="animate-pulse bg-gray-200 rounded-lg h-[300px]"
                        ></div>
                      ))
                    : categories.map((category) => (
                        <Link
                          to={`/shop/category/${category.slug}`}
                          key={category.id}
                        >
                          <Card className="overflow-hidden h-full group hover:shadow-lg transition-shadow duration-300 border-none">
                            <div className="relative h-[300px] overflow-hidden p-8">
                              <img
                                src={
                                  getPublicImageUrl(category.image) ||
                                  getPlaceholderImageUrl()
                                }
                                alt={category.name}
                                className="w-full h-full object-cover rounded-md transition-transform duration-700 group-hover:scale-110"
                                loading="lazy"
                               width={800} height={600} onError={(event) => { event.currentTarget.src = "/images/placeholder.svg"; }} />
                              <div className="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                                <div className="p-6 w-full">
                                  <h3 className="text-2xl font-bold text-white mb-2">
                                    {category.name}
                                  </h3>
                                  {category.description && (
                                    <p className="text-white/80 mb-4">
                                      {category.description}
                                    </p>
                                  )}
                                  <div className="flex items-center justify-between">
                                    <span className="text-white/90 text-sm">
                                      {category.productCount || 0} Products
                                    </span>
                                    <div className="bg-white/20 rounded-full p-2 transform translate-x-0 group-hover:translate-x-2 transition-transform">
                                      <ArrowRight className="h-4 w-4 text-white" />
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </Card>
                        </Link>
                      ))}
                </div>
              </div>
            </section>

            {/* --- FEATURED PRODUCTS SECTION --- */}
            <section className="py-16 bg-white">
              <div className="container mx-auto px-6">
                <div className="flex flex-col md:flex-row justify-between items-center mb-10">
                  <div>
                    <h2 className="text-3xl md:text-4xl font-bold text-navy-800 mb-2">
                      Featured Products
                    </h2>
                    <p className="text-gray-600 max-w-xl">
                      Our handpicked selection of stunning jewellery pieces chosen for everyday polish and thoughtful gifting.
                    </p>
                  </div>
                  <div className="mt-4 md:mt-0">
                    <Button asChild>
                      <Link
                        to="/shop/collection/featured"
                        className="flex items-center gap-2"
                      >
                        View All <ArrowRight size={16} />
                      </Link>
                    </Button>
                  </div>
                </div>
                {isLoadingFeatured ? (
                  <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    {Array.from({ length: 4 }).map((_, i) => (
                      <ProductCardSkeleton key={i} />
                    ))}
                  </div>
                ) : carouselItems.length > 0 ? (
                  <StandardCarousel
                    items={carouselItems}
                    theme="elegant"
                    showPricing={false}
                    autoPlay={false}
                  />
                ) : (
                  <div className="text-center py-10 bg-gray-50 rounded-lg">
                    <p className="text-gray-500">
                      No featured products found at this time.
                    </p>
                  </div>
                )}
              </div>
            </section>
          </>
        )}
      </main>
    </>
  );
};

export default Shop;
