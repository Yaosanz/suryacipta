import { Quote, Star } from "lucide-react";
import SectionHeader from "@/components/common/SectionHeader";
import type { CmsTestimonial } from "@/lib/cms";

const testimonials = [
  {
    name: "Perwakilan Kontraktor",
    role: "Proyek rumah ibadah, Bogor",
    quote: "Koordinasi ukuran dan detail ornamen berjalan jelas sebelum produksi dimulai.",
  },
  {
    name: "Tim Arsitek",
    role: "Proyek komersial, Jawa Barat",
    quote: "Tim responsif terhadap revisi shop drawing dan kebutuhan pemasangan di lapangan.",
  },
  {
    name: "Pemilik Proyek",
    role: "Proyek residensial",
    quote: "Pilihan motif custom membantu tampilan fasad tetap sesuai konsep bangunan.",
  },
];

/** Testimoni contoh yang jelas dilabeli peran hingga data klien terverifikasi tersedia. */
export default function TestimonialsSection({
  testimonials: items = testimonials.map((item) => ({
    name: item.name,
    companyRole: item.role,
    quote: item.quote,
    rating: 5,
  })),
}: {
  testimonials?: CmsTestimonial[];
}) {
  return (
    <section className="section-space bg-stone/55">
      <div className="container-site">
        <SectionHeader eyebrow="Pengalaman Klien" title="Kolaborasi yang terukur" align="center" />
        <div className="mt-10 grid gap-5 lg:grid-cols-3">
          {items.map((item) => (
            <figure key={`${item.name}-${item.companyRole}`} className="rounded-md border border-white bg-white p-7">
              <div className="flex items-center justify-between">
                <Quote className="text-gold" aria-hidden="true" />
                <span className="flex text-gold" aria-label="5 dari 5 bintang">
                  {Array.from({ length: item.rating }, (_, index) => (
                    <Star key={index} size={14} fill="currentColor" aria-hidden="true" />
                  ))}
                </span>
              </div>
              <blockquote className="mt-6 font-display text-xl leading-8 text-navy-dark">
                &ldquo;{item.quote}&rdquo;
              </blockquote>
              <figcaption className="mt-6 border-t border-line pt-4 text-sm">
                <strong className="block text-navy">{item.name}</strong>
                <span className="mt-1 block text-muted">{item.companyRole}</span>
              </figcaption>
            </figure>
          ))}
        </div>
        <p className="mt-5 text-center text-xs text-muted">
          Identitas testimoni ditampilkan secara umum sampai izin publikasi klien tersedia.
        </p>
      </div>
    </section>
  );
}
