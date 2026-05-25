import React, { useState } from 'react';
import { AdvancedSearch } from '@/components/AdvancedSearch';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import SEOMetaTags from '@/components/blog/SEOMetaTags';

interface SearchResult {
  id: number;
  name: string;
  affiliate_url: string | null;
  image_url: string | null;
  notes: string | null;
  category?: { id: number; name: string; slug: string };
  price?: number;
}

interface SearchFilters {
  category?: string;
  minPrice?: number;
  maxPrice?: number;
  material?: string;
  gemstone?: string;
  itemType?: string;
  featured?: boolean;
  sort?: string;
}

interface SearchFacets {
  [key: string]: string[] | undefined;
  categories?: string[];
  materials?: string[];
  gemstones?: string[];
  item_types?: string[];
  price_ranges?: string[];
}

const AdvancedSearchPage: React.FC = () => {
  const [results, setResults] = useState<SearchResult[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [query, setQuery] = useState('');
  const [facets, setFacets] = useState<SearchFacets>({});
  const [suggestions, setSuggestions] = useState<any[]>([]);

  const handleSearch = async (searchQuery: string, filters: SearchFilters = {}) => {
    setLoading(true);
    setError(null);
    setQuery(searchQuery);

    try {
      const params = new URLSearchParams({
        q: searchQuery,
        page: '1',
      });

      if (filters.category) params.set('category', filters.category);
      if (filters.minPrice !== undefined) params.set('min_price', String(filters.minPrice));
      if (filters.maxPrice !== undefined) params.set('max_price', String(filters.maxPrice));
      if (filters.material) params.set('material', filters.material);
      if (filters.gemstone) params.set('gemstone', filters.gemstone);
      if (filters.itemType) params.set('item_type', filters.itemType);
      if (filters.featured) params.set('featured', '1');
      if (filters.sort) params.set('sort', filters.sort);

      const response = await fetch(`/api/search/advanced?${params.toString()}`);
      const data = await response.json();

      setResults(data.data || []);
      setFacets(data.facets || {});
      setSuggestions(data.suggestions || []);
    } catch (err) {
      setError('Unable to perform search at this time. Please try again later.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      <SEOMetaTags
        title="Advanced Search | Latest Fashion Jewellery"
        description="Search jewellery using AI-powered recommendations, filters, and smart suggestions for faster discovery."
        keywords="jewellery search, advanced search, AI search, jewellery filters, product discovery"
      />
      <main className="py-16 bg-slate-50 min-h-screen">
        <div className="container mx-auto px-4">
          <div className="max-w-5xl mx-auto">
            <h1 className="text-4xl md:text-5xl font-bold text-slate-900 mb-4">
              Advanced Jewellery Search
            </h1>
            <p className="text-lg text-slate-600 mb-10">
              Discover products with smart search, AI suggestions, and filters tailored for fine jewellery.
            </p>

            <AdvancedSearch
              onSearch={handleSearch}
              facets={facets}
              searchSuggestions={suggestions}
            />

            <section className="mt-12 space-y-6">
              <Card>
                <CardHeader>
                  <CardTitle>
                    {query ? `Search results for "${query}"` : 'Search results will appear here'}
                  </CardTitle>
                </CardHeader>
                <CardContent>
                  {loading && <p className="text-slate-500">Loading results...</p>}
                  {error && <p className="text-red-600">{error}</p>}

                  {!loading && !error && results.length === 0 && query && (
                    <p className="text-slate-500">No matching products found.</p>
                  )}

                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {results.map((product) => (
                      <article key={product.id} className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div className="flex items-start gap-4">
                          <img
                            src={product.image_url || '/images/placeholder.svg'}
                            alt={product.name}
                            className="h-24 w-24 rounded-3xl object-cover"
                          />
                          <div className="space-y-2">
                            <h2 className="text-xl font-semibold text-slate-900">{product.name}</h2>
                            <div className="flex flex-wrap gap-2 items-center">
                              {product.category?.name && (
                                <Badge>{product.category.name}</Badge>
                              )}
                              {product.price !== undefined && (
                              <Badge variant="secondary">
                                ${typeof product.price === 'string' ? parseFloat(product.price).toFixed(2) : product.price?.toFixed?.(2)}
                              </Badge>
                            )}
                            </div>
                          </div>
                        </div>
                        {product.notes && <p className="mt-4 text-slate-600">{product.notes}</p>}
                        {product.affiliate_url && (
                          <a
                            href={product.affiliate_url}
                            target="_blank"
                            rel="noreferrer"
                            className="mt-4 inline-block text-sm font-semibold text-blue-600 hover:text-blue-800"
                          >
                            View on affiliate site
                          </a>
                        )}
                      </article>
                    ))}
                  </div>
                </CardContent>
              </Card>
            </section>
          </div>
        </div>
      </main>
    </>
  );
};

export default AdvancedSearchPage;
