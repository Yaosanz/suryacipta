import SectionHeader from "@/components/common/SectionHeader";
import AnimateOnScroll from "@/components/common/AnimateOnScroll";
import type { CmsSection } from "@/lib/cms";

/** Timeline proses proyek GRC dari konsultasi hingga serah terima. */
export default function ProcessSection({ section }: { section: CmsSection }) {
  const steps = section.content.items ?? [];

  return (
    <section className="section-space bg-navy text-white">
      <div className="container-site">
        <SectionHeader
          eyebrow={section.eyebrow ?? ""}
          title={section.title}
          description={section.description ?? undefined}
        />
        <ol className="mt-12 grid gap-7 lg:grid-cols-5">
          {steps.map((step, index) => (
            <AnimateOnScroll key={`${step.title}-${index}`} delay={Math.min(index * 0.06, 0.25)}>
              <li className="relative border-l border-gold/50 pl-5 lg:border-l-0 lg:border-t lg:pl-0 lg:pt-6">
                <span className="font-display text-3xl font-bold text-gold">
                  {String(index + 1).padStart(2, "0")}
                </span>
                <h3 className="mt-3 font-bold">{step.title}</h3>
                <p className="mt-2 text-sm leading-6 text-white/70">{step.text}</p>
              </li>
            </AnimateOnScroll>
          ))}
        </ol>
      </div>
    </section>
  );
}
