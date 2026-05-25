import React, { useReducer, useEffect, useMemo } from "react";

import { Link } from "react-router-dom";
import { Helmet } from "react-helmet-async";
import { useQuery } from "@tanstack/react-query";
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
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import {
  ArrowLeft,
  Moon,
  Info,
  Calculator,
  Loader2,
  CheckCircle,
  XCircle,
  RotateCcw,
} from "lucide-react";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { Switch } from "@/components/ui/switch";

// --- Types, Constants, and API Services ---
const NISAB_GOLD_GRAMS = 87.48;
const NISAB_SILVER_GRAMS = 612.36; // Nisab for Silver (52.5 Tola)
const ZAKAT_RATE = 0.025; // 2.5%
const TROY_OUNCE_IN_GRAMS = 31.1035;

interface ZakatAsset {
  label: string;
  value: number;
  isNegative?: boolean;
}

interface CalculationResult {
  netWorth: number;
  nisabValue: number;
  nisabType: "Gold" | "Silver"; // To show which Nisab is being used
  isEligible: boolean;
  zakatDue: number;
  breakdown: ZakatAsset[];
}
import {
  fetchLiveGoldPricePerGram,
  fetchLiveSilverPricePerGram,
} from "@/services/priceApi"; // Make sure path is correct

// --- State Management with useReducer ---
interface ZakatState {
  currency: string;
  goldWeight: string;
  goldPurity: string;
  includePersonalGold: boolean;
  silverWeight: string;
  cash: string;
  investments: string;
  receivables: string;
  liabilities: string;
  result: CalculationResult | null;
}

type Action =
  | {
      type: "SET_FIELD";
      payload: {
        field: keyof Omit<ZakatState, "result">;
        value: string | boolean;
      };
    }
  | { type: "CALCULATE"; payload: { goldPrice: number; silverPrice: number } }
  | { type: "RESET" };

const initialState: ZakatState = {
  currency: "USD",
  goldWeight: "",
  goldPurity: "22",
  includePersonalGold: true,
  silverWeight: "",
  cash: "",
  investments: "",
  receivables: "",
  liabilities: "",
  result: null,
};

function zakatReducer(state: ZakatState, action: Action): ZakatState {
  switch (action.type) {
    case "SET_FIELD":
      return { ...state, [action.payload.field]: action.payload.value };
    case "CALCULATE": {
      const { goldPrice, silverPrice } = action.payload;

      const getNum = (val: string) => parseFloat(val) || 0;

      const goldWeight = getNum(state.goldWeight);
      const goldPurityPercent = (parseFloat(state.goldPurity) || 0) / 24;
      const pureGoldWeight = state.includePersonalGold
        ? goldWeight * goldPurityPercent
        : 0;
      const goldValue = pureGoldWeight * goldPrice;

      const silverWeight = getNum(state.silverWeight);
      const silverValue = silverWeight * silverPrice;

      const cash = getNum(state.cash);
      const investments = getNum(state.investments);
      const receivables = getNum(state.receivables);
      const totalLiabilities = getNum(state.liabilities);

      const totalAssets =
        goldValue + silverValue + cash + investments + receivables;
      const netWorth = totalAssets - totalLiabilities;

      // Determine the Nisab value to use
      const goldNisabValue = NISAB_GOLD_GRAMS * goldPrice;
      const silverNisabValue = NISAB_SILVER_GRAMS * silverPrice;
      const nisabValue = Math.min(goldNisabValue, silverNisabValue);
      const nisabType = nisabValue === goldNisabValue ? "Gold" : "Silver";

      const isEligible = netWorth >= nisabValue;
      const zakatDue = isEligible ? netWorth * ZAKAT_RATE : 0;

      const breakdown: ZakatAsset[] = [
        { label: "Gold Value", value: goldValue },
        { label: "Silver Value", value: silverValue },
        { label: "Cash & Bank Balances", value: cash },
        { label: "Investments / Shares", value: investments },
        { label: "Receivables (Money owed to you)", value: receivables },
        {
          label: "Liabilities (Short-term debts)",
          value: totalLiabilities,
          isNegative: true,
        },
      ];

      return {
        ...state,
        result: {
          netWorth,
          nisabValue,
          nisabType,
          isEligible,
          zakatDue,
          breakdown,
        },
      };
    }
    case "RESET":
      return initialState;
    default:
      return state;
  }
}

