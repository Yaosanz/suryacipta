import type { Metadata } from "next";
import PageHero from "@/components/common/PageHero";
import PortfolioGallery from "@/components/sections/PortfolioGallery";
import CTASection from "@/components/sections/CTASection";
import { createPageMetadata } from "@/lib/seo";
import { getCmsHomeContent } from "@/lib/cms";

export function generateMetadata(): Metadata {
  return createPageMetadata(
    "Portofolio Proyek GRC",
    "Contoh proyek kubah, panel fasad, ornamen, krawangan, dan lisplang GRC di Jawa Barat dan Indonesia.",
    "/portofolio",
  );
}

export const revalidate = 60;

export default async function PortfolioPage() {
  const content = await getCmsHomeContent();
  return (
    <>
      <PageHero
        eyebrow="200+ Proyek"
        title="Portofolio pekerjaan GRC"
        description="Ragam pekerjaan religi, hospitality, komersial, dan residensial yang menggambarkan cakupan produksi dan instalasi kami."
        image="https://images.unsplash.com/photo-1487958449943-2429e8be8625?auto=format&fit=crop&w=2000&q=85"
      />
      <section className="section-space bg-paper">
        <div className="container-site"><PortfolioGallery projects={content.portfolio} /></div>
      </section>
      <CTASection />
    </>
  );
}
