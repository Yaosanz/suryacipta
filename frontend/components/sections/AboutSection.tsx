import Image from "next/image";
import Link from "next/link";
import { ArrowRight, BadgeCheck, DraftingCompass, Factory, Truck } from "lucide-react";
import AnimateOnScroll from "@/components/common/AnimateOnScroll";
import SectionHeader from "@/components/common/SectionHeader";
import type { CmsSection } from "@/lib/cms";

/** Ringkasan profil perusahaan dan cara kerja berbasis gambar kerja. */
export default function AboutSection({ section }: { section?: CmsSection }) {
  const iconMap = {
    factory: Factory,
    drafting: DraftingCompass,
    quality: BadgeCheck,
    delivery: Truck,
  };
  const features = section?.content.features ?? [];

  return (
    <section className="section-space bg-paper">
      <div className="container-site grid items-center gap-12 lg:grid-cols-[1.05fr_.95fr]">
        <AnimateOnScroll className="relative">
          <div className="relative aspect-[4/5] overflow-hidden rounded-md md:aspect-[5/4]">
            {section?.video ? (
              <video
                className="size-full object-cover"
                poster={section.image ?? undefined}
                controls
                playsInline
                preload="metadata"
                aria-label={section.video.alt ?? "Video profil perusahaan"}
              >
                <source src={section.video.url} type="video/mp4" />
              </video>
            ) : section?.image ? (
              <Image
                src={section.image}
                alt={section.imageAlt ?? ""}
                fill
                sizes="(max-width: 1024px) 100vw, 50vw"
                className="object-cover"
              />
            ) : (
              <div className="size-full bg-navy" aria-hidden="true" />
            )}
          </div>
          {section?.content.locationTitle && (
            <div className="absolute bottom-4 right-4 max-w-[min(220px,calc(100%-2rem))] rounded-md bg-gold p-5 text-navy-dark shadow-lg">
              <p className="font-display text-2xl font-bold md:text-3xl">{section.content.locationTitle}</p>
              {section.content.locationText && <p className="mt-1 text-xs font-bold leading-5">{section.content.locationText}</p>}
            </div>
          )}
        </AnimateOnScroll>
        <AnimateOnScroll delay={0.1}>
          <SectionHeader
            eyebrow={section?.eyebrow ?? ""}
            title={section?.title ?? ""}
            description={section?.description ?? undefined}
          />
          {features.length > 0 && (
            <div className="mt-7 grid gap-5 sm:grid-cols-2">
              {features.map((feature) => {
                const Icon = iconMap[feature.icon as keyof typeof iconMap] ?? BadgeCheck;
                return (
                  <div key={feature.title} className="border-l-2 border-gold pl-4">
                    <Icon className="text-navy" aria-hidden="true" />
                    <h3 className="mt-3 font-bold text-navy">{feature.title}</h3>
                    <p className="mt-2 text-sm leading-6 text-muted">{feature.text}</p>
                  </div>
                );
              })}
            </div>
          )}
          {section?.cta.url && section.cta.label && (
            <Link href={section.cta.url} className="btn-secondary mt-8">
              {section.cta.label} <ArrowRight size={17} aria-hidden="true" />
            </Link>
          )}
        </AnimateOnScroll>
      </div>
    </section>
  );
}
