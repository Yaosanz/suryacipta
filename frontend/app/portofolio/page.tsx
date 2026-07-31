import type { Metadata } from "next";
import PageHero from "@/components/common/PageHero";
import PortfolioGallery from "@/components/sections/PortfolioGallery";
import CTASection from "@/components/sections/CTASection";
import { createPageMetadata } from "@/lib/seo";
import { findCmsSection, getCmsHomeContent } from "@/lib/cms";

export async function generateMetadata(): Promise<Metadata> {
  const content = await getCmsHomeContent();
  const hero = findCmsSection(content, "portfolio", "hero");
  return createPageMetadata(
    hero?.title ?? content.site.companyName,
    hero?.description ?? content.site.description,
    "/portofolio",
  );
}

export default async function PortfolioPage() {
  const content = await getCmsHomeContent();
  const hero = findCmsSection(content, "portfolio", "hero");
  const cta = findCmsSection(content, "home", "cta");
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
        <div className="container-site"><PortfolioGallery projects={content.portfolio} /></div>
      </section>
      {cta && <CTASection site={content.site} section={cta} />}
    </>
  );
}
