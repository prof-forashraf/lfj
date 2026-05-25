// src/components/studio/WebcamCapture.tsx
import React, { useRef, useCallback } from 'react';
import Webcam from 'react-webcam';
import { Button } from '@/components/ui/button';
import { Camera } from 'lucide-react';
import { DialogClose } from '@/components/ui/dialog';

interface WebcamCaptureProps {
  onCapture: (imageSrc: string) => void;
}

const WebcamCapture: React.FC<WebcamCaptureProps> = ({ onCapture }) => {
  const webcamRef = useRef<Webcam>(null);

  const capture = useCallback(() => {
    if (webcamRef.current) {
      const imageSrc = webcamRef.current.getScreenshot();
      if (imageSrc) {
        onCapture(imageSrc);
      }
    }
  }, [webcamRef, onCapture]);

  return (
    <div className="flex flex-col items-center gap-4">
      <Webcam
        audio={false}
        ref={webcamRef}
        screenshotFormat="image/jpeg"
        className="rounded-lg w-full"
        videoConstraints={{ facingMode: "user" }}
      />
      <DialogClose asChild>
        <Button onClick={capture} className="w-full">
          <Camera className="mr-2 h-4 w-4" /> Take Photo
        </Button>
      </DialogClose>
    </div>
  );
};

export default WebcamCapture;