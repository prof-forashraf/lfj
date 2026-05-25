import React, { useRef, useEffect, useState } from 'react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Camera, RotateCcw, Download, X } from 'lucide-react';

interface VirtualTryOnProps {
  productId?: string;
  jewelryType: 'ring' | 'necklace' | 'earrings' | 'bracelet';
  productImage?: string;
  productName?: string;
}

export const VirtualTryOn: React.FC<VirtualTryOnProps> = ({
  productId,
  jewelryType,
  productImage,
  productName
}) => {
  const videoRef = useRef<HTMLVideoElement>(null);
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const [isStreaming, setIsStreaming] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [jewelryPosition, setJewelryPosition] = useState({ x: 0, y: 0, scale: 1 });

  useEffect(() => {
    return () => {
      // Cleanup camera stream
      if (videoRef.current?.srcObject) {
        const stream = videoRef.current.srcObject as MediaStream;
        stream.getTracks().forEach(track => track.stop());
      }
    };
  }, []);

  const startCamera = async () => {
    try {
      setIsLoading(true);
      setError(null);

      const stream = await navigator.mediaDevices.getUserMedia({
        video: {
          width: { ideal: 1280 },
          height: { ideal: 720 },
          facingMode: 'user'
        }
      });

      if (videoRef.current) {
        videoRef.current.srcObject = stream;
        videoRef.current.onloadedmetadata = () => {
          setIsStreaming(true);
          setIsLoading(false);
          initializeFaceDetection();
        };
      }
    } catch (err) {
      setError('Camera access denied. Please allow camera permissions to try on jewelry virtually.');
      setIsLoading(false);
    }
  };

  const stopCamera = () => {
    if (videoRef.current?.srcObject) {
      const stream = videoRef.current.srcObject as MediaStream;
      stream.getTracks().forEach(track => track.stop());
      videoRef.current.srcObject = null;
    }
    setIsStreaming(false);
  };

  const initializeFaceDetection = () => {
    // Initialize face detection for jewelry positioning
    // This would typically use MediaPipe or similar library
    // For now, we'll use basic positioning based on jewelry type
    setJewelryPosition(getDefaultPosition(jewelryType));
  };

  const getDefaultPosition = (type: string) => {
    switch (type) {
      case 'necklace':
        return { x: 0.5, y: 0.6, scale: 0.8 };
      case 'earrings':
        return { x: 0.3, y: 0.4, scale: 0.3 };
      case 'ring':
        return { x: 0.7, y: 0.8, scale: 0.2 };
      case 'bracelet':
        return { x: 0.6, y: 0.75, scale: 0.4 };
      default:
        return { x: 0.5, y: 0.5, scale: 0.5 };
    }
  };

  const captureImage = () => {
    if (!canvasRef.current || !videoRef.current) return;

    const canvas = canvasRef.current;
    const video = videoRef.current;
    const ctx = canvas.getContext('2d');

    if (!ctx) return;

    // Set canvas size to video size
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    // Draw video frame
    ctx.drawImage(video, 0, 0);

    // Overlay jewelry image
    if (productImage) {
      const img = new Image();
      img.onload = () => {
        const x = jewelryPosition.x * canvas.width - (img.width * jewelryPosition.scale) / 2;
        const y = jewelryPosition.y * canvas.height - (img.height * jewelryPosition.scale) / 2;

        ctx.drawImage(
          img,
          x, y,
          img.width * jewelryPosition.scale,
          img.height * jewelryPosition.scale
        );

        // Download the image
        const link = document.createElement('a');
        link.download = `virtual-try-on-${productName || 'jewelry'}.png`;
        link.href = canvas.toDataURL();
        link.click();
      };
      img.src = productImage;
    }
  };

  const adjustPosition = (axis: 'x' | 'y' | 'scale', delta: number) => {
    setJewelryPosition(prev => ({
      ...prev,
      [axis]: Math.max(0, Math.min(1, prev[axis] + delta))
    }));
  };

  return (
    <Card className="w-full max-w-4xl mx-auto">
      <CardHeader>
        <CardTitle className="flex items-center gap-2">
          <Camera className="h-5 w-5" />
          Virtual Try-On
          {productName && (
            <Badge variant="secondary">{productName}</Badge>
          )}
        </CardTitle>
      </CardHeader>
      <CardContent className="space-y-4">
        {error && (
          <div className="bg-red-50 border border-red-200 rounded-lg p-4">
            <div className="flex items-center gap-2 text-red-700">
              <X className="h-4 w-4" />
              <span className="text-sm">{error}</span>
            </div>
          </div>
        )}

        <div className="relative bg-black rounded-lg overflow-hidden" style={{ aspectRatio: '16/9' }}>
          {!isStreaming ? (
            <div className="absolute inset-0 flex items-center justify-center bg-gray-900">
              <div className="text-center text-white">
                <Camera className="h-12 w-12 mx-auto mb-4 opacity-50" />
                <p className="text-lg mb-4">Ready for Virtual Try-On</p>
                <Button onClick={startCamera} disabled={isLoading}>
                  {isLoading ? 'Starting Camera...' : 'Start Camera'}
                </Button>
              </div>
            </div>
          ) : (
            <>
              <video
                ref={videoRef}
                autoPlay
                playsInline
                muted
                className="w-full h-full object-cover"
              />
              {productImage && (
                <img
                  src={productImage}
                  alt="Jewelry overlay"
                  className="absolute pointer-events-none"
                  style={{
                    left: `${jewelryPosition.x * 100}%`,
                    top: `${jewelryPosition.y * 100}%`,
                    transform: `translate(-50%, -50%) scale(${jewelryPosition.scale})`,
                    maxWidth: '200px',
                    maxHeight: '200px',
                  }}
                />
              )}
            </>
          )}
          <canvas ref={canvasRef} className="hidden" />
        </div>

        {isStreaming && (
          <div className="space-y-4">
            {/* Position Controls */}
            <div className="grid grid-cols-2 md:grid-cols-4 gap-2">
              <Button
                variant="outline"
                size="sm"
                onClick={() => adjustPosition('x', -0.01)}
              >
                ← Left
              </Button>
              <Button
                variant="outline"
                size="sm"
                onClick={() => adjustPosition('x', 0.01)}
              >
                Right →
              </Button>
              <Button
                variant="outline"
                size="sm"
                onClick={() => adjustPosition('y', -0.01)}
              >
                ↑ Up
              </Button>
              <Button
                variant="outline"
                size="sm"
                onClick={() => adjustPosition('y', 0.01)}
              >
                Down ↓
              </Button>
            </div>

            <div className="grid grid-cols-2 gap-2">
              <Button
                variant="outline"
                size="sm"
                onClick={() => adjustPosition('scale', -0.1)}
              >
                Smaller
              </Button>
              <Button
                variant="outline"
                size="sm"
                onClick={() => adjustPosition('scale', 0.1)}
              >
                Larger
              </Button>
            </div>

            {/* Action Buttons */}
            <div className="flex gap-2">
              <Button onClick={captureImage} className="flex-1">
                <Download className="h-4 w-4 mr-2" />
                Capture & Download
              </Button>
              <Button variant="outline" onClick={stopCamera}>
                <RotateCcw className="h-4 w-4 mr-2" />
                Stop Camera
              </Button>
            </div>
          </div>
        )}

        <div className="text-sm text-gray-600 dark:text-gray-400">
          <p className="mb-2">
            <strong>How to use:</strong> Allow camera access, position yourself in frame,
            and adjust the jewelry overlay using the controls above.
          </p>
          <p>
            <strong>Tip:</strong> For best results, ensure good lighting and position
            your face/neck clearly in the center of the frame.
          </p>
        </div>
      </CardContent>
    </Card>
  );
};