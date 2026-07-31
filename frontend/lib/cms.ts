import "server-only";

import type { CmsVideo, PortfolioItem, Product } from "@/lib/cms-types";

export type CmsSection = {
  page: string;
  key: string;
  eyebrow: string | null;
  title: string;
  description: string | null;
  content: {
    badge?: string;
    secondaryCtaLabel?: string;
    secondaryCtaUrl?: string;
    secondaryDescription?: string;
    vision?: string;
    mission?: string;
    locationTitle?: string;
    locationText?: string;
    sidebarTitle?: string;
    sidebarDescription?: string;
    mapUrl?: string;
    highlights?: Array<{ title: string }>;
    features?: Array<{ icon: string; title: string; text: string }>;
    items?: Array<{ icon?: string; title: string; text: string }>;
    projectTypes?: Array<{ label: string }>;
    budgetRanges?: Array<{ label: string }>;
    estimator?: {
      title?: string;
      description?: string;
      areaLabel?: string;
      panelAreaLabel?: string;
      resultLabel?: string;
      allowancePercent?: number;
      defaultArea?: number;
      defaultPanelArea?: number;
    };
  };
  image: string | null;
  imageAlt: string | null;
  video: CmsVideo | null;
  cta: {
    label: string | null;
    url: string | null;
  };
};

export type CmsTestimonial = {
  name: string;
  companyRole: string | null;
  quote: string;
  rating: number;
};

export type CmsTeamMember = {
  name: string;
  role: string;
  bio: string | null;
  photo: string | null;
  photoAlt: string | null;
  linkedinUrl: string | null;
};

export type CmsBlogPost = {
  slug: string;
  title: string;
  excerpt: string;
  authorName?: string | null;
  image: string | null;
  imageAlt: string | null;
  video?: CmsVideo | null;
  publishedAt: string;
  seo?: { title: string | null; description: string | null };
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
    socialLinks?: Record<string, string>;
    logo?: string | null;
    logoAlt?: string | null;
    seo?: {
      title?: string | null;
      description?: string | null;
    };
    statistics: Array<{ value: string; label: string }>;
  };
  sections: CmsSection[];
  products: Product[];
  portfolio: PortfolioItem[];
  testimonials: CmsTestimonial[];
  team: CmsTeamMember[];
  faqs: Array<{ question: string; answer: string; category?: string | null }>;
  posts: CmsBlogPost[];
};

type ApiPayload = {
  site: CmsHomeContent["site"] | null;
  sections: CmsSection[];
  products: Array<{
    slug: string;
    name: string;
    category: string;
    shortDescription: string;
    description: string | null;
    features: string[];
    image: string | null;
    imageAlt: string | null;
    video: CmsVideo | null;
    featured: boolean;
    seo: { title: string | null; description: string | null };
  }>;
  portfolio: Array<{
    slug: string;
    title: string;
    clientName: string | null;
    location: string;
    category: string;
    year: number | null;
    description: string;
    image: string | null;
    imageAlt: string | null;
    video: CmsVideo | null;
    featured: boolean;
  }>;
  testimonials: CmsTestimonial[];
  team: CmsTeamMember[];
  faqs: CmsHomeContent["faqs"];
  posts: CmsBlogPost[];
};

const cmsFetchOptions = {
  cache: "no-store",
  headers: { Accept: "application/json" },
} as const;

function apiBaseUrl(): string {
  const baseUrl = process.env.CMS_API_URL ?? process.env.NEXT_PUBLIC_API_URL;

  if (!baseUrl) {
    throw new Error("CMS_API_URL atau NEXT_PUBLIC_API_URL wajib dikonfigurasi.");
  }

  return baseUrl.replace(/\/$/, "");
}

async function fetchCms<T>(path: string): Promise<T | null> {
  const response = await fetch(`${apiBaseUrl()}${path}`, cmsFetchOptions);

  if (response.status === 404) return null;
  if (!response.ok) throw new Error(`CMS merespons dengan status ${response.status} untuk ${path}.`);

  const body = (await response.json()) as { data?: T };
  if (!body.data) throw new Error(`Respons CMS untuk ${path} tidak memiliki data.`);

  return body.data;
}

export async function getCmsHomeContent(): Promise<CmsHomeContent> {
  const data = await fetchCms<ApiPayload>("/v1/content");

  if (!data?.site) {
    throw new Error("Profil website belum tersedia di CMS.");
  }

  return {
    site: data.site,
    sections: data.sections,
    products: data.products.map((product) => ({
      id: product.slug,
      name: product.name,
      slug: product.slug,
      category: product.category,
      description: product.shortDescription,
      longDescription: product.description ?? undefined,
      features: product.features,
      image: product.image,
      imageAlt: product.imageAlt ?? undefined,
      video: product.video ?? undefined,
      featured: product.featured,
      seo: product.seo,
    })),
    portfolio: data.portfolio.map((project) => ({
      id: project.slug,
      title: project.title,
      clientName: project.clientName ?? undefined,
      location: project.location,
      category: project.category,
      year: String(project.year ?? ""),
      description: project.description,
      image: project.image,
      imageAlt: project.imageAlt ?? undefined,
      video: project.video ?? undefined,
      featured: project.featured,
    })),
    testimonials: data.testimonials,
    team: data.team,
    faqs: data.faqs,
    posts: data.posts,
  };
}

export async function getCmsProduct(slug: string): Promise<Product | null> {
  const data = await fetchCms<ApiPayload["products"][number]>(
    `/v1/products/${encodeURIComponent(slug)}`,
  );

  if (!data) return null;

  return {
    id: data.slug,
    slug: data.slug,
    name: data.name,
    category: data.category,
    description: data.shortDescription,
    longDescription: data.description ?? undefined,
    features: data.features,
    image: data.image,
    imageAlt: data.imageAlt ?? undefined,
    video: data.video ?? undefined,
    featured: data.featured,
    seo: data.seo,
  };
}

export async function getCmsPost(slug: string): Promise<CmsBlogPost | null> {
  return fetchCms<CmsBlogPost>(`/v1/posts/${encodeURIComponent(slug)}`);
}

export function findCmsSection(
  content: CmsHomeContent,
  page: string,
  key: string,
): CmsSection | undefined {
  return content.sections.find((section) => section.page === page && section.key === key);
}
