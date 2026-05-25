// src/pages/GoldPrices.tsx
import React, { useState, useMemo } from "react";
import { Link } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
import {
  ArrowLeft,
  RefreshCw,
  TrendingUp,
  Calendar as CalendarIcon,
} from "lucide-react";
import {
  AreaChart,
  Area,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  ResponsiveContainer,
} from "recharts";
import { format, subDays, subMonths, subYears } from "date-fns";
import { DateRange } from "react-day-picker";

// --- Service and Type Imports ---
import {
  fetchLiveGoldPrices,
  fetchHistoricalGoldPrices,
  LivePriceData,
  HistoricalDataPoint,
} from "@/services/goldApi";

// --- Component Imports ---
import SEOMetaTags from "@/components/blog/SEOMetaTags";
import RelatedToolsCard from "@/components/tools/RelatedToolsCard";
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
  CardDescription,
} from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Badge } from "@/components/ui/badge";
import { useToast } from "@/hooks/use-toast";
import { Skeleton } from "@/components/ui/skeleton";
import { ToggleGroup, ToggleGroupItem } from "@/components/ui/toggle-group";
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from "@/components/ui/popover";
import { Calendar } from "@/components/ui/calendar";
import { cn } from "@/lib/utils"; // Assumes you have a utility function for class names

