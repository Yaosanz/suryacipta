import Image from "next/image";
import Link from "next/link";
import { ArrowDown, ArrowRight, CheckCircle2, MessageCircle } from "lucide-react";
import { COMPANY } from "@/lib/constants";
import type { CmsHomeContent, CmsSection } from "@/lib/cms";

/** Hero berorientasi konversi dengan foto arsitektur sebagai first-viewport signal. */
export default function HeroSection({
  site,
  section,
}: {
  site?: CmsHomeContent["site"];
  section?: CmsSection;
}) {
  const whatsapp = `https://wa.me/${site?.whatsapp ?? COMPANY.whatsapp}?text=${encodeURIComponent(
    "Halo, saya ingin konsultasi proyek GRC.",
  )}`;

  return (
    <section className="relative min-h-[760px] overflow-hidden bg-navy-dark text-white">
      <Image
        src={section?.image ?? "https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=2200&q=88"}
        alt={section?.imageAlt ?? "Fasad arsitektural dengan panel beton pracetak"}
        fill
        priority
        sizes="100vw"
        className="object-cover"
      />
      <div className="absolute inset-0 bg-[linear-gradient(90deg,rgba(10,37,50,.94)_0%,rgba(10,37,50,.76)_52%,rgba(10,37,50,.25)_100%)]" />
      <div className="container-site relative flex min-h-[760px] items-center pb-24 pt-32">
        <div className="max-w-3xl">
          <p className="inline-flex items-center gap-2 border border-gold/60 bg-navy-dark/50 px-3 py-2 text-xs font-bold text-gold-light backdrop-blur">
            <CheckCircle2 size={15} aria-hidden="true" />
            Berpengalaman lebih dari 25 tahun
          </p>
          <h1 className="mt-6 font-display text-[clamp(2.7rem,7vw,5.4rem)] font-bold leading-[1.02]">
            {section?.title ?? "Spesialis GRC Panel Cetak & Instalasi di Bogor"}
          </h1>
          <p className="mt-6 max-w-2xl text-base leading-8 text-white/80 md:text-lg">
            {section?.description ??
              "Dari shop drawing, produksi, quality control, hingga pemasangan GRC untuk masjid, hotel, komersial, dan perumahan di seluruh Indonesia."}
          </p>
          <div className="mt-8 flex flex-col gap-3 sm:flex-row">
            <a href={whatsapp} target="_blank" rel="noreferrer" className="btn-whatsapp">
              <MessageCircle size={18} aria-hidden="true" />
              Konsultasi Gratis
            </a>
            <Link
              href="/portofolio"
              className="inline-flex min-h-12 items-center justify-center gap-2 rounded-md border border-white/60 px-5 text-sm font-bold text-white hover:bg-white hover:text-navy"
            >
              Lihat Portofolio <ArrowRight size={17} aria-hidden="true" />
            </Link>
          </div>
          <div className="mt-10 grid max-w-2xl grid-cols-1 gap-3 border-t border-white/20 pt-6 text-sm font-semibold text-white/75 sm:grid-cols-3">
            <span>200+ proyek selesai</span>
            <span>Produksi & instalasi</span>
            <span>Custom sesuai desain</span>
          </div>
        </div>
      </div>
      <a
        href="#statistik"
        className="absolute bottom-7 left-1/2 -translate-x-1/2 text-white/70"
        aria-label="Lanjut ke statistik"
      >
        <ArrowDown className="animate-bounce" aria-hidden="true" />
      </a>
    </section>
  );
}
