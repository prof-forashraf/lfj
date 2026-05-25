import React, { useState } from "react";
import { Helmet } from "react-helmet-async";
import JewelleryTryOnStudio from "@/components/studio/JewelleryTryOnStudio";
import JewellerySelection from "@/components/studio/JewellerySelection";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbSeparator,
  BreadcrumbPage,
} from "@/components/ui/breadcrumb";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import {
  ArrowLeft,
  Sparkles as SparklesIcon,
  ImageUp as UploadCloudIcon,
} from "lucide-react";
import { Link } from "react-router-dom";
import { toast } from "sonner";

import { useStudioProducts } from "@/hooks/useProducts";
import { Product } from "@/services/productService";

const JewelleryStudioPage: React.FC = () => {
  const [selectedJewelleryForCanvas, setSelectedJewelleryForCanvas] =
    useState<Product | null>(null);
  const [previewJewellery, setPreviewJewellery] = useState<Product | null>(
    null
  );
  const [userImage, setUserImage] = useState<string | null>(null);
  const [activeTab, setActiveTab] = useState("jewellery");

  // ✅ Added search term state
  const [searchTerm, setSearchTerm] = useState("");

  // ✅ Now using searchTerm in the hook
  const { data: availableItems, isLoading: itemsLoading } =
    useStudioProducts(searchTerm);

  const handleJewellerySelect = (jewellery: Product) => {
    setPreviewJewellery(null);
    setSelectedJewelleryForCanvas(jewellery);
    if (!userImage) {
      toast.info("Upload your photo to see the item!");
    } else {
      setActiveTab("try-on");
    }
  };

  const handleUserImageUpload = (imageUrl: string) => {
    setUserImage(imageUrl);
    setSelectedJewelleryForCanvas(null);
    setActiveTab("jewellery");
    toast.info("Great! Now select your first item from the gallery.");
  };

  const handleResetCanvas = () => {
    setUserImage(null);
    setSelectedJewelleryForCanvas(null);
    setPreviewJewellery(null);
    toast.info(
      "Canvas cleared. Feel free to choose another photo or use your webcam."
    );
  };

  const handleItemAddedToCanvas = () => {
    setSelectedJewelleryForCanvas(null);
  };

  return (
    <>
      <Helmet>
        <title>
          AI Virtual Jewellery Try-On Studio | Latest Fashion Jewellery
        </title>
        <meta
          name="description"
          content="Experience the future of shopping with our AI-powered Virtual Try-On Studio. See jewellery perfectly placed on you with live camera support."
        />
      </Helmet>

      <div className="min-h-screen bg-gradient-to-br from-pink-50 via-purple-50 to-indigo-100 dark:from-gray-800 dark:via-gray-900 dark:to-black pt-0">
        <div className="container mx-auto px-2 sm:px-4 py-6 md:py-8">
          <div className="flex items-center justify-between mb-6">
            <Breadcrumb>
              <BreadcrumbList>
                <BreadcrumbItem>
                  <BreadcrumbLink asChild>
                    <Link
                      to="/"
                      className="text-primary-gold hover:text-opacity-80"
                    >
                      Home
                    </Link>
                  </BreadcrumbLink>
                </BreadcrumbItem>
                <BreadcrumbSeparator />
                <BreadcrumbItem>
                  <BreadcrumbPage className="font-semibold text-dark-slate">
                    AI Jewellery Studio
                  </BreadcrumbPage>
                </BreadcrumbItem>
              </BreadcrumbList>
            </Breadcrumb>

            <Button
              variant="outline"
              size="sm"
              asChild
              className="border-primary-gold/50 text-primary-gold hover:bg-primary-gold/10"
            >
              <Link to="/">
                <ArrowLeft className="mr-2 h-4 w-4" />
                Back to Home
              </Link>
            </Button>
          </div>

          <header className="text-center mb-8 md:mb-12">
            <h1 className="text-3xl md:text-4xl lg:text-5xl font-bold text-dark-slate font-playfair">
              AI Virtual Try-On Studio
            </h1>
            <p className="mt-3 text-md md:text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto font-lato">
              Our AI places jewellery on you instantly. Use your webcam or
              upload a photo to begin.
            </p>
          </header>

          <div className="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8">
            <div className="lg:col-span-8 xl:col-span-9">
              <JewelleryTryOnStudio
                userImage={userImage}
                newlySelectedJewellery={selectedJewelleryForCanvas}
                previewJewellery={previewJewellery}
                onUserImageUpload={handleUserImageUpload}
                onItemAddedToCanvas={handleItemAddedToCanvas}
                onReset={handleResetCanvas}
              />
            </div>

            <div className="lg:col-span-4 xl:col-span-3">
              <Card className="shadow-xl h-full">
                <Tabs
                  value={activeTab}
                  onValueChange={setActiveTab}
                  className="w-full flex flex-col h-full"
                >
                  <TabsList className="grid w-full grid-cols-2 sticky top-0 bg-gray-100 dark:bg-gray-800 z-10 rounded-t-lg shadow">
                    <TabsTrigger value="jewellery">Select Item</TabsTrigger>
                    <TabsTrigger value="try-on">Adjust Look</TabsTrigger>
                  </TabsList>

                  <TabsContent
                    value="jewellery"
                    className="flex-grow overflow-y-auto p-3 pt-4 md:p-4"
                  >
                    <JewellerySelection
                      availableItems={availableItems || []}
                      onSelect={handleJewellerySelect}
                      onPreview={setPreviewJewellery}
                      isLoading={itemsLoading}
                      searchTerm={searchTerm}
                      onSearchChange={setSearchTerm}
                    />
                  </TabsContent>

                  <TabsContent
                    value="try-on"
                    className="flex-grow overflow-auto p-4 md:p-6"
                  >
                    {!userImage ? (
                      <div className="text-center p-8 flex flex-col items-center h-full justify-center">
                        <UploadCloudIcon className="h-12 w-12 text-gray-400 mb-4" />
                        <p className="text-lg text-gray-500 mb-2">
                          Upload a photo or use your webcam
                        </p>
                        <p className="text-xs text-muted-foreground">
                          Then select items to start your AI try-on!
                        </p>
                      </div>
                    ) : (
                      <div className="text-center p-8 flex flex-col items-center h-full justify-center">
                        <SparklesIcon className="h-12 w-12 text-primary-gold mb-4" />
                        <p className="text-lg text-gray-500 mb-2">
                          You're ready to style!
                        </p>
                        <p className="text-sm text-muted-foreground">
                          Adjust items on the canvas or select more from the
                          'Select Item' tab.
                        </p>
                      </div>
                    )}
                  </TabsContent>
                </Tabs>
              </Card>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default JewelleryStudioPage;
