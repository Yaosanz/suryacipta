import type { Metadata } from "next";
import PageHero from "@/components/common/PageHero";
import ContactSection from "@/components/sections/ContactSection";
import FaqList from "@/components/common/FaqList";
import SectionHeader from "@/components/common/SectionHeader";
import { createPageMetadata } from "@/lib/seo";

export function generateMetadata(): Metadata {
  return createPageMetadata(
    "Kontak & Permintaan Penawaran",
    "Hubungi produsen GRC Bogor untuk konsultasi, survey, dan permintaan penawaran proyek.",
    "/kontak",
  );
}

export default function ContactPage() {
  return (
    <>
      <PageHero
        eyebrow="Hubungi Tim Kami"
        title="Konsultasi dan permintaan penawaran"
        description="Sampaikan kebutuhan awal. Tim kami akan membantu menentukan informasi teknis yang perlu dilengkapi."
        image="https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=2000&q=85"
      />
      <ContactSection />
      <section className="section-space bg-white">
        <div className="container-site">
          <SectionHeader eyebrow="Pertanyaan Umum" title="Informasi awal sebelum konsultasi" />
          <div className="mt-7 max-w-4xl"><FaqList /></div>
        </div>
      </section>
    </>
  );
}
