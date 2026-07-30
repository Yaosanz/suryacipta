import type { Metadata } from "next";
import Link from "next/link";
import { notFound } from "next/navigation";
import CTASection from "@/components/sections/CTASection";
import { BLOG_POSTS } from "@/lib/constants";
import { getCmsPost } from "@/lib/cms";
import { createPageMetadata } from "@/lib/seo";

type BlogPostProps = { params: Promise<{ slug: string }> };

export function generateStaticParams() {
  return BLOG_POSTS.map((post) => ({ slug: post.slug }));
}

export async function generateMetadata({ params }: BlogPostProps): Promise<Metadata> {
  const { slug } = await params;
  const post = await getCmsPost(slug);
  return post ? createPageMetadata(post.title, post.excerpt, `/blog/${post.slug}`) : {};
}

export default async function BlogPostPage({ params }: BlogPostProps) {
  const { slug } = await params;
  const post = await getCmsPost(slug);
  if (!post) notFound();

  return (
    <>
      <article className="bg-paper pb-20 pt-32">
        <header className="container-site max-w-4xl">
          <Link href="/blog" className="eyebrow">Artikel GRC</Link>
          <h1 className="mt-5 font-display text-4xl font-bold leading-tight text-navy md:text-6xl">{post.title}</h1>
          <p className="mt-5 text-lg leading-8 text-muted">{post.excerpt}</p>
        </header>
        <div className="container-site mt-12 max-w-4xl">
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
      <CTASection />
    </>
  );
}
