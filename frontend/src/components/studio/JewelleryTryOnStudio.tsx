import React, { useState, useRef, useEffect, useCallback } from "react";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Slider } from "@/components/ui/slider";
import { Label } from "@/components/ui/label";
import { Badge } from "@/components/ui/badge";
import { toast } from "sonner";
import {
  Camera,
  Pin,
  RefreshCw as RefreshIcon,
  Share2,
  Trash2,
  Move,
  RotateCw,
  Layers,
  ArrowUp,
  ArrowDown,
  Images,
  SwitchCamera,
  ArrowLeft,
} from "lucide-react";
import Webcam from "react-webcam";
import { motion, AnimatePresence } from "framer-motion";
import MultiImageUpload from "./MultiImageUpload";
import { useFaceLandmarker, FaceLandmarks } from "@/hooks/useFaceLandmarker";

interface Product {
  id: number | string;
  name: string;
  item_type: string;
  image_url: string | null;
  try_on_image_url: string | null;
}

interface UploadedImage {
  id: string;
  url: string;
  file?: File;
  name: string;
  size?: string;
  isSelected?: boolean;
}

const modelImages: UploadedImage[] = [
  { id: "model1", url: "/models/model-1.jpg", name: "Model 1" },
  { id: "model2", url: "/models/model-2.jpg", name: "Model 2" },
  { id: "model3", url: "/models/model-3.jpg", name: "Model 3" },
];

interface CanvasJewelleryItem extends Product {
  canvasId: string;
  position: { x: number; y: number };
  scale: number;
  rotation: number;
  opacity: number;
  zIndex: number;
}
interface JewelleryTryOnStudioProps {
  userImage: string | null;
  newlySelectedJewellery: Product | null;
  previewJewellery: Product | null;
  onUserImageUpload: (imageUrl: string) => void;
  onItemAddedToCanvas: () => void;
  onReset: () => void;
}

