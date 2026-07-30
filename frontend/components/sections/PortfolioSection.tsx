import Link from "next/link";
import { ArrowRight } from "lucide-react";
import PortfolioCard from "@/components/common/PortfolioCard";
import SectionHeader from "@/components/common/SectionHeader";
import { PORTFOLIO, type PortfolioItem } from "@/lib/constants";

/** Cuplikan proyek sebagai bukti cakupan layanan. */
export default function PortfolioSection({
  items = [...PORTFOLIO],
}: {
  items?: PortfolioItem[];
}) {
  return (
    <section className="section-space bg-white">
      <div className="container-site">
        <div className="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
          <SectionHeader
            eyebrow="Portofolio"
            title="Pekerjaan lintas fungsi dan skala"
            description="Contoh cakupan proyek religi, hospitality, komersial, dan residensial."
          />
          <Link href="/portofolio" className="btn-secondary shrink-0">
            Semua proyek <ArrowRight size={17} aria-hidden="true" />
          </Link>
        </div>
        <div className="mt-10 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
          {items.slice(0, 6).map((item) => (
            <PortfolioCard key={item.id} item={item} />
          ))}
        </div>
      </div>
    </section>
  );
}
