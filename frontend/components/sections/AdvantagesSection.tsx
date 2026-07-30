import {
  BadgeCheck,
  Clock3,
  Factory,
  Flame,
  Palette,
  Truck,
} from "lucide-react";
import SectionHeader from "@/components/common/SectionHeader";

const advantages = [
  { icon: Factory, title: "Produksi sendiri", text: "Kontrol mutu sejak cetakan hingga finishing." },
  { icon: Palette, title: "Custom sesuai desain", text: "Motif, dimensi, warna, dan tekstur sesuai gambar." },
  { icon: Clock3, title: "Jadwal transparan", text: "Milestone produksi dan instalasi disepakati sejak awal." },
  { icon: Flame, title: "Material tahan cuaca", text: "Komposisi dan finishing disesuaikan fungsi elemen." },
  { icon: Truck, title: "Layanan instalasi", text: "Koordinasi pengiriman, lifting, pemasangan, dan finishing." },
  { icon: BadgeCheck, title: "Quality control", text: "Dimensi dan kondisi elemen diperiksa sebelum dikirim." },
];

/** Keunggulan operasional yang relevan bagi pemilik proyek dan kontraktor. */
export default function AdvantagesSection() {
  return (
    <section className="section-space subtle-grid bg-paper">
      <div className="container-site">
        <SectionHeader
          eyebrow="Mengapa Kami"
          title="Satu tim dari desain hingga terpasang"
          align="center"
        />
        <div className="mt-12 grid gap-px overflow-hidden rounded-md border border-line bg-line md:grid-cols-2 lg:grid-cols-3">
          {advantages.map(({ icon: Icon, title, text }) => (
            <article key={title} className="bg-paper p-7">
              <Icon className="text-gold" size={30} aria-hidden="true" />
              <h3 className="mt-5 font-display text-xl font-bold text-navy-dark">{title}</h3>
              <p className="mt-2 text-sm leading-6 text-muted">{text}</p>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}
