"use client";

import { useMemo, useState } from "react";
import Link from "next/link";
import { ArrowRight } from "lucide-react";
import ProductCard from "@/components/common/ProductCard";
import SectionHeader from "@/components/common/SectionHeader";
import { PRODUCTS, type Product } from "@/lib/constants";

/** Katalog produk ringkas dengan filter tanpa request jaringan tambahan. */
export default function ProductsSection({
  products = [...PRODUCTS],
}: {
  products?: Product[];
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
          eyebrow="Produk GRC"
          title="Dibuat presisi untuk desain yang berbeda"
          description="Pilih produk standar atau konsultasikan bentuk custom untuk kebutuhan arsitektur, kontraktor, dan pemilik bangunan."
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
        <div className="mt-7 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          {filtered.map((product) => (
            <ProductCard key={product.id} product={product} />
          ))}
        </div>
        <div className="mt-9 text-center">
          <Link href="/produk" className="btn-secondary">
            Lihat semua produk <ArrowRight size={17} aria-hidden="true" />
          </Link>
        </div>
      </div>
    </section>
  );
}
