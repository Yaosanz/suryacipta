import type { Metadata } from "next";
import Image from "next/image";
import Link from "next/link";
import { Check, MessageCircle } from "lucide-react";
import { notFound } from "next/navigation";
import MediaPlaceholder from "@/components/common/MediaPlaceholder";
import { findCmsSection, getCmsHomeContent, getCmsProduct } from "@/lib/cms";
import { breadcrumbSchema, productSchema, serializeJsonLd } from "@/lib/structured-data";
import { createPageMetadata } from "@/lib/seo";

type ProductPageProps = { params: Promise<{ slug: string }> };

export async function generateMetadata({ params }: ProductPageProps): Promise<Metadata> {
  const { slug } = await params;
  const product = await getCmsProduct(slug);
  if (!product) return {};
  return createPageMetadata(
    product.seo?.title ?? product.name,
    product.seo?.description ?? product.description,
    `/produk/${product.slug}`,
  );
}

export default async function ProductDetailPage({ params }: ProductPageProps) {
  const { slug } = await params;
  const [product, content] = await Promise.all([getCmsProduct(slug), getCmsHomeContent()]);
  if (!product) notFound();
  const detail = findCmsSection(content, "products", "detail");

  const schemas = [
    productSchema(product, content.site.companyName),
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
          <p className="mt-5 max-w-2xl leading-8 text-white/70">
            {product.longDescription ?? product.description}
          </p>
        </div>
      </section>
      <section className="section-space bg-paper">
        <div className="container-site grid gap-10 lg:grid-cols-[1.1fr_.9fr]">
          <div className="grid gap-5">
            <div className="relative aspect-[4/3] overflow-hidden rounded-md">
              {product.image ? (
                <Image
                  src={product.image}
                  alt={product.imageAlt ?? product.name}
                  fill
                  priority
                  className="object-cover"
                />
              ) : (
                <MediaPlaceholder label={`Foto ${product.name} belum tersedia`} />
              )}
            </div>
            {product.video && (
              <div className="aspect-video overflow-hidden rounded-md bg-navy-dark">
                <video
                  className="size-full object-contain"
                  poster={product.image ?? undefined}
                  controls
                  playsInline
                  preload="metadata"
                  aria-label={product.video.alt ?? `Video ${product.name}`}
                >
                  <source src={product.video.url} type="video/mp4" />
                </video>
              </div>
            )}
          </div>
          <div>
            {detail?.eyebrow && <p className="eyebrow">{detail.eyebrow}</p>}
            {detail && (
              <>
                <h2 className="mt-3 font-display text-3xl font-bold text-navy">{detail.title}</h2>
                {detail.description && (
                  <p className="mt-5 leading-7 text-muted">{detail.description}</p>
                )}
              </>
            )}
            <ul className="mt-6 grid gap-3">
              {product.features.map((feature) => (
                <li key={feature} className="flex items-center gap-3 font-semibold">
                  <Check className="text-success" size={18} aria-hidden="true" /> {feature}
                </li>
              ))}
            </ul>
            {detail?.cta.label && (
              <a
                href={`https://wa.me/${content.site.whatsapp}?text=${encodeURIComponent(`Halo, saya ingin meminta penawaran ${product.name}.`)}`}
                className="btn-whatsapp mt-8"
                target="_blank"
                rel="noreferrer"
              >
                <MessageCircle size={18} aria-hidden="true" /> {detail.cta.label}
              </a>
            )}
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
