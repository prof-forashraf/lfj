
// src/components/landing/JewelleryTryOn.tsx
import React from 'react';
import { Link } from 'react-router-dom';
import { Button } from '@/components/ui/button';
import { ArrowRight, Camera, Sparkles, Zap } from 'lucide-react';

const JewelleryTryOn: React.FC = () => {
    return (
        <section className="section-padding bg-gradient-to-r from-primary/5 via-accent/5 to-primary/5 overflow-hidden">
            <div className="container mx-auto px-6">
                <div className="flex flex-col lg:flex-row items-center gap-12 xl:gap-16">
                    {/* Content */}
                    <div className="lg:w-1/2 text-center lg:text-left">
                        <div className="inline-block px-4 py-2 bg-primary/10 text-primary text-sm font-medium rounded-full border border-primary/20 mb-6">
                            🚀 Innovation Meets Beauty
                        </div>
                        
                        <h2 className="text-4xl md:text-5xl lg:text-6xl font-playfair font-bold heading-elegant mb-6">
                            Virtual Try-On
                            <span className="block text-3xl md:text-4xl lg:text-5xl text-muted-foreground font-cormorant font-light">
                                Experience
                            </span>
                        </h2>
                        
                        <p className="text-lg md:text-xl text-elegant mb-8 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                            Step into the future of jewellery shopping. See how our stunning pieces complement your unique style with cutting-edge AR technology.
                        </p>

                        {/* Features */}
                        <div className="flex flex-wrap gap-6 mb-10 justify-center lg:justify-start">
                            <div className="flex items-center space-x-2 text-sm text-muted-foreground">
                                <Camera className="w-4 h-4 text-primary" />
                                <span>Real-time Preview</span>
                            </div>
                            <div className="flex items-center space-x-2 text-sm text-muted-foreground">
                                <Zap className="w-4 h-4 text-primary" />
                                <span>Instant Results</span>
                            </div>
                            <div className="flex items-center space-x-2 text-sm text-muted-foreground">
                                <Sparkles className="w-4 h-4 text-primary" />
                                <span>HD Quality</span>
                            </div>
                        </div>
                        
                        <Button
                            size="lg"
                            className="btn-elegant group px-8 py-4 text-lg font-semibold"
                            asChild
                        >
                            <Link to="/jewellery-studio">
                                Launch Try-On Studio
                                <ArrowRight className="ml-2 h-5 w-5 transition-transform group-hover:translate-x-1" />
                            </Link>
                        </Button>
                    </div>
                    
                    {/* Visual */}
                    <div className="lg:w-1/2 relative">
                        <div className="relative rounded-2xl overflow-hidden elegant-shadow hover-glow transition-all duration-500">
                            <img
                                src="https://images.unsplash.com/photo-1649972904349-6e44c42644a7?q=80&w=1200&auto=format&fit=crop"
                                srcSet="https://images.unsplash.com/photo-1649972904349-6e44c42644a7?q=80&w=400&auto=format&fit=crop 400w, https://images.unsplash.com/photo-1649972904349-6e44c42644a7?q=80&w=800&auto=format&fit=crop 800w, https://images.unsplash.com/photo-1649972904349-6e44c42644a7?q=80&w=1200&auto=format&fit=crop 1200w"
                                sizes="(max-width: 768px) 100vw, 560px"
                                alt="Virtual Jewellery Try-On Experience"
                                className="w-full h-auto rounded-2xl"
                                loading="lazy"
                                decoding="async"
                                width={800}
                                height={600}
                                onError={(event) => { event.currentTarget.src = "/images/placeholder.svg"; }} />
                            
                            {/* Overlay Gradient */}
                            <div className="absolute inset-0 bg-gradient-to-t from-primary/20 via-transparent to-transparent rounded-2xl"></div>
                            
                            {/* Floating Elements */}
                            <div className="absolute top-6 right-6 bg-background/90 backdrop-blur-sm rounded-full p-3 animate-float">
                                <Sparkles className="w-6 h-6 text-primary" />
                            </div>
                            
                            <div className="absolute bottom-6 left-6 bg-background/90 backdrop-blur-sm rounded-lg px-4 py-2">
                                <p className="text-sm font-medium text-foreground">AI-Powered Fitting</p>
                            </div>
                        </div>
                        
                        {/* Decorative Elements */}
                        <div className="absolute -top-4 -right-4 w-24 h-24 luxury-gradient rounded-full opacity-20 animate-float" style={{ animationDelay: '1s' }}></div>
                        <div className="absolute -bottom-4 -left-4 w-16 h-16 rose-gold-gradient rounded-full opacity-30 animate-float" style={{ animationDelay: '2s' }}></div>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default JewelleryTryOn;
