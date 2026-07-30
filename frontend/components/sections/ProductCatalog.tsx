"use client";

import { useMemo, useState } from "react";
import ProductCard from "@/components/common/ProductCard";
import { PRODUCTS, type Product } from "@/lib/constants";

/** Katalog produk lengkap dengan filter kategori. */
export default function ProductCatalog({ items = [...PRODUCTS] }: { items?: Product[] }) {
  const categories = ["Semua", ...Array.from(new Set(items.map((item) => item.category)))];
  const [active, setActive] = useState("Semua");
  const products = useMemo(
    () => (active === "Semua" ? items : items.filter((item) => item.category === active)),
    [active, items],
  );

  return (
    <>
      <div className="flex gap-2 overflow-x-auto pb-2" role="tablist" aria-label="Filter katalog">
        {categories.map((category) => (
          <button
            key={category}
            role="tab"
            aria-selected={active === category}
            onClick={() => setActive(category)}
            className={`whitespace-nowrap rounded-sm border px-4 py-2 text-sm font-bold ${
              active === category ? "border-navy bg-navy text-white" : "border-line bg-white text-muted"
            }`}
          >
            {category}
          </button>
        ))}
      </div>
      <div className="mt-7 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        {products.map((product) => <ProductCard key={product.id} product={product} />)}
      </div>
    </>
  );
}
