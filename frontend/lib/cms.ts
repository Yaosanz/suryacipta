import 'server-only';

import type { CmsVideo, PortfolioItem, Product } from '@/lib/cms-types';

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
  site: CmsHomeContent['site'] | null;
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
  faqs: CmsHomeContent['faqs'];
  posts: CmsBlogPost[];
};

const cmsFetchOptions = {
  cache: 'no-store',
  headers: { Accept: 'application/json' },
} as const;

/**
 * Server-to-server CMS API base URL.
 *
 * Prefers CMS_API_URL (Docker-internal, e.g. http://backend:8080/api) because
 * the Next.js server can reach it directly. Falls back to NEXT_PUBLIC_API_URL.
 */
function cmsApiUrl(): string {
  const baseUrl = process.env.CMS_API_URL ?? process.env.NEXT_PUBLIC_API_URL;

  if (!baseUrl) {
    throw new Error('CMS_API_URL atau NEXT_PUBLIC_API_URL wajib dikonfigurasi.');
  }

  return baseUrl.replace(/\/$/, '').replace(/\/api$/, '');
}

/**
 * Public-facing API origin used to build absolute asset URLs that the browser
 * can reach. In Docker, CMS_API_URL points to an internal hostname (backend),
 * so assets must be resolved against the public NEXT_PUBLIC_API_URL instead.
 */
function publicApiOrigin(): string {
  const baseUrl = process.env.NEXT_PUBLIC_API_URL ?? process.env.CMS_API_URL;

  if (!baseUrl) {
    throw new Error('NEXT_PUBLIC_API_URL wajib dikonfigurasi untuk URL aset publik.');
  }

  return baseUrl.replace(/\/$/, '').replace(/\/api$/, '');
}

/**
 * Resolve a relative asset path (e.g. /storage/xxx.jpg) against the public API
 * origin. The backend returns relative paths so the frontend can build the
 * correct absolute URL regardless of Docker internal hostnames.
 */
function resolveAssetUrl(path: string | null | undefined): string | null {
  if (!path) return null;

  // Already an absolute URL — return as-is (e.g. external images, videos).
  if (path.startsWith('http://') || path.startsWith('https://')) {
    return path;
  }

  // Relative path — resolve against the public API origin.
  const base = publicApiOrigin();
  const normalized = path.startsWith('/') ? path : `/${path}`;
  return `${base}${normalized}`;
}

/**
 * Apply resolveAssetUrl to all media fields in a video object.
 */
function resolveVideo(video: CmsVideo | null | undefined): CmsVideo | null | undefined {
  if (!video) return video;
  return {
    ...video,
    url: resolveAssetUrl(video.url) ?? video.url,
  };
}

async function fetchCms<T>(path: string): Promise<T | null> {
  const response = await fetch(`${cmsApiUrl()}/api${path}`, cmsFetchOptions);

  if (response.status === 404) return null;
  if (!response.ok) throw new Error(`CMS merespons dengan status ${response.status} untuk ${path}.`);

  const body = (await response.json()) as { data?: T };
  if (!body.data) throw new Error(`Respons CMS untuk ${path} tidak memiliki data.`);

  return body.data;
}

export async function getCmsHomeContent(): Promise<CmsHomeContent> {
  const data = await fetchCms<ApiPayload>('/v1/content');

  if (!data?.site) {
    throw new Error('Profil website belum tersedia di CMS.');
  }

  return {
    site: {
      ...data.site,
      logo: resolveAssetUrl(data.site.logo),
    },
    sections: data.sections.map((section) => ({
      ...section,
      image: resolveAssetUrl(section.image),
      video: resolveVideo(section.video) ?? null,
    })),
    products: data.products.map((product) => ({
      id: product.slug,
      name: product.name,
      slug: product.slug,
      category: product.category,
      description: product.shortDescription,
      longDescription: product.description ?? undefined,
      features: product.features,
      image: resolveAssetUrl(product.image),
      imageAlt: product.imageAlt ?? undefined,
      video: resolveVideo(product.video) ?? undefined,
      featured: product.featured,
      seo: product.seo,
    })),
    portfolio: data.portfolio.map((project) => ({
      id: project.slug,
      title: project.title,
      clientName: project.clientName ?? undefined,
      location: project.location,
      category: project.category,
      year: String(project.year ?? ''),
      description: project.description,
      image: resolveAssetUrl(project.image),
      imageAlt: project.imageAlt ?? undefined,
      video: resolveVideo(project.video) ?? undefined,
      featured: project.featured,
    })),
    testimonials: data.testimonials,
    team: data.team.map((member) => ({
      ...member,
      photo: resolveAssetUrl(member.photo),
    })),
    faqs: data.faqs,
    posts: data.posts.map((post) => ({
      ...post,
      image: resolveAssetUrl(post.image),
      video: resolveVideo(post.video) ?? null,
    })),
  };
}

export async function getCmsProduct(slug: string): Promise<Product | null> {
  const data = await fetchCms<ApiPayload['products'][number]>(`/v1/products/${encodeURIComponent(slug)}`);

  if (!data) return null;

  return {
    id: data.slug,
    slug: data.slug,
    name: data.name,
    category: data.category,
    description: data.shortDescription,
    longDescription: data.description ?? undefined,
    features: data.features,
    image: resolveAssetUrl(data.image),
    imageAlt: data.imageAlt ?? undefined,
    video: resolveVideo(data.video) ?? undefined,
    featured: data.featured,
    seo: data.seo,
  };
}

export async function getCmsPost(slug: string): Promise<CmsBlogPost | null> {
  const post = await fetchCms<CmsBlogPost>(`/v1/posts/${encodeURIComponent(slug)}`);

  if (!post) return null;

  return {
    ...post,
    image: resolveAssetUrl(post.image),
    video: resolveVideo(post.video) ?? null,
  };
}

export function findCmsSection(content: CmsHomeContent, page: string, key: string): CmsSection | undefined {
  return content.sections.find((section) => section.page === page && section.key === key);
}
