import React, { useReducer, useMemo, useState } from "react";
import { Link } from "react-router-dom";
import { Helmet } from "react-helmet-async";
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
import { Button } from "@/components/ui/button";
import { ArrowLeft, RotateCcw, Diamond } from "lucide-react";
import { cn } from "@/lib/utils";

// --- Data and Types ---
interface RingSizeData {
  us: number;
  uk: string;
  eu: number;
  jp: number;
  diameter: number; // in mm
}

type SizeSystem = "us" | "uk" | "eu" | "jp" | "diameter";

const ringSizeData: RingSizeData[] = [
  { us: 3, uk: "F", eu: 44, jp: 4, diameter: 14.1 },
  { us: 3.5, uk: "G½", eu: 45.5, jp: 5, diameter: 14.5 },
  { us: 4, uk: "H½", eu: 47, jp: 7, diameter: 14.9 },
  { us: 4.5, uk: "I½", eu: 48, jp: 8, diameter: 15.3 },
  { us: 5, uk: "J½", eu: 49.5, jp: 9, diameter: 15.7 },
  { us: 5.5, uk: "L", eu: 50.5, jp: 10, diameter: 16.1 },
  { us: 6, uk: "M", eu: 52, jp: 11, diameter: 16.5 },
  { us: 6.5, uk: "N", eu: 53, jp: 12, diameter: 16.9 },
  { us: 7, uk: "O", eu: 54.5, jp: 14, diameter: 17.3 },
  { us: 7.5, uk: "P", eu: 55.5, jp: 15, diameter: 17.7 },
  { us: 8, uk: "Q", eu: 57, jp: 16, diameter: 18.1 },
  { us: 8.5, uk: "R", eu: 58, jp: 17, diameter: 18.5 },
  { us: 9, uk: "S", eu: 59.5, jp: 18, diameter: 18.9 },
  { us: 9.5, uk: "T", eu: 60.5, jp: 19, diameter: 19.4 },
  { us: 10, uk: "U", eu: 62, jp: 20, diameter: 19.8 },
  { us: 10.5, uk: "V", eu: 63, jp: 22, diameter: 20.2 },
  { us: 11, uk: "W", eu: 64.5, jp: 23, diameter: 20.6 },
  { us: 11.5, uk: "X", eu: 65.5, jp: 24, diameter: 21.0 },
  { us: 12, uk: "Y", eu: 67, jp: 25, diameter: 21.4 },
  { us: 12.5, uk: "Z", eu: 68, jp: 26, diameter: 21.8 },
  { us: 13, uk: "Z+1", eu: 69, jp: 27, diameter: 22.2 },
];

// --- State Management ---
interface ConverterState {
  us: string;
  uk: string;
  eu: string;
  jp: string;
  diameter: string;
}

type ConverterAction =
  | { type: "CONVERT"; payload: { from: SizeSystem; value: string } }
  | { type: "SET_ALL"; payload: ConverterState }
  | { type: "RESET" };

const initialState: ConverterState = {
  us: "",
  uk: "",
  eu: "",
  jp: "",
  diameter: "",
};

function findClosestSize(from: SizeSystem, value: string): RingSizeData | null {
  if (value.trim() === "") return null;

  let closestMatch: RingSizeData | null = null;
  let smallestDiff = Infinity;

  if (from === "uk") {
    const upperValue = value.toUpperCase();
    return (
      ringSizeData.find((data) => data.uk.toUpperCase() === upperValue) || null
    );
  }

  const numValue = parseFloat(value);
  if (isNaN(numValue)) return null;

  for (const sizeData of ringSizeData) {
    const diff = Math.abs(sizeData[from] - numValue);
    if (diff < smallestDiff) {
      smallestDiff = diff;
      closestMatch = sizeData;
    }
  }

  // Only return a match if it's reasonably close
  return smallestDiff < (from === "diameter" ? 0.25 : 0.3)
    ? closestMatch
    : null;
}

function converterReducer(
  state: ConverterState,
  action: ConverterAction
): ConverterState {
  switch (action.type) {
    case "CONVERT": {
      const { from, value } = action.payload;
      if (value.trim() === "") return initialState;

      const matchedSize = findClosestSize(from, value);

      if (matchedSize) {
        return {
          us: matchedSize.us.toString(),
          uk: matchedSize.uk,
          eu: matchedSize.eu.toString(),
          jp: matchedSize.jp.toString(),
          diameter: matchedSize.diameter.toFixed(1),
        };
      }
      // If no match, only update the field being typed in
      return { ...initialState, [from]: value };
    }
    case "SET_ALL":
      return action.payload;
    case "RESET":
      return initialState;
    default:
      return state;
  }
}

