export type CmsVideo = {
  url: string;
  alt?: string | null;
  width: number;
  height: number;
  duration: number;
  size: number;
};

export type Product = {
  id: string;
  name: string;
  slug: string;
  category: string;
  description: string;
  longDescription?: string;
  features: readonly string[];
  image: string | null;
  imageAlt?: string;
  featured?: boolean;
  video?: CmsVideo;
  seo?: { title: string | null; description: string | null };
};

export type PortfolioItem = {
  id: string;
  title: string;
  location: string;
  category: string;
  year: string;
  description: string;
  image: string | null;
  imageAlt?: string;
  clientName?: string;
  featured?: boolean;
  video?: CmsVideo;
};
