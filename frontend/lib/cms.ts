import "server-only";

import {
  BLOG_POSTS,
  COMPANY,
  FAQS,
  PORTFOLIO,
  PRODUCTS,
  type PortfolioItem,
  type Product,
} from "@/lib/constants";

export type CmsSection = {
  key: string;
  eyebrow: string | null;
  title: string;
  description: string | null;
  image: string | null;
  imageAlt: string | null;
};

export type CmsTestimonial = {
  name: string;
  companyRole: string | null;
  quote: string;
  rating: number;
};

export type CmsBlogPost = {
  slug: string;
  title: string;
  excerpt: string;
  image: string | null;
  imageAlt: string | null;
  publishedAt: string;
  contentHtml?: string;
};

export type CmsHomeContent = {
  site: {
    companyName: string;
    shortName: string;
    tagline: string;
    description: string;
    phone: string;
    whatsapp: string;
    email: string;
    address?: Record<string, string>;
    businessHours?: string | null;
    statistics: Array<{ value: string; label: string }>;
  };
  sections: CmsSection[];
  products: Product[];
  portfolio: PortfolioItem[];
  testimonials: CmsTestimonial[];
  faqs: Array<{ question: string; answer: string; category?: string | null }>;
  posts: CmsBlogPost[];
};

const fallbackTestimonials: CmsTestimonial[] = [
  {
    name: "Perwakilan Kontraktor",
    companyRole: "Proyek rumah ibadah, Bogor",
    quote: "Koordinasi ukuran dan detail ornamen berjalan jelas sebelum produksi dimulai.",
    rating: 5,
  },
  {
    name: "Tim Arsitek",
    companyRole: "Proyek komersial, Jawa Barat",
    quote: "Tim responsif terhadap revisi shop drawing dan kebutuhan pemasangan di lapangan.",
    rating: 5,
  },
  {
    name: "Pemilik Proyek",
    companyRole: "Proyek residensial",
    quote: "Pilihan motif custom membantu tampilan fasad tetap sesuai konsep bangunan.",
    rating: 5,
  },
];

const fallback: CmsHomeContent = {
  site: {
    companyName: COMPANY.name,
    shortName: COMPANY.shortName,
    tagline: COMPANY.tagline,
    description: COMPANY.description,
    phone: COMPANY.phone,
    whatsapp: COMPANY.whatsapp,
    email: COMPANY.email,
    address: { full: COMPANY.address.full },
    businessHours: COMPANY.hours,
    statistics: [...COMPANY.stats],
  },
  sections: [],
  products: [...PRODUCTS],
  portfolio: [...PORTFOLIO],
  testimonials: fallbackTestimonials,
  faqs: [...FAQS],
  posts: BLOG_POSTS.map((post) => ({
    slug: post.slug,
    title: post.title,
    excerpt: post.excerpt,
    image: null,
    imageAlt: null,
    publishedAt: post.date,
  })),
};

type ApiPayload = {
  site: CmsHomeContent["site"] | null;
  sections: CmsSection[];
  products: Array<{
    slug: string;
    name: string;
    category: string;
    shortDescription: string;
    features: string[];
    image: string | null;
  }>;
  portfolio: Array<{
    slug: string;
    title: string;
    location: string;
    category: string;
    year: number | null;
    description: string;
    image: string | null;
  }>;
  testimonials: CmsTestimonial[];
  faqs: CmsHomeContent["faqs"];
  posts: CmsBlogPost[];
};

