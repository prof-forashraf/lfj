import React, { useState, useEffect, useCallback } from 'react';
import { Search, Filter, X, SlidersHorizontal } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Slider } from '@/components/ui/slider';
import { Checkbox } from '@/components/ui/checkbox';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';

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

interface SearchSuggestion {
  type: 'related' | 'categories' | 'price_ranges';
  title: string;
  items: string[];
}

interface AdvancedSearchProps {
  onSearch: (query: string, filters: SearchFilters) => void;
  placeholder?: string;
  showFilters?: boolean;
  facets?: Record<string, string[] | undefined>;
  searchSuggestions?: SearchSuggestion[];
}

export const AdvancedSearch: React.FC<AdvancedSearchProps> = ({
  onSearch,
  placeholder = "Search for jewelry...",
  showFilters = true,
  facets: initialFacets = {},
  searchSuggestions = []
}) => {
  const [query, setQuery] = useState('');
  const [filters, setFilters] = useState<SearchFilters>({});
  const [suggestions, setSuggestions] = useState<SearchSuggestion[]>(searchSuggestions);
  const [autocompleteItems, setAutocompleteItems] = useState<string[]>([]);
  const [showSuggestions, setShowSuggestions] = useState(false);
  const [showFiltersPanel, setShowFiltersPanel] = useState(false);
  const [facets, setFacets] = useState<any>(initialFacets);

  React.useEffect(() => {
    setFacets(initialFacets);
  }, [initialFacets]);

  React.useEffect(() => {
    setSuggestions(searchSuggestions);
  }, [searchSuggestions]);

  // Debounced search
  const debouncedSearch = useCallback(
    debounce((searchQuery: string, searchFilters: SearchFilters) => {
      onSearch(searchQuery, searchFilters);
    }, 300),
    [onSearch]
  );

  // Autocomplete
  const fetchAutocomplete = useCallback(
    debounce(async (searchQuery: string) => {
      if (searchQuery.length < 2) {
        setAutocompleteItems([]);
        return;
      }

      try {
        const response = await fetch(`/api/search/autocomplete?q=${encodeURIComponent(searchQuery)}`);
        const data = await response.json();
        setAutocompleteItems(data.suggestions || []);
      } catch (error) {
        console.error('Autocomplete error:', error);
      }
    }, 200),
    []
  );

  // Handle search input
  const handleQueryChange = (value: string) => {
    setQuery(value);
    setShowSuggestions(true);
    fetchAutocomplete(value);
  };

  // Handle search submission
  const handleSearch = () => {
    debouncedSearch(query, filters);
    setShowSuggestions(false);
  };

  // Handle filter changes
  const handleFilterChange = (key: keyof SearchFilters, value: any) => {
    const newFilters = { ...filters, [key]: value };
    setFilters(newFilters);
    debouncedSearch(query, newFilters);
  };

  // Handle suggestion click
  const handleSuggestionClick = (suggestion: string) => {
    setQuery(suggestion);
    setShowSuggestions(false);
    debouncedSearch(suggestion, filters);
  };

  // Clear filters
  const clearFilters = () => {
    setFilters({});
    debouncedSearch(query, {});
  };

  // Active filters count
  const activeFiltersCount = Object.values(filters).filter(value =>
    value !== undefined && value !== null && value !== '' && value !== false
  ).length;

  return (
    <div className="w-full space-y-4">
      {/* Search Input */}
      <div className="relative">
        <div className="flex gap-2">
          <div className="relative flex-1">
            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-4 w-4" />
            <Input
              type="text"
              placeholder={placeholder}
              value={query}
              onChange={(e) => handleQueryChange(e.target.value)}
              onKeyPress={(e) => e.key === 'Enter' && handleSearch()}
              className="pl-10 pr-4"
            />
            {query && (
              <button
                onClick={() => {
                  setQuery('');
                  setAutocompleteItems([]);
                  setShowSuggestions(false);
                }}
                className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
              >
                <X className="h-4 w-4" />
              </button>
            )}
          </div>
          <Button onClick={handleSearch}>
            Search
          </Button>
          {showFilters && (
            <Button
              variant="outline"
              onClick={() => setShowFiltersPanel(!showFiltersPanel)}
              className="relative"
            >
              <SlidersHorizontal className="h-4 w-4 mr-2" />
              Filters
              {activeFiltersCount > 0 && (
                <Badge variant="destructive" className="ml-2 h-5 w-5 p-0 flex items-center justify-center text-xs">
                  {activeFiltersCount}
                </Badge>
              )}
            </Button>
          )}
        </div>

        {/* Autocomplete Suggestions */}
        {showSuggestions && (autocompleteItems.length > 0 || suggestions.length > 0) && (
          <Card className="absolute top-full left-0 right-0 z-50 mt-1 max-h-96 overflow-y-auto">
            <CardContent className="p-2">
              {/* Autocomplete */}
              {autocompleteItems.length > 0 && (
                <div className="mb-4">
                  <h4 className="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Suggestions</h4>
                  <div className="space-y-1">
                    {autocompleteItems.map((item, index) => (
                      <button
                        key={index}
                        onClick={() => handleSuggestionClick(item)}
                        className="w-full text-left px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-sm"
                      >
                        {item}
                      </button>
                    ))}
                  </div>
                </div>
              )}

              {/* AI Suggestions */}
              {suggestions.map((suggestion, index) => (
                <div key={index} className="mb-4">
                  <h4 className="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {suggestion.title}
                  </h4>
                  <div className="flex flex-wrap gap-1">
                    {suggestion.items.map((item, itemIndex) => (
                      <Badge
                        key={itemIndex}
                        variant="outline"
                        className="cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900"
                        onClick={() => handleSuggestionClick(item)}
                      >
                        {item}
                      </Badge>
                    ))}
                  </div>
                </div>
              ))}
            </CardContent>
          </Card>
        )}
      </div>

      {/* Filters Panel */}
      {showFilters && (
        <Collapsible open={showFiltersPanel} onOpenChange={setShowFiltersPanel}>
          <CollapsibleContent>
            <Card>
              <CardHeader className="pb-3">
                <div className="flex items-center justify-between">
                  <CardTitle className="text-lg">Filters</CardTitle>
                  {activeFiltersCount > 0 && (
                    <Button variant="ghost" size="sm" onClick={clearFilters}>
                      Clear All
                    </Button>
                  )}
                </div>
              </CardHeader>
              <CardContent className="space-y-4">
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                  {/* Category Filter */}
                  <div className="space-y-2">
                    <label className="text-sm font-medium">Category</label>
                    <Select
                      value={filters.category || ''}
                      onValueChange={(value) => handleFilterChange('category', value || undefined)}
                    >
                      <SelectTrigger>
                        <SelectValue placeholder="All Categories" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="">All Categories</SelectItem>
                        {facets.categories?.map((category: string) => (
                          <SelectItem key={category} value={category}>
                            {category}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>

                  {/* Material Filter */}
                  <div className="space-y-2">
                    <label className="text-sm font-medium">Material</label>
                    <Select
                      value={filters.material || ''}
                      onValueChange={(value) => handleFilterChange('material', value || undefined)}
                    >
                      <SelectTrigger>
                        <SelectValue placeholder="All Materials" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="">All Materials</SelectItem>
                        {facets.materials?.map((material: string) => (
                          <SelectItem key={material} value={material}>
                            {material}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>

                  {/* Gemstone Filter */}
                  <div className="space-y-2">
                    <label className="text-sm font-medium">Gemstone</label>
                    <Select
                      value={filters.gemstone || ''}
                      onValueChange={(value) => handleFilterChange('gemstone', value || undefined)}
                    >
                      <SelectTrigger>
                        <SelectValue placeholder="All Gemstones" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="">All Gemstones</SelectItem>
                        {facets.gemstones?.map((gemstone: string) => (
                          <SelectItem key={gemstone} value={gemstone}>
                            {gemstone}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>

                  {/* Item Type Filter */}
                  <div className="space-y-2">
                    <label className="text-sm font-medium">Type</label>
                    <Select
                      value={filters.itemType || ''}
                      onValueChange={(value) => handleFilterChange('itemType', value || undefined)}
                    >
                      <SelectTrigger>
                        <SelectValue placeholder="All Types" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="">All Types</SelectItem>
                        {facets.item_types?.map((type: string) => (
                          <SelectItem key={type} value={type}>
                            {type}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>
                </div>

                {/* Price Range */}
                <div className="space-y-2">
                  <label className="text-sm font-medium">Price Range</label>
                  <div className="px-2">
                    <Slider
                      value={[filters.minPrice || 0, filters.maxPrice || 10000]}
                      onValueChange={([min, max]) => {
                        handleFilterChange('minPrice', min > 0 ? min : undefined);
                        handleFilterChange('maxPrice', max < 10000 ? max : undefined);
                      }}
                      max={10000}
                      min={0}
                      step={50}
                      className="w-full"
                    />
                    <div className="flex justify-between text-sm text-gray-500 mt-1">
                      <span>${filters.minPrice || 0}</span>
                      <span>${filters.maxPrice || 10000}</span>
                    </div>
                  </div>
                </div>

                {/* Featured Only */}
                <div className="flex items-center space-x-2">
                  <Checkbox
                    id="featured"
                    checked={filters.featured || false}
                    onCheckedChange={(checked) => handleFilterChange('featured', checked)}
                  />
                  <label htmlFor="featured" className="text-sm font-medium">
                    Featured products only
                  </label>
                </div>

                {/* Sort Options */}
                <div className="space-y-2">
                  <label className="text-sm font-medium">Sort By</label>
                  <Select
                    value={filters.sort || 'relevance'}
                    onValueChange={(value) => handleFilterChange('sort', value)}
                  >
                    <SelectTrigger className="w-full">
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="relevance">Relevance</SelectItem>
                      <SelectItem value="price_low">Price: Low to High</SelectItem>
                      <SelectItem value="price_high">Price: High to Low</SelectItem>
                      <SelectItem value="newest">Newest First</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </CardContent>
            </Card>
          </CollapsibleContent>
        </Collapsible>
      )}
    </div>
  );
};

// Debounce utility
function debounce<T extends (...args: any[]) => any>(
  func: T,
  wait: number
): (...args: Parameters<T>) => void {
  let timeout: NodeJS.Timeout;
  return (...args: Parameters<T>) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => func(...args), wait);
  };
}