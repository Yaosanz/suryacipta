import type { Metadata } from "next";
import PageHero from "@/components/common/PageHero";
import SectionHeader from "@/components/common/SectionHeader";
import FaqList from "@/components/common/FaqList";
import ProductCatalog from "@/components/sections/ProductCatalog";
import Estimator from "@/components/sections/Estimator";
import CTASection from "@/components/sections/CTASection";
import { createPageMetadata } from "@/lib/seo";
import { findCmsSection, getCmsHomeContent } from "@/lib/cms";

export async function generateMetadata(): Promise<Metadata> {
  const content = await getCmsHomeContent();
  const hero = findCmsSection(content, "products", "hero");
  return createPageMetadata(
    hero?.title ?? content.site.companyName,
    hero?.description ?? content.site.description,
    "/produk",
  );
}

export default async function ProductsPage() {
  const content = await getCmsHomeContent();
  const hero = findCmsSection(content, "products", "hero");
  const support = findCmsSection(content, "products", "support");
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
        <div className="container-site">
          <ProductCatalog items={content.products} />
        </div>
      </section>
      {support && (
        <section className="section-space bg-white">
          <div className="container-site grid gap-10 lg:grid-cols-2">
            {support.content.estimator && <Estimator config={support.content.estimator} />}
            <div>
              <SectionHeader eyebrow={support.eyebrow ?? ""} title={support.title} />
              <div className="mt-6"><FaqList items={content.faqs} /></div>
            </div>
          </div>
        </section>
      )}
      {cta && <CTASection site={content.site} section={cta} />}
    </>
  );
}
