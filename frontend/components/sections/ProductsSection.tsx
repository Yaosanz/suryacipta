"use client";

import { useMemo, useState } from "react";
import Link from "next/link";
import { ArrowRight } from "lucide-react";
import ProductCard from "@/components/common/ProductCard";
import SectionHeader from "@/components/common/SectionHeader";
import type { CmsSection } from "@/lib/cms";
import type { Product } from "@/lib/cms-types";

/** Katalog produk ringkas dengan filter tanpa request jaringan tambahan. */
export default function ProductsSection({
  products,
  section,
}: {
  products: Product[];
  section: CmsSection;
}) {
  const [active, setActive] = useState("Semua");
  const categories = useMemo(
    () => ["Semua", ...Array.from(new Set(products.map((item) => item.category)))],
    [products],
  );
  const filtered = useMemo(
    () => (active === "Semua" ? products : products.filter((item) => item.category === active)),
    [active, products],
  );

  return (
    <section className="section-space bg-white">
      <div className="container-site">
        <SectionHeader
          eyebrow={section.eyebrow ?? ""}
          title={section.title}
          description={section.description ?? undefined}
        />
        <div className="mt-8 flex gap-2 overflow-x-auto pb-2" role="tablist" aria-label="Filter produk">
          {categories.map((category) => (
            <button
              key={category}
              type="button"
              role="tab"
              aria-selected={active === category}
              onClick={() => setActive(category)}
              className={`whitespace-nowrap rounded-sm border px-4 py-2 text-sm font-bold ${
                active === category
                  ? "border-navy bg-navy text-white"
                  : "border-line bg-white text-muted hover:border-navy"
              }`}
            >
              {category}
            </button>
          ))}
        </div>
        <div className="mt-7 grid items-stretch gap-6 md:grid-cols-2 lg:grid-cols-3" aria-live="polite">
          {filtered.map((product) => (
            <ProductCard key={product.id} product={product} />
          ))}
        </div>
        {section.cta.url && section.cta.label && (
          <div className="mt-9 text-center">
            <Link href={section.cta.url} className="btn-secondary">
              {section.cta.label} <ArrowRight size={17} aria-hidden="true" />
            </Link>
          </div>
        )}
      </div>
    </section>
  );
}