export async function getCmsHomeContent(): Promise<CmsHomeContent> {
  const baseUrl = process.env.CMS_API_URL ?? process.env.NEXT_PUBLIC_API_URL;
  const serverCmsConfigured = Boolean(process.env.CMS_API_URL);

  if (!baseUrl) return fallback;

  try {
    const response = await fetch(`${baseUrl.replace(/\/$/, "")}/v1/content`, {
      headers: { Accept: "application/json" },
      next: { revalidate: 60 },
    });

    if (!response.ok) {
      if (serverCmsConfigured) throw new Error(`CMS responded with ${response.status}`);
      return fallback;
    }

    const body = (await response.json()) as { data?: ApiPayload };
    if (!body.data) return fallback;

    const data = body.data;
    const products = data.products.map((product) => {
      const previous = PRODUCTS.find((item) => item.slug === product.slug);
      return {
        id: product.slug,
        name: product.name,
        slug: product.slug,
        category: product.category,
        description: product.shortDescription,
        features: product.features,
        image:
          product.image ??
          previous?.image ??
          "https://images.unsplash.com/photo-1487958449943-2429e8be8625?auto=format&fit=crop&w=1200&q=82",
      } satisfies Product;
    });

    const portfolio = data.portfolio.map((project, index) => {
      const previous = PORTFOLIO.find((item) => item.title === project.title);
      return {
        id: index + 1,
        title: project.title,
        location: project.location,
        category: project.category,
        year: String(project.year ?? ""),
        description: project.description,
        image:
          project.image ??
          previous?.image ??
          "https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1400&q=82",
      } satisfies PortfolioItem;
    });

    return {
      site: data.site ?? fallback.site,
      sections: data.sections ?? [],
      products,
      portfolio,
      testimonials: data.testimonials ?? [],
      faqs: data.faqs ?? [],
      posts: data.posts ?? [],
    };
  } catch (error) {
    if (serverCmsConfigured) throw error;
    return fallback;
  }
}

function apiBaseUrl(): string | undefined {
  return process.env.CMS_API_URL ?? process.env.NEXT_PUBLIC_API_URL;
}

export async function getCmsProduct(slug: string): Promise<Product | null> {
  const previous = PRODUCTS.find((item) => item.slug === slug);
  const baseUrl = apiBaseUrl();
  const serverCmsConfigured = Boolean(process.env.CMS_API_URL);
  if (!baseUrl) return previous ?? null;

  try {
    const response = await fetch(`${baseUrl.replace(/\/$/, "")}/v1/products/${encodeURIComponent(slug)}`, {
      headers: { Accept: "application/json" },
      next: { revalidate: 60 },
    });
    if (response.status === 404) return null;
    if (!response.ok) {
      if (serverCmsConfigured) throw new Error(`CMS responded with ${response.status}`);
      return previous ?? null;
    }

    const { data } = (await response.json()) as {
      data: {
        slug: string;
        name: string;
        category: string;
        shortDescription: string;
        features: string[];
        image: string | null;
      };
    };

    return {
      id: data.slug,
      slug: data.slug,
      name: data.name,
      category: data.category,
      description: data.shortDescription,
      features: data.features,
      image:
        data.image ??
        previous?.image ??
        "https://images.unsplash.com/photo-1487958449943-2429e8be8625?auto=format&fit=crop&w=1200&q=82",
    };
  } catch (error) {
    if (serverCmsConfigured) throw error;
    return previous ?? null;
  }
}

export async function getCmsPost(slug: string): Promise<CmsBlogPost | null> {
  const previous = fallback.posts.find((item) => item.slug === slug);
  const baseUrl = apiBaseUrl();
  const serverCmsConfigured = Boolean(process.env.CMS_API_URL);
  if (!baseUrl) return previous ?? null;

  try {
    const response = await fetch(`${baseUrl.replace(/\/$/, "")}/v1/posts/${encodeURIComponent(slug)}`, {
      headers: { Accept: "application/json" },
      next: { revalidate: 60 },
    });
    if (response.status === 404) return null;
    if (!response.ok) {
      if (serverCmsConfigured) throw new Error(`CMS responded with ${response.status}`);
      return previous ?? null;
    }

    const { data } = (await response.json()) as { data: CmsBlogPost };
    return data;
  } catch (error) {
    if (serverCmsConfigured) throw error;
    return previous ?? null;
  }
}
