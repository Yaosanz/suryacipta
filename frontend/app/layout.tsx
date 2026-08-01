import type { Metadata, Viewport } from "next";
import Navbar from "@/components/layout/Navbar";
import Footer from "@/components/layout/Footer";
import WhatsAppFloat from "@/components/layout/WhatsAppFloat";
import Analytics from "@/components/layout/Analytics";
import {
  localBusinessSchema,
  organizationSchema,
  serializeJsonLd,
} from "@/lib/structured-data";
import { getCmsHomeContent } from "@/lib/cms";
import "./globals.css";

export const dynamic = "force-dynamic";

const siteUrl = process.env.NEXT_PUBLIC_SITE_URL ?? "https://suryaciptaestetika.co.id";

export async function generateMetadata(): Promise<Metadata> {
  const { site } = await getCmsHomeContent();
  const title = site.seo?.title || `${site.companyName} | Produsen GRC Bogor`;
  const description = site.seo?.description || site.description;

  return {
    metadataBase: new URL(siteUrl),
    title: {
      default: title,
      template: `%s | ${site.shortName || site.companyName}`,
    },
    description,
    keywords: [
      "produsen GRC Bogor",
      "GRC panel Cibinong",
      "kubah masjid GRC",
      "kontraktor GRC Jakarta",
      "GRC cetak custom Bogor",
      "harga GRC panel per m2",
    ],
    authors: [{ name: site.companyName }],
    creator: site.companyName,
    alternates: { canonical: "/" },
    openGraph: {
      type: "website",
      locale: "id_ID",
      url: siteUrl,
      siteName: site.companyName,
      title,
      description,
      images: [{ url: "/og-image.svg", width: 1200, height: 630, alt: site.companyName }],
    },
    twitter: {
      card: "summary_large_image",
      title,
      description,
      images: ["/og-image.svg"],
    },
    robots: { index: true, follow: true },
    verification: process.env.GOOGLE_SITE_VERIFICATION
      ? { google: process.env.GOOGLE_SITE_VERIFICATION }
      : undefined,
    category: "construction",
  };
}

export const viewport: Viewport = {
  width: "device-width",
  initialScale: 1,
  themeColor: "#17394a",
};

export default async function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  const content = await getCmsHomeContent();

  return (
    <html lang="id">
      <body>
        <a
          href="#main-content"
          className="fixed left-3 top-3 z-[100] -translate-y-20 bg-white px-4 py-3 font-bold text-navy focus:translate-y-0"
        >
          Lewati ke konten
        </a>
        <Navbar site={content.site} />
        <main id="main-content">{children}</main>
        <Footer site={content.site} products={content.products} />
        <WhatsAppFloat whatsapp={content.site.whatsapp} />
        <Analytics />
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{
            __html: serializeJsonLd([
              organizationSchema(content.site),
              localBusinessSchema(content.site),
            ]),
          }}
        />
      </body>
    </html>
  );
}
