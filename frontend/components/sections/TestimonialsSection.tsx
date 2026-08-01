import { Quote, Star } from "lucide-react";
import AnimateOnScroll from "@/components/common/AnimateOnScroll";
import SectionHeader from "@/components/common/SectionHeader";
import type { CmsSection, CmsTestimonial } from "@/lib/cms";

/** Testimoni contoh yang jelas dilabeli peran hingga data klien terverifikasi tersedia. */
export default function TestimonialsSection({
  testimonials: items,
  section,
}: {
  testimonials: CmsTestimonial[];
  section: CmsSection;
}) {
  return (
    <section className="section-space bg-stone/55">
      <div className="container-site">
        <SectionHeader
          eyebrow={section.eyebrow ?? ""}
          title={section.title}
          description={section.description ?? undefined}
          align="center"
        />
        <div className="mt-10 grid gap-5 lg:grid-cols-3">
          {items.map((item) => {
            const rating = Math.max(1, Math.min(5, Math.round(item.rating)));

            return (
              <AnimateOnScroll key={`${item.name}-${item.companyRole}`} className="h-full">
              <figure className="h-full rounded-md border border-line/70 bg-white p-7 shadow-[0_12px_35px_rgba(16,45,59,0.06)]">
                <div className="flex items-center justify-between">
                  <Quote className="text-gold" aria-hidden="true" />
                  <span className="flex gap-0.5" aria-label={`${rating} dari 5 bintang`}>
                    {Array.from({ length: 5 }, (_, index) => {
                      const isFilled = index < rating;
                      return (
                        <Star
                          key={index}
                          size={14}
                          className={isFilled ? "text-gold" : "text-line"}
                          fill={isFilled ? "currentColor" : "none"}
                          aria-hidden="true"
                        />
                      );
                    })}
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
              </AnimateOnScroll>
            );
          })}
        </div>
      </div>
    </section>
  );
}
