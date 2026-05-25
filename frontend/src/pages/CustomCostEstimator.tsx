import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import SEOMetaTags from "@/components/blog/SEOMetaTags";
import RelatedToolsCard from "@/components/tools/RelatedToolsCard";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { ArrowLeft, Calculator, Plus, Trash2 } from "lucide-react";

interface Gemstone {
  id: string;
  type: string;
  carat: number;
  pricePerCarat: number;
}

const CustomCostEstimator: React.FC = () => {
  const [metalType, setMetalType] = useState<string>("");
  const [metalWeight, setMetalWeight] = useState<string>("");
  const [gemstones, setGemstones] = useState<Gemstone[]>([]);
  const [makingCharge, setMakingCharge] = useState<string>("20");
  const [estimate, setEstimate] = useState<{
    metalCost: number;
    gemstonesCost: number;
    makingCost: number;
    total: number;
  } | null>(null);

  const metals = [
    { value: "14k-gold", label: "14K Gold", pricePerGram: 35 },
    { value: "18k-gold", label: "18K Gold", pricePerGram: 45 },
    { value: "22k-gold", label: "22K Gold", pricePerGram: 55 },
    { value: "platinum", label: "Platinum", pricePerGram: 32 },
    { value: "silver", label: "Sterling Silver", pricePerGram: 0.8 },
    { value: "white-gold-14k", label: "14K White Gold", pricePerGram: 38 },
    { value: "white-gold-18k", label: "18K White Gold", pricePerGram: 48 },
  ];

  const gemstoneTypes = [
    { value: "diamond", label: "Diamond", basePrice: 5000 },
    { value: "ruby", label: "Ruby", basePrice: 1500 },
    { value: "sapphire", label: "Sapphire", basePrice: 1200 },
    { value: "emerald", label: "Emerald", basePrice: 2000 },
    { value: "pearl", label: "Pearl", basePrice: 800 },
    { value: "amethyst", label: "Amethyst", basePrice: 150 },
    { value: "garnet", label: "Garnet", basePrice: 200 },
    { value: "topaz", label: "Topaz", basePrice: 300 },
    { value: "aquamarine", label: "Aquamarine", basePrice: 400 },
    { value: "tanzanite", label: "Tanzanite", basePrice: 1000 },
  ];

  const addGemstone = () => {
    const newGemstone: Gemstone = {
      id: Date.now().toString(),
      type: "",
      carat: 0,
      pricePerCarat: 0,
    };
    setGemstones([...gemstones, newGemstone]);
  };

  const updateGemstone = (
    id: string,
    field: keyof Gemstone,
    value: string | number
  ) => {
    setGemstones(
      gemstones.map((gem) => {
        if (gem.id === id) {
          const updatedGem = { ...gem, [field]: value };
          if (field === "type") {
            const gemType = gemstoneTypes.find((g) => g.value === value);
            updatedGem.pricePerCarat = gemType?.basePrice || 0;
          }
          return updatedGem;
        }
        return gem;
      })
    );
  };

  const removeGemstone = (id: string) => {
    setGemstones(gemstones.filter((gem) => gem.id !== id));
  };

  const calculateEstimate = () => {
    if (!metalType || !metalWeight) return;

    const weight = parseFloat(metalWeight);
    const makingPercentage = parseFloat(makingCharge);

    if (
      isNaN(weight) ||
      weight <= 0 ||
      isNaN(makingPercentage) ||
      makingPercentage < 0
    )
      return;

    const selectedMetal = metals.find((m) => m.value === metalType);
    if (!selectedMetal) return;

    const metalCost = weight * selectedMetal.pricePerGram;

    const gemstonesCost = gemstones.reduce((total, gem) => {
      return total + gem.carat * gem.pricePerCarat;
    }, 0);

    const subtotal = metalCost + gemstonesCost;
    const makingCost = subtotal * (makingPercentage / 100);
    const total = subtotal + makingCost;

    setEstimate({
      metalCost: Math.round(metalCost),
      gemstonesCost: Math.round(gemstonesCost),
      makingCost: Math.round(makingCost),
      total: Math.round(total),
    });
  };

  useEffect(() => {
    if (metalType && metalWeight && parseFloat(metalWeight) > 0) {
      calculateEstimate();
    }
  }, [metalType, metalWeight, gemstones, makingCharge]);

  return (
    <>
      <SEOMetaTags
        title="Custom Jewellery Cost Estimator | Bespoke Jewelry Price Calculator | LatestFashionJewellery"
        description="Calculate the estimated cost for custom-designed jewellery pieces. Include metal costs, gemstones, and making charges for accurate pricing."
        canonical="/tools/custom-cost-estimator"
        keywords="custom jewelry cost calculator, bespoke jewelry price, custom ring price estimator, jewelry making cost, gold jewelry cost calculator"
        ogTitle="Custom Jewellery Cost Estimator - Calculate Bespoke Jewelry Prices"
        ogDescription="Professional tool to estimate costs for custom-designed jewelry including metals, gemstones, and craftsmanship charges."
        ogType="article"
      />

      <div className="min-h-screen bg-gradient-to-br from-indigo-50 via-background to-purple-50 py-12">
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
              <div className="flex justify-center mb-4">
                <div className="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center">
                  <Calculator className="w-8 h-8 text-indigo-600" />
                </div>
              </div>
              <h1 className="text-4xl md:text-5xl font-playfair font-bold text-dark-slate mb-4">
                Custom Jewellery Cost Estimator
              </h1>
              <p className="text-muted-foreground text-lg max-w-2xl mx-auto">
                Calculate the estimated cost for your custom-designed jewellery
                piece
              </p>
            </div>
          </div>

          {/* Calculator */}
          <Card className="bg-white/80 backdrop-blur-sm mb-8">
            <CardHeader>
              <CardTitle className="text-2xl font-playfair text-center">
                Design Specifications
              </CardTitle>
            </CardHeader>
            <CardContent className="space-y-6">
              {/* Metal Selection */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-2">
                  <Label htmlFor="metal">Metal Type</Label>
                  <Select value={metalType} onValueChange={setMetalType}>
                    <SelectTrigger>
                      <SelectValue placeholder="Select metal type" />
                    </SelectTrigger>
                    <SelectContent>
                      {metals.map((metal) => (
                        <SelectItem key={metal.value} value={metal.value}>
                          {metal.label} (${metal.pricePerGram}/g)
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>

                <div className="space-y-2">
                  <Label htmlFor="weight">Metal Weight (grams)</Label>
                  <Input
                    id="weight"
                    type="number"
                    step="0.1"
                    min="0.1"
                    placeholder="e.g., 5.5"
                    value={metalWeight}
                    onChange={(e) => setMetalWeight(e.target.value)}
                  />
                </div>
              </div>

              {/* Gemstones Section */}
              <div className="space-y-4">
                <div className="flex items-center justify-between">
                  <Label className="text-lg font-semibold">Gemstones</Label>
                  <Button
                    type="button"
                    variant="outline"
                    size="sm"
                    onClick={addGemstone}
                  >
                    <Plus className="w-4 h-4 mr-2" />
                    Add Gemstone
                  </Button>
                </div>

                {gemstones.map((gemstone, index) => (
                  <Card key={gemstone.id} className="p-4 bg-gray-50">
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                      <div className="space-y-2">
                        <Label>Gemstone Type</Label>
                        <Select
                          value={gemstone.type}
                          onValueChange={(value) =>
                            updateGemstone(gemstone.id, "type", value)
                          }
                        >
                          <SelectTrigger>
                            <SelectValue placeholder="Select gemstone" />
                          </SelectTrigger>
                          <SelectContent>
                            {gemstoneTypes.map((gem) => (
                              <SelectItem key={gem.value} value={gem.value}>
                                {gem.label}
                              </SelectItem>
                            ))}
                          </SelectContent>
                        </Select>
                      </div>

                      <div className="space-y-2">
                        <Label>Carat Weight</Label>
                        <Input
                          type="number"
                          step="0.01"
                          min="0.01"
                          placeholder="0.50"
                          value={gemstone.carat || ""}
                          onChange={(e) =>
                            updateGemstone(
                              gemstone.id,
                              "carat",
                              parseFloat(e.target.value) || 0
                            )
                          }
                        />
                      </div>

                      <div className="space-y-2">
                        <Label>Price per Carat ($)</Label>
                        <Input
                          type="number"
                          min="1"
                          placeholder="Price per carat"
                          value={gemstone.pricePerCarat || ""}
                          onChange={(e) =>
                            updateGemstone(
                              gemstone.id,
                              "pricePerCarat",
                              parseFloat(e.target.value) || 0
                            )
                          }
                        />
                      </div>

                      <Button
                        type="button"
                        variant="outline"
                        size="sm"
                        onClick={() => removeGemstone(gemstone.id)}
                        className="text-red-600 hover:text-red-700"
                      >
                        <Trash2 className="w-4 h-4" />
                      </Button>
                    </div>
                  </Card>
                ))}
              </div>

              {/* Making Charges */}
              <div className="space-y-2">
                <Label htmlFor="making">Making Charges (%)</Label>
                <Input
                  id="making"
                  type="number"
                  min="0"
                  max="100"
                  placeholder="20"
                  value={makingCharge}
                  onChange={(e) => setMakingCharge(e.target.value)}
                />
                <p className="text-sm text-muted-foreground">
                  Percentage of material cost for craftsmanship and labor
                </p>
              </div>

              {estimate && (
                <div className="mt-6 p-6 bg-indigo-50 rounded-lg border border-indigo-200">
                  <h3 className="text-xl font-semibold text-center mb-4">
                    Cost Breakdown
                  </h3>
                  <div className="space-y-3">
                    <div className="flex justify-between">
                      <span>Metal Cost:</span>
                      <span className="font-semibold">
                        ${estimate.metalCost.toLocaleString()}
                      </span>
                    </div>
                    <div className="flex justify-between">
                      <span>Gemstones Cost:</span>
                      <span className="font-semibold">
                        ${estimate.gemstonesCost.toLocaleString()}
                      </span>
                    </div>
                    <div className="flex justify-between">
                      <span>Making Charges:</span>
                      <span className="font-semibold">
                        ${estimate.makingCost.toLocaleString()}
                      </span>
                    </div>
                    <hr className="border-indigo-200" />
                    <div className="flex justify-between text-lg font-bold">
                      <span>Total Estimated Cost:</span>
                      <span className="text-indigo-700">
                        ${estimate.total.toLocaleString()}
                      </span>
                    </div>
                  </div>
                  <p className="text-sm text-muted-foreground text-center mt-4">
                    This is an approximate estimate. Final cost may vary based
                    on design complexity and market conditions.
                  </p>
                </div>
              )}
            </CardContent>
          </Card>

          {/* Information */}
          <Card className="bg-white/60 backdrop-blur-sm mb-8">
            <CardHeader>
              <CardTitle className="text-xl font-playfair text-center">
                Cost Factors to Consider
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-3">
                  <div>
                    <strong>Design Complexity:</strong> Intricate designs
                    require more labor time
                  </div>
                  <div>
                    <strong>Stone Setting:</strong> Multiple stones increase
                    craftsmanship costs
                  </div>
                  <div>
                    <strong>Metal Purity:</strong> Higher karat gold costs more
                    per gram
                  </div>
                </div>
                <div className="space-y-3">
                  <div>
                    <strong>Finishing:</strong> Polishing, engraving add to
                    final cost
                  </div>
                  <div>
                    <strong>Market Fluctuation:</strong> Metal and gemstone
                    prices vary daily
                  </div>
                  <div>
                    <strong>Certification:</strong> GIA certified stones command
                    premium prices
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>

          {/* Related Tools */}
          <div className="mb-8">
            <RelatedToolsCard currentPath="/tools/custom-cost-estimator" />
          </div>

          {/* Disclaimer */}
          <div className="p-6 bg-indigo-50 border border-indigo-200 rounded-lg">
            <p className="text-sm text-muted-foreground text-center">
              <strong>Disclaimer:</strong> This estimate is for planning
              purposes only. Actual costs may vary based on design complexity,
              market conditions, artisan skill level, and additional services.
              Always request detailed quotes from jewelers before commissioning
              custom pieces.
            </p>
          </div>
        </div>
      </div>
    </>
  );
};

export default CustomCostEstimator;
