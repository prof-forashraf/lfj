import React, { useReducer, useEffect } from "react";
import { Link } from "react-router-dom";
import { Helmet } from "react-helmet-async";
import { useQuery } from "@tanstack/react-query";
import apiClient from "@/lib/apiClient";
import SEOMetaTags from "@/components/blog/SEOMetaTags";
import RelatedToolsCard from "@/components/tools/RelatedToolsCard";
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
  CardDescription,
} from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Button } from "@/components/ui/button";
import {
  ArrowLeft,
  Calculator,
  RotateCcw,
  AlertTriangle,
  Loader2,
} from "lucide-react";
import { Alert, AlertDescription } from "@/components/ui/alert";

// --- Types and Constants ---
interface CalculationResult {
  originalWeight: number;
  pureGoldWeight: number;
  finalWeight: number;
  goldPricePerGram: number;
  estimatedValue: number;
  deductionAmount: number;
}

const TROY_OUNCE_IN_GRAMS = 31.1035;

const currencies = [
  { code: "USD", name: "US Dollar", symbol: "$" },
  { code: "EUR", name: "Euro", symbol: "€" },
  { code: "GBP", name: "British Pound", symbol: "£" },
  { code: "INR", name: "Indian Rupee", symbol: "₹" },
];

const caratOptions = [
  { value: "24", label: "24k (99.9% Pure)", purity: 1.0 },
  { value: "22", label: "22k (91.7% Pure)", purity: 0.917 },
  { value: "21", label: "21k (87.5% Pure)", purity: 0.875 },
  { value: "18", label: "18k (75.0% Pure)", purity: 0.75 },
  { value: "14", label: "14k (58.3% Pure)", purity: 0.583 },
  { value: "10", label: "10k (41.7% Pure)", purity: 0.417 },
];

// --- API Service ---
// Fetches the latest gold price per troy ounce from our existing API
const fetchGoldPricePerOunce = async (currency: string): Promise<number> => {
  const response = await apiClient.get<{ rates: { XAU: number } }>(`/tools/gold-price/${currency}`);
  return 1 / response.data.rates.XAU;
};

// --- State Management with useReducer ---
interface CalculatorState {
  weight: string;
  carat: string;
  deduction: string;
  currency: string;
  result: CalculationResult | null;
}

type CalculatorAction =
  | {
      type: "SET_FIELD";
      payload: { field: keyof Omit<CalculatorState, "result">; value: string };
    }
  | { type: "CALCULATE"; payload: { goldPricePerOunce: number } }
  | { type: "RESET" };

const initialState: CalculatorState = {
  weight: "",
  carat: "22",
  deduction: "20",
  currency: "USD",
  result: null,
};

function calculatorReducer(
  state: CalculatorState,
  action: CalculatorAction
): CalculatorState {
  switch (action.type) {
    case "SET_FIELD":
      return { ...state, [action.payload.field]: action.payload.value };
    case "CALCULATE": {
      const weightNum = parseFloat(state.weight);
      const caratInfo = caratOptions.find((c) => c.value === state.carat);
      const deductionNum = parseFloat(state.deduction);

      if (
        isNaN(weightNum) ||
        !caratInfo ||
        isNaN(deductionNum) ||
        weightNum <= 0
      ) {
        return { ...state, result: null };
      }

      const goldPricePerGram =
        action.payload.goldPricePerOunce / TROY_OUNCE_IN_GRAMS;
      const pureGoldWeight = weightNum * caratInfo.purity;
      const totalValueBeforeDeduction = pureGoldWeight * goldPricePerGram;
      const deductionAmount = totalValueBeforeDeduction * (deductionNum / 100);
      const estimatedValue = totalValueBeforeDeduction - deductionAmount;

      return {
        ...state,
        result: {
          originalWeight: weightNum,
          pureGoldWeight,
          finalWeight: pureGoldWeight * (1 - deductionNum / 100),
          goldPricePerGram,
          estimatedValue,
          deductionAmount,
        },
      };
    }
    case "RESET":
      return initialState;
    default:
      return state;
  }
}

