import type { Metadata } from "next";
import Image from "next/image";
import Link from "next/link";
import { ArrowUpRight } from "lucide-react";
import PageHero from "@/components/common/PageHero";
import { createPageMetadata } from "@/lib/seo";
import { findCmsSection, getCmsHomeContent } from "@/lib/cms";

export async function generateMetadata(): Promise<Metadata> {
  const content = await getCmsHomeContent();
  const hero = findCmsSection(content, "blog", "hero");
  return createPageMetadata(
    hero?.title ?? content.site.companyName,
    hero?.description ?? content.site.description,
    "/blog",
  );
}

export default async function BlogPage() {
  const content = await getCmsHomeContent();
  const hero = findCmsSection(content, "blog", "hero");
  return (
    <>
      {hero && (
        <PageHero
          eyebrow={hero.eyebrow}
          title={hero.title}
          description={hero.description}
          image={hero.image}
          imageAlt={hero.imageAlt}
        />
      )}
      <section className="section-space bg-paper">
        <div className="container-site grid gap-5 md:grid-cols-2">
          {content.posts.map((post) => (
            <article key={post.slug} className="overflow-hidden rounded-md border border-line bg-white">
              {post.image && (
                <div className="relative aspect-[16/9]">
                  <Image
                    src={post.image}
                    alt={post.imageAlt ?? post.title}
                    fill
                    sizes="(max-width: 768px) 100vw, 50vw"
                    className="object-cover"
                  />
                </div>
              )}
              <div className="p-7">
                <time className="text-xs font-bold text-gold" dateTime={post.publishedAt}>
                  {new Intl.DateTimeFormat("id-ID", { dateStyle: "long" }).format(new Date(post.publishedAt))}
                </time>
                <h2 className="mt-4 font-display text-2xl font-bold text-navy">{post.title}</h2>
                {post.authorName && <p className="mt-2 text-xs font-semibold text-muted">Oleh {post.authorName}</p>}
                <p className="mt-3 text-sm leading-7 text-muted">{post.excerpt}</p>
                <Link href={`/blog/${post.slug}`} className="mt-5 inline-flex items-center gap-2 text-sm font-bold text-navy">
                  Baca artikel <ArrowUpRight size={16} aria-hidden="true" />
                </Link>
              </div>
            </article>
          ))}
        </div>
      </section>
    </>
  );
}
