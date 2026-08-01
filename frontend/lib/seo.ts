import type { Metadata } from "next";

const siteUrl = process.env.NEXT_PUBLIC_SITE_URL ?? "https://suryaciptaestetika.co.id";

export function createPageMetadata(
  title: string,
  description: string,
  path: string,
): Metadata {
  const canonical = new URL(path, siteUrl).toString();

  return {
    title,
    description,
    alternates: { canonical },
    openGraph: {
      title,
      description,
      url: canonical,
      locale: "id_ID",
      type: "website",
      images: [{ url: "/og-image.svg", width: 1200, height: 630 }],
    },
    twitter: { card: "summary_large_image", title, description, images: ["/og-image.svg"] },
  };
}
