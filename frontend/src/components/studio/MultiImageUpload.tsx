// src/components/studio/MultiImageUpload.tsx
import React, { useCallback, useState } from 'react';
import { useDropzone } from 'react-dropzone';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { AspectRatio } from '@/components/ui/aspect-ratio';
import { Badge } from '@/components/ui/badge';
import { X, Upload, Camera, CheckCircle, Trash2 } from 'lucide-react';
import { toast } from 'sonner';
import Compressor from 'compressorjs';
import { motion, AnimatePresence } from 'framer-motion';

interface UploadedImage {
  id: string;
  url: string;
  file: File;
  name: string;
  size: string;
  isSelected?: boolean;
}

interface MultiImageUploadProps {
  onImagesChange: (images: UploadedImage[]) => void;
  onImageSelect: (imageUrl: string) => void;
  maxImages?: number;
  acceptedFormats?: string[];
}

const MultiImageUpload: React.FC<MultiImageUploadProps> = ({
  onImagesChange,
  onImageSelect,
  maxImages = 10,
  acceptedFormats = ['image/jpeg', 'image/png', 'image/webp']
}) => {
  const [images, setImages] = useState<UploadedImage[]>([]);
  const [isProcessing, setIsProcessing] = useState(false);

  const compressImage = (file: File): Promise<File> => {
    return new Promise((resolve, reject) => {
      new Compressor(file, {
        quality: 0.8,
        maxWidth: 1920,
        maxHeight: 1920,
        success: (result) => resolve(result as File),
        error: reject,
      });
    });
  };

  const processFiles = useCallback(async (files: File[]) => {
    if (files.length + images.length > maxImages) {
      toast.error(`Maximum ${maxImages} images allowed`);
      return;
    }

    setIsProcessing(true);
    const newImages: UploadedImage[] = [];

    try {
      for (const file of files) {
        if (!acceptedFormats.includes(file.type)) {
          toast.error(`${file.name} is not a supported format`);
          continue;
        }

        const compressedFile = await compressImage(file);
        const url = URL.createObjectURL(compressedFile);
        const sizeInMB = (compressedFile.size / (1024 * 1024)).toFixed(2);

        newImages.push({
          id: `${Date.now()}-${Math.random()}`,
          url,
          file: compressedFile,
          name: file.name,
          size: `${sizeInMB} MB`,
        });
      }

      const updatedImages = [...images, ...newImages];
      setImages(updatedImages);
      onImagesChange(updatedImages);
      
      if (newImages.length > 0) {
        toast.success(`Added ${newImages.length} image(s)`);
      }
    } catch (error) {
      toast.error('Failed to process images');
    } finally {
      setIsProcessing(false);
    }
  }, [images, maxImages, acceptedFormats, onImagesChange]);

  const { getRootProps, getInputProps, isDragActive } = useDropzone({
    onDrop: processFiles,
    accept: {
      'image/*': acceptedFormats.map(format => format.split('/')[1])
    },
    multiple: true,
    disabled: isProcessing || images.length >= maxImages
  });

  const removeImage = (id: string) => {
    const imageToRemove = images.find(img => img.id === id);
    if (imageToRemove) {
      URL.revokeObjectURL(imageToRemove.url);
    }
    const updatedImages = images.filter(img => img.id !== id);
    setImages(updatedImages);
    onImagesChange(updatedImages);
    toast.success('Image removed');
  };

  const selectImage = (image: UploadedImage) => {
    const updatedImages = images.map(img => ({
      ...img,
      isSelected: img.id === image.id
    }));
    setImages(updatedImages);
    onImageSelect(image.url);
    toast.success(`Selected ${image.name}`);
  };

  const clearAllImages = () => {
    images.forEach(image => URL.revokeObjectURL(image.url));
    setImages([]);
    onImagesChange([]);
    toast.success('All images cleared');
  };

  const formatBytes = (bytes: number) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  };

  return (
    <div className="space-y-4">
      {/* Upload Area */}
      <Card className={`border-2 border-dashed transition-colors ${
        isDragActive ? 'border-primary bg-primary/5' : 'border-gray-300 hover:border-gray-400'
      } ${isProcessing ? 'opacity-50 pointer-events-none' : ''}`}>
        <CardContent 
          {...getRootProps()} 
          className="p-8 text-center cursor-pointer"
        >
          <input {...getInputProps()} />
          <div className="flex flex-col items-center gap-2">
            {isDragActive ? (
              <Upload className="h-8 w-8 text-primary animate-bounce" />
            ) : (
              <Camera className="h-8 w-8 text-gray-400" />
            )}
            <p className="text-lg font-medium">
              {isDragActive ? 'Drop images here...' : 'Drag & drop images or click to browse'}
            </p>
            <p className="text-sm text-gray-500">
              Supports JPEG, PNG, WebP • Max {maxImages} images • Up to 10MB each
            </p>
            {isProcessing && (
              <Badge variant="outline" className="mt-2">Processing...</Badge>
            )}
          </div>
        </CardContent>
      </Card>

      {/* Images Grid */}
      {images.length > 0 && (
        <div className="space-y-4">
          <div className="flex items-center justify-between">
            <h3 className="font-semibold">
              Uploaded Images ({images.length}/{maxImages})
            </h3>
            <Button variant="outline" size="sm" onClick={clearAllImages}>
              <Trash2 className="h-4 w-4 mr-2" />
              Clear All
            </Button>
          </div>

          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <AnimatePresence>
              {images.map((image) => (
                <motion.div
                  key={image.id}
                  initial={{ opacity: 0, scale: 0.9 }}
                  animate={{ opacity: 1, scale: 1 }}
                  exit={{ opacity: 0, scale: 0.9 }}
                  className="relative group"
                >
                  <Card className={`overflow-hidden cursor-pointer transition-all ${
                    image.isSelected ? 'ring-2 ring-primary' : 'hover:shadow-lg'
                  }`}>
                    <CardContent className="p-0">
                      <AspectRatio ratio={1}>
                        <img
                          src={image.url}
                          alt={image.name}
                          className="object-cover w-full h-full"
                          onClick={() => selectImage(image)}
                          loading="lazy"
                          width={800}
                          height={600}
                          onError={(event) => { event.currentTarget.src = "/images/placeholder.svg"; }}
                        />
                        {image.isSelected && (
                          <div className="absolute inset-0 bg-primary/20 flex items-center justify-center">
                            <CheckCircle className="h-8 w-8 text-primary bg-white rounded-full" />
                          </div>
                        )}
                      </AspectRatio>
                      
                      {/* Image Info */}
                      <div className="p-2 bg-white">
                        <p className="text-xs font-medium truncate">{image.name}</p>
                        <p className="text-xs text-gray-500">{image.size}</p>
                      </div>

                      {/* Remove Button */}
                      <Button
                        variant="destructive"
                        size="icon"
                        className="absolute top-2 right-2 h-6 w-6 opacity-0 group-hover:opacity-100 transition-opacity"
                        onClick={(e) => {
                          e.stopPropagation();
                          removeImage(image.id);
                        }}
                      >
                        <X className="h-3 w-3" />
                      </Button>
                    </CardContent>
                  </Card>
                </motion.div>
              ))}
            </AnimatePresence>
          </div>
        </div>
      )}
    </div>
  );
};

export default MultiImageUpload;
