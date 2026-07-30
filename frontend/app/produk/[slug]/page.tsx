import type { Metadata } from "next";
import Image from "next/image";
import Link from "next/link";
import { Check, MessageCircle } from "lucide-react";
import { notFound } from "next/navigation";
import { PRODUCTS, COMPANY } from "@/lib/constants";
import { getCmsProduct } from "@/lib/cms";
import { breadcrumbSchema, productSchema, serializeJsonLd } from "@/lib/structured-data";
import { createPageMetadata } from "@/lib/seo";

type ProductPageProps = { params: Promise<{ slug: string }> };

export function generateStaticParams() {
  return PRODUCTS.map((product) => ({ slug: product.slug }));
}

export async function generateMetadata({ params }: ProductPageProps): Promise<Metadata> {
  const { slug } = await params;
  const product = await getCmsProduct(slug);
  if (!product) return {};
  return createPageMetadata(product.name, product.description, `/produk/${product.slug}`);
}

export default async function ProductDetailPage({ params }: ProductPageProps) {
  const { slug } = await params;
  const product = await getCmsProduct(slug);
  if (!product) notFound();

  const schemas = [
    productSchema(product),
    breadcrumbSchema([
      { name: "Beranda", path: "/" },
      { name: "Produk", path: "/produk" },
      { name: product.name, path: `/produk/${product.slug}` },
    ]),
  ];

  return (
    <>
      <section className="bg-navy-dark pb-14 pt-32 text-white">
        <div className="container-site">
          <Link href="/produk" className="text-sm font-bold text-gold-light">Produk / {product.category}</Link>
          <h1 className="mt-4 max-w-4xl font-display text-4xl font-bold md:text-6xl">{product.name}</h1>
          <p className="mt-5 max-w-2xl leading-8 text-white/70">{product.description}</p>
        </div>
      </section>
      <section className="section-space bg-paper">
        <div className="container-site grid gap-10 lg:grid-cols-[1.1fr_.9fr]">
          <div className="relative aspect-[4/3] overflow-hidden rounded-md">
            <Image src={product.image} alt={product.name} fill priority className="object-cover" />
          </div>
          <div>
            <p className="eyebrow">Spesifikasi Fleksibel</p>
            <h2 className="mt-3 font-display text-3xl font-bold text-navy">Dibuat sesuai kebutuhan proyek</h2>
            <p className="mt-5 leading-7 text-muted">
              Ukuran, ketebalan, detail cetakan, sistem rangka, warna, dan metode instalasi
              ditentukan setelah tinjauan gambar dan kondisi lapangan.
            </p>
            <ul className="mt-6 grid gap-3">
              {product.features.map((feature) => (
                <li key={feature} className="flex items-center gap-3 font-semibold">
                  <Check className="text-success" size={18} aria-hidden="true" /> {feature}
                </li>
              ))}
            </ul>
            <a
              href={`https://wa.me/${COMPANY.whatsapp}?text=${encodeURIComponent(`Halo, saya ingin meminta penawaran ${product.name}.`)}`}
              className="btn-whatsapp mt-8"
              target="_blank"
              rel="noreferrer"
            >
              <MessageCircle size={18} aria-hidden="true" /> Minta Penawaran
            </a>
          </div>
        </div>
      </section>
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: serializeJsonLd(schemas) }}
      />
    </>
  );
}