const RingSizeConverter: React.FC = () => {
  const [state, dispatch] = useReducer(converterReducer, initialState);
  const [hoveredSize, setHoveredSize] = useState<RingSizeData | null>(null);

  const handleInputChange = (from: SizeSystem, value: string) => {
    dispatch({ type: "CONVERT", payload: { from, value } });
  };

  const reset = () => dispatch({ type: "RESET" });

  const displayState = hoveredSize
    ? {
        us: hoveredSize.us.toString(),
        uk: hoveredSize.uk,
        eu: hoveredSize.eu.toString(),
        jp: hoveredSize.jp.toString(),
        diameter: hoveredSize.diameter.toFixed(1),
      }
    : state;

  return (
    <>
      <SEOMetaTags
        title="Ring Size Converter | International Ring Size Chart | LatestFashionJewellery"
        description="Convert ring sizes between US, UK, European, and Japanese standards. Professional ring sizing tool with diameter measurements and comprehensive size chart."
        canonical="/tools/ring-size-converter"
        keywords="ring size converter, ring size chart, US ring size, UK ring size, European ring size, Japanese ring size, ring diameter converter, ring sizing guide"
      />

      <div className="min-h-screen bg-gradient-to-br from-purple-50 via-background to-pink-50 py-12">
        <div className="container mx-auto px-4 max-w-5xl">
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
                Ring Size Converter
              </h1>
              <p className="text-muted-foreground text-lg max-w-2xl mx-auto">
                Convert ring sizes between international standards. Type in any
                field to see the conversion.
              </p>
            </div>
          </div>

          <Card className="bg-white/80 backdrop-blur-sm border-2 border-purple-100 mb-8">
            <CardHeader>
              <CardTitle className="text-center text-purple-700">
                International Ring Size Conversions
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-x-6 gap-y-4">
                {/* US Input */}
                <div>
                  <Label htmlFor="us">US / Canada</Label>
                  <Input
                    id="us"
                    value={displayState.us}
                    onChange={(e) => handleInputChange("us", e.target.value)}
                    placeholder="e.g., 7.5"
                    className="mt-1 font-semibold"
                  />
                </div>
                {/* UK Input */}
                <div>
                  <Label htmlFor="uk">UK / Australia</Label>
                  <Input
                    id="uk"
                    value={displayState.uk}
                    onChange={(e) => handleInputChange("uk", e.target.value)}
                    placeholder="e.g., P"
                    className="mt-1 font-semibold"
                  />
                </div>
                {/* EU Input */}
                <div>
                  <Label htmlFor="eu">Europe</Label>
                  <Input
                    id="eu"
                    value={displayState.eu}
                    onChange={(e) => handleInputChange("eu", e.target.value)}
                    placeholder="e.g., 55.5"
                    className="mt-1 font-semibold"
                  />
                </div>
                {/* JP Input */}
                <div>
                  <Label htmlFor="jp">Japan</Label>
                  <Input
                    id="jp"
                    value={displayState.jp}
                    onChange={(e) => handleInputChange("jp", e.target.value)}
                    placeholder="e.g., 15"
                    className="mt-1 font-semibold"
                  />
                </div>
                {/* Diameter Input */}
                <div>
                  <Label htmlFor="diameter">Diameter (mm)</Label>
                  <Input
                    id="diameter"
                    value={displayState.diameter}
                    onChange={(e) =>
                      handleInputChange("diameter", e.target.value)
                    }
                    placeholder="e.g., 17.7"
                    className="mt-1 font-semibold"
                  />
                </div>
              </div>
              <div className="text-center mt-6">
                <Button
                  onClick={reset}
                  variant="outline"
                  className="gap-2 bg-white/80"
                >
                  <RotateCcw className="h-4 w-4" />
                  Reset
                </Button>
              </div>
            </CardContent>
          </Card>

          <Card className="bg-white/60 backdrop-blur-sm mb-8">
            <CardHeader>
              <CardTitle className="text-xl font-playfair text-center">
                International Ring Size Chart
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div className="max-h-96 overflow-y-auto pr-4">
                <table className="w-full text-sm text-left">
                  <thead className="sticky top-0 bg-white/80 backdrop-blur-sm">
                    <tr className="border-b">
                      <th className="p-2 font-semibold">US/Canada</th>
                      <th className="p-2 font-semibold">UK/Australia</th>
                      <th className="p-2 font-semibold">Europe</th>
                      <th className="p-2 font-semibold">Japan</th>
                      <th className="p-2 font-semibold">Diameter (mm)</th>
                    </tr>
                  </thead>
                  <tbody onMouseLeave={() => setHoveredSize(null)}>
                    {ringSizeData.map((size) => (
                      <tr
                        key={size.us}
                        onMouseEnter={() => setHoveredSize(size)}
                        className="border-b border-gray-100 hover:bg-purple-100/50 cursor-pointer"
                      >
                        <td className="p-2 font-medium">{size.us}</td>
                        <td className="p-2">{size.uk}</td>
                        <td className="p-2">{size.eu}</td>
                        <td className="p-2">{size.jp}</td>
                        <td className="p-2">{size.diameter.toFixed(1)}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </CardContent>
          </Card>

          <div className="mb-8">
            <RelatedToolsCard currentPath="/tools/ring-size-converter" />
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            <Card className="bg-white/60 backdrop-blur-sm">
              <CardHeader>
                <CardTitle className="text-xl font-playfair">
                  How to Measure
                </CardTitle>
              </CardHeader>
              <CardContent className="space-y-4">
                <p>
                  <strong>Using a Ring:</strong> Measure the inner diameter of a
                  ring that fits well using a ruler or caliper.
                </p>
                <p>
                  <strong>Using String:</strong> Wrap string around your finger,
                  mark where it overlaps, then measure the length and divide by
                  π (3.14) to find the diameter.
                </p>
              </CardContent>
            </Card>
            <Card className="bg-white/60 backdrop-blur-sm">
              <CardHeader>
                <CardTitle className="text-xl font-playfair">
                  Tips for Accuracy
                </CardTitle>
              </CardHeader>
              <CardContent className="space-y-4">
                <p>
                  <strong>Best Time:</strong> Measure your finger at the end of
                  the day when it's largest.
                </p>
                <p>
                  <strong>Temperature:</strong> Avoid measuring when your hands
                  are very cold or warm.
                </p>
                <p>
                  <strong>Knuckle Size:</strong> Ensure the ring can slide over
                  your knuckle comfortably.
                </p>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </>
  );
};

export default RingSizeConverter;
