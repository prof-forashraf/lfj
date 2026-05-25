import React, { useEffect, useRef, useState } from 'react';
import { Camera, Heart, RotateCcw, Share2, Upload } from 'lucide-react';
import { toast } from 'sonner';

interface VirtualTryOnProps {
  jewelryType: string;
  productName: string;
  productImage: string;
}

const EnhancedVirtualTryOn: React.FC<VirtualTryOnProps> = ({
  jewelryType,
  productName,
  productImage,
}) => {
  const videoRef = useRef<HTMLVideoElement>(null);
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const [isCameraActive, setIsCameraActive] = useState(false);
  const [uploadedImage, setUploadedImage] = useState<string | null>(null);
  const [jewelryPosition, setJewelryPosition] = useState({ x: 0, y: 0, scale: 1 });
  const [faceDetected, setFaceDetected] = useState(false);
  const [isWishlisted, setIsWishlisted] = useState(false);

  const startCamera = async () => {
    try {
      const stream = await navigator.mediaDevices.getUserMedia({ video: true });

      if (videoRef.current) {
        videoRef.current.srcObject = stream;
        setUploadedImage(null);
        setFaceDetected(false);
        setIsCameraActive(true);
      }
    } catch {
      toast.error('Camera access was denied. You can upload a photo instead.');
    }
  };

  const handleImageUpload = (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (e) => {
      setUploadedImage(e.target?.result as string);
      setIsCameraActive(false);
      setFaceDetected(false);
    };
    reader.readAsDataURL(file);
  };

  useEffect(() => {
    if (!isCameraActive && !uploadedImage) return;

    const timer = window.setTimeout(() => setFaceDetected(true), 900);
    return () => window.clearTimeout(timer);
  }, [isCameraActive, uploadedImage]);

  const renderJewelry = (ctx: CanvasRenderingContext2D) => {
    if (!faceDetected) return;

    const img = new Image();
    img.src = productImage;
    img.onload = () => {
      const { x, y, scale } = jewelryPosition;
      const width = Math.min(220, img.width) * scale;
      const height = Math.min(180, img.height) * scale;
      const centerX = ctx.canvas.width / 2 - width / 2 + x;
      const centerY = ctx.canvas.height / 2 - height / 2 + y;

      ctx.drawImage(img, centerX, centerY, width, height);
    };
  };

  useEffect(() => {
    const canvas = canvasRef.current;
    const video = videoRef.current;
    if (!canvas || !video) return;

    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    let frameId = 0;

    const draw = () => {
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      if (isCameraActive) {
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
      } else if (uploadedImage) {
        const img = new Image();
        img.src = uploadedImage;
        img.onload = () => {
          ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
          renderJewelry(ctx);
        };
      } else {
        ctx.fillStyle = '#f3f4f6';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
      }

      if (isCameraActive) {
        renderJewelry(ctx);
      }

      frameId = requestAnimationFrame(draw);
    };

    draw();
    return () => cancelAnimationFrame(frameId);
  }, [isCameraActive, uploadedImage, faceDetected, jewelryPosition, productImage]);

  const handleShare = async () => {
    const shareData = {
      title: `${productName} virtual try-on`,
      text: `I tried on ${productName} with the Latest Fashion Jewellery try-on tool.`,
      url: window.location.href,
    };

    if (navigator.share) {
      await navigator.share(shareData);
      return;
    }

    await navigator.clipboard.writeText(`${shareData.text} ${shareData.url}`);
    toast.success('Try-on link copied to clipboard.');
  };

  const handleWishlist = () => {
    const saved = JSON.parse(localStorage.getItem('lfj_try_on_wishlist') || '[]') as string[];
    const next = saved.includes(productName)
      ? saved.filter((item) => item !== productName)
      : [...saved, productName];

    localStorage.setItem('lfj_try_on_wishlist', JSON.stringify(next));
    setIsWishlisted(next.includes(productName));
    toast.success(next.includes(productName) ? 'Saved to wishlist.' : 'Removed from wishlist.');
  };

  return (
    <div className="max-w-4xl mx-auto bg-white rounded-lg shadow-xl overflow-hidden">
      <div className="p-6">
        <p className="text-xs font-semibold uppercase tracking-[0.22em] text-primary-gold mb-2">
          Virtual try-on
        </p>
        <h2 className="text-2xl font-playfair font-bold mb-4">
          {productName} - {jewelryType}
        </h2>

        <div className="flex flex-wrap gap-3 mb-6">
          <button
            type="button"
            onClick={startCamera}
            className="flex items-center gap-2 bg-navy-900 text-white px-4 py-2 rounded-md hover:bg-navy-800"
          >
            <Camera size={20} />
            Start Camera
          </button>

          <label className="flex items-center gap-2 border border-primary-gold/30 text-navy-900 px-4 py-2 rounded-md hover:bg-soft-cream cursor-pointer">
            <Upload size={20} />
            Upload Photo
            <input
              type="file"
              accept="image/*"
              onChange={handleImageUpload}
              className="hidden"
            />
          </label>

          <button
            type="button"
            onClick={handleShare}
            className="flex items-center gap-2 border border-gray-200 px-4 py-2 rounded-md hover:border-primary-gold hover:text-primary-gold"
          >
            <Share2 size={20} />
            Share
          </button>

          <button
            type="button"
            onClick={handleWishlist}
            className="flex items-center gap-2 border border-gray-200 px-4 py-2 rounded-md hover:border-primary-gold hover:text-primary-gold"
          >
            <Heart size={20} className={isWishlisted ? 'fill-current text-red-500' : ''} />
            {isWishlisted ? 'Saved' : 'Add to Wishlist'}
          </button>
        </div>

        <div className="relative bg-gray-100 rounded-lg overflow-hidden mb-6">
          <canvas
            ref={canvasRef}
            width={640}
            height={480}
            className="w-full h-auto"
          />
          <video
            ref={videoRef}
            autoPlay
            muted
            playsInline
            className="absolute inset-0 w-full h-full object-cover"
            style={{ display: isCameraActive ? 'block' : 'none' }}
          />

          {!faceDetected && (isCameraActive || uploadedImage) && (
            <div className="absolute inset-0 flex items-center justify-center bg-black/50 text-white">
              Detecting face...
            </div>
          )}
        </div>

        {faceDetected && (
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
              <label className="block text-sm font-medium mb-1">Horizontal</label>
              <input
                type="range"
                min="-160"
                max="160"
                value={jewelryPosition.x}
                onChange={(e) => setJewelryPosition((prev) => ({ ...prev, x: parseInt(e.target.value) }))}
                className="w-full"
              />
            </div>
            <div>
              <label className="block text-sm font-medium mb-1">Vertical</label>
              <input
                type="range"
                min="-160"
                max="160"
                value={jewelryPosition.y}
                onChange={(e) => setJewelryPosition((prev) => ({ ...prev, y: parseInt(e.target.value) }))}
                className="w-full"
              />
            </div>
            <div>
              <label className="block text-sm font-medium mb-1">Scale</label>
              <input
                type="range"
                min="0.5"
                max="2"
                step="0.1"
                value={jewelryPosition.scale}
                onChange={(e) => setJewelryPosition((prev) => ({ ...prev, scale: parseFloat(e.target.value) }))}
                className="w-full"
              />
            </div>
            <div className="flex items-end">
              <button
                type="button"
                onClick={() => setJewelryPosition({ x: 0, y: 0, scale: 1 })}
                className="flex items-center gap-2 bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-800"
              >
                <RotateCcw size={20} />
                Reset
              </button>
            </div>
          </div>
        )}

        <div className="mt-6 p-4 bg-soft-cream rounded-lg">
          <h3 className="font-semibold mb-2">How to use</h3>
          <ul className="text-sm text-gray-600 space-y-1 list-disc pl-5">
            <li>Allow camera access or upload a photo.</li>
            <li>Position your face in the center.</li>
            <li>Wait for face detection.</li>
            <li>Adjust jewelry position and size.</li>
            <li>Share the look or save it to your wishlist.</li>
          </ul>
        </div>
      </div>
    </div>
  );
};

export default EnhancedVirtualTryOn;
