import { MessageCircle, Phone } from "lucide-react";
import type { CmsHomeContent, CmsSection } from "@/lib/cms";

/** CTA utama sebelum formulir untuk jalur konversi cepat. */
export default function CTASection({
  site,
  section,
}: {
  site?: CmsHomeContent["site"];
  section?: CmsSection;
}) {
  const whatsapp = site?.whatsapp ?? "";
  const phone = site?.phone ?? "";
  const primaryUrl = section?.cta.url ?? `https://wa.me/${whatsapp}`;
  const secondaryUrl = section?.content.secondaryCtaUrl ?? `tel:+${whatsapp}`;
  return (
    <section className="bg-gold py-14 text-navy-dark">
      <div className="container-site flex flex-col gap-7 lg:flex-row lg:items-center lg:justify-between">
        <div>
          {section?.eyebrow && <p className="text-xs font-extrabold uppercase">{section.eyebrow}</p>}
          <h2 className="mt-2 font-display text-3xl font-bold md:text-4xl">
            {section?.title}
          </h2>
          <p className="mt-3 max-w-2xl text-sm leading-6">
            {section?.description}
          </p>
        </div>
        <div className="flex flex-col gap-3 sm:flex-row">
          <a
            href={primaryUrl}
            target="_blank"
            rel="noreferrer"
            className="btn-whatsapp"
          >
            <MessageCircle size={18} aria-hidden="true" /> {section?.cta.label ?? "WhatsApp"}
          </a>
          <a href={secondaryUrl} className="btn-secondary border-navy-dark text-navy-dark">
            <Phone size={18} aria-hidden="true" /> {section?.content.secondaryCtaLabel ?? phone}
          </a>
        </div>
      </div>
    </section>
  );
}
