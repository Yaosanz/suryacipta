import type { Metadata } from "next";
import Image from "next/image";
import { BadgeCheck, Handshake, Lightbulb, ShieldCheck } from "lucide-react";
import PageHero from "@/components/common/PageHero";
import SectionHeader from "@/components/common/SectionHeader";
import CTASection from "@/components/sections/CTASection";
import { createPageMetadata } from "@/lib/seo";

export function generateMetadata(): Metadata {
  return createPageMetadata(
    "Tentang Kami",
    "Profil, proses produksi, visi, dan nilai CV. Surya Cipta Estetica Mandiri di Cibinong, Bogor.",
    "/tentang-kami",
  );
}

const values = [
  { icon: ShieldCheck, title: "Kualitas", text: "Spesifikasi dan detail disepakati sebelum produksi." },
  { icon: Handshake, title: "Kolaborasi", text: "Koordinasi terbuka dengan owner, arsitek, dan kontraktor." },
  { icon: Lightbulb, title: "Solutif", text: "Desain diselaraskan dengan kemampuan produksi dan instalasi." },
  { icon: BadgeCheck, title: "Tanggung jawab", text: "Milestone kerja dan perubahan ruang lingkup dicatat." },
];

export default function AboutPage() {
  return (
    <>
      <PageHero
        eyebrow="Profil Perusahaan"
        title="Pengalaman produksi yang bertemu kebutuhan desain"
        description="Berbasis di Cibinong, kami melayani kebutuhan elemen GRC custom untuk proyek di Jawa Barat dan berbagai wilayah Indonesia."
        image="https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=2000&q=85"
      />
      <section className="section-space bg-paper">
        <div className="container-site grid gap-12 lg:grid-cols-2">
          <div>
            <SectionHeader
              eyebrow="Perjalanan Kami"
              title="Tumbuh bersama proyek sejak era 1990-an"
              description="Surya Cipta Estetica Mandiri berkembang dari pengalaman lapangan dalam pekerjaan cetak arsitektural. Fokus kami adalah mengubah detail desain menjadi elemen GRC yang dapat diproduksi, dikirim, dan dipasang secara efektif."
            />
            <p className="mt-6 leading-8 text-muted">
              Basis produksi di koridor Cibinong memudahkan koordinasi proyek Jabodetabek. Untuk
              proyek luar wilayah, perencanaan modul dan metode pengiriman disusun sejak awal.
            </p>
          </div>
          <div className="relative aspect-[4/3] overflow-hidden rounded-md">
            <Image
              src="https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=1400&q=85"
              alt="Tim produksi melakukan koordinasi teknis"
              fill
              className="object-cover"
            />
          </div>
        </div>
      </section>
      <section className="section-space bg-white">
        <div className="container-site">
          <SectionHeader eyebrow="Arah Perusahaan" title="Visi, misi, dan nilai kerja" align="center" />
          <div className="mt-10 grid gap-6 md:grid-cols-2">
            <article className="border-l-4 border-gold bg-paper p-7">
              <h2 className="font-display text-2xl font-bold text-navy">Visi</h2>
              <p className="mt-3 leading-7 text-muted">Menjadi mitra GRC yang tepercaya untuk pekerjaan arsitektural custom di Indonesia.</p>
            </article>
            <article className="border-l-4 border-navy bg-paper p-7">
              <h2 className="font-display text-2xl font-bold text-navy">Misi</h2>
              <p className="mt-3 leading-7 text-muted">Menghasilkan elemen presisi, menjaga komunikasi proyek, dan terus meningkatkan proses produksi serta keselamatan kerja.</p>
            </article>
          </div>
          <div className="mt-6 grid gap-px overflow-hidden rounded-md border border-line bg-line md:grid-cols-2 lg:grid-cols-4">
            {values.map(({ icon: Icon, title, text }) => (
              <article key={title} className="bg-white p-6">
                <Icon className="text-gold" aria-hidden="true" />
                <h3 className="mt-4 font-bold text-navy">{title}</h3>
                <p className="mt-2 text-sm leading-6 text-muted">{text}</p>
              </article>
            ))}
          </div>
        </div>
      </section>
      <CTASection />
    </>
  );
}
