import HeroSection from "@/components/sections/HeroSection";
import StatsSection from "@/components/sections/StatsSection";
import AboutSection from "@/components/sections/AboutSection";
import ProductsSection from "@/components/sections/ProductsSection";
import AdvantagesSection from "@/components/sections/AdvantagesSection";
import ProcessSection from "@/components/sections/ProcessSection";
import PortfolioSection from "@/components/sections/PortfolioSection";
import TestimonialsSection from "@/components/sections/TestimonialsSection";
import TeamSection from "@/components/sections/TeamSection";
import CTASection from "@/components/sections/CTASection";
import ContactSection from "@/components/sections/ContactSection";
import { findCmsSection, getCmsHomeContent } from "@/lib/cms";

export default async function HomePage() {
  const content = await getCmsHomeContent();
  const hero = findCmsSection(content, "home", "hero");
  const about = findCmsSection(content, "home", "about");
  const products = findCmsSection(content, "home", "products");
  const advantages = findCmsSection(content, "home", "advantages");
  const process = findCmsSection(content, "home", "process");
  const portfolio = findCmsSection(content, "home", "portfolio");
  const testimonials = findCmsSection(content, "home", "testimonials");
  const team = findCmsSection(content, "home", "team");
  const cta = findCmsSection(content, "home", "cta");
  const contact = findCmsSection(content, "home", "contact");

  return (
    <>
      {hero && <HeroSection site={content.site} section={hero} />}
      <StatsSection stats={content.site.statistics} />
      {about && <AboutSection section={about} />}
      {products && <ProductsSection products={content.products} section={products} />}
      {advantages && <AdvantagesSection section={advantages} />}
      {process && <ProcessSection section={process} />}
      {portfolio && <PortfolioSection items={content.portfolio} section={portfolio} />}
      {testimonials && content.testimonials.length > 0 && (
        <TestimonialsSection testimonials={content.testimonials} section={testimonials} />
      )}
      {team && <TeamSection members={content.team} section={team} />}
      {cta && <CTASection site={content.site} section={cta} />}
      {contact && <ContactSection site={content.site} section={contact} />}
    </>
  );
}
