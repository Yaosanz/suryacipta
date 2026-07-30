import Image from "next/image";
import Link from "next/link";
import { ArrowUpRight, Check } from "lucide-react";
import type { Product } from "@/lib/constants";

/** Ringkasan produk GRC untuk listing dan homepage. */
export default function ProductCard({ product }: { product: Product }) {
  return (
    <article className="group overflow-hidden rounded-md border border-line bg-white">
      <div className="relative aspect-[4/3] overflow-hidden">
        <Image
          src={product.image}
          alt={`${product.name} produksi Surya Cipta Estetica`}
          fill
          sizes="(max-width: 768px) 100vw, 33vw"
          className="object-cover transition-transform duration-500 group-hover:scale-105"
        />
        <span className="absolute left-4 top-4 rounded-sm bg-navy px-3 py-1 text-xs font-bold text-white">
          {product.category}
        </span>
      </div>
      <div className="p-5">
        <h3 className="font-display text-2xl font-bold text-navy-dark">{product.name}</h3>
        <p className="mt-3 text-sm leading-6 text-muted">{product.description}</p>
        <ul className="mt-4 grid gap-2 text-sm">
          {product.features.map((feature) => (
            <li key={feature} className="flex items-center gap-2">
              <Check size={15} className="text-success" aria-hidden="true" />
              {feature}
            </li>
          ))}
        </ul>
        <Link
          href={`/produk/${product.slug}`}
          className="mt-5 inline-flex items-center gap-2 text-sm font-bold text-navy hover:text-gold"
        >
          Detail produk <ArrowUpRight size={16} aria-hidden="true" />
        </Link>
      </div>
    </article>
  );
}
