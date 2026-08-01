import type { CmsHomeContent } from "./cms";
import type { Product } from "./cms-types";

const siteUrl = process.env.NEXT_PUBLIC_SITE_URL ?? "https://suryaciptaestetika.co.id";
type SiteIdentity = CmsHomeContent["site"];

export function serializeJsonLd(value: unknown): string {
  return JSON.stringify(value)
    .replace(/</g, "\\u003c")
    .replace(/>/g, "\\u003e")
    .replace(/&/g, "\\u0026");
}

export function localBusinessSchema(site: SiteIdentity) {
  return {
    "@context": "https://schema.org",
    "@type": "HomeAndConstructionBusiness",
    "@id": `${siteUrl}/#business`,
    name: site.companyName,
    description: site.description,
    url: siteUrl,
    telephone: `+${site.whatsapp}`,
    email: site.email,
    address: {
      "@type": "PostalAddress",
      streetAddress: site.address?.street,
      addressLocality: site.address?.city,
      addressRegion: site.address?.province,
      postalCode: site.address?.postalCode,
      addressCountry: "ID",
    },
    geo: { "@type": "GeoCoordinates", latitude: -6.485, longitude: 106.836 },
    openingHours: "Mo-Sa 08:00-17:00",
    priceRange: "$$",
    image: site.logo ?? `${siteUrl}/og-image.svg`,
  };
}

export function organizationSchema(site: SiteIdentity) {
  return {
    "@context": "https://schema.org",
    "@type": "Organization",
    "@id": `${siteUrl}/#organization`,
    name: site.companyName,
    url: siteUrl,
    email: site.email,
    telephone: `+${site.whatsapp}`,
    logo: site.logo,
    contactPoint: {
      "@type": "ContactPoint",
      telephone: `+${site.whatsapp}`,
      contactType: "sales",
      availableLanguage: ["Indonesian"],
    },
  };
}

export function productSchema(product: Product, brandName: string) {
  return {
    "@context": "https://schema.org",
    "@type": "Product",
    name: product.name,
    image: product.image,
    description: product.description,
    category: product.category,
    brand: { "@type": "Brand", name: brandName },
    url: `${siteUrl}/produk/${product.slug}`,
    offers: {
      "@type": "Offer",
      availability: "https://schema.org/InStock",
      priceCurrency: "IDR",
      url: `${siteUrl}/kontak`,
      description: "Harga berdasarkan spesifikasi dan volume proyek.",
    },
  };
}

export function breadcrumbSchema(items: readonly { name: string; path: string }[]) {
  return {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    itemListElement: items.map((item, index) => ({
      "@type": "ListItem",
      position: index + 1,
      name: item.name,
      item: new URL(item.path, siteUrl).toString(),
    })),
  };
}
