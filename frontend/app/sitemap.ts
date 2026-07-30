import type { MetadataRoute } from "next";
import { getCmsHomeContent } from "@/lib/cms";

export default async function sitemap(): Promise<MetadataRoute.Sitemap> {
  const content = await getCmsHomeContent();
  const baseUrl = process.env.NEXT_PUBLIC_SITE_URL ?? "https://suryaciptaestetika.co.id";
  const now = new Date();
  const routes = [
    { path: "", priority: 1, frequency: "weekly" as const },
    { path: "/tentang-kami", priority: 0.8, frequency: "monthly" as const },
    { path: "/produk", priority: 0.9, frequency: "weekly" as const },
    { path: "/portofolio", priority: 0.9, frequency: "monthly" as const },
    { path: "/blog", priority: 0.7, frequency: "weekly" as const },
    { path: "/kontak", priority: 0.8, frequency: "monthly" as const },
  ];

  return [
    ...routes.map((route) => ({
      url: `${baseUrl}${route.path}`,
      lastModified: now,
      changeFrequency: route.frequency,
      priority: route.priority,
    })),
    ...content.products.map((product) => ({
      url: `${baseUrl}/produk/${product.slug}`,
      lastModified: now,
      changeFrequency: "monthly" as const,
      priority: 0.8,
    })),
    ...content.posts.map((post) => ({
      url: `${baseUrl}/blog/${post.slug}`,
      lastModified: new Date(post.publishedAt),
      changeFrequency: "monthly" as const,
      priority: 0.7,
    })),
  ];
}
