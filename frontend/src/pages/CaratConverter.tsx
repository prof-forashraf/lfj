import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import { Helmet } from "react-helmet-async";
import SEOMetaTags from "@/components/blog/SEOMetaTags";
import RelatedToolsCard from "@/components/tools/RelatedToolsCard";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Button } from "@/components/ui/button";
import { ArrowLeft, RotateCcw, Gem } from "lucide-react";

const CaratConverter: React.FC = () => {
  const [carat, setCarat] = useState<string>("");
  const [percentage, setPercentage] = useState<string>("");
  const [fineness, setFineness] = useState<string>("");

  // Convert from carat to other units
  const convertFromCarat = (caratValue: number) => {
    const pct = (caratValue / 24) * 100;
    const fin = pct * 10;
    setPercentage(pct.toFixed(2));
    setFineness(fin.toFixed(0));
  };

  // Convert from percentage to other units
  const convertFromPercentage = (pctValue: number) => {
    const car = (pctValue * 24) / 100;
    const fin = pctValue * 10;
    setCarat(car.toFixed(2));
    setFineness(fin.toFixed(0));
  };

  // Convert from fineness to other units
  const convertFromFineness = (finValue: number) => {
    const pct = finValue / 10;
    const car = (pct * 24) / 100;
    setPercentage(pct.toFixed(2));
    setCarat(car.toFixed(2));
  };

  const handleCaratChange = (value: string) => {
    setCarat(value);
    const numValue = parseFloat(value);
    if (!isNaN(numValue) && numValue >= 0 && numValue <= 24) {
      convertFromCarat(numValue);
    } else if (value === "") {
      setPercentage("");
      setFineness("");
    }
  };

  const handlePercentageChange = (value: string) => {
    setPercentage(value);
    const numValue = parseFloat(value);
    if (!isNaN(numValue) && numValue >= 0 && numValue <= 100) {
      convertFromPercentage(numValue);
    } else if (value === "") {
      setCarat("");
      setFineness("");
    }
  };

  const handleFinenessChange = (value: string) => {
    setFineness(value);
    const numValue = parseFloat(value);
    if (!isNaN(numValue) && numValue >= 0 && numValue <= 1000) {
      convertFromFineness(numValue);
    } else if (value === "") {
      setCarat("");
      setPercentage("");
    }
  };

  const reset = () => {
    setCarat("");
    setPercentage("");
    setFineness("");
  };

  const commonValues = [
    { name: "Pure Gold", carat: 24, percentage: 100, fineness: 1000 },
    { name: "Indian Standard", carat: 22, percentage: 91.67, fineness: 916.7 },
    { name: "European Standard", carat: 18, percentage: 75, fineness: 750 },
    {
      name: "American Standard",
      carat: 14,
      percentage: 58.33,
      fineness: 583.3,
    },
    { name: "Minimum Karat", carat: 10, percentage: 41.67, fineness: 416.7 },
  ];

  const loadCommonValue = (common: (typeof commonValues)[0]) => {
    setCarat(common.carat.toString());
    setPercentage(common.percentage.toFixed(2));
    setFineness(common.fineness.toFixed(0));
  };

  return (
    <>
      <SEOMetaTags
        title="Gold Carat Converter | Purity Calculator | LatestFashionJewellery"
        description="Convert gold purity between carat (k), percentage (%), and fineness. Professional gold purity calculator with common values for 24k, 22k, 18k, and 14k gold."
        canonical="/tools/carat-converter"
        keywords="carat converter, gold purity calculator, gold fineness converter, 24k gold, 22k gold, 18k gold, 14k gold, gold percentage calculator"
        ogTitle="Gold Carat Converter - Professional Purity Calculator"
        ogDescription="Convert between carat, percentage, and fineness measurements for gold purity. Essential tool for jewellery professionals and enthusiasts."
        ogType="article"
      />

      <div className="min-h-screen bg-gradient-to-br from-emerald-50 via-background to-green-50 py-12">
        <div className="container mx-auto px-4 max-w-4xl">
          {/* Header */}
          <div className="mb-8">
            <Link
              to="/tools"
              className="inline-flex items-center text-muted-foreground hover:text-primary mb-6"
            >
              <ArrowLeft className="mr-2 h-4 w-4" />
              Back to Tools
            </Link>

            <div className="text-center">
              <h1 className="text-4xl md:text-5xl font-playfair font-bold text-dark-slate mb-4">
                Carat Converter
              </h1>
              <p className="text-muted-foreground text-lg max-w-2xl mx-auto">
                Convert gold purity between different measurement units: Carat
                (k), Percentage (%), and Fineness
              </p>
            </div>
          </div>

          {/* Converter Cards */}
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <Card className="bg-white/80 backdrop-blur-sm hover:shadow-lg transition-all duration-300 border-2 hover:border-emerald-200">
              <CardHeader>
                <CardTitle className="text-center text-emerald-700">
                  <Gem className="h-6 w-6 mx-auto mb-2" />
                  Carat (k)
                </CardTitle>
              </CardHeader>
              <CardContent>
                <Label htmlFor="carat">Gold Carat (0-24k)</Label>
                <Input
                  id="carat"
                  type="number"
                  min="0"
                  max="24"
                  step="0.01"
                  value={carat}
                  onChange={(e) => handleCaratChange(e.target.value)}
                  placeholder="Enter carat value"
                  className="mt-2 text-center text-lg font-semibold"
                />
                <p className="text-xs text-muted-foreground mt-2 text-center">
                  24k = Pure Gold
                </p>
              </CardContent>
            </Card>

            <Card className="bg-white/80 backdrop-blur-sm hover:shadow-lg transition-all duration-300 border-2 hover:border-emerald-200">
              <CardHeader>
                <CardTitle className="text-center text-emerald-700">
                  <span className="text-2xl">%</span>
                  <div className="text-base mt-1">Percentage</div>
                </CardTitle>
              </CardHeader>
              <CardContent>
                <Label htmlFor="percentage">Gold Percentage (0-100%)</Label>
                <Input
                  id="percentage"
                  type="number"
                  min="0"
                  max="100"
                  step="0.01"
                  value={percentage}
                  onChange={(e) => handlePercentageChange(e.target.value)}
                  placeholder="Enter percentage"
                  className="mt-2 text-center text-lg font-semibold"
                />
                <p className="text-xs text-muted-foreground mt-2 text-center">
                  100% = Pure Gold
                </p>
              </CardContent>
            </Card>

            <Card className="bg-white/80 backdrop-blur-sm hover:shadow-lg transition-all duration-300 border-2 hover:border-emerald-200">
              <CardHeader>
                <CardTitle className="text-center text-emerald-700">
                  <span className="text-xl">‰</span>
                  <div className="text-base mt-1">Fineness</div>
                </CardTitle>
              </CardHeader>
              <CardContent>
                <Label htmlFor="fineness">Fineness (0-1000)</Label>
                <Input
                  id="fineness"
                  type="number"
                  min="0"
                  max="1000"
                  step="1"
                  value={fineness}
                  onChange={(e) => handleFinenessChange(e.target.value)}
                  placeholder="Enter fineness"
                  className="mt-2 text-center text-lg font-semibold"
                />
                <p className="text-xs text-muted-foreground mt-2 text-center">
                  1000 = Pure Gold
                </p>
              </CardContent>
            </Card>
          </div>

          {/* Reset Button */}
          <div className="text-center mb-8">
            <Button onClick={reset} variant="outline" className="gap-2">
              <RotateCcw className="h-4 w-4" />
              Reset All Values
            </Button>
          </div>

          {/* Common Values */}
          <Card className="bg-white/60 backdrop-blur-sm mb-8">
            <CardHeader>
              <CardTitle className="text-xl font-playfair text-center">
                Common Gold Purities
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                {commonValues.map((common, index) => (
                  <Button
                    key={index}
                    variant="outline"
                    className="h-auto p-4 flex flex-col gap-2 hover:bg-emerald-50"
                    onClick={() => loadCommonValue(common)}
                  >
                    <div className="font-semibold text-emerald-700">
                      {common.name}
                    </div>
                    <div className="text-sm space-y-1">
                      <div>{common.carat}k</div>
                      <div>{common.percentage.toFixed(1)}%</div>
                      <div>{common.fineness.toFixed(0)}‰</div>
                    </div>
                  </Button>
                ))}
              </div>
            </CardContent>
          </Card>

          {/* Related Tools */}
          <div className="mb-8">
            <RelatedToolsCard currentPath="/tools/carat-converter" />
          </div>

          {/* Information */}
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <Card className="bg-white/60 backdrop-blur-sm">
              <CardHeader>
                <CardTitle className="text-xl font-playfair">
                  Understanding the Units
                </CardTitle>
              </CardHeader>
              <CardContent className="space-y-4">
                <div>
                  <strong>Carat (k):</strong> Measures gold purity on a scale of
                  0-24, where 24k is pure gold.
                </div>
                <div>
                  <strong>Percentage (%):</strong> Shows the proportion of pure
                  gold in the alloy, where 100% is pure gold.
                </div>
                <div>
                  <strong>Fineness (‰):</strong> Parts per thousand of pure
                  gold, where 1000 represents pure gold.
                </div>
              </CardContent>
            </Card>

            <Card className="bg-white/60 backdrop-blur-sm">
              <CardHeader>
                <CardTitle className="text-xl font-playfair">
                  How to use this tool
                </CardTitle>
              </CardHeader>
              <CardContent className="space-y-4 text-sm text-gray-600">
                <div>Type a carat value to see the matching percentage and fineness.</div>
                <div>Type a percentage value to find the equivalent carat and fineness.</div>
                <div>Use the common values buttons for standard gold purities.</div>
                <div>Keep values within the expected range for the most accurate conversions.</div>
              </CardContent>
            </Card>

            <Card className="bg-white/60 backdrop-blur-sm">
              <CardHeader>
                <CardTitle className="text-xl font-playfair">
                  What to do next
                </CardTitle>
              </CardHeader>
              <CardContent className="space-y-4 text-sm text-gray-600">
                <div>
                  Avoid assuming these values represent the retailer’s final price.
                </div>
                <div>
                  Compare the purity details with product descriptions before you shop.
                </div>
                <div>
                  Use the customers’ product information and our curated collections to guide your choice.
                </div>
              </CardContent>
            </Card>
          </div>
          <div className="mt-6 rounded-3xl border border-emerald-200 bg-emerald-50 p-6 text-sm text-emerald-800">
            After using this tool, compare these purity values with the descriptions in our shop to make a more informed choice.
          </div>
          <div className="mt-6 text-center">
            <Button asChild className="bg-emerald-700 text-white px-8 py-3 hover:bg-emerald-800">
              <Link to="/shop">Browse curated gold jewellery</Link>
            </Button>
          </div>
        </div>
      </div>
    </>
  );
};

export default CaratConverter;