const GoldResaleCalculator: React.FC = () => {
  const [state, dispatch] = useReducer(calculatorReducer, initialState);
  const { weight, carat, deduction, currency, result } = state;

  // Fetch live gold price using React Query
  const {
    data: goldPricePerOunce,
    isLoading: isPriceLoading,
    isError,
  } = useQuery<number>({
    queryKey: ["liveGoldPrice", currency],
    queryFn: () => fetchGoldPricePerOunce(currency),
    staleTime: 5 * 60 * 1000, // Cache for 5 minutes
    refetchOnWindowFocus: true,
  });

  // Re-calculate whenever an input changes or the live price updates
  useEffect(() => {
    if (goldPricePerOunce) {
      dispatch({ type: "CALCULATE", payload: { goldPricePerOunce } });
    }
  }, [weight, carat, deduction, currency, goldPricePerOunce]);

  const handleFieldChange = (
    field: keyof Omit<CalculatorState, "result">,
    value: string
  ) => {
    dispatch({ type: "SET_FIELD", payload: { field, value } });
  };

  const reset = () => dispatch({ type: "RESET" });

  const currentCurrency = currencies.find((c) => c.code === currency);
  const formatPrice = (price: number) =>
    new Intl.NumberFormat("en-US", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    }).format(price);

  return (
    <>
      <SEOMetaTags
        title="Old Gold Resale Calculator | Gold Value Estimator | LatestFashionJewellery"
        description="Calculate the estimated resale value of your old gold jewellery after deducting melting, purification losses, and dealer margins. Professional gold value calculator."
        canonical="/tools/gold-resale-calculator"
        keywords="gold resale calculator, old gold value calculator, gold scrap calculator, sell gold calculator, gold buyback calculator, gold value estimator"
      />

      <div className="min-h-screen bg-gradient-to-br from-amber-50 via-background to-yellow-50 py-12">
        <div className="container mx-auto px-4 max-w-4xl">
          <div className="mb-8">
            <Link
              to="/tools"
              className="inline-flex items-center text-muted-foreground hover:text-primary mb-6"
            >
              <ArrowLeft className="mr-2 h-4 w-4" />
              Back to Tools
            </Link>
            <div className="text-center">
              <h1 className="text-4xl md:text-5xl font-playfair font-bold text-gray-800 mb-4">
                Old Gold Resale Calculator
              </h1>
              <p className="text-muted-foreground text-lg max-w-2xl mx-auto">
                Estimate the value of your old gold jewellery based on live
                market rates.
              </p>
            </div>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <Card className="bg-white/80 backdrop-blur-sm border-2 border-amber-200">
              <CardHeader>
                <CardTitle className="text-center text-amber-700 flex items-center justify-center gap-2">
                  <Calculator />
                  Calculator Input
                </CardTitle>
              </CardHeader>
              <CardContent className="space-y-6">
                <div>
                  <Label htmlFor="weight">Gold Weight (grams)</Label>
                  <Input
                    id="weight"
                    type="number"
                    step="0.01"
                    min="0"
                    value={weight}
                    onChange={(e) =>
                      handleFieldChange("weight", e.target.value)
                    }
                    placeholder="e.g., 10.5"
                    className="mt-2"
                  />
                </div>
                <div>
                  <Label htmlFor="carat">Gold Purity</Label>
                  <Select
                    value={carat}
                    onValueChange={(value) => handleFieldChange("carat", value)}
                  >
                    <SelectTrigger id="carat" className="mt-2">
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      {caratOptions.map((option) => (
                        <SelectItem key={option.value} value={option.value}>
                          {option.label}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div>
                  <Label htmlFor="deduction">Deduction Percentage (%)</Label>
                  <Select
                    value={deduction}
                    onValueChange={(value) =>
                      handleFieldChange("deduction", value)
                    }
                  >
                    <SelectTrigger id="deduction" className="mt-2">
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="15">15% - Premium Dealers</SelectItem>
                      <SelectItem value="18">18% - Good Dealers</SelectItem>
                      <SelectItem value="20">20% - Standard Rate</SelectItem>
                      <SelectItem value="22">22% - Average Dealers</SelectItem>
                      <SelectItem value="25">25% - High Deduction</SelectItem>
                    </SelectContent>
                  </Select>
                  <p className="text-xs text-muted-foreground mt-1">
                    Covers melting, purification, and dealer margins.
                  </p>
                </div>
                <div>
                  <Label htmlFor="currency">Currency</Label>
                  <Select
                    value={currency}
                    onValueChange={(value) =>
                      handleFieldChange("currency", value)
                    }
                  >
                    <SelectTrigger id="currency" className="mt-2">
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      {currencies.map((curr) => (
                        <SelectItem key={curr.code} value={curr.code}>
                          {curr.symbol} {curr.name}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <Button
                  onClick={reset}
                  variant="outline"
                  className="w-full gap-2 bg-white/80"
                >
                  <RotateCcw className="h-4 w-4" />
                  Reset Calculator
                </Button>
              </CardContent>
            </Card>

            <Card className="bg-gradient-to-br from-amber-100 to-yellow-100 border-2 border-amber-200 flex flex-col">
              <CardHeader>
                <CardTitle className="text-center text-amber-800">
                  Calculation Results
                </CardTitle>
              </CardHeader>
              <CardContent className="flex-grow flex flex-col justify-center space-y-4">
                {isPriceLoading ? (
                  <div className="flex flex-col items-center justify-center text-amber-700">
                    <Loader2 className="h-8 w-8 animate-spin mb-2" />
                    <p>Fetching Live Gold Price...</p>
                  </div>
                ) : isError ? (
                  <div className="text-center text-red-600">
                    Could not fetch live gold price. Please try again.
                  </div>
                ) : !result ? (
                  <div className="text-center text-muted-foreground">
                    Enter weight to see calculation.
                  </div>
                ) : (
                  <>
                    <div className="grid grid-cols-2 gap-4">
                      <div className="bg-white/60 rounded-lg p-3 text-center">
                        <div className="text-sm text-muted-foreground">
                          Original Weight
                        </div>
                        <div className="text-lg font-bold text-amber-700">
                          {result.originalWeight}g
                        </div>
                      </div>
                      <div className="bg-white/60 rounded-lg p-3 text-center">
                        <div className="text-sm text-muted-foreground">
                          Pure Gold
                        </div>
                        <div className="text-lg font-bold text-amber-700">
                          {result.pureGoldWeight.toFixed(2)}g
                        </div>
                      </div>
                    </div>
                    <div className="bg-white/60 rounded-lg p-4 text-center">
                      <div className="text-sm text-muted-foreground mb-2">
                        Value Before Deduction
                      </div>
                      <div className="text-xl font-bold text-amber-700 mb-1">
                        {currentCurrency?.symbol}
                        {formatPrice(
                          result.pureGoldWeight * result.goldPricePerGram
                        )}
                      </div>
                      <div className="text-xs text-muted-foreground">
                        Based on {currentCurrency?.symbol}
                        {formatPrice(result.goldPricePerGram)}/gram
                      </div>
                    </div>
                    <div className="bg-white/60 rounded-lg p-4 text-center">
                      <div className="text-sm text-muted-foreground mb-2">
                        Deduction ({deduction}%)
                      </div>
                      <div className="text-xl font-bold text-red-600 mb-1">
                        -{currentCurrency?.symbol}
                        {formatPrice(result.deductionAmount)}
                      </div>
                    </div>
                    <div className="bg-gradient-to-r from-amber-200 to-yellow-200 rounded-lg p-4 text-center border-2 border-amber-300">
                      <div className="text-lg text-amber-800 font-semibold mb-2">
                        Estimated Resale Value
                      </div>
                      <div className="text-4xl font-bold text-amber-900">
                        {currentCurrency?.symbol}
                        {formatPrice(result.estimatedValue)}
                      </div>
                    </div>
                  </>
                )}
              </CardContent>
            </Card>
          </div>

          <Alert className="mb-8 border-amber-300 bg-amber-100/80 text-amber-900">
            <AlertTriangle className="h-4 w-4 text-amber-600" />
            <AlertDescription>
              <strong>Important:</strong> This is an estimated value only.
              Actual prices may vary based on market conditions, dealer
              policies, jewellery condition, and local factors. Always get
              quotes from multiple dealers before selling.
            </AlertDescription>
          </Alert>
          <div className="mb-8">
            <RelatedToolsCard currentPath="/tools/gold-resale-calculator" />
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            <Card className="bg-white/60 backdrop-blur-sm">
              <CardHeader>
                <CardTitle className="text-xl font-playfair">
                  Understanding Deductions
                </CardTitle>
              </CardHeader>
              <CardContent className="space-y-4">
                <div>
                  <strong>Melting Loss (5-8%):</strong> Gold lost during the
                  melting process due to oxidation and handling.
                </div>
                <div>
                  <strong>Purification (3-5%):</strong> Cost of refining mixed
                  metals and impurities from the gold.
                </div>
                <div>
                  <strong>Dealer Margin (7-12%):</strong> Profit margin for the
                  dealer's business operations and risk.
                </div>
                <div>
                  <strong>Total Typical Range:</strong> 15-25% depending on
                  dealer and jewellery condition.
                </div>
              </CardContent>
            </Card>
            <Card className="bg-white/60 backdrop-blur-sm">
              <CardHeader>
                <CardTitle className="text-xl font-playfair">
                  Tips for Better Prices
                </CardTitle>
              </CardHeader>
              <CardContent className="space-y-4">
                <div>
                  <strong>Get Multiple Quotes:</strong> Different dealers offer
                  different rates and deductions.
                </div>
                <div>
                  <strong>Know Your Gold:</strong> Understand the purity and
                  weight before visiting dealers.
                </div>
                <div>
                  <strong>Timing Matters:</strong> Gold prices fluctuate daily -
                  monitor trends.
                </div>
                <div>
                  <strong>Negotiate:</strong> Deduction percentages are often
                  negotiable, especially for larger quantities.
                </div>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </>
  );
};

export default GoldResaleCalculator;
