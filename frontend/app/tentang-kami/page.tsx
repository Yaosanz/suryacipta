import type { Metadata } from "next";
import Image from "next/image";
import { BadgeCheck, Handshake, Lightbulb, ShieldCheck } from "lucide-react";
import PageHero from "@/components/common/PageHero";
import SectionHeader from "@/components/common/SectionHeader";
import CTASection from "@/components/sections/CTASection";
import TeamSection from "@/components/sections/TeamSection";
import MediaPlaceholder from "@/components/common/MediaPlaceholder";
import { createPageMetadata } from "@/lib/seo";
import { findCmsSection, getCmsHomeContent } from "@/lib/cms";

export async function generateMetadata(): Promise<Metadata> {
  const content = await getCmsHomeContent();
  const hero = findCmsSection(content, "about", "hero");
  return createPageMetadata(
    hero?.title ?? content.site.companyName,
    hero?.description ?? content.site.description,
    "/tentang-kami",
  );
}

export default async function AboutPage() {
  const content = await getCmsHomeContent();
  const hero = findCmsSection(content, "about", "hero");
  const story = findCmsSection(content, "about", "story");
  const values = findCmsSection(content, "about", "values");
  const team = findCmsSection(content, "about", "team");
  const cta = findCmsSection(content, "home", "cta");
  const valueIcons = {
    quality: ShieldCheck,
    collaboration: Handshake,
    solution: Lightbulb,
    responsibility: BadgeCheck,
  };

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
      {story && (
        <section className="section-space bg-paper">
          <div className="container-site grid gap-12 lg:grid-cols-2">
            <div>
              <SectionHeader
                eyebrow={story.eyebrow ?? ""}
                title={story.title}
                description={story.description ?? undefined}
              />
              {story.content.secondaryDescription && (
                <p className="mt-6 leading-8 text-muted">{story.content.secondaryDescription}</p>
              )}
            </div>
            <div className="relative aspect-[5/4] overflow-hidden rounded-md">
              {story.image ? (
                <Image
                  src={story.image}
                  alt={story.imageAlt ?? story.title}
                  fill
                  sizes="(max-width: 1024px) 100vw, 50vw"
                  className="object-cover"
                />
              ) : (
                <MediaPlaceholder label="Foto perjalanan perusahaan belum tersedia" />
              )}
            </div>
          </div>
        </section>
      )}
      {values && (
        <section className="section-space bg-white">
          <div className="container-site">
            <SectionHeader
              eyebrow={values.eyebrow ?? ""}
              title={values.title}
              description={values.description ?? undefined}
              align="center"
            />
            <div className="mt-10 grid gap-6 md:grid-cols-2">
              <article className="border-l-4 border-gold bg-paper p-7">
                <h2 className="font-display text-2xl font-bold text-navy">Visi</h2>
                <p className="mt-3 leading-7 text-muted">{values.content.vision}</p>
              </article>
              <article className="border-l-4 border-navy bg-paper p-7">
                <h2 className="font-display text-2xl font-bold text-navy">Misi</h2>
                <p className="mt-3 leading-7 text-muted">{values.content.mission}</p>
              </article>
            </div>
            <div className="mt-6 grid gap-px overflow-hidden rounded-md border border-line bg-line md:grid-cols-2 lg:grid-cols-4">
              {(values.content.items ?? []).map((item) => {
                const Icon = valueIcons[item.icon as keyof typeof valueIcons] ?? BadgeCheck;
                return (
                  <article key={item.title} className="bg-white p-6">
                    <Icon className="text-gold" aria-hidden="true" />
                    <h3 className="mt-4 font-bold text-navy">{item.title}</h3>
                    <p className="mt-2 text-sm leading-6 text-muted">{item.text}</p>
                  </article>
                );
              })}
            </div>
          </div>
        </section>
      )}
      <TeamSection members={content.team} section={team} />
      {cta && <CTASection site={content.site} section={cta} />}
    </>
  );
}
