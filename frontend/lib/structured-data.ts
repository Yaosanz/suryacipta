import { COMPANY, type Product } from "./constants";

const siteUrl = process.env.NEXT_PUBLIC_SITE_URL ?? "https://suryaciptaestetika.co.id";

export function serializeJsonLd(value: unknown): string {
  return JSON.stringify(value)
    .replace(/</g, "\\u003c")
    .replace(/>/g, "\\u003e")
    .replace(/&/g, "\\u0026");
}

export const localBusinessSchema = {
  "@context": "https://schema.org",
  "@type": "HomeAndConstructionBusiness",
  "@id": `${siteUrl}/#business`,
  name: COMPANY.name,
  description: COMPANY.description,
  url: siteUrl,
  telephone: "+6281282333021",
  email: COMPANY.email,
  address: {
    "@type": "PostalAddress",
    streetAddress: COMPANY.address.street,
    addressLocality: COMPANY.address.city,
    addressRegion: COMPANY.address.province,
    postalCode: COMPANY.address.postalCode,
    addressCountry: "ID",
  },
  geo: { "@type": "GeoCoordinates", latitude: -6.485, longitude: 106.836 },
  openingHours: "Mo-Sa 08:00-17:00",
  priceRange: "$$",
  image: `${siteUrl}/og-image.svg`,
};

export const organizationSchema = {
  "@context": "https://schema.org",
  "@type": "Organization",
  "@id": `${siteUrl}/#organization`,
  name: COMPANY.name,
  url: siteUrl,
  email: COMPANY.email,
  telephone: "+6281282333021",
  contactPoint: {
    "@type": "ContactPoint",
    telephone: "+6281282333021",
    contactType: "sales",
    availableLanguage: ["Indonesian"],
  },
};

export function productSchema(product: Product) {
  return {
    "@context": "https://schema.org",
    "@type": "Product",
    name: product.name,
    image: product.image,
    description: product.description,
    category: product.category,
    brand: { "@type": "Brand", name: COMPANY.shortName },
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