const ZakatCalculator: React.FC = () => {
  const [state, dispatch] = useReducer(zakatReducer, initialState);
  const {
    currency,
    goldWeight,
    goldPurity,
    includePersonalGold,
    silverWeight,
    cash,
    investments,
    receivables,
    liabilities,
    result,
  } = state;

  const { data: goldPrice, isLoading: goldLoading } = useQuery({
    queryKey: ["goldPrice", currency],
    queryFn: () => fetchLiveGoldPricePerGram(currency), // Use the correct service function
  });

  const { data: silverPrice, isLoading: silverLoading } = useQuery({
    queryKey: ["silverPrice", currency],
    queryFn: () => fetchLiveSilverPricePerGram(currency), // Use the correct service function
  });

  useEffect(() => {
    if (goldPrice !== undefined && silverPrice !== undefined) {
      dispatch({ type: "CALCULATE", payload: { goldPrice, silverPrice } });
    }
  }, [
    goldWeight,
    goldPurity,
    includePersonalGold,
    silverWeight,
    cash,
    investments,
    receivables,
    liabilities,
    currency,
    goldPrice,
    silverPrice,
  ]);

  const handleFieldChange = (
    field: keyof Omit<ZakatState, "result">,
    value: string | boolean
  ) => {
    dispatch({ type: "SET_FIELD", payload: { field, value } });
  };

  const reset = () => dispatch({ type: "RESET" });

  const currentCurrency = useMemo(
    () =>
      ({
        USD: "$",
        EUR: "€",
        GBP: "£",
        SAR: "ر.س",
        AED: "د.إ",
        PKR: "₨",
        INR: "₹",
      }[currency]),
    [currency]
  );

  const formatCurrency = (amount: number) =>
    `${currentCurrency} ${amount.toLocaleString("en-US", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    })}`;

  return (
    <>
      <SEOMetaTags
        title="Zakat Calculator | Calculate Zakat on Gold, Silver, Cash & Investments"
        description="A comprehensive Islamic Zakat calculator to accurately determine your Zakat obligation on gold, silver, cash, and other assets based on live market prices and the Nisab threshold."
        canonical="/tools/zakat-calculator"
        keywords="zakat calculator, islamic zakat, zakat on gold, zakat on silver, nisab calculator, how to calculate zakat, zakat on cash, zakat on investments"
      />
      <div className="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 py-12">
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
                <div className="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center shadow-inner">
                  <Moon className="w-8 h-8 text-green-600" />
                </div>
              </div>
              <h1 className="text-4xl md:text-5xl font-playfair font-bold text-gray-800 mb-4">
                Zakat Calculator
              </h1>
              <p className="text-muted-foreground text-lg max-w-2xl mx-auto">
                Calculate your annual Zakat obligation on gold, silver, cash,
                and other assets according to Islamic principles.
              </p>
            </div>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {/* --- INPUTS --- */}
            <div className="space-y-6">
              <Card className="bg-white/80 backdrop-blur-sm border-2 border-green-100">
                <CardHeader>
                  <CardTitle>Assets (What you own)</CardTitle>
                </CardHeader>
                <CardContent className="space-y-4">
                  <div className="p-4 bg-green-50/50 rounded-lg space-y-3">
                    <Label className="font-semibold">Gold Holdings</Label>
                    <div className="flex items-center space-x-2">
                      <Switch
                        id="personal-gold"
                        checked={state.includePersonalGold}
                        onCheckedChange={(c) =>
                          handleFieldChange("includePersonalGold", c)
                        }
                      />
                      <Label htmlFor="personal-gold">
                        Include personal use jewelry?
                      </Label>
                    </div>
                    {state.includePersonalGold && (
                      <div className="grid grid-cols-2 gap-4">
                        <div>
                          <Label htmlFor="goldWeight" className="text-xs">
                            Weight (grams)
                          </Label>
                          <Input
                            id="goldWeight"
                            type="number"
                            min="0"
                            placeholder="e.g., 100"
                            value={state.goldWeight}
                            onChange={(e) =>
                              handleFieldChange("goldWeight", e.target.value)
                            }
                          />
                        </div>
                        <div>
                          <Label htmlFor="goldPurity" className="text-xs">
                            Purity (Carat)
                          </Label>
                          <Select
                            value={state.goldPurity}
                            onValueChange={(v) =>
                              handleFieldChange("goldPurity", v)
                            }
                          >
                            <SelectTrigger>
                              <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                              {[24, 22, 21, 18, 14, 10].map((p) => (
                                <SelectItem key={p} value={String(p)}>
                                  {p}K
                                </SelectItem>
                              ))}
                            </SelectContent>
                          </Select>
                        </div>
                      </div>
                    )}
                  </div>
                  <div className="p-4 bg-gray-50/50 rounded-lg space-y-3">
                    <Label className="font-semibold">Silver Holdings</Label>
                    <div>
                      <Label htmlFor="silverWeight" className="text-xs">
                        Weight (grams)
                      </Label>
                      <Input
                        id="silverWeight"
                        type="number"
                        min="0"
                        placeholder="e.g., 600"
                        value={state.silverWeight}
                        onChange={(e) =>
                          handleFieldChange("silverWeight", e.target.value)
                        }
                      />
                    </div>
                  </div>
                  <div>
                    <Label htmlFor="cash">Cash & Bank Balances</Label>
                    <Input
                      id="cash"
                      type="number"
                      min="0"
                      placeholder="e.g., 5000"
                      value={state.cash}
                      onChange={(e) =>
                        handleFieldChange("cash", e.target.value)
                      }
                    />
                  </div>
                  <div>
                    <Label htmlFor="investments">
                      Investments, Stocks, Shares
                    </Label>
                    <Input
                      id="investments"
                      type="number"
                      min="0"
                      placeholder="e.g., 10000"
                      value={state.investments}
                      onChange={(e) =>
                        handleFieldChange("investments", e.target.value)
                      }
                    />
                  </div>
                  <div>
                    <Label htmlFor="receivables">
                      Money Owed to You (Receivables)
                    </Label>
                    <Input
                      id="receivables"
                      type="number"
                      min="0"
                      placeholder="e.g., 1000"
                      value={state.receivables}
                      onChange={(e) =>
                        handleFieldChange("receivables", e.target.value)
                      }
                    />
                  </div>
                </CardContent>
              </Card>
              <Card className="bg-white/80 backdrop-blur-sm border-2 border-red-100">
                <CardHeader>
                  <CardTitle>Liabilities (What you owe)</CardTitle>
                </CardHeader>
                <CardContent>
                  <div>
                    <Label htmlFor="liabilities">Short-term Debts</Label>
                    <Input
                      id="liabilities"
                      type="number"
                      min="0"
                      placeholder="e.g., 2000"
                      value={state.liabilities}
                      onChange={(e) =>
                        handleFieldChange("liabilities", e.target.value)
                      }
                    />
                    <p className="text-xs text-muted-foreground mt-1">
                      Debts due within one year.
                    </p>
                  </div>
                </CardContent>
              </Card>
              <div className="text-center">
                <Button
                  onClick={reset}
                  variant="outline"
                  className="gap-2 bg-white/80"
                >
                  <RotateCcw className="h-4 w-4" />
                  Reset Calculator
                </Button>
              </div>
            </div>

            {/* --- RESULTS --- */}
            <Card className="bg-gradient-to-br from-green-100 to-emerald-100 border-2 border-green-200 flex flex-col sticky top-8">
              <CardHeader>
                <CardTitle className="text-2xl font-playfair text-center text-green-800">
                  Your Zakat Calculation
                </CardTitle>
                <CardDescription className="text-center">
                  <Select
                    value={currency}
                    onValueChange={(v) => handleFieldChange("currency", v)}
                  >
                    <SelectTrigger className="w-48 mx-auto mt-2 bg-white/50">
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      {Object.entries({
                        USD: "$",
                        EUR: "€",
                        GBP: "£",
                        SAR: "ر.س",
                        AED: "د.إ",
                        PKR: "₨",
                        INR: "₹",
                      }).map(([code, symbol]) => (
                        <SelectItem key={code} value={code}>
                          {symbol} {code}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </CardDescription>
              </CardHeader>
              <CardContent className="flex-grow flex flex-col justify-center p-6">
                {goldLoading || silverLoading ? (
                  <div className="text-center text-green-700">
                    <Loader2 className="h-8 w-8 animate-spin mx-auto mb-2" />
                    <p>Fetching Live Prices...</p>
                  </div>
                ) : !result ? (
                  <div className="text-center text-muted-foreground">
                    Enter your assets to see calculation.
                  </div>
                ) : (
                  <div className="space-y-4">
                    <div className="p-3 bg-white/50 rounded-lg text-xs text-center text-muted-foreground">
                      Calculation based on: Gold at{" "}
                      {formatCurrency(goldPrice || 0)}/g and Silver at{" "}
                      {formatCurrency(silverPrice || 0)}/g
                    </div>
                    <div className="space-y-2 text-sm">
                      {result.breakdown.map(
                        (item) =>
                          item.value > 0 && (
                            <div
                              key={item.label}
                              className={`flex justify-between p-2 rounded-md ${
                                item.isNegative
                                  ? "bg-red-100/70"
                                  : "bg-green-50/70"
                              }`}
                            >
                              <span className="text-muted-foreground">
                                {item.label}
                              </span>
                              <span
                                className={`font-medium ${
                                  item.isNegative
                                    ? "text-red-600"
                                    : "text-green-800"
                                }`}
                              >
                                {item.isNegative ? "-" : ""}
                                {formatCurrency(item.value)}
                              </span>
                            </div>
                          )
                      )}
                    </div>
                    <hr />
                    <div className="flex justify-between font-semibold text-lg">
                      <span className="text-muted-foreground">
                        Zakat-able Wealth
                      </span>
                      <span>{formatCurrency(result.netWorth)}</span>
                    </div>
                    <div className="flex justify-between font-semibold text-lg">
                      <span className="text-muted-foreground">
                        Nisab Threshold ({result.nisabType})
                      </span>
                      <span>{formatCurrency(result.nisabValue)}</span>
                    </div>
                    <hr />
                    {result.isEligible ? (
                      <div className="p-4 text-center bg-white rounded-lg shadow-inner">
                        <CheckCircle className="h-10 w-10 text-green-500 mx-auto mb-2" />
                        <p className="text-muted-foreground">
                          Your Zakat is Due:
                        </p>
                        <p className="text-4xl font-bold text-green-700">
                          {formatCurrency(result.zakatDue)}
                        </p>
                      </div>
                    ) : (
                      <div className="p-4 text-center bg-white rounded-lg shadow-inner">
                        <XCircle className="h-10 w-10 text-amber-500 mx-auto mb-2" />
                        <p className="font-semibold text-amber-700">
                          Zakat Not Required
                        </p>
                        <p className="text-sm text-muted-foreground">
                          Your wealth is below the Nisab threshold.
                        </p>
                      </div>
                    )}
                  </div>
                )}
              </CardContent>
            </Card>
          </div>

          <div className="mt-8">
            <RelatedToolsCard currentPath="/tools/zakat-calculator" />
          </div>
          <div className="mt-8 p-6 bg-green-50/80 border border-green-200 rounded-lg">
            <p className="text-sm text-muted-foreground text-center">
              <strong>Disclaimer:</strong> This calculator provides an estimate
              based on common Islamic principles. Zakat rules can be complex and
              vary based on different schools of thought (madhhabs) and
              individual circumstances. Please consult a qualified Islamic
              scholar for specific religious guidance.
            </p>
          </div>
        </div>
      </div>
    </>
  );
};

export default ZakatCalculator;
