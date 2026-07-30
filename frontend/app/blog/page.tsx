import type { Metadata } from "next";
import Link from "next/link";
import { ArrowUpRight } from "lucide-react";
import PageHero from "@/components/common/PageHero";
import { createPageMetadata } from "@/lib/seo";
import { getCmsHomeContent } from "@/lib/cms";

export function generateMetadata(): Metadata {
  return createPageMetadata(
    "Artikel GRC",
    "Panduan teknis dan inspirasi penggunaan GRC untuk masjid, fasad, dan proyek konstruksi.",
    "/blog",
  );
}

export const revalidate = 60;

export default async function BlogPage() {
  const content = await getCmsHomeContent();
  return (
    <>
      <PageHero
        eyebrow="Wawasan GRC"
        title="Catatan untuk keputusan proyek yang lebih baik"
        description="Panduan praktis tentang desain, pemilihan produk, produksi, dan pemasangan GRC."
        image="https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=2000&q=85"
      />
      <section className="section-space bg-paper">
        <div className="container-site grid gap-5 md:grid-cols-2">
          {content.posts.map((post) => (
            <article key={post.slug} className="rounded-md border border-line bg-white p-7">
              <time className="text-xs font-bold text-gold" dateTime={post.publishedAt}>
                {new Intl.DateTimeFormat("id-ID", { dateStyle: "long" }).format(new Date(post.publishedAt))}
              </time>
              <h2 className="mt-4 font-display text-2xl font-bold text-navy">{post.title}</h2>
              <p className="mt-3 text-sm leading-7 text-muted">{post.excerpt}</p>
              <Link href={`/blog/${post.slug}`} className="mt-5 inline-flex items-center gap-2 text-sm font-bold text-navy">
                Baca artikel <ArrowUpRight size={16} aria-hidden="true" />
              </Link>
            </article>
          ))}
        </div>
      </section>
    </>
  );
}
