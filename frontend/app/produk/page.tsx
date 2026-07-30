import type { Metadata } from "next";
import PageHero from "@/components/common/PageHero";
import SectionHeader from "@/components/common/SectionHeader";
import FaqList from "@/components/common/FaqList";
import ProductCatalog from "@/components/sections/ProductCatalog";
import Estimator from "@/components/sections/Estimator";
import CTASection from "@/components/sections/CTASection";
import { createPageMetadata } from "@/lib/seo";
import { getCmsHomeContent } from "@/lib/cms";

export function generateMetadata(): Metadata {
  return createPageMetadata(
    "Produk GRC Custom",
    "Kubah masjid, panel cladding, ornamen, krawangan, lisplang, dan pilar GRC custom dari produsen GRC Bogor.",
    "/produk",
  );
}

export const revalidate = 60;

export default async function ProductsPage() {
  const content = await getCmsHomeContent();
  return (
    <>
      <PageHero
        eyebrow="Katalog Produk"
        title="Produk GRC untuk proyek arsitektur"
        description="Dari elemen dekoratif satuan hingga kebutuhan fasad proyek, seluruh spesifikasi ditinjau berdasarkan fungsi dan metode pemasangan."
        image="https://images.unsplash.com/photo-1518005020951-eccb494ad742?auto=format&fit=crop&w=2000&q=85"
      />
      <section className="section-space bg-paper">
        <div className="container-site">
          <ProductCatalog items={content.products} />
        </div>
      </section>
      <section className="section-space bg-white">
        <div className="container-site grid gap-10 lg:grid-cols-2">
          <Estimator />
          <div>
            <SectionHeader eyebrow="FAQ Produk" title="Sebelum meminta penawaran" />
            <div className="mt-6"><FaqList items={content.faqs} /></div>
          </div>
        </div>
      </section>
      <CTASection />
    </>
  );
}
