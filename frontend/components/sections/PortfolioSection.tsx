import Link from "next/link";
import { ArrowRight } from "lucide-react";
import PortfolioCard from "@/components/common/PortfolioCard";
import SectionHeader from "@/components/common/SectionHeader";
import type { CmsSection } from "@/lib/cms";
import type { PortfolioItem } from "@/lib/cms-types";

/** Cuplikan proyek sebagai bukti cakupan layanan. */
export default function PortfolioSection({
  items,
  section,
}: {
  items: PortfolioItem[];
  section: CmsSection;
}) {
  return (
    <section className="section-space bg-white">
      <div className="container-site">
        <div className="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
          <SectionHeader
            eyebrow={section.eyebrow ?? ""}
            title={section.title}
            description={section.description ?? undefined}
          />
          {section.cta.url && section.cta.label && (
            <Link href={section.cta.url} className="btn-secondary shrink-0">
              {section.cta.label} <ArrowRight size={17} aria-hidden="true" />
            </Link>
          )}
        </div>
        <div className="mt-10 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
          {items.slice(0, 6).map((item) => (
            <PortfolioCard key={item.id} item={item} href="/portofolio" />
          ))}
        </div>
      </div>
    </section>
  );
}
