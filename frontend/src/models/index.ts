
// Model definitions for the application
export interface Category {
  id: number;
  name: string;
  slug: string;
  description: string;
  image: string;
  productCount: number;
}

export interface Product {
  id: number;
  title: string;
  description: string;
  price: number;
  image: string;
  category: string;
  amazonUrl: string;
  isFeatured?: boolean;
  isBestSeller?: boolean;
  isTrending?: boolean;
  isAffordable?: boolean;
  isLuxury?: boolean;
}

export interface Event {
  id: number;
  title: string;
  description: string;
  date: string;
  location: string;
  image: string;
  isVirtual: boolean;
  registrationUrl?: string;
}

export interface Testimonial {
  id: number;
  name: string;
  avatar: string;
  rating: number;
  comment: string;
  product: string;
}

export interface Collection {
  id: number;
  name: string;
  slug: string;
  description: string;
  image: string;
  productCount: number;
}
