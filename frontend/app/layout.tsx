import type { Metadata, Viewport } from "next";
import Navbar from "@/components/layout/Navbar";
import Footer from "@/components/layout/Footer";
import WhatsAppFloat from "@/components/layout/WhatsAppFloat";
import Analytics from "@/components/layout/Analytics";
import { COMPANY } from "@/lib/constants";
import {
  localBusinessSchema,
  organizationSchema,
  serializeJsonLd,
} from "@/lib/structured-data";
import "./globals.css";

const siteUrl = process.env.NEXT_PUBLIC_SITE_URL ?? "https://suryaciptaestetika.co.id";

export const metadata: Metadata = {
  metadataBase: new URL(siteUrl),
  title: {
    default: "CV. Surya Cipta Estetica Mandiri | Produsen GRC Bogor",
    template: "%s | Surya Cipta Estetica",
  },
  description: COMPANY.description,
  keywords: [
    "produsen GRC Bogor",
    "GRC panel Cibinong",
    "kubah masjid GRC",
    "kontraktor GRC Jakarta",
    "GRC cetak custom Bogor",
    "harga GRC panel per m2",
  ],
  authors: [{ name: COMPANY.name }],
  creator: COMPANY.name,
  alternates: { canonical: "/" },
  openGraph: {
    type: "website",
    locale: "id_ID",
    url: siteUrl,
    siteName: COMPANY.shortName,
    title: "Surya Cipta Estetica | Spesialis GRC Panel Bogor",
    description: COMPANY.description,
    images: [{ url: "/og-image.svg", width: 1200, height: 630, alt: COMPANY.name }],
  },
  twitter: {
    card: "summary_large_image",
    title: "Surya Cipta Estetica | Spesialis GRC Panel Bogor",
    description: COMPANY.description,
    images: ["/og-image.svg"],
  },
  robots: { index: true, follow: true },
  verification: process.env.GOOGLE_SITE_VERIFICATION
    ? { google: process.env.GOOGLE_SITE_VERIFICATION }
    : undefined,
  category: "construction",
};

export const viewport: Viewport = {
  width: "device-width",
  initialScale: 1,
  themeColor: "#17394a",
};

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return (
    <html lang="id">
      <body>
        <a
          href="#main-content"
          className="fixed left-3 top-3 z-[100] -translate-y-20 bg-white px-4 py-3 font-bold text-navy focus:translate-y-0"
        >
          Lewati ke konten
        </a>
        <Navbar />
        <main id="main-content">{children}</main>
        <Footer />
        <WhatsAppFloat />
        <Analytics />
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{
            __html: serializeJsonLd([organizationSchema, localBusinessSchema]),
          }}
        />
      </body>
    </html>
  );
}
