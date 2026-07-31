import type { Metadata } from "next";
import PageHero from "@/components/common/PageHero";
import ContactSection from "@/components/sections/ContactSection";
import FaqList from "@/components/common/FaqList";
import SectionHeader from "@/components/common/SectionHeader";
import { createPageMetadata } from "@/lib/seo";
import { findCmsSection, getCmsHomeContent } from "@/lib/cms";

export async function generateMetadata(): Promise<Metadata> {
  const content = await getCmsHomeContent();
  const hero = findCmsSection(content, "contact", "hero");
  return createPageMetadata(
    hero?.title ?? content.site.companyName,
    hero?.description ?? content.site.description,
    "/kontak",
  );
}

export default async function ContactPage() {
  const content = await getCmsHomeContent();
  const hero = findCmsSection(content, "contact", "hero");
  const contact = findCmsSection(content, "contact", "contact");
  const faq = findCmsSection(content, "contact", "faq");

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
      {contact && <ContactSection site={content.site} section={contact} />}
      {faq && (
        <section className="section-space bg-white">
          <div className="container-site">
            <SectionHeader eyebrow={faq.eyebrow ?? ""} title={faq.title} description={faq.description ?? undefined} />
            <div className="mt-7 max-w-4xl"><FaqList items={content.faqs} /></div>
          </div>
        </section>
      )}
    </>
  );
}
