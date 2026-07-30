import HeroSection from "@/components/sections/HeroSection";
import StatsSection from "@/components/sections/StatsSection";
import AboutSection from "@/components/sections/AboutSection";
import ProductsSection from "@/components/sections/ProductsSection";
import AdvantagesSection from "@/components/sections/AdvantagesSection";
import ProcessSection from "@/components/sections/ProcessSection";
import PortfolioSection from "@/components/sections/PortfolioSection";
import TestimonialsSection from "@/components/sections/TestimonialsSection";
import CTASection from "@/components/sections/CTASection";
import ContactSection from "@/components/sections/ContactSection";
import { getCmsHomeContent } from "@/lib/cms";

export const revalidate = 60;

export default async function HomePage() {
  const content = await getCmsHomeContent();
  const hero = content.sections.find((section) => section.key === "hero");
  const about = content.sections.find((section) => section.key === "about");
  const cta = content.sections.find((section) => section.key === "cta");

  return (
    <>
      <HeroSection site={content.site} section={hero} />
      <StatsSection stats={content.site.statistics} />
      <AboutSection section={about} />
      <ProductsSection products={content.products} />
      <AdvantagesSection />
      <ProcessSection />
      <PortfolioSection items={content.portfolio} />
      <TestimonialsSection testimonials={content.testimonials} />
      <CTASection site={content.site} section={cta} />
      <ContactSection site={content.site} />
    </>
  );
}
