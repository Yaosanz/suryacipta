import Image from "next/image";
import Link from "next/link";
import { ExternalLink, UserRound } from "lucide-react";
import SectionHeader from "@/components/common/SectionHeader";
import type { CmsSection, CmsTeamMember } from "@/lib/cms";

type TeamSectionProps = {
  members: CmsTeamMember[];
  section?: CmsSection;
};

export default function TeamSection({ members, section }: TeamSectionProps) {
  if (members.length === 0 || !section) return null;

  return (
    <section className="section-space bg-paper" id="tim">
      <div className="container-site">
        <div className="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
          <SectionHeader
            eyebrow={section.eyebrow ?? ""}
            title={section.title}
            description={section.description ?? undefined}
          />
          {section.cta.url && section.cta.label && (
            <Link href={section.cta.url} className="btn-secondary shrink-0">
              {section.cta.label}
            </Link>
          )}
        </div>

        <div className="mt-10 grid gap-px overflow-hidden rounded-md border border-line bg-line sm:grid-cols-2 xl:grid-cols-4">
          {members.map((member) => (
            <article key={`${member.name}-${member.role}`} className="group bg-white">
              <div className="relative aspect-[4/3] overflow-hidden bg-navy">
                {member.photo ? (
                  <Image
                    src={member.photo}
                    alt={member.photoAlt ?? `${member.name}, ${member.role}`}
                    fill
                    sizes="(min-width: 1280px) 25vw, (min-width: 640px) 50vw, 100vw"
                    className="object-cover transition-transform duration-300 group-hover:scale-[1.02]"
                  />
                ) : (
                  <div className="flex h-full items-center justify-center bg-navy text-gold-light">
                    <UserRound size={52} strokeWidth={1.25} aria-hidden="true" />
                  </div>
                )}
              </div>
              <div className="p-6">
                <div className="flex items-start justify-between gap-3">
                  <div>
                    <h3 className="font-display text-xl font-bold text-navy">{member.name}</h3>
                    <p className="mt-1 text-xs font-bold uppercase text-gold">{member.role}</p>
                  </div>
                  {member.linkedinUrl && (
                    <a
                      href={member.linkedinUrl}
                      target="_blank"
                      rel="noopener noreferrer"
                      aria-label={`LinkedIn ${member.name}`}
                      className="text-muted transition-colors hover:text-navy"
                    >
                      <ExternalLink size={20} aria-hidden="true" />
                    </a>
                  )}
                </div>
                {member.bio && <p className="mt-4 text-sm leading-6 text-muted">{member.bio}</p>}
              </div>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}
