import React, { useReducer, useEffect, useMemo } from "react";
import { Link } from "react-router-dom";
import { Helmet } from "react-helmet-async";
import { useQuery } from "@tanstack/react-query";
import apiClient from "@/lib/apiClient";
import SEOMetaTags from "@/components/blog/SEOMetaTags";
import RelatedToolsCard from "@/components/tools/RelatedToolsCard";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Button } from "@/components/ui/button";
import {
  ArrowLeft,
  Diamond,
  Info,
  Loader2,
  AlertCircle,
  RotateCcw,
} from "lucide-react";

// --- Types and Constants ---
const cuts = ["Excellent", "Very Good", "Good", "Fair", "Poor"];
const colors = ["D", "E", "F", "G", "H", "I", "J", "K", "L"];
const clarities = [
  "FL",
  "IF",
  "VVS1",
  "VVS2",
  "VS1",
  "VS2",
  "SI1",
  "SI2",
  "I1",
];
const MIN_CARAT = 0.1;
const MAX_CARAT = 10.0;

// --- API Service ---

interface FetchPriceOptions {
  carat: string;
  cut: string;
  color: string;
  clarity: string;
}

const fetchPricePerCarat = async (
  options: FetchPriceOptions
): Promise<number> => {
  const { carat, cut, color, clarity } = options;
  if (!carat || !cut || !color || !clarity) {
    throw new Error("All 4 Cs must be selected to fetch a price.");
  }

  const params = new URLSearchParams({ carat, cut, color, clarity });
  const response = await apiClient.get<{ pricePerCarat: number }>(`/tools/diamond-price?${params.toString()}`);
  return response.data.pricePerCarat;
};

// --- State Management ---
interface EstimatorState {
  carat: string;
  cut: string;
  color: string;
  clarity: string;
}

type EstimatorAction =
  | {
      type: "SET_FIELD";
      payload: { field: keyof EstimatorState; value: string };
    }
  | { type: "RESET" };

const initialState: EstimatorState = {
  carat: "1.00",
  cut: "Excellent",
  color: "D",
  clarity: "FL",
};

function estimatorReducer(
  state: EstimatorState,
  action: EstimatorAction
): EstimatorState {
  switch (action.type) {
    case "SET_FIELD":
      return { ...state, [action.payload.field]: action.payload.value };
    case "RESET":
      return initialState;
    default:
      return state;
  }
}

