import React, { useState, useEffect, useMemo } from "react";
import { useSearchParams } from "react-router-dom";
import { Helmet } from "react-helmet-async";
import { format } from "date-fns";
import { DateRange } from "react-day-picker";
import "react-day-picker/dist/style.css";
import { DayPicker } from "react-day-picker";
import { useInfiniteEvents } from "@/hooks/useEvents";
import { useInView } from "react-intersection-observer";
import { useDebounce } from "@/hooks/useDebounce";
import { GetEventsParams, Event as EventType } from "@/services/eventService";
import EventCard from "@/components/events/EventCard";
import EventCardSkeleton from "@/components/skeletons/EventCardSkeleton";
import RandomProductSection from "@/components/products/RandomProductSection";
import RecentPostsSection from "@/components/blog/RecentPostsSection";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from "@/components/ui/popover";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Tabs, TabsList, TabsTrigger } from "@/components/ui/tabs";
import {
  Calendar as CalendarIcon,
  Search,
  InfoIcon,
  X as XIcon,
  Loader2,
} from "lucide-react";

const EventsPage: React.FC = () => {
  const [searchParams, setSearchParams] = useSearchParams();
  const [activeTab, setActiveTab] = useState<GetEventsParams["status"]>(
    (searchParams.get("status") as GetEventsParams["status"]) || "upcoming"
  );
  const [searchTerm, setSearchTerm] = useState(
    searchParams.get("search") || ""
  );
  const [category, setCategory] = useState(searchParams.get("category") || "");
  const [dateRange, setDateRange] = useState<DateRange | undefined>({
    from: searchParams.get("start_date")
      ? new Date(searchParams.get("start_date")!)
      : undefined,
    to: searchParams.get("end_date")
      ? new Date(searchParams.get("end_date")!)
      : undefined,
  });
  const debouncedSearchTerm = useDebounce(searchTerm, 500);

  const {
    data,
    fetchNextPage,
    hasNextPage,
    isLoading,
    isFetchingNextPage,
    isError,
    error,
  } = useInfiniteEvents({
    perPage: 9,
    status: activeTab,
    search: debouncedSearchTerm,
    category: category,
    start_date: dateRange?.from
      ? format(dateRange.from, "yyyy-MM-dd")
      : undefined,
    end_date: dateRange?.to ? format(dateRange.to, "yyyy-MM-dd") : undefined,
  });

  const allEvents = useMemo(() => {
    if (!data) return [];
    // InfiniteData has a `pages` array
    const pages = (data as any).pages;
    if (!Array.isArray(pages)) return [];
    const flattenedEvents: EventType[] = [];
    for (const page of pages) {
      if (page && Array.isArray(page.data)) {
        flattenedEvents.push(...page.data);
      }
    }
    return flattenedEvents;
  }, [data]);

  const { ref, inView } = useInView({ threshold: 0, rootMargin: "400px" });
  useEffect(() => {
    if (inView && hasNextPage && !isFetchingNextPage) fetchNextPage();
  }, [inView, hasNextPage, isFetchingNextPage, fetchNextPage]);
  useEffect(() => {
    const params = new URLSearchParams();
    params.set("status", activeTab);
    if (debouncedSearchTerm) params.set("search", debouncedSearchTerm);
    if (category) params.set("category", category);
    if (dateRange?.from)
      params.set("start_date", format(dateRange.from, "yyyy-MM-dd"));
    if (dateRange?.to)
      params.set("end_date", format(dateRange.to, "yyyy-MM-dd"));
    setSearchParams(params, { replace: true });
  }, [activeTab, debouncedSearchTerm, category, dateRange, setSearchParams]);

  const handleTabChange = (newStatus: string) => {
    setActiveTab(newStatus as GetEventsParams["status"]);
  };
  const handleClearFilters = () => {
    setSearchTerm("");
    setCategory("");
    setDateRange(undefined);
  };

  const eventCategories = [
    { value: "show", label: "Shows & Fairs" },
    { value: "exhibition", label: "Exhibitions" },
    { value: "summit", label: "Summits" },
    { value: "market", label: "Markets" },
    { value: "bead", label: "Bead Events" },
  ];

  return (
    <>
      <Helmet>
        <title>
          Jewellery Events Calendar | Workshops, Shows & Exhibitions
        </title>
        <meta
          name="description"
          content="Stay updated on the latest jewellery events, including online webinars, fashion showcases, trade shows, and exhibitions relevant to jewellery enthusiasts."
        />
      </Helmet>
      <main className="container mx-auto px-4 py-12 md:py-16">
        <header className="text-center mb-12">
          <h1 className="text-4xl lg:text-5xl font-playfair font-bold text-dark-slate">
            Events Calendar
          </h1>
          <p className="mt-3 text-lg text-gray-600 max-w-2xl mx-auto font-lato">
            Discover workshops, exhibitions, and shows happening in the
            jewellery world.
          </p>
        </header>

        <div className="bg-white p-4 rounded-xl shadow-md border border-gray-100 mb-12">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-center">
            <div className="relative">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
              <Input
                placeholder="Search events..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="pl-10 h-11"
              />
            </div>
            <Select value={category} onValueChange={setCategory}>
              <SelectTrigger className="h-11">
                <SelectValue placeholder="All Categories" />
              </SelectTrigger>
              <SelectContent>
                {eventCategories.map((cat) => (
                  <SelectItem key={cat.value} value={cat.value}>
                    {cat.label}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
            <Popover>
              <PopoverTrigger asChild>
                <Button
                  variant="outline"
                  className="w-full justify-start text-left font-normal h-11"
                >
                  <CalendarIcon className="mr-2 h-4 w-4" />
                  {dateRange?.from ? (
                    dateRange.to ? (
                      `${format(dateRange.from, "LLL d, y")} - ${format(
                        dateRange.to,
                        "LLL d, y"
                      )}`
                    ) : (
                      format(dateRange.from, "LLL d, y")
                    )
                  ) : (
                    <span>Pick a date range</span>
                  )}
                </Button>
              </PopoverTrigger>
              <PopoverContent className="w-auto p-0" align="start">
                <DayPicker
                  mode="range"
                  selected={dateRange}
                  onSelect={setDateRange}
                  numberOfMonths={2}
                  defaultMonth={dateRange?.from}
                />
              </PopoverContent>
            </Popover>
            <Button
              variant="ghost"
              onClick={handleClearFilters}
              className="h-11"
            >
              <XIcon className="mr-2 h-4 w-4" /> Clear Filters
            </Button>
          </div>
        </div>

        <Tabs
          value={activeTab}
          onValueChange={handleTabChange}
          className="mb-8 md:mb-12"
        >
          <TabsList className="grid w-full grid-cols-2 md:w-auto md:mx-auto md:inline-flex shadow-sm">
            <TabsTrigger value="upcoming">Upcoming</TabsTrigger>
            <TabsTrigger value="past">Past</TabsTrigger>
          </TabsList>
        </Tabs>

        <section>
          {isLoading ? (
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
              {Array.from({ length: 9 }).map((_, index) => (
                <EventCardSkeleton key={index} />
              ))}
            </div>
          ) : isError ? (
            <div className="text-center py-10 text-red-500">
              Error loading events: {(error as Error)?.message}
            </div>
          ) : allEvents.length > 0 ? (
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
              {allEvents.map((event) => (
                <EventCard key={event.id} event={event} />
              ))}
            </div>
          ) : (
            <div className="text-center py-16 border border-dashed border-gray-300 rounded-lg mt-8">
              <InfoIcon className="mx-auto h-12 w-12 text-gray-400 mb-4" />
              <h3 className="text-xl font-medium text-gray-900">
                No events found
              </h3>
              <p className="mt-1 text-sm text-gray-500">
                Try adjusting your filters or check back later.
              </p>
            </div>
          )}

          <div className="mt-12 flex justify-center h-10 items-center">
            <div ref={ref} />
            {isFetchingNextPage && (
              <div className="flex items-center space-x-2 text-gray-500">
                <Loader2 className="h-5 w-5 animate-spin" />
                <span>Loading more events...</span>
              </div>
            )}
            {hasNextPage && !isFetchingNextPage && (
              <Button onClick={() => fetchNextPage()} variant="outline">
                Load More
              </Button>
            )}
            {!hasNextPage && !isLoading && allEvents.length > 0 && (
              <p className="text-gray-500">
                You've reached the end of the list.
              </p>
            )}
          </div>
        </section>

        <div className="my-16 md:my-24 pt-16 border-t space-y-24">
          <RandomProductSection
            title="Shop Event Styles"
            limit={4}
            sectionId="events-page-products"
          />
          <RecentPostsSection title="From Our Journal" limit={4} />
        </div>
      </main>
    </>
  );
};

export default EventsPage;
