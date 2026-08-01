import type { Metadata } from "next";
import Image from "next/image";
import Link from "next/link";
import { notFound } from "next/navigation";
import CTASection from "@/components/sections/CTASection";
import { findCmsSection, getCmsHomeContent, getCmsPost } from "@/lib/cms";
import { createPageMetadata } from "@/lib/seo";

type BlogPostProps = { params: Promise<{ slug: string }> };

export async function generateMetadata({ params }: BlogPostProps): Promise<Metadata> {
  const { slug } = await params;
  const post = await getCmsPost(slug);
  return post
    ? createPageMetadata(
        post.seo?.title || post.title,
        post.seo?.description || post.excerpt,
        `/blog/${post.slug}`,
      )
    : {};
}

export default async function BlogPostPage({ params }: BlogPostProps) {
  const { slug } = await params;
  const [post, content] = await Promise.all([getCmsPost(slug), getCmsHomeContent()]);
  if (!post) notFound();
  const blogHero = findCmsSection(content, "blog", "hero");
  const cta = findCmsSection(content, "home", "cta");

  return (
    <>
      <article className="bg-paper pb-20 pt-32">
        <header className="container-site max-w-4xl">
          <Link href="/blog" className="eyebrow">{blogHero?.eyebrow}</Link>
          <h1 className="mt-5 font-display text-4xl font-bold leading-tight text-navy md:text-6xl">{post.title}</h1>
          <p className="mt-5 text-lg leading-8 text-muted">{post.excerpt}</p>
          {post.authorName && <p className="mt-4 text-sm font-semibold text-muted">Oleh {post.authorName}</p>}
        </header>
        <div className="container-site mt-12 max-w-4xl">
          {post.image && (
            <div className="relative mb-10 aspect-[16/9] overflow-hidden rounded-md">
              <Image
                src={post.image}
                alt={post.imageAlt ?? post.title}
                fill
                sizes="(max-width: 896px) 100vw, 896px"
                className="object-cover"
                priority
              />
            </div>
          )}
          {post.video && (
            <div className="mb-10 aspect-video overflow-hidden rounded-md bg-navy-dark">
              <video
                className="size-full object-contain"
                poster={post.image ?? undefined}
                controls
                playsInline
                preload="metadata"
                aria-label={post.video.alt ?? `Video artikel ${post.title}`}
              >
                <source src={post.video.url} type="video/mp4" />
              </video>
            </div>
          )}
          {post.contentHtml ? (
            <div
              className="prose prose-slate max-w-none border-t border-line pt-9"
              dangerouslySetInnerHTML={{ __html: post.contentHtml }}
            />
          ) : (
            <div className="border-t border-line pt-9 text-base leading-8 text-muted">
              <p>{post.excerpt}</p>
            </div>
          )}
        </div>
      </article>
      {cta && <CTASection site={content.site} section={cta} />}
    </>
  );
}