const DiamondEstimator: React.FC = () => {
  const [state, dispatch] = useReducer(estimatorReducer, initialState);
  const { carat, cut, color, clarity } = state;

  const isCaratValid = useMemo(() => {
    const caratNum = parseFloat(carat);
    return !isNaN(caratNum) && caratNum >= MIN_CARAT && caratNum <= MAX_CARAT;
  }, [carat]);

  const {
    data: pricePerCarat,
    isLoading,
    isError,
    error,
  } = useQuery<number, Error>({
    queryKey: ["diamondPrice", carat, cut, color, clarity],
    queryFn: () => fetchPricePerCarat({ carat, cut, color, clarity }),
    staleTime: 24 * 60 * 60 * 1000,
    enabled: !!carat && !!cut && !!color && !!clarity && isCaratValid, // Query is disabled if carat is invalid
    retry: false,
  });

  const handleFieldChange = (field: keyof EstimatorState, value: string) => {
    dispatch({ type: "SET_FIELD", payload: { field, value } });
  };

  const resetFields = () => {
    dispatch({ type: "RESET" });
  };

  const estimatedValue = useMemo(() => {
    if (!pricePerCarat || !carat || !isCaratValid) return null;
    return Math.round(pricePerCarat * parseFloat(carat));
  }, [pricePerCarat, carat, isCaratValid]);

  return (
    <>
      <SEOMetaTags
        title="Diamond Price Estimator | 4Cs Diamond Value Calculator | LatestFashionJewellery"
        description="Estimate diamond value based on the 4 Cs - Carat, Cut, Color, and Clarity. Professional diamond price calculator for buyers and sellers."
        canonical="/tools/diamond-estimator"
        keywords="diamond price estimator, diamond value calculator, 4Cs diamond, diamond appraisal, diamond worth, carat price calculator"
      />

      <div className="min-h-screen bg-gradient-to-br from-blue-50 via-background to-indigo-50 py-12">
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
              <div className="flex justify-center mb-4">
                <div className="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
                  <Diamond className="w-8 h-8 text-blue-600" />
                </div>
              </div>
              <h1 className="text-4xl md:text-5xl font-playfair font-bold text-gray-800 mb-4">
                Diamond Price Estimator
              </h1>
              <p className="text-muted-foreground text-lg max-w-2xl mx-auto">
                Estimate diamond value based on the 4 Cs: Carat, Cut, Color, and
                Clarity.
              </p>
            </div>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <Card className="lg:col-span-3 bg-white/80 backdrop-blur-sm border-2 border-blue-100">
              <CardHeader>
                <CardTitle className="text-2xl font-playfair text-center">
                  Diamond Specifications
                </CardTitle>
              </CardHeader>
              <CardContent className="space-y-6">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div className="space-y-2">
                    <Label htmlFor="carat">Carat Weight</Label>
                    <Input
                      id="carat"
                      type="number"
                      step="0.01"
                      min={MIN_CARAT}
                      max={MAX_CARAT}
                      placeholder="e.g., 1.50"
                      value={carat}
                      onChange={(e) =>
                        handleFieldChange("carat", e.target.value)
                      }
                    />
                    {!isCaratValid && carat !== "" && (
                      <p className="text-xs text-red-600 flex items-center gap-1">
                        <AlertCircle size={14} />
                        Please enter a value between {MIN_CARAT} and {MAX_CARAT}
                        .
                      </p>
                    )}
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="cut">Cut Quality</Label>
                    <Select
                      value={cut}
                      onValueChange={(value) => handleFieldChange("cut", value)}
                    >
                      <SelectTrigger>
                        <SelectValue placeholder="Select cut" />
                      </SelectTrigger>
                      <SelectContent>
                        {cuts.map((c) => (
                          <SelectItem key={c} value={c}>
                            {c}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="color">Color Grade</Label>
                    <Select
                      value={color}
                      onValueChange={(value) =>
                        handleFieldChange("color", value)
                      }
                    >
                      <SelectTrigger>
                        <SelectValue placeholder="Select color" />
                      </SelectTrigger>
                      <SelectContent>
                        {colors.map((c) => (
                          <SelectItem key={c} value={c}>
                            {c}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="clarity">Clarity Grade</Label>
                    <Select
                      value={clarity}
                      onValueChange={(value) =>
                        handleFieldChange("clarity", value)
                      }
                    >
                      <SelectTrigger>
                        <SelectValue placeholder="Select clarity" />
                      </SelectTrigger>
                      <SelectContent>
                        {clarities.map((c) => (
                          <SelectItem key={c} value={c}>
                            {c}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>
                </div>
                <Button
                  onClick={resetFields}
                  variant="outline"
                  className="w-full gap-2 bg-white/80"
                >
                  <RotateCcw className="h-4 w-4" />
                  Reset Fields
                </Button>
              </CardContent>
            </Card>

            <Card className="lg:col-span-2 bg-gradient-to-br from-blue-100 to-indigo-100 border-2 border-blue-200 flex flex-col">
              <CardHeader>
                <CardTitle className="text-center text-blue-800">
                  Estimated Value
                </CardTitle>
              </CardHeader>
              <CardContent className="flex-grow flex flex-col items-center justify-center p-6">
                {isLoading ? (
                  <div className="text-center text-blue-700">
                    <Loader2 className="h-8 w-8 animate-spin mx-auto mb-2" />
                    <p>Fetching Price...</p>
                  </div>
                ) : !isCaratValid ? (
                  <div className="text-center text-amber-600 p-4 bg-amber-50/50 rounded-lg">
                    <AlertCircle className="mx-auto mb-2 h-8 w-8" />
                    <p className="font-semibold">Invalid Carat Weight</p>
                    <p className="text-sm">
                      Enter a value between {MIN_CARAT} and {MAX_CARAT}.
                    </p>
                  </div>
                ) : isError ? (
                  <div className="text-center text-red-600 p-4 bg-red-50/50 rounded-lg">
                    <AlertCircle className="mx-auto mb-2 h-8 w-8" />
                    <p className="font-semibold">Price Not Found</p>
                    <p className="text-sm">{error.message}</p>
                  </div>
                ) : (
                  <>
                    <div className="text-5xl font-bold text-blue-800 mb-2">
                      $
                      {estimatedValue ? estimatedValue.toLocaleString() : "..."}
                    </div>
                    <div className="text-sm text-muted-foreground text-center">
                      Based on a fetched price of $
                      {pricePerCarat?.toLocaleString()} per carat.
                    </div>
                  </>
                )}
              </CardContent>
            </Card>
          </div>

          <div className="mt-8">
            <RelatedToolsCard currentPath="/tools/diamond-estimator" />
          </div>
          <div className="mt-8 p-6 bg-blue-50/80 border border-blue-200 rounded-lg">
            <p className="text-sm text-muted-foreground text-center">
              <strong>Disclaimer:</strong> This is an approximate estimate for
              informational purposes only. Actual market prices vary
              significantly based on certification (GIA, AGS), fluorescence,
              shape, and other individual diamond characteristics. Always
              consult a certified gemologist for an accurate appraisal.
            </p>
          </div>
        </div>
      </div>
    </>
  );
};

export default DiamondEstimator;
