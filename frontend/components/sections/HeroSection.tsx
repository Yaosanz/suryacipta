import Image from "next/image";
import Link from "next/link";
import { ArrowDown, ArrowRight, CheckCircle2, MessageCircle } from "lucide-react";
import type { CmsHomeContent, CmsSection } from "@/lib/cms";

/** Hero berorientasi konversi dengan foto arsitektur sebagai first-viewport signal. */
export default function HeroSection({
  site,
  section,
}: {
  site?: CmsHomeContent["site"];
  section?: CmsSection;
}) {
  const whatsapp = `https://wa.me/${site?.whatsapp}?text=${encodeURIComponent(
    "Halo, saya ingin konsultasi proyek GRC.",
  )}`;
  const primaryUrl = section?.cta.url ?? whatsapp;
  const primaryLabel = section?.cta.label;
  const secondaryUrl = section?.content.secondaryCtaUrl;
  const secondaryLabel = section?.content.secondaryCtaLabel;
  const highlights = section?.content.highlights ?? site?.statistics.slice(0, 3).map((stat) => ({
    title: `${stat.value} ${stat.label}`,
  })) ?? [];

  return (
    <section className="relative min-h-[min(760px,100svh)] overflow-hidden bg-navy-dark text-white">
      {section?.video ? (
        <video
          className="absolute inset-0 size-full object-cover"
          poster={section.image ?? undefined}
          autoPlay
          muted
          loop
          playsInline
          preload="metadata"
          aria-hidden="true"
        >
          <source src={section.video.url} type="video/mp4" />
        </video>
      ) : section?.image ? (
        <Image
          src={section.image}
          alt={section.imageAlt ?? ""}
          fill
          priority
          sizes="100vw"
          className="object-cover"
        />
      ) : null}
      <div className="absolute inset-0 bg-[linear-gradient(90deg,rgba(10,37,50,.94)_0%,rgba(10,37,50,.76)_52%,rgba(10,37,50,.25)_100%)]" />
      <div className="container-site relative flex min-h-[min(760px,100svh)] items-center pb-20 pt-28 md:pb-24 md:pt-32">
        <div className="max-w-3xl">
          {section?.content.badge && (
            <p className="inline-flex max-w-full items-center gap-2 border border-gold/60 bg-navy-dark/65 px-3 py-2 text-xs font-bold text-gold-light backdrop-blur">
              <CheckCircle2 className="shrink-0" size={15} aria-hidden="true" />
              <span>{section.content.badge}</span>
            </p>
          )}
          <h1 className="mt-6 max-w-[18ch] [overflow-wrap:anywhere] font-display text-[clamp(2.45rem,7vw,5.4rem)] font-bold leading-[1.04]">
            {section?.title}
          </h1>
          <p className="mt-6 max-w-2xl text-base leading-8 text-white/80 md:text-lg">
            {section?.description}
          </p>
          <div className="mt-8 flex flex-col gap-3 sm:flex-row">
            {primaryLabel && (
              <a
                href={primaryUrl}
                target={primaryUrl.startsWith("https://") ? "_blank" : undefined}
                rel={primaryUrl.startsWith("https://") ? "noreferrer" : undefined}
                className="btn-whatsapp"
              >
                <MessageCircle size={18} aria-hidden="true" />
                {primaryLabel}
              </a>
            )}
            {secondaryUrl && secondaryLabel && (
              <Link
                href={secondaryUrl}
                className="inline-flex min-h-12 items-center justify-center gap-2 rounded-md border border-white/60 px-5 text-sm font-bold text-white hover:bg-white hover:text-navy"
              >
                {secondaryLabel} <ArrowRight size={17} aria-hidden="true" />
              </Link>
            )}
          </div>
          {highlights.length > 0 && (
            <div className="mt-10 grid max-w-2xl grid-cols-1 gap-3 border-t border-white/20 pt-6 text-sm font-semibold text-white/75 sm:grid-cols-3">
              {highlights.map((highlight) => <span key={highlight.title}>{highlight.title}</span>)}
            </div>
          )}
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
