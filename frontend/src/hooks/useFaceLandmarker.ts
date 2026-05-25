// src/hooks/useFaceLandmarker.ts
import { useState, useEffect } from 'react';
import { FaceLandmarker, FilesetResolver } from '@mediapipe/tasks-vision';

export interface FaceLandmarks {
    leftEarTragion: { x: number; y: number };
    rightEarTragion: { x: number; y: number };
    noseTip: { x: number; y: number };
    chin: { x: number; y: number };
}

export const useFaceLandmarker = () => {    
    const [landmarkEngine, setLandmarkEngine] = useState<((image: HTMLImageElement) => Promise<FaceLandmarks | null>) | null>(null);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        const initEngine = async () => {
            try {
                const vision = await FilesetResolver.forVisionTasks("https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.3/wasm");
                const faceLandmarker = await FaceLandmarker.createFromOptions(vision, {
                    baseOptions: {
                        modelAssetPath: `https://storage.googleapis.com/mediapipe-models/face_landmarker/face_landmarker/float16/1/face_landmarker.task`,
                        delegate: 'GPU',
                    },
                    outputFaceBlendshapes: false,
                    outputFacialTransformationMatrixes: false,
                    numFaces: 1,
                });
                
                const engine = async (image: HTMLImageElement): Promise<FaceLandmarks | null> => {
                    const results = faceLandmarker.detect(image);
                    if (results.faceLandmarks && results.faceLandmarks.length > 0) {
                        const landmarks = results.faceLandmarks[0];
                        // Key landmark indices for jewellery
                        // Left Ear: 127, Right Ear: 356, Nose Tip: 1, Chin: 152
                        return {
                            leftEarTragion: { x: landmarks[127].x * image.naturalWidth, y: landmarks[127].y * image.naturalHeight },
                            rightEarTragion: { x: landmarks[356].x * image.naturalWidth, y: landmarks[356].y * image.naturalHeight },
                            noseTip: { x: landmarks[1].x * image.naturalWidth, y: landmarks[1].y * image.naturalHeight },
                            chin: { x: landmarks[152].x * image.naturalWidth, y: landmarks[152].y * image.naturalHeight + 40 }, // Offset for necklace
                        };
                    }
                    return null;
                };
                setLandmarkEngine(() => engine);
            } catch (e: any) {
                console.error("Failed to initialize FaceLandmarker:", e);
                setError("Could not load AI model. Please check your internet connection and try again.");
            }
        };

        initEngine();
    }, []);

    return { landmarkEngine, error };
};