const JewelleryTryOnStudio: React.FC<JewelleryTryOnStudioProps> = ({
  userImage,
  newlySelectedJewellery,
  previewJewellery,
  onUserImageUpload,
  onItemAddedToCanvas,
  onReset,
}) => {
  const [canvasItems, setCanvasItems] = useState<CanvasJewelleryItem[]>([]);
  const [activeItemId, setActiveItemId] = useState<string | null>(null);
  const [faceLandmarks, setFaceLandmarks] = useState<FaceLandmarks | null>(
    null
  );
  const [isProcessing, setIsProcessing] = useState(false);
  const [mode, setMode] = useState<"initial" | "webcam" | "upload" | "preview">(
    "initial"
  );
  const [uploadedImages, setUploadedImages] = useState<UploadedImage[]>([]);
  const [currentImageIndex, setCurrentImageIndex] = useState(0);
  const [facingMode, setFacingMode] = useState<"user" | "environment">("user");

  const { landmarkEngine, error: landmarkError } = useFaceLandmarker();
  const canvasRef = useRef<HTMLDivElement>(null);
  const fileInputRef = useRef<HTMLInputElement>(null);
  const userImageRef = useRef<HTMLImageElement>(null);
  const webcamRef = useRef<Webcam>(null);

  useEffect(() => {
    if (landmarkError) toast.error(landmarkError);
  }, [landmarkError]);

  useEffect(() => {
    if (userImage === null) {
      setCanvasItems([]);
      setActiveItemId(null);
      setFaceLandmarks(null);
      setMode("initial");
    } else {
      processImageForLandmarks(userImage);
      setMode("preview");
    }
  }, [userImage]);

  useEffect(() => {
    if (newlySelectedJewellery && userImage) {
      if (!newlySelectedJewellery.try_on_image_url) {
        toast.error("Sorry, this item isn't available for virtual try-on.");
        onItemAddedToCanvas();
        return;
      }

      const newItemOnCanvas: CanvasJewelleryItem = {
        ...newlySelectedJewellery,
        canvasId: `item-${Date.now()}`,
        position: { x: 50, y: 50 },
        scale:
          newlySelectedJewellery.item_type === "earrings"
            ? 0.08
            : newlySelectedJewellery.item_type === "rings"
            ? 0.1
            : 0.2,
        rotation: 0,
        opacity: 1,
        zIndex:
          canvasItems.length > 0
            ? Math.max(...canvasItems.map((item) => item.zIndex)) + 1
            : 1,
      };
      setCanvasItems((prevItems) => [...prevItems, newItemOnCanvas]);
      setActiveItemId(newItemOnCanvas.canvasId);
      onItemAddedToCanvas();
      toast.success(`Added ${newItemOnCanvas.name}`);
    }
  }, [newlySelectedJewellery, userImage, onItemAddedToCanvas]);

  const processImageForLandmarks = useCallback(
    async (imageSrc: string) => {
      if (!landmarkEngine || !imageSrc) return;
      const image = new Image();
      image.crossOrigin = "Anonymous";
      image.src = imageSrc;
      image.onload = async () => {
        setIsProcessing(true);
        toast.info("AI is analyzing your photo...");
        try {
          const landmarks = await landmarkEngine(image);
          setFaceLandmarks(landmarks);
          toast.success(
            landmarks
              ? "AI analysis complete!"
              : "AI couldn't detect a face clearly. Manual placement is enabled."
          );
        } catch (e) {
          console.error("Face landmark analysis error:", e);
          toast.error("An error occurred during AI analysis.");
        } finally {
          setIsProcessing(false);
        }
      };
    },
    [landmarkEngine]
  );

  const handleCapture = useCallback(() => {
    if (webcamRef.current) {
      const imageSrc = webcamRef.current.getScreenshot();
      if (imageSrc) onUserImageUpload(imageSrc);
    }
  }, [webcamRef, onUserImageUpload]);

  const handleModelSelect = (model: UploadedImage) => {
    onUserImageUpload(model.url);
  };

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files[0]) {
      onUserImageUpload(URL.createObjectURL(e.target.files[0]));
    }
  };

  const handleMultiImageUpload = (images: UploadedImage[]) => {
    setUploadedImages(images);
    if (images.length > 0) {
      const selectedImage = images.find((img) => img.isSelected) || images[0];
      setCurrentImageIndex(images.indexOf(selectedImage));
      onUserImageUpload(selectedImage.url);
    }
  };

  const handleImageSelect = (imageUrl: string) => {
    const imageIndex = uploadedImages.findIndex((img) => img.url === imageUrl);
    if (imageIndex !== -1) {
      setCurrentImageIndex(imageIndex);
      onUserImageUpload(imageUrl);
    }
  };

  const switchToNextImage = () => {
    if (uploadedImages.length > 1) {
      const nextIndex = (currentImageIndex + 1) % uploadedImages.length;
      setCurrentImageIndex(nextIndex);
      onUserImageUpload(uploadedImages[nextIndex].url);
    }
  };

  const switchToPrevImage = () => {
    if (uploadedImages.length > 1) {
      const prevIndex =
        currentImageIndex === 0
          ? uploadedImages.length - 1
          : currentImageIndex - 1;
      setCurrentImageIndex(prevIndex);
      onUserImageUpload(uploadedImages[prevIndex].url);
    }
  };

  const toggleCamera = () => {
    setFacingMode((prev) => (prev === "user" ? "environment" : "user"));
  };

  const handleLayerChange = (direction: "up" | "down") => {
    if (!activeItemId) return;
    setCanvasItems((prevItems) => {
      const sorted = [...prevItems].sort((a, b) => a.zIndex - b.zIndex);
      const currentIndex = sorted.findIndex(
        (item) => item.canvasId === activeItemId
      );

      if (direction === "up" && currentIndex < sorted.length - 1) {
        [sorted[currentIndex], sorted[currentIndex + 1]] = [
          sorted[currentIndex + 1],
          sorted[currentIndex],
        ];
      } else if (direction === "down" && currentIndex > 0) {
        [sorted[currentIndex], sorted[currentIndex - 1]] = [
          sorted[currentIndex - 1],
          sorted[currentIndex],
        ];
      }
      return sorted.map((item, index) => ({ ...item, zIndex: index }));
    });
  };

  const handleTransform = (
    e: React.MouseEvent<HTMLDivElement>,
    itemId: string,
    action: "drag" | "resize" | "rotate"
  ) => {
    e.preventDefault();
    e.stopPropagation();
    const canvasNode = canvasRef.current;
    if (!canvasNode) return;

    const itemToTransform = canvasItems.find((i) => i.canvasId === itemId);
    if (!itemToTransform) return;

    const startState = { ...itemToTransform };
    const startMouse = { x: e.clientX, y: e.clientY };
    const canvasRect = canvasNode.getBoundingClientRect();
    const itemRect = e.currentTarget.getBoundingClientRect();
    const itemCenter = {
      x: itemRect.left + itemRect.width / 2,
      y: itemRect.top + itemRect.height / 2,
    };

    const onMouseMove = (moveEvent: MouseEvent) => {
      moveEvent.preventDefault();
      const mouse = { x: moveEvent.clientX, y: moveEvent.clientY };

      setCanvasItems((prevItems) =>
        prevItems.map((currentItem) => {
          if (currentItem.canvasId === itemId) {
            let { position, scale, rotation } = currentItem;

            switch (action) {
              case "drag": {
                const dx = mouse.x - startMouse.x;
                const dy = mouse.y - startMouse.y;
                position = {
                  x: startState.position.x + (dx / canvasRect.width) * 100,
                  y: startState.position.y + (dy / canvasRect.height) * 100,
                };
                break;
              }
              case "resize": {
                const initialDist = Math.hypot(
                  startMouse.x - itemCenter.x,
                  startMouse.y - itemCenter.y
                );
                const currentDist = Math.hypot(
                  mouse.x - itemCenter.x,
                  mouse.y - itemCenter.y
                );
                scale =
                  initialDist > 0
                    ? startState.scale * (currentDist / initialDist)
                    : startState.scale;
                break;
              }
              case "rotate": {
                const startAngle = Math.atan2(
                  startMouse.y - itemCenter.y,
                  startMouse.x - itemCenter.x
                );
                const currentAngle = Math.atan2(
                  mouse.y - itemCenter.y,
                  mouse.x - itemCenter.x
                );
                const angleDiff = currentAngle - startAngle;
                rotation = startState.rotation + angleDiff * (180 / Math.PI);
                break;
              }
            }
            return { ...currentItem, position, scale, rotation };
          }
          return currentItem;
        })
      );
    };

    const onMouseUp = () => {
      document.removeEventListener("mousemove", onMouseMove);
      document.removeEventListener("mouseup", onMouseUp);
    };

    document.addEventListener("mousemove", onMouseMove);
    document.addEventListener("mouseup", onMouseUp);
  };

  const handleQuickPlacement = (
    itemId: string,
    position?: { x: number; y: number }
  ) => {
    if (!position || !userImageRef.current) {
      toast.error("AI landmark not found for this position.");
      return;
    }
    const { naturalWidth, naturalHeight } = userImageRef.current;
    const relativeX = (position.x / naturalWidth) * 100;
    const relativeY = (position.y / naturalHeight) * 100;
    setCanvasItems((items) =>
      items.map((item) =>
        item.canvasId === itemId
          ? { ...item, position: { x: relativeX, y: relativeY } }
          : item
      )
    );
  };

  const sortedCanvasItems = React.useMemo(
    () => [...canvasItems].sort((a, b) => a.zIndex - b.zIndex),
    [canvasItems]
  );

  const activeItem = canvasItems.find((item) => item.canvasId === activeItemId);

  // ✅ REPLACED: This is the final, high-performance share function.
  const handleShare = async () => {
    if (!userImage) return;

    // Helper function to robustly load and decode an image
    const loadImage = async (src: string): Promise<HTMLImageElement> => {
      const img = new Image();
      img.crossOrigin = "Anonymous";
      img.src = src;
      // .decode() returns a promise that resolves when the image is ready to be used.
      await img.decode();
      return img;
    };

    const promise = (async () => {
        // 1. Concurrently load the background and all jewellery images
        const backgroundPromise = loadImage(userImage);
        const itemPromises = sortedCanvasItems.map((item) =>
          loadImage(item.try_on_image_url!)
        );

        // Wait for everything to be fully loaded and decoded
        const [backgroundImage, ...loadedItemImages] = await Promise.all([
          backgroundPromise,
          ...itemPromises,
        ]);

        // 2. Create the high-resolution canvas
        const canvas = document.createElement("canvas");
        canvas.width = backgroundImage.naturalWidth;
        canvas.height = backgroundImage.naturalHeight;
        const ctx = canvas.getContext("2d");
        if (!ctx) {
          throw new Error("Could not create canvas context.");
        }

        // 3. Draw the background image first
        ctx.drawImage(backgroundImage, 0, 0, canvas.width, canvas.height);

        // 4. Draw each jewellery item on top with its transformations
        sortedCanvasItems.forEach((item, index) => {
          const img = loadedItemImages[index];

          const canvasX = (item.position.x / 100) * canvas.width;
          const canvasY = (item.position.y / 100) * canvas.height;

          // This makes an item at max scale (0.5) roughly 20% of the canvas width
          const desiredWidth = item.scale * (canvas.width * 0.4);
          const scaleFactor = desiredWidth / img.naturalWidth;
          const finalWidth = img.naturalWidth * scaleFactor;
          const finalHeight = img.naturalHeight * scaleFactor;

          ctx.save();
          ctx.translate(canvasX, canvasY);
          ctx.rotate(item.rotation * (Math.PI / 180));
          ctx.drawImage(
            img,
            -finalWidth / 2,
            -finalHeight / 2,
            finalWidth,
            finalHeight
          );
          ctx.restore();
        });

        // 5. Trigger the download
        const image = canvas.toDataURL("image/png");
        const link = document.createElement("a");
        link.href = image;
        link.download = "virtual-try-on.png";
        link.click();
      return "Success!";
    })();

    toast.promise(promise, {
      loading: "Creating your high-quality image...",
      success: "Image downloaded! 🎉",
      error: (err: any) => {
        console.error("Failed to create image:", err);
        return "Could not create image. Please try again.";
      },
    });
  };

  return (
    <div className="space-y-4">
      <Card className="overflow-hidden shadow-lg">
        <CardContent
          ref={canvasRef}
          className="p-0 relative w-full bg-gray-100 flex items-center justify-center"
          style={{
            height: "clamp(400px, 70vh, 650px)",
            backgroundImage: userImage ? `url(${userImage})` : "none",
            backgroundSize: "contain",
            backgroundPosition: "center",
            backgroundRepeat: "no-repeat",
          }}
        >
          {!userImage ? (
            <AnimatePresence>
              {mode === "initial" && (
                <motion.div
                  initial={{ opacity: 0 }}
                  animate={{ opacity: 1 }}
                  exit={{ opacity: 0 }}
                  className="text-center p-8 flex flex-col items-center gap-4"
                >
                  <h3 className="text-xl font-semibold text-gray-700">
                    Start Your Virtual Try-On
                  </h3>
                  <p className="text-gray-500 max-w-sm">
                    Choose an option below to see how our stunning jewellery
                    looks on you.
                  </p>
                  <div className="flex flex-col sm:flex-row gap-4 mt-4">
                    <Button size="lg" onClick={() => setMode("upload")}>
                      <Images className="mr-2 h-5 w-5" />
                      Upload Photos
                    </Button>
                    <Button
                      size="lg"
                      variant="outline"
                      onClick={() => setMode("webcam")}
                    >
                      <Camera className="mr-2 h-5 w-5" />
                      Use Webcam
                    </Button>
                  </div>
                  <input
                    ref={fileInputRef}
                    type="file"
                    hidden
                    accept="image/*"
                    onChange={handleFileChange}
                  />
                </motion.div>
              )}
              {mode === "webcam" && (
                <motion.div
                  initial={{ opacity: 0 }}
                  animate={{ opacity: 1 }}
                  className="w-full h-full flex flex-col items-center justify-center bg-black"
                >
                  <Webcam
                    audio={false}
                    ref={webcamRef}
                    screenshotFormat="image/jpeg"
                    className="w-full h-full object-cover"
                    videoConstraints={{ facingMode }}
                  />
                  <div className="absolute bottom-5 z-10 flex gap-4">
                    <Button
                      size="lg"
                      onClick={toggleCamera}
                      variant="secondary"
                      className="rounded-full h-12 w-12 p-0 shadow-lg"
                    >
                      <SwitchCamera className="h-6 w-6" />
                    </Button>
                    <Button
                      size="lg"
                      onClick={handleCapture}
                      className="rounded-full h-16 w-16 p-0 shadow-lg"
                    >
                      <Camera className="h-8 w-8" />
                    </Button>
                    <Button
                      size="lg"
                      variant="secondary"
                      onClick={() => setMode("initial")}
                    >
                      Cancel
                    </Button>
                  </div>
                </motion.div>
              )}
              {mode === "upload" && (
                <motion.div
                  initial={{ opacity: 0 }}
                  animate={{ opacity: 1 }}
                  className="w-full h-full p-4 flex flex-col"
                >
                  <div className="mb-4 flex items-center justify-between flex-shrink-0">
                    <h3 className="text-lg font-semibold">
                      Upload Your Photos
                    </h3>
                    <Button
                      variant="outline"
                      onClick={() => setMode("initial")}
                    >
                      <ArrowLeft className="h-4 w-4 mr-2" />
                      Back
                    </Button>
                  </div>
                  <MultiImageUpload
                    onImagesChange={handleMultiImageUpload}
                    onImageSelect={handleImageSelect}
                    maxImages={20}
                  />
                </motion.div>
              )}
            </AnimatePresence>
          ) : (
            <>
              <img
                ref={userImageRef}
                src={userImage}
                alt="User"
                className="absolute w-0 h-0 opacity-0 pointer-events-none"
               loading="lazy" width={800} height={600} onError={(event) => { event.currentTarget.src = "/images/placeholder.svg"; }} />
              {isProcessing && (
                <div className="absolute inset-0 bg-black/40 flex items-center justify-center z-50 text-white">
                  <p>AI is processing...</p>
                </div>
              )}
              <AnimatePresence>
                {sortedCanvasItems.map((item) => (
                  <motion.div
                    key={item.canvasId}
                    className="absolute select-none"
                    style={{
                      left: `${item.position.x}%`,
                      top: `${item.position.y}%`,
                      width: `${item.scale * 200}px`,
                      transform: `translate(-50%, -50%) rotate(${item.rotation}deg)`,
                      zIndex: item.zIndex,
                      opacity: item.opacity,
                    }}
                    onMouseDown={(e) => {
                      setActiveItemId(item.canvasId);
                      handleTransform(e, item.canvasId, "drag");
                    }}
                    initial={{ opacity: 0, scale: 0.5 }}
                    animate={{ opacity: item.opacity, scale: 1 }}
                    exit={{ opacity: 0, scale: 0.5 }}
                    transition={{ type: "spring", stiffness: 260, damping: 20 }}
                  >
                    <img
                      src={item.try_on_image_url!}
                      alt={item.name}
                      draggable="false"
                      className="w-full h-full object-contain pointer-events-none drop-shadow-lg"
                     loading="lazy" width={800} height={600} onError={(event) => { event.currentTarget.src = "/images/placeholder.svg"; }} />

                    {activeItemId === item.canvasId && (
                      <>
                        <div
                          className="absolute -top-3 -right-3 p-1 bg-white rounded-full shadow-md cursor-alias"
                          onMouseDown={(e) =>
                            handleTransform(e, item.canvasId, "rotate")
                          }
                        >
                          <RotateCw className="h-4 w-4 text-primary" />
                        </div>
                        <div
                          className="absolute -bottom-3 -right-3 p-1 bg-white rounded-full shadow-md cursor-nwse-resize"
                          onMouseDown={(e) =>
                            handleTransform(e, item.canvasId, "resize")
                          }
                        >
                          <Move className="h-4 w-4 text-primary" />
                        </div>
                        <div className="absolute inset-0 border-2 border-dashed border-primary/70 pointer-events-none"></div>
                      </>
                    )}
                  </motion.div>
                ))}
              </AnimatePresence>

              {previewJewellery && (
                <div
                  className="absolute p-1 pointer-events-none"
                  style={{
                    left: `50%`,
                    top: `50%`,
                    width: "100px",
                    transform: `translate(-50%, -50%)`,
                    opacity: 0.5,
                    zIndex: 999,
                  }}
                >
                  <img
                    src={previewJewellery.try_on_image_url!}
                    alt="preview"
                    draggable="false"
                    className="w-full h-full object-contain drop-shadow-lg"
                   loading="lazy" width={800} height={600} onError={(event) => { event.currentTarget.src = "/images/placeholder.svg"; }} />
                </div>
              )}
            </>
          )}
        </CardContent>
      </Card>

      <div className="flex flex-wrap gap-2 items-center">
        <Button variant="outline" onClick={onReset} disabled={!userImage}>
          <RefreshIcon className="mr-2 h-4 w-4" />
          Change Photo
        </Button>
        <Button
          variant="outline"
          onClick={handleShare}
          disabled={!userImage || canvasItems.length === 0}
        >
          <Share2 className="mr-2 h-4 w-4" />
          Save Result
        </Button>

        {uploadedImages.length > 1 && userImage && (
          <>
            <div className="flex items-center gap-2 ml-auto">
              <Button size="sm" variant="outline" onClick={switchToPrevImage}>
                ←
              </Button>
              <Badge variant="secondary">
                {currentImageIndex + 1} / {uploadedImages.length}
              </Badge>
              <Button size="sm" variant="outline" onClick={switchToNextImage}>
                →
              </Button>
            </div>
          </>
        )}
      </div>

      <AnimatePresence>
        {activeItem && (
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: 20 }}
          >
            <Card className="mt-4 shadow-lg">
              <CardHeader>
                <CardTitle className="text-lg flex justify-between items-center">
                  Edit: {activeItem.name}
                  <Button
                    variant="ghost"
                    size="icon"
                    onClick={() =>
                      setCanvasItems((items) =>
                        items.filter((i) => i.canvasId !== activeItem.canvasId)
                      )
                    }
                  >
                    <Trash2 className="h-4 w-4 text-destructive" />
                  </Button>
                </CardTitle>
              </CardHeader>
              <CardContent className="p-4 space-y-6">
                {faceLandmarks && (
                  <div className="space-y-2 p-3 bg-gray-50 rounded-lg">
                    <Label className="font-semibold flex items-center">
                      <Pin className="h-4 w-4 mr-2" />
                      AI Quick Placement
                    </Label>
                    <div className="flex flex-wrap gap-2">
                      <Button
                        variant="secondary"
                        size="sm"
                        onClick={() => processImageForLandmarks(userImage!)}
                      >
                        <RefreshIcon className="mr-2 h-4 w-4" />
                        Re-Scan
                      </Button>
                      {activeItem.item_type === "earrings" && (
                        <>
                          <Button
                            variant="secondary"
                            size="sm"
                            disabled={!faceLandmarks.leftEarTragion}
                            onClick={() =>
                              handleQuickPlacement(
                                activeItem.canvasId,
                                faceLandmarks.leftEarTragion
                              )
                            }
                          >
                            Left Ear
                          </Button>
                          <Button
                            variant="secondary"
                            size="sm"
                            disabled={!faceLandmarks.rightEarTragion}
                            onClick={() =>
                              handleQuickPlacement(
                                activeItem.canvasId,
                                faceLandmarks.rightEarTragion
                              )
                            }
                          >
                            Right Ear
                          </Button>
                        </>
                      )}
                      {activeItem.item_type === "necklaces" && (
                        <Button
                          variant="secondary"
                          size="sm"
                          disabled={!faceLandmarks.chin}
                          onClick={() =>
                            handleQuickPlacement(
                              activeItem.canvasId,
                              faceLandmarks.chin
                            )
                          }
                        >
                          On Neck
                        </Button>
                      )}
                    </div>
                  </div>
                )}
                <div>
                  <Label htmlFor="scale-slider">
                    Scale ({(activeItem.scale * 100).toFixed(0)}%)
                  </Label>
                  <Slider
                    id="scale-slider"
                    value={[activeItem.scale]}
                    onValueChange={([v]) =>
                      setCanvasItems((items) =>
                        items.map((i) =>
                          i.canvasId === activeItem.canvasId
                            ? { ...i, scale: v }
                            : i
                        )
                      )
                    }
                    max={0.5}
                    min={0.01}
                    step={0.01}
                  />
                </div>
                <div>
                  <Label htmlFor="rotation-slider">
                    Rotation ({activeItem.rotation.toFixed(0)}°)
                  </Label>
                  <Slider
                    id="rotation-slider"
                    value={[activeItem.rotation]}
                    onValueChange={([v]) =>
                      setCanvasItems((items) =>
                        items.map((i) =>
                          i.canvasId === activeItem.canvasId
                            ? { ...i, rotation: v }
                            : i
                        )
                      )
                    }
                    max={180}
                    min={-180}
                    step={1}
                  />
                </div>
                <div>
                  <Label htmlFor="opacity-slider">
                    Opacity ({(activeItem.opacity * 100).toFixed(0)}%)
                  </Label>
                  <Slider
                    id="opacity-slider"
                    value={[activeItem.opacity]}
                    onValueChange={([v]) =>
                      setCanvasItems((items) =>
                        items.map((i) =>
                          i.canvasId === activeItem.canvasId
                            ? { ...i, opacity: v }
                            : i
                        )
                      )
                    }
                    max={1}
                    min={0}
                    step={0.01}
                  />
                </div>
                <div>
                  <Label className="flex items-center">
                    <Layers className="h-4 w-4 mr-2" /> Layering
                  </Label>
                  <div className="flex gap-2 mt-2">
                    <Button
                      variant="outline"
                      size="sm"
                      onClick={() => handleLayerChange("up")}
                    >
                      <ArrowUp className="mr-2 h-4 w-4" /> Forward
                    </Button>
                    <Button
                      variant="outline"
                      size="sm"
                      onClick={() => handleLayerChange("down")}
                    >
                      <ArrowDown className="mr-2 h-4 w-4" /> Backward
                    </Button>
                  </div>
                </div>
              </CardContent>
            </Card>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
};

export default JewelleryTryOnStudio;
