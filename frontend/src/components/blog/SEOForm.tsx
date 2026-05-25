
import React, { useState, useEffect } from 'react';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Card, CardContent } from '@/components/ui/card';
import { CheckCircle2, AlertCircle, Tag } from 'lucide-react';

interface SEOFormProps {
  title: string;
  metaDescription: string;
  slug: string;
  openGraph: {
    title: string;
    description: string;
    image: string;
  };
  onTitleChange: (value: string) => void;
  onMetaDescriptionChange: (value: string) => void;
  onSlugChange: (value: string) => void;
  onOGTitleChange: (value: string) => void;
  onOGDescriptionChange: (value: string) => void;
  onOGImageChange: (value: string) => void;
}

const SEOForm: React.FC<SEOFormProps> = ({
  title,
  metaDescription,
  slug,
  openGraph,
  onTitleChange,
  onMetaDescriptionChange,
  onSlugChange,
  onOGTitleChange,
  onOGDescriptionChange,
  onOGImageChange
}) => {
  const [slugValue, setSlugValue] = useState(slug);
  const [titleScore, setTitleScore] = useState({ score: 0, message: '' });
  const [descriptionScore, setDescriptionScore] = useState({ score: 0, message: '' });

  // Update slug when title changes, but only if slug is empty or user hasn't manually changed it
  useEffect(() => {
    if (!slug && title) {
      const generatedSlug = title
        .toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-');
      
      setSlugValue(generatedSlug);
      onSlugChange(generatedSlug);
    }
  }, [title, slug, onSlugChange]);

  // Evaluate title
  useEffect(() => {
    if (!title) {
      setTitleScore({ score: 0, message: 'Title is required' });
      return;
    }

    if (title.length < 10) {
      setTitleScore({ score: 1, message: 'Title is too short (aim for 50-60 characters)' });
    } else if (title.length > 70) {
      setTitleScore({ score: 2, message: 'Title is too long (aim for 50-60 characters)' });
    } else if (title.length > 45 && title.length < 65) {
      setTitleScore({ score: 4, message: 'Title length is optimal' });
    } else {
      setTitleScore({ score: 3, message: 'Title length is acceptable' });
    }
  }, [title]);

  // Evaluate description
  useEffect(() => {
    if (!metaDescription) {
      setDescriptionScore({ score: 0, message: 'Meta description is required' });
      return;
    }

    if (metaDescription.length < 50) {
      setDescriptionScore({ score: 1, message: 'Description is too short (aim for 150-160 characters)' });
    } else if (metaDescription.length > 160) {
      setDescriptionScore({ score: 2, message: 'Description is too long (aim for 150-160 characters)' });
    } else if (metaDescription.length > 145 && metaDescription.length < 160) {
      setDescriptionScore({ score: 4, message: 'Description length is optimal' });
    } else {
      setDescriptionScore({ score: 3, message: 'Description length is acceptable' });
    }
  }, [metaDescription]);

  const handleSlugChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const value = e.target.value
      .toLowerCase()
      .replace(/[^\w-]/g, '-') // Replace non-word chars with dashes
      .replace(/--+/g, '-')     // Replace multiple dashes with single dash
      .replace(/^-+|-+$/g, ''); // Remove leading/trailing dashes
    
    setSlugValue(value);
    onSlugChange(value);
  };

  return (
    <Tabs defaultValue="basic">
      <TabsList className="grid w-full grid-cols-3">
        <TabsTrigger value="basic">Basic SEO</TabsTrigger>
        <TabsTrigger value="social">Social Media</TabsTrigger>
        <TabsTrigger value="preview">Preview</TabsTrigger>
      </TabsList>
      
      <TabsContent value="basic" className="space-y-4 mt-4">
        <div className="space-y-2">
          <div className="flex justify-between items-start">
            <Label htmlFor="title">Meta Title</Label>
            <div className="text-xs text-muted-foreground">
              {title.length}/70
            </div>
          </div>
          <Input
            id="title"
            value={title}
            onChange={(e) => onTitleChange(e.target.value)}
            placeholder="Enter SEO title"
            maxLength={70}
          />
          <div className="flex items-center text-xs">
            {titleScore.score > 0 && (
              <>
                {titleScore.score >= 3 ? (
                  <CheckCircle2 className="h-3 w-3 text-green-500 mr-1" />
                ) : (
                  <AlertCircle className="h-3 w-3 text-amber-500 mr-1" />
                )}
                <span className={titleScore.score >= 3 ? "text-green-500" : "text-amber-500"}>
                  {titleScore.message}
                </span>
              </>
            )}
          </div>
        </div>
        
        <div className="space-y-2">
          <div className="flex justify-between items-start">
            <Label htmlFor="meta-description">Meta Description</Label>
            <div className="text-xs text-muted-foreground">
              {metaDescription.length}/160
            </div>
          </div>
          <Textarea
            id="meta-description"
            value={metaDescription}
            onChange={(e) => onMetaDescriptionChange(e.target.value)}
            placeholder="Enter meta description"
            maxLength={160}
          />
          <div className="flex items-center text-xs">
            {descriptionScore.score > 0 && (
              <>
                {descriptionScore.score >= 3 ? (
                  <CheckCircle2 className="h-3 w-3 text-green-500 mr-1" />
                ) : (
                  <AlertCircle className="h-3 w-3 text-amber-500 mr-1" />
                )}
                <span className={descriptionScore.score >= 3 ? "text-green-500" : "text-amber-500"}>
                  {descriptionScore.message}
                </span>
              </>
            )}
          </div>
        </div>
        
        <div className="space-y-2">
          <div className="flex items-center justify-between">
            <Label htmlFor="slug">URL Slug</Label>
            <Tag className="h-4 w-4 text-muted-foreground" />
          </div>
          <div className="flex items-center">
            <span className="text-muted-foreground text-sm bg-muted px-2 py-1 rounded-l-md border border-r-0">
              yoursite.com/blog/
            </span>
            <Input
              id="slug"
              value={slugValue}
              onChange={handleSlugChange}
              placeholder="url-slug"
              className="rounded-l-none"
            />
          </div>
        </div>
      </TabsContent>
      
      <TabsContent value="social" className="space-y-4 mt-4">
        <div className="space-y-2">
          <Label htmlFor="og-title">Social Title</Label>
          <Input
            id="og-title"
            value={openGraph.title}
            onChange={(e) => onOGTitleChange(e.target.value)}
            placeholder="Enter social media title"
          />
          <p className="text-xs text-muted-foreground">
            Leave blank to use the same as meta title
          </p>
        </div>
        
        <div className="space-y-2">
          <Label htmlFor="og-description">Social Description</Label>
          <Textarea
            id="og-description"
            value={openGraph.description}
            onChange={(e) => onOGDescriptionChange(e.target.value)}
            placeholder="Enter social media description"
          />
          <p className="text-xs text-muted-foreground">
            Leave blank to use the same as meta description
          </p>
        </div>
        
        <div className="space-y-2">
          <Label htmlFor="og-image">Social Image URL</Label>
          <Input
            id="og-image"
            type="url"
            value={openGraph.image}
            onChange={(e) => onOGImageChange(e.target.value)}
            placeholder="https://example.com/image.jpg"
          />
          <p className="text-xs text-muted-foreground">
            1200 x 630 pixels recommended for optimal display on social platforms
          </p>
        </div>
      </TabsContent>
      
      <TabsContent value="preview" className="mt-4">
        <div className="space-y-4">
          <Card>
            <CardContent className="pt-6">
              <h3 className="text-base font-medium mb-1">Google Search Preview</h3>
              <div className="p-4 border rounded-md bg-white">
                <p className="text-[#1a0dab] text-lg font-medium mb-1 truncate">
                  {title || "Title example - Your Website Name"}
                </p>
                <p className="text-[#006621] text-sm mb-1">
                  yoursite.com/blog/{slugValue || "page-url"}
                </p>
                <p className="text-[#545454] text-sm line-clamp-2">
                  {metaDescription || "This is an example meta description. It will be displayed in search results below the title of your page."}
                </p>
              </div>
            </CardContent>
          </Card>
          
          <Card>
            <CardContent className="pt-6">
              <h3 className="text-base font-medium mb-1">Facebook Preview</h3>
              <div className="border rounded-md overflow-hidden bg-[#f2f3f5]">
                {openGraph.image ? (
                  <div 
                    className="h-40 bg-cover bg-center" 
                    style={{ backgroundImage: `url(${openGraph.image})` }}
                  />
                ) : (
                  <div className="h-40 bg-gray-200 flex items-center justify-center">
                    <span className="text-gray-500">No image provided</span>
                  </div>
                )}
                <div className="p-3 bg-white">
                  <p className="text-[#385898] text-xs uppercase">yoursite.com</p>
                  <p className="font-bold text-[#1c1e21] mt-1">
                    {openGraph.title || title || "Your Article Title"}
                  </p>
                  <p className="text-[#606770] text-sm mt-1 line-clamp-2">
                    {openGraph.description || metaDescription || "Your article description will appear here. Make it engaging to encourage clicks."}
                  </p>
                </div>
              </div>
            </CardContent>
          </Card>
          
          <Card>
            <CardContent className="pt-6">
              <h3 className="text-base font-medium mb-1">Twitter Preview</h3>
              <div className="border rounded-md overflow-hidden">
                {openGraph.image ? (
                  <div 
                    className="h-36 bg-cover bg-center" 
                    style={{ backgroundImage: `url(${openGraph.image})` }}
                  />
                ) : (
                  <div className="h-36 bg-gray-200 flex items-center justify-center">
                    <span className="text-gray-500">No image provided</span>
                  </div>
                )}
                <div className="p-3">
                  <p className="font-bold text-gray-900">
                    {openGraph.title || title || "Your Article Title"}
                  </p>
                  <p className="text-gray-600 text-sm mt-1 line-clamp-2">
                    {openGraph.description || metaDescription || "Your article description will appear here. Keep it under 200 characters for best results."}
                  </p>
                  <p className="text-gray-500 text-xs mt-2">yoursite.com</p>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
      </TabsContent>
    </Tabs>
  );
};

export default SEOForm;
