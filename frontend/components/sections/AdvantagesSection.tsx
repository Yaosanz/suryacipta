import {
  BadgeCheck,
  CloudSun,
  Clock3,
  Factory,
  Palette,
  Truck,
} from "lucide-react";
import SectionHeader from "@/components/common/SectionHeader";

import AnimateOnScroll from "@/components/common/AnimateOnScroll";
import type { CmsSection } from "@/lib/cms";

/** Keunggulan operasional yang relevan bagi pemilik proyek dan kontraktor. */
export default function AdvantagesSection({ section }: { section: CmsSection }) {
  const iconMap = {
    factory: Factory,
    palette: Palette,
    clock: Clock3,
    weather: CloudSun,
    delivery: Truck,
    quality: BadgeCheck,
  };
  const advantages = section.content.items ?? [];

  return (
    <section className="section-space subtle-grid bg-paper">
      <div className="container-site">
        <SectionHeader
          eyebrow={section.eyebrow ?? ""}
          title={section.title}
          description={section.description ?? undefined}
          align="center"
        />
        <div className="mt-12 grid gap-px overflow-hidden rounded-md border border-line bg-line md:grid-cols-2 lg:grid-cols-3">
          {advantages.map((item, index) => {
            const Icon = iconMap[item.icon as keyof typeof iconMap] ?? BadgeCheck;
            return (
              <AnimateOnScroll key={item.title} delay={Math.min(index * 0.05, 0.25)} className="h-full bg-paper">
                <article className="h-full p-7 transition-colors duration-200 hover:bg-white">
                  <Icon className="text-gold" size={30} aria-hidden="true" />
                  <h3 className="mt-5 font-display text-xl font-bold text-navy-dark">{item.title}</h3>
                  <p className="mt-2 text-sm leading-6 text-muted">{item.text}</p>
                </article>
              </AnimateOnScroll>
            );
          })}
        </div>
      </div>
    </section>
  );
}
