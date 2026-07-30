import Image from "next/image";
import Link from "next/link";
import { ArrowRight, DraftingCompass, Factory } from "lucide-react";
import AnimateOnScroll from "@/components/common/AnimateOnScroll";
import SectionHeader from "@/components/common/SectionHeader";
import type { CmsSection } from "@/lib/cms";

/** Ringkasan profil perusahaan dan cara kerja berbasis gambar kerja. */
export default function AboutSection({ section }: { section?: CmsSection }) {
  return (
    <section className="section-space bg-paper">
      <div className="container-site grid items-center gap-12 lg:grid-cols-[1.05fr_.95fr]">
        <AnimateOnScroll className="relative">
          <div className="relative aspect-[4/5] overflow-hidden rounded-md md:aspect-[5/4]">
            <Image
              src={section?.image ?? "https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1400&q=85"}
              alt={section?.imageAlt ?? "Tim konstruksi meninjau pekerjaan pada area produksi"}
              fill
              sizes="(max-width: 1024px) 100vw, 50vw"
              className="object-cover"
            />
          </div>
          <div className="absolute bottom-4 right-4 max-w-[220px] rounded-md bg-gold p-5 text-navy-dark shadow-lg">
            <p className="font-display text-3xl font-bold">Cibinong</p>
            <p className="mt-1 text-xs font-bold">Basis produksi strategis untuk Jabodetabek</p>
          </div>
        </AnimateOnScroll>
        <AnimateOnScroll delay={0.1}>
          <SectionHeader
            eyebrow={section?.eyebrow ?? "Tentang Kami"}
            title={section?.title ?? "GRC yang dirancang untuk kebutuhan proyek nyata"}
            description={section?.description ?? "CV. Surya Cipta Estetica Mandiri memproduksi dan memasang elemen GRC dari workshop di Cibinong, Bogor. Setiap pekerjaan dimulai dari kebutuhan desain, ukuran lapangan, dan metode instalasi."}
          />
          <div className="mt-7 grid gap-4 sm:grid-cols-2">
            <div className="border-l-2 border-gold pl-4">
              <Factory className="text-navy" aria-hidden="true" />
              <h3 className="mt-3 font-bold text-navy">Produksi terkendali</h3>
              <p className="mt-2 text-sm leading-6 text-muted">
                Cetakan, curing, finishing, dan pengecekan dilakukan dalam alur kerja yang jelas.
              </p>
            </div>
            <div className="border-l-2 border-gold pl-4">
              <DraftingCompass className="text-navy" aria-hidden="true" />
              <h3 className="mt-3 font-bold text-navy">Custom dari shop drawing</h3>
              <p className="mt-2 text-sm leading-6 text-muted">
                Dimensi dan detail disepakati sebelum produksi untuk mengurangi rework.
              </p>
            </div>
          </div>
          <Link href="/tentang-kami" className="btn-secondary mt-8">
            Pelajari lebih lanjut <ArrowRight size={17} aria-hidden="true" />
          </Link>
        </AnimateOnScroll>
      </div>
    </section>
  );
}