const GoldPrices: React.FC = () => {
  const [selectedCurrency, setSelectedCurrency] = useState("USD");
  // State for the active time range button ('7D', '1M', '3M', '1Y', or 'custom')
  const [timeRange, setTimeRange] = useState("1M");

  // State specifically for the custom date range picker
  const [customDate, setCustomDate] = useState<DateRange | undefined>({
    from: subMonths(new Date(), 1),
    to: new Date(),
  });

  const { toast } = useToast();

  const currencies = useMemo(
    () => [
      { code: "USD", name: "US Dollar", symbol: "$" },
      { code: "EUR", name: "Euro", symbol: "€" },
      { code: "GBP", name: "British Pound", symbol: "£" },
      { code: "INR", name: "Indian Rupee", symbol: "₹" },
    ],
    []
  );

  // --- Data Fetching Hooks ---
  const {
    data: livePrices,
    isLoading: isLiveLoading,
    isError: isLiveError,
    error: liveError,
    refetch: refetchLivePrices,
  } = useQuery<LivePriceData>({
    queryKey: ["goldPrices", selectedCurrency],
    queryFn: () => fetchLiveGoldPrices(selectedCurrency),
    staleTime: 5 * 60 * 1000,
  });

  const { data: historicalData, isLoading: isHistoricalLoading } = useQuery<
    HistoricalDataPoint[]
  >({
    // The query key now correctly and simply depends on the state that drives the fetch.
    // When any of these values change, react-query will automatically re-fetch the data.
    queryKey: ["historicalGoldPrices", selectedCurrency, timeRange, customDate],
    queryFn: () => {
      // If the user has selected a custom range, use the customDate state
      if (timeRange === "custom" && customDate?.from && customDate?.to) {
        return fetchHistoricalGoldPrices({
          currency: selectedCurrency,
          startDate: format(customDate.from, "yyyy-MM-dd"),
          endDate: format(customDate.to, "yyyy-MM-dd"),
        });
      }
      // Otherwise, use the predefined timeRange
      return fetchHistoricalGoldPrices({
        currency: selectedCurrency,
        range: timeRange,
      });
    },
    staleTime: 60 * 60 * 1000, // Cache historical data for 1 hour
    // The query is only disabled if the user is in 'custom' mode but hasn't finished picking a date range
    enabled: timeRange !== "custom" || (!!customDate?.from && !!customDate?.to),
  });

  // This is a derived value for the Calendar UI. It calculates what the date picker
  // should show based on the current state, but it doesn't set state itself,
  // which prevents re-render loops and bugs.
  const displayedDateInPicker = useMemo(() => {
    if (timeRange === "custom") {
      return customDate;
    }
    const toDate = new Date();
    let fromDate: Date;
    switch (timeRange) {
      case "7D":
        fromDate = subDays(toDate, 7);
        break;
      case "3M":
        fromDate = subMonths(toDate, 3);
        break;
      case "1Y":
        fromDate = subYears(toDate, 1);
        break;
      default:
        fromDate = subMonths(toDate, 1);
        break;
    }
    return { from: fromDate, to: toDate };
  }, [timeRange, customDate]);

  const handleRefresh = () => {
    refetchLivePrices().then(() =>
      toast({
        title: "Prices Refreshed",
        description: `Live gold prices for ${selectedCurrency} have been updated.`,
      })
    );
  };

  const currentCurrency = currencies.find((c) => c.code === selectedCurrency);
  const formatPrice = (price: number) =>
    new Intl.NumberFormat("en-US", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    }).format(price);
  const karats: ("24k" | "22k" | "18k" | "14k")[] = [
    "24k",
    "22k",
    "18k",
    "14k",
  ];
  const getPurityText = (karat: (typeof karats)[number]) =>
    ({
      "24k": "99.9% Pure",
      "22k": "91.7% Pure",
      "18k": "75.0% Pure",
      "14k": "58.3% Pure",
    }[karat]);
  const getPricePerGram = (
    prices: LivePriceData,
    karat: (typeof karats)[number]
  ) => prices[`price_gram_${karat}`];
  const getPricePerOunce = (
    prices: LivePriceData,
    karat: (typeof karats)[number]
  ) =>
    prices.price *
    { "24k": 1, "22k": 0.9167, "18k": 0.75, "14k": 0.5833 }[karat];

  return (
    <>
      <SEOMetaTags
        title={`Live Gold Prices in ${selectedCurrency}`}
        description={`Track real-time and historical gold prices for 24k, 22k, 18k, and 14k gold in ${selectedCurrency}.`}
        canonical="/tools/gold-prices"
        keywords={`live gold price ${selectedCurrency}, gold rate today, 24k gold price, historical gold chart`}
      />
      <div className="min-h-screen bg-gray-50 py-12">
        <div className="container mx-auto px-4 max-w-6xl">
          <div className="mb-8">
            <Link
              to="/tools"
              className="inline-flex items-center text-muted-foreground hover:text-primary mb-6"
            >
              <ArrowLeft className="mr-2 h-4 w-4" />
              Back to Tools
            </Link>
            <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
              <div>
                <h1 className="text-4xl md:text-5xl font-playfair font-bold text-gray-800 mb-2">
                  Live Gold Prices
                </h1>
                <p className="text-muted-foreground text-lg">
                  Real-time precious metal prices from the global market.
                </p>
              </div>
              <div className="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                <Select
                  value={selectedCurrency}
                  onValueChange={setSelectedCurrency}
                >
                  <SelectTrigger className="w-full sm:w-48 bg-white">
                    <SelectValue placeholder="Select Currency" />
                  </SelectTrigger>
                  <SelectContent>
                    {currencies.map((c) => (
                      <SelectItem key={c.code} value={c.code}>
                        {c.symbol} {c.name}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
                <Button
                  onClick={handleRefresh}
                  disabled={isLiveLoading}
                  variant="outline"
                  className="bg-white min-w-fit"
                >
                  <RefreshCw
                    className={`mr-2 h-4 w-4 ${
                      isLiveLoading ? "animate-spin" : ""
                    }`}
                  />{" "}
                  Refresh
                </Button>
              </div>
            </div>
          </div>

          <div className="flex justify-end items-center mb-4">
            {livePrices && !isLiveLoading && (
              <Badge variant="secondary" className="text-sm">
                Last updated: {new Date().toLocaleString()}
              </Badge>
            )}
          </div>

          {isLiveLoading ? (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
              {[...Array(4)].map((_, i) => (
                <Skeleton key={i} className="h-32 rounded-lg" />
              ))}
            </div>
          ) : isLiveError ? (
            <Card className="bg-red-50 border-red-200 text-red-800 text-center py-10 mb-12">
              <CardTitle>Failed to Load Prices</CardTitle>
              <CardDescription className="mt-2">
                {(liveError as Error).message}
              </CardDescription>
            </Card>
          ) : (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
              {livePrices &&
                karats.map((karat) => (
                  <Card
                    key={karat}
                    className="bg-white hover:shadow-xl transition-shadow duration-300 border"
                  >
                    <CardHeader className="pb-2">
                      <CardTitle className="text-xl font-semibold text-gray-700">
                        {karat.toUpperCase()}
                      </CardTitle>
                      <CardDescription>{getPurityText(karat)}</CardDescription>
                    </CardHeader>
                    <CardContent>
                      <p className="text-3xl font-bold text-gray-900">
                        {currentCurrency?.symbol}
                        {formatPrice(getPricePerOunce(livePrices, karat))}
                      </p>
                      <p className="text-sm text-muted-foreground">
                        per Troy Ounce
                      </p>
                      <p className="text-lg font-semibold text-gray-700 mt-2">
                        {currentCurrency?.symbol}
                        {formatPrice(getPricePerGram(livePrices, karat))}
                      </p>
                      <p className="text-xs text-muted-foreground">per Gram</p>
                    </CardContent>
                  </Card>
                ))}
            </div>
          )}

          <Card className="bg-white">
            <CardHeader>
              <div className="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                <div>
                  <CardTitle className="text-2xl font-playfair flex items-center gap-2">
                    <TrendingUp className="text-primary" />
                    Historical Price Trend
                  </CardTitle>
                  <CardDescription>
                    Gold Price (24k per Ounce) in {selectedCurrency}.
                  </CardDescription>
                </div>
                <div className="flex items-center gap-2">
                  <Popover>
                    <PopoverTrigger asChild>
                      <Button
                        id="date"
                        variant={"outline"}
                        className={cn(
                          "w-[300px] justify-start text-left font-normal",
                          !displayedDateInPicker && "text-muted-foreground"
                        )}
                      >
                        <CalendarIcon className="mr-2 h-4 w-4" />
                        {displayedDateInPicker?.from ? (
                          displayedDateInPicker.to ? (
                            <>
                              {" "}
                              {format(
                                displayedDateInPicker.from,
                                "LLL dd, y"
                              )}{" "}
                              - {format(displayedDateInPicker.to, "LLL dd, y")}{" "}
                            </>
                          ) : (
                            format(displayedDateInPicker.from, "LLL dd, y")
                          )
                        ) : (
                          <span>Pick a date</span>
                        )}
                      </Button>
                    </PopoverTrigger>
                    <PopoverContent className="w-auto p-0" align="end">
                      <Calendar
                        initialFocus
                        mode="range"
                        defaultMonth={displayedDateInPicker?.from}
                        selected={displayedDateInPicker}
                        onSelect={(range) => {
                          setCustomDate(range);
                          setTimeRange("custom");
                        }}
                        numberOfMonths={2}
                      />
                    </PopoverContent>
                  </Popover>
                  <ToggleGroup
                    type="single"
                    value={timeRange}
                    onValueChange={(value) => {
                      if (value) setTimeRange(value);
                    }}
                    className="bg-gray-100 rounded-lg p-1 border"
                  >
                    {["7D", "1M", "3M", "1Y"].map((range) => (
                      <ToggleGroupItem key={range} value={range}>
                        {range}
                      </ToggleGroupItem>
                    ))}
                  </ToggleGroup>
                </div>
              </div>
            </CardHeader>
            <CardContent>
              <div className="h-96 w-full">
                {isHistoricalLoading ? (
                  <Skeleton className="h-full w-full" />
                ) : (
                  <ResponsiveContainer width="100%" height="100%">
                    <AreaChart
                      data={historicalData}
                      margin={{ top: 5, right: 30, left: 20, bottom: 5 }}
                    >
                      <defs>
                        <linearGradient
                          id="colorPrice"
                          x1="0"
                          y1="0"
                          x2="0"
                          y2="1"
                        >
                          <stop
                            offset="5%"
                            stopColor="#8884d8"
                            stopOpacity={0.8}
                          />
                          <stop
                            offset="95%"
                            stopColor="#8884d8"
                            stopOpacity={0}
                          />
                        </linearGradient>
                      </defs>
                      <CartesianGrid strokeDasharray="3 3" stroke="#e0e0e0" />
                      <XAxis
                        dataKey="date"
                        tickFormatter={(str) => format(new Date(str), "MMM d")}
                        tick={{ fontSize: 12 }}
                      />
                      <YAxis
                        tickFormatter={(value) =>
                          `${currentCurrency?.symbol}${value}`
                        }
                        domain={["dataMin - 50", "dataMax + 50"]}
                        tick={{ fontSize: 12 }}
                      />
                      <Tooltip
                        contentStyle={{
                          backgroundColor: "rgba(255, 255, 255, 0.8)",
                          backdropFilter: "blur(5px)",
                          border: "1px solid #ccc",
                          borderRadius: "0.5rem",
                        }}
                        formatter={(value: number) => [
                          formatPrice(value),
                          `Price (${currentCurrency?.symbol})`,
                        ]}
                        labelFormatter={(label) =>
                          format(new Date(label), "eeee, LLL d, yyyy")
                        }
                      />
                      <Area
                        type="monotone"
                        dataKey="price"
                        stroke="#8884d8"
                        fillOpacity={1}
                        fill="url(#colorPrice)"
                      />
                    </AreaChart>
                  </ResponsiveContainer>
                )}
              </div>
            </CardContent>
          </Card>

          <div className="mt-12">
            <RelatedToolsCard currentPath="/tools/gold-prices" />
          </div>
          <div className="mt-12 p-6 bg-yellow-50/50 border border-yellow-200 rounded-lg">
            <p className="text-sm text-muted-foreground text-center">
              <strong>Disclaimer:</strong> Prices are sourced from our internal
              database and are for informational purposes only. They may not
              reflect actual retail rates from a specific dealer. Always consult
              with a professional for precise buying or selling prices.
            </p>
          </div>
        </div>
      </div>
    </>
  );
};

export default GoldPrices;
